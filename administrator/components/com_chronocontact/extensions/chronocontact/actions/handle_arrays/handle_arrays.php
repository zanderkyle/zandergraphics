<?php
/**
* ChronoCMS version 1.0
* Copyright (c) 2012 ChronoCMS.com, All rights reserved.
* Author: (ChronoCMS.com Team)
* license: Please read LICENSE.txt
* Visit http://www.ChronoCMS.com for regular updates and information.
**/
namespace GCore\Extensions\Chronocontact\Actions\HandleArrays;
defined('_JEXEC') or die('Restricted access');
defined("GCORE_SITE") or die;
Class HandleArrays {
	
	function config(){
		echo \GCore\Helpers\Html::formStart();
		echo \GCore\Helpers\Html::formSecStart();
		echo \GCore\Helpers\Html::formLine('config[handle_arrays][{N}][enabled]', array('type' => 'dropdown', 'label' => 'Enabled', 'options' => array(0 => l_('NO'), 1 => l_('YES')), 'sublabel' => 'Enable or disable the arrays handler.'));
		echo \GCore\Helpers\Html::formLine('config[handle_arrays][{N}][fields_list]', array('type' => 'text', 'label' => 'Fields list', 'class' => 'XL', 'sublabel' => 'Comma separated list of fields to be processed, leave empty and all fields with array values will be processed.'));
		echo \GCore\Helpers\Html::formLine('config[handle_arrays][{N}][delimiter]', array('type' => 'text', 'label' => 'Delimiter', 'value' => ',', 'class' => 'SSS', 'sublabel' => 'The string used to delimit the arrays values'));
		echo \GCore\Helpers\Html::formLine('config[handle_arrays][{N}][skipped]', array('type' => 'text', 'label' => 'Skipped fields', 'class' => 'XL', 'sublabel' => 'Comma separated list of fields to be skipped from processing in case the fields list is left empty.'));
		echo \GCore\Helpers\Html::formSecEnd();
		echo \GCore\Helpers\Html::formEnd();
	}
	
	function execute(&$form, $config = array(), $action_id = null){
		$config = new \GCore\Libs\Parameter($config);
		if((bool)$config->get('enabled', 0) === false){
			return;
		}
		$skipped = $config->get('skipped', '');
		if(!empty($skipped)){
			$skipped = explode(',', $skipped);
			array_walk($skipped, create_function('&$val', '$val = trim($val);'));
		}else{
			$skipped = array();
		}
		$del = $config->get('delimiter', ',');
		
		//handle specific fields only ?
		if(strlen($config->get('fields_list', ''))){
			$fields_list = explode(',', $config->get('fields_list', ''));
			foreach($fields_list as $field){
				$field = trim($field);
				//get field value
				$field_value = \GCore\Libs\Arr::getVal($form->data, explode('.', $field));
				if(is_array($field_value)){
					$form->data = \GCore\Libs\Arr::setVal($form->data, explode('.', $field), implode($del, $field_value));
				}
			}
		}else{
			$form->data = $this->array_handler($form->data, $skipped, $del);
		}
	}
	
	function array_handler($data = array(), $skipped = array(), $del = ','){
		foreach($data as $name => $value){
			if(is_array($value) AND !in_array($name, $skipped)){
				$value = $this->array_handler($value, $skipped, $del);
				$data[$name] = implode($del, $value);
			}
		}
		return $data;
	}
}