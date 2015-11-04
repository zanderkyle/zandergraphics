<?php
/**
* @version 1.0.0
* @package RSSeo! 1.0.0
* @copyright (C) 2009-2012 www.rsjoomla.com
* @license GPL, http://www.gnu.org/copyleft/gpl.html
*/

defined('_JEXEC') or die();
 
jimport( 'joomla.application.component.model' );

class rsseoModelRedirects extends JModelList
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
				'id', 'from', 'to',
				'type', 'published'
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
		$this->setState('filter.published', $this->getUserStateFromRequest($this->context.'.filter.published', 'filter_published', ''));
		
		// List state information.
		parent::populateState('id', 'ASC');
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
		$query->from($db->quoteName('#__rsseo_redirects'));
		
		// Filter by published state
		$published = $this->getState('filter.published');
		if (is_numeric($published)) {
			$query->where($db->quoteName('published').' = ' . (int) $published);
		}
		elseif ($published === '') {
			$query->where('('.$db->quoteName('published').' = 0 OR '.$db->quoteName('published').' = 1)');
		}
		
		// Filter by search in title
		$search = $this->getState('filter.search');
		if (!empty($search)) {
			$search = $db->Quote('%'.$db->escape($search, true).'%');
			$query->where($db->quoteName('from').' LIKE '.$search.' OR '.$db->quoteName('to').' LIKE '.$search.' ');
		}
		
		// Add the list ordering clause
		$listOrdering = $this->getState('list.ordering', 'id');
		$listDirn = $db->escape($this->getState('list.direction', 'ASC'));
		$query->order($db->quoteName($listOrdering).' '.$listDirn);

		return $query;
	}
}