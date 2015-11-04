<?php
/**
 * @package ats
 * @copyright Copyright (c)2011-2014 Nicholas K. Dionysopoulos / AkeebaBackup.com
 * @license GNU GPL v3 or later
 */
namespace Akeeba\TicketSystem\Site\Model;

use Akeeba\TicketSystem\Admin\Model\DefaultDataModel;
use FOF30\Container\Container;

defined('_JEXEC') or die();

class Jusers extends DefaultDataModel
{
    public function __construct(Container $container, array $config = array())
    {
        $config['tableName']   = '#__users';
        $config['idFieldName'] = 'id';

        parent::__construct($container, $config);
    }

	public function buildQuery($overrideLimits = false)
    {
		$db = $this->getDbo();

		$query = $db->getQuery(true)
                    ->select('*')
                    ->from($db->qn('#__users'));

		if($username = $this->getState('username',null,'raw'))
        {
			$query->where($db->qn('username').' = '.$db->q($username));
		}

		if($userid = $this->getState('user_id',null,'int'))
        {
			$query->where($db->qn('id').' = '.$db->q($userid));
		}

		if($email = $this->getState('email',null,'raw'))
        {
			$query->where($db->qn('email').' = '.$db->q($email));
		}

		$block = $this->getState('block',null,'int');

		if(!is_null($block))
        {
			$query->where($db->qn('block').' = '.$db->q($block));
		}

		if($search = $this->getState('search',null))
		{
			$query->where(
				'('.
				'('.$db->qn('username').' LIKE '.$db->q('%'.$db->escape($search).'%', false).') OR '.
				'('.$db->qn('name').' LIKE '.$db->q('%'.$db->escape($search).'%', false).') OR '.
				'('.$db->qn('email').' LIKE '.$db->q('%'.$db->escape($search).'%', false).') '.
				')'
			);
		}

		$order = $this->getState('filter_order', 'id', 'cmd');

		if(!in_array($order, array_keys($this->knownFields)))
        {
            $order = 'id';
        }

		$dir = $this->getState('filter_order_Dir', 'DESC', 'cmd');
		$query->order($order.' '.$dir);

		return $query;
	}
}