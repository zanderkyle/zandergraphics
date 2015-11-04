<?php
/**
* @version 1.0.0
* @package RSSEO! 1.0.0
* @copyright (C) 2009-2012 www.rsjoomla.com
* @license GPL, http://www.gnu.org/copyleft/gpl.html
*/

defined( '_JEXEC' ) or die( 'Restricted access' ); 
jimport( 'joomla.application.component.view');

class rsseoViewCompetitors extends JViewLegacy
{
	protected $items;
	protected $pagination;
	protected $state;
	protected $config;
	
	public function display($tpl = null) {
		$this->items 		= $this->get('Items');
		$this->pagination 	= $this->get('Pagination');
		$this->state 		= $this->get('State');
		$this->config 		= rsseoHelper::getConfig();
		
		$this->addToolBar();
		$this->sidebar = rsseoHelper::isJ3() ? JHtmlSidebar::render() : '';
		parent::display($tpl);
	}
	
	protected function addToolBar() {
		$parent = $this->state->get('filter.parent');
		
		if (!$parent) {
			JToolBarHelper::title(JText::_('COM_RSSEO_LIST_COMPETITORS'),'rsseo');	
			JToolBarHelper::addNew('competitor.add');
			JToolBarHelper::editList('competitor.edit');
		} else {
			JToolBarHelper::title(JText::sprintf('COM_RSSEO_LIST_COMPETITORS_FOR', $this->get('competitor')),'rsseo');
			JToolBarHelper::custom('back','back','back',JText::_('COM_RSSEO_GLOBAL_BACK'),false);
		}
		
		JToolBarHelper::deleteList('COM_RSSEO_GLOBAL_CONFIRM_DELETE','competitors.delete');
		
		if (!$parent) {
			JToolBarHelper::custom('compete','compete','compete',JText::_('COM_RSSEO_COMPETE'),true);
			JToolBarHelper::custom('competitors.export','upload','upload_f2',JText::_('COM_RSSEO_GLOBAL_EXPORT'),false);
		}
		
		if (JFactory::getUser()->authorise('core.admin', 'com_rsseo'))
			JToolBarHelper::preferences('com_rsseo');
		
		JToolBarHelper::custom('main', 'rsseo.png', 'rsseo.png', JText::_('COM_RSSEO_GLOBAL_COMPONENT'), false);
	}
	
	protected function getSortFields() {
		$fields = array(
			'id' => JText::_('COM_RSSEO_GLOBAL_SORT_ID'),
			'name' => JText::_('COM_RSSEO_COMPETITORS_COMPETITOR')
		);
		
		if ($this->config->enable_pr) $fields['pagerank'] = JText::_('COM_RSSEO_COMPETITORS_PAGE_RANK');
		if ($this->config->enable_googlep) $fields['googlep'] = JText::_('COM_RSSEO_COMPETITORS_GOOGLE_PAGES');
		if ($this->config->enable_googleb) $fields['googleb'] = JText::_('COM_RSSEO_COMPETITORS_GOOGLE_BACKLINKS');
		if ($this->config->enable_bingp) $fields['bingp'] = JText::_('COM_RSSEO_COMPETITORS_BING_PAGES');
		if ($this->config->enable_bingb) $fields['bingb'] = JText::_('COM_RSSEO_COMPETITORS_BING_BACKLINKS');
		if ($this->config->enable_alexa) $fields['alexa'] = JText::_('COM_RSSEO_COMPETITORS_ALEXA_RANK');
		if ($this->config->enable_tehnorati) $fields['technorati'] = JText::_('COM_RSSEO_COMPETITORS_TECHNORATI_RANK');
		if ($this->config->enable_dmoz) $fields['dmoz'] = JText::_('COM_RSSEO_COMPETITORS_DMOZ_RANK');
		$fields['date'] = JText::_('COM_RSSEO_COMPETITORS_DATE');
		
		return $fields;
	}
}