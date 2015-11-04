<?php
/**
* @package RSContact!
* @copyright (C) 2015 www.rsjoomla.com
* @license GPL, http://www.gnu.org/licenses/gpl-3.0.html
*/

defined('_JEXEC') or die('Restricted access');

JText::script('MOD_RSCONTACT_SALUTATION_ERROR');
JText::script('MOD_RSCONTACT_FIRST_NAME_ERROR');
JText::script('MOD_RSCONTACT_LAST_NAME_ERROR');
JText::script('MOD_RSCONTACT_FULL_NAME_ERROR');
JText::script('MOD_RSCONTACT_EMAIL_ERROR');
JText::script('MOD_RSCONTACT_ADDRESS_1_ERROR');
JText::script('MOD_RSCONTACT_ADDRESS_2_ERROR');
JText::script('MOD_RSCONTACT_CITY_ERROR');
JText::script('MOD_RSCONTACT_STATE_ERROR');
JText::script('MOD_RSCONTACT_ZIP_ERROR');
JText::script('MOD_RSCONTACT_ZIP_NOT_A_ALPHANUMERIC_ERROR');
JText::script('MOD_RSCONTACT_HOME_PHONE_ERROR');
JText::script('MOD_RSCONTACT_MOBILE_PHONE_ERROR');
JText::script('MOD_RSCONTACT_WORK_PHONE_ERROR');
JText::script('MOD_RSCONTACT_PHONE_NOT_A_NUMBER_ERROR');
JText::script('MOD_RSCONTACT_COMPANY_ERROR');
JText::script('MOD_RSCONTACT_WEBSITE_ERROR');
JText::script('MOD_RSCONTACT_SUBJECT_ERROR');
JText::script('MOD_RSCONTACT_MESSAGE_ERROR');	
JText::script('MOD_RSCONTACT_CHARACTERS_LEFT');
	
class modRSContactHelper{
	static $states = array(
			array('AK', 'Alaska'),
			array('AL', 'Alabama'),
			array('AR', 'Arkansas'),
			array('AZ', 'Arizona'),
			array('CA', 'California'),
			array('CO', 'Colorado'),
			array('CT', 'Connecticut'),
			array('DC', 'District of Columbia'),
			array('DE', 'Delaware'),
			array('FL', 'Florida'),
			array('GA', 'Georgia'),
			array('HI', 'Hawaii'),
			array('IA', 'Iowa'),
			array('ID', 'Idaho'),
			array('IL', 'Illinois'),
			array('IN', 'Indiana'),
			array('KS', 'Kansas'),
			array('KY', 'Kentucky'),
			array('LA', 'Louisiana'),
			array('MA', 'Massachusetts'),
			array('MD', 'Maryland'),
			array('ME', 'Maine'),
			array('MI', 'Michigan'),
			array('MN', 'Minnesota'),
			array('MO', 'Missouri'),
			array('MS', 'Mississippi'),
			array('MT', 'Montana'),
			array('NC', 'North Carolina'),
			array('ND', 'North Dakota'),
			array('NE', 'Nebraska'),
			array('NH', 'New Hampshire'),
			array('NJ', 'New Jersey'),
			array('NM', 'New Mexico'),
			array('NV', 'Nevada'),
			array('NY', 'New York'),
			array('OH', 'Ohio'),
			array('OK', 'Oklahoma'),
			array('OR', 'Oregon'),
			array('PA', 'Pennsylvania'),
			array('PR', 'Puerto Rico'),
			array('RI', 'Rhode Island'),
			array('SC', 'South Carolina'),
			array('SD', 'South Dakota'),
			array('TN', 'Tennessee'),
			array('TX', 'Texas'),
			array('UT', 'Utah'),
			array('VA', 'Virginia'),
			array('VT', 'Vermont'),
			array('WA', 'Washington'),
			array('WI', 'Wisconsin'),
			array('WV', 'West Virginia'),
			array('WY', 'Wyoming'),
			array('OU', 'Outside US')
		);
	
