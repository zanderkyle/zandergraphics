<?php
/**
 * @package   AkeebaTicketSystem
 * @copyright Copyright (c)2011-2015 Nicholas K. Dionysopoulos
 * @license   GNU General Public License version 3, or later
 */

namespace Akeeba\TicketSystem\Admin\Controller;

use Akeeba\TicketSystem\Admin\Helper\Bbcode;
use Akeeba\TicketSystem\Admin\Helper\ComponentParams;
use Akeeba\TicketSystem\Admin\Helper\Credits;
use Akeeba\TicketSystem\Admin\Helper\Permissions;
use FOF30\Controller\DataController;
use JDate;
use JText;

defined('_JEXEC') or die;

class Post extends DataController
{
    protected function onBeforeAdd()
    {
        // You are not supposed to directly create a new post using the controller
        return false;
    }

    protected function onBeforeEdit()
    {
        // General check on editing
        $canEdit = parent::checkACL('@edit');

        /** @var \Akeeba\TicketSystem\Admin\Model\Posts $item */
        $item              = $this->getModel();

        $this->getIDsFromRequest($item);

        $ticket = $item->ticket;

        // The standard check failed, maybe we're category manager?
        if ( !$canEdit)
        {
            $canEdit = Permissions::isManager($ticket->catid);
        }

        // Are we withing the editing time limit?
        $withinEditingTime = Permissions::editGraceTime($item);

        return $canEdit || $withinEditingTime;
    }

