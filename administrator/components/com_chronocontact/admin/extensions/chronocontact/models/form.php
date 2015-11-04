<?php
/**
* ChronoCMS version 1.0
* Copyright (c) 2012 ChronoCMS.com, All rights reserved.
* Author: (ChronoCMS.com Team)
* license: Please read LICENSE.txt
* Visit http://www.ChronoCMS.com for regular updates and information.
**/
namespace GCore\Admin\Extensions\Chronocontact\Models;
defined('_JEXEC') or die('Restricted access');
defined("GCORE_SITE") or die;
class Form extends \GCore\Libs\GModel {
	var $tablename = '#__chronoengine_forms';
	
	function initialize(){
		$this->validate = array(
			'name' => array(
				'required' => true,
				'not_empty' => true,
				'message' => l_('FORMS_FORM_NAME_REQUIRED')
			),
		);
	}
	
	function beforeSave(&$data, &$params, $mode){
		parent::beforeSave($data, $params);
		if(!empty($data['fields_config']) AND $data['form_type'] == '1'){
			if(isset($data['fields_config']['{N}'])){
				unset($data['fields_config']['{N}']);
			}
			$data['wizardcode'] = serialize($data['fields_config']);
			if(!empty($data['fields_config'])){
				ob_start();
				echo \GCore\Helpers\Html::formStart();
				echo \GCore\Helpers\Html::formSecStart();
				foreach($data['fields_config'] as $field){
					if(isset($field['options'])){
						$options = array();
						if(!empty($field['options'])){
							$lines = explode("\n", $field['options']);
							foreach($lines as $line){
								$opts = explode("=", $line);
								$options[$opts[0]] = $opts[1];
							}
						}
						$field['options'] = $options;
					}
					if(isset($field['values'])){
						$values = array();
						if(!empty($field['values'])){
							$values = explode("\n", $field['values']);
						}
						$field['values'] = $values;
					}
					echo \GCore\Helpers\Html::formLine($field['name'], $field);
				}
				echo \GCore\Helpers\Html::formSecEnd();
				echo \GCore\Helpers\Html::formEnd();
				$data['content'] = ob_get_clean();
			}else{
				$data['content'] = '';
			}
		}
		if(isset($data['config'])){
			$data['config'] = base64_encode(serialize($data['config']));
		}
	}
}