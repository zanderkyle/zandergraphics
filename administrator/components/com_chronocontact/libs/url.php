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
class Url {

	public static function current(){
		$pageURL = self::domain();
		if(isset($_SERVER['PHP_SELF']) AND isset($_SERVER['REQUEST_URI'])){
			//APACHE			
			$pageURL .= $_SERVER['REQUEST_URI'];
		}else{
			//IIS
			$pageURL .= $_SERVER['SCRIPT_NAME'];
			if(!empty($_SERVER['QUERY_STRING'])){
				$pageURL .= '?'.$_SERVER['QUERY_STRING'];
			}
		}
		return $pageURL;
	}
	
	public static function domain(){
		$dURL = (isset($_SERVER['HTTPS']) AND ($_SERVER['HTTPS'] == 'on')) ? 'https://' : 'http://';
		$dURL .= $_SERVER['SERVER_NAME'];
		if ($_SERVER['SERVER_PORT'] != '80'){
			$dURL .= ':'.$_SERVER['SERVER_PORT'];
		}
		
		return $dURL;
	}
	
	public static function referer(){
		return !empty($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
	}
	
	public static function root(){
		$file = str_replace(array('/', '\\'), DS, __FILE__);
		$doc_root = str_replace(array('/', '\\'), DS, $_SERVER['DOCUMENT_ROOT']);
		$fs = explode(DS, $file);
		$dirs = explode(DS, $doc_root);
		$chunks = array(self::domain());
		foreach($fs as $f){
			if(in_array($f, $dirs) || in_array($f, array('libs', 'url.php'))){
				continue;
			}
			$chunks[] = $f;
		}
		if(substr($chunks[count($chunks) - 1], 0, -1) != '/'){
			$chunks[count($chunks) - 1] .= '/';
		}
		return implode('/', $chunks);
	}
	
	public static function abs_to_url($path){
		return str_replace(array(GCORE_FRONT_PATH, DS), array(GCORE_FRONT_URL, '/'), $path);
	}
	
	public static function url_to_abs($url){
		return str_replace(array(GCORE_FRONT_URL, '/'), array(GCORE_FRONT_PATH, DS), $url);
	}
	
	public static function buildQuery($path, $params = array()){
		if(empty($params)){
			return $path;
		}
		$url_params = array();
		if(strpos($path, '?') !== false){
			$path_pcs = explode('?', $path);
			$path_comps = parse_url($path);
			$query = $path_comps['query'];
			parse_str($query, $fragments);
			$fragments = array_merge($fragments, $params);
			return $path_pcs[0].'?'.http_build_query($fragments);
		}else{
			return $path.'?'.http_build_query($params);
		}
	}
}