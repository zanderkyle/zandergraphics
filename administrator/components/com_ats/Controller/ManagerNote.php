<?php
/**
 * @package   AkeebaTicketSystem
 * @copyright Copyright (c)2011-2015 Nicholas K. Dionysopoulos
 * @license   GNU General Public License version 3, or later
 */

namespace Akeeba\TicketSystem\Admin\Controller;

use Akeeba\TicketSystem\Admin\Helper\Permissions;
use FOF30\Container\Container;
use FOF30\Controller\DataController;

defined('_JEXEC') or die;

class ManagerNote extends DataController
{
    public function __construct(Container $container, array $config = array())
    {
        $config['cacheableTasks'] = array();

        parent::__construct($container, $config);
    }

    /**
     * Check if the user is a manager of the category linked to the ticket
     *
     * @return bool
     */
    protected function onBeforeSave()
    {
        /** @var \Akeeba\TicketSystem\Admin\Model\ManagerNotes $model */
        $model = $this->getModel();

        $this->getIDsFromRequest($model);

        $newNote = $model->ats_managernote_id <= 0;

        if($newNote)
        {
            $ticket_id = $this->input->getInt('ats_ticket_id',0);
        }
        else
        {
            $ticket_id = $model->ats_ticket_id;
        }

        if($ticket_id <= 0)
        {
            return false;
        }

        /** @var \Akeeba\TicketSystem\Admin\Model\Tickets $ticket */
        $ticket = $this->container->factory->model('Tickets')->tmpInstance();
        $ticket->load($ticket_id);

        if(!Permissions::isManager($ticket->catid))
        {
            return false;
        }

        $url = 'index.php?option=com_ats&view=Ticket&id='.$ticket_id.$this->getItemidURLSuffix();

        if($customURL = $this->input->getBase64('returnurl', ''))
        {
            $url = base64_decode($customURL);
        }

        $this->input->set('returnurl', base64_encode($url));

        return true;
    }

    protected function onBeforeAdd()
    {
        return false;
    }

    protected function onBeforeRead()
    {
        /** @var \Akeeba\TicketSystem\Admin\Model\ManagerNotes $item */
        $item = $this->getModel();

        $this->getIDsFromRequest($item);

        $ticket = $item->ticket;

        return Permissions::isManager($ticket->catid);
    }

    protected function onBeforeEdit()
    {
        /** @var \Akeeba\TicketSystem\Admin\Model\ManagerNotes $item */
        $item = $this->getModel();

        $this->getIDsFromRequest($item);

        $ticket = $item->ticket;

        return Permissions::isManager($ticket->catid);
    }

    protected function onBeforeRemove()
    {
        /** @var \Akeeba\TicketSystem\Admin\Model\ManagerNotes $item */
        $item = $this->getModel();

        $this->getIDsFromRequest($item);

        $ticket = $item->ticket;

        return Permissions::isManager($ticket->catid);
    }

    protected function onBeforePublish()
    {
        /** @var \Akeeba\TicketSystem\Admin\Model\ManagerNotes $item */
        $item = $this->getModel();

        $this->getIDsFromRequest($item);

        $ticket = $item->ticket;

        return Permissions::isManager($ticket->catid);
    }

    protected function onBeforeUnpublish()
    {
        /** @var \Akeeba\TicketSystem\Admin\Model\ManagerNotes $item */
        $item = $this->getModel();

        $this->getIDsFromRequest($item);

        $ticket = $item->ticket;

        return Permissions::isManager($ticket->catid);
    }
}