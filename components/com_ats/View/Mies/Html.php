<?php
/**
 * @package   AkeebaTicketSystem
 * @copyright Copyright (c)2011-2015 Nicholas K. Dionysopoulos
 * @license   GNU General Public License version 3, or later
 */

namespace Akeeba\TicketSystem\Site\View\Mies;

use Akeeba\TicketSystem\Admin\Helper\Permissions;
use Akeeba\TicketSystem\Site\Model\Tickets;

defined('_JEXEC') or die;

class Html extends \FOF30\View\DataView\Html
{
    /** @var bool Is the current user a manager? */
    protected $isManager = false;

    protected function onBeforeBrowse()
    {
        /** @var Tickets $model */
        $model = $this->getModel();

        // Apply custom filters for frontend
        $model->status_array('O,C,P,1,2,3,4,5,6,7,8,9')
                ->enabled(1)
                ->filterNewest(1)
                ->filter_order_Dir('ASC');

        $this->isManager = Permissions::isManager();

        if($this->isManager)
        {
            $model->created_by($model->getState('userid', $this->container->platform->getUser()->id));
        }
        else
        {
            $model->created_by($this->container->platform->getUser()->id);
        }

        parent::onBeforeBrowse();
    }
}