<?php
/**
 * @package	Antivirus
 * @copyright	Copyright (C) 2014 SiteGuarding.com. All rights reserved.
 * @license	GNU General Public License version 2 or later
 */
defined('_JEXEC') or die('Restricted access');
 
// import Joomla view library
jimport('joomla.application.component.view');
 

class jAntivirusViewScanner extends JViewLegacy
{
	function display($tpl = null) 
	{
		// Check server settings
		$errors = json_decode(SGAntiVirus::checkServerSettings(true), true);
		if (count($errors))
		{
			foreach ($errors as $error)
			{
				JError::raiseWarning( 100, $error );
			}
		}
		
		// Set the toolbar
		$this->addToolBar();
 
		// Display the template
		parent::display($tpl);
 
		// Set the document
		$this->setDocument();
	}
 

	protected function addToolBar() 
	{
		$canDo = jAntivirusHelper::getActions();
		JToolBarHelper::title(JText::_('COM_JANTIVIRUS_TITLE_SCANNER'), 'jantivirus');

		if ($canDo->get('core.admin')) 
		{
			JToolBarHelper::divider();
			JToolBarHelper::preferences('com_jantivirus');
		}
	}

	protected function setDocument() 
	{
		$document = JFactory::getDocument();
		$document->setTitle(JText::_('COM_JANTIVIRUS_TITLE_SCANNER'));
	}
}
