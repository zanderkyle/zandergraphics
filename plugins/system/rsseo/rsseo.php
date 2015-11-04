<?php
/**
* @version 1.0.0
* @package RSSEO! 1.0.0
* @copyright (C) 2009-2012 www.rsjoomla.com
* @license GPL, http://www.gnu.org/licenses/gpl-2.0.html
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
$_SESSION['VMCHECK'] = 'NOCHECK';

/**
 * RSSeo system plugin
 */
class plgSystemRsseo extends JPlugin
{
	public $url;
	
	/**
	 * Object Constructor.
	 *
	 * @access	public
	 * @param	object	The object to observe -- event dispatcher.
	 * @param	object	The configuration object for the plugin.
	 * @return	void
	 * @since	1.6
	 */
	public function __construct(&$subject, $config) {
		parent::__construct($subject, $config);
		$this->url = urldecode(str_replace(JURI::root(), '', JURI::getInstance()));
	}
	
	/**
	 *	Check if the plugin can run
	 */
	protected function canRun() {
		if (file_exists(JPATH_ADMINISTRATOR.'/components/com_rsseo/helpers/rsseo.php')) {
			require_once JPATH_ADMINISTRATOR.'/components/com_rsseo/helpers/rsseo.php';
			return true;
		}
		
		return false;
	}
	
	/**
	 *	onAfterDispatch method
	 */
	public function onAfterDispatch() {
		$doc		= JFactory::getDocument();
		$app 		= JFactory::getApplication();
		$jconfig	= JFactory::getConfig();
		
		if (!$this->canRun() || $app->isAdmin()) {
			return false;
		}
		
		$config		= rsseoHelper::getConfig();
		
		// Set Yahoo! site verification key
		if ($this->params->get('enabley',0)) {
			$doc->setMetaData('y_key', $this->params->get('contenty',''));
		}
		// Set Bing site verification key
		if ($this->params->get('enableb',0)) {
			$doc->setMetaData('msvalidate.01', $this->params->get('contentb',''));
		}
		// Set Google site verification key
		if ($this->params->get('enable',0)) {
			$doc->setMetaData($this->params->get('type','google-site-verification'), $this->params->get('content',''));
		}
		
		// Add site name in title
		$sitename = $jconfig->get('sitename');
		if ($config->site_name_in_title != 0 && !empty($sitename)) {
			if ($oldtitle = $doc->getTitle()) {
				if (strpos($oldtitle, $sitename) === FALSE) {
					if ($config->site_name_in_title == 1) {
						$doc->setTitle($oldtitle.' '.$config->site_name_separator.' '.$sitename);
					} else if ($config->site_name_in_title == 2) {
						$doc->setTitle($sitename.' '.$config->site_name_separator.' '.$oldtitle);
					}
				}
			}
		}
		
		// Add page if auto-crawler is ON
		$this->auto();
		
		// Set new metadata
		$this->meta();
	}
	
	/**
	 *	onAfterInitialise method
	 */
	public function onAfterInitialise() {
		$db		= JFactory::getDbo();
		$query	= $db->getQuery(true);
		$app	= JFactory::getApplication();
		
		if (!$this->canRun() || $app->isAdmin()) {
			return false;
		}
		
		// Get current URL
		$url = $this->getURL();
		
		// Redirect page if available
		$query->clear();
		$query->select('*')->from($db->qn('#__rsseo_redirects'))->where($db->qn('published').' = 1');
		$db->setQuery($query);
		
		if ($redirects = $db->loadObjectList()) {
			foreach ($redirects as $redirect) {
				if (urldecode(trim($redirect->from)) == urldecode($url)) {
					if (empty($redirect->to)) 
						continue;
					
					$redirectURL = substr($redirect->to,0,4) != 'http' ? JURI::root().$redirect->to : $redirect->to;
					
					if ($redirect->type == 301) {
						header("HTTP/1.1 301 Moved Permanently");
						header("Location: ".$redirectURL);
						$app->close();
					} else {
						header("Location: ".$redirectURL);
						$app->close();
					}
				}
			}
		}
		
		
		// Canonicalization
		if ($this->params->get('enablecan','0')) {
			$host = $this->params->get('domain','');
			$host = trim($host);
			
			if ($host) {
				$host = str_replace(array('http://','https://'), '', $host);
				if(@$_SERVER['HTTP_HOST'] == $host) {
					return true;	
				}
				// Get protocol
				$protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') ? 'https://' : 'http://';
				
				$url = $protocol . $host . $_SERVER['REQUEST_URI'];
				header("HTTP/1.1 301 Moved Permanently");
				header('Location: '. $url);
				$app->close();
			}
		}
	}
	
