<?php
/**
 * @package   AkeebaTicketSystem
 * @copyright Copyright (c)2011-2015 Nicholas K. Dionysopoulos
 * @license   GNU General Public License version 3, or later
 */

namespace Akeeba\TicketSystem\Admin\Model;

use FOF30\Container\Container;

defined('_JEXEC') or die;

/**
 * Class Categories
 *
 * @property    int     $id             Primary key
 * @property    string  $title
 * @property    array   $params         Category params
 * @property    string  $description    Category description
 * @property    int     $level          Category level (depth)
 *
 * Filters:
 *
 * @method  $this   category($catid)    Filter by category ID
 * @method  $this   userid($id)         User used to filter categories by access
 * @method  $this   ignoreUser($flag)   Should I ignore the user access level?
 *
 * @package Akeeba\TicketSystem\Admin\Model
 */
class Categories extends DefaultDataModel
{
    public function __construct(Container $container, array $config = array())
    {
        $config['tableName']   = '#__categories';
        $config['idFieldName'] = 'id';

        parent::__construct($container, $config);

        $this->addBehaviour('Filters');
    }

    public function get($overrideLimits = false, $limitstart = 0, $limit = 0)
    {
        // Always show all the categories
        return parent::get(true, $limitstart, $limit);
    }

    public function buildQuery($override = false)
    {
        $db = $this->getDbo();

        $query = parent::buildQuery($override);

        $query->where($db->qn('extension').' = '.$db->q('com_ats'));
        $query->where($db->qn('published').' = '.$db->q(1));

        if($id = $this->getState('category', null, 'int'))
        {
            $query->where($db->qn('id').' = '.$db->q($id));
        }

        $userid = $this->getState('userid', null, 'int');
        $user	= $this->container->platform->getUser($userid);

        $fltIgnoreUser = $this->getState('ignoreUser', 0);

        // Do I have a valid user (so I'm not in CLI) AND I want to apply access level rules?
        if ($user && !$user->authorise('core.admin') && !$fltIgnoreUser)
        {
            $groups	= implode(',', $user->getAuthorisedViewLevels());
            $query->where('access IN ('.$groups.')');
        }

        // Apply custom ordering
        $query->clear('order');

        $listOrdering = $this->getState('filter_order', 'lft');
        $listDirn     = $db->escape($this->getState('filter_order_Dir', 'ASC'));

        if ($listOrdering == 'access')
        {
            $query->order('access '.$listDirn.', lft '.$listDirn);
        }
        else
        {
            $query->order($db->escape($listOrdering).' '.$listDirn);
        }

        return $query;
    }
}