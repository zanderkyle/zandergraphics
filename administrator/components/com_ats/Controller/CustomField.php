<?php
/**
 * @package   AkeebaTicketSystem
 * @copyright Copyright (c)2011-2015 Nicholas K. Dionysopoulos
 * @license   GNU General Public License version 3, or later
 */

namespace Akeeba\TicketSystem\Admin\Controller;

defined('_JEXEC') or die;

use FOF30\Controller\DataController;

class CustomField extends DataController
{
    /**
     * We are telling the model to not perform additional checks on the linked category, since we are working in the
     * browser view and we're not passing all the required info (for example we are reordering or unpublishing a record)
     */
    protected function onBeforeSaveorder()
    {
        /** @var \Akeeba\TicketSystem\Admin\Model\CustomFields $model */
        $model = $this->getModel();
        $model->preventCatCheck = true;
    }

    protected function onAfterSaveorder()
    {
        /** @var \Akeeba\TicketSystem\Admin\Model\CustomFields $model */
        $model = $this->getModel();
        $model->preventCatCheck = false;
    }

    protected function onBeforePublish()
    {
        /** @var \Akeeba\TicketSystem\Admin\Model\CustomFields $model */
        $model = $this->getModel();
        $model->preventCatCheck = true;
    }

    protected function onAfterPublish()
    {
        /** @var \Akeeba\TicketSystem\Admin\Model\CustomFields $model */
        $model = $this->getModel();
        $model->preventCatCheck = false;
    }

    protected function onBeforeUnpublish()
    {
        /** @var \Akeeba\TicketSystem\Admin\Model\CustomFields $model */
        $model = $this->getModel();
        $model->preventCatCheck = true;
    }

    protected function onAfterUnpublish()
    {
        /** @var \Akeeba\TicketSystem\Admin\Model\CustomFields $model */
        $model = $this->getModel();
        $model->preventCatCheck = false;
    }
}