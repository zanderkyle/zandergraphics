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

jimport('joomla.application.component.controllerform');

class CreativeContactFormControllerCreativeField extends JControllerForm
{
	function __construct($default = array()) {
		parent::__construct($default);
	
		$this->registerTask('add', 'addfield');

		$this->registerTask('save', 'saveField');
		$this->registerTask('apply', 'saveField');
		$this->registerTask('save2new', 'saveField');

		$this->registerTask('cancel', 'closeField');
		$this->registerTask('close', 'closeField');

		$this->registerTask('save2copy', 'copyField');

	}
	
	function addfield() {
		$form_id = (int)$_REQUEST['filter_form_id'];
		$link = 'index.php?option=com_creativecontactform&view=creativefield&layout=edit&filter_form_id='.$form_id;
		$this->setRedirect($link, $msg);
	}	


	function saveField() {
		$id = JRequest::getInt('id',0);
		$form_id = (int)$_REQUEST['jform']['id_form'];
		$model = $this->getModel('creativefield');
	
		$response = $model->saveField();

		$msg_string = $response[0];
		$insert_id = $response[1];

		$id = ($id == 0 && $insert_id != 0) ? $insert_id : $id; 

		if ($msg_string == 'no') {
			$msg = JText::_( 'COM_CREATIVECONTACTFORM_FIELD_SAVED' );
			$msg_type = 'message';
		} else {
			$msg = JText::_( $msg_string );
			$msg_type = 'error';
		}
		
		if($_REQUEST['task'] == 'apply' && $id != 0)
			$link = 'index.php?option=com_creativecontactform&view=creativefield&layout=edit&id='.$id;
		elseif($_REQUEST['task'] == 'save2new')
			$link = 'index.php?option=com_creativecontactform&view=creativefield&layout=edit&filter_form_id='.$form_id;
		else
			$link = 'index.php?option=com_creativecontactform&view=creativefields&filter_form_id='.$form_id;
		$this->setRedirect($link, $msg, $msg_type);
	}

	function copyField() {
		$form_id = (int)$_REQUEST['jform']['id_form'];
		$link = 'index.php?option=com_creativecontactform&view=creativefields&filter_form_id='.$form_id;
		$this->setRedirect($link);
	}

	function closeField() {
		$form_id = (int)$_REQUEST['jform']['id_form'];
	
		$link = 'index.php?option=com_creativecontactform&view=creativefields&filter_form_id='.$form_id;
		$this->setRedirect($link);
	}
}