	/**
	 *	onAfterRender method
	 */
	public function onAfterRender() {
		$db		= JFactory::getDbo();
		$query	= $db->getQuery(true);
		$app	= JFactory::getApplication();
		$change = false;
		
		if (!$this->canRun() || $app->isAdmin()) {
			return false;
		}
		
		$config	= rsseoHelper::getConfig();
		
		// Get page body
		$body = JResponse::getBody();
		
		// Remove the meta generator
		if ($this->params->get('generator',0)) {
			$body = preg_replace('/<meta.*name=[\",\']generator[\",\'].*\/?>/i', '', $body);
			$change = true;
		}
		
		// Replace keywords
		if ($config->enable_keyword_replace == 1) {
			$change = true;
			
			// Get all the keywords
			$query->clear();
			$query->select('*')->from($db->qn('#__rsseo_keywords'))->order($query->charLength('keyword').' DESC');
			$db->setQuery($query);
			if ($keywords = $db->loadObjectList()) {
				// Get current URL
				$url = $this->getURL();
				$url = str_replace(array(JURI::root(),'&amp;'), array('','&'), $url);
				$url = str_replace('&', '&amp;', $url);
				
				// Get all links from our page
				preg_match_all('#<a\s[^>]*href=(\"??)([^\" >]*?)\\1[^>]*>(.*)<\/a>#siU', $body, $links);
				if (!empty($links)) {
					foreach($links[0] as $i => $link) {
						$body = str_replace($link,'{rsseo '.$i.'}', $body);
					}
				}
				
				foreach ($keywords as $keyword) {
					if (!empty($keyword->link) && ($keyword->link == $url || $keyword->link == JURI::root().$url))
						continue;
					
					
					$lowerK = mb_strtolower($keyword->keyword);
					$lowerB = mb_strtolower($body);
					
					if (strpos($lowerB, $lowerK) !== FALSE) {
						$body = $this->replace($body, $keyword->keyword, $this->_setOptions($keyword->keyword, $keyword->bold, $keyword->underline, $keyword->link, $keyword->attributes), $keyword->limit);
							
						preg_match_all('#<a\s[^>]*href=(\"??)([^\" >]*?)\\1[^>]*>(.*)<\/a>#siU', $body, $links2[$keyword->keyword]);
						if (!empty($links2)) {
							foreach ($links2[$keyword->keyword][0] as $j => $link) {
								$body = str_replace($link,'{rsseo '.md5($keyword->keyword).' '.$j.'}', $body);
							}
						}
					}
				}
				
				foreach ($links[0] as $i => $link)
					$body = str_replace('{rsseo '.$i.'}', $link, $body);
					
				foreach ($keywords as $keyword) {
					if (!empty($links2[$keyword->keyword][0])) {
						foreach ($links2[$keyword->keyword][0] as $i => $link) {
							$body = str_replace('{rsseo '.md5($keyword->keyword).' '.$i.'}', $link, $body);
						}
					}
				}
				
			}
		}
		
		// Add Google tracking code
		if ($config->ga_tracking) {
			$code = $config->ga_code;
			if (!empty($code)) {
				if (strpos($body,$code) === false) {
					$text = '<script type="text/javascript">'."\n";
					$text .= "\t".'var _gaq = _gaq || [];'."\n";
					$text .= "\t".'_gaq.push([\'_setAccount\', \''.$code.'\']);'."\n";
					$text .= "\t".'_gaq.push([\'_trackPageview\']);'."\n";
					$text .= "\t".'(function() {'."\n";
					$text .= "\t\t".'var ga = document.createElement(\'script\'); ga.type = \'text/javascript\'; ga.async = true;'."\n";
					$text .= "\t\t".'ga.src = (\'https:\' == document.location.protocol ? \'https://ssl\' : \'http://www\') + \'.google-analytics.com/ga.js\';'."\n";
					$text .= "\t\t".'var s = document.getElementsByTagName(\'script\')[0]; s.parentNode.insertBefore(ga, s);'."\n";
					$text .= "\t".'})();'."\n";
					$text .= '</script>'."\n";
					$text .= '</head>'."\n";
					
					$change = true;
					$body	= str_replace('</head>', $text, $body);
				}
			}
		}
		
		if ($change)
			JResponse::setBody($body);
	}
	
	
	/**
	 *	Method to get the current URL
	 */
	protected function getURL() {
		return $this->url;
	}
	
