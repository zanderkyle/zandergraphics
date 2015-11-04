<?php
/**
 * @package   AkeebaTicketSystem
 * @copyright Copyright (c)2011-2015 Nicholas K. Dionysopoulos
 * @license   GNU General Public License version 3, or later
 */

namespace Akeeba\TicketSystem\Admin\Model;

use Akeeba\TicketSystem\Admin\Helper\Permissions;
use FOF30\Container\Container;

defined('_JEXEC') or die;

/**
 * Class AutoReplies
 *
 * @property    string  title   Autoreply title
 * @property    string  reply   Autoreply content
 *
 *
 * @package Akeeba\TicketSystem\Admin\Model
 */
class AutoReplies extends DefaultDataModel
{
    /** @var array Processed tickets stack, in order to prevent double posting */
    protected $processedTickets = array();

    public function __construct(Container $container, array $config = array())
    {
        parent::__construct($container, $config);

        $this->addBehaviour('Filters');

        $this->autoChecks = false;
    }

    public function check()
    {
        $this->assertNotEmpty($this->title, 'COM_ATS_AUTOREPLIES_ERR_TITLE');
        $this->assertNotEmpty($this->reply, 'COM_ATS_AUTOREPLY_ERR_REPLY_EMPTY');

        parent::check();
    }

    protected function onBeforeBind(&$data)
    {
        if(is_array($data) && !isset($data['params']))
        {
            $data['params']['categories'] = array();
        }
    }

    /**
     *
     * TODO Rewrite this engine to use the new relation system inside FOF30
     *
     * @return bool|int
     */
    public function runAutoreplies()
    {
        // Let's get all the enabled autoreply rules
        $rules = $this->tmpInstance()
                      ->enabled(1)
                      ->filter_order('ordering')
                      ->filter_order_Dir('ASC')
                      ->get(true);

        // And now let's start looking for suitable tickets
        foreach($rules as $rule)
        {
            // If I got an error let's bubble it out
            $this->runRule($rule);
        }

        // If everything was ok, let's return the number of processed tickets
        return count($this->processedTickets);
    }

    protected function runRule($rule)
    {
        $cats           = array();
        $keywords_title = array();
        $keywords_text  = array();
        $botids         = $this->getBots();

        $rawData = trim($rule->keywords_title);
        $rawData = str_replace("\\n", "\n", $rawData);
        $rawData = str_replace("\r", "\n", $rawData);
        $rawData = str_replace("\n\n", "\n", $rawData);

        if($rawData)
        {
            $keywords_title = explode("\n", $rawData);
        }

        $rawData = trim($rule->keywords_text);
        $rawData = str_replace("\\n", "\n", $rawData);
        $rawData = str_replace("\r", "\n", $rawData);
        $rawData = str_replace("\n\n", "\n", $rawData);

        if($rawData)
        {
            $keywords_text = explode("\n", $rawData);
        }

        // FOF will automatically decode the JSON string into an array
        $params = $rule->params;

        if(isset($params['categories']))
        {
            $cats = $params['categories'];
        }

        $tickets = $this->getTickets($cats);

        // Performs several checks vs the rule, narrowing the number of affected tickets
        $tickets = $this->checkMinAfter($rule->min_after, $tickets);
        $tickets = $this->checkNumPosts($rule->num_posts, $tickets);
        $tickets = $this->checkAttachments($rule->attachment, $tickets);
        $tickets = $this->checkManagerReply($rule->run_after_manager, $tickets);
        $tickets = $this->checkTitleKeywords($keywords_title, $tickets);
        $tickets = $this->checkTextKeywords($keywords_text, $tickets);

        if($tickets)
        {
            $jNow = $this->container->platform->getDate();

            // Don't get a tmpInstance, since that will detach the relations
            /** @var Posts $post */
            $post = $this->container->factory->model('Posts');

            /** @var Tickets $t */
            $t = $this->container->factory->model('Tickets')->tmpInstance();
            // Remove the modified behavior, we want to do that manually
            $t->removeBehaviour('Modified');

            foreach($tickets as $ticket)
            {
                $post->reset(true, true);
                $t->reset();

                $current_bot = $botids[array_rand($botids)];

                // I'll leave empty the creation date, so F0F will populate it automatically.
                // Otherwise it will populate the modified_ column set, resulting in an edited post (which is false)
                $data = array(
                    'ats_post_id'    => 0,
                    'ats_ticket_id'	 => $ticket,
                    'content'	     => $rule->reply,
                    'created_on'	 => null,
                    'created_by'	 => $current_bot,
                    'enabled'		 => 1
                );

                $post->save($data);

                $t->find($ticket);

                $t->status      = 'P';
                $t->modified_on = $jNow->toSql();
                $t->modified_by = $current_bot;

                $t::$overrideuser = 1;

                $t->save();

                $t::$overrideuser = 0;

                $this->processedTickets[] = $ticket;
            }
        }

        return true;
    }

