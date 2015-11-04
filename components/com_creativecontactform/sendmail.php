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
define( '_JEXEC', 1 );
defined('_JEXEC') or die('Restircted access');
error_reporting(0);

session_start();
//check captcha  

define( 'DS', DIRECTORY_SEPARATOR );
define( 'CAPTCHA_TESTED', $captcha_tested );
define('JPATH_BASE', dirname(__FILE__).DS.'..'.DS.'..' );

require_once ( JPATH_BASE .DS.'includes'.DS.'defines.php' );
require_once ( JPATH_BASE .DS.'includes'.DS.'framework.php' );

$app = JFactory::getApplication('site');
$app->initialise();

$module_id = JRequest::getInt('creativecontactform_module_id', 0, 'post');
$form_id = JRequest::getInt('creativecontactform_form_id', 0, 'post');
$get_token = JRequest::getInt('get_token', 0, 'get');

$comparams = JComponentHelper::getParams( 'com_creativecontactform' );

$db = JFactory::getDBO();
//get form configuration
$query = "
			SELECT
				sp.`email_to`,
				sp.`email_bcc`,
				sp.`email_subject`,
				sp.`email_from`,
				sp.`email_from_name`,
				sp.`email_replyto`,
				sp.`email_replyto_name`,
				sp.`email_info_show_referrer`,
				sp.`email_info_show_ip`,
				sp.`email_info_show_browser`,
				sp.`email_info_show_os`,
				sp.`email_info_show_sc_res`
			FROM
				`#__creative_forms` sp
			WHERE sp.published = '1'
			AND sp.id = '".$form_id."'";
$db->setQuery($query);
$form_data = $db->loadAssoc();


JResponse::allowCache( false );
JResponse::setHeader( 'Content-Type', 'text/plain' );

if($get_token == 0) {
	if (!JRequest::checkToken()) {
		echo '[{"invalid":"invalid_token"}]';
	}
	else {
		
		$info = Array();
		
		//get from
		$fromname = $app->getCfg('fromname', $app->getCfg('sitename'));
		$mailfrom = $app->getCfg('mailfrom');
		if (!$mailfrom ) {
			$info[] = 'Mail from not set in Joomla Global Configuration';
		}
		
		//get email to
		$email_to = array();
		if ( $form_data['email_to'] != '' ) {
			$email_to = explode(',', $form_data['email_to']);
		}
		if (count($email_to) == 0) {
			$email_to = $mailfrom;
		}
		
		// Email subject
		$creativecontactform_subject = $form_data['email_subject'] == '' ? 'Message sent from '.$app->getCfg('sitename') : $form_data['email_subject'];
		
		$mail = JFactory::getMailer();
		
		//generate the body
		$body = '';
		$sender_email = '';
		$sender_name = '';
		if(isset($_POST['creativecontactform_fields'])) {
			foreach($_POST['creativecontactform_fields'] as $field_data) {

				$field_label = strip_tags(trim($field_data[1]));
				$field_type = strip_tags(trim($field_data[2]));

				if(isset($field_data[0])) {
					if(is_array($field_data[0])) {
						$field_value = implode(', ',$field_data[0]);
						$field_value = strip_tags(trim($field_value));
					}
					else
						$field_value = strip_tags(trim($field_data[0]));
				}
				else {
					$field_value = '';
				}
				$field_value = str_replace('creative_empty', '', $field_value);

				// start separator
				if($field_type == 'text-area')
					$fields_seperator = ":\n";
				else
					$fields_seperator = ": ";

				// ens separator
				if($field_type == 'text-area')
					$fields_end_seperator = "\r\n\n";
				else
					$fields_end_seperator = "\r\n";

				$body .= $field_label.$fields_seperator.$field_value.$fields_end_seperator;
				
				if($field_type == 'email')
					$sender_email = $field_value;

				if($field_type == 'name')
					$sender_name = $field_value;
			}
		}
		
		//Set the body
		$mail->setBody( $body );
		$info[] = 'Body set successfully!';
		
		//Set subject
		$mail->setSubject( $creativecontactform_subject );
		$info[] = 'Subject set successfully!';
		
		//Set Recipient
		$mail->addRecipient( $email_to );
		//$info[] = 'Recipient set: '.$email_to;
		
		//Set Sender
		$sender_email = $form_data['email_from'] == '' ? ($sender_email == '' ?  $mailfrom : $sender_email) : $form_data['email_from'];
		$sender_name = $form_data['email_from_name'] == '' ? ($sender_name == '' ?  $fromname : $sender_name) : $form_data['email_from_name'];
		$mail->setSender( array( $sender_email, $sender_name ) );
		$info[] = 'Sender set successfully!';
		
		// set reply to
		$replyto_email = $form_data['email_replyto'] == '' ? ($sender_email == '' ?  $mailfrom : $sender_email) : $form_data['email_replyto'];
		$mail->ClearReplyTos();
		$email_replyto_name = $form_data['email_replyto_name'] == '' ? ($sender_name == '' ? $fromname : $sender_name) : $form_data['email_replyto_name'];
		$mail->addReplyTo( array( $replyto_email, $email_replyto_name) );
		$info[] = 'Reply to set successfully!';
		
		// add blind carbon recipient
		if ($form_data['email_bcc'] != '') {
			$email_bcc = explode(',', $form_data['email_bcc']);
			$mail->addBCC($email_bcc);
			$info[] = 'BCC recipients added successfully!';
		}
		
		if ( $mail->Send() === true ) {
			JSession::getFormToken(true);
			$info[] = 'Email sent successful';
		}
		else $info[] = 'There are problems sending email';
		
		//generates json output
		echo '[{';
		if(sizeof($info) > 0) {
			echo '"info": ';
			echo '[';
			foreach ($info as $k => $data) {
				echo '"'.$data.'"';
				if ($k != sizeof($info) - 1)
					echo ',';
			}
			echo ']';
		}
			
		echo '}]';
	}
}
else {
	echo JSession::getFormToken();
}
jexit();