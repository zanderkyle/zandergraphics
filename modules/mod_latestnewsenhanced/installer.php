<?php
/**
 * @copyright	Copyright (C) 2011 Simplify Your Web, Inc. All rights reserved.
 * @license		GNU General Public License version 3 or later; see LICENSE.txt
 */

// No direct access to this file
defined('_JEXEC') or die;

/**
 * Script file of the LatestNewsEnhanced module
 */
class mod_latestnewsenhancedInstallerScript
{	
	static $minimum_needed_library_version = '1.2.4';
	static $download_link = 'http://www.simplifyyourweb.com/index.php/downloads/category/23-libraries';
	
	/**
	 * Called before an install/update method
	 *
	 * @return  boolean  True on success
	 */
	public function preflight($type, $parent) 
	{	
		//if ($type != 'uninstall') { // preflight not executed on uninstall		
			// check if syw library is present			
			
			if (!JFolder::exists(JPATH_ROOT.'/libraries/syw')) {
				
				$message = JText::_('SYW_MISSING_SYWLIBRARY').'.<br /><a href="'.self::$download_link.'" target="_blank">'.JText::_('SYW_DOWNLOAD_SYWLIBRARY').'</a>.';				
				JFactory::getApplication()->enqueueMessage($message, 'error');
				return false;
				
			} else {
				jimport('syw.version');			
				if (SYWVersion::isCompatible(self::$minimum_needed_library_version)) {
										
					JFactory::getApplication()->enqueueMessage(JText::_('SYW_COMPATIBLE_SYWLIBRARY'), 'message');					
					return true;
					
				} else {
					
					$message = JText::_('SYW_NONCOMPATIBLE_SYWLIBRARY').'.<br />'.JText::_('SYW_UPDATE_SYWLIBRARY').JText::_('SYW_OR').'<a href="'.self::$download_link.'" target="_blank">'.strtolower(JText::_('SYW_DOWNLOAD_SYWLIBRARY')).'</a>.';					
					JFactory::getApplication()->enqueueMessage($message, 'error');
					return false;
				}
			}
		//}
	}
	
	/**
	 * Called after an install/update method
	 *
	 * @return  boolean  True on success
	 */
	public function postflight($type, $parent) 
	{
		echo '<p style="margin-top: 10px">';
		echo '<img src="../modules/mod_latestnewsenhanced/images/logo.png" style="float: none" />';
		echo '<br /><br />'.JText::_('MOD_LATESTNEWSENHANCED_VERSION');
		echo '<br /><br />Olivier Buisard @ <a href="http://www.simplifyyourweb.com" target="_blank">Simplify Your Web</a>';
		echo '</p>';
		
		if ($type == 'update') {
				
			// delete unnecessary files
			
			jimport('joomla.filesystem.file');
			jimport('joomla.filesystem.folder');
				
			$files = array();
				
			$folders = array();
				
			foreach ($files as $file) {
				if (JFile::exists(JPATH_ROOT.$file) && !JFile::delete(JPATH_ROOT.$file)) {
					JFactory::getApplication()->enqueueMessage(JText::sprintf('FILES_JOOMLA_ERROR_FILE_FOLDER', $file), 'warning');
				}
			}
				
			foreach ($folders as $folder) {
				if (JFolder::exists(JPATH_ROOT.$folder) && !JFolder::delete(JPATH_ROOT.$folder)) {
					JFactory::getApplication()->enqueueMessage(JText::sprintf('FILES_JOOMLA_ERROR_FILE_FOLDER', $folder), 'warning');
				}
			}
			
			// update warning			
			JFactory::getApplication()->enqueueMessage(JText::_('MOD_LATESTNEWSENHANCED_WARNING_RELEASENOTES'), 'warning');
		}
		
		return true;
	}	
	
	/**
	 * Called on installation
	 *
	 * @return  boolean  True on success
	 */
	public function install($parent) {}
	
	/**
	 * Called on update
	 *
	 * @return  boolean  True on success
	 */
	public function update($parent) {}
	
	/**
	 * Called on uninstallation
	 */
	public function uninstall($parent) {}
	
}
?>