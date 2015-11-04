<?php
/**
* ChronoCMS version 1.0
* Copyright (c) 2012 ChronoCMS.com, All rights reserved.
* Author: (ChronoCMS.com Team)
* license: Please read LICENSE.txt
* Visit http://www.ChronoCMS.com for regular updates and information.
**/
namespace GCore\Libs;
defined('_JEXEC') or die('Restricted access');
defined("GCORE_SITE") or die;
class App {
	var $path = '';
	var $url = '';
	var $site = GCORE_SITE;
	var $_vars = array();
	var $buffer = '';
	var $extension = '';
	var $controller = '';
	var $action = '';
	var $template = '';
	var $tvout = 'index';
	var $language = 'en-gb';
	var $direction = 'ltr';
	var $thread = 'gcore';
	var $reset = false;
	
	function __construct($site = GCORE_SITE, $thread = 'gcore'){
		$this->path = constant('GCORE_'.strtoupper($site).'_PATH');
		$this->url = constant('GCORE_'.strtoupper($site).'_URL');
		$this->language = Base::getConfig('site_language', 'en-gb');
		$this->site = $site;
		$this->thread = $thread;
	}
	
	public static function getInstance($site = GCORE_SITE, $thread = 'gcore'){
		static $instances;
		if(!isset($instances)){
			$instances = array();
		}
		if(empty($instances[$site][$thread])){
			$instances[$site][$thread] = new self($site, $thread);
			return $instances[$site][$thread];
		}else{
			return $instances[$site][$thread];
		}
	}
	
	function set($key, $value = null){
		if(is_array($key)){
			$this->_vars = array_merge($this->_vars, $key);
			return;
		}
		$this->_vars[$key] = $value;
	}
	
	function get($key, $default = null){
		if(isset($this->_vars[$key])){
			return $this->_vars[$key];
		}
		return $default;
	}
	
	function redirect($url){
		Env::redirect($url);
	}

	function getBuffer(){
		return $this->buffer;
	}
	
	function initialize(){
		//start the session
		$user = Base::getUser();
		Event::trigger('on_initialize');
	}
	
	function route(){
		Event::trigger('on_before_route');
		if($this->site != 'admin'){
			$params = array();
			Route::translate($params);
		}else{
			$params = array(
				'extension' => Request::data('ext', ''), 
				'controller' => Request::data('cont', ''), 
				'action' => Request::data('act', ''), 
			);
			if(empty($params['extension']) AND empty($params['controller'])){
				$params['controller'] = 'cpanel';
			}
		}
		$this->extension = !empty($params['extension']) ? $params['extension'] : '';
		$this->controller = !empty($params['controller']) ? $params['controller'] : '';
		$this->action = !empty($params['action']) ? $params['action'] : '';
		//set the active route data in the request
		Request::set('_Route.ext', $this->extension);
		Request::set('_Route.cont', $this->controller);
		Request::set('_Route.act', $this->action);
		foreach($params as $k => $var){
			if(strlen(Request::data($k)) == 0 AND !in_array($k, array('extension', 'controller', 'action', 'params'))){
				Request::set($k, $var);
				Request::set('_Route.'.$k, $var);
			}
		}
		if(!empty($params['params'])){
			$this->set($params['params']);
			Request::set('_Route.params', $params['params']);
		}
		Event::trigger('on_after_route', $params);
	}
	
	function dispatch($content_only = false){
		Event::trigger('on_before_dispatch');
		$session = Base::getSession();
		reset:
		//if no action set, set it to index
		if(strlen(trim($this->action)) == 0){
			$this->action = 'index';
		}
		//set admin path
		$site = '';
		if($this->site == 'admin'){
			$site = '\Admin';
		}
		//load the extension class
		$controller = !empty($this->controller) ? '\Controllers\\'.Str::camilize($this->controller) : '\\'.Str::camilize($this->extension);
		$extension = !empty($this->extension) ? '\Extensions\\'.Str::camilize($this->extension) : '';
		$classname = '\GCore'.$site.$extension.$controller;
		//set referer
		if(!$content_only){
			$this->tvout = strlen(Request::data('tvout', null)) > 0 ? Request::data('tvout') : $this->tvout;
			if(!($this->controller == 'users' AND ($this->action == 'login' OR $this->action == 'logout' OR $this->action == 'register')) AND (!empty($this->extension) OR !empty($this->controller)) AND $this->tvout == 'index'){
				$session->set('_referer', Url::current());
			}else{
				//$session->set('_referer', 'index.php');
			}
		}
		//check permissions
		if(!Authorize::authorized($classname, $this->action)){
			if($content_only){
				return;
			}
			$this->redirect(r_('index.php?cont=users&act=login'));
		}
		//if the extension class not found or the action function not found then load an error
		if(!class_exists($classname) OR !in_array($this->action, get_class_methods($classname)) OR substr($this->action, 0, 1) == '_'){
			$this->controller = 'errors';
			$this->action = 'e404';
			//reset the controller
			$classname = '\GCore\Controllers\Errors';
			//we need the rendered content only
			if($content_only){
				return;
			}
		}
		//load language file
		if(!empty($extension)){
			Lang::load($site.$extension);
		}
		//load class and run the action
		${$classname} = new $classname($this->site, $this->thread);
		ob_start();
		$continue = ${$classname}->_initialize();
		if($continue !== false){
			${$classname}->{$this->action}();
			if($this->reset === true){
				$this->reset = false;
				goto reset;
			}
			//initialize and render view
			$view = new View;
			$view->initialize(${$classname});
			$view->renderView($this->action);
		}
		//get the action output buffer
		$this->buffer = ob_get_clean();
		Event::trigger('on_after_dispatch');
	}
	
	function render(){
		Event::trigger('on_before_render');
		$template_model = new \GCore\Admin\Models\Template();
		$params = null;
		if(empty($this->template)){			
			$template_data = $template_model->find('first', array(
				'conditions' => array('Template.site' => $this->site, 'Template.default' => 1),
				'recursive' => -1,
				'cache' => true
			));
		}else{
			$template_data = $template_model->find('first', array(
				'conditions' => array('Template.name' => $this->template),
				'recursive' => -1,
				'cache' => true
			));
		}
		if(!empty($template_data)){
			$this->template = $template_data['Template']['source'];
			$params = new Parameter($template_data['Template']['params']);
		}
		//get template view from the request
		$this->tvout = strlen(Request::data('tvout', null)) > 0 ? Request::data('tvout') : $this->tvout;
		//render the active template
		$doc = Document::getInstance($this->site, $this->thread);
		$template = Template::getInstance($doc, $this->template, $this->tvout, $params);
		$this->buffer = $template->render();
		Event::trigger('on_after_render');
	}
	
	function output(){
		echo $this->buffer;
		if(Base::getConfig('debug', 0)){
			\GCore\Loader::debug();
		}
	}
	
	public static function call($site, $extension, $controller = '', $action = '', $params = array()){
		$x_app = self::getInstance($site, 'x_core_'.rand());
		$x_app->extension = $extension;
		$x_app->controller = $controller;
		$x_app->action = $action;
		$x_app->set($params);
		$x_app->dispatch(true);
		return $x_app->buffer;
	}
}