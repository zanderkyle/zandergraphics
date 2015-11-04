<?php
/**
* @package RSFirewall!
* @copyright (C) 2009-2014 www.rsjoomla.com
* @license GPL, http://www.gnu.org/licenses/gpl-2.0.html
*/

defined('_JEXEC') or die('Restricted access');

class RSFirewallModelLogs extends JModelList
{
	public function __construct($config = array()) {
		if (empty($config['filter_fields'])) {
			$config['filter_fields'] = array(
				'logs.level', 'logs.date', 'logs.ip', 'logs.user_id', 'logs.username', 'logs.page', 'logs.referer'
			);
		}

		parent::__construct($config);
	}
	
	protected function getListQuery() {
		$db 	= JFactory::getDBO();
		$query 	= $db->getQuery(true);
		
		// get filtering states
		$search = $this->getState('filter.search');
		$level 	= $this->getState('filter.level');
		
		$query	->select($db->qn('logs').'.*')
				->select($db->qn('#__rsfirewall_lists').'.'.$db->qn('type'))
				->select($db->qn('#__rsfirewall_lists').'.'.$db->qn('id', 'listId'))
				->from($db->qn('#__rsfirewall_logs', 'logs'))
				->join('LEFT', $db->qn('#__rsfirewall_lists').' ON ('.$db->qn('logs').'.'.$db->qn('ip').' = '.$db->qn('#__rsfirewall_lists').'.'.$db->qn('ip').')');
		// search
		if ($search != '') {
			$search = $db->q('%'.str_replace(' ', '%', $db->escape($search, true)).'%', false);
			$like 	= array();
			$like[] = $db->qn('logs.ip').' LIKE '.$search;
			$like[] = $db->qn('logs.user_id').' LIKE '.$search;
			$like[] = $db->qn('logs.username').' LIKE '.$search;
			$like[] = $db->qn('logs.page').' LIKE '.$search;
			$like[] = $db->qn('logs.referer').' LIKE '.$search;
			$query->where('('.implode(' OR ', $like).')');
		}
		// level
		if ($level != '') {
			$query->where($db->qn('logs.level').'='.$db->q($level));
		}
		
		// order by
		$query->order($db->escape($this->getState('list.ordering', 'logs.date')).' '.$db->escape($this->getState('list.direction', 'desc')));
		
		return $query;
	}
	
	protected function populateState($ordering = null, $direction = null) {
		$this->setState('filter.search', $this->getUserStateFromRequest($this->context.'.filter.search', 'filter_search'));
		$this->setState('filter.level',  $this->getUserStateFromRequest($this->context.'.filter.level',  'filter_level'));
		
		// List state information.
		parent::populateState('logs.date', 'desc');
	}
	
	public function getLevels()
	{
		return array(
			JHtml::_('select.option', 'low', JText::_('COM_RSFIREWALL_LEVEL_LOW')),
			JHtml::_('select.option', 'medium', JText::_('COM_RSFIREWALL_LEVEL_MEDIUM')),
			JHtml::_('select.option', 'high', JText::_('COM_RSFIREWALL_LEVEL_HIGH')),
			JHtml::_('select.option', 'critical', JText::_('COM_RSFIREWALL_LEVEL_CRITICAL'))
		);
	}
	
	public function getIsJ30() {
		$jversion = new JVersion();
		return $jversion->isCompatible('3.0');
	}
	
	public function getFilterBar() {
		require_once JPATH_COMPONENT.'/helpers/adapters/filterbar.php';
		
		$options = array();
		$options['search'] = array(
			'label' => JText::_('JSEARCH_FILTER'),
			'value' => $this->getState('filter.search')
		);
		$options['limitBox']  = $this->getPagination()->getLimitBox();
		$options['listDirn']  = $this->getState('list.direction', 'desc');
		$options['listOrder'] = $this->getState('list.ordering', 'logs.date');
		$options['sortFields'] = array(
			JHtml::_('select.option', 'logs.level', JText::_('COM_RSFIREWALL_ALERT_LEVEL')),
			JHtml::_('select.option', 'logs.date', JText::_('COM_RSFIREWALL_LOG_DATE_EVENT')),
			JHtml::_('select.option', 'logs.ip', JText::_('COM_RSFIREWALL_LOG_IP_ADDRESS')),
			JHtml::_('select.option', 'logs.user_id', JText::_('COM_RSFIREWALL_LOG_USER_ID')),
			JHtml::_('select.option', 'logs.username', JText::_('COM_RSFIREWALL_LOG_USERNAME')),
			JHtml::_('select.option', 'logs.page', JText::_('COM_RSFIREWALL_LOG_PAGE')),
			JHtml::_('select.option', 'logs.referer', JText::_('COM_RSFIREWALL_LOG_REFERER'))
		);
		$options['rightItems'] = array(
			array(
				'input' => '<select name="filter_level" class="inputbox" onchange="this.form.submit()">'."\n"
						   .'<option value="">'.JText::_('COM_RSFIREWALL_SELECT_LEVEL').'</option>'."\n"
						   .JHtml::_('select.options', $this->getLevels(), 'value', 'text', $this->getState('filter.level'))."\n"
						   .'</select>'
			)
		);
		
		$bar = new RSFilterBar($options);
		
		return $bar;
	}
	
	public function getSideBar() {
		require_once JPATH_COMPONENT.'/helpers/toolbar.php';
		
		RSFirewallToolbarHelper::addFilter(
			JText::_('COM_RSFIREWALL_SELECT_LEVEL'),
			'filter_level',
			JHtml::_('select.options', $this->getLevels(), 'value', 'text', $this->getState('filter.level'))
		);
		
		return RSFirewallToolbarHelper::render();
	}
}