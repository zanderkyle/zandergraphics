<?php
/**
 * @copyright	Copyright (C) 2011 Simplify Your Web, Inc. All rights reserved.
 * @license		GNU General Public License version 3 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

class SYWLibraries {
	
	static $jqLoaded = false;
	static $jqncLoaded = false;
	
	static $jqpLoaded = false;
	
	static $jqcLoaded = false;
	static $jqcthrottleLoaded = false;
	static $jqctouchLoaded = false;
	static $jqcmousewheelLoaded = false;
	static $jqctransitLoaded = false;
		
	/**
	 * Load the jQuery library if needed
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
	 * Load the jQuery library if needed
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
	
	/**
	 * Load Pajinate jQuery plugin if needed
	 */
	static function loadPagination()
	{
		$doc = JFactory::getDocument();
	
		if (self::$jqpLoaded) {
			return;
		}
			
		$doc->addScript(JURI::root(true).'/media/syw/js/pagination/jquery.pajinate.min.js');
		
		self::$jqpLoaded = true;
	}
	
	/**
	 * Load the carousel library (and its plugins) if needed
	 * jQuery v1.7+
	 */
	static function loadCarousel($throttle = true, $touch = true, $mousewheel = false, $transit = false)
	{
		$doc = JFactory::getDocument();		
	
		if ($throttle) {
			self::loadCarousel_throttle();
		}
		
		if ($touch) {
			self::loadCarousel_touch();
		}
		
		if ($mousewheel) {
			self::loadCarousel_mousewheel();
		}
		
		if ($transit) {
			self::loadCarousel_transit();
		}
	
		if (self::$jqcLoaded) {
			return;
		}
		
		$doc->addScript(JURI::root(true).'/media/syw/js/carousel/jquery.carouFredSel-6.2.1-packed.js');
	
		self::$jqcLoaded = true;
	}
	
	static function loadCarousel_throttle()
	{
		$doc = JFactory::getDocument();
	
		if (self::$jqcthrottleLoaded) {
			return;
		}
			
		$doc->addScript(JURI::root(true).'/media/syw/js/carousel/jquery.ba-throttle-debounce.min.js');
	
		self::$jqcthrottleLoaded = true;
	}
	
	static function loadCarousel_touch()
	{
		$doc = JFactory::getDocument();
	
		if (self::$jqctouchLoaded) {
			return;
		}
			
		$doc->addScript(JURI::root(true).'/media/syw/js/carousel/jquery.touchSwipe.min.js');
	
		self::$jqctouchLoaded = true;
	}
	
	static function loadCarousel_mousewheel()
	{
		$doc = JFactory::getDocument();
	
		if (self::$jqcmousewheelLoaded) {
			return;
		}
		
		$doc->addScript(JURI::root(true).'/media/syw/js/carousel/jquery.mousewheel.min.js');
	
		self::$jqcmousewheelLoaded = true;
	}
	
	static function loadCarousel_transit()
	{
		$doc = JFactory::getDocument();
	
		if (self::$jqctransitLoaded) {
			return;
		}
		
		$doc->addScript(JURI::root(true).'/media/syw/js/carousel/jquery.transit.min.js');

		self::$jqctransitLoaded = true;
	}
	
}
?>
