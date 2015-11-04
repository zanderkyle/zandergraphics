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

class CreativeContactFormControllerCreativeForm extends JControllerForm
{
	function __construct($default = array()) {
		parent::__construct($default);
	
		$this->registerTask('save', 'saveForm');
		$this->registerTask('apply', 'saveForm');
		$this->registerTask('save2new', 'saveForm');

		$this->registerTask('save2copy', 'copyForm');
	}

	function saveForm() {
		$id = JRequest::getInt('id',0);
		$model = $this->getModel('creativeform');
	
		$response = $model->saveForm();

		$msg_string = $response[0];
		$insert_id = $response[1];

		$id = ($id == 0 && $insert_id != 0) ? $insert_id : $id; 

		if ($msg_string == 'no') {
			$msg = JText::_( 'COM_CREATIVECONTACTFORM_FORM_SAVED' );
			$msg_type = 'message';
		} else {
			$msg = JText::_( $msg_string );
			$msg_type = 'error';
		}
		
		if($_REQUEST['task'] == 'apply' && $id != 0)
			$link = 'index.php?option=com_creativecontactform&view=creativeform&layout=edit&id='.$id;
		elseif($_REQUEST['task'] == 'save2new')
			$link = 'index.php?option=com_creativecontactform&view=creativeform&layout=edit';
		else
			$link = 'index.php?option=com_creativecontactform&view=creativeforms';
		$this->setRedirect($link, $msg, $msg_type);
	}

	function copyForm() {
		$link = 'index.php?option=com_creativecontactform&view=creativeforms';
		$this->setRedirect($link);
	}


}
