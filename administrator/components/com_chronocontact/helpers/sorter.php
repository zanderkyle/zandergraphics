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
class Sorter {
	var $direction = 'asc';
	var $active_field = '';
	var $action = 'index';
	
	function initialize(){
		$this->url = !empty($this->url) ? $this->url : \GCore\Libs\Url::current();
	}
	
	public function link($text, $field, $params = array()){
		$drc = ($field == $this->active_field) ? $this->direction : 'asc';
		$drc_new = (strtolower($this->direction) == 'asc') ? 'desc' : 'asc';
		
		$params['class'] = 'sort-link';
		$href = r_(\GCore\Libs\Url::buildQuery($this->url, array('act' => $this->action, 'orderfld' => $field, 'orderdrc' => $drc)));
		if($field == $this->active_field){
			$href = r_(\GCore\Libs\Url::buildQuery($this->url, array('act' => $this->action, 'orderfld' => $field, 'orderdrc' => $drc_new)));
			$params['class'] = 'sort-link sorted-'.$drc;
			$params['style'] = !empty($params['style']) ? $params['style'] : array();
			$params['style']['padding-right'] = '15px';
			$params['style']['background'] = "right center url('".GCORE_FRONT_URL."assets/images/sort_".$drc.".png') no-repeat transparent";
			$params['style'] = \GCore\Helpers\Html::styles($params['style']);
		}
		
		$full = \GCore\Helpers\Html::url($text, $href, $params);
		return $full;
	}	
}