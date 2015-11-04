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
class Html {
	static $counter = 1;
	static $usedIds = array();
	static $last_field_params = array();
	
	function __construct(){
		
	}
	
	public static function image($src, $params = array()){
		$attributes = array('src', 'height', 'width', 'border', 'alt', 'rel', 'title', 'style', 'class', 'onclick');
		$params['src'] = $src;
		return self::_concat($params, $attributes, '<img ', ' />');
		break;
	}
	
	public static function url($text, $href, $params = array()){
		$attributes = array('href', 'target', 'alt', 'rel', 'title', 'style', 'class', 'onclick');
		$params['href'] = $href;
		return self::_concat($params, $attributes, '<a ', '>'.$text.'</a>');
		break;
	}
	
	public static function styles($styles = array()){
		return implode('; ', array_map(create_function('$k,$v', 'return $k.":".$v;'), array_keys($styles), array_values($styles)));
	}
	
	private static function __fix_params(&$params){
		if(!isset($params['label'])){
			$params['label'] = array();
		}
		if(is_string($params['label']) AND !empty($params['label'])){
			$params['label'] = array('text' => $params['label']);
		}
	}
	
	public static function _concat($atts = array(), $valid = array(), $prefix = '', $postfix = ''){
		$out = array();
		foreach($atts as $k => $v){
			if(in_array($k, $valid)){
				if(is_array($v) AND $k == 'style'){
					$v = self::styles($v);
				}
				$out[] = $k.'="'.$v.'"';
			}
		}
		if(!empty($out)){
			return $prefix.implode(' ', $out).$postfix;
		}
		return $prefix.$postfix;
	}
	
	private static function _uniqueId($id){
		$new_id = str_replace('__#', self::$counter, $id);
		if(!in_array($new_id, self::$usedIds)){
			self::$usedIds[] = $id = $new_id;
			//reset ids counter
			self::$counter = 1;
		}else{
			if($new_id != $id){
				self::$counter++;
				return self::_uniqueId($id);
			}
		}
		return $id;
	}
	
	private static function _autoId($str, $replacer = '_'){
		$str = trim($str);
		$str = str_replace(array('[', ']'), '', $str);
		$str = preg_replace('/[^a-z0-9{}'.$replacer.']/i', $replacer, $str);
		$str = preg_replace('/'.$replacer.'+/', $replacer, $str);
		return $str;
	}
	
	public static function label($params = array(), $f_params = array()){
		$attributes = array('for', 'class', 'id');
		
		if(!isset($params['text'])){
			return '';
		}
		if(!array_key_exists('for', $params) OR (array_key_exists('for', $params) AND strlen($params['for']) == 0 AND $params['for'] !== false)){
			$params['for'] = isset($f_params['id']) ? $f_params['id'] : '';
		}
		if(empty($params['position'])){
			$params['position'] = 'left';
		}
		$default_class = 'gform-label-'.$params['position'];
		$params['class'] = !isset($params['class']) ? $default_class : $params['class'];
		if(!empty($params['sub'])){
			$params['class'] = self::addClass(array('gform-sub-label'), $params['class']);
			$params['class'] = self::removeClass(array('gform-label-left', 'gform-label-right', 'gform-label-top'), $params['class']);
		}
		
		return self::_concat($params, $attributes, '<label ', '>'.$params['text'].'</label>');
	}
	
	public static function container($tag = 'div', $html = '', $params = array()){
		//$params = array_merge(array('class' => 'gform-input', 'id' => 'fin__#'), $params);
		if(isset($params['id'])){
			$params['id'] = self::_uniqueId($params['id']);
		}
		return self::_concat($params, array_keys($params), '<'.$tag.(!empty($params) ? ' ': ''), '>'.$html.'</'.$tag.'>');
	}
	
	public static function formStart($class = 'gform-all', $id = ''){
		return '<div class="'.$class.'"'.($id ? ' id="'.$id.'"' : '').'>';
	}
	
	public static function formSecStart($class = 'gform-section', $id = ''){
		return '<table class="'.$class.'"'.($id ? ' id="'.$id.'"' : '').'>';
	}
	
	public static function formSecEnd(){
		return '</table>';
	}
	
	public static function formEnd(){
		return '</div>';
	}
	
	public static function formLine($name, $params = array()){
		//make sure that we have a field name and type set
		if(empty($name) OR empty($params['type'])){
			return '';
		}
		self::__fix_params($params);
		$tags = array();
		switch($params['type']){
			default:
				$formInput = self::formInput($name, $params);
				$postfix = !empty($params['id']) ? '-'.$params['id'] : '__#';
				$form_cell = self::container('td', $formInput, array('class' => 'gform-line-td', 'id' => 'ftd'.$postfix));
				$tags[] = self::container('tr', $form_cell, array('class' => 'gform-line-tr', 'id' => 'ftr'.$postfix));
				break;
		}
		return implode("\n", $tags);
	}
	
