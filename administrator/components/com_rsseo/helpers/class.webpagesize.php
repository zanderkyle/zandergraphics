<?php
## v0.1	Beta ## 02 April 2005
## v0.2	Beta ## 05 April 2005
## v1.0	## 06 April 2005
##      { using curl to get filesize }
## v1.1 ## 07 April 2005
##      { supporting 'base url' }
##      ## 09 April 2005
##      { fixing bugs on parsing CSS }
##
######
## Webpage Size Calculator
##
## Is your website page too fat?
## If your website pages are too fat, there are many problems you face.
## Such as: (1) slow motion on displaying your webpage,
##          (2) bandwidth usage that is over-dose.
## But, how dou know the size of your webpage?
## It is not easy to measure your webpage weight, in particular a dynamic page.
##
## This class calculates size of webpage and all elements, such as image, js, swf, frame, etc.
## By knowing your page size, you can take an action,
## wether reducing the size or removing unimportant parts.
##
## Limitation
## Can not measure javascript`s pre-loading images
#######
## Author: Huda M Elmatsani
## Email: 	justhuda ## gmail ## com
##
## 04/02/2005
#######
## Copyright (c) 2005 Huda M Elmatsani All rights reserved.
## This program is freeware
## Please, tell me if you made improvements or just a little modification
## Please, tell me if you made online tool with this class
########
## USAGE
##
## $size = new WebpageSize;
## $size->setURL("http://www.php.net/");
## $size->printResult();
##
## see sample.webpagesize.php
##
## credits:
## Fauzan Aminuddin, Satya Agustan Dinata, Ciko Parera @phpug-at-yahoogroups
####

class WebpageSize {

	public $url			= '';
    public $baseurl		= '';
    public $tailfile	= '';
    public $totalsize	= '';
    public $pages		= array();
    public $freqpages	= array(); //frequency of page element to be loaded

	public function __construct($url) {
		$this->url = $this->parseURL($url);
	}

    protected function parseURL($url) {
        $this->tailfile = substr($url, strrpos($url, '/')+1);
        $parsed = parse_url($url);
        
		if ($this->tailfile == $parsed['host']) {
			$this->tailfile = '';
		}
		
		if (substr($url, -1)=='/' || $this->tailfile) {
            return $url;
		} else {
            $url =  $url.'/';
		}
		
        return $url;
    }

    /*
    * searching base href
    */
	protected function setBaseURL($str) {
		preg_match("/base.*[\s]*href[\040]*=[\040]*\"?([^\"' >]+)/ie", $str, $match);

         if(isset($match[1])) {
            $url = $this->parseURL($match[1]);
            if(substr($url, -1)!='/') {
				$url .= '/';
			}
			
            $this->baseurl = $url;
        } else {
            $this->baseurl = $this->url;
        }
    }

    /*
    *  core function!
    *  page elements and the size
    */
	public function getResult() {
        $pages	= array();
		$paths	= $this->grabPageSources();
        array_unshift($paths, $this->url);
		
		for ($i=0; $i<count($paths); $i++) {
			if (!array_key_exists($paths[$i],$pages)) {
				$filesize = strlen($this->getContent($paths[$i]));	
				$this->freqpages[$paths[$i]] = 1;
				$pages[$paths[$i]] = $filesize;
				$this->totalsize  += $filesize;
			} else {
				$this->freqpages[$paths[$i]] += 1;
			}
		}
		
        natsort($pages);
        return $pages;
	}

	public function totalPageSize() {
		return $this->totalsize;
	}
	
	public function getTotal() {
		return $this->readableSize($this->totalPageSize());
	}

	/*
	* this one is usefull
	*/
	public function readableSize($size) {
		return number_format($size/1024,2)." KB";
	}

	public function getPages() {
		$pages	= $this->getResult();
		$return	= array();
		
		while (list($url,$size) = each($pages)){
			$page = new stdClass();
			$page->url	= $url;
			$page->size = $this->readableSize($size);
			$page->freq = $this->freqpages[$url];
			
			$return[] = $page;
		}
		
		return $return;
	}
	
	public function sizeofpage() {
		$pages = $this->getResult();
		return $this->readableSize($this->totalPageSize());
	}
	
	public function getTime() {
		$pages = $this->getResult();
		
		while(list($url,$size) = each($pages)) {
			$time = microtime();
			$time = explode(" ", $time);
			$time = $time[1] + $time[0];
			$start = $time;
			$this->getContent($url);
			$time = microtime();
			$time = explode(" ", $time);
			$time = $time[1] + $time[0];
			$finish = $time;
			$totaltime = ($finish - $start);
			$this->totaltime += $totaltime;
		}
		
		return $this->totaltime;
	}

    /*
    *  taking webpage content
    *  fopen is lighter than cURL
    */
	protected function getContent($url){
		return rsseoHelper::fopen($url);
    }

