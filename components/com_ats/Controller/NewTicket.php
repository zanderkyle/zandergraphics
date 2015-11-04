<?php
/**
 * @package   AkeebaTicketSystem
 * @copyright Copyright (c)2011-2015 Nicholas K. Dionysopoulos
 * @license   GNU General Public License version 3, or later
 */

namespace Akeeba\TicketSystem\Site\Controller;

use Akeeba\TicketSystem\Admin\Controller\Ticket;
use Akeeba\TicketSystem\Admin\Controller\TraitSaveUserTags;
use Akeeba\TicketSystem\Admin\Helper\ComponentParams;
use Akeeba\TicketSystem\Admin\Helper\Credits;
use Akeeba\TicketSystem\Admin\Helper\Permissions;
use Akeeba\TicketSystem\Site\Model\Attempts;
use Akeeba\TicketSystem\Site\Model\Attachments;
use Akeeba\TicketSystem\Site\Model\Categories;
use Akeeba\TicketSystem\Site\Model\Posts;
use Akeeba\TicketSystem\Site\Model\Tickets;
use FOF30\Container\Container;
use JText;

defined('_JEXEC') or die;

class NewTicket extends Ticket
{
    use TraitSaveUserTags;

    public function __construct(Container $container, array $config = array())
    {
        $config['modelName'] = 'Tickets';
        $config['cacheableTasks'] = [];

        parent::__construct($container, $config);
    }

    public function execute($task)
    {
        $allowed = array(
            'default', 'add', 'save'
        );

        // Only allow a small subset of available tasks
        if(!in_array($task, $allowed))
        {
            return false;
        }

        if ($task == 'default')
        {
            $task = $this->getCrudTask();
        }

        if($task == 'read')
        {
            $task = 'add';
        }

        return parent::execute($task);
    }

