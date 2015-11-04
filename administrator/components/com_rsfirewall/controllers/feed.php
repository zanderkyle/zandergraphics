<?php
/**
* @package RSFirewall!
* @copyright (C) 2009-2014 www.rsjoomla.com
* @license GPL, http://www.gnu.org/licenses/gpl-2.0.html
*/

defined('_JEXEC') or die('Restricted access');

class RSFirewallControllerFeed extends JControllerForm
{
	public function __construct() {
		parent::__construct();
	}
	
	protected function allowAdd($data = array()) {
		$user = JFactory::getUser();
		return $user->authorise('feeds.manage', 'com_rsfirewall');
	}

	protected function allowEdit($data = array(), $key = 'id') {
		$user = JFactory::getUser();
		return $user->authorise('feeds.manage', 'com_rsfirewall');
	}
}