    /**
     * Perform a complex ACL check
     *
     * @return boolean
     */
    protected function onBeforeSave()
    {
        $component = \JComponentHelper::getParams('com_ats');
        $container = $this->container;
        $format    = $this->input->getCmd('format', '');

        // Model can't fetch the ats_post_id name, so I have to manually set it
        /** @var \Akeeba\TicketSystem\Admin\Model\Posts $post */
        $post = $this->getModel();
        $this->getIDsFromRequest($post);

        // Check if it's a new post
        $newPost = $post->ats_post_id <= 0;

        $user = $container->platform->getUser();

        // Check if it's a new ticket
        if ($newPost)
        {
            $ticket_id = $this->input->getInt('ats_ticket_id', 0);
        }
        else
        {
            $ticket_id = $post->ats_ticket_id;
        }

        if ($ticket_id <= 0)
        {
            return false;
        }

        /** @var \Akeeba\TicketSystem\Admin\Model\Tickets $ticket */
        $ticket = $container->factory->model('Tickets')->tmpInstance();
        $ticket->load($ticket_id);

        // Allow new ticket posting only for managers, or owner, if the ticket is not closed
        if ($newPost)
        {
            if (!Permissions::isManager($ticket->catid))
            {
                if ($user->id != $ticket->created_by)
                {
                    return false;
                }
                elseif ($ticket->status == 'C')
                {
                    return false;
                }
            }

            // If the user is a manager and there isn't an assigned user, let's assign him to the ticket
            if ( !$ticket->assigned_to && Permissions::isManager($ticket->catid))
            {
                $ticket->assigned_to = $user->id;
                $ticket->store();
            }
        }
        else
        {
            // Allow ticket editing for owner, if he has edit.own and the ticket is not closed,
            // or if it's an administrator
            if (!Permissions::isManager($ticket->catid))
            {
                if ($user->id != $ticket->created_by)
                {
                    return false;
                }
                elseif ($ticket->status == 'C')
                {
                    return false;
                }
                elseif (
                    !$user->authorise('core.edit.own', 'com_ats.category.' . $ticket->catid) &&
                    !$user->authorise('core.edit.own', 'com_ats')
                )
                {
                    \JLoader::import('joomla.utilities.date');
                    \JLoader::import('joomla.application.component.helper');

                    $editedOn    = new JDate($post->created_on);
                    $altEditedOn = new JDate($post->modified_on);

                    if ($altEditedOn->toUnix() > $editedOn->toUnix())
                    {
                        $editedOn = $altEditedOn;
                    }
                    $now       = new JDate();
                    $editedAgo = abs($now->toUnix() - $editedOn->toUnix());

                    $editeableforxminutes = ComponentParams::getParam('editeableforxminutes', 15);

                    return ($editedAgo < 60 * $editeableforxminutes);
                }
            }
        }

        // If we are still here, the user is allowed to post

        // Handle uploads
        $canPostAttachments = false;

        if ($user->authorise('ats.attachment', 'com_ats'))
        {
            $canPostAttachments = true;
        }
        elseif ($user->authorise('ats.attachment', 'com_ats.category.' . $ticket->catid))
        {
            $canPostAttachments = true;
        }

        $url = 'index.php?option=com_ats&view=Tickets&id='.$ticket_id.$this->getItemidURLSuffix();

        // Get return URL
        if ($customURL = $this->input->getString('returnurl', ''))
        {
            $url = base64_decode($customURL);
        }

        // Let's check if any custom field is not valid.
        // If so, redirect to the edit page, preventing attachment upload (data is not valid)
        // I need to do this check here or I can't redirect to the single post page ;(
        $custom = $this->input->get('params', array(), 'array');

        if ($custom)
        {
            // Grab ticket info and inject them into the model, so I can use the model function
            // without messing up any existing data
            /** @var \Akeeba\TicketSystem\Admin\Model\Tickets $m_ticket */
            $m_ticket = $container->factory->model('tickets')->tmpInstance();

            $m_ticket->catid($ticket->catid);
            $m_ticket->setState('params', $custom);

            // Failed validation!
            if ( !$m_ticket->isValid())
            {
                // If custom fields are not ok
                $err_url  = 'index.php?option=com_ats&view=Post&task=edit&id=' . $post->ats_post_id . $this->getItemidURLSuffix();
                $err_url .= '&formsubmit=1&returnurl=' . base64_encode($url);

                // I don't worry about AJAX requests, since I'll do that only with existing tickets
                $this->setRedirect($err_url, JText::_('COM_ATS_ERR_NEWTICKET_CUSTOM_FIELDS'), 'error');
                $this->redirect();
            }
            else
            {
                // If I'm here ticket could be saved (ACL checks and above custom fields validations)
                $ticket->params = json_encode($custom);
                $ticket->store();
            }
        }

        // JInput organizes the file array automatically
        $file = $this->input->files->get('attachedfile', array(), 'array');

        if (isset($file[0]) && ($file[0]['name'] != '') && $canPostAttachments)
        {
            /** @var \Akeeba\TicketSystem\Admin\Model\Attachments $attachmentModel */
            $attachmentModel = $container->factory->model('Attachments')->tmpInstance();

            list($attachIds, $attachmentErrors) = $attachmentModel->manageUploads($file);
            $this->getModel()->setState('attachment_errors', $attachmentErrors);

            $this->input->set('ats_attachment_id', $attachIds);
        }

        if (isset($file[0]) && ($file[0]['name'] != '') && !$canPostAttachments)
        {
            $this->getModel()
                ->setState('attachment_errors', array(JText::_('COM_ATS_POSTS_ERR_ATTACHMENTNOTALLOWED')));
        }

        // Let's check if anyone posted a reply while we were writing
        $last_post_req = $this->input->getInt('last_ats_post_id', 0);

        // Let's load the entire list, so I'll get the last post id
        $posts = $ticket->getRelations()->getData('posts', function($model){
            $model->filter_order('created_on')->filter_order_Dir('ASC');
        });

        $last_post = $posts->last();
        $last_post_id = 0;

        if($last_post)
        {
            $last_post_id = $last_post->ats_post_id;
        }

        $session = $container->session;

        if ($last_post_req && ($last_post_req != $last_post_id))
        {
            // It's a different one from the incoming one, a new answer has been posted
            // Let's warn the user and save his previous post
            if ($format == 'json')
            {
                $response['result']      = false;
                $response['error']       = JText::_('COM_ATS_TICKET_REPLY_POSTED_WRITING');
                $response['forceReload'] = 1;
                $response['lastPost']    = $last_post_id;

                header('Content-type: application/json');
                echo json_encode($response);

                $container->platform->closeApplication();
            }
            else
            {
                $url = 'index.php?option=com_ats&view=Ticket&id=' . $ticket_id . $this->getItemidURLSuffix() . '&warn_reply=1';
                $url = \JRoute::_($url) . '#p' . $last_post_id;
                $session->set('post_content', $this->input->getHtml('content', ''), 'com_ats.newticket');

                \JFactory::getApplication()->redirect($url);
            }
        }
        else
        {
            // Everything is ok, let's delete any saved data into the session
            $session->clear('post_content', 'com_ats.newticket');

            $url = 'index.php?option=com_ats&view=Ticket&id=' . $ticket_id . $this->getItemidURLSuffix();

            // Some servers and backend can't read encoded '&' . Let's make it work, despite XHTML compliance
            $url = \JRoute::_($url, false);
        }

        // Do I have to charge any extra credits?
        $extra_cred = $this->input->getInt('extracredits', 0);

        if ($extra_cred && $component->get('showcredits', 0))
        {
            // User hasn't enough credits, let's stop here and warn the manager
            if (Credits::creditsLeft($ticket->created_by) < $extra_cred)
            {
                $err_url  = 'index.php?option=com_ats&view=Ticket&id=' . $ticket_id . $this->getItemidURLSuffix();
                $err_url .= '&formsubmit=1&returnurl=' . base64_encode($url);

                $this->setRedirect($err_url, JText::_('COM_ATS_POSTS_ERR_EXTRA_CREDITS'), 'error');
                $this->redirect();
            }
        }

        $this->input->set('returnurl', base64_encode($url));

        return true;
    }

