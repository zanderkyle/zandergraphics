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
class Document {	
	var $cssfiles = array();
	var $csscodes = array();
	var $jsfiles = array();
	var $jscodes = array();
	var $modules = null;
	var $lang = '';
	var $url = '';
	var $direction = '';
	var $site = '';
	var $thread = 'gcore';
	var $title = array();
	var $meta = array();
	var $base = '';
	
	function __construct($site = GCORE_SITE, $thread = 'gcore'){
		$app = App::getInstance($site, $thread);
		$this->language = $app->language;
		$this->url = $app->url;
		$this->direction = $app->direction;
		$this->site = $site;
		$this->thread = $thread;
		$this->path = $app->path;
		$this->meta[] = array(
			'http-equiv' => 'content-type',
			'content' => 'text/html; charset=utf-8',
		);
		if(strlen(trim(Base::getConfig('meta_robots', 'index,follow')))){
			$this->meta[] = array('name' => 'robots', 'content' => Base::getConfig('meta_robots', 'index,follow'));
		}
		if(strlen(trim(Base::getConfig('meta_keywords', '')))){
			$this->meta[] = array('name' => 'keywords', 'content' => Base::getConfig('meta_keywords'));
		}
		if(strlen(trim(Base::getConfig('meta_description', '')))){
			$this->meta[] = array('name' => 'description', 'content' => Base::getConfig('meta_description'));
		}
		$this->meta[] = array('name' => 'generator', 'content' => 'ChronoCMS 1.0 - Next generation content management system');
	}
	
	public static function getInstance($site = GCORE_SITE, $thread = 'gcore'){
		static $instances;
		if(!isset($instances)){
			$instances = array();
		}
		if(empty($instances[$site][$thread])){
			$instances[$site][$thread] = new Document($site, $thread);
			return $instances[$site][$thread];
		}else{
			return $instances[$site][$thread];
		}
	}
	
	function addCssFile($path, $media = 'screen'){
		if(substr($path, 0, 4) != 'http'){
			if(strpos($path, '/') === false){
				$path = \GCore\Helpers\Assets::css($path);
			}else{
				//relative file path provided
				$path = $this->url.$path;
			}
		}
		if(!in_array($path, (array)Arr::getVal($this->cssfiles, array('[n]', 'href')))){
			$this->cssfiles[] = array('href' => $path, 'media' => $media, 'rel' => 'stylesheet', 'type' => 'text/css');
		}
	}
	
	function addJsFile($path, $type = 'text/javascript'){
		if(substr($path, 0, 4) != 'http'){
			if(strpos($path, '/') === false){
				$path = \GCore\Helpers\Assets::js($path);
			}else{
				//relative file path provided
				$path = $this->url.$path;
			}
		}
		if(!in_array($path, (array)Arr::getVal($this->jsfiles, array('[n]', 'src')))){
			$this->jsfiles[] = array('src' => $path, 'type' => $type);
		}
	}
	
	function _($name){
		switch($name){
			case 'jquery':
				$this->addJsFile(GCORE_FRONT_URL.'assets/js/jquery/jquery.js');
			break;
			case 'jquery-ui':
				$this->addJsFile(GCORE_FRONT_URL.'assets/js/jquery/jquery-ui.js');
				$this->addCssFile(GCORE_FRONT_URL.'assets/css/jquery/'.Base::getConfig('jquery_theme', 'base').'/jquery-ui.min.css');
			break;
			case 'jquery.validate':
				$this->addJsFile(GCORE_FRONT_URL.'assets/js/jquery/jquery.validate.js');
			break;
			case 'forms':
				$this->addCssFile(GCORE_FRONT_URL.'assets/css/forms/'.Base::getConfig('forms_theme', 'default').'.css');
			break;
			case 'datatable':
				$this->addCssFile(GCORE_FRONT_URL.'assets/css/datatable/'.Base::getConfig('forms_theme', 'default').'.css');
			break;
			case 'autocompleter':
				$this->_('autocomplete');
				$this->addJsFile(GCORE_FRONT_URL.'assets/js/autocompleter/autocompleter.js');
			break;
			case 'editor':
				//run editor files load hook
				$hook_results = \GCore\Libs\Event::trigger('on_editor_load');
				if(in_array(true, $hook_results, true)){
					break;
				}
				$this->addJsFile(GCORE_FRONT_URL.'assets/js/gcore_editor/gcore_editor.js');
				$this->addCssFile(GCORE_FRONT_URL.'assets/css/gcore_editor/gcore_editor.css');
			break;
			default:
				$this->addJsFile(GCORE_FRONT_URL.'assets/js/jquery/uitems/jquery.ui.core.min.js');
				$this->addJsFile(GCORE_FRONT_URL.'assets/js/jquery/uitems/jquery.ui.widget.min.js');
				$this->addJsFile(GCORE_FRONT_URL.'assets/js/jquery/uitems/jquery.ui.position.min.js');
				$this->addJsFile(GCORE_FRONT_URL.'assets/js/jquery/uitems/jquery.ui.menu.min.js');
				$this->addJsFile(GCORE_FRONT_URL.'assets/js/jquery/uitems/jquery.ui.'.$name.'.min.js');
				$this->addCssFile(GCORE_FRONT_URL.'assets/css/jquery/'.Base::getConfig('jquery_theme', 'base').'/jquery-ui.min.css');
				break;
		}
	}
	
