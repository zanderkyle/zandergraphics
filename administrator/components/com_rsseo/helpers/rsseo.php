<?php
/**
* @version 1.0.0
* @package RSSEO! 1.0.0
* @copyright (C) 2009-2012 www.rsjoomla.com
* @license GPL, http://www.gnu.org/licenses/gpl-2.0.html
*/

defined('_JEXEC') or die('Restricted access');

// DO NOT CHANGE BELOW
define('RSSEO_PRODUCT','RSSEO!');
define('RSSEO_VERSION','1.0.0');
define('RSSEO_REVISION','16');
define('RSSEO_KEY','SEO56H8K3U');
define('RSSEO_COPYRIGHT','&copy;2009-2012 www.rsjoomla.com');
define('RSSEO_LICENSE','GPL License');
define('RSSEO_AUTHOR','<a href="http://www.rsjoomla.com" target="_blank">www.rsjoomla.com</a>');
// DO NOT CHANGE ABOVE


class rsseoHelper {

	// Check for Joomla! version
	public static function isJ3() {
		return version_compare(JVERSION, '3.0', '>=');
	}
	
	// Get component configuration
	public static function getConfig($name = null) {
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		static $config;
		
		if (empty($config)) {
			$query->clear();
			$query->select($db->qn('params'));
			$query->from('#__extensions');
			$query->where($query->qn('type') . ' = ' . $db->quote('component'));
			$query->where($query->qn('element') . ' = ' . $db->quote('com_rsseo'));
			$db->setQuery($query);
			$params = $db->loadResult();
			
			$registry = new JRegistry;
			$registry->loadString($params);
			$config = $registry->toObject();
		}
		
		if ($name != null) {
			if (isset($config->$name)) return $config->$name;
				else return false;
		}
		else return $config;
	}
	
	public static function genKeyCode() {
		$code = rsseoHelper::getConfig('global_register_code');
		if (empty($code)) 
			return;
		return md5($code.RSSEO_KEY);
	}
	
	
	// Add backend submenus
	public static function addSubmenu($vName) {
		$layout = JFactory::getApplication()->input->getCmd('layout');
		
		JHtmlSidebar::addEntry(JText::_('COM_RSSEO_MENU_DASHBOARD'),		'index.php?option=com_rsseo',						($vName == '' || $vName == 'default') && $layout != 'update');
		JHtmlSidebar::addEntry(JText::_('COM_RSSEO_MENU_SEO_PERFORMANCE'),	'index.php?option=com_rsseo&view=competitors',		$vName == 'competitors');
		JHtmlSidebar::addEntry(JText::_('COM_RSSEO_MENU_PAGES'),			'index.php?option=com_rsseo&view=pages',			$vName == 'pages');
		JHtmlSidebar::addEntry(JText::_('COM_RSSEO_MENU_CRAWLER'),			'index.php?option=com_rsseo&view=crawler',			$vName == 'crawler');
		JHtmlSidebar::addEntry(JText::_('COM_RSSEO_MENU_SITEMAP' ),			'index.php?option=com_rsseo&view=sitemap',			$vName == 'sitemap');
		JHtmlSidebar::addEntry(JText::_('COM_RSSEO_MENU_REDIRECTS'),		'index.php?option=com_rsseo&view=redirects',		$vName == 'redirects');
		JHtmlSidebar::addEntry(JText::_('COM_RSSEO_MENU_KEYWORDS' ),		'index.php?option=com_rsseo&view=keywords',			$vName == 'keywords');
		JHtmlSidebar::addEntry(JText::_('COM_RSSEO_MENU_BACKUP_RESTORE'),	'index.php?option=com_rsseo&view=backup',			$vName == 'backup');
		JHtmlSidebar::addEntry(JText::_('COM_RSSEO_MENU_ANALYTICS'),		'index.php?option=com_rsseo&view=analytics',		$vName == 'analytics');
		JHtmlSidebar::addEntry(JText::_('COM_RSSEO_MENU_UPDATE'),			'index.php?option=com_rsseo&layout=update',			$layout == 'update');
	}
	
	// Set scripts and stylesheets
	public static function setScripts($from) {
		$doc = JFactory::getDocument();
		
		if ($from == 'administrator') {
			$doc->addScript(JURI::root(true).'/administrator/components/com_rsseo/assets/js/rsseo.js?v='.RSSEO_REVISION);
			$doc->addStyleSheet(JURI::root(true).'/administrator/components/com_rsseo/assets/css/style.css?v='.RSSEO_REVISION);
			
			if (rsseoHelper::isJ3()) {
				$doc->addStyleSheet(JURI::root(true).'/administrator/components/com_rsseo/assets/css/j3.css?v='.RSSEO_REVISION);
				JHtml::_('formbehavior.chosen', 'select');
			} else {
				$doc->addStyleSheet(JURI::root(true).'/administrator/components/com_rsseo/assets/css/j2.css?v='.RSSEO_REVISION);
			}
			
		} else {
			
		}
	}
	
