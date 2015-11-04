<?php
/**
* @version 1.0.0
* @package RSSEO! 1.0.0
* @copyright (C) 2009-2012 www.rsjoomla.com
* @license GPL, http://www.gnu.org/licenses/gpl-2.0.html
*/

defined('_JEXEC') or die('Restricted access');


class crawlerHelper {	
	protected $id;
	protected $initialize;
	protected $original;
	
	public function __construct($initialize, $id, $original = 0) {
		// Initialize crawler
		$this->initialize = $initialize;
		// Set page ID
		$this->id = $id;
		// Set original
		$this->original = $original;
	}
	
	public static function getInstance($initialize, $id, $original = 0) {
		$modelClass = 'crawlerHelper';
		return new $modelClass($initialize, $id, $original);
	}
	
	/**
	 *	Method to crawl a page
	 */
	public function crawl() {
		// Initialize crawler
		$this->initialize();
		
		$db		= JFactory::getDbo();
		$query	= $db->getQuery(true);
		$config	= rsseoHelper::getConfig();
		
		// Load current page
		$query->clear();
		if ($this->id) {
			$query->select($db->qn('id').', '.$db->qn('level'))->from($db->qn('#__rsseo_pages'))->where($db->qn('id').' = '.(int) $this->id)->where($db->qn('published').' != -1');
		} else {
			$query->select($db->qn('id').', '.$db->qn('level'))->from($db->qn('#__rsseo_pages'))->where($db->qn('crawled').' = 0')->where($db->qn('level').' != 127')->where($db->qn('published').' != -1')->order($db->qn('level').' ASC')->order($db->qn('id').' ASC');
		}
		
		$db->setQuery($query, 0, 1);
		$page = $db->loadObject();
		
		if (!empty($page)) {
			$thepage = $this->check($page->id);
			
			// Count the number of pages crawled
			$query->clear();
			$query->select('COUNT(id)')->from($db->qn('#__rsseo_pages'))->where($db->qn('crawled').' != 0')->where($db->qn('level').' != 127')->where($db->qn('published').' != -1');
			$db->setQuery($query);
			$pages_crawled = $db->loadResult();
			
			// Count the number of pages left on this level..
			$query->clear();
			$query->select('COUNT(id)')->from($db->qn('#__rsseo_pages'))->where($db->qn('crawled').' = 0')->where($db->qn('level').' = '.$db->q($thepage->level))->where($db->qn('published').' != -1');
			$db->setQuery($query);
			$pages_left = $db->loadResult();
			
			// Count total pages crawled
			$query->clear();
			$query->select('COUNT(id)')->from($db->qn('#__rsseo_pages'))->where($db->qn('published').' != -1');
			$db->setQuery($query);
			$total_pages = $db->loadResult();
			
			$color = '';
			switch($thepage->grade) {
				case ($thepage->grade >= 0 && $thepage->grade < 33): 
					$color = 'red'; 
				break;
				
				case ($thepage->grade >= 33 && $thepage->grade < 66):
					$color = 'orange'; 
				break;
				
				case -1:
					$color = '';
				break;
				
				default: 
					$color = 'green'; 
				break;
			}
			
			
			$values = array('url' => $thepage->url, 'title' => $thepage->title, 'level' => $thepage->level,
							'grade' => ceil($thepage->grade), 'date' => JHtml::_('date', $thepage->date, $config->global_dateformat), 'crawled' => $pages_crawled,
							'remaining' => $pages_left, 'total' => $total_pages, 'finished' => 0, 'color' => $color
			);
		} else {
			$values = array('finished' => 1, 'finishtext' => JText::_('COM_RSSEO_GLOBAL_FINISH'));
			
			// Turn on the auto crawler
			if (JFactory::getApplication()->input->getInt('auto') == 1)
				$this->auto(1);
		}
		
		return json_encode($values);
	}
	
