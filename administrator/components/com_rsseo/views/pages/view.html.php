<?php
/**
* @version 1.0.0
* @package RSSEO! 1.0.0
* @copyright (C) 2009-2012 www.rsjoomla.com
* @license GPL, http://www.gnu.org/copyleft/gpl.html
*/

defined( '_JEXEC' ) or die( 'Restricted access' ); 
jimport( 'joomla.application.component.view');

class rsseoViewPages extends JViewLegacy
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
		
		// Levels filter.
		$options	= array();
		$options[]	= JHtml::_('select.option', '127', JText::_('COM_RSSEO_GLOBAL_UNDEFINED'));
		$options[]	= JHtml::_('select.option', '1', JText::_('J1'));
		$options[]	= JHtml::_('select.option', '2', JText::_('J2'));
		$options[]	= JHtml::_('select.option', '3', JText::_('J3'));
		$options[]	= JHtml::_('select.option', '4', JText::_('J4'));
		$options[]	= JHtml::_('select.option', '5', JText::_('J5'));
		$options[]	= JHtml::_('select.option', '6', JText::_('J6'));
		$options[]	= JHtml::_('select.option', '7', JText::_('J7'));
		$options[]	= JHtml::_('select.option', '8', JText::_('J8'));
		$options[]	= JHtml::_('select.option', '9', JText::_('J9'));
		$options[]	= JHtml::_('select.option', '10', JText::_('J10'));

		// Sitemap filter
		$sitemapoptions   = array();
		$sitemapoptions[] = JHtml::_('select.option', '1', JText::_('JYES'));
		$sitemapoptions[] = JHtml::_('select.option', '0', JText::_('JNO'));
		
		$this->f_levels = $options;
		$this->pconfig	= array('published' => true, 'unpublished' => true, 'archived' => false, 'trash' => false, 'all' => false);
		$this->sitemap	= $sitemapoptions;
		
		$this->addToolBar();
		$this->sidebar = rsseoHelper::isJ3() ? JHtmlSidebar::render() : '';
		
		parent::display($tpl);
	}
	
	protected function addToolBar() {
		JToolBarHelper::title(JText::_('COM_RSSEO_LIST_PAGES'),'rsseo');

		JToolBarHelper::addNew('page.add');
		JToolBarHelper::editList('page.edit');
		JToolBarHelper::deleteList('COM_RSSEO_PAGE_CONFIRM_DELETE','pages.delete');
		$bar = JToolBar::getInstance('toolbar');
		$bar->appendButton('Confirm',JText::_('COM_RSSEO_DELETE_ALL_PAGES_MESSAGE',true),'delete',JText::_('COM_RSSEO_DELETE_ALL_PAGES'),'pages.removeall',false);
		JToolBarHelper::publishList('pages.publish');
		JToolBarHelper::unpublishList('pages.unpublish');
		
		JToolBarHelper::custom('pages.addsitemap','new','new',JText::_('COM_RSSEO_PAGE_ADDTOSITEMAP'));
		JToolBarHelper::custom('pages.removesitemap','trash','trash',JText::_('COM_RSSEO_PAGE_REMOVEFROMSITEMAP'));
		JToolBarHelper::custom('restore','restore','restore',JText::_('COM_RSSEO_RESTORE_PAGES'));
		JToolBarHelper::custom('refresh','refresh','refresh',JText::_('COM_RSSEO_BULK_REFRESH'));
		
		if (JFactory::getUser()->authorise('core.admin', 'com_rsseo'))
			JToolBarHelper::preferences('com_rsseo');
		
		JToolBarHelper::custom('main', 'rsseo.png', 'rsseo.png', JText::_('COM_RSSEO_GLOBAL_COMPONENT'), false);
		
		if (rsseoHelper::isJ3()) {
			JHtmlSidebar::addFilter(
				JText::_('JOPTION_SELECT_MAX_LEVELS'),
				'filter_level',
				JHtml::_('select.options', $this->f_levels, 'value', 'text', $this->state->get('filter.level'), true)
			);
			JHtmlSidebar::addFilter(
				JText::_('JOPTION_SELECT_PUBLISHED'),
				'filter_published',
				JHtml::_('select.options', JHtml::_('jgrid.publishedOptions', $this->pconfig), 'value', 'text', $this->state->get('filter.published'), true)
			);
			JHtmlSidebar::addFilter(
				JText::_('COM_RSSEO_SITEMAP_FILTER'),
				'filter_insitemap',
				JHtml::_('select.options', $this->sitemap, 'value', 'text', $this->state->get('filter.insitemap'), true)
			);
		}
	}
	
	protected function getSortFields() {
		$fields = array(
			'id' => JText::_('COM_RSSEO_GLOBAL_SORT_ID'),
			'url' => JText::_('COM_RSSEO_PAGES_URL'),
			'title' => JText::_('COM_RSSEO_PAGES_TITLE'),
			'level' => JText::_('COM_RSSEO_PAGES_LEVEL'),
			'grade' => JText::_('COM_RSSEO_PAGES_GRADE'),
			'crawled' => JText::_('COM_RSSEO_PAGES_CRAWLED'),
			'date' => JText::_('COM_RSSEO_PAGES_DATE')
		);
		
		return $fields;
	}
}