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

jimport('joomla.application.component.controlleradmin');

class CreativeContactFormControllerCreativeFields extends JControllerAdmin
{
	/**
	 * Constructor.
	 *
	 * @param	array	$config	An optional associative array of configuration settings.
	 *
	 * @return	ContactControllerContacts
	 * @see		JController
	 * @since	1.6
	 */
	public function __construct($config = array())
	{
		parent::__construct($config);

		$this->registerTask('publish', 'publishField');
		$this->registerTask('unpublish', 'publishField');

		$this->registerTask('delete', 'deleteField');

	}

	public function publishField() {
		$pks   = $this->input->post->get('cid', array(), 'array');

		// Get the model
		$model = $this->getModel();

		$val = $_REQUEST['task'] == 'publish' ? 1 : 0;

		$result = $model->publish($pks,$val);

		$form_id = (int)$_REQUEST['filter_form_id'];
		$link = 'index.php?option=com_creativecontactform&view=creativefields&filter_form_id='.$form_id;
		$this->setRedirect($link);
	}


	public function deleteField() {
		$pks   = $this->input->post->get('cid', array(), 'array');

		// Get the model
		$model = $this->getModel();

		$result = $model->delete($pks);

		$form_id = (int)$_REQUEST['filter_form_id'];
		$link = 'index.php?option=com_creativecontactform&view=creativefields&filter_form_id='.$form_id;
		$this->setRedirect($link);
	}


	/**
	 * Proxy for getModel.
	 *
	 * @param	string	$name	The name of the model.
	 * @param	string	$prefix	The prefix for the PHP class name.
	 *
	 * @return	JModel
	 * @since	1.6
	 */
	public function getModel($name = 'creativefield', $prefix = 'CreativeContactFormModel', $config = array('ignore_request' => true))
	{
		$model = parent::getModel($name, $prefix, $config);

		return $model;
	}
	
	/**
	 * Method to save the submitted ordering values for records via AJAX.
	 *
	 * @return	void
	 *
	 * @since   3.0
	 */
	public function saveOrderAjax()
	{
		// Get the input
		$pks   = $this->input->post->get('cid', array(), 'array');
		$order = $this->input->post->get('order', array(), 'array');
		// Sanitize the input
		JArrayHelper::toInteger($pks);
		JArrayHelper::toInteger($order);
	
		// Get the model
		$model = $this->getModel();
	
		// Save the ordering
		$return = $model->saveorder($pks, $order);
	
		if ($return)
		{
			echo "1";
		}
	
		// Close the application
		JFactory::getApplication()->close();
	}
}