    protected function onAfterSave()
    {
        $extra_cred = $this->input->getInt('extracredits');

        if($extra_cred && ComponentParams::getParam('showcredits', 0))
        {
            /** @var \Akeeba\TicketSystem\Admin\Model\Posts $model */
            $model  = $this->getModel();
            $ticket = $model->ticket;

            // Probably that's an overkill, but let's check if the user has enough credits
            if(Credits::creditsLeft($ticket->created_by) >= $extra_cred)
            {
                // We're adding extra credits, the ticket is always created and we don't care about ticket priority
                Credits::chargeCredits($ticket->created_by, $ticket->catid, $ticket->ats_ticket_id, $model->ats_post_id, false, false, 0, $extra_cred);
            }
        }

        // User tagging
        $tags = $this->input->get('usertags', array(), 'array', 2);

        // I have to blank out the tags only if I'm in the frontend (on backend I'm saving the tags while I'm saving
        // the ticket, not when adding a post
        if($this->container->platform->isFrontend())
        {
            /** @var \Akeeba\TicketSystem\Admin\Model\Posts $model */
            $model = $this->getModel();
            $ticket = $model->ticket;
            $user   = \JUser::getInstance($ticket->created_by);

            $user->setParam('ats_tags', $tags);
            $user->save();
        }
    }

    public function save()
    {
        try
        {
            $result = true;
            $error  = '';

            parent::save();
        }
        catch(\Exception $e)
        {
            $result = false;
            $error  = $e->getMessage();
        }

        /** @var \Akeeba\TicketSystem\Admin\Model\Posts $post */
        $post = $this->getModel();

        // Update saved attachments with post id
        $attachments = $this->input->getString('ats_attachment_id', '');

        if ($attachments)
        {
            /** @var \Akeeba\TicketSystem\Admin\Model\Attachments $attachmentModel */
            $attachmentModel = $this->container->factory->model('Attachments')->tmpInstance();

            $attachmentModel->updateSavedAttachments($attachments, $post->ats_post_id);
        }

        // Frontend AJAX requests need a special handling
        if ($this->container->platform->isFrontend() && $this->input->getCmd('format', '') == 'json')
        {
            // First of all let's invoke the onAfterSave event
            $result = $this->triggerEvent('onAfterSave');

            header('Content-type: application/json');

            $response = array(
                'result'            => $result,
                'id'                => $post->getId(),
                'error'             => $error,
                'attachment_errors' => $post->getState('attachment_errors', array())
            );

            echo json_encode($response);

            $this->container->platform->closeApplication();
        }

        return $result;
    }

    public function creditsrefund()
    {
        // CSRF prevention
        $this->csrfProtection();

        $status = false;

        /** @var \Akeeba\TicketSystem\Admin\Model\Posts $post */
        $post = $this->getModel();

        if ( !$post->getId())
        {
            $this->getIDsFromRequest($post);
        }

        if ($post->ats_post_id)
        {
            $ticket    = $post->ticket;

            if ($ticket->ats_ticket_id)
            {
                if (!Permissions::isManager($ticket->catid))
                {
                    return false;
                }

                Credits::refundCredits($ticket->catid, $ticket->ats_ticket_id, $post->ats_post_id, 'post');

                $status = true;
            }
        }

        $url = 'index.php?option=com_ats&view=Posts&id='.$post->ats_post_id;

        if ($customURL = $this->input->getString('returnurl', ''))
        {
            $url = base64_decode($customURL);
        }

        if ( !$status)
        {
            $this->setRedirect($url, JText::_('COM_ATS_POSTS_CREDITS_REFUND_ERROR'), 'error');
        }
        else
        {
            $this->setRedirect($url, JText::_('COM_ATS_POSTS_CREDITS_REFUND_SUCCESS'));
        }
    }

    public function creditscharge()
    {
        // CSRF prevention
        $this->csrfProtection();

        $status = false;

        /** @var \Akeeba\TicketSystem\Admin\Model\Posts $post */
        $post = $this->getModel();

        if ( !$post->getId())
        {
            $this->getIDsFromRequest($post);
        }

        if ($post->ats_post_id)
        {
            $ticket = $post->ticket;

            if ($ticket->ats_ticket_id)
            {
                if (!Permissions::isManager($ticket->catid))
                {
                    return false;
                }

                Credits::chargeCredits($ticket->created_by, $ticket->catid, $ticket->ats_ticket_id, $post->ats_post_id, false, $ticket->public);

                $status = true;
            }
        }

        $url = 'index.php?option=com_ats&view=Posts&id='.$post->ats_post_id;

        if ($customURL = $this->input->getString('returnurl', ''))
        {
            $url = base64_decode($customURL);
        }

        if ( !$status)
        {
            $this->setRedirect($url, JText::_('COM_ATS_POSTS_CREDITS_CHARGE_ERROR'), 'error');
        }
        else
        {
            $this->setRedirect($url, JText::_('COM_ATS_POSTS_CREDITS_CHARGE_SUCCESS'));
        }
    }

    /**
     * Parses the incoming raw bbcode into HTML code. This is used to provide a preview of the ticket
     */
    public function parsebbcode()
    {
        $content = $this->input->getHtml('content', '');
        $html    = Bbcode::parseBBCode($content);

        echo json_encode($html);

        $this->container->platform->closeApplication();
    }
}