<?php
/**
* @version 1.0.0
* @package RSSEO! 1.0.0
* @copyright (C) 2009-2012 www.rsjoomla.com
* @license GPL, http://www.gnu.org/licenses/gpl-2.0.html
*/

defined('_JEXEC') or die('Restricted access');
jimport('joomla.filesystem.file');

class sitemapHelper {
	
	protected $sitemap;
	protected $ror;
	protected $new;
	protected $protocol;
	protected $modified;
	protected $auto;
	protected $replace = array();
	protected $root;
	
	public function __construct($new, $protocol, $modified, $auto) {
		// The sitemap.xml path
		$this->sitemap = JPATH_SITE.'/sitemap.xml';
		// The ror.xml path
		$this->ror = JPATH_SITE.'/ror.xml';
		// Do we create a new sitemap ?
		$this->new = $new;
		// Set protocol
		$this->protocol = $protocol;
		// Set last modified time
		$this->modified = $modified;
		// Set auto-crawled
		$this->auto = $auto;
		// Set root
		$this->root = JURI::root();
		
		if (substr($this->root,0,8) == 'https://' && $this->protocol == 0) {
			$this->root = str_replace('https://','http://',$this->root);
		}
		
		if (substr($this->root,0,7) == 'http://' && $this->protocol == 1) {
			$this->root = str_replace('http://','https://',$this->root);
		}
		
		$this->update();
		
		$empty = '';
		if (JFile::exists($this->sitemap) && $this->new) {
			$this->write($this->sitemap,$empty,'w');
		}
		
		if (JFile::exists($this->ror) && $this->new) {
			$this->write($this->ror,$empty,'w');
		}
		
		// Reset file 
		$this->reset();
		
		$this->redirects();
	}
	
	public static function getInstance($new, $protocol, $modified, $auto) {
		$modelClass = 'sitemapHelper';
		return new $modelClass($new, $protocol, $modified, $auto);
	}
	
	/**
	 *	Add XML Headers
	 */
	public function setHeader() {
		if ($this->new) {
			if (JFile::exists($this->sitemap)) {
				$header = '<?xml version="1.0" encoding="UTF-8"?>'."\n";
				$header .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'."\n";
				
				$this->write($this->sitemap,$header,'a');
			}
			
			if (JFile::exists($this->ror)) {
				$header = '<?xml version="1.0" encoding="utf-8"?>'."\n";
				$header .= '<rss version="2.0" xmlns:ror="http://rorweb.com/0.1/">'."\n";
				$header .= '<channel>'."\n";
				$header .= "\t".'<title>ROR Sitemap for '.$this->root.'</title>'."\n";
				$header .= "\t".'<description>ROR Sitemap for '.$this->root.'</description>'."\n";
				$header .= "\t".'<link>'.$this->root.'</link>'."\n";
				$header .= "\t".'<item>'."\n";
				$header .= "\t\t".'<title>ROR Sitemap for '.$this->root.'</title>'."\n";
				$header .= "\t\t".'<link>'.$this->root.'</link>'."\n";
				$header .= "\t\t".'<ror:about>sitemap</ror:about>'."\n";
				$header .= "\t\t".'<ror:type>SiteMap</ror:type>'."\n";
				$header .= "\t".'</item>'."\n";
				
				$this->write($this->ror,$header,'a');
			}
		}
	}
	
	protected function update() {
		if ($this->new) {
			$db			= JFactory::getDbo();
			$query		= $db->getQuery(true);
			$component	= JComponentHelper::getComponent('com_rsseo');
			$cparams	= $component->params;
			
			if ($cparams instanceof JRegistry) {
				$cparams->set('sitemapauto', $this->auto);
				$query->clear();
				$query->update($db->quoteName('#__extensions'));
				$query->set($db->quoteName('params'). ' = '.$db->quote((string) $cparams));
				$query->where($db->quoteName('extension_id'). ' = '. $db->quote($component->id));
				
				$db->setQuery($query);
				$db->execute();
			}
		}
	}
	
