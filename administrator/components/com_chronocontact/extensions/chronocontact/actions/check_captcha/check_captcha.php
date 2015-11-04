<?php
/**
* ChronoCMS version 1.0
* Copyright (c) 2012 ChronoCMS.com, All rights reserved.
* Author: (ChronoCMS.com Team)
* license: Please read LICENSE.txt
* Visit http://www.ChronoCMS.com for regular updates and information.
**/
namespace GCore\Extensions\Chronocontact\Actions\CheckCaptcha;
defined('_JEXEC') or die('Restricted access');
defined("GCORE_SITE") or die;
Class CheckCaptcha {
	var $events = array('success' => 0, 'fail' => 0);
	var $fail = 'load';
	var $stop = array('fail');
	var $defaults = array(
		'enabled' => 0,
		'error' => 'You have entered a wrong verification code!',
	);
	
	function execute(&$form, $config = array(), $action_id = null){
		$config = new \GCore\Libs\Parameter($config);
		if((bool)$config->get('enabled', 0) === false){
			return;
		}
		$result = \GCore\Helpers\Captcha\Captcha::check();
		if($result){
			$this->events['success'] = 1;
		}else{
			$this->events['fail'] = 1;
			$form->errors[] = $config->get('error', 'You have entered a wrong verification code!');
		}
	}
	
	function config(){
		echo \GCore\Helpers\Html::formStart();
		echo \GCore\Helpers\Html::formSecStart();
		echo \GCore\Helpers\Html::formLine('config[check_captcha][{N}][enabled]', array('type' => 'dropdown', 'label' => 'Enabled', 'options' => array(0 => l_('NO'), 1 => l_('YES')), 'sublabel' => 'Enable the core captcha processing, please use {captcha_img} in your form code to display the image, and add a field with name="captcha" to enter the code.'));
		echo \GCore\Helpers\Html::formLine('config[check_captcha][{N}][error]', array('type' => 'text', 'label' => 'Error', 'style' => 'width:500px;', 'default' => 'You have entered a wrong verification code!', 'sublabel' => 'The error message displayed when a wrong code is entered.'));
		echo \GCore\Helpers\Html::formSecEnd();
		echo \GCore\Helpers\Html::formEnd();
	}
}