    public function save()
    {
        $this->csrfProtection();

        // Fetch page parameters
        $params    = \JFactory::getApplication()->getPageParameters('com_ats');
        $session   = $this->container->session;

        // Fetch the category
        $category_id  = 0;
        $ticket       = $this->input->get('ticket', array(), 'array');

        $ticket['params'] = $this->input->get('params', array(), 'array');

        $post = $this->input->get('post', null, 'array', 2);

        if(is_array($ticket))
        {
            if(array_key_exists('catid', $ticket))
            {
                $category_id = $ticket['catid'];
            }
        }

        if(empty($category_id))
        {
            $category_id = $params->get('category', 0);
        }

        /** @var Categories $catModel */
        $catModel   = $this->container->factory->model('Categories')->tmpInstance();
        $categories = $catModel->category($category_id)->get(true);
        $category   = $categories->first();

        if(!$category)
        {
            // Category not found, this means that the user is not allowed to access it
            return false;
        }

        // Get ACL permissions
        $perms = Permissions::getActions($category_id);

        // Can I post to the category? If I can't, throw a 403!
        if(!$perms['core.create'])
        {
            return false;
        }

        // Am I a manager?
        $isManager = Permissions::isManager($category_id);

        // -- If I am a manager allow specifying the created_by user (post as another user)
        if ($isManager && $this->input->getInt('created_by', 0))
        {
            $date = new \JDate();
            $ticket['created_by'] = $this->input->getInt('created_by', 0);
            $ticket['created_on'] = $date->toSql();
        }

        // Save everything in the session
        if(!array_key_exists('public', $ticket)) 	 $ticket['public']      = 1;
        if(!array_key_exists('content', $post)) 	 $post['content']       = '';
        if(!array_key_exists('content_html', $post)) $post['content_html']  = '';
        if(!array_key_exists('created_by', $post))   $post['created_by']    = null;

        $postcontent = empty($post['content']) ? $post['content_html'] : $post['content'];

        $session->set('ticket_title',	$ticket['title'],	'com_ats.newticket');
        $session->set('ticket_public',	$ticket['public'],	'com_ats.newticket');
        $session->set('custom',			$ticket['params'],	'com_ats.newticket');
        $session->set('post_content',	$postcontent,		'com_ats.newticket');

        if ($isManager)
        {
            $session->set('created_by',	$ticket['created_by'],	'com_ats.newticket');
        }

        // Check public status
        if(!$perms['ats.private'])
        {
            $ticket['public'] = 1;
        }

        // Check title and content fields; fail if they're missing
        $error = false;

        if(empty($ticket['title']))
        {
            $error = JText::_('COM_ATS_ERR_NEWTICKET_NOTITLE');
        }

        if(!array_key_exists('content', $post))
        {
            $post['content'] = '';
        }

        if(!array_key_exists('content_html', $post))
        {
            $post['content_html'] = '';
        }

        // Is the credit feature on?
        if(ComponentParams::getParam('showcredits', 0))
        {
            $userId = \JFactory::getUser()->id;

            if ($isManager)
            {
                $userId = $post['created_by'];
            }

            // TODO check vs priority
            $hasCredits  = Credits::haveEnoughCredits($userId, $ticket['catid'], true, $ticket['public'], false);

            if(!$hasCredits)
            {
                if ($isManager)
                {
                    $error = JText::_('COM_ATS_ERR_NEWTICKET_NOT_ENOUGH_CREDITS_USER');
                }
                else
                {
                    $error = JText::_('COM_ATS_ERR_NEWTICKET_NOT_ENOUGH_CREDITS');
                }
            }
        }

        // --- Create the ticket
        /** @var Tickets $model */
        $model = $this->getModel()->reset();

        // Add custom fields validation
        if($error === false)
        {
            $isValid = $model->isValid();

            if(!$isValid)
            {
                $error = JText::_('COM_ATS_ERR_NEWTICKET_CUSTOM_FIELDS');
            }
        }

        if($error !== false)
        {
            // Let's add a flag field to hide/show custom fields validation labels
            $url = \JRoute::_('index.php?option=com_ats&view=NewTicket&category='.$category_id.'&formsubmit=1'.$this->getItemidURLSuffix());
            $this->setRedirect($url, $error, 'error');

            return true;
        }

        $ticket['catid'] = $category_id;

        // Save the ticket
        try
        {
            $model->save($ticket);
        }
        catch(\Exception $e)
        {
            $url = \JRoute::_('index.php?option=com_ats&view=NewTicket&category='.$category_id.'&Itemid='.$this->input->getInt('Itemid',0));
            $this->setRedirect($url, $e->getMessage(), 'error');

            return true;
        }

        $ats_ticket_id = $model->getId();
        $post['ats_ticket_id'] = $ats_ticket_id;

        // --- Create attachment
        /** @var Attachments $attachmentModel */
        $attachmentModel = $this->container->factory->model('Attachments')->tmpInstance();
        $attErrors       = array();
        $file            = $this->input->files->get('attachedfile', array(), 'array');

        if(isset($file[0]) && ($file[0]['name'] != '') && $perms['ats.attachment'])
        {
            list($post['ats_attachment_id'], $attErrors) = $attachmentModel->manageUploads($file);
        }

        // --- Create post
        $post['enabled'] = 1;
        $status          = true;
        $postError       = '';
        /** @var Posts $pModel */
        $pModel          = $this->container->factory->model('posts')->tmpInstance();

        // If I'm a manager, let's copy the created_by value from the ticket, maybe I'm creating the ticket on user behalf
        if($isManager)
        {
            $post['created_by'] = $ticket['created_by'];
            $post['created_on'] = $ticket['created_on'];
        }

        // Save the post (only if we didn't have any error with attachments)
        if(!$attErrors)
        {
            try
            {
                $pModel->save($post);
            }
            catch(\Exception $e)
            {
                $postError = $e->getMessage();
                $status    = false;
            }
        }

        // If I have any attachment, I have to check if they failed
        if(isset($post['ats_attachment_id']))
        {
            $status = $status && empty($attErrors);
        }

        if(!$status)
        {
            // Remove the attachments
            if(isset($post['ats_attachment_id']))
            {
                $attachments = explode(',', $post['ats_attachment_id']);

                foreach($attachments as $attachment)
                {
                    if($attachment)
                    {
                        $attachmentModel->delete($attachment);
                    }
                }
            }

            // Remove the ticket
            $model->delete($ats_ticket_id);

            // Redirect
            $url = \JRoute::_('index.php?option=com_ats&view=NewTicket&category='.$category_id.$this->getItemidURLSuffix(), false);

            if($postError)
            {
                $this->setRedirect($url, $postError, 'error');
            }
            else
            {
                $this->setRedirect($url);
            }

            return true;
        }

        // Update saved attachments with post id
        if(isset($post['ats_attachment_id']))
        {
            $attachmentModel->updateSavedAttachments($post['ats_attachment_id'], $pModel->ats_post_id);
        }

        // Clear session
        $session->clear('ticket_title',	 'com_ats.newticket');
        $session->clear('ticket_public', 'com_ats.newticket');
        $session->clear('custom', 		 'com_ats.newticket');
        $session->clear('post_content',	 'com_ats.newticket');
        $session->clear('created_by',	 'com_ats.newticket');

        // Redirect
        if($customURL = $this->input->getString('returnurl','')) $customURL = base64_decode($customURL);
        $url = !empty($customURL) ? $customURL : 'index.php?option=com_ats&view=Tickets&category='.$category_id.$this->getItemidURLSuffix();

        $this->setRedirect($url, JText::_('COM_ATS_LBL_NEWTICKET_SAVED'));
    }