    protected function getBots()
    {
        $params   = \JComponentHelper::getParams('com_ats');
        $botusers = array();
        $bots     = array();

        // Let's check if I have any username
        $rawData = trim($params->get('botusers', ''));

        // Just in case something funky happened...
        $rawData = str_replace("\\n", "\n", $rawData);
        $rawData = str_replace("\r", "\n", $rawData);
        $rawData = str_replace("\n\n", "\n", $rawData);

        if($rawData)
        {
            $botusers = explode("\n", $rawData);
        }

        foreach($botusers as $bot)
        {
            $bots[] = $this->container->platform->getUser($bot)->id;
        }

        return $bots;
    }

    private function getTickets($cats = array())
    {
        static $tickets = array();

        $db = $this->getDbo();

        // Remove any empty value
        $cats = array_diff($cats, array('', '0'));

        $hash = md5(implode('', $cats));

        if(!isset($tickets[$hash]))
        {
            $bots  = $this->getBots();

            // Get the ticket id where a bot already replied in previous iterations
            $query = $db->getQuery(true)
                        ->select($db->qn('ats_ticket_id'))
                        ->from($db->qn('#__ats_posts'))
                        ->where($db->qn('created_by').' IN('.implode(',', $bots).')');
            $autoreplied = $db->setQuery($query)->loadColumn();

            // Get only open tickets
            $query = $db->getQuery(true)
                        ->select($db->qn('ats_ticket_id'))
                        ->from($db->qn('#__ats_tickets'))
                        ->where($db->qn('status').' = '.$db->quote('O'));

            // Skip tickets with an auto-reply
            if($autoreplied)
            {
                $query->where($db->qn('ats_ticket_id').' NOT IN('.implode($autoreplied).')');
            }

            if($cats)
            {
                $query->where($db->qn('catid').' IN('.implode(',', $cats).')');
            }

            $tickets[$hash] = $db->setQuery($query)->loadColumn();
        }

        // Subtracts the already processed tickets in order to avoid double posting
        $tickets[$hash] = array_diff($tickets[$hash], $this->processedTickets);

        return $tickets[$hash];
    }

    private function checkMinAfter($minutes, $tickets)
    {
        if(!$minutes || !$tickets)
        {
            return $tickets;
        }

        $db   = $this->getDbo();
        $time = $this->container->platform->getDate('-'.$minutes.' minutes');

        $query = $db->getQuery(true)
                    ->select($db->qn('ats_ticket_id'))
                    ->from($db->qn('#__ats_tickets'))
                    ->where($db->qn('ats_ticket_id').' IN('.implode(',', $tickets).')')
                    ->where($db->qn('modified_on').' <= '.$db->q($time->toSql()));

        $tickets = $db->setQuery($query)->loadColumn();

        return $tickets;
    }

    private function checkNumPosts($numPost, $tickets)
    {
        // If I have no constraints on the number of posts, let's return the passed ticket array
        if(!$numPost || $numPost == 1 || !$tickets)
        {
            return $tickets;
        }

        $db = $this->getDbo();

        $query = $db->getQuery(true)
                    ->select($db->qn('ats_ticket_id').', COUNT(*) as total')
                    ->from($db->qn('#__ats_posts'))
                    ->where($db->qn('ats_ticket_id').' IN('.implode(',', $tickets).')')
                    ->group($db->qn('ats_ticket_id'))
                    ->having($db->qn('total').' >= '.$numPost);

        $tickets = $db->setQuery($query)->loadColumn();

        return $tickets;
    }

