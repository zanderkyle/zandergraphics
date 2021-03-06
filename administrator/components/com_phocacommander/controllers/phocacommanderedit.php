<?php
/*
 * @package		Joomla.Framework
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @component Phoca Component
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License version 2 or later;
 */
defined('_JEXEC') or die();
jimport('joomla.application.component.controllerform');

class PhocaCommanderCpControllerPhocaCommanderEdit extends JControllerForm
{
	protected	$option 		= 'com_phocacommander';
	

	function __construct($config=array()) {
		parent::__construct($config);
		
		$app   			= JFactory::getApplication();
		$context 		= 'com_phocacommander.phocacommander.';
		$orderinga 		= $app->input->get('orderinga', '', 'string');
		$orderingb 		= $app->input->get('orderingb', '', 'string');
		$directiona 	= $app->input->get('directiona', '', 'string');
		$directionb 	= $app->input->get('directionb', '', 'string');
		$activepanel 	= $app->input->get('activepanel', '', 'string');
		$panel 			= $app->input->get('panel', '', 'string');
		$foldera 		= $app->input->get('foldera', '', 'string');
		$folderb 		= $app->input->get('folderb', '', 'string');
		
		if(JRequest::checkToken()) {
			$app->input->post->set('orderinga', $orderinga);
			$app->input->post->set('orderingb', $orderingb);
			$app->input->post->set('directiona', $directiona);
			$app->input->post->set('directionb', $directionb);
			$app->input->post->set('foldera', $foldera);
			$app->input->post->set('folderb', $folderb);
			$app->input->post->set('activepanel', $activepanel);
			$app->input->post->set('panel', $panel);
			

			$app->getUserStateFromRequest($context .'orderinga', 'orderinga', $orderinga, 'string');
			$app->getUserStateFromRequest($context .'orderingb', 'orderingb', $orderingb, 'string');
			$app->getUserStateFromRequest($context .'directiona', 'directiona', $directiona, 'string');
			$app->getUserStateFromRequest($context .'directionb', 'directionb', $directionb, 'string');
			$app->getUserStateFromRequest($context .'panel', 'panel', $panel, 'string');
			$app->getUserStateFromRequest($context .'activepanel', 'activepanel', $activepanel, 'string');
			$app->getUserStateFromRequest($context .'foldera', 'foldera', $foldera, 'string');
			$app->getUserStateFromRequest($context .'folderb', 'folderb', $folderb, 'string');
		}
		
	}
	
	protected function allowEdit($data = array(), $key = 'id') {
		$user		= JFactory::getUser();
		$allow		= null;
		$allow		= $user->authorise('core.edit', 'com_phocacommander');
		
		if ($allow === null) {
			return parent::allowEdit($data, $key);
		} else {
			return $allow;
		}
	}
	
	public function cancel($key = null)
	{
		JRequest::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		$this->setRedirect(JRoute::_('index.php?option=com_phocacommander'.$this->getRedirectToListAppend(), false));

		return true;
	}
	
	public function edit($key = null, $urlVar = null) {
	
		$app   		= JFactory::getApplication();
		$context 	= "$this->option.edit.$this->context";
		$file		= $app->input->get( 'filename', '', 'string'  );
		$recordId 	= 1;
		$key = $urlVar 	= 'id';

		if (!$this->allowEdit(array($key => $recordId), $key))
		{
			$this->setError(JText::_('JLIB_APPLICATION_ERROR_EDIT_NOT_PERMITTED'));
			$this->setMessage($this->getError(), 'error');

			$this->setRedirect(
				JRoute::_(
					'index.php?option=' . $this->option . '&view=' . $this->view_list
					. $this->getRedirectToListAppend() . '&file='.$file, false
				)
			);

			return false;
		}
		
		$this->holdEditId($context, $recordId);
		$app->setUserState($context . '.data', null);

		$this->setRedirect(
			JRoute::_(
				'index.php?option=' . $this->option . '&view=' . $this->view_item
				. $this->getRedirectToItemAppend($recordId, $urlVar) . '&file='.$file, false
			)
		);

		return true;
	}
	
