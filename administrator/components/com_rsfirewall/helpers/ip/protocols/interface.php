<?php
/**
* @package RSFirewall!
* @copyright (C) 2009-2014 www.rsjoomla.com
* @license GPL, http://www.gnu.org/licenses/gpl-2.0.html
*/

defined('_JEXEC') or die('Restricted access');

interface RSFirewallIPInterface
{	
	// Test returns true if IP matches current protocol.
	// @return boolean
	public static function test($ip);
	
	// Provides an unpacking method for IP. Used by toBinary().
	// @return string
	public function toUnpacked();
	
	// Provides a variable that can be used with comparison operators.
	// @return mixed
	public function toComparable();
	
	// Makes sure mask is clean. Returns cleaned mask as a result.
	// @return int
	public function cleanMask($mask);
}