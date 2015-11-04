<?php
/**
 * @package   AkeebaTicketSystem
 * @copyright Copyright (c)2011-2015 Nicholas K. Dionysopoulos
 * @license   GNU General Public License version 3, or later
 */

namespace Akeeba\TicketSystem\Site\View\Latests;

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

        $categories = Permissions::getManagerCategories();

        // Apply custom filters for frontend
        $model->categories($categories)
                ->enabled(1)
                ->status('O')
                ->filterNewest(1)
                ->filter_order_Dir('ASC');

        parent::onBeforeBrowse();
    }
}