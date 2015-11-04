<?php
/**
 * @package   AkeebaTicketSystem
 * @copyright Copyright (c)2011-2015 Nicholas K. Dionysopoulos
 * @license   GNU General Public License version 3, or later
 */

namespace Akeeba\TicketSystem\Admin\Model;

use Akeeba\TicketSystem\Admin\Helper\Bbcode;
use Akeeba\TicketSystem\Admin\Helper\ComponentParams;
use Akeeba\TicketSystem\Admin\Helper\Credits;
use Akeeba\TicketSystem\Admin\Helper\Filter;
use FOF30\Container\Container;
use JDate;
use JText;

defined('_JEXEC') or die;

/**
 * @property    int             ats_post_id         Primary key
 * @property    int[]|string    ats_attachment_id   Imploded array of attachments linked to the post
 * @property    int             ats_ticket_id       Ticket containing the post
 * @property    string          content             Raw bbcode content
 * @property    string          content_html        HTML content of the post
 * @property    string          origin              Origin of the ticket (web|email)
 * @property    int             timespent           Time spent on the post
 *
 * @property-read Tickets       ticket     Relation to the ticket
 */
class Posts extends DefaultDataModel
{
    /** @var Attachments[]  */
    public $attachments = array();

    /** @var  Tickets */
    protected $_ticket;
    /** @var  int */
    protected $_ticket_id;

    public function __construct(Container $container, array $config = array())
    {
        // Force the name of the table and the primary key. This is required because we are going to reuse this
        // model in views with a different name
        $config['tableName']   = '#__ats_posts';
        $config['idFieldName'] = 'ats_post_id';

        parent::__construct($container, $config);

        $this->addBehaviour('Filters');
        $this->belongsTo('ticket', 'Tickets', 'ats_ticket_id', 'ats_ticket_id');

        // TODO add custom checks
        $this->autoChecks = false;
    }

    public function onBeforeBind(&$from)
    {
        // Let's make sure that the incoming array has all the keys - Maybe it's just an overkill?
        $from = (array)$from;

        if(array_key_exists('content', $from) && !array_key_exists('content_html', $from))
        {
            $from['content_html'] = '';
        }
        elseif(!array_key_exists('content', $from) && array_key_exists('content_html', $from))
        {
            $from['content'] = '';
        }
    }

    public function check()
    {
        parent::check();

        // Do we have a valid ticket?
        $this->assertNotEmpty($this->ats_ticket_id, JText::_('COM_ATS_POSTS_ERR_NOTICKET'));

        $ticket = $this->ticket;

        $this->assert($ticket->ats_ticket_id == $this->ats_ticket_id, JText::_('COM_ATS_POSTS_ERR_INVALIDTICKET'));

        $this->_ticket = clone $ticket;

        // Process the content
        $content = htmlentities($this->content, ENT_NOQUOTES, 'UTF-8', false);
        $content = str_replace('<', '&lt;', $content);
        $content = str_replace('>', '&gt;', $content);

        if(!empty($content))
        {
            $this->content_html = Bbcode::parseBBCode($content);
        }
        else
        {
            $this->content_html = Filter::filterText($this->content_html);
        }
    }

    protected function onBeforeCreate($dataObject)
    {
        $this->onSaving($dataObject);
    }

    protected function onBeforeUpdate($dataObject)
    {
        $this->onSaving($dataObject);
    }

    protected function onAfterSave()
    {
        if(!is_null($this->_ticket_id))
        {
            // Charge credits for the reply
            if($this->created_by == $this->_ticket->created_by)
            {
                // If the post is within 10 seconds of the ticket, it's the
                // first post of a ticket and must not be charged.
                \JLoader::import('joomla.utilities.date');

                $params  = \JComponentHelper::getParams('com_ats');
                $jTicket = new JDate($this->_ticket->created_on);
                $jPost   = new JDate($this->created_on);

                if($params->get('showcredits', 0) && abs($jTicket->toUnix() - $jPost->toUnix()) > 10)
                {
                    Credits::chargeCredits($this->created_by, $this->_ticket->catid, $this->_ticket->ats_ticket_id, $this->ats_post_id, false, $this->_ticket->public);
                }
            }

            // Update the existing ticket
            if($this->_ticket->status != 'C')
            {
                // Get the total amount of tracked item for all posts of this ticket
                $db = $this->getDbo();

                $query = $db->getQuery(true)
                            ->select('SUM(' . $db->qn('timespent') . ')')
                            ->from($db->qn('#__ats_posts'))
                            ->where($db->qn('ats_ticket_id') . ' = ' . $db->q($this->_ticket_id))
                            ->where($db->qn('enabled') . ' = ' . $db->q('1'));
                $timespent = $db->setQuery($query)->loadResult();

                $ticket = $this->_ticket;
                $ticket::$overrideuser = 1;

                if($this->created_by == $this->_ticket->created_by)
                {
                    // The user replied -- open
                    $this->_ticket->save(array(
                        'status'	=> 'O',
                        'timespent'	=> $timespent,
                    ));
                }
                else
                {
                    // A manager replied -- pending
                    $this->_ticket->save(array(
                        'status'	=> 'P',
                        'timespent'	=> $timespent,
                    ));
                }

                $ticket::$overrideuser = 0;
            }

            // New post, call plugins (e.g. email)
            $this->postNotifiable(true);
        }
        else
        {
            // Existing post is modified, call plugins (e.g. email)
            $this->postNotifiable(false);
        }
    }

