<?php
/**
* @package RSFirewall!
* @copyright (C) 2009-2014 www.rsjoomla.com
* @license GPL, http://www.gnu.org/licenses/gpl-2.0.html
*/

defined('_JEXEC') or die('Restricted access');

class RSFirewallModelUpdates extends JModelLegacy
{
	public function getHash() {
		$version = new RSFirewallVersion();
		return md5(RSFirewallConfig::getInstance()->get('code').$version->key);
	}
	
	public function getJoomlaVersion() {
		$jversion = new JVersion();
		return $jversion->getShortVersion();
	}
	
	public function getSideBar() {
		require_once JPATH_COMPONENT.'/helpers/toolbar.php';
		
		return RSFirewallToolbarHelper::render();
	}
}