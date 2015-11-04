<?php
/**
 * @package   AkeebaTicketSystem
 * @copyright Copyright (c)2011-2015 Nicholas K. Dionysopoulos
 * @license   GNU General Public License version 3, or later
 */

namespace Akeeba\TicketSystem\Admin\View\Tickets;

defined('_JEXEC') or die;

class Csv extends \FOF30\View\DataView\Csv
{
    public function onBeforeBrowse()
    {
        // Let's force eager loading on joomla_categories relationship
        $model = $this->getModel();
        $model->with(array('joomla_category'));

        parent::onBeforeBrowse();
    }
}