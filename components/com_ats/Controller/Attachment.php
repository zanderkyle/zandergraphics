<?php
/**
 * @package   AkeebaTicketSystem
 * @copyright Copyright (c)2011-2015 Nicholas K. Dionysopoulos
 * @license   GNU General Public License version 3, or later
 */

namespace Akeeba\TicketSystem\Site\Controller;

use FOF30\Container\Container;

defined('_JEXEC') or die;

class Attachment extends \Akeeba\TicketSystem\Admin\Controller\Attachment
{
    public function __construct(Container $container, array $config = array())
    {
        $this->cacheableTasks = [];

        parent::__construct($container, $config);
    }


    public function execute($task)
    {
        $allowed = array(
            'default', 'unpublish', 'publish', 'remove', 'read'
        );

        // Only allow a small subset of available tasks
        if(!in_array($task, $allowed))
        {
            return false;
        }

        if ($task == 'default')
        {
            $task = $this->getCrudTask();
        }

        // There aren't such actions on frontend
        if(in_array($task , array('new', 'edit', 'read', 'add')))
        {
            $task = 'read';
        }

        return parent::execute($task);
    }
}