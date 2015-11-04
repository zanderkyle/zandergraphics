<?php
/**
 * @package	Antivirus
 * @copyright	Copyright (C) 2014 SiteGuarding.com. All rights reserved.
 * @license	GNU General Public License version 2 or later
 */
defined('_JEXEC') or die('Restricted access');
 
// import Joomla controller library
jimport('joomla.application.component.controller');
 
class jAntiVirusController extends JControllerLegacy
{
	protected $license_info; 
	
	function display($cachable = false) 
	{
		// set default view if not set
		$view = JRequest::getCmd('view', 'scanner');
		
		// Check license info
		$params = JComponentHelper::getParams('com_jantivirus'); 
		if (trim($params->get('access_key')) == '') $view = 'registration';
		else {
			$this->license_info = SGAntiVirus::GetLicenseInfo(JURI::root(), $params->get('access_key'));
			if ($this->license_info === false) $view = 'registration';
			
			
			// QuarantineFiles
			if (JRequest::getVar( 'action', '' ) == 'QuarantineFiles')
			{
				if ($this->license_info['membership'] == 'pro')
				{ 
					$file_type = JRequest::getVar( 'file_type', '' );
					switch ($file_type)
					{
						default:
						case 'main':
							 $a = SGAntiVirus::QuarantineFiles($this->license_info['last_scan_files']['main']);
							 break;
							 
						case 'heuristic':
							 $a = SGAntiVirus::QuarantineFiles($this->license_info['last_scan_files']['heuristic']);
							 break;
							
					}
						
					if ($a === true)
					{
						JFactory::getApplication()->enqueueMessage( JText::_('COM_JANTIVIRUS_MALWARE_MOVED_TO_QUARANTINE') );
					}
					else {
						JError::raiseWarning( 100, JText::_('COM_JANTIVIRUS_OPERATION_FAILED') );
					}
				}
			}
			
			// SendFilesForAnalyze
			if (JRequest::getVar( 'action', '' ) == 'SendFilesForAnalyze')
			{
				if ($this->license_info['membership'] == 'pro')
				{ 
					$files = $this->license_info['last_scan_files'];
									
					$a = SGAntiVirus::SendFilesForAnalyze($files, $this->license_info['email']);
					if ($a === true)
					{
						JFactory::getApplication()->enqueueMessage( JText::_('COM_JANTIVIRUS_FILES_SENT_FOR_ANALYZE').' '.$this->license_info['email'] );
					}
					else {
						JError::raiseWarning( 100, JText::_('COM_JANTIVIRUS_OPERATION_FAILED_NOTHING_SENT_FOR_ANALYZE') );
					}
				}
			}
			
			
			// Prepare data for Last Scan Results
			$avp_alert_main = 0;
			if (count($this->license_info['last_scan_files']['main']))
			{
				foreach ($this->license_info['last_scan_files']['main'] as $k => $tmp_file)
				{
					if (file_exists(JPATH_SITE.'/'.$tmp_file)) $avp_alert_main++;
					else unset($this->license_info['last_scan_files']['main'][$k]); 
				}
			}
			if ($this->license_info['membership'] != 'pro') 
			{
				if ( isset($this->license_info['last_scan_files_counters']['main']) ) $avp_alert_main = $this->license_info['last_scan_files_counters']['main'];
				else $avp_alert_main = 0; 
			}
		
			$avp_alert_heuristic = 0;
			if (count($this->license_info['last_scan_files']['heuristic']))
			{
				foreach ($this->license_info['last_scan_files']['heuristic'] as $k => $tmp_file)
				{
					if (file_exists(JPATH_SITE.'/'.$tmp_file)) $avp_alert_heuristic++;
					else unset($this->license_info['last_scan_files']['heuristic'][$k]);
				}
			}
			if ($this->license_info['membership'] != 'pro') 
			{
				if (isset($this->license_info['last_scan_files_counters']['heuristic'])) $avp_alert_heuristic = $this->license_info['last_scan_files_counters']['heuristic'];
				else $avp_alert_heuristic = 0;
			}
			
			$this->license_info['last_scan_files_counters']['main'] = $avp_alert_main;
			$this->license_info['last_scan_files_counters']['heuristic'] = $avp_alert_heuristic;
		
			if ($avp_alert_main > 0 || $avp_alert_heuristic > 0)
			{
				JError::raiseWarning( 100, JText::_('COM_JANTIVIRUS_ERROR_ACTIONREVIEW_REQUIRED').' ('.$avp_alert_main.'/'.$avp_alert_heuristic.')' );
			} 
			
			$session = JFactory::getSession();
			$session->set('jantivirus_license_info', $this->license_info);
		}
		
		
		JRequest::setVar('view', $view);
 
		// call parent behavior
		parent::display($cachable);
 
		// Set the submenu
		jAntivirusHelper::addSubmenu($view);
	}
	
	function registration_confirm()
	{
		
		$errors = SGAntiVirus::checkServerSettings(true);
		if (count($errors))
		{
			foreach ($errors as $error)
			{
				JError::raiseWarning( 100, $error );
			}
		}
		//print_r($errors);

		$access_key = md5(time().JURI::root());
		$user =& JFactory::getUser();
		$email = JRequest::getVar('email', $user->email);
		$result = SGAntiVirus::sendRegistration(JURI::root(), $email, $access_key, $errors);
		
		if ($result === true)
		{
			$params = JComponentHelper::getParams('com_jantivirus'); 
			$params->set('access_key', $access_key);
			$params->set('notification_email', $email);
			
			$db = JFactory::getDBO();
			$query = $db->getQuery(true);
			
			// Build the query
			$query->update('#__extensions AS a');
			$query->set('a.params = ' . $db->quote((string)$params));
			$query->where('a.element = "com_jantivirus"');
			
			// Execute the query
			$db->setQuery($query);
			$db->query();


			// Send access_key to user
			$message = 'Dear Customer!'."<br><br>";
			$message .= 'Thank you for registration your copy of Antivirus Website Protection. Please keep this email for your records, it contains your registration information and you will need it in the future.'."<br><br>";
			$message .= '<b>Registration information:</b>'."<br><br>";
			$message .= '<b>Domain:</b> '.JURI::root()."<br>";
			$message .= '<b>Email:</b> '.$email."<br>";
			$message .= '<b>Access Key:</b> '.$access_key."<br><br>";
			$subject = 'AntiVirus Registration Information';
			
			SGAntiVirus_module::SendEmail($email, $message, $subject);

			
			$msg = JText::_('COM_JANTIVIRUS_MSG_THANKYOU_FOR_REGISTRATION');
			$this->setRedirect(JRoute::_('index.php?option=com_jantivirus'), $msg); 
		
		}
		else {
			// Show error
			JError::raiseWarning( 100, $result );						
			$this->setRedirect(JRoute::_('index.php?option=com_jantivirus'), false); 
		}
	}
	
}
