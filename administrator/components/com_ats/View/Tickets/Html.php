<?php
/**
 * @package   AkeebaTicketSystem
 * @copyright Copyright (c)2011-2015 Nicholas K. Dionysopoulos
 * @license   GNU General Public License version 3, or later
 */

namespace Akeeba\TicketSystem\Admin\View\Tickets;

use Akeeba\TicketSystem\Admin\Helper\Permissions;
use Akeeba\TicketSystem\Admin\Helper\Subscriptions;

defined('_JEXEC') or die;

/**
 * Class Html
 *
 * @property    \Akeeba\TicketSystem\Admin\Model\Tickets    $item
 *
 * @package Akeeba\TicketSystem\Admin\View\Tickets
 */
class Html extends \FOF30\View\DataView\Html
{
    protected $cache            = array();
    protected $hasSubscriptions = false;
    protected $showuserinfo     = false;
    protected $activesubs       = array();
    protected $inactivesubs     = array();
    protected $isManager        = false;

    protected function onBeforeBrowse()
    {
        // Let's force eager loading on joomla_categories relationship
        /** @var \Akeeba\TicketSystem\Admin\Model\Tickets $model */
        $model = $this->getModel();
        $model->with(array('joomla_category'));

        parent::onBeforeBrowse();
    }

    protected function onBeforeEdit()
    {
        parent::onBeforeEdit();

        $this->showuserinfo = true;
        $this->getContainer()->template->addCSS('media://com_ats/css/frontend.css');

        // Am I a full manager or I can simply view/edit the ticket?
        $this->isManager = Permissions::isManager($this->item->catid);

        // Do we have a subscriptions component?
        $this->hasSubscriptions = Subscriptions::hasSubscriptionsComponent();

        if($this->hasSubscriptions)
        {
            $user = $this->getContainer()->platform->getUser($this->item->created_by);
            $subs = Subscriptions::getSubscriptionsList($user);

            $this->activesubs   = $subs->active;
            $this->inactivesubs = $subs->inactive;
        }
    }
}