	/**
	 *	Method to add a page to database trough the auto-crawler
	 */
	protected function auto() {
		$db		= JFactory::getDbo();
		$doc	= JFactory::getDocument();
		$app	= JFactory::getApplication();
		$query	= $db->getQuery(true);
		
		if (!$this->canRun() || $app->isAdmin()) {
			return false;
		}
		
		$config	= rsseoHelper::getConfig();
		
		if ($config->crawler_enable_auto) {
			$ignored = $config->crawler_ignore;
			$ignored = str_replace("\r",'',$ignored);
			$ignored = explode("\n",$ignored);
			
			// Get current URL
			$url = $this->getURL();
			$url = $this->clean_url($url);
			if (!$url) return;
			
			$url = str_replace(array(JURI::root(),'&amp;'), array('','&'), $url);
			$url = str_replace('&', '&amp;', $url);
			
			$query->clear();
			$query->select($db->qn('id'))->from($db->qn('#__rsseo_pages'))->where($db->qn('url').' = '.$db->q($url));
			$db->setQuery($query);
			$pageID = $db->loadResult();
			
			if (empty($pageID) && !$this->ignore($url,$ignored)) {
				$query->clear();
				$query->insert($db->qn('#__rsseo_pages'))->set($db->qn('url').' = '.$db->q($url))->set($db->qn('title').' = '.$db->q($doc->getTitle()));
				$query->set($db->qn('keywords').' = '.$db->q($doc->getMetaData('keywords')))->set($db->qn('description').' = '.$db->q($doc->getDescription()));
				$query->set($db->qn('sitemap').' = 0')->set($db->qn('crawled').' = 0')->set($db->qn('level').' = 127');
				$query->set($db->qn('date').' = '.$db->q(JFactory::getDate()->toSql()));
				$db->setQuery($query);
				$db->execute();
			}
		}
	}
	
	/**
	 *	Method to set metadata
	 */
	protected function meta() {
		$db		= JFactory::getDbo();
		$doc	= JFactory::getDocument();
		$app	= JFactory::getApplication();
		$query	= $db->getQuery(true);
		
		if (!$this->canRun() || $app->isAdmin() || $doc->getType() != 'html') {
			return false;
		}

		$config	= rsseoHelper::getConfig();
		
		// Get current URL
		$url = $this->getURL();
		$url = str_replace(array(JURI::root(),'&amp;'), array('','&'), $url);
		$url = str_replace('&', '&amp;', $url);
		
		// Get page
		$query->clear();
		$query->select($db->qn('id').', '.$db->qn('title').', '.$db->qn('description').', '.$db->qn('keywords').', '.$db->qn('level').', '.$db->qn('crawled').', '.$db->qn('modified').', '.$db->qn('canonical').', '.$db->qn('robots'));
		$query->from($db->qn('#__rsseo_pages'))->where($db->qn('url').' = '.$db->q($url))->where($db->qn('published').' = 1');
		$db->setQuery($query,0,1);
		$page = $db->loadObject();
		
		// Set the new Title , MetaKeywords , and the Description
		if (!empty($page) && (($page->crawled == 1 || $page->level == 0) || $page->modified == 1 )) {
			if (!($page->level == 0 && $page->title == null)) {
				$page->title		= str_replace('&#039;', "'", $page->title);
				$page->keywords		= str_replace('&#039;', "'", $page->keywords);
				$page->description	= str_replace('&#039;', "'", $page->description);
				
				// Set page title
				$doc->setTitle($page->title);
				
				// Set canonical link
				$canonical = trim($page->canonical);
				if (!empty($canonical))
					$doc->addHeadLink($canonical, 'canonical', 'rel');
				
				// Set Meta Keywords
				$doc->setMetaData('keywords',$page->keywords);
				// Set Meta Description
				$doc->setDescription($page->description);
				// Set Robots
				if (!empty($page->robots)) 
					$this->addRobots($page->robots);						
			}
		}
	}
	
