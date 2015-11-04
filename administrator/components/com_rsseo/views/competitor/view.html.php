<?php
/**
* @version 1.0.0
* @package RSSEO! 1.0.0
* @copyright (C) 2009-2012 www.rsjoomla.com
* @license GPL, http://www.gnu.org/copyleft/gpl.html
*/

defined( '_JEXEC' ) or die( 'Restricted access' ); 
jimport( 'joomla.application.component.view');

class rsseoViewCompetitor extends JViewLegacy
{
	protected $form;
	protected $item;
	
	public function display($tpl = null) {
		$this->form 		 = $this->get('Form');
		$this->item 		 = $this->get('Item');
		
		$this->addToolbar();
		parent::display($tpl);
	}
	
	protected function addToolbar() {
		JToolBarHelper::title(JText::_('COM_RSSEO_COMPETITOR_EDIT'),'rsseo');
		
		JToolBarHelper::apply('competitor.apply');
		JToolBarHelper::save('competitor.save');
		JToolBarHelper::cancel('competitor.cancel');
	}
}