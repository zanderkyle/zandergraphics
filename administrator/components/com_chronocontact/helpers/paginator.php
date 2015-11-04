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
class Paginator {
	var $total = 0;
	var $limit = 0;
	var $offset = 0;
	var $url = '';
	var $page = 1;
	var $numbers = 5;
	var $start = 1;
	var $end = 5;
	
	function initialize(){
		$this->limit = !empty($this->limit) ? $this->limit : \GCore\Libs\Base::getConfig('list_limit', 15);
		$this->page = $current_page = ($this->offset/$this->limit) + 1;
		$side = floor($this->numbers/2);
		$diff = 0;
		if($current_page - $side < 1){
			$this->start = 1;
			$diff = $side - $current_page;
		}else{
			$this->start = $current_page - $side;
		}
		$this->end = $current_page + $side + $diff;
		if($this->end > ceil($this->total/$this->limit)){
			$this->end = ceil($this->total/$this->limit);
		}
		$this->url = !empty($this->url) ? $this->url : \GCore\Libs\Url::current();
	}
	
	public function getPrevious(){
		$styles = '';
		if($this->page == 1){
			$styles = 'display:none';
		}
		return \GCore\Helpers\Html::url(l_('PAGINATOR_PREV'), \GCore\Libs\Url::buildQuery($this->url, array('page' => ($this->page - 1))), array('class' => 'previous button-previous', 'style' => $styles));
	}
	
	public function getFirst(){
		$styles = '';
		if($this->page == 1){
			$styles = 'display:none';
		}
		return \GCore\Helpers\Html::url(l_('PAGINATOR_FIRST'), \GCore\Libs\Url::buildQuery($this->url, array('page' => 1)), array('class' => 'first button-first', 'style' => $styles));
	}
	
	public function getLast(){
		$styles = '';
		if($this->page == $this->end OR $this->end < 2){
			$styles = 'display:none';
		}
		return \GCore\Helpers\Html::url(l_('PAGINATOR_LAST'), \GCore\Libs\Url::buildQuery($this->url, array('page' => ceil($this->total/$this->limit))), array('class' => 'last button-last', 'style' => $styles));
	}
	
	public function getNext(){
		$styles = '';
		if($this->page == $this->end OR $this->end < 2){
			$styles = 'display:none';
		}
		return \GCore\Helpers\Html::url(l_('PAGINATOR_NEXT'), \GCore\Libs\Url::buildQuery($this->url, array('page' => ($this->page + 1))), array('class' => 'next button-next', 'style' => $styles));
	}
	
	public function getNumbers(){
		$list = array();
		for($i = $this->start; $i <= $this->end; $i++){
			$alt_class = '';
			$url = \GCore\Libs\Url::buildQuery($this->url, array('page' => ($i)));
			if($this->page == $i){
				//current page
				$alt_class = ' button-disabled active-page-number';
				$url = 'javascript:void(0);';
			}
			$list[] = \GCore\Helpers\Html::url($i, $url, array('class' => 'page-number button-page-number'.$alt_class));
		}
		if(count($list) == 1){
			$list = array();
		}
		$full = \GCore\Helpers\Html::container('span', implode("\n", $list), array('class' => 'page-numbers'));
		return $full;
	}
	
	public function getNav(){
		$first = $this->getFirst();
		$prev = $this->getPrevious();
		$numbers = $this->getNumbers();
		$next = $this->getNext();
		$last = $this->getLast();
		$full = \GCore\Helpers\Html::container('div', $first.$prev.$numbers.$next.$last, array('class' => 'gcore-datatable-paginator paging-full-numbers'));
		//add css file
		$doc = \GCore\Libs\Document::getInstance();
		$doc->addCssFile(\GCore\Helpers\Assets::css('paginator'));
		return $full;
	}
	
	public function getInfo(){
		$text = sprintf(l_('PAGINATOR_INFO'), ($this->total > 0 ? $this->offset + 1 : $this->offset), ($this->offset + $this->limit > $this->total) ? $this->total : $this->offset + $this->limit, $this->total);
		$full = \GCore\Helpers\Html::container('div', $text, array('class' => 'gcore-datatable-info'));
		return $full;
	}
	
	public function getList(){
		$dropdown = \GCore\Helpers\Html::input('limit', array('type' => 'dropdown', 'values' => $this->limit, 'onchange' => "$(this).closest('form').submit();", 'options' => array(5 => 5, 10 => 10, 15 => 15, 20 => 20, 30 => 30, 50 => 50, 100 => 100, \GCore\Libs\Base::getConfig('max_list_limit', 1000) => l_('PAGINATOR_ALL'))));
		$text = sprintf(l_('PAGINATOR_SHOW_X_ENTRIES'), $dropdown);
		$full = \GCore\Helpers\Html::container('div', $text, array('class' => 'gcore-datatable-list'));
		return $full;
	}
	
}