	/**
	 *	Method to add robots
	 */
	protected function addRobots($robots) {
		$doc =& JFactory::getDocument();
		
		$registry = new JRegistry;
		$registry->loadString($robots);
		$robots = $registry->toArray();
		
		if (!empty($robots)) {
			$therobots = array();
			
			foreach($robots as $robot => $value) {
				if ($robot == 'index' && $value == '1')
					$therobots[] = 'index'; 
				elseif ($robot == 'index' && $value == '0')
					$therobots[] = 'noindex';
				
				if ($robot == 'follow' && $value == '1')
					$therobots[] = 'follow'; 
				elseif ($robot == 'follow' && $value == '0')
					$therobots[] = 'nofollow';
				
				if ($robot == 'archive' && $value == '1')
					$therobots[] = 'archive'; 
				elseif ($robot == 'archive' && $value == '0')
					$therobots[] = 'noarchive';
				
				if ($robot == 'snippet' && $value == '1')
					$therobots[] = 'snippet'; 
				elseif ($robot == 'snippet' && $value == '0')
					$therobots[] = 'nosnippet';
				
				if ($robot == 'odp' && $value == '1')
					$therobots[] = 'odp'; 
				elseif ($robot == 'odp' && $value == '0')
					$therobots[] = 'noodp';
			}
			
			if (!empty($therobots)) {
				$therobots = implode(',',$therobots);
				$doc->setMetaData('robots',$therobots);
			}
		}
	}
	
	/**
	 *	Method to ignore a link from beeing added to the pages database
	 */
	protected function ignore($url, $pattern_array) {
		$return = false;
		if (is_array($pattern_array)) {
			foreach ($pattern_array as $pattern) {
				$pattern = str_replace('&', '&amp;', $pattern);
				$pattern = $this->_transform_string($pattern);	
				preg_match_all($pattern, $url, $matches);
		
				if (count($matches[0]) > 0)
					$return = true;
			}
		}
		return $return;
	}

