<?php
/**
* @version 1.0.0
* @package RSSEO! 1.0.0
* @copyright (C) 2009-2012 www.rsjoomla.com
* @license GPL, http://www.gnu.org/copyleft/gpl.html
*/

defined( '_JEXEC' ) or die( 'Restricted access' ); 
jimport( 'joomla.application.component.view');

class rsseoViewRedirects extends JViewLegacy
{
	protected $items;
	protected $pagination;
	protected $state;
	
	public function display($tpl = null) {
		$this->items 		= $this->get('Items');
		$this->pagination 	= $this->get('Pagination');
		$this->state 		= $this->get('State');
		
		$this->addToolBar();
		$this->sidebar = rsseoHelper::isJ3() ? JHtmlSidebar::render() : '';
		
		parent::display($tpl);
	}
	
	protected function addToolBar() {
		JToolBarHelper::title(JText::_('COM_RSSEO_LIST_REDIRECTS'),'rsseo');
		
		JToolBarHelper::addNew('redirect.add');
		JToolBarHelper::editList('redirect.edit');
		JToolBarHelper::deleteList('COM_RSSEO_GLOBAL_CONFIRM_DELETE', 'redirects.delete');
		JToolBarHelper::publishList('redirects.publish');
		JToolBarHelper::unpublishList('redirects.unpublish');
		
		if (JFactory::getUser()->authorise('core.admin', 'com_rsseo'))
			JToolBarHelper::preferences('com_rsseo');
		
		JToolBarHelper::custom('main', 'rsseo.png', 'rsseo.png', JText::_('COM_RSSEO_GLOBAL_COMPONENT'), false);
		
		if (rsseoHelper::isJ3()) {
			JHtmlSidebar::addFilter(
				JText::_('JOPTION_SELECT_PUBLISHED'),
				'filter_published',
				JHtml::_('select.options', JHtml::_('jgrid.publishedOptions', array('published' => true, 'unpublished' => true, 'archived' => false, 'trash' => false, 'all' => false)), 'value', 'text', $this->state->get('filter.published'), true)
			);
		}
	}
	
	protected function getSortFields() {
		$fields = array(
			'id' => JText::_('COM_RSSEO_GLOBAL_SORT_ID'),
			'from' => JText::_('COM_RSSEO_REDIRECTS_FROM'),
			'to' => JText::_('COM_RSSEO_REDIRECTS_TO')
		);
		
		return $fields;
	}
}