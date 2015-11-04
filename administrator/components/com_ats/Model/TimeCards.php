<?php
/**
 * @package   AkeebaTicketSystem
 * @copyright Copyright (c)2011-2015 Nicholas K. Dionysopoulos
 * @license   GNU General Public License version 3, or later
 */
namespace Akeeba\TicketSystem\Admin\Model;

defined('_JEXEC') or die;

class TimeCards extends Posts
{
    public function buildQuery($overrideLimits = false)
    {
        $db = $this->getDbo();

        $query = parent::buildQuery($overrideLimits);

        if($this->getState('sumtimespent', null, 'int'))
        {
            $this->addKnownField('tot_timespent');
            $this->addKnownField('poster_name', null, 'text');

            $query->select(array(
                'SUM('.$db->qn('timespent').' / 60) as tot_timespent',
                $db->qn('users').'.'.$db->qn('name').' as poster_name'
            ));

            // Let's get the name of the poster
            $query->innerJoin($db->qn('#__users').' AS '.$db->qn('users').
                ' ON '.$db->qn('created_by').' = '.$db->qn('users').'.'.$db->qn('id'));

            // Get replies with a stored time spent only. This will exclude managers that never posted a post with a
            // time spent value, but we can't work on users since we should run multiple queries to check if the user
            // is a manager for the current category or not.
            $query->where($db->qn('timespent').' > 0');

            $query->group($db->qn('created_by'));
        }

        return $query;
    }
}