    private function checkAttachments($attach, $tickets)
    {
        // If the rule applies to posts with or without attachments (ie every ticket),
        // let's return the passed ticket array
        if($attach == 2 || !$tickets)
        {
            return $tickets;
        }

        $db = $this->getDbo();

        $query = $db->getQuery(true)
                    ->select($db->qn('ats_ticket_id').', SUM('.$db->qn('ats_attachment_id').') as attachments')
                    ->from($db->qn('#__ats_posts'))
                    ->where($db->qn('ats_ticket_id').' IN('.implode(',', $tickets).')')
                    ->group($db->qn('ats_ticket_id'));

        // I want tickets where AT LEAST ONE post has an attachment
        if($attach)
        {
            $query->having($db->qn('attachments').' >= 0');
        }
        // I want tickets where NO POSTS have an attachments
        else
        {
            $query->having($db->qn('attachments').' = 0');
        }

        $tickets = $db->setQuery($query)->loadColumn();

        return $tickets;
    }

    private function checkManagerReply($manager, $tickets)
    {
        // Rule applies regardless the manager reply, let's return the passed ticket array
        if($manager == 2 || !$tickets)
        {
            return $tickets;
        }

        $db       = $this->getDbo();
        $managers = array();

        // Let's get all the affected categories
        $query = $db->getQuery(true)
                    ->select($db->qn('catid'))
                    ->from($db->qn('#__ats_tickets'))
                    ->where($db->qn('ats_ticket_id').' IN('.implode(',', $tickets).')')
                    ->group($db->qn('catid'));

        $cats = $db->setQuery($query)->loadColumn();

        // Now let's get all the managers
        // The only problem that could arise, is if a manager posts as customer inside another category he's not manager
        // I really think this is a remote chance
        foreach($cats as $cat)
        {
            $managers = array_merge($managers, Permissions::getManagers($cat));
        }

        $managers = \JArrayHelper::getColumn($managers, 'id');

        $query = $db->getQuery(true)
                    ->select($db->qn('ats_ticket_id'))
                    ->from($db->qn('#__ats_posts'))
                    ->where($db->qn('ats_ticket_id').' IN('.implode(',', $tickets).')')
                    ->group($db->qn('ats_ticket_id'));

        // I want only ticket where the manager didn't reply
        if(!$manager)
        {
            $query->where($db->qn('created_by').' NOT IN('.implode(',', $managers).')');
        }
        // Only where where he replied
        else
        {
            $query->where($db->qn('created_by').' IN('.implode(',', $managers).')');
        }

        $tickets = $db->setQuery($query)->loadColumn();

        return $tickets;

    }

    private function checkTitleKeywords(array $keywords, $tickets)
    {
        if(!$keywords || !$tickets)
        {
            return $tickets;
        }

        $db    = $this->getDbo();
        $parts = array();

        $query = $db->getQuery(true)
                    ->select($db->qn('ats_ticket_id'))
                    ->from($db->qn('#__ats_tickets'))
                    ->where($db->qn('ats_ticket_id').' IN('.implode(',', $tickets).')');

        foreach($keywords as $keyword)
        {
            $parts[] = $db->qn('title').' LIKE '.$db->q('%'.$keyword.'%');
        }

        $query->where('('.implode(' OR ', $parts).')');

        $tickets = $db->setQuery($query)->loadColumn();

        return $tickets;
    }

    private function checkTextKeywords($keywords, $tickets)
    {
        if(!$keywords || !$tickets)
        {
            return $tickets;
        }

        $db    = $this->getDbo();
        $parts = array();

        $query = $db->getQuery(true)
                    ->select($db->qn('ats_ticket_id'))
                    ->from($db->qn('#__ats_posts'))
                    ->where($db->qn('ats_ticket_id').' IN('.implode(',', $tickets).')');

        foreach($keywords as $keyword)
        {
            $parts[] = $db->qn('content').' LIKE '.$db->q('%'.$keyword.'%');
        }

        $query->where('('.implode(' OR ', $parts).')');

        $tickets = $db->setQuery($query)->loadColumn();

        return $tickets;
    }

    protected function setParamsAttribute($value)
    {
        return $this->setAttributeForJson($value);
    }

    protected function getParamsAttribute($value)
    {
        return $this->getAttributeForJson($value);
    }
}