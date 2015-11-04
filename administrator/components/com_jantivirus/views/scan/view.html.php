<?php
/**
 * @package	Antivirus
 * @copyright	Copyright (C) 2014 SiteGuarding.com. All rights reserved.
 * @license	GNU General Public License version 2 or later
 */
defined('_JEXEC') or die('Restricted access');
 
// import Joomla view library
jimport('joomla.application.component.view');
 

class jAntivirusViewScan extends JViewLegacy
{
	function display($tpl = null) 
	{
		session_start();
		$session_id = md5(time().'-'.rand(1,10000));
		$_SESSION['scan']['session_id'] = $session_id;
		$session = JFactory::getSession();
		$session->set('jantivirus_session_id', $session_id);

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
		JToolBarHelper::title(JText::_('COM_JANTIVIRUS_TITLE_SCANNER'), 'jantivirus');

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
		$document->setTitle(JText::_('COM_JANTIVIRUS_TITLE_SCANNER'));
	}
}
