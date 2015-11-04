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
class Text {
	static $title = 'Text Box';
	static $settings = array(
		'tag' => 'input',
		'type' => 'text',
		'name' => 'text',
		'id' => 'text',
		'value' => '',
		'label' => 'Text Label',
		'sublabel' => '',
		'maxlength' => '',
		'size' => '',
		'class' => '',
		'title' => '',
		'style' => ''
	);
	
	static $configs = array(
		'name' => array('value' => 'text{N}', 'label' => 'Field Name', 'type' => 'text'),
		'id' => array('value' => 'text{N}', 'label' => 'Field ID', 'type' => 'text'),
		'value' => array('value' => '', 'label' => 'Field Value', 'type' => 'text'),
		'label' => array('value' => 'Text Label', 'label' => 'Label', 'type' => 'text', 'class' => 'L'),
		'sublabel' => array('value' => '', 'label' => 'Sub Label', 'type' => 'text', 'class' => 'L'),
		'maxlength' => array('value' => '', 'label' => 'Max Length', 'type' => 'text'),
		'size' => array('value' => '', 'label' => 'Size', 'type' => 'text'),
		'class' => array('value' => '', 'label' => 'Class', 'type' => 'text'),
		'title' => array('value' => '', 'label' => 'Title', 'type' => 'text', 'class' => 'L'),
		'style' => array('value' => '', 'label' => 'Style', 'type' => 'text', 'class' => 'L'),
	);
	
	public static function element($data = array()){
		echo \GCore\Helpers\Html::formSecStart('original_element', 'text_origin');
		echo \GCore\Helpers\Html::formLine(self::$settings['name'], array_merge(self::$settings, $data));
		echo \GCore\Helpers\Html::formSecEnd();
	}
	
	public static function config($data = array(), $k = '{N}'){
		echo \GCore\Helpers\Html::formSecStart('original_element_conifg', 'text_origin_config');
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