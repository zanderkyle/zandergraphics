<?php
/**
 * @package	Antivirus
 * @copyright	Copyright (C) 2014 SiteGuarding.com. All rights reserved.
 * @license	GNU General Public License version 2 or later
 */
defined('_JEXEC') or die;
 

abstract class jAntiVirusHelper
{

	public static function addSubmenu($submenu) 
	{
		$session = JFactory::getSession();
		$license_info = $session->get('jantivirus_license_info');
		if ($license_info['last_scan_files_counters']['main'] > 0 || $license_info['last_scan_files_counters']['heuristic'] > 0)
		{
			$txt_alert = ' <span class="label_error">['.$license_info['last_scan_files_counters']['main'].'/'.$license_info['last_scan_files_counters']['heuristic'].']</span>';
		}
		else $txt_alert = '';

		JSubMenuHelper::addEntry(JText::_('COM_JANTIVIRUS_SUBMENU_SCANNER'), 'index.php?option=com_jantivirus', $submenu == 'scanner');
		JSubMenuHelper::addEntry(JText::_('COM_JANTIVIRUS_SUBMENU_SCANRESULTS').$txt_alert, 'index.php?option=com_jantivirus&view=scanresults', $submenu == 'scanresults');
		JSubMenuHelper::addEntry(JText::_('COM_JANTIVIRUS_SUBMENU_REPORTS'), 'index.php?option=com_jantivirus&view=reports', $submenu == 'reports');
		JSubMenuHelper::addEntry(JText::_('COM_JANTIVIRUS_SUBMENU_SUPPORT'), 'index.php?option=com_jantivirus&view=support', $submenu == 'support');
		// set some global property
		$document = JFactory::getDocument();
		$document->addStyleDeclaration('.icon-48-jantivirus {background-image: url(../media/com_jantivirus/images/jantivirus-logo-48x48.png);}');
		if ($submenu == 'scanner') 
		{
			$document->setTitle(JText::_('COM_JANTIVIRUS_TITLE_SCANNER'));
		}
		if ($submenu == 'scanresults') 
		{
			$document->setTitle(JText::_('COM_JANTIVIRUS_SUBMENU_SCANRESULTS'));
		}
		if ($submenu == 'reports') 
		{
			$document->setTitle(JText::_('COM_JANTIVIRUS_TITLE_REPORTS'));
		}
		if ($submenu == 'support') 
		{
			$document->setTitle(JText::_('COM_JANTIVIRUS_TITLE_SUPPORT'));
		}
	}
	/**
	 * Get the actions
	 */
	public static function getActions($messageId = 0)
	{
		$user	= JFactory::getUser();
		$result	= new JObject;
 
		if (empty($messageId)) {
			$assetName = 'com_jantivirus';
		}
		else {
			$assetName = 'com_jantivirus.message.'.(int) $messageId;
		}
 
		$actions = array(
			'core.admin', 'core.manage', 'core.create', 'core.edit', 'core.delete'
		);
 
		foreach ($actions as $action) {
			$result->set($action,	$user->authorise($action, $assetName));
		}
 
		return $result;
	}
}
