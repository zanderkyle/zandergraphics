<?php
/**
* @package RSFirewall!
* @copyright (C) 2009-2014 www.rsjoomla.com
* @license GPL, http://www.gnu.org/licenses/gpl-2.0.html
*/

defined('_JEXEC') or die('Restricted access');

class RSFirewallTableOffenders extends JTable
{
	/**
	 * Primary Key
	 *
	 * @public int
	 */
	public $id 	 = null;
	public $ip 	 = null;
	public $date = null;
		
	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 */
	public function __construct(& $db) {
		parent::__construct('#__rsfirewall_offenders', 'id', $db);
	}
	
	public function store($updateNulls = false) {
		if (!$this->id) {
			$this->date = JFactory::getDate()->toSql();
		}
		
		return parent::store($updateNulls);
	}
}