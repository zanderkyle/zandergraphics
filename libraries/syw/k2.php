<?php
/**
 * @copyright	Copyright (C) 2011 Simplify Your Web, Inc. All rights reserved.
 * @license		GNU General Public License version 3 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

class SYWK2 {
	
	static $k2_exists = NULL;
	
	static function exists()
	{	
		if (isset(self::$k2_exists)) {
			return self::$k2_exists;
		}
		
		//self::$k2_exists = JComponentHelper::isEnabled('com_k2', true); // this generates a warning when K2 is missing
		
		/*if (!self::$k2_exists) {
			$lang = JFactory::getLanguage();
			$lang->load('lib_syw', JPATH_ADMINISTRATOR);
			JFactory::getApplication()->enqueueMessage(JText::_('LIB_SYW_DISCARD_MESSAGE'), 'warning');
		}*/
		
		self::$k2_exists = true;
		
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('extension_id AS id, element AS "option", params, enabled');
		$query->from('#__extensions');
		$query->where($query->qn('type') . ' = ' . $db->quote('component'));
		$query->where($query->qn('element') . ' = ' . $db->quote('com_k2'));
		$db->setQuery($query);
		
		$cache = JFactory::getCache('_system', 'callback');
		
		$k2_component = $cache->get(array($db, 'loadObject'), null, 'com_k2', false);
		
		if ($error = $db->getErrorMsg() || empty($k2_component)) {
			self::$k2_exists = false;
		}
		
		return self::$k2_exists;
	}	
	
}
