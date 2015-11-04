<?php
/**
* @package RSFirewall!
* @copyright (C) 2009-2014 www.rsjoomla.com
* @license GPL, http://www.gnu.org/licenses/gpl-2.0.html
*/

defined('_JEXEC') or die('Restricted access');

class RSFirewallTableLogs extends JTable
{
	/**
	 * Primary Key
	 *
	 * @var int
	 */
	public $id = null;
	
	public $level 			 = null;
	public $date 			 = null;
	public $ip 			 = null;
	public $user_id 		 = null;
	public $username 		 = null;
	public $page 			 = null;
	public $referer		 = null;
	public $code 			 = null;
	public $debug_variables = null;
		
	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 */
	function __construct(& $db) {
		parent::__construct('#__rsfirewall_logs', 'id', $db);
	}
}