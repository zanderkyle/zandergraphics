<?php
/**
* @version 1.0.0
* @package RSSEO! 1.0.0
* @copyright (C) 2009-2012 www.rsjoomla.com
* @license GPL, http://www.gnu.org/licenses/gpl-2.0.html
*/

defined('_JEXEC') or die('Restricted access');


class competitorsHelper {
	
	protected $id;
	protected $url;
	
	protected $values = array(
		'pagerank' => 0,
		'googlep' => 0,
		'googleb' => 0,
		'bingp' => 0,
		'bingb' => 0,
		'alexa' => 0,
		'technorati' => 0,
		'dmoz' => 0,
	);
	
	public function __construct($id, $url) {
		// Set Competitor ID
		$this->id = $id;
		
		// Set Competitor URL
		$this->url = $url;
	}
	
	public static function getInstance($id, $url) {
		$modelClass = 'competitorsHelper';
		return new $modelClass($id, $url);
	}
	
	public function check($output = false) {
		// Get configuration
		$config = rsseoHelper::getConfig();
		
		if ($config->enable_pr)
			$this->pagerank();
		
		if ($config->enable_googlep)
			$this->googlepages();
		
		if ($config->enable_googleb)
			$this->googlebacklinks();
		
		if ($config->enable_bingp)
			$this->bingpages();
		
		if ($config->enable_bingb)
			$this->bingbacklinks();
		
		if ($config->enable_alexa)
			$this->alexa();
		
		if ($config->enable_tehnorati)
			$this->technorati();
		
		if ($config->enable_dmoz)
			$this->dmoz();
		
		$this->update();
		
		return json_encode($this->values);
	}
	
	/*
	*	Calculate Google page rank
	*/
	protected function pagerank() {
		$db		= JFactory::getDbo();
		$query	= $db->getQuery(true);
		$check	= false;
		
		$query->clear();
		$query->select('`pagerank`, `date`')->from('`#__rsseo_competitors`')->where('`parent_id` = '.$this->id)->order('`date` DESC');
		$db->setQuery($query,0,1);
		$cache = $db->loadObject();
		
		if (empty($cache)) {
			$check = true;
		} else {
			if (JFactory::getDate($cache->date)->toUnix() + 86400 < JFactory::getDate()->toUnix())
				$check = true;
			else 
				$this->values['pagerank'] = (int) $cache->pagerank;
		}
		
		if ($check) {
			require_once JPATH_ADMINISTRATOR. '/components/com_rsseo/helpers/google.php';
			$google = new RSSeoGoogle($this->url);
			$this->values['pagerank'] = (int) $google->prank();
		}
	}
	
	/*
	*	Calculate Google pages
	*/
	protected function googlepages() {
		$url = str_replace(array('http://','https://','www.'),'',$this->url);
		$url = 'http://www.'.rsseoHelper::getConfig('google_domain').'/search?q=site%3A' . urlencode($url);
		
		if ($data = rsseoHelper::fopen($url)) {	
			$pattern = '#<div id=resultStats>(.*?)<nobr>#is';
			if (preg_match($pattern, $data, $match)) {
				if (!empty($match[1])) {
					$result = trim($match[1]);
					if ($result = preg_replace('#[^0-9]#', '', $result))
						$this->values['googlep'] = $result;
				} else $this->values['googlep'] = 0;
			} else $this->values['googlep'] = 0;
		} else $this->values['googlep'] = -1;
	}
	
	/*
	*	Calculate Google backlinks
	*/
	protected function googlebacklinks() {
		$url = str_replace(array('http://','https://','www.'), '', $this->url);
		$url = 'http://www.'.rsseoHelper::getConfig('google_domain').'/search?q=link%3A' . urlencode($url);
		
		if ($data = rsseoHelper::fopen($url)) {
			$pattern = '#<div id=resultStats>(.*?)<nobr>#is';
			if (preg_match($pattern, $data, $match)) {
				if (!empty($match[1])) {
					$result = trim($match[1]);
					if ($result = preg_replace('#[^0-9]#', '', $result)) {
						$this->values['googleb'] = $result;
					} else $this->values['googleb'] = 0;
				} else $this->values['googleb'] = 0;
			} else $this->values['googleb'] = 0;
		} else $this->values['googleb'] = -1;
	}
	
	/*
	*	Calculate Bing pages
	*/
	protected function bingpages() {
		$url = str_replace(array('http://','https://','www.'), '', $this->url);
		$url = 'http://www.bing.com/search?q=' . urlencode($url);
		$found = false;
		
		if ($data = rsseoHelper::fopen($url)) {
			$pattern1 = '#<span class="sb_count" id="count">(.*?) results<\/span>#i';
			$pattern2 = '#<span class="sb_count" id="count">(.*?) of (.*?) results<\/span>#i';
			
			if (preg_match($pattern1, $data, $matches1)) {
				if (!empty($matches1[1])) {
					$this->values['bingp'] = str_replace(array(',','.'),'',$matches1[1]);
					$found = true;
				}
			}
			
			if (!$found) {
				if (preg_match($pattern2, $data, $matches2)) {
					if (!empty($matches2[2])) {
						$this->values['bingp'] = str_replace(array(',','.'), '', $matches2[2]);
						$found = true;
					}
				}
			}
		}
		
		if (!$found)
			$this->values['bingp'] = -1;
	}
	
