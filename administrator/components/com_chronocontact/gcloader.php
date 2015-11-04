<?php
/**
* ChronoCMS version 1.0
* Copyright (c) 2012 ChronoCMS.com, All rights reserved.
* Author: (ChronoCMS.com Team)
* license: Please read LICENSE.txt
* Visit http://www.ChronoCMS.com for regular updates and information.
**/

//global namespace for the global helper function pr()
namespace {
	defined('_JEXEC') or die('Restricted access');
	defined("GCORE_SITE") or die;
	//multi purpose function
	function pr($array = array(), $return = false){
		if($return){
			return '<pre>'.print_r($array, $return).'</pre>';
		}else{
			echo '<pre>';
			print_r($array, $return);
			echo '</pre>';
		}
	}
	//multi purpose function
	function prf($array = array(), $file = ''){
		$file = empty($file) ? dirname(__FILE__).DIRECTORY_SEPARATOR.'tmp'.DIRECTORY_SEPARATOR.'debug.html' : $file;
		//add time
		file_put_contents($file, pr(date("d-m-Y H:i:s", time()), true)."\n\n", FILE_APPEND);
		file_put_contents($file, pr($array, true)."\n\n", FILE_APPEND);
	}
	
	function l_($text){
		return \GCore\Libs\Lang::_($text);
	}
	if(!function_exists('r_')){
		function r_($url){
			return \GCore\Libs\Route::_($url);
		}
	}
	
	if(get_magic_quotes_gpc()){
		function stripslashes_gpc(&$value){
			$value = stripslashes($value);
		}
		array_walk_recursive($_GET, 'stripslashes_gpc');
		array_walk_recursive($_POST, 'stripslashes_gpc');
		array_walk_recursive($_COOKIE, 'stripslashes_gpc');
		array_walk_recursive($_REQUEST, 'stripslashes_gpc');
	}
}
//GCore namespace for the loader
namespace GCore{
	if(!defined('DS')){
		define('DS', DIRECTORY_SEPARATOR);
	}

	class Loader {
		static $classname = "";
		static $filepath = "";
		static $memory_usage = 0;
		static $start_time = 0;
		
		static public function register($name){
			if(empty(self::$start_time)){
				self::$start_time = microtime(true);
				self::$memory_usage = memory_get_usage();
			}
			if(strlen(trim($name)) > 0){
				$dirs = explode("\\", $name);
				$dirs = array_values(array_filter($dirs));
				//if the class doesn't belong to the GCore then don't try to auto load it
				if($dirs[0] !== "GCore"){
					return false;
				}
				//build the include file path
				$strings = array();
				foreach($dirs as $k => $dir){
					if($dir === "GCore"){
						//root dir
						$strings[] = dirname(__FILE__);
						continue;
					}
					if($k == (count($dirs) - 1)){
						//last dir (file name)
						$strings[] = strtolower(preg_replace('/([a-z])([A-Z])/', '$1_$2', $dir)).".php";
						continue;
					}
					if(empty($dirs[$k])){
						//empty value
						continue;
					}
					//otherwise, uncamilize the namespace name to get the directory name
					$strings[] = strtolower(preg_replace('/([a-z])([A-Z])/', '$1_$2', $dir));
				}
				//load the file if exists
				$file = implode(DIRECTORY_SEPARATOR, $strings);
				//self::$filepath = $file;
				if(file_exists($file) AND substr($file, -4, 4) == ".php"){
					require_once($file);
					if(class_exists($name)){
						return true;
					}else{
						self::$filepath = $file;
						self::$classname = $name;
					}
				}
				/*if(Libs\Base::getConfig('debug', 0)){
					self::debug();
				}*/
			}
		}
		
		static public function debug(){
			if(!empty(self::$classname))
			echo nl2br("\nClass name: \"".self::$classname."\" could NOT be found, additionally, the file below does NOT exist: \n".self::$filepath);
		}
	}
	spl_autoload_register(__NAMESPACE__ .'\Loader::register');
}