<?php
/**
* @version 1.0.0
* @package RSSeo! 1.0.0
* @copyright (C) 2009-2012 www.rsjoomla.com
* @license GPL, http://www.gnu.org/copyleft/gpl.html
*/

defined('_JEXEC') or die();
jimport( 'joomla.application.component.model' );

class rsseoModelCompetitors extends JModelList
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
				'id', 'name', 'pagerank',
				'googleb', 'googlep', 'alexa',
				'bingb', 'bingp', 'technorati',
				'dmoz', 'date'
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
		$this->setState('filter.parent', $this->getUserStateFromRequest($this->context.'.filter.parent', 'filter_parent', 0));
		
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
		$query->from($db->quoteName('#__rsseo_competitors'));
		
		// Get parents only
		$parent = $this->getState('filter.parent');
		$query->where('`parent_id` = '.$parent);
		
		// Filter by search in title
		$search = $this->getState('filter.search');
		if (!empty($search)) {
			$search = $db->Quote('%'.$db->escape($search, true).'%');
			$query->where('`name` LIKE '.$search.' OR tags LIKE '.$search.' ');
		}
		
		// Add the list ordering clause
		$listOrdering = $this->getState('list.ordering', 'id');
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
		$config = rsseoHelper::getConfig();
		
		foreach ($items as $i => $item) {
			if (!$this->getState('filter.parent')) {			
				// Get history
				$query->clear();
				$query->select('*')->from('`#__rsseo_competitors`')->where('`parent_id` = '.(int) $item->id)->order('`date` DESC');
				$db->setQuery($query,0,2);
				$history = $db->loadObjectList();
				
				if(isset($history[1])) {
					$compare = $history[1]; 
				} else $compare = $history[0];
				
				if (empty($compare)) {
					$compare = $item;
				}
				
				// Google page rank
				if ($config->enable_pr) {
					if ($compare->pagerank < $item->pagerank) 
						$items[$i]->pagerankbadge = 'success';
					else if ($compare->pagerank > $item->pagerank)
						$items[$i]->pagerankbadge = 'important';
					else if ($compare->pagerank == $item->pagerank) 
						$items[$i]->pagerankbadge = '';
				} else $items[$i]->pagerankbadge = '';
				
				// Google pages
				if ($config->enable_googlep) {
					if ($compare->googlep < $item->googlep) 
						$items[$i]->googlepbadge = 'success';
					else if ($compare->googlep > $item->googlep)
						$items[$i]->googlepbadge = 'important';
					else if ($compare->googlep == $item->googlep) 
						$items[$i]->googlepbadge = '';
				} else $items[$i]->googlepbadge = '';
					
				// Google backlinks
				if ($config->enable_googleb) {
					if ($compare->googleb < $item->googleb) 
						$items[$i]->googlebbadge = 'success';
					else if ($compare->googleb > $item->googleb)
						$items[$i]->googlebbadge = 'important';
					else if ($compare->googleb == $item->googleb) 
						$items[$i]->googlebbadge = '';
				} else $items[$i]->googlebbadge = '';
				
				// Bing pages
				if ($config->enable_bingp) {
					if ($compare->bingp < $item->bingp) 
						$items[$i]->bingpbadge = 'success';
					else if ($compare->bingp > $item->bingp)
						$items[$i]->bingpbadge = 'important';
					else if ($compare->bingp == $item->bingp) 
						$items[$i]->bingpbadge = '';
				} else $items[$i]->bingpbadge = '';
				
				// Bing backlinks
				if ($config->enable_bingb) {
					if ($compare->bingb < $item->bingb) 
						$items[$i]->bingbbadge = 'success';
					else if ($compare->bingb > $item->bingb)
						$items[$i]->bingbbadge = 'important';
					else if ($compare->bingb == $item->bingb) 
						$items[$i]->bingbbadge = '';
				} else $items[$i]->bingbbadge = '';
					
				// Alexa page rank
				if ($config->enable_alexa) {
					if ($compare->alexa < $item->alexa) 
						$items[$i]->alexabadge = 'important';
					else if ($compare->alexa > $item->alexa)
						$items[$i]->alexabadge = 'success';
					else if ($compare->alexa == $item->alexa) 
						$items[$i]->alexabadge = '';
				} else $items[$i]->alexabadge = '';
				
				// Technorati rank
				if ($config->enable_tehnorati) {
					if ($compare->technorati < $item->technorati) 
						$items[$i]->technoratibadge = 'success';
					else if ($compare->technorati > $item->technorati)
						$items[$i]->technoratibadge = 'important';
					else if ($compare->technorati == $item->technorati) 
						$items[$i]->technoratibadge = '';
				} else $items[$i]->technoratibadge = '';
				
				//Dmoz
				if ($config->enable_dmoz) {
					if ($item->dmoz == -1)
						$items[$i]->dmozbadge = '';
					else if ($item->dmoz == 0)
						$items[$i]->dmozbadge = 'important';
					else if ($item->dmoz == 1)
						$items[$i]->dmozbadge = 'success';
				} else $items[$i]->dmozbadge = '';
			
			} else {
				$items[$i]->pagerankbadge = '';
				$items[$i]->googlepbadge = '';
				$items[$i]->googlebbadge = '';
				$items[$i]->bingpbadge = '';
				$items[$i]->bingbbadge = '';
				$items[$i]->alexabadge = '';
				$items[$i]->technoratibadge = '';
				$items[$i]->dmozbadge = '';
			}
			
			// Convert number
			$items[$i]->googlep = $items[$i]->googlep == -1 ? '-' : number_format($items[$i]->googlep, 0, '', '.');
			$items[$i]->googleb = $items[$i]->googleb == -1 ? '-' : number_format($items[$i]->googleb, 0, '', '.');
			$items[$i]->bingp = $items[$i]->bingp == -1 ? '-' : number_format($items[$i]->bingp, 0, '', '.');
			$items[$i]->bingb = $items[$i]->bingb == -1 ? '-' : number_format($items[$i]->bingb, 0, '', '.');
			$items[$i]->alexa = $items[$i]->alexa == -1 ? '-' : number_format($items[$i]->alexa, 0, '', '.');
			$items[$i]->technorati = $items[$i]->technorati == -1 ? '-' : number_format($items[$i]->technorati, 0, '', '.');
		}
		
		return $items;
	}
	
	/**
	 * Method to get competitor name.
	 *
	 * @return	string	The name of the competitor.
	 */
	public function getCompetitor() {
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		
		$query->select('`name`')->from('`#__rsseo_competitors`')->where('`id` = '.$this->getState('filter.parent'));
		$db->setQuery($query);
		return $db->loadResult();
	}
}