<?php
/**
* @package RSFirewall!
* @copyright (C) 2009-2014 www.rsjoomla.com
* @license GPL, http://www.gnu.org/licenses/gpl-2.0.html
*/

defined('_JEXEC') or die('Restricted access');

class RSFirewallVersion
{
	public $version = '2.8.14';
	public $key		= 'FW6AL534B2';
	// Unused
	public $revision = null;
	
	public function __toString() {
		return $this->version;
	}
}