	/**
	 *	Method to check a page
	 */
	protected function check($id) {
		JTable::addIncludePath(JPATH_ADMINISTRATOR.'/components/com_rsseo/tables');
		
		$db		= JFactory::getDbo();
		$query	= $db->getQuery(true);
		$page	= JTable::getInstance('Page', 'rsseoTable');
		$config = rsseoHelper::getConfig();
		
		// Load page details
		$page->load($id);
		
		require_once JPATH_ADMINISTRATOR. '/components/com_rsseo/helpers/phpQuery.php';
		
		$url = JURI::root().$page->url;
		$url = str_replace(' ','%20',$url);
		
		$contents	= rsseoHelper::fopen($url,1);
		
		// Remove pages that are not HTML and 500 error page
		if (strpos($contents,'<html') === false || (strpos($contents,'RSSEOINVALID') !== false && $url != '')) {
			if ($id != 1)
				$page->published = -1;
			$page->store();
			return $page;
		}
		
		$dom		= phpQuery::newDocumentHTML($contents);
		
		// Initialize values
		$title = $description = $keywords = '';
		$images = $images_no_alt = $images_no_hw = $links = $headings = 0;
		
		preg_match('#<title>(.*?)<\/title>#',$contents,$titlematch);
		preg_match_all('#<h([0-9+])(.*?)<\/h([0-9+])>#is',$contents,$matches);
		
		// Set title
		$title = !empty($titlematch) && !empty($titlematch[1]) ? $titlematch[1] : '';
		// Get number of headings
		$headings = !empty($matches) && !empty($matches[0]) ? count($matches[0]) : 0;
		// Get the number of links
		$links = $dom->find('a')->length();
		// Get meta description
		$description = $dom->find('meta[name=description]')->attr('content');
		// Get meta keywords
		$keywords = $dom->find('meta[name=keywords]')->attr('content');
		// Get total number of images
		$images = $dom->find('img')->length();
		// Get images without the alt attribute
		$images_no_alt = $dom->find('img:not([alt])')->length();
		// Get images without the width and height attribute
		$images_no_hw = $dom->find('img:not([width],[height])')->length();
		
		// Get images without the alt attribute
		$imagesnoalt = array();
		foreach ($dom->find('img:not([alt])') as $image) {
			$src = phpQuery::pq($image)->attr('src');
			if (!in_array($src,$imagesnoalt))
				$imagesnoalt[] = $src;
		}
		
		if (!empty($imagesnoalt)) {
			$registry = new JRegistry;
			$registry->loadArray($imagesnoalt);
			$page->imagesnoalt = $registry->toString();
		} else $page->imagesnoalt = '';
		
		// Get images without the width and height attribute
		$imagesnowh = array();
		foreach ($dom->find('img:not([width],[height])') as $image) {
			$src = phpQuery::pq($image)->attr('src');
			if (!in_array($src,$imagesnowh))
				$imagesnowh[] = $src;
		}
		
		if (!empty($imagesnowh)) {
			$registry = new JRegistry;
			$registry->loadArray($imagesnowh);
			$page->imagesnowh = $registry->toString();
		} else $page->imagesnowh = '';
		
		// Calculate the density keywords
		$density_keywords = $page->keywordsdensity;
		if (!empty($density_keywords)) {
			$density_keywords	= explode(',',$density_keywords);			
			array_walk($density_keywords, array('crawlerHelper', 'lowercasearray'));
			$density_keywords	= array_unique($density_keywords);			
			$content			= mb_strtolower(strip_tags($contents),'UTF-8');
			$densityparams		= array();
			
			foreach ($density_keywords as $keyword) {
				if (empty($keyword)) continue;
				$densityparams[$keyword] = $this->keywordDensity($content,$keyword);
			}
			
			$registry = new JRegistry;
			$registry->loadArray($densityparams);			
			$page->densityparams = $registry->toString();
		} else $page->densityparams = '';
		
		// Set page tile, page description and page keywords
		$page->title		= html_entity_decode($title, ENT_COMPAT, 'UTF-8');
		$page->description	= html_entity_decode($description, ENT_COMPAT, 'UTF-8');
		$page->keywords		= html_entity_decode($keywords, ENT_COMPAT, 'UTF-8');
		
		// Create the parameters
		$params = array();
		
		// Check for SEF links
		$params['url_sef'] = strpos($page->url, '.php?') === FALSE ? 1 : 0;
		
		// Check for title duplicates
		$query->clear();
		$query->select('COUNT(id)')->from($db->qn('#__rsseo_pages'))->where($db->qn('title').' = '.$db->quote($page->title))->where($db->qn('published').' = 1');
		$db->setQuery($query);
		$params['duplicate_title'] = $db->loadResult();
		
		// Check for title length
		$params['title_length'] = function_exists('mb_strlen') ? mb_strlen($page->title) : strlen($page->title);
		
		// Check for description duplicates
		$query->clear();
		$query->select('COUNT(id)')->from($db->qn('#__rsseo_pages'))->where($db->qn('description').' = '.$db->quote($page->description))->where($db->qn('published').' = 1');
		$db->setQuery($query);
		$params['duplicate_desc'] = $db->loadResult();		
		
		// Check for description length
		$params['description_length'] = function_exists('mb_strlen') ? mb_strlen($page->description) : strlen($page->description);
		
		// Check for the number of keywords
		if (trim($keywords) != '') {
			$k = explode(',',$page->keywords);
			$params['keywords'] = count($k);
		} else $params['keywords'] = 0;
		
		$params['headings'] = $headings;
		$params['images'] = $images;
		$params['images_wo_alt'] = $images_no_alt;
		$params['images_wo_hw'] = $images_no_hw;
		$params['links'] = $links;
		
		$registry = new JRegistry;
		$registry->loadArray($params);
		$page->params = $registry->toString();
		
		// Calculate the page grade
		$grade = $total = 0;
		
		if ($params['url_sef'] == 1 && $config->crawler_sef) $grade ++;
		if ($params['duplicate_title'] == 1 && $config->crawler_title_duplicate) $grade ++;
		if ($params['title_length'] >= 10 && $params['title_length'] <= 70 && $config->crawler_title_length) $grade ++;
		if ($params['duplicate_desc'] == 1 && $config->crawler_description_duplicate) $grade ++;
		if ($params['description_length'] >= 70 && $params['description_length'] <= 150 && $config->crawler_description_length) $grade ++;
		if ($params['keywords'] <= 10 && $config->crawler_keywords) $grade ++;
		if ($params['headings'] > 0 && $config->crawler_headings) $grade ++;
		if ($params['images'] <= 10 && $config->crawler_images) $grade ++;
		if ($params['images_wo_alt'] == 0 && $config->crawler_images_alt) $grade ++;
		if ($params['images_wo_hw'] == 0 && $config->crawler_images_hw) $grade ++;
		if ($params['links'] <= 100 && $config->crawler_intext_links) $grade ++;
		
		if ($config->crawler_sef) $total ++;
		if ($config->crawler_title_duplicate) $total ++;
		if ($config->crawler_title_length) $total ++;
		if ($config->crawler_description_duplicate) $total ++;
		if ($config->crawler_description_length) $total ++;
		if ($config->crawler_keywords) $total ++;
		if ($config->crawler_headings) $total ++;
		if ($config->crawler_images) $total ++;
		if ($config->crawler_images_alt) $total ++;
		if ($config->crawler_images_hw) $total ++;
		if ($config->crawler_intext_links) $total ++;
		
		if ($total == 0)
			$page->grade = 0;
		else
			$page->grade = ($grade * 100 / $total);
		
		$page->crawled = 1;
		$page->date = JFactory::getDate()->toSql();
		if ($page->level <= 127) $page->store();
		
		// Get ignored links
		$ignored = $config->crawler_ignore;
		$ignored = str_replace("\r",'',$ignored);
		$ignored = explode("\n", $ignored);
		
		if ($config->crawler_level == -1 || ($config->crawler_level != -1 && $page->level < $config->crawler_level)) {
			foreach ($dom->find('a') as $href) {
				$href = phpQuery::pq($href)->attr('href');
				if ($href = $this->clean_url($href)) {
					
					// Skip ignored links
					foreach($ignored as $ignore) {
						if(!empty($ignore)) {
							$ignore = str_replace('&', '&amp;', $ignore);
							if ($this->ignored($href, $ignore))
								continue 2;
						}
					}
					
					// Skip unwanted links
					if (strpos($href,'mailto:') !== FALSE) continue;
					if (strpos($href,'javascript:') !== FALSE) continue;
					if (strpos($href,'ymsgr:im') !== FALSE) continue;
					if ($page->level >= 127) continue;
					if ($href == 'administrator/' || $href == 'administrator') continue;
					if (substr($href,0,1) == '#') continue;
					
					// Replace the root if any
					$href = str_replace(JURI::root(),'',$href);
					
					// Add URL to database
					$query->clear();
					$query->select('COUNT(id)')->from($db->qn('#__rsseo_pages'))->where($db->qn('url').' = '.$db->q($href));
					$db->setQuery($query);
					if($db->loadResult() == 0) {						
						$query->clear();
						$query->insert($db->qn('#__rsseo_pages'))->set($db->qn('url').' = '.$db->q($href))->set($db->qn('level').' = '.$db->q($page->level + 1));
						$query->set($db->qn('title').' = '.$db->q(''))->set($db->qn('insitemap').' = '.$db->q('1'))->set($db->qn('sitemap').' = '.$db->q('0'))->set($db->qn('crawled').' = '.$db->q('0'));
						$query->set($db->qn('date').' = '.$db->q(JFactory::getDate()->toSql()));
						$db->setQuery($query);
						$db->execute();
					}
				}
			}
		}
		
		return $page;
	}
	
