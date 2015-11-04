<?php
/**
* ChronoCMS version 1.0
* Copyright (c) 2012 ChronoCMS.com, All rights reserved.
* Author: (ChronoCMS.com Team)
* license: Please read LICENSE.txt
* Visit http://www.ChronoCMS.com for regular updates and information.
**/
namespace GCore\Extensions\Chronocontact\Actions\CustomCode;
defined('_JEXEC') or die('Restricted access');
defined("GCORE_SITE") or die;
Class CustomCode {
	function execute(&$form, $config = array(), $action_id = null){
		$config = new \GCore\Libs\Parameter($config);
		$content = $config->get('content', '');
		ob_start();
		eval('?>'.$content);
		$output = ob_get_clean();
		echo \GCore\Libs\Str::replacer($content, $form->data);
	}
	
	function config($configs = array(), $extra_config = array()){
		echo \GCore\Helpers\Html::formStart();
		echo \GCore\Helpers\Html::formSecStart();
		echo \GCore\Helpers\Html::formLine('config[custom_code][{N}][content]', array('type' => 'textarea', 'label' => (!empty($extra_config['label']) ? $extra_config['label'] : 'Content'), 'rows' => 20, 'cols' => 100, 'sublabel' => 'Enter any content here. You can use plain text, HTML and/or PHP code with tags. If you want to display the value of a form input then enter the field name inside curly brackets e.g. {my_input_name}.'));
		echo \GCore\Helpers\Html::formSecEnd();
		echo \GCore\Helpers\Html::formEnd();
	}
}