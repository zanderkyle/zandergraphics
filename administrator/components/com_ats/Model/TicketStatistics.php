<?php
/**
 * @package   AkeebaTicketSystem
 * @copyright Copyright (c)2011-2015 Nicholas K. Dionysopoulos
 * @license   GNU General Public License version 3, or later
 */

namespace Akeeba\TicketSystem\Admin\Model;

defined('_JEXEC') or die;

/**
 * Class TicketStatistics
 *
 * @method  $this   created_since($date)     created_on >= $date
 * @method  $this   created_until($date)     created_on <= $date
 *
 * @package Akeeba\TicketSystem\Admin\Model
 */
class TicketStatistics extends Tickets
{
    public function buildQuery($override = false)
    {
        $db = $this->getDbo();

        $query = parent::buildQuery($override);

        if($this->getState('groupbydate') == 1)
        {
            $query->clear('select');

            $query->select(array(
                'DATE('.$db->qn('created_on').') AS '.$db->qn('date'),
                'COUNT('.$db->qn('ats_ticket_id').') AS '.$db->qn('tickets')
            ));

            $query->group('DATE('.$db->qn('created_on').')');

            $this->addKnownField('date');
            $this->addKnownField('tickets');
        }

        return $query;
    }

    /**
     * Given an array of intervals, returns ratings according to such time intervals
     *
     * @param    array   $intervals  It must be formatted in this way:
     *                                  key   = key for later use (ie week)
     *                                  value = value to be passed to strtotime function (ie 7 days)
     *
     * @return   array   Return ratings grouped by user and indexed by the keys provided in the $interval param
     */
    public function getRatings($intervals = array())
    {
        $db      = $this->getDbo();
        $ratings = array();

        // Base query: grab only assigned and closed tickets
        $basequery = $db->getQuery(true)
            ->select(
                array(
                    'AVG(' . $db->qn('rating') . ') AS '. $db->qn('average'),
                    $db->qn('id'),
                    $db->qn('name')
                )
            )
            ->from($db->qn('#__ats_tickets'))
            ->innerJoin($db->qn('#__users').' ON ' . $db->qn('assigned_to') . ' = ' . $db->qn('id'))
            ->where($db->qn('assigned_to') . ' <> ' . $db->q(''))
            ->where($db->qn('status') . ' = ' . $db->quote('C'))
            ->where($db->qn('rating') . ' > ' . $db->q('0'))
            ->group($db->qn('assigned_to'));

        foreach($intervals as $key => $time)
        {
            $query = clone $basequery;

            if(!is_null($time))
            {
                $mDate = date('Y-m-d', strtotime('-'.$time));
                $jModified = $this->container->platform->getDate($mDate);
                $query->where($db->qn('modified_on') . ' >= ' . $db->quote($jModified->toSql()));
            }

            $rows = $db->setQuery($query)->loadObjectList();

            foreach($rows as $row)
            {
                $ratings[$row->id]['user'] = $row->name;
                $ratings[$row->id][$key]   = $row->average;
            }
        }

        return $ratings;
    }

    public function getTotalsByCategory()
    {
        $db = $this->getDbo();

        $query = $db->getQuery(true)
                    ->select('COUNT(ats_ticket_id) as total, catid')
                    ->from($db->qn('#__ats_tickets'))
                    ->group($db->qn('catid'))
                    ->order($db->qn('catid'));

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

        $count = $db->setQuery($query)->loadObjectList('catid');

        return $count;
    }
}