	/**
	 *	Method to create the ignore pattern
	 */
	protected function _transform_string($string) {
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
	 *	Method to add custom attributes to the keyword
	 */
	protected function _setOptions($text, $bold = 0, $underline = 0, $link = '', $attributes) {
		$pattern = '/^(https?|ftp):\/\/(?#)(([a-z0-9$_\.\+!\*\'\(\),;\?&=-]|%[0-9a-f]{2})+(?#)(:([a-z0-9$_\.\+!\*\'\(\),;\?&=-]|%[0-9a-f]{2})+)?(?#)@)?(?#)((([a-z0-9][a-z0-9-]*[a-z0-9]\.)*(?#)[a-z][a-z0-9-]*[a-z0-9](?#)|((\d|[1-9]\d|1\d{2}|2[0-4][0-9]|25[0-5])\.){3}(?#)(\d|[1-9]\d|1\d{2}|2[0-4][0-9]|25[0-5])(?#))(:\d+)?(?#))(((\/+([a-z0-9$_\.\+!\*\'\(\),;:@&=-]|%[0-9a-f]{2})*)*(?#)(\?([a-z0-9$_\.\+!\*\'\(\),;:@&=-]|%[0-9a-f]{2})*)(?#)?)?)?(?#)(#([a-z0-9$_\.\+!\*\'\(\),;:@&=-]|%[0-9a-f]{2})*)?(?#)$/i';
		
		if ($bold == 1) {
			$startB = '<strong>';
			$endB = '</strong>';
		} elseif ($bold == 2) {
			$startB = '<b>';
			$endB = '</b>';
		} elseif ($bold == 0) {
			$startB = '';
			$endB = '';
		}
		
		if ($underline == 1) {
			$startU = '<u>';
			$endU = '</u>';
		} else {
			$startU = '';
			$endU = '';
		}
		
		$valid_url = preg_match($pattern,$link);
		
		if($valid_url) {
			return $startB.$startU.'<a href="'.$link.'" '.trim($attributes).'>'.$text.'</a>'.$endU.$endB;
		} else {
			return $startB.$startU.$text.$endU.$endB;
		}
	}
	
	/**
	 *	Method to replace keywords
	 */
	protected function replace($bodyText, $searchTerm, $replaceWith, $limit) {
		$app = JFactory::getApplication();
		if (!$this->canRun() || $app->isAdmin()) {
			return false;
		}

		$config			= rsseoHelper::getConfig();
		$original		= $replaceWith;
		$newText		= '';
		$i				= -1;
		$lcSearchTerm	= mb_strtolower($searchTerm);
		$lcBodyText		= mb_strtolower($bodyText);
		$chars			= $config->approved_chars;
		$counter		= 0;
		
		while (strlen($bodyText) > 0) {				
			// Get index of search term
			$i = $this->_indexOf($lcBodyText, $lcSearchTerm, $i+1);
			if ($i < 0) {
				$newText .= $bodyText;
				$bodyText = '';
			} else {
				// Skip anything inside an HTML tag
				if (($this->_lastIndexOf($bodyText,">",$i) >= $this->_lastIndexOf($bodyText,"<",$i))) {
					// Skip anything inside a <script> or <style> block
					if (($this->_lastIndexOf($lcBodyText,"/script>",$i) >= $this->_lastIndexOf($lcBodyText,"<script",$i)) && ($this->_lastIndexOf($lcBodyText,"/style>",$i) >= $this->_lastIndexOf($lcBodyText,"<style",$i)) && ($this->_lastIndexOf($lcBodyText,"/button>",$i) >= $this->_lastIndexOf($lcBodyText,"<button",$i)) && ($this->_lastIndexOf($lcBodyText,"/textarea>",$i) >= $this->_lastIndexOf($lcBodyText,"<textarea",$i)) && ($this->_lastIndexOf($lcBodyText,"/select>",$i) >= $this->_lastIndexOf($lcBodyText,"<select",$i)) && ($this->_lastIndexOf($lcBodyText,"/a>",$i) >= $this->_lastIndexOf($lcBodyText,"<a ",$i)) && ($this->_lastIndexOf($lcBodyText,"/title>",$i) >= $this->_lastIndexOf($lcBodyText,"<title",$i)) && ($this->_lastIndexOf($lcBodyText,"/h1>",$i) >= $this->_lastIndexOf($lcBodyText,"<h1",$i)) && ($this->_lastIndexOf($lcBodyText,"/h2>",$i) >= $this->_lastIndexOf($lcBodyText,"<h2",$i)) && ($this->_lastIndexOf($lcBodyText,"/h3>",$i) >= $this->_lastIndexOf($lcBodyText,"<h3",$i)) && ($this->_lastIndexOf($lcBodyText,"/h4>",$i) >= $this->_lastIndexOf($lcBodyText,"<h4",$i)) && ($this->_lastIndexOf($lcBodyText,"/h5>",$i) >= $this->_lastIndexOf($lcBodyText,"<h5",$i)) )
					{
						
						$word		= substr($bodyText, $i - 1, strlen($searchTerm) + 2);
						$firstChar	= substr($word, 0, 1);
						$lastChar	= substr($word, -1);							
						
						if ((strpos($chars,$firstChar) !== FALSE) && (strpos($chars,$lastChar) !== FALSE)) {
							$exact_word = ltrim($word,$firstChar);
							$exact_word = rtrim($exact_word,$lastChar);
							
							$pattern = '#href="(.*?)"#is';
							preg_match($pattern,$replaceWith,$matches);								
							if (!empty($matches) && !empty($matches[1]))
								$replaceWith = str_replace($matches[1], '{rsseo_rskeydel_link}', $replaceWith);
							
							$replaceWith = str_replace(mb_strtolower($exact_word),$exact_word,mb_strtolower($replaceWith));					
							
							if (!empty($matches) && !empty($matches[1]))
								$replaceWith = str_replace('{rsseo_rskeydel_link}', $matches[1], $replaceWith);
							
							if (empty($limit))
								$newText .= substr($bodyText, 0, $i) . $replaceWith;
							else {
								if ($counter < $limit)
									$newText .= substr($bodyText, 0, $i) . $replaceWith;
								else
									$newText .= substr($bodyText, 0, $i) . $searchTerm;
							}
							$bodyText = substr($bodyText, $i+strlen($searchTerm));
							$lcBodyText = mb_strtolower($bodyText);
							$i = -1;
							$counter++;
							$replaceWith = $original;
						}
					}
				}
			}
		}
		return $newText;
	}
	
	/**
	 *	Helper method for replacing keywords
	 */
	protected function _indexOf($text, $search, $i) {
		$return = strpos($text, $search, $i);
		if ($return === false)
			$return = -1;
		
		return $return;
	}
	
	/**
	 *	Helper method for replacing keywords
	 */
	protected function _lastIndexOf($text, $search, $i) {
		$length = strlen($text);
		$i = ($i > 0)?($length - $i):abs($i);
		$pos = strpos(strrev($text), strrev($search), $i);
		return ($pos === false)? -1 : ( $length - $pos - strlen($search) );
	}
	
	/**
	 *	Method to clean the url
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
		
		return $url;
	}
}