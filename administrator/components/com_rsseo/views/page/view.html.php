<?php
/**
* @version 1.0.0
* @package RSSEO! 1.0.0
* @copyright (C) 2009-2012 www.rsjoomla.com
* @license GPL, http://www.gnu.org/copyleft/gpl.html
*/

defined( '_JEXEC' ) or die( 'Restricted access' ); 
jimport( 'joomla.application.component.view');

class rsseoViewPage extends JViewLegacy
{
	protected $form;
	protected $item;
	protected $config;
	protected $layout;
	protected $details;
	
	public function display($tpl = null) {
		$this->layout		= $this->getLayout();
		$this->item			= $this->get('Item');
		
		if ($this->layout == 'details') {
			$this->details 		 = $this->get('Details');
		} else {
			$this->form 		 = $this->get('Form');
			$this->config 		 = rsseoHelper::getConfig();
		}
		
		$this->addToolbar();
		parent::display($tpl);
	}
	
	protected function addToolbar() {
		if ($this->layout == 'details') {
			JToolBarHelper::title(JText::_('COM_RSSEO_PAGE_SIZE_DETAILS'),'rsseo');
			
			$bar 		= JToolBar::getInstance('toolbar');
			$bar->appendButton('Link', 'back', JText::_('COM_RSSEO_GLOBAL_BACK'), 'index.php?option=com_rsseo&view=page&layout=edit&id='.$this->item->id);
		} else {
			JToolBarHelper::title(JText::_('COM_RSSEO_PAGE_NEW_EDIT'),'rsseo');
		
			JToolBarHelper::apply('page.apply');
			JToolBarHelper::save('page.save');
			JToolBarHelper::cancel('page.cancel');
			JToolBarHelper::custom('page.refresh','html','html',JText::_('COM_RSSEO_GLOBAL_REFRESH'),false);
		}
		
		JToolBarHelper::custom('main', 'rsseo.png', 'rsseo.png', JText::_('COM_RSSEO_GLOBAL_COMPONENT'), false);
	}
}