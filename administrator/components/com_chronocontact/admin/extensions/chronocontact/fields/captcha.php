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
class Captcha {
	static $title = 'Captcha';
	static $settings = array(
		'tag' => 'input',
		'type' => 'multi',
		'name' => 'captcha',
		'id' => 'captcha',
		'label' => 'Captcha',
		'sublabel' => '',
		'class' => '',
		'title' => '',
		'layout' => 'wide',
		'inputs' => array(
			'field' => array(
				'type' => 'text',
				'name' => 'captcha',
				'id' => 'captcha',
				'sublabel' => '',
			),
			'image' => array(
				'type' => 'custom',
				'name' => 'captcha',
				'code' => '{captcha_img}'
			),
		)
	);
	
	static $configs = array(
		'label' => array('value' => 'Captcha', 'label' => 'Label', 'type' => 'text', 'class' => 'L', 'alt' => 'ghost'),
	);
	
	public static function element($data = array()){
		echo \GCore\Helpers\Html::formSecStart('original_element', 'captcha_origin');
		echo \GCore\Helpers\Html::formLine(self::$settings['name'], array_merge(self::$settings, $data));
		echo \GCore\Helpers\Html::formSecEnd();
	}
	
	public static function config($data = array(), $k = '{N}'){
		echo \GCore\Helpers\Html::formSecStart('original_element_conifg', 'captcha_origin_config');
		foreach(self::$configs as $name => $params){
			$params['value'] = isset($data[$name]) ? $data[$name] : (isset($params['value']) ? $params['value'] : '');
			$params['values'] = isset($data[$name]) ? $data[$name] : (isset($params['values']) ? $params['values'] : '');
			echo \GCore\Helpers\Html::formLine('fields_config['.$k.']['.$name.']', $params);
		}
		echo \GCore\Helpers\Html::input('fields_config['.$k.'][inputs][field][type]', array('type' => 'hidden', 'alt' => 'ghost', 'value' => 'text'));
		echo \GCore\Helpers\Html::input('fields_config['.$k.'][inputs][field][name]', array('type' => 'hidden', 'alt' => 'ghost', 'value' => 'captcha'));
		echo \GCore\Helpers\Html::input('fields_config['.$k.'][inputs][image][type]', array('type' => 'hidden', 'alt' => 'ghost', 'value' => 'custom'));
		echo \GCore\Helpers\Html::input('fields_config['.$k.'][inputs][image][name]', array('type' => 'hidden', 'alt' => 'ghost', 'value' => 'captcha'));
		echo \GCore\Helpers\Html::input('fields_config['.$k.'][inputs][image][code]', array('type' => 'hidden', 'alt' => 'ghost', 'value' => '{captcha_img}'));
		echo \GCore\Helpers\Html::input('fields_config['.$k.'][layout]', array('type' => 'hidden', 'alt' => 'ghost', 'value' => self::$settings['layout']));
		echo \GCore\Helpers\Html::input('fields_config['.$k.'][name]', array('type' => 'hidden', 'alt' => 'ghost', 'value' => 'captcha'));
		echo \GCore\Helpers\Html::input('fields_config['.$k.'][render_type]', array('type' => 'hidden', 'alt' => 'ghost', 'value' => 'captcha'));
		echo \GCore\Helpers\Html::input('fields_config['.$k.'][type]', array('type' => 'hidden', 'alt' => 'ghost', 'value' => self::$settings['type']));
		echo \GCore\Helpers\Html::formSecEnd();
	}
}
?>