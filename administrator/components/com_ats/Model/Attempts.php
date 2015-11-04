<?php
/**
 * @package ats
 * @copyright Copyright (c)2011-2014 Nicholas K. Dionysopoulos / AkeebaBackup.com
 * @license GNU GPL v3 or later
 */

namespace Akeeba\TicketSystem\Admin\Model;

use FOF30\Container\Container;

defined('_JEXEC') or die;

/**
 * Class TicketStatistics
 *
 * @property    int ticket_clicks       How many clicks on a Ticket were made for this attempt?
 * @property    int docimport_clicks    How many clicks on a DocImport page were made for this attempt?
 *
 * @method  $this   status_array($string)    String containing all the requested statuses
 * @method  $this   created_since($date)     created_on >= $date
 * @method  $this   created_until($date)     created_on <= $date
 *
 * @package Akeeba\TicketSystem\Admin\Model
 */
class Attempts extends DefaultDataModel
{
    public function __construct(Container $container, array $config = array())
    {
        parent::__construct($container, $config);

        $this->autoChecks = false;
    }

    public function getTotalsByCategory()
    {
        $db = $this->getDbo();

        $query = $db->getQuery(true)
                    ->select('COUNT(ats_attempt_id) as total, ats_category_id')
                    ->from($db->qn('#__ats_attempts'))
                    ->where($db->qn('ats_ticket_id').' = 0')
                    ->group($db->qn('ats_category_id'))
                    ->order($db->qn('ats_category_id'));

        $since = $this->getState('created_since');
        if(intval($since))
        {
            $since = $this->container->platform->getDate($since);
            $query->where($db->qn('created_on').' >= '.$db->q($since->toSql()));
        }

        $until = $this->getState('created_until');
        if(intval($until))
        {
            $until = $this->container->platform->getDate($until);
            $query->where($db->qn('created_on').' <= '.$db->q($until->toSql()));
        }

        $count = $db->setQuery($query)->loadObjectList('ats_category_id');

        return $count;
    }
}