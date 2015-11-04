<?php
/**
 * @package   AkeebaTicketSystem
 * @copyright Copyright (c)2011-2015 Nicholas K. Dionysopoulos
 * @license   GNU General Public License version 3, or later
 */

namespace Akeeba\TicketSystem\Site\Controller;

use FOF30\Container\Container;

defined('_JEXEC') or die;

class Post extends \Akeeba\TicketSystem\Admin\Controller\Post
{
	public function __construct(Container $container, array $config = array())
	{
		$this->cacheableTasks = [];

		parent::__construct($container, $config);
	}

}