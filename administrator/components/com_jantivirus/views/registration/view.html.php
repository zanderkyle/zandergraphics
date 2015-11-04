<?php
/**
 * @package	Antivirus
 * @copyright	Copyright (C) 2014 SiteGuarding.com. All rights reserved.
 * @license	GNU General Public License version 2 or later
 */
defined('_JEXEC') or die('Restricted access');
 
// import Joomla view library
jimport('joomla.application.component.view');
 

class jAntivirusViewRegistration extends JViewLegacy
{

	function display($tpl = null) 
	{

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
		JToolBarHelper::title(JText::_('COM_JANTIVIRUS_TITLE_REGISTRATION'), 'jantivirus');

		if ($canDo->get('core.admin')) 
		{
			JToolBarHelper::divider();
			JToolBarHelper::preferences('com_jantivirus');
		}
	}

	protected function setDocument() 
	{
		$document = JFactory::getDocument();
		$document->setTitle(JText::_('COM_JANTIVIRUS_TITLE_REGISTRATION'));
	}
}
