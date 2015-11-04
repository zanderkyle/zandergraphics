<?php
/**
 * @package   AkeebaTicketSystem
 * @copyright Copyright (c)2011-2015 Nicholas K. Dionysopoulos
 * @license   GNU General Public License version 3, or later
 */

namespace Akeeba\TicketSystem\Admin\Helper;

use Akeeba\TicketSystem\Admin\Model\Posts;
use Akeeba\TicketSystem\Admin\Model\Tickets;
use Akeeba\TicketSystem\Site\Model\Categories;
use FOF30\Container\Container;

defined('_JEXEC') or die;

class Permissions
{
    /**
     * Returns the all the privileges linked with a specific ticket
     *
     * @param   Tickets $ticket
     *
     * @return array
     */
    public static function getPrivileges($ticket)
    {
        $ret = array(
            'view'		 => false,
            'post'		 => false,
            'edit'		 => false,
            'edit.state' => false,
            'admin'		 => false,
            'close'		 => false,
            'attachment' => false,
        );

        $container = Container::getInstance('com_ats');
        $actions   = self::getActions($ticket->catid);
        $user      = $container->platform->getUser();

        // Can I change the visibility of the ticket?
        $ret['edit.state'] = $actions['core.edit.state'];

        // If I am the onwer of this ticket, I can see it and post to it
        if($ticket->created_by == $user->id)
        {
            $ret['view']        = true;
            $ret['post']        = true;
            $ret['close']       = true;
            $ret['edit']        = $actions['core.edit.own'];
        }

        // If I am the manager I can do anything
        if(self::isManager($ticket->catid))
        {
            $ret = array(
                'view'		 => true,
                'post'		 => true,
                'edit'		 => true,
                'edit.state' => true,
                'admin'		 => true,
                'close'		 => true,
                'attachment' => true,
            );
        }

        // If it's a public ticket, I can view it
        if($ticket->public)
        {
            $ret['view'] = true;
        }

        // What about attachments?
        $ret['attachment'] = $actions['ats.attachment'];

        return $ret;
    }

    /**
     * Gets a list of the actions that can be performed.
     *
     * @param	int		$categoryId     The category ID.
     * @param   int     $userId         User id
     *
     * @return	\stdClass
     */
    public static function getActions($categoryId = 0, $userId = null)
    {
        $container = Container::getInstance('com_ats');
        $user      = $container->platform->getUser($userId);

        $result	= array();

        $actions = array(
            'core.admin', 'core.manage', 'core.create', 'core.edit',
            'core.edit.own', 'core.edit.state', 'core.delete',
            'ats.private', 'ats.attachment'
        );

        foreach ($actions as $action)
        {
            if($categoryId)
            {
                $result[$action] = $user->authorise($action, 'com_ats') ||
                                    $user->authorise($action, 'com_ats.category.'.(int) $categoryId);
            }
            else
            {
                $result[$action] = $user->authorise($action, 'com_ats');
            }
        }

        return $result;
    }

    /**
     * Is the user a manger?
     *
     * @param   int   $category  Category id (opt.)
     * @param   int   $userid    Userid (opt. current user if null)
     *
     * @return  bool  Is manager?
     */
    public static function isManager($category = null, $userid = null)
    {
        $container = Container::getInstance('com_ats');

        // Automatically fetches the current user if the id is null
        $user = $container->platform->getUser($userid);

        if ($user->authorise('core.admin', 'com_ats') 	||
            $user->authorise('core.manage', 'com_ats') 	||
            ($category ? $user->authorise('core.manage', 'com_ats.category.'.$category) : false))
        {
            return true;
        }

        return false;
    }

    /**
     * Returns the categories where the user is a manager
     *
     * @param   int $userid
     */
    public static function getManagerCategories($userid = null)
    {
        static $cache = array();

        $container = Container::getInstance('com_ats');
        $userid    = $container->platform->getUser($userid)->id;

        if(!isset($cache[$userid]))
        {
            // First of all let's fetch all the categories the user has access to
            /** @var Categories $catModel */
            $catModel   = $container->factory->model('Categories')->tmpInstance();
            $categories = $catModel->userid($userid)->get();

            $allowed = array();

            /** @var Categories $category */
            foreach($categories as $category)
            {
                if(Permissions::isManager($category->id, $userid))
                {
                    $allowed[] = $category->id;
                }
            }

            $cache[$userid] = $allowed;
        }

        return $cache[$userid];
    }