	/**
	 *	Method to initialize the crawler
	 */
	protected function initialize() {
		$db		= JFactory::getDbo();
		$query	= $db->getQuery(true);
		$config = rsseoHelper::getConfig();
		
		if ($this->initialize) {
			$query->clear();
			$query->update($db->qn('#__rsseo_pages'))->set($db->qn('crawled').' = 0')->where($db->qn('published').' != -1');
			$db->setQuery($query);
			$db->execute();
			
			// Turn off the auto crawler
			if ($config->crawler_enable_auto)
				$this->auto(0);
		}
		
		if ($this->original) {
			$query->clear();
			$query->update($db->qn('#__rsseo_pages'))->set($db->qn('crawled').' = 0')->set($db->qn('modified').' = 0')->where($db->qn('id').' = '.$db->q($this->id));
			$db->setQuery($query);
			$db->execute();
		}
	}
	
	/**
	 *	Method to check if a link is ignored
	 */
	protected function ignored($url, $pattern) {
		$pattern = $this->transform_string($pattern);	
		preg_match_all($pattern, $url, $matches);
		
		if (count($matches[0]) > 0) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 *	Method to transform a string
	 */
	protected function transform_string($string) {
		$string = preg_quote($string, '/');
		$string = str_replace(preg_quote('{*}', '/'), '(.*)', $string);	
		
		$pattern = '#\\\{(\\\\\?){1,}\\\}#';
		preg_match_all($pattern, $string, $matches);
		if (count($matches[0]) > 0) {
			foreach ($matches[0] as $match) {
				$count = count(explode('\?', $match)) - 1;
				$string = str_replace($match, '(.){'.$count.'}', $string);
			}
		}
		
		return '#'.$string.'$#';
	}
	
	/**
	 *	Method to calculate a keywords density
	 */
	protected function keywordDensity($string, $keyword) {
		$pattern =  "/\p{L}[\p{L}\p{Mn}\p{Pd}'\x{2019}]*/u";
		$total_words = preg_match_all($pattern, $string, $matches);
		$times_used  = mb_substr_count($string,$keyword,'UTF-8');
		
		if (!$total_words) return '0.00 %';
		if (!$times_used) return '0.00 %';
		
		$density = ($times_used / $total_words) * 100;
		return number_format($density,2).' %';
	}
	
	/**
	 *	Method to clean URL
	 */
	protected function clean_url($url) {
		$internal_links[] = JURI::root();
		$internal_links[] = JURI::root(true);
		
		foreach($internal_links as $internal_link) {
			$url = str_replace($internal_link, '', $url);
		}
		
		// If url still contains http:// it's an external link
		if (strpos($url,'http://') !== false || strpos($url,'https://') !== false || strpos($url,'ftp://') !== false) {
			return false;
		}
		
		//let's clear anything after #
		$url_exp = explode('#',$url);
		$url = $url_exp[0];
		
		$array_extensions = array('jpg','jpeg','gif','png','pdf','doc','xls','odt','mp3','wav','wmv','wma','evy','fif','spl','hta','acx','hqx','doc','dot','bin','class','dms','exe','lha','lzh','oda','axs','pdf','prf','p10','crl','ai','eps','ps','rtf','setpay','setreg','xla','xlc','xlm','xls','xlt','xlw','msg','sst','cat','stl','pot','pps','ppt','mpp','wcm','wdb','wks','wps','hlp','bcpio','cdf','z','tgz','cpio','csh','dcr','dir','dxr','dvi','gtar','gz','hdf','ins','isp','iii','js','latex','mdb','crd','clp','dll','m13','m14','mvb','wmf','mny','pub','scd','trm','wri','cdf','nc','pma','pmc','pml','pmr','pmw','p12','pfx','p7b','spc','p7r','p7c','p7m','p7s','sh','shar','sit','sv4cpio','sv4crc','tar','tcl','tex','texi','texinfo','roff','t','tr','man','me','ms','ustar','src','cer','crt','der','pko','zip','au','snd','mid','rmi','mp3','aif','aifc','aiff','m3u','ra','ram','wav','bmp','cod','gif','ief','jpe','jpeg','jpg','jfif','svg','tif','tiff','ras','cmx','ico','pnm','pbm','pgm','ppm','rgb','xbm','xpm','xwd','nws','css','323','stm','uls','bas','c','h','txt','rtx','sct','tsv','htt','htc','etx','vcf','mp2','mpa','mpe','mpeg','mpg','mpv2','mov','qt','lsf','lsx','asf','asr','asx','avi','movie','flr','vrml','wrl','wrz','xaf','xof','swf');
		
		for ($i = 0; $i < count($array_extensions); $i++) {
			if (strtolower(substr($url, strlen($url) - (strlen($array_extensions[$i]) + 1))) == '.'.$array_extensions[$i]) {
				return false;
			}
		}
		
		if (substr($url,0,1) == '/') 
			$url = substr($url,1);
		
		$url = urldecode($url);
		
		return $url;
	}
	
	protected function auto($value) {
		if (!$this->id) {
			$db		= JFactory::getDbo();
			$query	= $db->getQuery(true);
			$component	= JComponentHelper::getComponent('com_rsseo');
			$cparams	= $component->params;
			
			if ($cparams instanceof JRegistry) {
				$cparams->set('crawler_enable_auto', $value);
				$query->clear();
				$query->update($db->quoteName('#__extensions'));
				$query->set($db->quoteName('params'). ' = '.$db->quote((string) $cparams));
				$query->where($db->quoteName('extension_id'). ' = '. $db->quote($component->id));
				
				$db->setQuery($query);
				$db->execute();
			}
		}
		return true;
	}
	
	protected function lowercasearray(&$item) {
		$item = mb_strtolower(trim($item),'UTF-8');		
	}
}