<?php
/**
* ChronoCMS version 1.0
* Copyright (c) 2012 ChronoCMS.com, All rights reserved.
* Author: (ChronoCMS.com Team)
* license: Please read LICENSE.txt
* Visit http://www.ChronoCMS.com for regular updates and information.
**/
namespace GCore\Extensions\Chronocontact\Actions\LoadCaptcha;
defined('_JEXEC') or die('Restricted access');
defined("GCORE_SITE") or die;
Class LoadCaptcha {
	function execute(&$form, $config = array(), $action_id = null){
		$config = new \GCore\Libs\Parameter($config);
		if((bool)$config->get('refresh_button', 0) === true){
			$form->content = str_replace('{captcha_img}', '<img src="'.GCORE_ROOT_URL.'index.php?option=com_chronocontact&act=render&action=load_captcha&ccfname='.$form->name.'&tvout=ajax" alt="" id="gcaptcha_'.$action_id.'" /><img src="'.GCORE_ROOT_URL.'extensions/forms/actions/load_captcha/refresh.png" border="0" style="padding:0px 0px 15px 10px;" alt="refresh" onclick="document.getElementById(\'gcaptcha_'.$action_id.'\').src = \''.GCORE_ROOT_URL.'index.php?option=com_chronocontact&act=render&tvout=ajax&action=load_captcha&ccfname='.$form->name.'\' + \'&\' + Math.random();" />', $form->content);
		}else{
			$form->content = str_replace('{captcha_img}', '<img src="'.GCORE_ROOT_URL.'index.php?option=com_chronocontact&act=render&action=load_captcha&ccfname='.$form->name.'&tvout=ajax" alt="" />', $form->content);
		}
	}
	
	function render(&$form, $config = array(), $action_id = null){
		$config = new \GCore\Libs\Parameter($config);
		\GCore\Helpers\Captcha\Captcha::display($config->get('fonts', 0));
	}
	
	function config(){
		echo \GCore\Helpers\Html::formStart();
		echo \GCore\Helpers\Html::formSecStart();
		echo \GCore\Helpers\Html::formLine('config[load_captcha][{N}][fonts]', array('type' => 'dropdown', 'label' => 'Use GD True Fonts', 'options' => array(0 => l_('NO'), 1 => l_('YES')), 'sublabel' => 'Image will look better but requires GD true type fonts support.'));
		echo \GCore\Helpers\Html::formLine('config[load_captcha][{N}][refresh_button]', array('type' => 'dropdown', 'label' => 'Refresh Button', 'options' => array(0 => l_('NO'), 1 => l_('YES')), 'sublabel' => 'Show a refresh button beside the image to generate a new one.'));
		echo \GCore\Helpers\Html::formSecEnd();
		echo \GCore\Helpers\Html::formEnd();
	}
}