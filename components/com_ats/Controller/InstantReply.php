<?php
/**
 * @package   AkeebaTicketSystem
 * @copyright Copyright (c)2011-2015 Nicholas K. Dionysopoulos
 * @license   GNU General Public License version 3, or later
 */

namespace Akeeba\TicketSystem\Site\Controller;

use Akeeba\TicketSystem\Site\Model\Categories;
use FOF30\Container\Container;
use FOF30\Controller\DataController;

defined('_JEXEC') or die;

class InstantReply extends DataController
{
    public function __construct(Container $container, array $config = array())
    {
        $config['cacheableTasks'] = array();

        parent::__construct($container, $config);
    }

    protected function onBeforeBrowse()
    {
        // Get category information
        $category_id = $this->input->getInt('catid',0);
        /** @var Categories $catModel */
        $catModel    = $this->container->factory->model('Categories')->tmpInstance();
        $category    = $catModel->category($category_id)->get(true)->first();

        if(!$category)
        {
            return false;
        }

        $params = new \JRegistry();
        $params->loadString($category->params, 'JSON');

        $docimportCategories = $params->get('dicats',array());

        $this->getModel()->setState('dicats', $docimportCategories);

        return true;
    }
}