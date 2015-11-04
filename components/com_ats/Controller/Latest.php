<?php
/**
 * @package   AkeebaTicketSystem
 * @copyright Copyright (c)2011-2015 Nicholas K. Dionysopoulos
 * @license   GNU General Public License version 3, or later
 */
namespace Akeeba\TicketSystem\Site\Controller;

use Akeeba\TicketSystem\Admin\Helper\Permissions;
use Akeeba\TicketSystem\Site\Model\Tickets;
use FOF30\Container\Container;
use FOF30\Controller\DataController;

defined('_JEXEC') or die;

class Latest extends DataController
{
    public function __construct(Container $container, array $config = array())
    {
        parent::__construct($container, $config);

        $this->modelName      = 'Tickets';
        $this->cacheableTasks = array();
    }

    public function execute($task)
    {
        $task = 'browse';

        return parent::execute($task);
    }

    protected function onBeforeBrowse()
    {
        $platform = $this->container->platform;

        if($platform->getUser()->guest)
        {
            // Not a logged in user, redirect to login page
            $returl = base64_encode(\JUri::getInstance()->toString());
            $url    = \JRoute::_('index.php?option=com_users&view=login&return='.$returl, false);

            \JFactory::getApplication()->redirect($url, \JText::_('JLIB_APPLICATION_ERROR_ACCESS_FORBIDDEN'));
        }

        // Am I manager of at least one category? If not, stop here
        if(!Permissions::getManagerCategories())
        {
            return false;
        }

        /** @var Tickets $model */
        $model = $this->getModel();
        $model->viewName = $this->viewName;

        $this->registerCacheParams();
    }

    private function registerCacheParams()
    {
        // Let's register our params for url caching
        $urlparams = array(
            'assignedtome'   => 'BOOL',
            'categories'     => 'ARRAY',
            'filterNewest'   => 'BOOL',
            'frontendfilter' => 'BOOL',
            'alias'          => 'STRING',
            'status'         => 'CMD',
            'catid'          => 'INT',
            'public'         => 'INT',
            'modified_on'    => 'STRING',
            'status_array'   => 'ARRAY',
            'created_by'     => 'INT',
        );

        /** @var \JApplicationCms $app */
        $app = \JFactory::getApplication();

        $registeredurlparams = null;

        if (!empty($app->registeredurlparams))
        {
            $registeredurlparams = $app->registeredurlparams;
        }
        else
        {
            $registeredurlparams = new \stdClass;
        }

        foreach ($urlparams as $key => $value)
        {
            // Add your safe url parameters with variable type as value {@see JFilterInput::clean()}.
            $registeredurlparams->$key = $value;
        }

        $app->registeredurlparams = $registeredurlparams;
    }
}