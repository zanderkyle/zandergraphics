<?php
/**
 * @package   AkeebaTicketSystem
 * @copyright Copyright (c)2011-2015 Nicholas K. Dionysopoulos
 * @license   GNU General Public License version 3, or later
 */

namespace Akeeba\TicketSystem\Site\Controller;

use Akeeba\TicketSystem\Admin\Helper\Permissions;
use FOF30\Container\Container;
use FOF30\Controller\DataController;

defined('_JEXEC') or die;

class Juser extends DataController
{
	public function __construct(Container $container, array $config = array())
	{
		$this->cacheableTasks = [];

		parent::__construct($container, $config);
	}


	public function execute($task)
    {
        $task = 'browse';

        return parent::execute($task);
    }

    protected function onBeforeBrowse()
    {
        $cat = $this->input->getInt('category', 0);

        return Permissions::isManager($cat);
    }
}