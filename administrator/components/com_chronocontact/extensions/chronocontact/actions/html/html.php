<?php
/**
* ChronoCMS version 1.0
* Copyright (c) 2012 ChronoCMS.com, All rights reserved.
* Author: (ChronoCMS.com Team)
* license: Please read LICENSE.txt
* Visit http://www.ChronoCMS.com for regular updates and information.
**/
namespace GCore\Extensions\Chronocontact\Actions\Html;
defined('_JEXEC') or die('Restricted access');
defined("GCORE_SITE") or die;
Class Html {
	function execute(&$form, $config = array(), $action_id = null){
		$doc = \GCore\Libs\Document::getInstance();
		$doc->_('forms');
		ob_start();
		eval('?>'.$form->content);
		$output = ob_get_clean();
		//get current url
		$current_url = \GCore\Libs\Url::current();
		//add any CSS or JS to the head
		self::add_css($form);
		self::add_js($form);
		//generate <form tag
		$form_tag = '<form';
		$form_action = (strlen($form->params->get('action_url', '')) > 0) ? $form->params->get('action_url', '') : \GCore\Libs\Url::buildQuery($current_url, array('ccfevent' => 'submit'));
		$form_tag .= ' action="'.$form_action.'"';
		//get method
		$form_method = $form->params->get('form_method', 'post');
		if($form->params->get('form_method', 'post') == 'file'){
			$form_tag .= ' enctype="multipart/form-data"';
			$form_method = 'post';
		}
		$form_tag .= ' method="'.$form_method.'"';
		$form_tag .= ' name="'.$form->name.'"';
		$form_tag .= ' id="'.$form->name.'"';
		$form_tag .= ' class="'.$form->params->get('form_class', 'gform').'"';
		if($form->params->get('form_tag_attach', '')){
			$form_tag .= $form->params->get('form_tag_attach', '');
		}

		$form_tag .= '>';

		echo $form_tag;
		//add fields values
		$output = \GCore\Helpers\DataLoader::load($output, $form->data);
		//show output
		echo $output;
		echo '</form>';
	}
	
	function add_css($form){
		if(!empty($form->extras['css'])){
			$doc = \GCore\Libs\Document::getInstance();
			$doc->addCssCode($form->extras['css']);
		}
	}
	
	function add_js($form){
		if(!empty($form->extras['js'])){
			$doc = \GCore\Libs\Document::getInstance();
			$doc->addJsCode($form->extras['js']);
		}
	}
}