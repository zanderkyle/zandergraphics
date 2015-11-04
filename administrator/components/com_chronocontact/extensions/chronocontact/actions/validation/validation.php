<?php
/**
* ChronoCMS version 1.0
* Copyright (c) 2012 ChronoCMS.com, All rights reserved.
* Author: (ChronoCMS.com Team)
* license: Please read LICENSE.txt
* Visit http://www.ChronoCMS.com for regular updates and information.
**/
namespace GCore\Extensions\Chronocontact\Actions\Validation;
defined('_JEXEC') or die('Restricted access');
defined("GCORE_SITE") or die;
Class Validation {
	var $events = array('success' => 0, 'fail' => 0);
	var $fail = 'load';
	var $stop = array('fail');
	
	function execute(&$form, $config = array(), $action_id = null){
		//$config = new \GCore\Libs\Parameter($config);
		$failed = false;
		foreach($config['rules'] as $rule => $data){
			if(!empty($data)){
				$fields = explode("\n", $data);
				foreach($fields as $field){
					$fch = explode(':', $field);
					if(count($fch) > 1){
						$valid = \GCore\Libs\Validate::$rule($form->data($fch[0]));
						if(!$valid){
							$failed = true;
							$form->errors[] = $fch[1];
						}
					}
				}
			}
		}
		if($failed){
			$this->events['fail'] = 1;
		}
	}
	
	function config(){
		echo \GCore\Helpers\Html::formStart();
		echo \GCore\Helpers\Html::formSecStart();
		echo \GCore\Helpers\Html::formLine('config[validation][{N}][rules][not_empty]', array('type' => 'textarea', 'label' => 'Not Empty', 'rows' => 5, 'cols' => 100, 'sublabel' => 'Multi line list of fields names to be validated as not empty, e.g: address:Address can NOT be empty'));
		echo \GCore\Helpers\Html::formLine('config[validation][{N}][rules][is_empty]', array('type' => 'textarea', 'label' => 'Empty', 'rows' => 5, 'cols' => 100, 'sublabel' => ''));
		echo \GCore\Helpers\Html::formLine('config[validation][{N}][rules][no_spaces]', array('type' => 'textarea', 'label' => 'No Spaces', 'rows' => 5, 'cols' => 100, 'sublabel' => ''));
		echo \GCore\Helpers\Html::formLine('config[validation][{N}][rules][alpha]', array('type' => 'textarea', 'label' => 'Alpha', 'rows' => 5, 'cols' => 100, 'sublabel' => ''));
		echo \GCore\Helpers\Html::formLine('config[validation][{N}][rules][alphanumeric]', array('type' => 'textarea', 'label' => 'Alpha Numeric', 'rows' => 5, 'cols' => 100, 'sublabel' => ''));
		echo \GCore\Helpers\Html::formLine('config[validation][{N}][rules][digit]', array('type' => 'textarea', 'label' => 'Digit', 'rows' => 5, 'cols' => 100, 'sublabel' => ''));
		echo \GCore\Helpers\Html::formLine('config[validation][{N}][rules][nodigit]', array('type' => 'textarea', 'label' => 'No Digit', 'rows' => 5, 'cols' => 100, 'sublabel' => ''));
		echo \GCore\Helpers\Html::formLine('config[validation][{N}][rules][number]', array('type' => 'textarea', 'label' => 'Number', 'rows' => 5, 'cols' => 100, 'sublabel' => ''));
		echo \GCore\Helpers\Html::formLine('config[validation][{N}][rules][email]', array('type' => 'textarea', 'label' => 'Email', 'rows' => 5, 'cols' => 100, 'sublabel' => ''));
		echo \GCore\Helpers\Html::formLine('config[validation][{N}][rules][phone]', array('type' => 'textarea', 'label' => 'Phone', 'rows' => 5, 'cols' => 100, 'sublabel' => ''));
		echo \GCore\Helpers\Html::formLine('config[validation][{N}][rules][phone_inter]', array('type' => 'textarea', 'label' => 'International Phone', 'rows' => 5, 'cols' => 100, 'sublabel' => ''));
		echo \GCore\Helpers\Html::formLine('config[validation][{N}][rules][url]', array('type' => 'textarea', 'label' => 'URL', 'rows' => 5, 'cols' => 100, 'sublabel' => ''));
		echo \GCore\Helpers\Html::formSecEnd();
		echo \GCore\Helpers\Html::formEnd();
	}
}