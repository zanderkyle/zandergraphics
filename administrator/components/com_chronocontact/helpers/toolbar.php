<?php
/**
* ChronoCMS version 1.0
* Copyright (c) 2012 ChronoCMS.com, All rights reserved.
* Author: (ChronoCMS.com Team)
* license: Please read LICENSE.txt
* Visit http://www.ChronoCMS.com for regular updates and information.
**/
namespace GCore\Helpers;
defined('_JEXEC') or die('Restricted access');
defined("GCORE_SITE") or die;
class Toolbar {
	static $buttons = array();
	static $form_id = 'admin_form';
	static $title = '';
	
	public function addButton($id, $link, $text = '', $image = '', $type = 'submit', $alert = ''){
		self::$buttons[$id] = array(
			'link' => $link,
			'text' => $text,
			'image' => $image,
			'type' => $type,
			'alert' => empty($alert) ? l_('SELECTION_REQUIRED') : $alert,
		);
	}
	
	public function getButtons(){
		return self::$buttons;
	}
	
	public function getFormID(){
		return self::$form_id;
	}
	
	public function getTitle(){
		return self::$title;
	}
	
	public function setTitle($str = ''){
		self::$title = $str;
	}
	
	public function setFormID($form_id = 'admin_form'){
		self::$form_id = $form_id;
	}
	
	public static function selectAll(){
		return '<input type="checkbox" name="select_all" value="" onClick="toggleSelectors(this.checked, \''.self::$form_id.'\');" />';
	}
	
	public static function selector($val = '{id}'){
		return '<input type="checkbox" name="gcb[]" value="'.$val.'" onClick="toggleRowActive(this, false);" id="gcb-'.$val.'" class="gc_selector" />';
	}
}