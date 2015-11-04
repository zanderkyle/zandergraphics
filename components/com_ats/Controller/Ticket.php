<?php
/**
 * @package   AkeebaTicketSystem
 * @copyright Copyright (c)2011-2015 Nicholas K. Dionysopoulos
 * @license   GNU General Public License version 3, or later
 */

namespace Akeeba\TicketSystem\Site\Controller;

defined('_JEXEC') or die;

use Akeeba\TicketSystem\Admin\Helper\Permissions;
use Akeeba\TicketSystem\Site\Model\Categories;
use Akeeba\TicketSystem\Site\Model\Tickets;
use JText;

class Ticket extends \Akeeba\TicketSystem\Admin\Controller\Ticket
{
    public function execute($task)
    {
        $allowed = array(
            'default', 'browse', 'assign', 'ajax_set_status',
            'public_publish', 'public_unpublish', 'publish', 'unpublish',
            'close', 'reopen', 'move'
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

        // There aren't such actions on frontend
        if(in_array($task , array('new', 'edit', 'read', 'add')))
        {
            $task = 'read';
        }

        return parent::execute($task);
    }

    public function close()
    {
        $this->csrfProtection();

        /** @var Tickets $ticket */
        $ticket = $this->getModel();

        if(!$ticket->getId())
        {
            $this->getIDsFromRequest($ticket);
        }

        $perms = Permissions::getPrivileges($ticket);

        // Only managers and the owner of the ticket can close it
        if(!$perms['admin'] && !($ticket->created_by = $this->container->platform->getUser()->id))
        {
            return false;
        }

        $data = $ticket->getData();
        $data['status'] = 'C';

        // Do I have any incoming rating from ticket closing?
        if($this->input->getInt('rating', 0))
        {
            $data['rating'] = $this->input->getInt('rating', 0);
        }

        $url = 'index.php?option=com_ats&view=Ticket&id='.$ticket->ats_ticket_id.$this->getItemidURLSuffix();

        if($customURL = $this->input->getBase64('returnurl', ''))
        {
            $url = base64_decode($customURL);
        }

        try
        {
            $ticket->save($data);

            $this->setRedirect($url);
        }
        catch(\Exception $e)
        {
            $this->setRedirect($url, $e->getMessage(), 'error');
        }

        return true;
    }

    public function reopen()
    {
        $this->csrfProtection();

        /** @var Tickets $ticket */
        $ticket = $this->getModel();

        if(!$ticket->getId())
        {
            $this->getIDsFromRequest($ticket);
        }

        $perms = Permissions::getPrivileges($ticket);

        // Only managers can reopen a ticket
        if(!$perms['admin'])
        {
            return false;
        }

        $data = $ticket->getData();
        $data['status'] = 'O';

        $url = 'index.php?option=com_ats&view=Ticket&id='.$ticket->ats_ticket_id.$this->getItemidURLSuffix();

        if($customURL = $this->input->getBase64('returnurl', ''))
        {
            $url = base64_decode($customURL);
        }

        try
        {
            $ticket->save($data);

            $this->setRedirect($url);
        }
        catch(\Exception $e)
        {
            $this->setRedirect($url, $e->getMessage(), 'error');
        }

        return true;
    }

    public function move()
    {
        $this->csrfProtection();

        /** @var Tickets $ticket */
        $ticket = $this->getModel();

        if(!$ticket->getId())
        {
            $this->getIDsFromRequest($ticket);
        }

        $perms = Permissions::getPrivileges($ticket);

        // Only managers can move a ticket
        if(!$perms['admin'])
        {
            return false;
        }

        $data = $ticket->getData();
        $data['catid'] = $this->input->getInt('catid', 0);

        $url = 'index.php?option=com_ats&view=Ticket&id='.$ticket->ats_ticket_id.$this->getItemidURLSuffix();

        if($customURL = $this->input->getBase64('returnurl', ''))
        {
            $url = base64_decode($customURL);
        }

        try
        {
            $ticket->save($data);

            $this->setRedirect($url);
        }
        catch(\Exception $e)
        {
            $this->setRedirect($url, $e->getMessage(), 'error');
        }

        return true;
    }

    protected function onBeforeRead()
    {
        // Get the ticket ID
        $ticketid = $this->input->getInt('id', 0);
        $platform = $this->container->platform;

        if(!$ticketid)
        {
            $platform->raiseError(501, JText::_('COM_ATS_ERR_INVALID_TICKETID'));
        }

        if ($this->layout != 'print')
        {
            $this->layout = 'item';
        }

        /** @var Tickets $ticket */
        $ticket = $this->getModel();
        $this->getIDsFromRequest($ticket);

        // Is this a valid ticket?
        if(!$ticket->getId())
        {
            $platform->raiseError(404, JText::_('COM_ATS_ERR_TICKETNOTFOUND'));
        }

        $perms = Permissions::getPrivileges($ticket);

        if(!$perms['view'])
        {
            if($this->container->platform->getUser()->guest)
            {
                $returl = base64_encode(\JUri::getInstance()->toString());
                $url    = \JRoute::_('index.php?option=com_users&view=login&return='.$returl, false);
                \JFactory::getApplication()->redirect($url, JText::_('COM_ATS_ERR_TICKETNOTAUTH'));
            }
            else
            {
                $platform->raiseError(403, JText::_('COM_ATS_ERR_TICKETNOTAUTH'));
            }
        }

        // Check on category ticket
        /** @var Categories $category */
        $category   = $this->container->factory->model('Categories')->tmpInstance();
        $categories = $category->category($ticket->catid)->get();

        if(!$categories->count())
        {
            // No category? Stop here
            $platform->raiseError(403, JText::_('COM_ATS_ERR_TICKETNOTAUTH'));
        }

        // TODO Reload reply data from session if the ticket was not valid

        // Redirection to canonical URL
        // If I type http://www.example.com/support/12345 I want it to take me to ticket 12345
        $currentURL   = \JUri::getInstance()->toString(array('path', 'query', 'fragment'));
        $canonicalURL = \JRoute::_('index.php?option=com_ats&view=Ticket&id='.$ticket->ats_ticket_id, false);
        $canonicalURL = urldecode($canonicalURL);
        $currentURL   = urldecode($currentURL);

        if(substr($currentURL, 0, strlen($canonicalURL)) != $canonicalURL)
        {
            \JFactory::getApplication()->redirect($canonicalURL, '', 'message', true);
        }

        $this->registerCacheParams();
    }

    protected function onBeforeBrowse()
    {
        // Let's check if the user has access to the category
        $category_id = $this->input->getInt('category',0);

        if(!($category_id))
        {
            /** @var \JApplicationSite $app */
            $app = \JFactory::getApplication();
            $params = $app->getParams();

            // Maybe I got here directly from the menu
            $category_id = $params->get('category', 0);
        }

        /** @var Categories $category */
        $category   = $this->container->factory->model('Categories')->tmpInstance();
        $categories = $category->category($category_id)->get();

        if(!$categories->count())
        {
            // No category? Stop here
            throw new \Exception('Access forbidden', 403);
        }

        // Redirection to canonical URL
        // If I type http://www.example.com/support/12345 I want it to take me to ticket 12345
        $currentURL   = \JUri::getInstance()->toString(array('path', 'query', 'fragment'));
        $canonicalURL = \JRoute::_('index.php?option=com_ats&view=Tickets&category='.$category_id, false);

        // -- Maybe the current URL is urlencoded?
        if (preg_match('/%[a-z0-9]{2}/', $currentURL))
        {
            $currentURL = urldecode($currentURL);
        }

        // -- Maybe the cacnonical URL is urlencoded?
        if (preg_match('/%[a-z0-9]{2}/', $canonicalURL))
        {
            $currentURL = urldecode($canonicalURL);
        }

        if(substr($currentURL, 0, strlen($canonicalURL)) != $canonicalURL)
        {
            // This line is required for the state to persist
            $dummyList = $this->getModel()->get();

            // Perform the actual redirection
            \JFactory::getApplication()->redirect($canonicalURL, '', 'message', true);
        }

        $this->registerCacheParams();
    }

    private function registerCacheParams()
    {
        // Let's register our params for url caching
        $urlparams = array(
            'assignedtome'   => 'BOOL',
            'categories'     => 'ARRAY',
            'filterNewest'   => 'BOOL',
            'frontendfilter' => 'BOOL',
            'alias'          => 'STRING',
            'status'         => 'CMD',
            'catid'          => 'INT',
            'public'         => 'INT',
            'modified_on'    => 'STRING',
            'status_array'   => 'ARRAY',
            'created_by'     => 'INT',
        );

        /** @var \JApplicationCms $app */
        $app = \JFactory::getApplication();

        $registeredurlparams = null;

        if (!empty($app->registeredurlparams))
        {
            $registeredurlparams = $app->registeredurlparams;
        }
        else
        {
            $registeredurlparams = new \stdClass;
        }

        foreach ($urlparams as $key => $value)
        {
            // Add your safe url parameters with variable type as value {@see JFilterInput::clean()}.
            $registeredurlparams->$key = $value;
        }

        $app->registeredurlparams = $registeredurlparams;
    }
}