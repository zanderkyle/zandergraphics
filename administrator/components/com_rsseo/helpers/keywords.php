<?php
/**
* @version 1.0.0
* @package RSSEO! 1.0.0
* @copyright (C) 2009-2012 www.rsjoomla.com
* @license GPL, http://www.gnu.org/licenses/gpl-2.0.html
*/

defined('_JEXEC') or die('Restricted access');


class keywordsHelper {
	
	protected $id;
	protected $keyword;
	protected $position;
	protected $domains = array();
	protected $values = array('position' => 0, 'badge' => '', 'date' => '0000-00-00 00:00:00');
	
	public function __construct($id, $keyword, $position = 0) {
		// Set Keyword ID
		$this->id = $id;
		// Set Keyword
		$this->keyword = $keyword;
		// Get old position
		$this->position = $position;
		
		// Set domains
		$this->setDomains();
	}
	
	public function getInstance($id, $keyword, $position = 0) {
		$modelClass = 'keywordsHelper';
		return new $modelClass($id, $keyword, $position);
	}
	
	/*
	 *	Set domains
	 */
	protected function setDomains() {
		$config = rsseoHelper::getConfig();
		
		$mainsite = JURI::root();
		$mainsite_nohw = str_replace(array('http://','https://','www.'), '', $mainsite);
		
		$this->domains[] = $mainsite;
		$this->domains[] = $mainsite_nohw;
		
		if (!empty($config->subdomains)) {
			if ($subdomains = explode("\n", $config->subdomains)) {
				foreach ($subdomains as $subdomain) {
					$this->domains[] = trim($subdomain);
				}
			}
		}
	}
	
	public function check() {
		// Get configuration
		$config = rsseoHelper::getConfig();
		
		require_once JPATH_ADMINISTRATOR. '/components/com_rsseo/helpers/phpQuery.php';
		
		$keyword = str_replace(' ', '+', $this->keyword);
		$keyword = str_replace('%26', '&',$keyword);
		
		$valid = false;
		$position = 1;
		
		for($limit = 0; $limit < 5; $limit++) {
			$url 		= 'http://www.'.$config->google_domain.'/search?q='.$keyword.'&pws=0&start='.(10*$limit);
			$contents	= rsseoHelper::fopen($url,1);
			$dom		= phpQuery::newDocument($contents);
			
			foreach ($dom->find('h3[class=r] a') as $a) {
				$href = phpQuery::pq($a)->attr('href');
				foreach ($this->domains as $domain) {
					if(empty($domain)) continue;
					if(strpos($href,$domain) !== false) {
						$valid = true;
						continue;
					}
				}
				
				if ($valid) continue;
				$position++;
			}
			if ($valid) break;
		}
		
		$position = $valid ? $position : 0;
		if ($position > $this->position) 
			$color = 'important';
		else if ($position < $this->position) 
			$color = 'success';
		else if ($position == $this->position)
			$color = '';
		
		$this->update($position);
		$this->values['position'] = $position;
		$this->values['badge'] = $color;
		
		return json_encode($this->values);
	}
	
	/*
	*	Update keyword
	*/
	protected function update($position) {
		$db		= JFactory::getDbo();
		$query	= $db->getQuery(true);
		$now	= JFactory::getDate()->toSql();
		
		// Update parent
		$query->clear();
		$query->update($db->quoteName('#__rsseo_keywords'));
		$query->set($db->quoteName('date').' = '.$db->quote($now));
		$query->set($db->quoteName('position').' = '.$db->quote($position));
		$query->set($db->quoteName('lastposition').' = '.$db->quote($this->position));
		$query->where('`id` = '.$this->id);
		
		$db->setQuery($query);
		$db->execute();
		
		$this->values['date'] = JHtml::_('date', $now, rsseoHelper::getConfig('global_dateformat'));
	}
}