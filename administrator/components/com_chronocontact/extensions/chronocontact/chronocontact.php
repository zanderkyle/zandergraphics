<?php
/**
* ChronoCMS version 1.0
* Copyright (c) 2012 ChronoCMS.com, All rights reserved.
* Author: (ChronoCMS.com Team)
* license: Please read LICENSE.txt
* Visit http://www.ChronoCMS.com for regular updates and information.
**/
namespace GCore\Extensions\Chronocontact;
defined('_JEXEC') or die('Restricted access');
defined("GCORE_SITE") or die;
class Chronocontact extends \GCore\Libs\Controller {
	function index(){
		//get the form name
		$form_name = \GCore\Libs\Request::data('ccfname');
		if(empty($form_name)){
			$form_name = $this->get('ccfname');
		}
		$event = strlen(\GCore\Libs\Request::data('ccfevent')) ? \GCore\Libs\Request::data('ccfevent') : (strlen($this->get('ccfevent')) ? $this->get('ccfevent') : 'load');
		//load the form class
		$GfForm = Libs\Form::getInstance($form_name);
		$GfForm->process($event);
	}
	
	function render(){
		$form_name = \GCore\Libs\Request::data('ccfname');
		if(!empty($form_name)){
			$GfForm = Libs\Form::getInstance($form_name);
			$GfForm->render_action(\GCore\Libs\Request::data('action'), \GCore\Libs\Request::data('action_id'));
		}else{
			Libs\Form::render_action(\GCore\Libs\Request::data('action'), \GCore\Libs\Request::data('action_id'));
		}
	}
}
?>