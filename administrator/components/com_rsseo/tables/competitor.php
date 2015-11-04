<?php
/**
* @version 1.0.0
* @package RSSEO! 1.0.0
* @copyright (C) 2009-2012 www.rsjoomla.com
* @license GPL, http://www.gnu.org/copyleft/gpl.html
*/

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

class rsseoTableCompetitor extends JTable
{
	/**
	 * @param	JDatabase	A database connector object
	 */
	public function __construct($db) {
		parent::__construct('#__rsseo_competitors', 'id', $db);
	}
	
	/**
	 * Method to delete a node and, optionally, its child nodes from the table.
	 *
	 * @param   integer  $pk        The primary key of the node to delete.
	 * @param   boolean  $children  True to delete child nodes, false to move them up a level.
	 *
	 * @return  boolean  True on success.
	 *
	 * @see     http://docs.joomla.org/JTable/delete
	 * @since   2.5
	 */
	public function delete($pk = null, $children = false) {
		$db		= JFactory::getDBO();
		$query	= $db->getQuery(true);
		
		// Remove the history
		$query->clear();
		$query->delete();
		$query->from($db->quoteName('#__rsseo_competitors'));
		$query->where($db->quoteName('parent_id').' = '.$db->quote($pk));
		
		$db->setQuery($query);
		$db->execute();
		
		return parent::delete($pk, $children);
	}
}