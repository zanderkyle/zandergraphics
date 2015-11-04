<?php
/**
* @package RSFirewall!
* @copyright (C) 2009-2014 www.rsjoomla.com
* @license GPL, http://www.gnu.org/licenses/gpl-2.0.html
*/

defined('_JEXEC') or die('Restricted access');

class RSFirewallTableIgnored extends JTable
{
	/**
	 * Primary Key
	 *
	 * @var int
	 */
	public $id = null;
	
	public $path = null;
	public $type = null;
		
	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 */
	function __construct(& $db) {
		parent::__construct('#__rsfirewall_ignored', 'id', $db);
	}
}