	public function save($key = null, $urlVar = null)
	{
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		$app   = JFactory::getApplication();
		$lang  = JFactory::getLanguage();
		$model = $this->getModel();

		$data  = $this->input->post->get('jform', array(), 'array');
		$context = "$this->option.edit.$this->context";
		$task = $this->getTask();

		$key = $urlVar 	= 'id';

		$recordId = $this->input->getInt($urlVar);

		$data[$key] = $recordId;
		
		// Access check.
		if (!$this->allowSave($data, $key))
		{
			$this->setError(JText::_('JLIB_APPLICATION_ERROR_SAVE_NOT_PERMITTED'));
			$this->setMessage($this->getError(), 'error');

			$this->setRedirect(
				JRoute::_(
					'index.php?option=' . $this->option . '&view=' . $this->view_list
					. $this->getRedirectToListAppend(). '&file='.$data['filename'] , false
				)
			);

			return false;
		}

		// Validate the posted data.
		// Sometimes the form needs some posted data, such as for plugins and modules.
		$form = $model->getForm($data, false);

		if (!$form)
		{
			$app->enqueueMessage($model->getError(), 'error');

			return false;
		}

		// Test whether the data is valid.
		$validData = $model->validate($form, $data);

		// Check for validation errors.
		if ($validData === false)
		{
			// Get the validation messages.
			$errors = $model->getErrors();

			// Push up to three validation messages out to the user.
			for ($i = 0, $n = count($errors); $i < $n && $i < 3; $i++)
			{
				if ($errors[$i] instanceof Exception)
				{
					$app->enqueueMessage($errors[$i]->getMessage(), 'warning');
				}
				else
				{
					$app->enqueueMessage($errors[$i], 'warning');
				}
			}

			// Save the data in the session.
			$app->setUserState($context . '.data', $data);

			// Redirect back to the edit screen.
			$this->setRedirect(
				JRoute::_(
					'index.php?option=' . $this->option . '&view=' . $this->view_item
					. $this->getRedirectToItemAppend($recordId, $urlVar). '&file='.$data['filename'], false
				)
			);

			return false;
		}

		if (!isset($validData['tags']))
		{
			$validData['tags'] = null;
		}

		// Attempt to save the data.
		if (!$model->save($validData))
		{
			
			
			// Save the data in the session.
			$app->setUserState($context . '.data', $validData);

			// Redirect back to the edit screen.
			$this->setError(JText::sprintf('JLIB_APPLICATION_ERROR_SAVE_FAILED', $model->getError()));
			$this->setMessage($this->getError(), 'error');

			$this->setRedirect(
				JRoute::_(
					'index.php?option=' . $this->option . '&view=' . $this->view_item
					. $this->getRedirectToItemAppend($recordId, $urlVar). '&file='.$data['filename'], false
				)
			);

			return false;
		}

		$this->setMessage(
			JText::_(
				($lang->hasKey($this->text_prefix . ($recordId == 0 && $app->isSite() ? '_SUBMIT' : '') . '_SAVE_SUCCESS')
					? $this->text_prefix
					: 'JLIB_APPLICATION') . ($recordId == 0 && $app->isSite() ? '_SUBMIT' : '') . '_SAVE_SUCCESS'
			)
		);

		// Redirect the user and adjust session state based on the chosen task.
		
		switch ($task)
		{
			case 'apply':
				// Set the record data in the session.
				//$recordId = $model->getState($this->context . '.id');
				
				$this->holdEditId($context, $recordId);
				$app->setUserState($context . '.data', null);
				//$model->checkout($recordId);

				// Redirect back to the edit screen.
				$this->setRedirect(
					JRoute::_(
						'index.php?option=' . $this->option . '&view=' . $this->view_item
						. $this->getRedirectToItemAppend($recordId, $urlVar). '&file='.$data['filename'], false
					)
				);
				break;



			default:
				// Clear the record id and data from the session.
				$this->releaseEditId($context, $recordId);
				$app->setUserState($context . '.data', null);

				// Redirect to the list screen.
				$this->setRedirect(
					JRoute::_(
						'index.php?option=' . $this->option , false
					)
				);
				break;
		}

		
		$this->postSaveHook($model, $validData);

		return true;
	}
}
?>
