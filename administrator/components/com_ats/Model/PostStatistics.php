<?php
/**
 * @package   AkeebaTicketSystem
 * @copyright Copyright (c)2011-2015 Nicholas K. Dionysopoulos
 * @license   GNU General Public License version 3, or later
 */
namespace Akeeba\TicketSystem\Admin\Model;

defined('_JEXEC') or die;

class PostStatistics extends Posts
{
    public function buildQuery($overrideLimits = false)
    {
        $db = $this->getDbo();

        $query = parent::buildQuery($overrideLimits);

        if($this->getState('groupbydate') == 1)
        {
            $query->clear('select');

            $query->select(array(
                'DATE('.$db->qn('created_on').') AS '.$db->qn('date'),
                'COUNT('.$db->qn('ats_post_id').') AS '.$db->qn('posts')
            ));

            $query->group('DATE('.$db->qn('created_on').')');

            $this->addKnownField('date');
            $this->addKnownField('posts');
        }

        return $query;
    }
}