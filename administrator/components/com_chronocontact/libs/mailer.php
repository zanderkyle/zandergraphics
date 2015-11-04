<?php
/**
* ChronoCMS version 1.0
* Copyright (c) 2012 ChronoCMS.com, All rights reserved.
* Author: (ChronoCMS.com Team)
* license: Please read LICENSE.txt
* Visit http://www.ChronoCMS.com for regular updates and information.
**/
namespace GCore\Libs;
defined('_JEXEC') or die('Restricted access');
defined("GCORE_SITE") or die;
class Mailer {

	public static function send($to = array(), $subject = '', $body = '', $attachments = array(), $other = array()){
		require_once(GCORE_FRONT_PATH.'vendors'.DS.'phpmailer'.DS.'class.phpmailer.php');
		
		$mail = new \PHPMailer();
		//get recipients
		foreach((array)$to as $address){
			$mail->AddAddress(trim($address));
		}
		//subject
		$mail->Subject = $subject;
		//from
		$from_name = !empty($other['from_name']) ? $other['from_name'] : Base::getConfig('mail_from_name');
		$from_email = !empty($other['from_email']) ? $other['from_email'] : Base::getConfig('mail_from_email');
		$mail->SetFrom($from_email, $from_name);
		//reply to
		$reply_name = !empty($other['reply_name']) ? $other['reply_name'] : Base::getConfig('mail_reply_name');
		$reply_email = !empty($other['reply_email']) ? $other['reply_email'] : Base::getConfig('mail_reply_email');
		if(!empty($reply_name) AND !empty($reply_email)){
			$mail->AddReplyTo($reply_email, $reply_name);
		}
		
		if((bool)Base::getConfig('smtp', 0) === true){
			$mail->IsSMTP();
			$mail->SMTPAuth   = true;
			$mail->Host       = Base::getConfig('smtp_host');
			$mail->Port       = Base::getConfig('smtp_port');
			$mail->Username   = Base::getConfig('smtp_username');
			$mail->Password   = Base::getConfig('smtp_password');
		}
		$mail->AltBody = 'To view the message, please use an HTML compatible email viewer!'; // optional - MsgHTML will create an alternate automatically
		$body = nl2br($body);
		$mail->MsgHTML($body);
		
		//attachments
		foreach((array)$attachments as $attachment){
			$mail->AddAttachment($attachment);
		}

		if(!$mail->Send()){
			$session = Base::getSession();
			$session->setFlash('warning', 'Mailer Error: '.$mail->ErrorInfo);
			return false;
		}
		
		return true;
	}
}