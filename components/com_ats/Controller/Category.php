<?php
/**
 * @package   AkeebaTicketSystem
 * @copyright Copyright (c)2011-2015 Nicholas K. Dionysopoulos
 * @license   GNU General Public License version 3, or later
 */

namespace Akeeba\TicketSystem\Site\Controller;

defined('_JEXEC') or die;

use FOF30\Controller\DataController;

class Category extends DataController
{
	protected function onBeforeBrowse()
	{
		$this->getModel()->setState('filter_order', 'lft');
		$this->getModel()->setState('filter_order_Dir', 'ASC');
	}
}