	public static function loadJs($file){
		JHtml::script('mod_rscontact/'.$file.'.js', false, true);
	}	
	
	public static function loadCss($file){
		JHtml::stylesheet('mod_rscontact/'.$file.'.css', array(), true);
	}
	
	public static function cleanInput($input){
		return htmlentities(is_array($input) ? implode(', ', $input) : $input, ENT_QUOTES, "UTF-8");
	}
	
	public static function captchaGenerate($event, $value=''){
		JPluginHelper::importPlugin('captcha', JFactory::getConfig()->get('captcha'));	
		$dispatcher = JEventDispatcher::getInstance();
		if($event == 'onDisplay'){
			$dispatcher->trigger('onInit', 'capt');
			return $dispatcher->trigger('onDisplay', array('capt', 'capt', ''));
		}
		if($event == 'onCheckAnswer'){
			return $dispatcher->trigger('onCheckAnswer', $value);
		}
	} 
	
	public static function split($input){
		$options = trim($input);
		$options = str_replace(array("\r\n", "\r"), "\n", $options);
		$options = preg_split("/[\n,]+/", $options); 
		return $options;
	}
	
	protected static function showResponse( $status, $message, $warnings = array() ) {
		$response = (object) array(
			'status' 	=> $status,
			'message' 	=> $message,
			'warnings' 	=> $warnings
		);
		JFactory::getDocument()->setMimeEncoding('application/json');
		echo json_encode($response);
		JFactory::getApplication()->close();
	}
	
	
	public static function getAjax(){
		JFactory::getLanguage()->load('mod_rscontact');
		$warning 	= array();
		$jInput		= JFactory::getApplication()->input;
		//ajax submit
		$inputs 	= $jInput->get('data', array(), 'ARRAY');
		
		$user 		= JFactory::getUser();
		$config		= JFactory::getConfig();
		
		$user_id 	= $user->get('id');
		$username	= $user->get('username');
		$user_email = $user->get('email');
		
		$timeZone	= $config->get('offset');
		$myDate 	= JDate::getInstance('now', $timeZone);
		$date 		= $myDate->format('d-m-Y', true, true);
		$date_time	= $myDate->format('d-m-Y H:i:s', true, true);
		
		$module 	= JModuleHelper::getModule('rscontact', $inputs['mod-rscontact-module-name']);
		$params 	= new JRegistry();
		$params->loadString($module->params);
		
		$recipient		= $params->get('mail_to');
		$bcc 			= $params->get('mail_bcc');
		$cc 			= $params->get('mail_cc');
		$message_set	= $params->get('mail_msg');
		$fullname_email	= $params->get('name_type') == 1;
		$thank_you		= $params->get('thank_you', JText::_('MOD_RSCONTACT_THANK_YOU_DEFAULT'));
		$send_copy		= $params->get('send_copy') == 1;
		$show_captcha	= $params->get('captcha');
		$subject_predef	= $params->get('email_subj');
		$set_reply		= $params->get('reply_to');
		$reply_email	= $params->get('reply_email');
		$ip_remote		= $jInput->server->get('REMOTE_ADDR');
		
	 	$salut_form 	= !empty($inputs['mod_rscontact_salutation'])	? self::cleanInput($inputs['mod_rscontact_salutation'])		: '';
		$first_name		= !empty($inputs['mod_rscontact_first_name']) 	? self::cleanInput($inputs['mod_rscontact_first_name'])		: '';
		$last_name		= !empty($inputs['mod_rscontact_last_name'])	? self::cleanInput($inputs['mod_rscontact_last_name'])		: '';
		$fullname		= !empty($inputs['mod_rscontact_full_name'])	? self::cleanInput($inputs['mod_rscontact_full_name'])		: ''; 
		$email			= !empty($inputs['mod_rscontact_email'])		? self::cleanInput($inputs['mod_rscontact_email']) 			: '';
		$address_1		= !empty($inputs['mod_rscontact_address_1'])	? self::cleanInput($inputs['mod_rscontact_address_1'])		: '';
		$address_2		= !empty($inputs['mod_rscontact_address_2'])	? self::cleanInput($inputs['mod_rscontact_address_2'])		: '';
		$city			= !empty($inputs['mod_rscontact_city'])			? self::cleanInput($inputs['mod_rscontact_city'])			: '';
		$state			= !empty($inputs['mod_rscontact_states'])		? self::cleanInput($inputs['mod_rscontact_states'])			: '';
		$zip			= !empty($inputs['mod_rscontact_zip'])			? self::cleanInput($inputs['mod_rscontact_zip'])			: '';
		$h_phone 		= !empty($inputs['mod_rscontact_home_phone'])	? self::cleanInput($inputs['mod_rscontact_home_phone'])		: '';
		$m_phone		= !empty($inputs['mod_rscontact_mobile_phone']) ? self::cleanInput($inputs['mod_rscontact_mobile_phone'])	: '';
		$w_phone		= !empty($inputs['mod_rscontact_work_phone'])	? self::cleanInput($inputs['mod_rscontact_work_phone'])		: '';
		$company		= !empty($inputs['mod_rscontact_company'])		? self::cleanInput($inputs['mod_rscontact_company'])		: '';
		$website		= !empty($inputs['mod_rscontact_website'])		? self::cleanInput($inputs['mod_rscontact_website'])		: '';
		$subject		= !empty($inputs['mod_rscontact_subject'])		? self::cleanInput($inputs['mod_rscontact_subject'])		: '';
		$message		= !empty($inputs['mod_rscontact_message'])		? self::cleanInput($inputs['mod_rscontact_message'])		: '';
		$cf1			= !empty($inputs['mod_rscontact_cf1'])			? self::cleanInput($inputs['mod_rscontact_cf1'])			: '';
		$cf2			= !empty($inputs['mod_rscontact_cf2'])			? self::cleanInput($inputs['mod_rscontact_cf2'])			: '';
		$cf3			= !empty($inputs['mod_rscontact_cf3'])			? self::cleanInput($inputs['mod_rscontact_cf3'])			: '';
		$selfcopy		= !empty($inputs['mod_rscontact_selfcopy'])		? self::cleanInput($inputs['mod_rscontact_selfcopy'])		: '';
		
		//captcha fields
		foreach($inputs as $key=>$value){
			if(strpos($key, 'mod_rscontact')===false){
				$jInput->set($key, $value);
			}
		}
		
		try{
		 	if (!JSession::checkToken('request')) {
				throw new Exception(JText::_('MOD_RSCONTACT_INVALID_TOKEN'));
			} 
			
			if($show_captcha){	
				if((!$res = self::captchaGenerate('onCheckAnswer',' ')) || (!$res[0])){ 
					throw new Exception(JText::_('MOD_RSCONTACT_CAPTCHA_ERROR'));
				}
			}
			
			if(!JMailHelper::isEmailAddress($email)){
				throw new Exception(JText::_('MOD_RSCONTACT_EMAIL_ERROR'));
			}
			
			if(!$recipient){
				throw new Exception(JText::_('MOD_RSCONTACT_EMAIL_TO_ERROR'));
			}
			
			if($fullname_email){
				$sender = $fullname;
			}
			else{
				$sender = $first_name.' '.$last_name;
			}
			
			foreach(self::$states as $state_t){
				if(strcmp($state_t[0], $state) == 0){
					$state = $state_t[1];
				}
			}
		
			$placeholders = array(
				'{salut-form}'			=> $salut_form, 
				'{first-name}'			=> $first_name, 
				'{last-name}'			=> $last_name, 
				'{fullname}'			=> $fullname,
				'{subject}'				=> $subject,
				'{email}'				=> $email, 
				'{address-1}'			=> $address_1, 
				'{address-2}'			=> $address_2, 
				'{city}'				=> $city, 
				'{state}'				=> $state,
				'{zip}'					=> $zip, 
				'{home-phone}'			=> $h_phone, 
				'{mobile-phone}'		=> $m_phone,
				'{work-phone}'			=> $w_phone,
				'{company}'				=> $company, 
				'{website}'				=> $website,
				'{message}'				=> $message, 
				'{cf1}'					=> $cf1, 
				'{cf2}'					=> $cf2, 
				'{cf3}'					=> $cf3,
				'{username}'			=> $username,
				'{user-id}'				=> $user_id,
				'{user-email}'			=> $user_email,
				'{date}'				=> $date,
				'{date-time}'			=> $date_time,
				'{ip}'					=> $ip_remote,
				'{your-website}'		=> $config->get('sitename'),
				'{your-website-url}' 	=> JUri::root()
			);
			
			$msg = str_replace(array_keys($placeholders), array_values($placeholders), $message_set);
			$subject_predef	= strip_tags(str_replace(array_keys($placeholders), array_values($placeholders), $subject_predef));
		
			// array email addresses
			$recipient	= preg_split( '/[;,]+/', $recipient );
			$bcc	   	= preg_split( '/[;,]+/', $bcc );
			$cc		   	= preg_split( '/[;,]+/', $cc );
			
			if (($set_reply) && (strlen($reply_email)>0)) {
				$replyTo = $reply_email;
			}
			else {
				$replyTo = $email;
			}
			 
			// send admin email
			$sent_admin = JFactory::getMailer()->sendMail($config->get('mailfrom'), $sender, $recipient, $subject_predef, $msg, $mode = true, $cc, $bcc, $attachment = null, $replyTo, $replyToName = null);
		
			// send selfcopy email
			if ($selfcopy || $send_copy) { 
				$subject = JText::sprintf('MOD_RSCONTACT_SEND_COPY_SUBJECT', $config->get('sitename'));
				
				$sent_user = JFactory::getMailer()->sendMail($config->get('mailfrom'), $config->get('fromname'), $email, $subject, $msg, true);
				if ($sent_user !== true) {
					$errorMessage = JText::_('MOD_RSCONTACT_NO_FURTHER_INFORMATION_AVAILABLE');
					if (is_object($sent_user) && is_callable(array($sent_user, 'getMessage'))) {
						$errorMessage = $sent_user->getMessage();
					}
					$warning[] = JText::sprintf('MOD_RSCONTACT_EMAIL_FAILED_COPY', $errorMessage);
				}
			} 
			
			if ($sent_admin !== true) {
				$db = JFactory::getDbo();
				$jdate = new JDate('now');
				$query = $db->getQuery(true);
				
				// Get all admin users for database
				$query->clear()
				->select($db->qn(array('id', 'name', 'email', 'sendEmail')))
				->from($db->qn('#__users'))
				->where($db->qn('sendEmail') . ' = ' . 1);

				$db->setQuery($query);
				if ($rows = $db->loadObjectList()) {
					foreach ($rows as $row) {
						$user_send_from = $user_id ? $user_id : $row->id;
						$not_sent 		= JText::sprintf('MOD_RSCONTACT_ADMIN_EMAIL_NOT_SENT', '<strong>'.$params->get('mail_to').'</strong><br />');
						$values = array($db->q($user_send_from), $db->q($row->id), $db->q($jdate->toSql()), $db->q($subject_predef), $db->q($not_sent.$msg));
						$query->clear()
							->insert($db->qn('#__messages'))
							->columns($db->qn(array('user_id_from', 'user_id_to', 'date_time', 'subject', 'message')))
							->values(implode(',', $values));
						$db->setQuery($query);
						$db->execute();
					}
				}
				
				$errorMessage = JText::_('MOD_RSCONTACT_NO_FURTHER_INFORMATION_AVAILABLE');
				if (is_object($sent_admin) && is_callable(array($sent_admin, 'getMessage'))) {
					$errorMessage = $sent_admin->getMessage();
				}
				
				$warning[] = JText::sprintf('MOD_RSCONTACT_EMAIL_FAILED', $errorMessage);
			}
			
			self::showResponse(1, $thank_you, $warning);
		} catch (Exception $e) {
			self::showResponse(0, $e->getMessage());
		}
	}
}