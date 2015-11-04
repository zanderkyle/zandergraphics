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
class Textarea {
	static $title = 'Textarea Box';
	static $settings = array(
		'tag' => 'input',
		'type' => 'textarea',
		'name' => 'textarea',
		'id' => 'textarea',
		'value' => '',
		'label' => 'Textarea Label',
		'sublabel' => '',
		'rows' => '3',
		'cols' => '40',
		'class' => '',
		'title' => '',
		'style' => ''
	);
	
	static $configs = array(
		'name' => array('value' => 'textarea{N}', 'label' => 'Field Name', 'type' => 'text'),
		'id' => array('value' => 'textarea{N}', 'label' => 'Field ID', 'type' => 'text'),
		'value' => array('value' => '', 'label' => 'Field Value', 'type' => 'textarea', 'class' => 'L'),
		'label' => array('value' => 'Textarea Label', 'label' => 'Label', 'type' => 'text', 'class' => 'L'),
		'sublabel' => array('value' => '', 'label' => 'Sub Label', 'type' => 'text', 'class' => 'L'),
		'rows' => array('value' => '3', 'label' => 'Rows', 'type' => 'text', 'class' => 'SSS'),
		'cols' => array('value' => '40', 'label' => 'Columns', 'type' => 'text', 'class' => 'SSS'),
		'class' => array('value' => '', 'label' => 'Class', 'type' => 'text'),
		'title' => array('value' => '', 'label' => 'Title', 'type' => 'text', 'class' => 'L'),
		'style' => array('value' => '', 'label' => 'Style', 'type' => 'text', 'class' => 'L'),
	);
	
	public static function element($data = array()){
		echo \GCore\Helpers\Html::formSecStart('original_element', 'textarea_origin');
		echo \GCore\Helpers\Html::formLine(self::$settings['name'], array_merge(self::$settings, $data));
		echo \GCore\Helpers\Html::formSecEnd();
	}
	
	public static function config($data = array(), $k = '{N}'){
		echo \GCore\Helpers\Html::formSecStart('original_element_conifg', 'textarea_origin_config');
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