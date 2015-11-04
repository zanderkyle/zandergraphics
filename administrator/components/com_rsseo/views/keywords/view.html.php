<?php
/**
* @version 1.0.0
* @package RSSEO! 1.0.0
* @copyright (C) 2009-2012 www.rsjoomla.com
* @license GPL, http://www.gnu.org/copyleft/gpl.html
*/

defined( '_JEXEC' ) or die( 'Restricted access' ); 
jimport( 'joomla.application.component.view');

class rsseoViewKeywords extends JViewLegacy
{
	protected $items;
	protected $pagination;
	protected $state;
	
	public function display($tpl = null) {
		$this->items 		= $this->get('Items');
		$this->pagination 	= $this->get('Pagination');
		$this->state 		= $this->get('State');
		
		$this->filter = array(
			JHtml::_('select.option', 'low', JText::_('COM_RSSEO_KEYWORD_IMPORTANCE_LOW')),
			JHtml::_('select.option', 'relevant', JText::_('COM_RSSEO_KEYWORD_IMPORTANCE_RELEVANT')),
			JHtml::_('select.option', 'important', JText::_('COM_RSSEO_KEYWORD_IMPORTANCE_IMPORTANT')),
			JHtml::_('select.option', 'critical', JText::_('COM_RSSEO_KEYWORD_IMPORTANCE_CRITICAL'))
		);
		
		$this->addToolBar();
		$this->sidebar = rsseoHelper::isJ3() ? JHtmlSidebar::render() : '';
		
		parent::display($tpl);
	}
	
	protected function addToolBar() {
		JToolBarHelper::title(JText::_('COM_RSSEO_LIST_KEYWORDS'),'rsseo');
		
		JToolBarHelper::addNew('keyword.add');
		JToolBarHelper::editList('keyword.edit');
		JToolBarHelper::deleteList('COM_RSSEO_GLOBAL_CONFIRM_DELETE', 'keywords.delete');
		
		if (JFactory::getUser()->authorise('core.admin', 'com_rsseo'))
			JToolBarHelper::preferences('com_rsseo');
		
		if (rsseoHelper::isJ3()) {
			JHtmlSidebar::addFilter(
				JText::_('COM_RSSEO_KEYWORDS_IMPORTANCE_SELECT'),
				'filter_importance',
				JHtml::_('select.options', $this->filter, 'value', 'text', $this->state->get('filter.importance'), true)
			);
		}
		
		JToolBarHelper::custom('main', 'rsseo.png', 'rsseo.png', JText::_('COM_RSSEO_GLOBAL_COMPONENT'), false);
	}
	
	protected function getSortFields() {
		$fields = array(
			'id' => JText::_('COM_RSSEO_GLOBAL_SORT_ID'),
			'keyword' => JText::_('COM_RSSEO_KEYWORDS_KEYWORD'),
			'importance' => JText::_('COM_RSSEO_KEYWORDS_IMPORTANCE'),
			'position' => JText::_('COM_RSSEO_KEYWORDS_POSITION'),
			'date' => JText::_('COM_RSSEO_KEYWORDS_DATE')
		);
		
		return $fields;
	}
}