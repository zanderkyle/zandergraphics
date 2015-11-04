<?php
/**
 * @package   AkeebaTicketSystem
 * @copyright Copyright (c)2011-2015 Nicholas K. Dionysopoulos
 * @license   GNU General Public License version 3, or later
 */

namespace Akeeba\TicketSystem\Admin\View\TicketStatistics;

defined('_JEXEC') or die;

class Json extends \FOF30\View\DataView\Json
{
    protected function onShowspared($tpl)
    {
        if (is_null($tpl))
        {
            $tpl = 'json';
        }

        echo $this->loadTemplate($tpl, true);

        return false;
    }
}