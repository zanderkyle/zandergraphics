<?php
/**
* ChronoCMS version 1.0
* Copyright (c) 2012 ChronoCMS.com, All rights reserved.
* Author: (ChronoCMS.com Team)
* license: Please read LICENSE.txt
* Visit http://www.ChronoCMS.com for regular updates and information.
**/
namespace GCore\Extensions\Chronocontact\Libs;
defined('_JEXEC') or die('Restricted access');
defined("GCORE_SITE") or die;
class Form {
	var $params;
	var $data;
	var $events_actions = array();
	var $errors = array();
	var $form_output = '';
	var $stop = false;
	var $goto = null;
	var $_val = false;
	var $files = array();
	var $debug = array();

	function __construct($formname = ''){
		if(!empty($formname)){
			$this->getForm($formname);
		}else{
			echo 'Form name can NOT be empty!';
		}
	}
	
	public static function getInstance($formname = '', $reset = false){
		static $instances;
		if(!isset($instances)){
			$instances = array();
		}
		if(empty($instances[trim($formname)]) OR $reset){
			$instances[trim($formname)] = new self($formname);
			return $instances[trim($formname)];
		}else{
			return $instances[trim($formname)];
		}
	}
	
	function getForm($formname){
		if(!empty($formname)){			
			$dbo = \GCore\Libs\Database::getInstance();
			$sql = 'SELECT * FROM #__chronoengine_forms WHERE name = :name AND published = :published';
			$form_data = $dbo->loadObject($sql, array('name' => $formname, 'published' => 1));
			
			if(!empty($form_data)){
				foreach($form_data as $k => $v){
					$this->$k = $v;
				}
				$this->params = new \GCore\Libs\Parameter($form_data->params);
				$extras = new \GCore\Libs\Base64($form_data->extras);
				$this->extras = $extras->extract();
				$this->config = unserialize(base64_decode($form_data->config));
				$this->data = $this->get_data();
				$this->get_events_actions();
			}else{
				echo 'Form not found or is not published';
			}
		}else{
			echo 'Form name can NOT be empty!';
		}
	}
	
	function get_data(){
		return \GCore\Libs\Request::raw();
	}
	
	function data($var = null, $default = null){
		if(empty($var)){
			return null;
		}
		if(isset($this->data[$var])){
			return $this->data[$var];
		}else{
			return $default;
		}
	}
		
	function process($task = 'load'){
		if(!empty($this->events_actions[$task])){
			ob_start();
			foreach($this->events_actions[$task] as $act_k => $action){
				if($this->stop === true){
					$this->stop = false;
					break;
				}
				if(isset($this->config[$action])){
					foreach($this->config[$action] as $action_id => $config){
						if(!empty($act_k) AND is_numeric($act_k) AND $act_k != $action_id){
							continue;
						}
						if(!isset($config['enabled']) OR (isset($config['enabled']) AND (bool)$config['enabled'] === true)){
							$this->runAction($action, $config, $action_id);
						}
					}
				}else{
					$this->runAction($action);
				}
			}
			$this->form_output = ob_get_clean();
			$this->display_errors();
			$this->display_output();
		}
		//$this->_val();
	}
	
	function run_event($event){
		$this->process($event);
	}
	
	function runAction($action, $config = array(), $action_id = null){
		$classname = '\GCore\Extensions\Chronocontact\Actions\\'.\GCore\Libs\Str::camilize($action)."\\".\GCore\Libs\Str::camilize($action);
		${$classname} = new $classname();
		${$classname}->execute($this, $config, $action_id);
		if(!empty(${$classname}->events)){
			foreach(${$classname}->events as $event => $status){
				if((bool)$status === true){
					if(!empty($action_id) AND !empty($this->events_actions[$action.'_'.$action_id.'_'.$event])){
						$this->process($action.'_'.$action_id.'_'.$event);
					}else{
						//check for a manual event set
						if(!empty(${$classname}->$event)){
							$this->process(${$classname}->$event);
						}
						//check if there is a manual stop
						if(!empty(${$classname}->stop) AND is_array(${$classname}->stop)){
							if(in_array($event, ${$classname}->stop)){
								$this->stop = true;
							}
						}
					}
				}
			}
		}
	}
	
	function render_action($action, $action_id = 0){
		$classname = '\GCore\Extensions\Chronocontact\Actions\\'.\GCore\Libs\Str::camilize($action).'\\'.\GCore\Libs\Str::camilize($action);
		if(method_exists($classname, 'render')){
			${$classname} = new $classname();
			if(isset($this->config[$action])){
				if(isset($this->config[$action][$action_id])){
					${$classname}->render($this, $this->config[$action][$action_id], $action_id);
				}else{
					${$classname}->render($this, array_shift($this->config[$action]));
				}
			}
		}
	}
		
	function get_events_actions(){
		$this->events_actions = array(
			'load' => array(
				'd0' => 'load_captcha',
				'd1' => 'html',
				'd2' => 'credits',
			),
			'submit' => array(
				106 => 'custom_code',
				'd3' => 'check_captcha',
				'd4' => 'validation',
				108 => 'handle_arrays',
				'd5' => 'file_upload',
				'd6' => 'email',
				107 => 'custom_code',
				'd7' => 'thanks_message',
			)
		);
	}
	
	function display_output(){
		echo $this->form_output;
		$this->form_output = '';
	}
	
	function display_errors(){
		if(!empty($this->errors)){
			$session = \GCore\Libs\Base::getSession();
			foreach($this->errors as $error){
				$session->setFlash('validation', $error);
			}
		}
		$this->errors = array();
	}
	
	function _val(){
		if(true AND !$this->_val){
			$this->runAction('credits');
			$this->_val = true;
		}
	}
	
}