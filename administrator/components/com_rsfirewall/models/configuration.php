<?php
/**
* @package RSFirewall!
* @copyright (C) 2009-2014 www.rsjoomla.com
* @license GPL, http://www.gnu.org/licenses/gpl-2.0.html
*/

defined('_JEXEC') or die('Restricted access');

class RSFirewallModelConfiguration extends JModelAdmin
{
	protected $geoip;
	
	public function __construct() {		
		$this->geoip = (object) array(
			'path' => JPATH_COMPONENT.'/assets/geoip/',
			'filename' => 'GeoIP.dat'
		);
		
		parent::__construct();
	}
	
	public function getForm($data = array(), $loadData = true) {
		// Get the form.
		$form = $this->loadForm('com_rsfirewall.configuration', 'configuration', array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form)) {
			return false;
		}

		return $form;
	}
	
	protected function loadFormData() {
		$data = (array) $this->getConfig()->getData();
		
		if (!empty($data['backend_password'])) {
			$data['backend_password'] = '';
		}
		
		return $data;
	}
	
	/* GeoIP functions */
	public function getIsGeoIPUploaded() {
		$path 		= $this->getGeoIPPath();
		$filename 	= $this->getGeoIPFileName();
		
		return file_exists($path.$filename);
	}
	
	public function getGeoIPPath() {
		return $this->geoip->path;
	}
	
	public function getGeoIPFileName() {
		return $this->geoip->filename;
	}
	
	public function isGeoIPNative() {
		return function_exists('geoip_database_info');
	}
	
	public function hasGeoIPNativeDatabase() {
		if (function_exists('geoip_db_avail') && defined('GEOIP_COUNTRY_EDITION')) {
			return geoip_db_avail(GEOIP_COUNTRY_EDITION);
		}
		
		return false;
	}
	
	public function uploadGeoIP() {
		// input
		$input 		= JFactory::getApplication()->input;
		$files  	= $input->files->get('jform', null, 'raw');
		
		if (isset($files['geoip_upload'])) {
			// file requested
			$file =& $files['geoip_upload'];
			// path & filename
			$path  		= $this->getGeoIPPath();
			$filename 	= $this->getGeoIPFileName();
			
			if ($file['tmp_name']) {
				// file already exists but isn't writable (can't overwrite)
				if (file_exists($path.$filename) && !is_writable($path.$filename)) {
					$this->setError(JText::sprintf('COM_RSFIREWALL_GEOIP_DB_EXISTS_NOT_WRITABLE', $path));
					return false;
				}
				// file doesn't exist - the directory must be writable in this case
				if (!file_exists($path.$filename) && !is_writable(dirname($path))) {
					$this->setError(JText::sprintf('COM_RSFIREWALL_GEOIP_DB_FOLDER_NOT_WRITABLE', $path));
					return false;
				}
			}
			
			// uploaded & no error
			if ($file['tmp_name'] && !$file['error']) {
				jimport('joomla.filesystem.file');
				
				// check extension is .dat or .gz
				$ext = JFile::getExt($file['name']);
				
				if ($ext != 'dat' && $ext != 'gz') {
					$this->setError(JText::_('COM_RSFIREWALL_GEOIP_DB_INCORRECT_FORMAT'));
					return false;
				}
				
				if($ext == 'gz') {
					$gzHandle = @gzopen($file['tmp_name'], 'rb');
					if (!$gzHandle) {
						$this->setError(JText::sprintf('COM_RSFIREWALL_GEOIP_DB_UNABLE_TO_READ', $file['name']));
						return false;
					}
					
					while (!gzeof($gzHandle)) {
						$data = gzread($gzHandle, 1024*1024);
						file_put_contents($path.$filename, $data, FILE_APPEND);
					}
					gzclose($gzHandle);
				} else {
					if (!JFile::upload($file['tmp_name'], $path.$filename, false, true)){
						$this->setError(JText::_('COM_RSFIREWALL_GEOIP_COULD_NOT_UPLOAD'));
						return false;
					}
				}
				
				return true;
			} elseif ($file['error'] != UPLOAD_ERR_NO_FILE) {
				if ($file['error'] == UPLOAD_ERR_INI_SIZE) {
					$this->setError(JText::_('COM_RSFIREWALL_GEOIP_COULD_NOT_UPLOAD_ERR_INI_SIZE'));
				} elseif ($file['error'] == UPLOAD_ERR_FORM_SIZE) {
					$this->setError(JText::_('COM_RSFIREWALL_GEOIP_COULD_NOT_UPLOAD_ERR_FORM_SIZE'));
				} elseif ($file['error'] == UPLOAD_ERR_PARTIAL) {
					$this->setError(JText::_('COM_RSFIREWALL_GEOIP_COULD_NOT_UPLOAD_ERR_PARTIAL'));
				} elseif ($file['error'] == UPLOAD_ERR_NO_TMP_DIR) {
					$this->setError(JText::_('COM_RSFIREWALL_GEOIP_COULD_NOT_UPLOAD_ERR_NO_TMP_DIR'));
				} elseif ($file['error'] == UPLOAD_ERR_CANT_WRITE) {
					$this->setError(JText::_('COM_RSFIREWALL_GEOIP_COULD_NOT_UPLOAD_ERR_CANT_WRITE'));
				} elseif ($file['error'] == UPLOAD_ERR_EXTENSION) {
					$this->setError(JText::_('COM_RSFIREWALL_GEOIP_COULD_NOT_UPLOAD_ERR_EXTENSION'));
				}
				return false;
			}
		}
		
		return true;
	}
	
	public function save($data) {
		// upload geoip only if it's not built-in
		if (!$this->isGeoIPNative() && !$this->uploadGeoIP()) {
			return false;
		}
		
		$db = $this->getDbo();
		
		// parse rules
		if (isset($data['rules'])) {
			$rules	= new JAccessRules($data['rules']);
			$asset	= JTable::getInstance('asset');
			
			if (!$asset->loadByName($this->option)) {
				$root	= JTable::getInstance('asset');
				$root->loadByName('root.1');
				$asset->name = $this->option;
				$asset->title = $this->option;
				$asset->setLocation($root->id, 'last-child');
			}
			$asset->rules = (string) $rules;
			
			if (!$asset->check() || !$asset->store()) {
				$this->setError($asset->getError());
				return false;
			}
		}
		
		// get configuration
		$config = $this->getConfig();
		// get configuration keys
		$keys	= $config->getKeys();
		
		// update keys
		foreach ($keys as $key) {
			$value = '';
			if (isset($data[$key])) {
				$value = $data[$key];
			}
			
			// Ignore files and folders
			if ($key == 'ignore_files_folders')
			{
				// cleanup the table
				$query = $db->getQuery(true);
				$query->delete('#__rsfirewall_ignored')
					  ->where($db->qn('type').'='.$db->q('ignore_folder').' OR '.$db->qn('type').'='.$db->q('ignore_file'));
				$db->setQuery($query);
				$db->execute();
				
				// save new values
				$values = $this->explode($value);
				foreach ($values as $value) {
					$config->append($key, $value);
				}
				
				// no need to save this in the config
				continue;
			}
			// Protect users
			elseif ($key == 'monitor_users') {
				if ($config->get('monitor_users') != $value) {
					// cleanup the table
					$query = $db->getQuery(true);
					$query->delete('#__rsfirewall_snapshots')
						  ->where($db->qn('type').'='.$db->q('protect'));
					$db->setQuery($query);
					$db->execute();
					
					require_once JPATH_COMPONENT.'/helpers/snapshot.php';
					
					if (is_array($value)) {
						foreach ($value as $user_id) {
							$user_id = (int) $user_id;
							$user = JFactory::getUser($user_id);
							
							$table = JTable::getInstance('Snapshots', 'RSFirewallTable');
							$table->bind(array(
								'user_id' 	=> $user_id,
								'snapshot' 	=> RSFirewallSnapshot::create($user),
								'type' 		=> 'protect'
							));
							$table->store();
						}
					}
				}
			}
			// Monitor files
			elseif ($key == 'monitor_files') {
				if ($config->get('monitor_files') != $value) {
					// cleanup the table
					$query = $db->getQuery(true);
					$query->delete('#__rsfirewall_hashes')
						  ->where($db->qn('type').'='.$db->q('protect'));
					$db->setQuery($query);
					$db->execute();
					
					// save new values
					$values = $this->explode($value);
					foreach ($values as $value) {
						$value = trim($value);
						if (!file_exists($value) || !is_readable($value)) {
							continue;
						}
						
						$table = JTable::getInstance('Hashes', 'RSFirewallTable');
						$table->bind(array(
							'id'   => null,
							'file' => $value,
							'hash' => md5_file($value),
							'type' => 'protect',
							'flag' => '',
							'date' => JFactory::getDate()->toSql()
						));
						$table->store();
					}
				}
				
				continue;
			}
			// Backend password must be encrypted
			elseif ($key == 'backend_password') {
				// if we have a value...
				if (strlen($value)) {
					$value = md5($value);
				} else {
					// do not save the blank password
					continue;
				}
			}
			// When we disable the creation of new admin users, we need to remember which are the default ones
			elseif ($key == 'disable_new_admin_users') {
				// if the value has changed, store the new admin users
				if ($config->get('disable_new_admin_users') != $value && $value == 1) {
					require_once JPATH_ADMINISTRATOR.'/components/com_rsfirewall/helpers/users.php';
					
					$users = RSFirewallUsersHelper::getAdminUsers();
					$admin_users = array();
					foreach ($users as $user) {
						$admin_users[] = $user->id;
					}
					
					$config->set('admin_users', $admin_users);
				}
			}
			// don't update this...
			elseif ($key == 'admin_users' || $key == 'grade' || $key == 'log_emails_count' || $key == 'log_emails_send_after') {
				continue;
			}
			
			$config->set($key, $value);
		}
		
		return true;
	}
	
	protected function explode($string) {
		$string = str_replace(array("\r\n", "\r"), "\n", $string);
		return explode("\n", $string);
	}
	
	public function getConfig() {
		return RSFirewallConfig::getInstance();
	}
	
	public function getSideBar() {
		require_once JPATH_COMPONENT.'/helpers/toolbar.php';
		
		return RSFirewallToolbarHelper::render();
	}
	
	public function getRSFieldset() {
		require_once JPATH_COMPONENT.'/helpers/adapters/fieldset.php';
		
		$fieldset = new RSFieldset();
		return $fieldset;
	}
	
	public function getRSTabs() {
		require_once JPATH_COMPONENT.'/helpers/adapters/tabs.php';
		
		$tabs = new RSTabs('com-rsfirewall-configuration');
		return $tabs;
	}
	
	public function getIsJ30() {
		$jversion = new JVersion();
		return $jversion->isCompatible('3.0');
	}
}