	/*
	*	Calculate Bing backlinks
	*/
	protected function bingbacklinks() {
		$url = str_replace(array('http://','https://','www.'),'',$this->url);
		$url = 'http://www.bing.com/search?filt=all&q=link%3A' . urlencode($url);
		$found = false;
		
		if ($data = rsseoHelper::fopen($url)) {
			$pattern1 = '#<span class="sb_count" id="count">(.*?) results<\/span>#i';
			$pattern2 = '#<span class="sb_count" id="count">(.*?) of (.*?) results<\/span>#is';
			
			if (preg_match($pattern1, $data, $matches1)) {
				if (!empty($matches1[1])) {
					$this->values['bingb'] = str_replace(array(',','.'), '', $matches1[1]);
					$found = true;
				}
			}
			
			if (!$found) {
				if (preg_match($pattern2, $data, $matches2)) {
					if (!empty($matches2[2])) {
						$this->values['bingb'] = str_replace(array(',','.'), '', $matches2[2]);
						$found = true;
					}
				}
			}
		}
		
		if (!$found)
			$this->values['bingb'] = -1;
	}
	
	/*
	*	Calculate Alexa rank
	*/
	protected function alexa() {
		$url = trim($this->url);
		$url = str_replace(array('http://','https://','www.'), '', $url);
		$url = 'http://data.alexa.com/data?cli=10&dat=snbamz&url=' . urlencode($url);
		
		if ($data = rsseoHelper::fopen($url)) {
			$pattern = '#<popularity url="(.*?)" text="([0-9]+)"#is';
			if (preg_match($pattern, $data, $match)) {
				if (!empty($match[2]))
					$this->values['alexa'] = $match[2];
			} else $this->values['alexa'] = 0;
		} else $this->values['alexa'] = -1;
	}
	
	/*
	*	Calculate Technorati rank
	*/
	protected function technorati() {
		$url = trim($this->url);
		$url = str_replace(array('http://','https://'),'',$url);
		$url = 'http://technorati.com/blogs/'. urlencode($url);
		
		if ($data = rsseoHelper::fopen($url,1)) {
			if (preg_match('/Authority: (.*)<\/strong>/isU',$data,$match)) {
				if (!empty($match[1])) {
					$this->values['technorati'] = $match[1];
				} else $this->values['technorati'] = 0;
			} $this->values['technorati'] = 0;
		} else $this->values['technorati'] = -1;
	}
	
	/*
	*	Calculate Dmoz
	*/
	protected function dmoz() {
		$url = str_replace(array('http://','https://','www.'), '', $this->url);
		$url = 'http://www.dmoz.org/search?q='.urlencode($url).'&cat=all&all=no';
		
		if ($data = rsseoHelper::fopen($url,0)) {
			$pattern = '#<h3 class=\"open-dir-sites\">(.*?)<\/h3>#is';
			if (preg_match($pattern,$data,$matches)) {
				if (!empty($matches[1])) {
					$pattern = '#<small>\((.*?) of (.*?)\)</small>#is';
					if (preg_match($pattern,$matches[1],$match)) {
						if (!empty($match[2])) {
							$this->values['dmoz'] = 1;
						} else $this->values['dmoz'] = 0;
					} else $this->values['dmoz'] = 0;
				} else $this->values['dmoz'] = 0;
			} else $this->values['dmoz'] = 0;
		} else $this->values['dmoz'] = 0;
	}
	
	/*
	*	Update values
	*/
	protected function update() {
		$db		= JFactory::getDbo();
		$query	= $db->getQuery(true);
		$now	= JFactory::getDate()->toSql();
		
		// Add new record for history
		$query->clear();
		$query->insert('`#__rsseo_competitors`');
		$query->set('`parent_id` = '. (int) $this->id);
		$query->set('`date` = '.$db->quote($now));
		
		foreach($this->values as $name => $value) {
			$query->set($db->quoteName($name).' = '.$db->quote($value));
		}
		
		$db->setQuery($query);
		$db->execute();
		
		// Update parent
		$query->clear();
		$query->update('`#__rsseo_competitors`');
		$query->set('`date` = '.$db->quote($now));
		$query->where('`id` = '.$this->id);
		
		foreach($this->values as $name => $value) {
			$query->set($db->quoteName($name).' = '.$db->quote($value));
		}
		
		$db->setQuery($query);
		$db->execute();
	}
}