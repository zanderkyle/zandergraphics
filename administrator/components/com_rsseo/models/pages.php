<?php
/**
* @version 1.0.0
* @package RSSeo! 1.0.0
* @copyright (C) 2009-2012 www.rsjoomla.com
* @license GPL, http://www.gnu.org/copyleft/gpl.html
*/

defined('_JEXEC') or die();
jimport( 'joomla.application.component.model' );

class rsseoModelPages extends JModelList
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
				'id', 'url', 'title',
				'level', 'grade', 'crawled',
				'date'
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
		$this->setState('filter.level', $this->getUserStateFromRequest($this->context.'.filter.level', 'filter_level', ''));
		$this->setState('filter.insitemap', $this->getUserStateFromRequest($this->context.'.filter.insitemap', 'filter_insitemap', ''));
		$this->setState('filter.md5title', JFactory::getApplication()->input->get('md5title'));
		$this->setState('filter.md5descr', JFactory::getApplication()->input->get('md5descr'));
		
		// List state information.
		parent::populateState('level', 'ASC');
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
		$query->from($db->quoteName('#__rsseo_pages'));
		
		// Filter by level.
		if ($level = $this->getState('filter.level')) {
			$query->where($db->quoteName('level').' = ' . (int) $level);
		}
		
		// Filter by sitemap.
		$insitemap = $this->getState('filter.insitemap');
		if (is_numeric($insitemap)) {
			$query->where($db->quoteName('insitemap').' = ' . (int) $insitemap);
		}
		
		// Filter by page title.
		if ($md5title = $this->getState('filter.md5title')) {
			$query->where('MD5('.$db->quoteName('title').') = ' . $db->quote($md5title));
		}
		
		// Filter by page description.
		if ($md5descr = $this->getState('filter.md5descr')) {
			$query->where('MD5('.$db->quoteName('description').') = ' . $db->quote($md5descr));
		}
		
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
			$query->where('('.$db->quoteName('url').' LIKE '.$search.' OR '.$db->quoteName('title').' LIKE '.$search.')');
		}
		
		// Add the list ordering clause
		$listOrdering = $this->getState('list.ordering', 'level');
		$listDirn = $db->escape($this->getState('list.direction', 'ASC'));
		$query->order($db->escape($listOrdering).' '.$listDirn);
		
		return $query;
	}
	
	/**
	 * Method to get the items list.
	 *
	 * @return	mixed	An array of data items on success, false on failure.
	 * @since	1.6.1
	 */
	public function getItems() {
		$db		= JFactory::getDBO();
		$query	= $db->getQuery(true);
		$items	= parent::getItems();
		
		foreach ($items as $i => $item) {
			switch($item->grade) {
				case ($item->grade >= 0 && $item->grade < 33): 
					$items[$i]->color = 'red'; 
				break;
				
				case ($item->grade >= 33 && $item->grade < 66):
					$items[$i]->color = 'orange'; 
				break;
				
				case -1:
					$items[$i]->color = '';
				break;
				
				default: 
					$items[$i]->color = 'green'; 
				break;
			}
		}
		
		return $items;
	}
}