	public static function useragents() {
		$useragents = array('Mozilla/5.0 (Windows NT 6.1; rv:13.0) Gecko/20100101 Firefox/13.0.1',
							'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:13.0) Gecko/20100101 Firefox/13.0.1',
							'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/534.57.2 (KHTML, like Gecko) Version/5.1.7 Safari/534.57.2',
							'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/534.57.2 (KHTML, like Gecko) Version/5.1.7 Safari/534.57.2',
							'Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; WOW64; Trident/5.0)',
							'Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; Trident/5.0)'
							);
		
		$count = count($useragents) - 1;
		return $useragents[rand(0,$count)];
	}
	
	public static function fopen($url, $headers = 1, $test = false) {
		$uri		= JURI::getInstance();
		$host		= $uri->getHost();
		$config 	= rsseoHelper::getConfig();
		$useragent	= rsseoHelper::useragents();
		$data		= false;
		$url		= html_entity_decode($url);
		$google		= JFactory::getApplication()->input->getInt('google',0);
		$host		= str_replace('www.','',$host);
		
		$errors = array('cURL' 	=> true, 'fsockopen' 		=> true,
						'fopen' => true, 'file_get_contents' => true
						);
		
		// cURL
		if (extension_loaded('curl')) {
			$ch = @curl_init();
			
			// Set options
			@curl_setopt($ch, CURLOPT_URL, $url);
			@curl_setopt($ch, CURLOPT_HEADER, $headers);
			//@curl_setopt($ch, CURLOPT_FAILONERROR, 1);
			@curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			@curl_setopt($ch, CURLOPT_USERAGENT, $useragent);
			@curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
			@curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			@curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

			if ($config->proxy_enable) {
				@curl_setopt($ch, CURLOPT_PROXY, $config->proxy_server);
				@curl_setopt($ch, CURLOPT_PROXYPORT, $config->proxy_port);
				@curl_setopt($ch, CURLOPT_PROXYUSERPWD, $config->proxy_username.':'.$config->proxy_password); 
			}
			
			// Set timeout
			@curl_setopt($ch, CURLOPT_TIMEOUT, 5);
			
			// Grab data
			$data = @curl_exec($ch);
			
			$headers_sent = false;
			if ($headers) {
				$objs = explode("\n",$data);
				foreach($objs as $obj) {
					if(strpos($obj,'Location:') !== false) {
						$new_url = trim(str_replace('Location: ','',$obj));
						if(strpos($new_url,$host) !== false) {
							$data = rsseoHelper::fopen($new_url,0);
							
							if (!empty($data))
								$headers_sent = true;
						}
					}
				}
			}
			
			$curl_error = curl_error($ch);
			$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			@curl_close($ch);
			
			if ($httpcode > 300 && !$headers_sent && !$test && !$google) 
				return 'RSSEOINVALID';
			
			if(empty($data)) {
				$errors['cURL'] = false;
			}
			
			// Return data
			if ($data !== false && !$test)
				return $data;
		} else {
			$errors['cURL'] = false;
		}

		// file_get_contents
		if(function_exists('file_get_contents') && ini_get('allow_url_fopen')) {
			$url = str_replace('://localhost', '://127.0.0.1', $url);
			@ini_set('user_agent',$useragent);
			$data = @file_get_contents($url);
			
			$response = false;
			if (isset($http_response_header)) {
				if (is_array($http_response_header))
					foreach ($http_response_header as $header) {
						if (substr($header,0,4) == 'HTTP') {
							if (substr_count($header, '200 OK') > 0)
								$response = true;
						}
					}
			}
			
			if (!$response && !$test && !$google)
				return 'RSSEOINVALID';
			
			if(empty($data)) 
				$errors['file_get_contents'] = false;
			
			// Return data
			if ($data !== false && !$test)
				return $data;
		} else {
			$errors['file_get_contents'] = false;
		}
		
	 	// fopen
		if (function_exists('fopen') && ini_get('allow_url_fopen')) {
			if (ini_get('default_socket_timeout') < 5) ini_set('default_socket_timeout', 5);
			@stream_set_blocking($handle, 1);
			@stream_set_timeout($handle, 5);
			@ini_set('user_agent',$useragent);
			
			$url = str_replace('://localhost', '://127.0.0.1', $url);
			if ($handle = @fopen ($url, 'r')) {
				$data = '';
				while (!feof($handle))
					$data .= @fread($handle, 8192);
			
				// Clean up
				@fclose($handle);
			
				if(empty($data))
					$errors['fopen'] = false;
				
				$response1 = false;
				if (isset($http_response_header)) {
					if (is_array($http_response_header))
						foreach ($http_response_header as $header) {
							if (substr($header,0,4) == 'HTTP') {
								if (substr_count($header, '200 OK') > 0)
									$response1 = true;
							}
						}
				}
				
				if (!$response1 && !$test && !$google)
					return 'RSSEOINVALID';
				
				// Return data
				if ($data !== false && !$test)
					return $data;
			} else {
				$response = false;
				if (isset($http_response_header)) {
					if (is_array($http_response_header))
						foreach ($http_response_header as $header) {
							if (substr($header,0,4) == 'HTTP') {
								if (substr_count($header, '200 OK') > 0)
									$response = true;
							}
						}
				}
				
				if (!$response && !$test && !$google)
					return 'RSSEOINVALID';
			}
		} else {
			$errors['fopen'] = false;
		}
		
		// fsockopen
		if (function_exists('fsockopen')) {
			$errno = 0;
			$errstr = '';

			$url_info = parse_url($url);
			if(isset($url_info['host']) && $url_info['host'] == 'localhost') {
				$url_info['host'] = '127.0.0.1';
			}
		
			if ($fsock = @fsockopen($url_info['host'], 80, $errno, $errstr, 5)) {
				@fputs($fsock, 'GET '.$url_info['path'].(!empty($url_info['query']) ? '?'.$url_info['query'] : '').' HTTP/1.1'."\r\n");
				@fputs($fsock, 'HOST: '.$url_info['host']."\r\n");
				@fputs($fsock, "User-Agent: ".$useragent."\r\n");
				@fputs($fsock, 'Connection: close'."\r\n\r\n");
        
				// Set timeout
				@stream_set_blocking($fsock, 1);
				@stream_set_timeout($fsock, 5);
				
				$data		= '';
				$fheaders	= '';
				$response	= @fgets($fsock);
				
				if (substr_count($response, '200 OK') > 0) {
					$passed_header = false;
					while (!@feof($fsock)) {
						if ($passed_header)
							$data .= @fread($fsock, 1024);
						else {
							if (@fgets($fsock,1024) == "\r\n") {
								$passed_header = true;
							}
						}
					}
				} else {
					$fheaders = rsseoHelper::getHeaders($url_info['host'],$url_info['path'].(!empty($url_info['query']) ? '?'.$url_info['query'] : ''));
					
					if ($headers && !empty($fheaders)) {
						$objs = explode("\n",$fheaders);
						foreach($objs as $obj) {
							if(strpos($obj,'Location:') !== false) {
								$new_url = trim(str_replace('Location: ','',$obj));
								if(strpos($new_url,$url_info['host']) !== false){
									$data = rsseoHelper::fopen($new_url,0);
									break;
								}
							}
						}
					}
					
					if (empty($data) && !$test && !$google) {
						return 'RSSEOINVALID';
					}
				}
				
				// Clean up
				@fclose($fsock);
				
				if(empty($data)) {
					$errors['fsockopen'] = false;
				}
				
				// Return data
				if ($data !== false && !$test)
					return $data;
			}
		} else {
			$errors['fsockopen'] = false;
		}
		
		if ($test) {
			return $errors;
		} else {
			return $data;
		}
	}
	