	public function add($page) {
		if (JFile::exists($this->sitemap)) {
			$this->addSitemap($page);
		}
		
		if (JFile::exists($this->ror)) {
			$this->addRor($page);
		}
	}
	
	public function close() {
		if (JFile::exists($this->sitemap)) {
			$this->closeSitemap();
		}
		
		if (JFile::exists($this->ror)) {
			$this->closeRor();
		}
	}
	
	protected function addSitemap($page) {
		if (!empty($this->replace[$page->url])) {
			$page->url = $this->replace[$page->url];
		}
		
		if (strpos($page->url,$this->root) === false) {
			$href = $this->root.$page->url;
		} else {
			$href = $page->url;
		}
		
		$string = "\t".'<url>'."\n";
		$string .= "\t\t".'<loc>'.$this->xmlentities($href).'</loc>'."\n";
		$string .= "\t\t".'<priority>'.($page->priority ? $page->priority : '0.5').'</priority>'."\n";
		$string .= "\t\t".'<changefreq>'.($page->frequency ? $page->frequency : 'weekly').'</changefreq>'."\n";
		$string .= "\t\t".'<lastmod>'.$this->modified.'</lastmod>'."\n";
		$string .= "\t".'</url>'."\n";
		
		$this->write($this->sitemap, $string, 'a');
	}
	
	protected function addRor($page) {
		if (!empty($this->replace[$page->url])) {
			$page->url = $this->replace[$page->url];
		}
		
		if (strpos($page->url,$this->root) === false) {
			$href = $this->root.$page->url;
		} else {
			$href = $page->url;
		}
		
		$string = "\t".'<item>'."\n";
		$string .= "\t\t".'<link>'.$this->xmlentities($href).'</link>'."\n";
		$string .= "\t\t".'<title>'.$this->xmlentities($page->title).'</title>'."\n";
		$string .= "\t\t".'<ror:updatePeriod>'.($page->frequency ? $page->frequency : 'weekly').'</ror:updatePeriod>'."\n";
		$string .= "\t\t".'<ror:sortOrder>'.$page->level.'</ror:sortOrder>'."\n";
		$string .= "\t\t".'<ror:resourceOf>sitemap</ror:resourceOf>'."\n";
		$string .= "\t".'</item>'."\n";
		
		$this->write($this->ror, $string, 'a');
	}
	
	protected function closeSitemap() {
		$string = '</urlset>';
		$this->write($this->sitemap, $string, 'a');
	}
	
	protected function closeRor() {
		$string = '</channel>'."\n";
		$string .= '</rss>';
		
		$this->write($this->ror, $string, 'a');
	}
	
	protected function redirects() {
		$db		= JFactory::getDbo();
		$query	= $db->getQuery(true);
		
		$query->select($db->quoteName('from').', '.$db->quoteName('to'))->from($db->quoteName('#__rsseo_redirects'))->where($db->quoteName('published').' = '.$db->quote(1));
		$db->setQuery($query);
		if ($redirects = $db->loadObjectList()) {
			foreach ($redirects as $redirect) {
				$redirect->from = htmlentities($redirect->from);
				$redirect->to = htmlentities($redirect->to);
				$this->replace[$redirect->from] = $redirect->to;
			}
		}
	}
	
	protected function reset() {
		if ($this->new) {
			$db		= JFactory::getDbo();
			$query	= $db->getQuery(true);
			
			$query->update($db->quoteName('#__rsseo_pages'))->set($db->quoteName('sitemap').' = 0');
			$db->setQuery($query);
			$db->execute();
		}
	}
	
	protected function xmlentities($string) {		
		//prepare string 
		$string = str_replace('&amp;','&',$string);
		$string = str_replace('&','&#38;',$string);
		
		return $string;
	}
	
	protected function write($filename, $string, $write_type) {
		if (is_writable($filename)) {
			if (!$handle = fopen($filename, $write_type)) {
				echo "Cannot open file ($filename)";
				exit;
			}
			// Write $somecontent to our opened file.
			if (fwrite($handle, $string) === FALSE) {
				echo "Cannot write to file ($filename)";
				exit;
			}
			fclose($handle);
		} else {
			echo "The file $filename is not writable";
		}
	}
}