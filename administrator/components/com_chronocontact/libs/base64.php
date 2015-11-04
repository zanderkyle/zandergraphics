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
class Base64{
	var $base = array();
	
	function __construct($string = ''){
		$this->setBase($string);
	}
	
	function get($k, $v = null){
		if(isset($this->base[$k])){
			return base64_decode($this->base[$k]);
		}else{
			return $v;
		}
	}
	
	function extract(){
		foreach($this->base as $k => $v){
			$this->base[$k] = base64_decode($this->base[$k]);
		}
		return $this->base;
	}
	
	function set($k, $v){
		$this->base[$k] = base64_encode($v);
	}
	
	function setBase($string = ''){
		if(strlen(trim(($string))) > 0){
			$data = unserialize($string);
			$this->base = $data;
		}else{
			$this->base = array();
		}
	}
	
	function toString(){
		return serialize($this->base);
	}
}