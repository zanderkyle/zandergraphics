<?php
/**
 * @package   AkeebaTicketSystem
 * @copyright Copyright (c)2011-2015 Nicholas K. Dionysopoulos
 * @license   GNU General Public License version 3, or later
 */

namespace Akeeba\TicketSystem\Admin\Controller;

use FOF30\Container\Container;
use FOF30\Controller\DataController;

defined('_JEXEC') or die;

class TicketStatistic extends DataController
{
    public function __construct(Container $container, array $config = array())
    {
        parent::__construct($container, $config);

        $this->registerTask('showspared', 'browse');
    }
}