	function __($type, $id = '', $params = array()){
		switch($type){
			case 'tabs':
				$this->addJsCode('jQuery(document).ready(function($){$("'.$id.'").tabs();});');
			break;
			case 'accordion':
				$this->addJsCode('jQuery(document).ready(function($){$("'.$id.'").accordion();});');
			break;
			case 'validate':
				$this->addJsCode('jQuery(document).ready(function($){$("'.$id.'").validate();});');
			break;
			case 'keepalive':
				$this->addJsCode('setInterval(function(){$.get("index.php?cont=errors&act=e404&tvout=ajax");}, '.(((int)Base::getConfig('session_lifetime') * 60 * 1000) - 30000).');');
			break;
			case 'tooltip':
				$this->addJsCode('jQuery(document).ready(function($){$("'.$id.'").tooltip('.json_encode($params).');});');
			break;
			case 'autocompleter':
				$this->addJsCode('jQuery(document).ready(function($){$("'.$id.'").autoCompleter('.json_encode($params).');});');
			break;
			case 'editor':
				//run editor files load hook
				$hook_results = \GCore\Libs\Event::trigger('on_editor_enable', $id, $params);
				if(in_array(true, $hook_results, true)){
					break;
				}
				$this->addJsCode('jQuery(document).ready(function($){$("'.$id.'").gcoreEditor('.json_encode($params).');});');
			break;
		}
	}
	
	function addCssCode($content, $media = 'screen'){
		if(!isset($this->csscodes[$media])){
			$this->csscodes[$media] = array();
		}
		if(!in_array($content, $this->csscodes[$media])){
			$this->csscodes[$media][] = $content;
		}
	}
	
	function addJsCode($content, $type = 'text/javascript'){
		if(!isset($this->jscodes[$type])){
			$this->jscodes[$type] = array();
		}
		if(!in_array($content, $this->jscodes[$type])){
			$this->jscodes[$type][] = $content;
		}
	}
	
	function addMeta($params = array()){
		if(!empty($params)){
			$this->meta[] = $params;
		}
	}
	
	function getMeta(){
		$meta_tags = array();
		if(!empty($this->meta)){
			foreach($this->meta as $meta){
				$meta_tags[] = \GCore\Helpers\Html::_concat($meta, array_keys($meta), '<meta ', ' />');
			}
			return implode("\n", $meta_tags);
		}
		return '';
	}
	
	function getFavicon(){
		$data = array('rel' => 'shortcut icon', 'href' => \GCore\Helpers\Assets::image('favicon.ico'));
		return \GCore\Helpers\Html::_concat($data, array_keys($data), '<link ', ' />');
	}
	
	function setTitle($title = ''){
		$this->title[] = $title;
	}
	
	function getTitle(){
		if(Base::getConfig('prepend_site_title', 1) == 1){
			array_unshift($this->title, Base::getConfig('site_title'));
		}
		return '<title>'.implode(' - ', array_filter($this->title)).'</title>';
	}
	
	function getBase(){
		if(!empty($this->base)){
			return '<base href="'.$this->base.'" />';
		}
		if($this->site != 'admin'){
			return '<base href="'.Url::root().'" />';
		}
		return '';
	}
	
	function getHeader(){
		return '__GCORE_HEADER__';
	}
	
	function getBody(){
		$app = App::getInstance($this->site, $this->thread);
		return $app->getBuffer();
	}
	
	function fetchModules($site = ''){
		if(!is_null($this->modules)){
			return $this->modules;
		}
		
		if(empty($site)){
			$site = $this->site;
		}
		$module_model = new \GCore\Admin\Models\Module();
		$modules = $module_model->find('all', array(
			'conditions' => array('Module.site' => $site, 'Module.published' => 1),
			'order' => array('Module.level'),
			'recursive' => -1,
			'cache' => true,
		));
		return $this->modules = $modules;
	}
	
	function countModules($position){
		$modules = $this->fetchModules();
		$position = (array)$position;
		$count = 0;
		if(!empty($modules)){
			foreach($modules as $module){
				if(!empty($module['Module']['position']) AND in_array($module['Module']['position'], $position)){
					$count++;
				}
			}
		}
		return $count;
	}
	
	function getModules($position, $container = false){
		$modules = $this->fetchModules();
		$output = '';
		//ob_start();
		if(!empty($modules)){
			foreach($modules as $module){
				if(!empty($module['Module']['position']) AND $module['Module']['position'] == $position){
					//check if the module is enabled in the current page
					if($this->site != 'admin' AND !in_array('0', $module['Module']['pages'], true) AND !in_array(Request::data('_Route.index'), $module['Module']['pages'], true)){
						continue;
					}
					//check permissions
					if(!empty($module['Module']['rules']['display']) AND !Authorize::check_rules($module['Module']['rules']['display'])){
						continue;
					}
					$output .= \GCore\Helpers\Module::render($module['Module'], $container);
				}
			}
		}
		//$output = ob_get_clean();
		return $output;
	}
	
	function getSystemMessages(){
		return '__GCORE_SYSTEM_MESSAGES__';
	}
}