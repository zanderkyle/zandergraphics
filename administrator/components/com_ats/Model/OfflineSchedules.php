<?php
/**
 * @package   AkeebaTicketSystem
 * @copyright Copyright (c)2011-2015 Nicholas K. Dionysopoulos
 * @license   GNU General Public License version 3, or later
 */

namespace Akeeba\TicketSystem\Admin\Model;

use FOF30\Container\Container;

defined('_JEXEC') or die;

class OfflineSchedules extends DefaultDataModel
{
    public function __construct(Container $container, array $config = array())
    {
        parent::__construct($container, $config);

        $this->addBehaviour('Filters');

        $this->addSkipCheckField('ordering');
    }

    protected function setWeekdaysAttribute($value)
    {
        return $this->setAttributeForImplodedArray($value);
    }

    protected function getWeekdaysAttribute($value)
    {
        return $this->getAttributeForImplodedArray($value);
    }

    protected function setDaysAttribute($value)
    {
        return $this->setAttributeForImplodedArray($value);
    }

    protected function getDaysAttribute($value)
    {
        return $this->getAttributeForImplodedArray($value);
    }

    protected function setMonthsAttribute($value)
    {
        return $this->setAttributeForImplodedArray($value);
    }

    protected function getMonthsAttribute($value)
    {
        return $this->getAttributeForImplodedArray($value);
    }

    protected function setYearsAttribute($value)
    {
        return $this->setAttributeForImplodedArray($value);
    }

    protected function getYearsAttribute($value)
    {
        return $this->getAttributeForImplodedArray($value);
    }
}