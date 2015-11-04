<?php
/**
 * @package   AkeebaTicketSystem
 * @copyright Copyright (c)2011-2015 Nicholas K. Dionysopoulos
 * @license   GNU General Public License version 3, or later
 */

namespace Akeeba\TicketSystem\Admin\Form\Field;

use Akeeba\TicketSystem\Admin\Helper\Select;
use FOF30\Form\Field\GenericList;

defined('_JEXEC') or die;

class CustomfieldCats extends GenericList
{
    protected function getInput()
    {
        /** @var \Akeeba\TicketSystem\Admin\Model\CustomFields $item */
        $item = $this->form->getModel();

        foreach($item->cats as $cat)
        {
            $this->value[] = $cat->id;
        }

        return parent::getInput();
    }

    protected function getOptions()
    {
        $options = Select::getCategoriesOptions();

        return $options;
    }
}