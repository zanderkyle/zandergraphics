<?php
/**
* @package RSFirewall!
* @copyright (C) 2009-2014 www.rsjoomla.com
* @license GPL, http://www.gnu.org/licenses/gpl-2.0.html
*/

defined('_JEXEC') or die('Restricted access');

class RSFirewallModelLog extends JModelAdmin
{
	public function getTable($type = 'Logs', $prefix = 'RSFirewallTable', $config = array()) {
		$table = JTable::getInstance($type, $prefix, $config);
		return $table;
	}
	
	public function getForm($data = array(), $loadData = true) {
		// Get the form.
		$form = $this->loadForm('com_rsfirewall.log', 'log', array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form)) {
			return false;
		}

		return $form;
	}
	
	protected function loadFormData() {
		// Check the session for previously entered form data.
		$app  = JFactory::getApplication();
		$data = $app->getUserState('com_rsfirewall.edit.log.data', array());

		if (empty($data)) {
			$data = $this->getItem();
		}

		return $data;
	}
	
	public function truncate() {
		$db = JFactory::getDBO();
		$db->truncateTable('#__rsfirewall_logs');
		
		RSFirewallLogger::getInstance()->add('critical', 'LOG_EMPTIED')->save();
	}
	
	public function prepareData($ids) {
		$table = $this->getTable();
		
		$data = array();
		foreach ($ids as $id) {
			if ($table->load($id)) {
				$data[] = $table->ip;
			}
		}
		
		return array_unique($data);
	}
}