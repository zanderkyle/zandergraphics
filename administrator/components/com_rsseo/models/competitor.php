<?php
/**
* @version 1.0.0
* @package RSSeo! 1.0.0
* @copyright (C) 2009-2012 www.rsjoomla.com
* @license GPL, http://www.gnu.org/copyleft/gpl.html
*/

defined('_JEXEC') or die();
jimport( 'joomla.application.component.model' );

class rsseoModelCompetitor extends JModelAdmin
{
	protected $text_prefix = 'COM_RSSEO';

	public function getTable($type = 'Competitor', $prefix = 'rsseoTable', $config = array()) {
		return JTable::getInstance($type, $prefix, $config);
	}
	
	public function getItem($pk = null) {
		return parent::getItem($pk);
	}
	
	public function getForm($data = array(), $loadData = true) {
		$jinput = JFactory::getApplication()->input;
		
		// Get the form.
		$form = $this->loadForm('com_rsseo.competitor', 'competitor', array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form))
			return false;
		
		if ($jinput->get('id')) {
			$form->setFieldAttribute('name', 'readonly', 'true');
		} else {
			$form->setValue('name', null, 'http://');
		}
		
		return $form;
	}
	
	protected function loadFormData() {
		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState('com_rsseo.edit.competitor.data', array());

		if (empty($data))
			$data = $this->getItem();

		return $data;
	}
	
	public function save($data) {
		// Initialise variables;
		$table = $this->getTable();
		$pk = (!empty($data['id'])) ? $data['id'] : (int) $this->getState($this->getName() . '.id');
		$isNew = true;

		// Load the row if saving an existing tag.
		if ($pk > 0) {
			$table->load($pk);
			$isNew = false;
		}

		// Bind the data.
		if (!$table->bind($data)) {
			$this->setError($table->getError());
			return false;
		}

		// Check the data.
		if (!$table->check()) {
			$this->setError($table->getError());
			return false;
		}

		// Store the data.
		if (!$table->store()) {
			$this->setError($table->getError());
			return false;
		}
		
		if ($isNew) {
			require_once JPATH_ADMINISTRATOR. '/components/com_rsseo/helpers/competitors.php';
			$competitor = competitorsHelper::getInstance($table->id, $table->name);
			$competitor->check();
		}
		
		$this->setState($this->getName() . '.id', $table->id);
		
		return true;
	}
}