	public static function formInput($name, $params = array(), $formInputParams = array()){
		//make sure that we have a field name and type set
		if(empty($name) OR empty($params['type'])){
			return '';
		}
		$postfix = !empty($params['id']) ? '-'.$params['id'] : '__#';
		self::__fix_params($params);
		$tags = array();
		if(empty($formInputParams['class'])){
			$class = 'gform-input';
			if(!empty($params['label']['position']) AND $params['label']['position'] == 'top'){
				$class = 'gform-input-wide';
			}
		}else{
			$class = $formInputParams['class'];
		}
		$id = 'fin'.$postfix;
		if(!empty($formInputParams['id'])){
			$id = $formInputParams['id'];
		}
		switch($params['type']){
			case 'radio':
			case 'checkbox_group':
				$input = self::input($name, $params);				
				$column = 'single';
				if(!empty($params['horizontal'])){
					$column = 'multiple';
				}
				$input = self::container('div', $input, array('class' => 'gform-'.$column.'-column', 'id' => 'fclmn__#'));
				break;
			default:				
				$input = self::input($name, $params);
				break;
		}
		$params = self::$last_field_params;
		//the main label is NOT a sub label, inject it before the input
		if(isset($params['label']) AND empty($params['label']['sub'])){
			$tags[] = self::label($params['label'], $params);
		}
		
		if(!empty($params['sublabel'])){
			//we have a secondary sublabel description
			$tags[] = self::container('div', $input.self::label(array('text' => $params['sublabel'], 'sub' => true), $params), array('class' => $class, 'id' => $id));
		}else{
			$tags[] = self::container('div', $input, array('class' => $class, 'id' => $id));
		}
		//the main label is a sub label, inject it after the input
		if(isset($params['label']) AND !empty($params['label']['sub'])){
			$tags[] = self::label($params['label'], $params);
		}		
		
		return implode("\n", $tags);
	}
	
