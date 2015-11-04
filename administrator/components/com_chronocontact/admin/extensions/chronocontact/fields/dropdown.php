<?php
/**
* ChronoCMS version 1.0
* Copyright (c) 2012 ChronoCMS.com, All rights reserved.
* Author: (ChronoCMS.com Team)
* license: Please read LICENSE.txt
* Visit http://www.ChronoCMS.com for regular updates and information.
**/
namespace GCore\Admin\Extensions\Chronocontact\Fields;
defined('_JEXEC') or die('Restricted access');
defined("GCORE_SITE") or die;
class Dropdown {
	static $title = 'Dropdown';
	static $settings = array(
		'tag' => 'select',
		'type' => 'dropdown',
		'name' => 'dropdown',
		'id' => 'dropdown',
		'values' => '',
		'options' => array(0 => 'No', 1 => 'Yes'),
		'label' => 'Dropdown Label',
		'sublabel' => '',
		//'multiple' => '0',
		'empty' => '',
		'size' => '',
		'class' => '',
		'title' => '',
		'style' => ''
	);
	
	static $configs = array(
		'name' => array('value' => 'dropdown{N}', 'label' => 'Field Name', 'type' => 'text'),
		'id' => array('value' => 'dropdown{N}', 'label' => 'Field ID', 'type' => 'text'),
		'options' => array('value' => "0=No\n1=Yes", 'label' => 'Options', 'type' => 'textarea', 'alt' => 'options', 'class' => 'L', 'sublabel' => 'In Multiline format, value=Title'),
		'empty' => array('value' => '', 'label' => 'Empty Option', 'type' => 'text'),
		'values' => array('value' => '', 'label' => 'Selected Values', 'type' => 'textarea', 'alt' => 'multiline', 'class' => 'L', 'sublabel' => 'In Multiline format'),
		'label' => array('value' => 'Dropdown Label', 'label' => 'Label', 'type' => 'text', 'class' => 'L'),
		'sublabel' => array('value' => '', 'label' => 'Sub Label', 'type' => 'text', 'class' => 'L'),
		'multiple' => array('value' => '', 'label' => 'Multiple', 'type' => 'dropdown', 'options' => array(0 => 'No', 1 => 'Yes')),
		'size' => array('value' => '', 'label' => 'Size', 'type' => 'text'),
		'class' => array('value' => '', 'label' => 'Class', 'type' => 'text'),
		'title' => array('value' => '', 'label' => 'Title', 'type' => 'text', 'class' => 'L'),
		'style' => array('value' => '', 'label' => 'Style', 'type' => 'text', 'class' => 'L'),
	);
	
	public static function element($data = array()){
		echo \GCore\Helpers\Html::formSecStart('original_element', 'dropdown_origin');
		echo \GCore\Helpers\Html::formLine(self::$settings['name'], array_merge(self::$settings, $data));
		echo \GCore\Helpers\Html::formSecEnd();
	}
	
	public static function config($data = array(), $k = '{N}'){
		echo \GCore\Helpers\Html::formSecStart('original_element_conifg', 'dropdown_origin_config');
		foreach(self::$configs as $name => $params){
			$params['value'] = isset($data[$name]) ? $data[$name] : (isset($params['value']) ? $params['value'] : '');
			$params['values'] = isset($data[$name]) ? $data[$name] : (isset($params['values']) ? $params['values'] : '');
			echo \GCore\Helpers\Html::formLine('fields_config['.$k.']['.$name.']', $params);
		}
		echo \GCore\Helpers\Html::input('fields_config['.$k.'][type]', array('type' => 'hidden', 'value' => self::$settings['type']));
		echo \GCore\Helpers\Html::formSecEnd();
	}
}
?>