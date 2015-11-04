<?php
/**
* @package RSFirewall!
* @copyright (C) 2009-2014 www.rsjoomla.com
* @license GPL, http://www.gnu.org/licenses/gpl-2.0.html
*/

defined('_JEXEC') or die('Restricted access');

class RSFirewallViewLists extends JViewLegacy
{
	protected $items;
	protected $pagination;
	protected $state;
	protected $filterbar;
	protected $sidebar;
	protected $dropdown;
	protected $isJ30;
	
	public function display($tpl = null) {
		$user = JFactory::getUser();
		if (!$user->authorise('lists.manage', 'com_rsfirewall')) {
			$app = JFactory::getApplication();
			$app->enqueueMessage(JText::_('JERROR_ALERTNOAUTHOR'), 'error');
			$app->redirect(JRoute::_('index.php?option=com_rsfirewall', false));
		}
		
		$this->addToolBar();
		
		$this->isJ30		= $this->get('isJ30');
		
		$this->items 		= $this->get('Items');
		$this->pagination 	= $this->get('Pagination');
		$this->state 		= $this->get('State');
		
		$this->filterbar	= $this->get('FilterBar');		
		$this->sidebar 		= $this->get('SideBar');
		$this->dropdown		= $this->get('Dropdown');
		
		parent::display($tpl);
	}
	
	protected function addToolBar() {
		// set title
		JToolBarHelper::title('RSFirewall!', 'rsfirewall');
		
		require_once JPATH_COMPONENT.'/helpers/toolbar.php';
		RSFirewallToolbarHelper::addToolbar('lists');
		
		JToolBarHelper::addNew('list.add');
		JToolBarHelper::addNew('list.bulkadd', JText::_('COM_RSFIREWALL_BULK_ADD'));
		JToolBarHelper::editList('list.edit');
		JToolBarHelper::divider();
		JToolBarHelper::publish('lists.publish', 'JTOOLBAR_PUBLISH', true);
		JToolBarHelper::unpublish('lists.unpublish', 'JTOOLBAR_UNPUBLISH', true);
		JToolBarHelper::divider();
		JToolBarHelper::deleteList('COM_RSFIREWALL_CONFIRM_DELETE', 'lists.delete');
	}
}