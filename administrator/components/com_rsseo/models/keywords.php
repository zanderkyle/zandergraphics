<?php
/**
* @version 1.0.0
* @package RSSEO! 1.0.0
* @copyright (C) 2009-2012 www.rsjoomla.com
* @license GPL, http://www.gnu.org/copyleft/gpl.html
*/

defined('_JEXEC') or die();
jimport( 'joomla.application.component.model' );

class rsseoModelKeywords extends JModelList
{
	/**
	 * Constructor.
	 *
	 * @param	array	An optional associative array of configuration settings.
	 * @see		JController
	 * @since	1.6
	 */
	public function __construct($config = array()) {
		if (empty($config['filter_fields'])) {
			$config['filter_fields'] = array(
				'id', 'keyword', 'importance',
				'position', 'date'
			);
		}

		parent::__construct($config);
	}
	
	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @return	void
	 * @since	1.6
	 */
	protected function populateState($ordering = null, $direction = null) {
		$this->setState('filter.search', $this->getUserStateFromRequest($this->context.'.filter.search', 'filter_search'));
		$this->setState('filter.importance', $this->getUserStateFromRequest($this->context.'.filter.importance', 'filter_importance'));
		
		// List state information.
		parent::populateState('importance', 'DESC');
	}
	
	/**
	 * Build an SQL query to load the list data.
	 *
	 * @return	JDatabaseQuery
	 * @since	1.6
	 */
	protected function getListQuery() {
		$db 	= JFactory::getDBO();
		$query 	= $db->getQuery(true);
		
		// Select fields
		$query->select('*');
		
		// Select from table
		$query->from($db->quoteName('#__rsseo_keywords'));
		
		// Filter by importance
		if ($importance = $this->getState('filter.importance')) {
			$query->where($db->quoteName('importance').' = '.$db->quote($importance));
		}
		
		// Filter by search in title
		$search = $this->getState('filter.search');
		if (!empty($search)) {
			$search = $db->Quote('%'.$db->escape($search, true).'%');
			$query->where($db->quoteName('keyword').' LIKE '.$search.' ');
		}
		
		// Add the list ordering clause
		$listOrdering = $this->getState('list.ordering', 'importance');
		$listDirn = $db->escape($this->getState('list.direction', 'DESC'));
		$query->order($db->quoteName($listOrdering).' '.$listDirn);

		return $query;
	}
	
	/**
	 * Method to get the items list.
	 *
	 * @return	mixed	An array of data items on success, false on failure.
	 * @since	1.6.1
	 */
	public function getItems() {
		$items	= parent::getItems();
		
		foreach ($items as $i => $item) {
			if (!empty($item->lastposition)) {
				if ($item->position > $item->lastposition)
					$items[$i]->badge = 'important';
				else if ($item->position < $item->lastposition) 
					$items[$i]->badge = 'success';
				else if ($item->position == $item->lastposition)
					$items[$i]->badge = '';
			} else $items[$i]->badge = '';
		}
		
		return $items;
	}
}