    protected  function onAfterSave()
    {
        $this->saveUserTags();

        $ats_attempt_id = $this->input->getInt('ats_attempt_id');

        // Did ATS show up some instant reply, but the user ignored them?
        if($ats_attempt_id)
        {
            /** @var Attempts $attempt */
            $attempt = $this->container->factory->model('Attempts')->tmpInstance();
            /** @var Tickets $ticket */
            $ticket  = $this->getModel();

            $attempt->load($ats_attempt_id);

            $bind['ats_ticket_id'] = $ticket->ats_ticket_id;
            $attempt->save($bind);
        }

        return true;
    }

    /*
	 * Make sure the user is allowed to post in this category
	 */
    protected function onBeforeAdd()
    {
        // Fetch page parameters
        /** @var \JApplicationSite $app */
        $app     = \JFactory::getApplication();
        $params  = $app->getParams('com_ats');
        $session = $this->container->session;
        $view    = $this->getView();

        // Fetch the category
        $category_id = $this->input->getInt('category',0);
        /** @var Categories $catModel */
        $catModel    = $this->container->factory->model('Categories')->tmpInstance();

        if (empty($category_id))
        {
            $category_id = $params->get('category', 0);
        }

        if (empty($category_id))
        {
            // No category specified. We'll have to show a landing page.
            $this->layout = 'landing';
            $this->getView()->setLayout('landing');
            $this->getView()->categories = $catModel->get(true);

            return true;
        }

        $categories = $catModel->category($category_id)->get(true);
        /** @var Categories $category */
        $category   = $categories->first();

        if(!$category)
        {
            // Category not found, this means that the user is not allowed to access it
            return false;
        }

        // Get ACL permissions
        $perms = Permissions::getActions($category->id);

        // Can I post to the category? If I can't, throw a 403!
        if(!$perms['core.create'])
        {
            return false;
        }

        // -- If I am a manager allow specifying the created_by user (post as another user)
        $isManager = Permissions::isManager($category->id);

        // Load session and push data
        // @TODO Load everything inside the $cache variable, in the same way we do in Akeeba Subs
        $custom = $session->get('custom', array(), 'com_ats.newticket');

        // Make sure that it's an array, so it will be handled correctly by ats plugins (ie customfields)
        if(!is_array($custom))
        {
            $custom = json_decode($custom, true);
        }

        $cache['params'] = $custom;

        $view->ticket_title  = $session->get('ticket_title', '', 'com_ats.newticket');
        $view->ticket_public = $session->get('ticket_public', null, 'com_ats.newticket');
        $view->post_content  = $session->get('post_content', '', 'com_ats.newticket');
        $view->cache         = $cache;

        if ($isManager)
        {
            $view->created_by = $session->get('created_by', \JFactory::getUser()->id, 'com_ats.newticket');
        }

        $session->clear('ticket_title',		'com_ats.newticket');
        $session->clear('ticket_public',	'com_ats.newticket');
        $session->clear('post_content',		'com_ats.newticket');

        // Push permissions
        $view->allow_private    = $perms['ats.private'];
        $view->allow_attachment = $perms['ats.attachment'];

        // Push page parameters
        $view->pageparams = $params;

        // Push category object
        $category->objParams = new \JRegistry();

        $category->objParams->loadString($category->params, 'JSON');

        $view->category = $category;

        return true;
    }
}