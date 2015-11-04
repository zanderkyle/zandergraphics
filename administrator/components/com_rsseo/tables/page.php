<?php
/**
* @version 1.0.0
* @package RSSEO! 1.0.0
* @copyright (C) 2009-2012 www.rsjoomla.com
* @license GPL, http://www.gnu.org/copyleft/gpl.html
*/

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

class rsseoTablePage extends JTable
{
	/**
	 * @param	JDatabase	A database connector object
	 */
	public function __construct($db) {
		parent::__construct('#__rsseo_pages', 'id', $db);
	}
	
	/**
	 * Overloaded bind function
	 *
	 * @param   array  $array   Named array
	 * @param   mixed  $ignore  An optional array or space separated list of properties
	 * to ignore while binding.
	 *
	 * @return  mixed  Null if operation was satisfactory, otherwise returns an error string
	 *
	 * @see     JTable::bind
	 * @since   11.1
	 */
	public function bind($array, $ignore = '') {
		if (isset($array['robots']) && is_array($array['robots'])) {
			$registry = new JRegistry;
			$registry->loadArray($array['robots']);
			$array['robots'] = (string) $registry;
		}

		return parent::bind($array, $ignore);
	}
	
	/**
	 * Overloaded check function
	 *
	 * @return  boolean  True on success, false on failure
	 *
	 * @see     JTable::check
	 * @since   11.1
	 */
	public function check() {
		$jinput = JFactory::getApplication()->input->get('jform',array(),'array');
		
		$this->url		= str_replace(array('&amp;','&apos;','&quot;','&gt;','&lt;'),array("&","'",'"',">","<"),$this->url);
		$this->url		= str_replace(array("&","'",'"',">","<"),array('&amp;','&apos;','&quot;','&gt;','&lt;'),$this->url);
		$this->url		= trim($this->url);
		$this->modified	= 1;
		
		if (isset($jinput['original']) && $jinput['original'] == 1) {
			$this->modified = 0;
			$this->crawled = 0;
		}
		return true;
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
		if ($pk == 1) {
			$this->setError(JText::_('COM_RSSEO_CANNOT_DELETE_HOME_PAGE'));
			return false;
		}
		
		return parent::delete($pk, $children);
	}
}