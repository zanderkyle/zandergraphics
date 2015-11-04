<?php
/**
 * @package   AkeebaTicketSystem
 * @copyright Copyright (c)2011-2015 Nicholas K. Dionysopoulos
 * @license   GNU General Public License version 3, or later
 */

namespace Akeeba\TicketSystem\Admin\Controller;

defined('_JEXEC') or die;

use FOF30\Container\Container;
use FOF30\Controller\Controller;

class Updates extends Controller
{
	public function __construct(Container $container, array $config = array())
	{
		parent::__construct($container, $config);

		$this->registerDefaultTask(array('force'));
		$this->cacheableTasks = array();
	}

	public function force()
	{
		/** @var  $model \Akeeba\TicketSystem\Admin\Model\Updates */
		$model = $this->getModel();

		$model->getUpdates(true);

		$url = 'index.php?option=com_ats';
		$msg = \JText::_('AKEEBA_COMMON_UPDATE_INFORMATION_RELOADED');
		$this->setRedirect($url, $msg);
	}
}