    protected function onAfterGetItemsArray($items)
    {
        /** @var \Akeeba\TicketSystem\Admin\Model\Attachments $attachModel */
        $attachModel = $this->container->factory->model('Attachments')->tmpInstance();

        foreach($items as $item)
        {
            $attachments = array();

            if($item->ats_attachment_id)
            {
                $attachments = $attachModel->ids_search($item->ats_attachment_id)->get(true);
            }

            $item->attachments = $attachments;
        }
    }

    protected function onAfterLoad($success, $keys)
    {
        if(!$success)
        {
            return;
        }

        if($this->ats_attachment_id)
        {
            /** @var \Akeeba\TicketSystem\Admin\Model\Attachments $attachModel */
            $attachModel = $this->container->factory->model('Attachments')->tmpInstance();
            $attachments = $attachModel->ids_search($this->ats_attachment_id)->get(true);

            $this->attachments = $attachments;
        }
    }

    protected function onBeforeDelete($oid)
    {
        // Delete any attachments
        if($this->ats_attachment_id)
        {
            foreach($this->attachments as $attachment)
            {
                $attachment->delete();
            }
        }

        // Refund credits
        $ticket = $this->ticket;

        Credits::refundCredits($ticket->catid, $ticket->ats_ticket_id, $this->ats_post_id, 'post');
    }

    /**
     * While publishing a post, we have to charge the user if we are using the credit system
     */
    protected function onBeforePublish()
    {
        // Apply credit logic only if we enabled that feature
        if(!ComponentParams::getParam('showcredits', 0))
        {
            return;
        }

        $ticket = $this->ticket;

        // Charge credits on post publish
        Credits::chargeCredits($this->created_by, $ticket->catid, $ticket->ats_ticket_id, $this->ats_post_id, false, $ticket->public);
    }

    /**
     * While unpublishing a post, we have to refund the user if we are using the credit system
     */
    protected function onBeforeUnpublish()
    {
        // Apply credit logic only if we enabled that feature
        if(!ComponentParams::getParam('showcredits', 0))
        {
            return;
        }

        $ticket = $this->ticket;

        // Refund credits on post unpublish
        Credits::refundCredits($ticket->catid, $ticket->ats_ticket_id, $this->ats_post_id, 'post');
    }

    protected function setAtsAttachmentIdAttribute($value)
    {
        return $this->setAttributeForImplodedArray($value);
    }

    protected function getAtsAttachmentIdAttribute($value)
    {
        return $this->getAttributeForImplodedArray($value);
    }

    /**
     * Single function to hook up on saving events (create/update), since using the event onBeforeSave is not useful. It
     * fires too soon, before data is binded, leading to wrong results.
     *
     * @param $dataObject
     */
    private function onSaving($dataObject)
    {
        // Special handling of created_on/_by and modified_on/_by columns, because tickets and posts filed from the web
        // and processed through the plugin may otherwise get the wrong user ID.
        $uid      = $this->container->platform->getUser()->id;
        $nullDate = $this->getDbo()->getNullDate();
        $nowDate  = $this->container->platform->getDate('now', null, false);

        if (empty($this->created_by) || ($this->created_on == $nullDate) || empty($this->created_on))
        {
            if (empty($this->created_by) && $uid)
            {
                $this->created_by = $uid;
            }

            $this->created_on = $nowDate->toSql();
        }
        else
        {
            if (empty($this->modified_by) && $uid)
            {
                $this->modified_by = $uid;
            }

            $this->modified_on = $nowDate->toSql();
        }

        $dataObject->created_by  = $this->created_by;
        $dataObject->created_on  = $this->created_on;
        $dataObject->modified_by = $this->modified_by;
        $dataObject->modified_on = $this->modified_on;

        // Check for new post
        // If it's a new post, save the ticket ID
        if($this->ats_post_id == 0)
        {
            $this->_ticket_id = $this->ats_ticket_id;
        }
        else
        {
            $this->_ticket_id = null;
        }
    }

    private function postNotifiable($newPost = false)
    {
        \JLoader::import('joomla.plugin.helper');
        \JPluginHelper::importPlugin('ats');

        // Reference to the Ticket object
        $ticket = clone $this->ticket;

        $info = array(
            'new'		=> $newPost,
            'post'		=> clone $this,
            'ticket'	=> $ticket,
        );

        // Fire plugins passing ourselves as a parameter
        \JEventDispatcher::getInstance()->trigger('onATSPost', array($info));
    }
}