	public static function input($name, $params = array()){
		//reset ids counter
		//self::$counter = 1;
		$output = '';
		//make sure that we have a field name and type set
		if(empty($name) OR empty($params['type'])){
			return $output;
		}
		self::__fix_params($params);
		$params['name'] = $name;
		//force field id if it doesn't exist
		if(!isset($params['id'])){
			$params['id'] = self::_uniqueId('fld__#');
		}
		if(!isset($params['class'])){
			$params['class'] = '';
		}
		$params['class'] = self::addClass(array('gform-'.$params['type']), $params['class']);
		
		$tags = array();
		switch($params['type']){
			case 'hidden':
				$attributes = array('type', 'name', 'id', 'value', 'alt');
				$tags[] = self::_concat($params, $attributes, '<input ', ' />');
				break;
			case 'submit':
			case 'button':
				$attributes = array('type', 'name', 'id', 'value', 'class', 'style', 'onclick');
				$tags[] = self::_concat($params, $attributes, '<input ', ' />');
				break;
			case 'textarea':
				$attributes = array('name', 'id', 'class', 'rows', 'cols', 'title', 'style', 'onclick', 'onchange', 'alt');
				$tags[] = self::_concat($params, $attributes, '<textarea ', '>'.(isset($params['value']) ? $params['value'] : '').'</textarea>');
				break;
			case 'dropdown':
				$attributes = array('name', 'id', 'class', 'title', 'multiple', 'size', 'style', 'onclick', 'onchange', 'alt');
				if(array_key_exists('multiple', $params) AND empty($params['multiple'])){
					unset($params['multiple']);
				}
				$set_empty = false;
				if(array_key_exists('empty', $params) AND !empty($params['empty'])){
					$set_empty = true;
				}
				$tags[] = self::_concat($params, $attributes, '<select ', '>');
				if(!empty($params['options']) AND is_array($params['options'])){
					if($set_empty){
						$params['options'] = array('' => $params['empty']) + $params['options'];
					}
					foreach($params['options'] as $value => $title){
						$option_params = array('value' => $value);
						if(isset($params['values']) AND in_array($value, (array)$params['values'])){
							$option_params['selected'] = 'selected';
						}
						if(!empty($params['options_classes'][$value])){
							$option_params['class'] = $params['options_classes'][$value];
						}
						$tags[] = self::_concat($option_params, array('value', 'selected', 'class'), '<option ', '>'.$title.'</option>');
					}
				}
				$tags[] = '</select>';
				break;
			case 'radio':
				$attributes = array('type', 'name', 'id', 'class', 'title', 'value', 'style', 'checked', 'onclick', 'onchange', 'alt');
				if(!empty($params['ghost']) AND (bool)$params['ghost'] === true){
					$tags[] = self::input($params['name'], array('type' => 'hidden', 'value' => isset($params['ghost_value']) ? $params['ghost_value'] : ''));
				}
				if(!empty($params['options']) AND is_array($params['options'])){
					$id = $params['id'].'__#';
					$originals = $params;
					foreach($params['options'] as $value => $label){
						unset($params['checked']);
						if(isset($originals['value']) AND strlen($originals['value']) AND $value == $originals['value']){
							$params['checked'] = 'true';
						}
						$params['value'] = $value;
						$params['id'] = self::_uniqueId($id);
						$item = array();
						$item[] = self::_concat($params, $attributes, '<input ', ' />');
						$item[] = self::label(array('text' => $label, 'class' => ''), $params);
						$tags[] = self::container('div', implode("\n", $item), array('class' => 'gform-radio-item', 'id' => 'fitem__#'));
					}
					$params['id'] = '';
				}
				break;
			case 'checkbox_group':
				$attributes = array('type', 'name', 'id', 'class', 'title', 'value', 'style', /*'_data',*/ 'checked', 'onclick', 'onchange', 'alt');
				if(!empty($params['ghost']) AND (bool)$params['ghost'] === true){
					$tags[] = self::input($params['name'], array('type' => 'hidden', 'value' => isset($params['ghost_value']) ? $params['ghost_value'] : ''));
				}
				$params['type'] = 'checkbox';
				if(!array_key_exists('brackets', $params) OR $params['brackets'] === true){
					$params['name'] = $params['name'].'[]';
				}
				if(!empty($params['options']) AND is_array($params['options'])){
					$id = $params['id'].'__#';
					foreach($params['options'] as $value => $label){
						unset($params['checked']);
						if(isset($params['values']) AND in_array($value, (array)$params['values'])){
							$params['checked'] = 'true';
						}
						$params['value'] = $value;
						$params['id'] = self::_uniqueId($id);
						$item = array();
						$item[] = self::_concat($params, $attributes, '<input ', ' />');
						$item[] = self::label(array('text' => $label, 'class' => ''), $params);
						$tags[] = self::container('div', implode("\n", $item), array('class' => 'gform-checkbox-item', 'id' => 'fitem__#'));
					}
					$params['id'] = '';
				}
				break;
			case 'checkbox':
				$attributes = array('type', 'name', 'id', 'class', 'title', 'value', 'style', 'checked', 'onclick', 'onchange', 'alt');
				if(!empty($params['ghost']) AND (bool)$params['ghost'] === true){
					$tags[] = self::input($params['name'], array('type' => 'hidden', 'value' => isset($params['ghost_value']) ? $params['ghost_value'] : ''));
				}
				if(array_key_exists('checked', $params) AND empty($params['checked'])){
					unset($params['checked']);
				}
				//$tags[] = self::label(array('text' => $label), $params);
				$tags[] = self::_concat($params, $attributes, '<input ', ' />');
				break;
			case 'file':
				$attributes = array('type', 'name', 'id', 'class', 'title', 'style', 'onclick', 'onchange', 'alt');
				if(!empty($params['ghost']) AND (bool)$params['ghost'] === true){
					$tags[] = self::input($params['name'], array('type' => 'hidden', 'value' => isset($params['ghost_value']) ? $params['ghost_value'] : ''));
				}
				$tags[] = self::_concat($params, $attributes, '<input ', ' />');
				break;
			case 'text':
			case 'password':
				$attributes = array('type', 'name', 'id', 'value', 'class', 'size', 'maxlength', 'title', 'style', 'onclick', 'onchange', 'alt');
				$params['id'] = self::_uniqueId($params['id']);				
				$tags[] = self::_concat($params, $attributes, '<input ', ' />');
				break;
			case 'multi':
				$layout = !empty($params['layout']) ? '-'.$params['layout'] : '';
				if(!empty($params['inputs'])){
					foreach($params['inputs'] as $sub_input){
						if(!empty($sub_input['name'])){
							$tags[] = self::formInput($sub_input['name'], $sub_input, array('class' => 'gform-subinput-container'.$layout, 'id' => 'fitem__#'));
						}
					}
				}
				break;
			case 'custom':			
				$tags[] = $params['code'];
				break;
		}
		self::$last_field_params = $params;
		$return = implode("\n", $tags);
		if(!empty($params['beforeInput'])){
			$return = $params['beforeInput'].$return;
		}
		if(!empty($params['afterInput'])){
			$return = $return.$params['afterInput'];
		}
		return $return;
	}
	
	public static function addClass($new, $orig){
		if(is_array($orig)){
			return trim(implode(' ', array_merge($orig, (array)$new)));
		}else{
			$orig = array_filter(explode(' ', $orig));
			return trim(implode(' ', array_merge($orig, (array)$new)));
		}
	}
	
	public static function removeClass($rem, $orig){
		if(is_array($orig)){
			foreach($orig as $k => $class){
				if(in_array($class, $rem)){
					unset($orig[$k]);
				}
			}
			return implode(' ', $orig);
		}else{
			$orig = array_filter(explode(' ', $orig));
			return self::removeClass($rem, $orig);
		}
	}
}