	public static function convertseconds($sec) {
		$text = '';

		$hours = intval(intval($sec) / 3600); 
		$text .= str_pad($hours, 2, "0", STR_PAD_LEFT). ":";

		$minutes = intval(($sec / 60) % 60); 
		$text .= str_pad($minutes, 2, "0", STR_PAD_LEFT). ":";

		$seconds = intval($sec % 60); 
		$text .= str_pad($seconds, 2, "0", STR_PAD_LEFT);

		return $text;
	}
	
	public static function keywords() {
		$db		= JFactory::getDbo();
		$query	= $db->getQuery(true);
		$config = rsseoHelper::getConfig();
		
		if ($config->copykeywords) {
			$query->clear();
			$query->update($db->qn('#__rsseo_pages'))->set($db->qn('keywordsdensity').' = '.$db->qn('keywords'));
			
			if (!$config->overwritekeywords)
				$query->where($db->qn('keywordsdensity').' = '.$db->quote(''));
			
			$db->setQuery($query);
			if ($db->execute()) {
				$component	= JComponentHelper::getComponent('com_rsseo');
				$cparams	= $component->params;
				
				if ($cparams instanceof JRegistry) {
					$cparams->set('copykeywords', 0);
					$cparams->set('overwritekeywords', 0);
					$query->clear();
					$query->update($db->quoteName('#__extensions'));
					$query->set($db->quoteName('params'). ' = '.$db->quote((string) $cparams));
					$query->where($db->quoteName('extension_id'). ' = '. $db->quote($component->id));
					
					$db->setQuery($query);
					$db->execute();
				}
			}
		}
	}
	
	public function getHeaders($host, $doc) {
		$httpheader = '';
		$fp = @fsockopen($host, 80, $errno, $errstr, 30);
		if (!$fp) {
			return false;
		} else {
			@fputs($fp, 'GET '.$doc.' HTTP/1.0'."\r\n".'Host: '.$host."\r\n\r\n");
			
			while(!@feof($fp)) {
				$httpresult = @fgets ($fp,1024);
				$httpheader = $httpheader.$httpresult;
				if ($httpresult == "\r\n")
					break;
			}
			@fclose ($fp);
		}
		return $httpheader;
	}
}