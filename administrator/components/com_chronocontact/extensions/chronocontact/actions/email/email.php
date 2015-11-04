<?php
/**
* ChronoCMS version 1.0
* Copyright (c) 2012 ChronoCMS.com, All rights reserved.
* Author: (ChronoCMS.com Team)
* license: Please read LICENSE.txt
* Visit http://www.ChronoCMS.com for regular updates and information.
**/
namespace GCore\Extensions\Chronocontact\Actions\Email;
defined('_JEXEC') or die('Restricted access');
defined("GCORE_SITE") or die;
Class Email {
	
	function config(){
		echo \GCore\Helpers\Html::formStart();
		echo \GCore\Helpers\Html::formSecStart();
		echo \GCore\Helpers\Html::formLine('config[email][{N}][action_label]', array('type' => 'text', 'label' => 'Email Label', 'class' => 'XL', 'sublabel' => 'A label used to identify your email easily.'));
		echo \GCore\Helpers\Html::formLine('config[email][{N}][enabled]', array('type' => 'dropdown', 'label' => 'Enabled', 'options' => array(0 => l_('NO'), 1 => l_('YES')), 'sublabel' => 'Enable or disable the email.'));
		echo \GCore\Helpers\Html::formLine('config[email][{N}][to]', array('type' => 'text', 'label' => 'To*', 'class' => 'XL', 'sublabel' => 'The email address to receive the email, can hold a comma separated list of addresses too.'));
		echo \GCore\Helpers\Html::formLine('config[email][{N}][subject]', array('type' => 'text', 'label' => 'Subject*', 'class' => 'XL', 'sublabel' => 'The subject of the email.'));
		echo \GCore\Helpers\Html::formLine('config[email][{N}][template]', array('type' => 'textarea', 'label' => 'Email Template', 'rows' => 20, 'cols' => 100, 'sublabel' => 'Enter your email template here. You can use plain text with HTML tags. If you want to display the value of a form input then enter the field name inside curly brackets e.g. {my_input_name}. You can also use PHP inside <?php . . . ?> tags.'));
		echo \GCore\Helpers\Html::formLine('config[email][{N}][attach]', array('type' => 'text', 'label' => 'Attachment files', 'class' => 'XL', 'sublabel' => 'Comma separated list of files fields names to be attached to this email.'));
		echo \GCore\Helpers\Html::formLine('config[email][{N}][dto]', array('type' => 'text', 'label' => 'Dynamic To', 'class' => 'XL', 'sublabel' => 'A form field name holding an email address to be used as one of the recipient(s) address(es).'));
		echo \GCore\Helpers\Html::formLine('config[email][{N}][dsubject]', array('type' => 'text', 'label' => 'Dynamic Subject', 'class' => 'XL', 'sublabel' => 'A form field name holding the email subject.'));
		echo \GCore\Helpers\Html::formLine('config[email][{N}][reply_name]', array('type' => 'text', 'label' => 'Reply To Name', 'class' => 'XL', 'sublabel' => 'The name which is going to appear when the recipient hits reply.'));
		echo \GCore\Helpers\Html::formLine('config[email][{N}][reply_email]', array('type' => 'text', 'label' => 'Reply to Email', 'class' => 'XL', 'sublabel' => 'The email address which is going to appear when the recipient hits reply, must be valid and preferably something@yourdomain.com'));
		echo \GCore\Helpers\Html::formLine('config[email][{N}][dreply_name]', array('type' => 'text', 'label' => 'Dynamic Reply To Name', 'class' => 'XL', 'sublabel' => 'The field name holding the name which is going to appear when the recipient hits reply.'));
		echo \GCore\Helpers\Html::formLine('config[email][{N}][dreply_email]', array('type' => 'text', 'label' => 'Dynamic Reply to Email', 'class' => 'XL', 'sublabel' => 'The field name holding the email address which is going to appear when the recipient hits reply, must be valid and preferably something@yourdomain.com'));
		echo \GCore\Helpers\Html::formLine('config[email][{N}][from_name]', array('type' => 'text', 'label' => 'From Name', 'class' => 'XL', 'sublabel' => 'The name from which the email will be sent.'));
		echo \GCore\Helpers\Html::formLine('config[email][{N}][from_email]', array('type' => 'text', 'label' => 'From Email', 'class' => 'XL', 'sublabel' => 'The email address from which the email will be sent, must be valid and preferably something@yourdomain.com'));
		echo \GCore\Helpers\Html::formLine('config[email][{N}][dfrom_name]', array('type' => 'text', 'label' => 'Dynamic From Name', 'class' => 'XL', 'sublabel' => 'The field name holding the name from which the email will be sent.'));
		echo \GCore\Helpers\Html::formLine('config[email][{N}][dfrom_email]', array('type' => 'text', 'label' => 'Dynamic From Email', 'class' => 'XL', 'sublabel' => 'The field name holding the email address from which the email will be sent, must be valid and preferably something@yourdomain.com'));
		echo \GCore\Helpers\Html::formSecEnd();
		echo \GCore\Helpers\Html::formEnd();
	}
	
	function execute(&$form, $config = array(), $action_id = null){
		$config = new \GCore\Libs\Parameter($config);
		ob_start();
		eval('?>'.$config->get('template', ''));
		$body = ob_get_clean();
		$body = \GCore\Libs\Str::replacer($body, $form->data, '.', true);
		//get recipient
		$tos = array();
		if(strlen(trim($config->get('to', '')))){
			$tos = explode(',', trim($config->get('to', '')));
		}
		if(strlen(trim($config->get('dto', '')))){
			$dtos = explode(',', trim($config->get('dto', '')));
			foreach($dtos as $dto){
				$tos[] = $form->data($dto);
			}
		}
		//subject
		$subject = trim($config->get('subject', '')) ? $config->get('subject', '') : $form->data($config->get('dsubject', ''));
		//from
		$others = array();
		$others['from_name'] = trim($config->get('from_name', '')) ? $config->get('from_name', '') : $form->data($config->get('dfrom_name'), null);
		$others['from_email'] = trim($config->get('from_email', '')) ? $config->get('from_email', '') : $form->data($config->get('dfrom_email'), null);
		//reply to
		$others['reply_name'] = trim($config->get('reply_name', '')) ? $config->get('reply_name', '') : $form->data($config->get('dreply_name'), null);
		$others['reply_email'] = trim($config->get('reply_email', '')) ? $config->get('reply_email', '') : $form->data($config->get('dreply_email'), null);
		
		//attach
		$attachments = array();
		if(strlen(trim($config->get('attach', '')))){
			$attachs = explode(',', trim($config->get('attach', '')));
			foreach($form->files as $name => $file){
				if(in_array($name, $attachs)){
					$attachments[] = $file['path'];
				}
			}
		}
		$sent = \GCore\Libs\Mailer::send($tos, $subject, $body, $attachments, $others);
	}
}