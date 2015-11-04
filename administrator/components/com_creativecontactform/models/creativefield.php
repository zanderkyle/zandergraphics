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

class CreativeContactFormModelCreativeField extends JModelAdmin
{
	//get max id
	public function getMax_id()
	{
		// Create a new query object.
		$db		= $this->getDbo();
		$query = 'SELECT COUNT(id) AS count_id FROM #__creative_fields';
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
	public function getTable($type = 'CreativeField', $prefix = 'CreativeFieldTable', $config = array()) 
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
		$form = $this->loadForm('com_creativecontactform.creativefield', 'creativefield', array('control' => 'jform', 'load_data' => $loadData));
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
		$data = JFactory::getApplication()->getUserState('com_creativecontactform.edit.creativefield.data', array());
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
					'UPDATE #__creative_field' .
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
	function saveField()
	{
		$date = new JDate();
		$id = JRequest::getInt('id',0);

		$max_id = $this->getMax_id();

		if($max_id >= 5 && $id == 0) {
			$response = array(0=>"COM_CREATIVECONTACTFORM_ERROR_FIELD_SAVED","1"=>0);
			return $response;
		}
		
		$req = new JObject();

		$req->id_form = (int)$_REQUEST['jform']['id_form'];
		$req->id_user = 0;
		$req->name = $_REQUEST['jform']['name'];
		$req->tooltip_text =  strip_tags($_REQUEST['jform']['tooltip_text']);
		$req->id_type = (int)$_REQUEST['jform']['id_type'];
		$req->created = '0000-00-00 00:00:00';
		$req->publish_up = '0000-00-00 00:00:00';
		$req->publish_down = '0000-00-00 00:00:00';
		$req->published = (int)$_REQUEST['jform']['published'];
		$req->checked_out = 0;
		$req->checked_out_time = '0000-00-00 00:00:00';
		$req->access = 1;
		$req->featured = 0;
		
		$req->required = (int)$_REQUEST['jform']['required'];
		$req->width = strip_tags($_REQUEST['jform']['width']);
		$req->field_margin_top = strip_tags($_REQUEST['jform']['field_margin_top']);
		$req->select_show_scroll_after = isset($_REQUEST['jform']['select_show_scroll_after']) ? (int)$_REQUEST['jform']['select_show_scroll_after'] : 10;
		$req->select_show_search_after = isset($_REQUEST['jform']['select_show_search_after']) ? (int)$_REQUEST['jform']['select_show_search_after'] : 10;
		$req->message_required = strip_tags($_REQUEST['jform']['message_required']);
		$req->message_invalid = strip_tags($_REQUEST['jform']['message_invalid']);
		$req->show_parent_label = isset($_REQUEST['jform']['show_parent_label']) ? (int)$_REQUEST['jform']['show_parent_label'] : 1;
		$req->message_invalid = strip_tags($_REQUEST['jform']['message_invalid']);

		$req->select_default_text = strip_tags($_REQUEST['jform']['select_default_text']);
		$req->select_no_match_text = strip_tags($_REQUEST['jform']['select_no_match_text']);
		$req->upload_button_text = strip_tags($_REQUEST['jform']['upload_button_text']);
		$req->upload_minfilesize = strip_tags($_REQUEST['jform']['upload_minfilesize']);
		$req->upload_maxfilesize = strip_tags($_REQUEST['jform']['upload_maxfilesize']);
		$req->upload_acceptfiletypes = strip_tags($_REQUEST['jform']['upload_acceptfiletypes']);
		$req->upload_minfilesize_message = strip_tags($_REQUEST['jform']['upload_minfilesize_message']);
		$req->upload_maxfilesize_message = strip_tags($_REQUEST['jform']['upload_maxfilesize_message']);
		$req->upload_acceptfiletypes_message = strip_tags($_REQUEST['jform']['upload_acceptfiletypes_message']);
		$req->captcha_wrong_message = strip_tags($_REQUEST['jform']['captcha_wrong_message']);
		$req->datepicker_date_format = strip_tags($_REQUEST['jform']['datepicker_date_format']);
		$req->datepicker_animation = strip_tags($_REQUEST['jform']['datepicker_animation']);

		$req->datepicker_style = (int)$_REQUEST['jform']['datepicker_style'];
		$req->datepicker_icon_style = (int)$_REQUEST['jform']['datepicker_icon_style'];
		$req->datepicker_show_icon = isset($_REQUEST['jform']['datepicker_show_icon']) ? (int)$_REQUEST['jform']['datepicker_show_icon'] : 1;
		$req->datepicker_input_readonly = (int)$_REQUEST['jform']['datepicker_input_readonly'];
		$req->datepicker_number_months = isset($_REQUEST['jform']['datepicker_number_months']) ? (int)$_REQUEST['jform']['datepicker_number_months'] : 1;

		$req->datepicker_mindate = strip_tags($_REQUEST['jform']['datepicker_mindate']);
		$req->datepicker_maxdate = strip_tags($_REQUEST['jform']['datepicker_maxdate']);

		$req->datepicker_changemonths = (int)$_REQUEST['jform']['datepicker_changemonths'];
		$req->datepicker_changeyears = (int)$_REQUEST['jform']['datepicker_changeyears'];
		$req->column_type = (int)$_REQUEST['jform']['column_type'];
		
		$req->custom_html = $_REQUEST['jform']['custom_html'];
		$req->google_maps = $_REQUEST['jform']['google_maps'];
		$req->heading = $_REQUEST['jform']['heading'];

		$req->recaptcha_site_key = strip_tags($_REQUEST['jform']['recaptcha_site_key']);
		$req->recaptcha_security_key = strip_tags($_REQUEST['jform']['recaptcha_security_key']);
		$req->recaptcha_wrong_message = strip_tags($_REQUEST['jform']['recaptcha_wrong_message']);
		$req->recaptcha_theme = strip_tags($_REQUEST['jform']['recaptcha_theme']);
		$req->recaptcha_type = strip_tags($_REQUEST['jform']['recaptcha_type']);
		$req->contact_data = $_REQUEST['jform']['contact_data'];
		$req->contact_data_width = $_REQUEST['jform']['contact_data_width'];
		$req->creative_popup = $_REQUEST['jform']["creative_popup"];
		$req->creative_popup_embed = $_REQUEST['jform']["creative_popup_embed"];


		$response = array(0=>"no","1"=>0);

		if($req->id_type == 13 || $req->id_type == 19) { // for captchas, set required to yes
			$req->required = 1;
		}

		if($req->column_type == 1 || $req->column_type == 2 ||  $req->id_type == 13 || $req->id_type == 14 || $req->id_type == 15 || $req->id_type == 16 || $req->id_type == 17 || $req->id_type == 18 || $req->id_type == 19 || $req->id_type == 20 || $req->id_type == 21 || $req->id_type == 22) {
			$response = array(0=>"COM_CREATIVECONTACTFORM_ERROR_FIELD_SAVED","1"=>0);

			return $response;
		}		


		if($id == 0) {//if id ==0, we add the record
			$req->id = NULL;

			if($req->id_type == 20) { // for contact data, set show parent label to 0
				$req->show_parent_label = 0;
			}

			//get max ordering
			$query = "SELECT MAX(`ordering`) FROM `#__creative_fields` WHERE `id_form` = '".$req->id_form."'";
			$this->_db->setQuery($query);
			$max_order = $this->_db->loadResult();
			$max_order ++;

			$req->ordering = $max_order;
	
			if (!$this->_db->insertObject( '#__creative_fields', $req, 'id' )) {
				$cis_error = "COM_CREATIVECONTACTFORM_ERROR_FIELD_SAVED";
				
				$response[0] = $cis_error;
				return $response;
			}
			$new_insert_id = $this->_db->insertid();
			$response[1] = $new_insert_id;
		}
		else { //else update the record
			$req->id = $id;
			if (!$this->_db->updateObject( '#__creative_fields', $req, 'id' )) {
				$cis_error = "COM_CREATIVECONTACTFORM_ERROR_FIELD_SAVED";
				$response[0] = $cis_error;
				return $response;
			}
		}
	
		return $response;
	}

}