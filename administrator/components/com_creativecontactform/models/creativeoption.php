<?php
/**
 * Joomla! component Creative Contact Form
 *
 * @version $Id: 2012-04-05 14:30:25 svn $
 * @author creative-solutions.net
 * @package Creative Contact Form
 * @subpackage com_creativecontactform
 * @license GNU/GPL
 *
 */

// no direct access
defined('_JEXEC') or die('Restircted access');

// import Joomla modelform library
jimport('joomla.application.component.modeladmin');

class CreativeContactFormModelCreativeOption extends JModelAdmin
{
	//get max id
	public function getMax_id()
	{
		// Create a new query object.
		$db		= $this->getDbo();
		$query = 'SELECT COUNT(id) AS count_id FROM #__creative_options';
		$db->setQuery($query);
		$max_id = $db->loadResult();
		return $max_id;
	}
	/**
	 * Returns a reference to the a Table object, always creating it.
	 *
	 * @param	type	The table type to instantiate
	 * @param	string	A prefix for the table class name. Optional.
	 * @param	array	Configuration array for model. Optional.
	 * @return	JTable	A database object
	 * @since	1.6
	 */
	public function getTable($type = 'CreativeOption', $prefix = 'CreativeOptionTable', $config = array()) 
	{
		return JTable::getInstance($type, $prefix, $config);
	}
	/**
	 * Method to get the record form.
	 *
	 * @param	array	$data		Data for the form.
	 * @param	boolean	$loadData	True if the form is to load its own data (default case), false if not.
	 * @return	mixed	A JForm object on success, false on failure
	 * @since	1.6
	 */
	public function getForm($data = array(), $loadData = true) 
	{
		// Get the form.
		$form = $this->loadForm('com_creativecontactform.creativeoption', 'creativeoption', array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form)) 
		{
			return false;
		}
		return $form;
	}
	/**
	 * Method to get the data that should be injected in the form.
	 *
	 * @return	mixed	The data for the form.
	 * @since	1.6
	 */
	protected function loadFormData() 
	{
		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState('com_creativecontactform.edit.creativeoption.data', array());
		if (empty($data)) 
		{
			$data = $this->getItem();
		}
			$data = $this->getItem();
		return $data;
	}
	
	protected function canEditState($record)
	{
		return parent::canEditState($record);
	}
	
	
	/**
	 * Method to toggle the featured setting of contacts.
	 *
	 * @param	array	$pks	The ids of the items to toggle.
	 * @param	int		$value	The value to toggle to.
	 *
	 * @return	boolean	True on success.
	 * @since	1.6
	 */
	public function featured($pks, $value = 0)
	{
		// Sanitize the ids.
		$pks = (array) $pks;
		JArrayHelper::toInteger($pks);
	
		if (empty($pks)) {
			$this->setError(JText::_('COM_CREATIVECONTACTFORM_NO_ITEM_SELECTED'));
			return false;
		}
	
		$table = $this->getTable();
	
		try
		{
			$db = $this->getDbo();
	
			$db->setQuery(
					'UPDATE #__creative_options' .
					' SET featured = '.(int) $value.
					' WHERE id IN ('.implode(',', $pks).')'
			);
			if (!$db->query()) {
				throw new Exception($db->getErrorMsg());
			}
	
		}
		catch (Exception $e)
		{
			$this->setError($e->getMessage());
			return false;
		}
	
		$table->reorder();
	
		// Clean component's cache
		$this->cleanCache();
	
		return true;
	}
}