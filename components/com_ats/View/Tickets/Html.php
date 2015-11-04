<?php
/**
 * @package   AkeebaTicketSystem
 * @copyright Copyright (c)2011-2015 Nicholas K. Dionysopoulos
 * @license   GNU General Public License version 3, or later
 */

namespace Akeeba\TicketSystem\Site\View\Tickets;

use Akeeba\TicketSystem\Admin\Helper\Permissions;
use Akeeba\TicketSystem\Site\Model\Categories;
use Akeeba\TicketSystem\Site\Model\Tickets;

defined('_JEXEC') or die;

class Html extends \FOF30\View\DataView\Html
{
    /** @var  Categories Category for the current list of tickets */
    protected $category;
    /** @var  bool  Can the user create a ticket? */
    protected $canCreate;
    /** @var  bool  Is the current user a manager? */
    protected $isManager;
    /** @var  array Privileges for the current ticket */
    protected $ticketPerms;

    protected function onBeforeRead()
    {
        parent::onBeforeRead();

        /** @var \JApplicationSite $app */
        $app = \JFactory::getApplication();
        $params = $app->getParams();
        $this->pageParams = $params;

        /** @var Tickets $ticket */
        $ticket = $this->item;

        $this->ticketPerms = Permissions::getPrivileges($ticket);
        $this->isManager   = Permissions::isManager($ticket->catid);

        $this->container->platform->importPlugin('ats');
    }

    protected function onBeforePrint()
    {
        $this->onBeforeRead();
    }

    protected function onBeforeBrowse()
    {
        /** @var Tickets $model */
        $model = $this->getModel();

        // Apply custom filters for frontend
        $model->frontendfilter(1)
              ->enabled(1)
              ->filterNewest(1)
              ->filter_order_Dir('DESC');

        parent::onBeforeBrowse();

        // Let's fetch the category from the request or page params
        $category_id = $this->input->getInt('category',0);

        if(!($category_id))
        {
            // Maybe I got here directly from the menu
            $category_id = $this->pageParams->get('category', 0);
        }

        /** @var Categories $category */
        $category       = $this->container->factory->model('Categories')->tmpInstance();
        $this->category = $category->category($category_id)->get()->first();

        $actions = Permissions::getActions($this->category->id);
        $this->canCreate = $actions['core.create'];

        $this->isManager = Permissions::isManager($this->category->id);
    }
}