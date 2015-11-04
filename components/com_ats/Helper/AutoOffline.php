<?php
/**
 * @package   AkeebaTicketSystem
 * @copyright Copyright (c)2011-2015 Nicholas K. Dionysopoulos
 * @license   GNU General Public License version 3, or later
 */

namespace Akeeba\TicketSystem\Site\Helper;

use Akeeba\TicketSystem\Admin\Helper\ComponentParams;
use FOF30\Container\Container;

defined('_JEXEC') or die;


class AutoOffline
{
    /**
     * Processes the automatic off-line schedule definitions and brings the
     * ticket system on-line / off-line based on your schedule. If no definition
     * matches the ticket system is brought on-line. If no definition is set
     * then the off-line status remains in its current, manual state.
     *
     * @return void
     */
    public static function processAutoOffline()
    {
        // Get off line schedule definitions
        $container = Container::getInstance('com_ats');
        $platform  = $container->platform;

        $db        = $container->db;

        $query = $db->getQuery(true)
                    ->select('*')
                    ->from($db->qn('#__ats_offlineschedules'))
                    ->where($db->qn('enabled') . ' = ' . $db->q(1))
                    ->order($db->qn('ordering') . ' ASC');
        $definitions = $db->setQuery($query)->loadObjectList();

        // Quit if there are no definitions
        if (empty($definitions))
        {
            return;
        }

        // Default state is no tickets = off and no replies = off
        $nonewtickets = false;
        $noreplies    = false;

        // Get the current time, weekday, day, month and year
        $jDate = $platform->getDate();

        $hour		= $jDate->format('H');
        $minute		= $jDate->format('i');
        $second		= $jDate->format('s');
        $weekday	= $jDate->format('w');
        $day		= $jDate->format('d');
        $month		= $jDate->format('m');
        $year		= $jDate->format('Y');

        $jNow = $platform->getDate($hour . ':' . $minute . ':' . $second);

        // Loop through all definitions
        foreach ($definitions as $def)
        {
            // Break down the definition date stuff into arrays
            $weekdays	= explode(',', $def->weekdays);
            $days		= explode(',', $def->days);
            $months		= explode(',', $def->months);
            $years		= explode(',', $def->years);

            // Check if we are on the correct weekday
            if (!empty($weekdays) && !in_array('*', $weekdays))
            {
                if (!in_array($weekday, $weekdays))
                {
                    continue;
                }
            }

            // Check if we are on the correct weekday
            if (!empty($days) && !in_array('0', $days) && !in_array('*', $days))
            {
                if (!in_array($day, $days))
                {
                    continue;
                }
            }

            // Check if we are on the correct weekday
            if (!empty($months) && !in_array('*', $months) && !in_array('0', $months))
            {
                if (!in_array($month, $months))
                {
                    continue;
                }
            }

            // Check if we are on the correct weekday
            if (!empty($years) && !in_array('*', $years) && !in_array('0', $years))
            {
                if (!in_array($year, $years))
                {
                    continue;
                }
            }

            // Check the time
            $jStart = $platform->getDate($def->timestart);
            $jEnd   = $platform->getDate($def->timeend);

            if (($jStart->toUnix() <= $jNow->toUnix()) && ($jEnd->toUnix() >= $jNow->toUnix()))
            {
                if ($def->notickets)
                {
                    $nonewtickets = true;
                }
                if ($def->noreplies)
                {
                    $noreplies = true;
                }
            }

            // If both no tickets and no replies are set to true break the loop
            if ($nonewtickets && $noreplies)
            {
                break;
            }
        }

        // Get the existing values of no tickets and no replies.
        $oldNoNewTickets = ComponentParams::getParam('nonewtickets', 0);
        $oldNoReplies    = ComponentParams::getParam('noreplies', 0);

        // If they differ, set them in the component configuration and save it.
        if (($oldNoNewTickets != $nonewtickets) || ($oldNoReplies != $noreplies))
        {
            ComponentParams::setParam('nonewtickets', $nonewtickets ? 1 : 0);
            ComponentParams::setParam('noreplies', $noreplies ? 1 : 0);
        }
    }
}