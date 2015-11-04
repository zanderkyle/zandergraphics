<?php
/**
 * @package   AkeebaTicketSystem
 * @copyright Copyright (c)2011-2015 Nicholas K. Dionysopoulos
 * @license   GNU General Public License version 3, or later
 */

namespace Akeeba\TicketSystem\Admin\Controller;

use Akeeba\TicketSystem\Admin\Helper\ComponentParams;
use Akeeba\TicketSystem\Admin\Helper\Credits;
use Akeeba\TicketSystem\Admin\Helper\Html;
use Akeeba\TicketSystem\Admin\Helper\Permissions;
use Akeeba\TicketSystem\Admin\Model\Tickets;
use FOF30\Controller\DataController;
use JText;

defined('_JEXEC') or die;

class Ticket extends DataController
{
	use TraitSaveUserTags;

    /**
     * Makes a ticket public
     */
    public function public_publish()
    {
        return $this->setvisibility(1);
    }

    /**
     * Makes a ticket private
     */
    public function public_unpublish()
    {
        return $this->setvisibility(0);
    }

    public function setvisibility($value)
    {
        /** @var Tickets $model */
        $model = $this->getModel();

        if(!$model->getId())
        {
            $this->getIDsFromRequest($model);
        }

        $data  = $model->getData();
        $perms = Permissions::getPrivileges($model);

        // I can't edit the state and I'm not a manager? Let's stop here
        if(!$perms['edit.state'] && !$perms['admin'])
        {
            return false;
        }

        $data['public'] = (int) $value;

        $url = 'index.php?option=com_ats&view=Tickets';

        if($customURL = $this->input->getBase64('returnurl', ''))
        {
            $url = base64_decode($customURL);
        }

        try
        {
            $model->save($data);

            $this->setRedirect($url);
        }
        catch(\Exception $e)
        {
            $this->setRedirect($url, $e->getMessage(), 'error');
        }

        return true;
    }

    public function assign()
    {
        $this->csrfProtection();

        /** @var Tickets $model */
        $model = $this->getModel();

        if(!$model->getId())
        {
            $this->getIDsFromRequest($model);
        }

        $data = $model->getData();

        $container = $this->container;
        $perms     = Permissions::getPrivileges($model);

        if(!$perms['admin'])
        {
            return false;
        }

        $assigned_to = $this->input->getInt('assigned_to', 0);
        $data['assigned_to'] = $assigned_to;

        if($assigned_to)
        {
            $return['assigned'] = $container->platform->getUser($assigned_to)->name;
        }
        else
        {
            $return['assigned'] = JText::_('COM_ATS_TICKETS_UNASSIGNED');
        }

        try
        {
            $model->save($data);
            $return['result'] = true;
        }
        catch(\Exception $e)
        {
            $return['result'] = false;
        }

        $container->platform->importPlugin('ats');
        $container->platform->runPlugins('onATSassign', array($model));

        echo json_encode($return);

        $container->platform->closeApplication();
    }

    public function ajax_set_status()
    {
        $this->csrfProtection();

        $id		  = $this->input->getInt('id');
        $status   = $this->input->getCmd('status');
        $standard = array('O', 'C', 'P');
        $custom   = ComponentParams::getCustomTicketStatuses();

        if(!$id)
        {
            echo json_encode(array('err' => JText::_('COM_ATS_TICKET_INVALID_ID')));

            return false;
        }

        if(!in_array($status, $standard) && !isset($custom[$status]))
        {
            echo json_encode(array('err' => JText::_('COM_ATS_TICKET_INVALID_STATE')));

            return false;
        }

        $model = $this->getModel();
        $model->find($id);

        $data['status'] = $status;

        try
        {
            $model->save($data);

            if(in_array($status, $standard))
            {
                $deco  = JText::_('COM_ATS_TICKETS_STATUS_'.$status);
            }
            else
            {
                $deco  = $custom[$status];
            }

            $class = Html::getStatusClass($status);

            $result = array('msg' => $deco, 'ats_class' => $class);
        }
        catch(\Exception $e)
        {
            $result = array('err' => $e->getMessage());
        }

        echo json_encode($result);

        $this->container->platform->closeApplication();
    }

    /**
     * Charges again some credits for a ticket
     */
    public function creditscharge()
    {
        $this->csrfProtection();

        $status = false;

        /** @var \Akeeba\TicketSystem\Admin\Model\Tickets $model */
        $model = $this->getModel();

        if(!$model->getId())
        {
            $this->getIDsFromRequest($model);
        }

        $ticket = $model->find();

        if($ticket->ats_ticket_id)
        {
            $platform = $this->container->platform;

            if(
                !$platform->authorise('core.manage','com_ats.category.'.$ticket->catid) &&
                !$platform->authorise('core.manage','com_ats')
            ) {
                return false;
            }

            Credits::chargeCredits($ticket->created_by, $ticket->catid, $ticket->ats_ticket_id, 0, true, $ticket->public);
            $status = true;
        }

        $url = 'index.php?option=com_ats&view=Ticket&id='.$ticket->ats_ticket_id;

        if($customURL = $this->input->getString('returnurl',''))
        {
            $url = base64_decode($customURL);
        }

        if(!$status)
        {
            $this->setRedirect($url, JText::_('COM_ATS_TICKETS_CREDITS_CHARGE_ERROR'), 'error');
        }
        else
        {
            $this->setRedirect($url, JText::_('COM_ATS_TICKETS_CREDITS_CHARGE_SUCCESS'));
        }
    }

    /**
     * Refunds the credits charged for a ticket
     */
    public function creditsrefund()
    {
        $this->csrfProtection();

        $status = false;

        /** @var \Akeeba\TicketSystem\Admin\Model\Tickets $model */
        $model = $this->getModel();

        if(!$model->getId())
        {
            $this->getIDsFromRequest($model);
        }

        $ticket = $model->find();

        if($ticket->ats_ticket_id)
        {
            $platform = $this->container->platform;

            if(
                !$platform->authorise('core.manage','com_ats.category.'.$ticket->catid) &&
                !$platform->authorise('core.manage','com_ats')
            ) {
                return false;
            }

            Credits::refundCredits($ticket->catid, $ticket->ats_ticket_id, 0, 'ticket');

            $status = true;
        }

        $url = 'index.php?option=com_ats&view=Ticket&id='.$ticket->ats_ticket_id;

        if($customURL = $this->input->getString('returnurl',''))
        {
            $url = base64_decode($customURL);
        }

        if(!$status)
        {
            $this->setRedirect($url, JText::_('COM_ATS_TICKETS_CREDITS_REFUND_ERROR'), 'error');
        }
        else
        {
            $this->setRedirect($url, JText::_('COM_ATS_TICKETS_CREDITS_REFUND_SUCCESS'));
        }
    }

    protected function onAfterSave()
    {
        $this->saveUserTags();
    }

    protected function onAfterApply()
    {
        $this->saveUserTags();
    }

    protected function onAfterSavenew()
    {
        $this->saveUserTags();
    }
}