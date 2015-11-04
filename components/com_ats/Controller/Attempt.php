<?php
/**
 * @package   AkeebaTicketSystem
 * @copyright Copyright (c)2011-2015 Nicholas K. Dionysopoulos
 * @license   GNU General Public License version 3, or later
 */

namespace Akeeba\TicketSystem\Site\Controller;

use Akeeba\TicketSystem\Site\Model\Attempts;
use FOF30\Container\Container;
use FOF30\Controller\DataController;

defined('_JEXEC') or die;

class Attempt extends DataController
{
    public function __construct(Container $container, array $config = array())
    {
        $this->cacheableTasks = [];

        parent::__construct($container, $config);
    }


    public function save()
    {
        /** @var Attempts $model */
        $model        = $this->getModel();
        $updateClicks = $this->input->getCmd('update_clicks');

        $this->getIDsFromRequest($model);

        if($model->getId() && $updateClicks)
        {
            if($updateClicks == 'docimport')
            {
                $model->docimport_clicks += 1;
            }
            elseif($updateClicks == 'ats')
            {
                $model->ticket_clicks +=1;
            }
        }

        parent::save();

        if($this->input->getCmd('format') == 'json')
        {
            echo json_encode($model->getData());

            $this->container->platform->closeApplication();
        }
    }
}