<?php
/**
* @package RSFirewall!
* @copyright (C) 2009-2014 www.rsjoomla.com
* @license GPL, http://www.gnu.org/licenses/gpl-2.0.html
*/

defined('_JEXEC') or die('Restricted access');

class RSFirewallTableSnapshots extends JTable
{
	/**
	 * Primary Key
	 *
	 * @var int
	 */
	public $id = null;
	
	public $user_id = null;
	public $snapshot = null;
	public $type = null;
		
	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 */
	function __construct(& $db) {
		parent::__construct('#__rsfirewall_snapshots', 'id', $db);
	}
}