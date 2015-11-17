<?php
/**
 * @copyright	Copyright (C) 2011 Simplify Your Web, Inc. All rights reserved.
 * @license		GNU General Public License version 3 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

class SYWFonts {
	
	static $iconfontLoaded = false;
		
	/**
	 * Load the icn font if needed
	 */
	static function loadIconFont()
	{
		$doc = JFactory::getDocument();
	
		if (self::$iconfontLoaded) {
			return;
		}
		
		$doc->addStyleSheet(JURI::base(true).'/media/syw/css/fonts.css');
						
		self::$iconfontLoaded = true;
	}
	
}
?>
