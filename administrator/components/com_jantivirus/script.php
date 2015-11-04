<?php
/**
 * @package	Antivirus
 * @copyright	Copyright (C) 2014 SiteGuarding.com. All rights reserved.
 * @license	GNU General Public License version 2 or later
 */
defined('_JEXEC') or die('Restricted access');
 
class com_jantivirusInstallerScript
{
	function install($parent) 
	{
		
		$src = dirname(__FILE__) . '/' . "modules";
		$dst = JPATH_ROOT . '/'."administrator". '/' . "modules";
		
		self::recurse_copy($src, $dst);
		
		if (version_compare (JVERSION, '1.6.0', 'ge')) {
			$defaultParams = '{"cache":"1","moduleclass_sfx":"","class_sfx":""}';
		} else {
			$defaultParams = "cache=1\nmoduleclass_sfx=\nclass_sfx=\n";
		}
		self::installModule('jAntivirus Status', 'mod_jantivirus', 0, $defaultParams, $dst);
		
		
		// Redirect
		$parent->getParent()->setRedirectURL('index.php?option=com_jantivirus');
	}
	
	
	
	public function createIndexFolder ($path) {

		if (JFolder::create ($path)) {
			if (!JFile::exists ($path . '/' . 'index.html')) {
				JFile::copy (JPATH_ROOT . '/' . 'components' . '/' . 'index.html', $path . '/' . 'index.html');
			}
			return TRUE;
		}
		return FALSE;
	}
	
	
	public function recurse_copy ($src, $dst) {

		$dir = opendir ($src);
		self::createIndexFolder ($dst);

		if (is_resource ($dir)) {
			while (FALSE !== ($file = readdir ($dir))) {
				if (($file != '.') && ($file != '..')) {
					if (is_dir ($src . '/' . $file)) {
						self::recurse_copy ($src . '/' . $file, $dst . '/' . $file);
					} else {
						if (JFile::exists ($dst . '/' . $file)) {
							if (!JFile::delete ($dst . '/' . $file)) {
								$app = JFactory::getApplication ();
								$app->enqueueMessage ('Couldnt delete ' . $dst . '/' . $file);
								return false;
							}
						}
						if (!JFile::move ($src . '/' . $file, $dst . '/' . $file)) {
							$app = JFactory::getApplication ();
							$app->enqueueMessage ('Couldnt move ' . $src . '/' . $file . ' to ' . $dst . '/' . $file);
							return false;
						}
					}
				}
			}
			closedir ($dir);
			if (is_dir ($src)) {
				JFolder::delete ($src);
			}
		} else {
			$app = JFactory::getApplication ();
			$app->enqueueMessage ('Couldnt read dir ' . $dir . ' source ' . $src);
			return false;
		}
		return true;
	}
		
		
	public function installModule ($title, $module, $ordering, $params, $src, $client_id=1, $position='position-4', $access=1) {

		$params = '';

		$table = JTable::getInstance ('module');

		$db = $table->getDBO ();
		$q = 'SELECT id FROM `#__modules` WHERE `module` = "' . $module . '" ';
		$db->setQuery ($q);
		$id = $db->loadResult ();

		$src .= '/' . $module;
		if (!empty($id)) {
			//echo 'errrr='.$id;
			//return;
		}
		$table->load ();

		if (empty($table->title)) {
			$table->title = $title;
		}
		if (empty($table->ordering)) {
			$table->ordering = $ordering;
		}
		
		$table->published = 1;
		
		if (empty($table->module)) {
			$table->module = $module;
		}
		if (empty($table->params)) {
			$table->params = $params;
		}
		// table is loaded with access=1
			$table->access = $access;
			
		$table->position = 'status';


		$table->client_id  = 1;

		$table->language = '*';


		if (!$table->check ()) {
			$app = JFactory::getApplication ();
			$app->enqueueMessage ('jAntivirus Installer table->check throws error for ' . $title . ' ' . $module . ' ' . $params);
		}

		if (!$table->store ()) {
			$app = JFactory::getApplication ();
			$app->enqueueMessage ('jAntivirus  table->store throws error for for ' . $title . ' ' . $module . ' ' . $params);
		}

		$errors = $table->getErrors ();
		foreach ($errors as $error) {
			$app = JFactory::getApplication ();
			$app->enqueueMessage (get_class ($this) . '::store ' . $error);
		}


		$lastUsedId = $table->id;

		$q = 'SELECT moduleid FROM `#__modules_menu` WHERE `moduleid` = "' . $lastUsedId . '" ';
		$db->setQuery ($q);
		$moduleid = $db->loadResult ();

		$action = '';
		if (empty($moduleid)) {
			$q = 'INSERT INTO `#__modules_menu` (`moduleid`, `menuid`) VALUES( "' . $lastUsedId . '" , "0");';
		} else {

		}
		$db->setQuery ($q);
		$db->query ();

		if (version_compare (JVERSION, '1.6.0', 'ge')) {

			$q = 'SELECT extension_id FROM `#__extensions` WHERE `element` = "' . $module . '" ';
			$db->setQuery ($q);
			$ext_id = $db->loadResult ();

			$action = '';
			if (empty($ext_id)) {
				if (version_compare (JVERSION, '1.6.0', 'ge')) {
					$manifest_cache = json_encode (JApplicationHelper::parseXMLInstallFile ($src . '/' . $module . '.xml'));
				}
				$q = 'INSERT INTO `#__extensions` 	(`name`, `type`, `element`, `folder`, `client_id`, `enabled`, `access`, `protected`, `manifest_cache`, `params`, `ordering`) VALUES
																( "' . $module . '" , "module", "' . $module . '", "", "'.$client_id.'", "1","' . $access . '", "0", "' . $db->getEscaped ($manifest_cache) . '", "' . $params . '","' . $ordering . '");';
			} else {


			}
			$db->setQuery ($q);
			if (!$db->query ()) {
				$app = JFactory::getApplication ();
				$app->enqueueMessage (get_class ($this) . '::  ' . $db->getErrorMsg ());
			}

		}
	}

}