    /*
    * searching webpage elements
    */
	protected function grabPageSources() {
		$content  = $this->getContent($this->url);
		$this->setBaseURL($content);
		
		$arr_src1 = array();
		$arr_src2 = array(); 
		$arr_src3 = array();
        $arr_src4 = array();
		$arr_src5 = array();
		$arr_src6 = array();	
	
		$arr_src1 = $this->searchSources($content);
        
		//search CSS classes that applied on page
		$arr_src2 = $this->xsearchSourcesOnCSS($content);
		if(empty($arr_src2)) $arr_src2 = array();
		
		$arr_src3 = $this->searchCSSLinks($content);
       /*  if(!empty($arr_src3))
			$arr_src4 = $this->searchSourcesOnCSSFiles($arr_src3); */
       
	   //search on frames if exists
        $arr_src5 = $this->searchFrames($content);
		
        if(!empty($arr_src5))
			$arr_src6 = $this->searchSourcesOnFrames($arr_src5);

		$arr_sources  = array_merge ($arr_src1, $arr_src2, $arr_src3, $arr_src4, $arr_src5, $arr_src6);
		$arr_sources = array_unique($arr_sources);
		
        return $this->resolvePathSources($arr_sources);
	}

    /*
    * searching image/js elements
    */
	protected function searchSources($str) {
		preg_match_all("/[img|input|embed|script]+.*[\s]*(src|background)[\040]*=[\040]*\"?([^\"' >]+)/ie", $str, $arr_source);
		return $arr_source[2];
	}

    /*
    * searching class elements
    */
	protected function searchCSSClasses($str) {
		preg_match_all("/class[\040]*=[\040]*\"?([^\"' >]+)/ie", $str, $arr_source);
		if (!empty($arr_source) && !empty($arr_source[1])) {
			$return = array_unique($arr_source[1]);
			return $return;
		} else return array();
	}
	
    /*
    * searching frame elements
    */
	protected function searchFrames($str) {
		preg_match_all("/frame.*[\s]*src[\040]*=[\040]*\"?([^\"' >]+)/ie", $str, $arr_source);
		return $arr_source[1];
	}

    /*
    * searching css elements
    */
	protected function xsearchSourcesOnCSS($str) {
		preg_match_all("/(url\(\"?([^\")]+))/ie", $str, $arr_source);
		return $arr_source[2];
	}

    /*
    * searching webpage elements on frames
    */
    protected function searchSourcesOnFrames($framefiles) {
        $arr_source  = array();
        $arr_sources = array();
        while(list(,$src)   = each($framefiles)) {
            $framepage        = $this->makeAbsolutePath($src,$this->baseurl);
            $page = new WebpageSize($framepage);
            $arr_source  = $page->grabPageSources();
            $arr_sources = array_merge($arr_sources, $arr_source);
        }
        return $arr_sources;
    }

    /*
    * searching webpage elements on CSS files
    */
    protected function searchSourcesOnCSSFiles($cssfiles) {
        //search sources on CSS file
        $arr_CSSlinks = array();
        while(list(,$src)   = each($cssfiles)) {
            $numstepback    = substr_count($src, "../");
            $CSSpage        = $this->makeAbsolutePath($src,$this->baseurl);

            $CSScontent     = $this->getContent($CSSpage);
            $arr_sourcelink    = $this->xsearchSourcesOnCSS($CSScontent);
            if(empty( $arr_sourcelink )) continue;

            while(list(,$srclink)   = each($arr_sourcelink)) {
                $arr_CSSlink[]  =   str_repeat("../",$numstepback) . $srclink;
            }
            $arr_CSSlinks   = array_merge($arr_CSSlinks, $arr_CSSlink);
        }
		
        return $arr_CSSlinks;
    }

    /*
    * searching webpage elements on CSS
    */
	protected function searchCSSLinks($str) {
        preg_match_all("/<link[^>]+href[\040]*=[\040]*[\"|\'|\\\\]*([^\'|\"|>|\040]*(.*)\.css)[\"|\'|>|\040|\\\\]*/ie",$str, $arr_CSSlink);
        return $arr_CSSlink[1];
	}

	/*
    *  from "../../images/some.jpg" for example to "http://www.domain.com/images/some.jpg"
    */
	protected function resolvePathSources($paths) {
		$arr_path = array();
		
		if(!empty($paths)) {
			foreach ($paths as $src) {
				$src = str_replace("'","",$src);
				$arr_path[] = $this->makeAbsolutePath($src,$this->baseurl);
			}
		}
		
	   return $arr_path;
	}
	
	protected function makeAbsolutePath($src, $url) {
		$addone = 1;
		$config = new JConfig();
		$sef	= $config->sef;
		
		if (strtolower(substr($src,0,4)) != 'http') {
			$u =  JURI::getInstance();
			$uri =  JURI::getInstance($src);
			$src = $uri->toString();
			
			$numrel  = substr_count($src, "../");
			$src     = str_replace("../","",$src);

			for($i=0; $i < $numrel+$addone; $i++) {
				$lastslash  = strrpos($url,"/");
				$url       = substr($url, 0, $lastslash);
			}
			
			return $u->toString(array('scheme','host')).$src;
		} else return $src;
	}
}