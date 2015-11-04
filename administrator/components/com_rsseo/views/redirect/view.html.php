<?php
/**
* @version 1.0.0
* @package RSSEO! 1.0.0
* @copyright (C) 2009-2012 www.rsjoomla.com
* @license GPL, http://www.gnu.org/copyleft/gpl.html
*/

defined( '_JEXEC' ) or die( 'Restricted access' ); 
jimport( 'joomla.application.component.view');

class rsseoViewRedirect extends JViewLegacy
{
	protected $form;
	protected $item;
	
	public function display($tpl = null) {
		$this->form 		= $this->get('Form');
		$this->item			= $this->get('Item');
		
		$this->addToolBar();
		parent::display($tpl);
	}
	
	protected function addToolBar() {
		JToolBarHelper::title(JText::_('COM_RSSEO_REDIRECT_NEW_EDIT'),'rsseo');
		
		JToolBarHelper::apply('redirect.apply');
		JToolBarHelper::save('redirect.save');
		JToolBarHelper::cancel('redirect.cancel');
	}
}