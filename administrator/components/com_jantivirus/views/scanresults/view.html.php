<?php
/**
 * @package	Antivirus
 * @copyright	Copyright (C) 2014 SiteGuarding.com. All rights reserved.
 * @license	GNU General Public License version 2 or later
 */
defined('_JEXEC') or die('Restricted access');
 
// import Joomla view library
jimport('joomla.application.component.view');
 

class jAntivirusViewScanresults extends JViewLegacy
{
	protected $reports;
	
	function display($tpl = null) 
	{
		$session = JFactory::getSession();
		$license_info = $session->get('jantivirus_license_info');
		
		$this->reports = $license_info['reports'];
		
		// Set the toolbar
		$this->addToolBar();
 
		// Display the template
		parent::display($tpl);
 
		// Set the document
		$this->setDocument();
	}
 
	/**
	 * Setting the toolbar
	 */
	protected function addToolBar() 
	{
		$canDo = jAntivirusHelper::getActions();
		JToolBarHelper::title(JText::_('COM_JANTIVIRUS_TITLE_SCANRESULTS'), 'jantivirus');

		if ($canDo->get('core.admin')) 
		{
			JToolBarHelper::divider();
			JToolBarHelper::preferences('com_jantivirus');
		}
	}
	/**
	 * Method to set up the document properties
	 *
	 * @return void
	 */
	protected function setDocument() 
	{
		$document = JFactory::getDocument();
		$document->setTitle(JText::_('COM_JANTIVIRUS_TITLE_SCANRESULTS'));
	}
}