    /**
     * Fetches all the managers of the given category
     *
     * @param   int     $category    Ticket category
     *
     * @return  array   List of ids and names of managers (indexed by id) of the category
     */
    public static function getManagers($category = null)
    {
        static $cache = array();

        if(isset($cache[$category]))
        {
            return $cache[$category];
        }

        //AFAIK there is no way to get the list of users enabled to do something, so we have to improvise
        $container = Container::getInstance('com_ats');

        $db		   = $container->db;
        $users 	   = array();
        $allowed   = array();
        $managers  = array();

        // First, let's get the whole list of groups
        $query = $db->getQuery(true)
                    ->select('id')
                    ->from('#__usergroups')
                    ->order('id DESC');
        $groups = $db->setQuery($query)->loadColumn();

        // Then check if they can admin tickets
        foreach ($groups as $group)
        {
            if (\JAccess::checkGroup($group, 'core.admin', 'com_ats')  ||
                \JAccess::checkGroup($group, 'core.manage', 'com_ats') ||
                ($category ? \JAccess::checkGroup($group, 'core.manage', 'com_ats.category.'.$category) : false))
            {
                //If so, let's get all the users
                $users = array_merge($users, \JAccess::getUsersByGroup($group));
            }
        }

        // Let's check if any user has the right privileges
        foreach($users as $user)
        {
            $juser = $container->platform->getUser($user);

            if ($juser->authorise('core.admin', 'com_ats') 		||
                $juser->authorise('core.manage', 'com_ats') 	||
                ($category ? $juser->authorise('core.manage', 'com_ats.category.'.$category) : false))
            {
                $allowed[] = $user;
            }
        }

        if($allowed)
        {
            $query = $db->getQuery(true)
                        ->select('id, name')
                        ->from('#__users')
                        ->where('id IN('.implode(',', $allowed).')')
                        ->where('block = 0');
            $managers = $db->setQuery($query)->loadObjectList('id');
        }

        $cache[$category] = $managers;

        return $managers;
    }

    /**
     * Checks if a post if editable since we're inside the grace time
     *
     * @param   Posts   $post   Post to check
     *
     * @return  bool    Are we within the grace time?
     */
    public static function editGraceTime($post)
    {
        $container = Container::getInstance('com_ats');
        $graceTime = ComponentParams::getParam('editeableforxminutes', 15);
        $result    = false;
        $userid    = $container->platform->getUser()->id;

        if(($post->modified_by == $userid) || ($post->created_by == $userid))
        {
            if(($post->modified_by == $userid))
            {
                $editedOn = $container->platform->getDate($post->modified_on);
            }
            else
            {
                $editedOn = $container->platform->getDate($post->created_on);
            }

            $now = $container->platform->getDate();

            $editedAgo = abs($now->toUnix() - $editedOn->toUnix());

            $result = $editedAgo < 60 * $graceTime;
        }

        return $result;
    }

    /**
     * Is the post attachment visible?
     *
     * @param   \Akeeba\TicketSystem\Admin\Model\Attachments    $attachment Attachment record
     * @param   bool                                            $isManager  Am I a manager? Calculated in the ticket
     *                                                                      view since I need the cat id
     * @param   \Akeeba\TicketSystem\Admin\Model\Posts          $post       Post record
     *
     * @return bool
     */
    public static function attachmentVisible($attachment, $isManager, $post)
    {
        /*
         * This was the original if statement:
         * if($attachment->ats_attachment_id && !empty($attachment->original_filename) && ($attachment->enabled || (!$attachment->enabled && $user->authorise('core.manage','com_ats')) || $this->isManager || (!$attachment->enabled && $user->authorise('core.edit.own','com_ats') && ($user->id == $item->created_by)))):
         *
         * it was split into several different checks for better readability
         */

        // No attachment id? Stop here
        if(!$attachment->ats_attachment_id)
        {
            return false;
        }

        // No attachment file name? Stop here
        if(!$attachment->original_filename)
        {
            return false;
        }

        // Well, if it's enabled everyone can see it
        if($attachment->enabled)
        {
            return true;
        }

        // If it's not enabled and I'am a manager, I can see it anyway
        if($isManager)
        {
            return true;
        }

        $container = Container::getInstance('com_ats');
        $user      = $container->platform->getUser();

        // Mhm... I'm not a manager and the attachment is not enabled. Can I still see it?
        // If I can edit my own tickets and I'm the owner of the post, I can do that
        if($user->authorise('core.edit.own','com_ats') && ($user->id == $post->created_by))
        {
            return true;
        }

        return false;
    }

    /**
     * Is the attachment private?
     *
     * @param   \Akeeba\TicketSystem\Admin\Model\Attachments    $attachment     Attachment record
     * @param   bool                                            $isManager      Am I a manager?
     * @param   int                                             $ticket_owner   Ticket owner
     *
     * @return bool
     */
    public static function attachmentPrivate($attachment, $isManager, $ticket_owner)
    {
        // Is the "Attachments private" flag enabled?
        if(!ComponentParams::getParam('attachments_private', 0))
        {
            return false;
        }

        $container = Container::getInstance('com_ats');
        $user      = $container->platform->getUser();

        if (
            // If this is not a manager
            !$isManager
            // and he doesn't own the attachment
            && ($user->id != $attachment->created_by)
            // and he doesn't own the ticket
            && ($user->id != $ticket_owner)
        )
        {
            // The attachment is private
            return true;
        }

        // Otherwise I is not private
        return false;
    }
}