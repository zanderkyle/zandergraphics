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

class CreativeContactFormModelCreativeForm extends JModelAdmin
{
	//get max id
	public function getMax_id()
	{
		// Create a new query object.
		$db		= $this->getDbo();
		$query = 'SELECT COUNT(id) AS count_id FROM #__creative_forms';
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
	public function getTable($type = 'CreativeForm', $prefix = 'CreativeFormTable', $config = array()) 
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
		$form = $this->loadForm('com_creativecontactform.creativeform', 'creativeform', array('control' => 'jform', 'load_data' => $loadData));
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
		$data = JFactory::getApplication()->getUserState('com_creativecontactform.edit.creativeform.data', array());
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
					'UPDATE #__creative_forms' .
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

	/**
	 * Method to save field
	 */
	function saveForm()
	{
		$date = new JDate();
		$id = JRequest::getInt('id',0);

		$max_id = $this->getMax_id();

		if($max_id >= 1 && $id == 0) {
			$response = array(0=>"COM_CREATIVECONTACTFORM_ERROR_FORM_SAVED","1"=>0);
			return $response;
		}
		
		$req = new JObject();

		$req->email_to = strip_tags($_REQUEST['jform']['email_to']);
		$req->email_bcc = strip_tags($_REQUEST['jform']['email_bcc']);
		$req->email_subject = strip_tags($_REQUEST['jform']['email_subject']);
		$req->email_from = strip_tags($_REQUEST['jform']['email_from']);
		$req->email_from_name = strip_tags($_REQUEST['jform']['email_from_name']);
		$req->email_replyto = strip_tags($_REQUEST['jform']['email_replyto']);
		$req->email_replyto_name = strip_tags($_REQUEST['jform']['email_replyto_name']);
		$req->shake_count = (int)$_REQUEST['jform']['shake_count'];
		$req->shake_distanse = (int)$_REQUEST['jform']['shake_distanse'];
		$req->shake_duration = (int)$_REQUEST['jform']['shake_duration'];
		$req->id_template = (int)$_REQUEST['jform']['id_template'];
		$req->name = strip_tags($_REQUEST['jform']['name']);
		$req->top_text = strip_tags($_REQUEST['jform']['top_text']);
		$req->pre_text = strip_tags($_REQUEST['jform']['pre_text']);
		$req->thank_you_text = strip_tags($_REQUEST['jform']['thank_you_text']);
		$req->send_text = strip_tags($_REQUEST['jform']['send_text']);
		$req->send_new_text = strip_tags($_REQUEST['jform']['send_new_text']);
		$req->close_alert_text = strip_tags($_REQUEST['jform']['close_alert_text']);
		$req->form_width = strip_tags($_REQUEST['jform']['form_width']);
		$req->name = strip_tags($_REQUEST['jform']['name']);
		$req->name = strip_tags($_REQUEST['jform']['name']);
		$req->name = strip_tags($_REQUEST['jform']['name']);
		$req->name = strip_tags($_REQUEST['jform']['name']);
		$req->name = strip_tags($_REQUEST['jform']['name']);
		$req->name = strip_tags($_REQUEST['jform']['name']);
		$req->alias = '';
		$req->created = '0000-00-00 00:00:00';
		$req->publish_up = '0000-00-00 00:00:00';
		$req->publish_down = '0000-00-00 00:00:00';
		$req->published = (int)$_REQUEST['jform']['published'];
		$req->checked_out = 0;
		$req->checked_out_time = '0000-00-00 00:00:00';
		$req->access = 1;
		$req->featured = 0;
		$req->language = '';
		$req->redirect = strip_tags($_REQUEST['jform']['redirect']);
		$req->redirect_itemid = (int)$_REQUEST['jform']['redirect_itemid'];
		$req->redirect_url = $_REQUEST['jform']['redirect_url'];
		$req->redirect_delay = (int)$_REQUEST['jform']['redirect_delay'];
		$req->send_copy_enable = (int)$_REQUEST['jform']['send_copy_enable'];
		$req->send_copy_text = strip_tags($_REQUEST['jform']['send_copy_text']);
		$req->show_back = (int)$_REQUEST['jform']['show_back'];

		$req->email_info_show_referrer = (int)$_REQUEST['jform']['email_info_show_referrer'];
		$req->email_info_show_ip = (int)$_REQUEST['jform']['email_info_show_ip'];
		$req->email_info_show_browser = (int)$_REQUEST['jform']['email_info_show_browser'];
		$req->email_info_show_os = (int)$_REQUEST['jform']['email_info_show_os'];
		$req->email_info_show_sc_res = (int)$_REQUEST['jform']['email_info_show_sc_res'];
		$req->custom_css = strip_tags($_REQUEST['jform']['custom_css']);

		$response = array(0=>"no","1"=>0);

		if($id == 0) {//if id ==0, we add the record
			$req->id = NULL;

			//get max ordering
			$query = "SELECT MAX(`ordering`) FROM `#__creative_forms`";
			$this->_db->setQuery($query);
			$max_order = $this->_db->loadResult();
			$max_order ++;

			$req->ordering = $max_order;
	
			if (!$this->_db->insertObject( '#__creative_forms', $req, 'id' )) {
				$cis_error = "COM_CREATIVECONTACTFORM_ERROR_FORM_SAVED";
				
				$response[0] = $cis_error;
				return $response;
			}
			$new_insert_id = $this->_db->insertid();
			$response[1] = $new_insert_id;
		}
		else { //else update the record
			$req->id = $id;
			if (!$this->_db->updateObject( '#__creative_forms', $req, 'id' )) {
				$cis_error = "COM_CREATIVECONTACTFORM_ERROR_FORM_SAVED";
				$response[0] = $cis_error;
				return $response;
			}
		}
	
		return $response;
	}

	function deleteForm($pks) {
		if(is_array($pks)) {
			foreach($pks as $f_id) {
				//delete form
				$query = "DELETE FROM `#__creative_forms` WHERE `id` = '".$f_id."'";
				$this->_db->setQuery($query);
				$this->_db->query();

				//delete fields
				$query = "DELETE FROM `#__creative_fields` WHERE `id_form` = '".$f_id."'";
				$this->_db->setQuery($query);
				$this->_db->query();
			}
		}
	}


}