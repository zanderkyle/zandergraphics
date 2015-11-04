
<?php
/**
* @version 1.0.0
* @package RSSEO! 1.0.0
* @copyright (C) 2009-2012 www.rsjoomla.com
* @license GPL, http://www.gnu.org/copyleft/gpl.html
*/

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

class rsseoTableKeyword extends JTable
{
	/**
	 * @param	JDatabase	A database connector object
	 */
	public function __construct($db) {
		parent::__construct('#__rsseo_keywords', 'id', $db);
	}
}