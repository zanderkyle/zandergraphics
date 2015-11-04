<?php
/**
* ChronoCMS version 1.0
* Copyright (c) 2012 ChronoCMS.com, All rights reserved.
* Author: (ChronoCMS.com Team)
* license: Please read LICENSE.txt
* Visit http://www.ChronoCMS.com for regular updates and information.
**/
namespace GCore\Extensions\Chronocontact\Actions\ThanksMessage;
defined('_JEXEC') or die('Restricted access');
defined("GCORE_SITE") or die;
Class ThanksMessage {
	function execute(&$form, $config = array(), $action_id = null){
		$config = new \GCore\Libs\Parameter($config);
		$message = $config->get('message', '');
		echo \GCore\Libs\Str::replacer($message, $form->data);
	}
	
	function config(){
		echo \GCore\Helpers\Html::formStart();
		echo \GCore\Helpers\Html::formSecStart();
		echo \GCore\Helpers\Html::formLine('config[thanks_message][{N}][message]', array('type' => 'textarea', 'label' => 'Message', 'rows' => 20, 'cols' => 100, 'sublabel' => 'Enter your message here. You can use plain text with HTML tags. If you want to display the value of a form input then enter the field name inside curly brackets e.g. {my_input_name}.'));
		echo \GCore\Helpers\Html::formSecEnd();
		echo \GCore\Helpers\Html::formEnd();
	}
}