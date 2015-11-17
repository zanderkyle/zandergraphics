<?php
/**
 * @copyright	Copyright (C) 2011 Simplify Your Web, Inc. All rights reserved.
 * @license		GNU General Public License version 3 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

class SYWUtilities {
	
	static $jqLoaded = false;
	static $jqncLoaded = false;
		
	/**
	 * DEPRECATED
	 * Load the jQuery library if needed
	 * Keep for compatibility with extensions requiring version 1.0.1 of the library
	 */
	static function loadJQuery($local, $version)
	{
		$doc = JFactory::getDocument();
	
		if (self::$jqLoaded) {
			return;
		}
			
		if ($local) {
			$doc->addScript(JURI::root(true).'/media/syw/js/jquery/jquery-'.$version.'.min.js');
		} else {
			$doc->addScript('//ajax.googleapis.com/ajax/libs/jquery/'.$version.'/jquery.min.js');
		}
			
		// add script instead of declaration to make sure the two files follow each other
		//$doc->addScript(JURI::root(true).'/media/syw/js/jquery/syw.noconflict.js'); 
			
		self::$jqLoaded = true;
	}
	
	/**
	 * DEPRECATED
	 * Load the jQuery library if needed
	 * Keep for compatibility with extensions requiring version 1.0.1 of the library
	 */
	static function loadJQueryNoConflict()
	{
		$doc = JFactory::getDocument();
	
		if (self::$jqncLoaded) {
			return;
		}
			
		$doc->addScript(JURI::root(true).'/media/syw/js/jquery/syw.noconflict.js');
			
		self::$jqncLoaded = true;
	}
	
	/*
	 * Determines if the device is a mobile one
	 */
	static function isMobile()
	{
		return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
	}
	
	/*
	 * DEPRECATED
	 * Returns the google font found in a font family or false if none is found
	 * The returned font is of format "Google Font"
	 */
	static function googleFont($font_family) 
	{
		$google_font = false;
		
		$standard_fonts = array();
		$standard_fonts[] = "Palatino Linotype";
		$standard_fonts[] = "Book Antiqua";
		$standard_fonts[] = "MS Serif";
		$standard_fonts[] = "New York";
		$standard_fonts[] = "Times New Roman";
		$standard_fonts[] = "Arial Black";
		$standard_fonts[] = "Comic Sans MS";
		$standard_fonts[] = "Lucida Sans Unicode";
		$standard_fonts[] = "Lucida Grande";
		$standard_fonts[] = "Trebuchet MS";
		$standard_fonts[] = "MS Sans Serif";
		$standard_fonts[] = "Courier New";
		$standard_fonts[] = "Lucida Console";
		
		$fonts = explode(',', $font_family);
		foreach ($fonts as $font) {
			if (substr_count($font, '"') == 2) { // found a font with 2 quotes
				$font = trim($font, '"');
				foreach ($standard_fonts as $standard_font) {
					if (strcasecmp($standard_font, $font) == 0) { // identical fonts
						return false;
					}
				}				
				$google_font = $font;
			}
		}	
		
		return $google_font;
	}
	
	/*
	* Returns the google font found in a font family
	* The returned font is of format "Google Font"
	*/
	static function getGoogleFont($font_family)
	{
		$google_font = '';
	
		$standard_fonts = array();
		$standard_fonts[] = "Palatino Linotype";
		$standard_fonts[] = "Book Antiqua";
		$standard_fonts[] = "MS Serif";
		$standard_fonts[] = "New York";
		$standard_fonts[] = "Times New Roman";
		$standard_fonts[] = "Arial Black";
		$standard_fonts[] = "Comic Sans MS";
		$standard_fonts[] = "Lucida Sans Unicode";
		$standard_fonts[] = "Lucida Grande";
		$standard_fonts[] = "Trebuchet MS";
		$standard_fonts[] = "MS Sans Serif";
		$standard_fonts[] = "Courier New";
		$standard_fonts[] = "Lucida Console";
	
		$fonts = explode(',', $font_family);
		foreach ($fonts as $font) {
			if (substr_count($font, '"') == 2) { // found a font with 2 quotes
				$font = trim($font, '"');
				foreach ($standard_fonts as $standard_font) {
					if (strcasecmp($standard_font, $font) == 0) { // identical fonts
						return '';
					}
				}
				$google_font = $font;
			}
		}
	
		return $google_font;
	}
	
	/*
	 * Transform "Google Font" into Google+Font for use in <link> tag
	 */
	static function getSafeGoogleFont($google_font)
	{
		$font = str_replace(' ', '+', $google_font); // replace spaces by +
		return trim($font, '"');
	}
	
	/*
	 * Convert a hexa decimal color code to its RGB equivalent
	 *
	 * @param string $hexStr (hexadecimal color value)
	 * @param boolean $returnAsString (if set true, returns the value separated by the separator character. Otherwise returns associative array)
	 * @param string $seperator (to separate RGB values. Applicable only if second parameter is true.)
	 * @return array or string (depending on second parameter. Returns False if invalid hex color value)
	 */
	static function hex2RGB($hexStr, $returnAsString = false, $seperator = ',') 
	{
	    $hexStr = preg_replace("/[^0-9A-Fa-f]/", '', $hexStr); // Gets a proper hex string
	    $rgbArray = array();
	    if (strlen($hexStr) == 6) { // if a proper hex code, convert using bitwise operation. No overhead... faster
	        $colorVal = hexdec($hexStr);
	        $rgbArray['red'] = 0xFF & ($colorVal >> 0x10);
	        $rgbArray['green'] = 0xFF & ($colorVal >> 0x8);
	        $rgbArray['blue'] = 0xFF & $colorVal;
	    } elseif (strlen($hexStr) == 3) { // if shorthand notation, need some string manipulations
	        $rgbArray['red'] = hexdec(str_repeat(substr($hexStr, 0, 1), 2));
	        $rgbArray['green'] = hexdec(str_repeat(substr($hexStr, 1, 1), 2));
	        $rgbArray['blue'] = hexdec(str_repeat(substr($hexStr, 2, 1), 2));
	    } else {
	        return false; //Invalid hex color code
	    }
	    
	    return $returnAsString ? implode($seperator, $rgbArray) : $rgbArray; // returns the rgb string or the associative array
	} 
	
	/*
	 * Determine if the Joomla version is Joomla 3
	*/
	static function isJoomla3($and_over = false) 
	{		
		$version = new JVersion();
		$jversion = explode('.', $version->getShortVersion());
		if ($and_over) {
			if (intval($jversion[0]) > 2) { // Joomla! 3+
				return true;
			}
		} else {
			if (intval($jversion[0]) > 2 && intval($jversion[0]) < 4) { // Joomla! 3 only
				return true;
			}
		}
		
		return false;
	}
	
}
?>
