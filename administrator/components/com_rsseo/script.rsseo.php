<?php
/**
* @version 1.0.0
* @package RSSeo! 1.0.0
* @copyright (C) 2009-2012 www.rsjoomla.com
* @license GPL, http://www.gnu.org/copyleft/gpl.html
*/

defined( '_JEXEC' ) or die( 'Restricted access' );

class com_rsseoInstallerScript 
{
	public function install($parent) {}

	public function postflight($type, $parent) {
		$db		= JFactory::getDBO();
		$query	= $db->getQuery(true);
		
		$query->clear();
		$query->select($db->qn('extension_id'))->from($db->qn('#__extensions'))->where($db->qn('element').' = '.$db->quote('com_rsseo'))->where($db->qn('type').' = '.$db->quote('component'));
		$db->setQuery($query);
		$extension_id = $db->loadResult();
		
		if ($type == 'install') {
			// Add default configuration when installing the first time RSSeo!
			if ($extension_id) {
				$default = '{"global_register_code":"","global_dateformat":"d M y H:i","google_domain":"google.com","enable_pr":"1","enable_googlep":"1","enable_googleb":"1","enable_bingp":"1","enable_bingb":"1","enable_alexa":"1","enable_tehnorati":"0","enable_dmoz":"0","analytics_enable":"0","analytics_username":"","analytics_password":"","ga_tracking":"0","ga_code":"","crawler_enable_auto":"1","crawler_level":"2","site_name_in_title":"0","site_name_separator":"|","crawler_sef":"1","crawler_title_duplicate":"1","crawler_title_length":"1","crawler_description_duplicate":"1","crawler_description_length":"1","crawler_keywords":"1","crawler_headings":"1","crawler_images":"1","crawler_images_alt":"1","crawler_images_hw":"1","crawler_intext_links":"1","crawler_ignore":"{*}tmpl=component{*}\\r\\n{*}format=pdf{*}\\r\\n{*}format=feed{*}\\r\\n{*}output=pdf{*}\\r\\n{*}?gclid={*}","enable_keyword_replace":"1","approved_chars":",;:.?!$%*&()[]{} ","subdomains":"","proxy_enable":"0","proxy_server":"","proxy_port":"","proxy_username":"","proxy_password":"","keyword_density_enable":"1","copykeywords":"0","overwritekeywords":"0","sitemapauto":"0","ga_account":"","ga_start":"","ga_end":"","ga_token":"","sitemap_menus":"","sitemap_excludes":""}';
			
				$query->clear();
				$query->update($db->qn('#__extensions'))->set($db->qn('params').' = '.$db->quote($default))->where($db->qn('extension_id').' = '.(int) $extension_id);
				$db->setQuery($query);
				$db->execute();
			}
		}
		
		if ($type == 'update') {
			// We only need to run this update query on Joomla! 2.5
			if (!version_compare(JVERSION, '3.0', '>=')) {
				
				// ======================================
				// =========== START OLD DATA ===========
				// ======================================
				
				$db->setQuery("ALTER TABLE #__rsseo_keywords CHANGE KeywordLink KeywordLink TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL");
				$db->execute();
				
				$db->setQuery("SHOW COLUMNS FROM #__rsseo_pages WHERE Field = ".$db->q('PageModified')."");
				if (!$db->loadResult()) {
					$db->setQuery("ALTER TABLE #__rsseo_pages ADD PageModified INT( 2 ) NOT NULL");
					$db->execute();
				}

				$db->setQuery("SHOW COLUMNS FROM #__rsseo_pages WHERE Field = ".$db->q('PageKeywordsDensity')."");
				if (!$db->loadResult())
				{
					$db->setQuery("ALTER TABLE #__rsseo_pages ADD PageKeywordsDensity TEXT NOT NULL AFTER PageKeywords");
					$db->execute();
				}

				$db->setQuery("SHOW COLUMNS FROM #__rsseo_pages WHERE Field = ".$db->q('PageInSitemap')."");
				if (!$db->loadResult()) {
					$db->setQuery("ALTER TABLE #__rsseo_pages ADD PageInSitemap INT ( 2 ) NOT NULL AFTER PageSitemap");
					$db->execute();
					$db->setQuery("UPDATE #__rsseo_pages SET PageInSitemap = 1 ");
					$db->execute();
				}

				$db->setQuery("SHOW COLUMNS FROM #__rsseo_pages WHERE Field = ".$db->q('densityparams')."");
				if (!$db->loadResult()) {
					$db->setQuery("ALTER TABLE #__rsseo_pages ADD densityparams TEXT NOT NULL AFTER params");
					$db->execute();
				}

				$db->setQuery("SHOW COLUMNS FROM #__rsseo_pages WHERE Field = ".$db->q('canonical')."");
				if (!$db->loadResult()) {
					$db->setQuery("ALTER TABLE #__rsseo_pages ADD canonical VARCHAR (500) NOT NULL AFTER densityparams");
					$db->execute();
				}

				$db->setQuery("SHOW COLUMNS FROM #__rsseo_pages WHERE Field = ".$db->q('robots')."");
				if (!$db->loadResult()) {
					$db->setQuery("ALTER TABLE #__rsseo_pages ADD robots VARCHAR (255) NOT NULL AFTER canonical");
					$db->execute();
				}

				$db->setQuery("SHOW COLUMNS FROM #__rsseo_pages WHERE Field = ".$db->q('frequency')."");
				if (!$db->loadResult()) {
					$db->setQuery("ALTER TABLE #__rsseo_pages ADD frequency VARCHAR (255) NOT NULL AFTER robots");
					$db->execute();
				}

				$db->setQuery("SHOW COLUMNS FROM #__rsseo_pages WHERE Field = ".$db->q('priority')."");
				if (!$db->loadResult()) {
					$db->setQuery("ALTER TABLE #__rsseo_pages ADD ".$db->qn('priority')." VARCHAR (255) NOT NULL AFTER frequency");
					$db->execute();
				}

				$db->setQuery("SHOW COLUMNS FROM #__rsseo_competitors WHERE Field = ".$db->q('LastTehnoratiRank')."");
				if (!$db->loadResult()) {
					$db->setQuery("ALTER TABLE #__rsseo_competitors ADD LastTehnoratiRank INT( 11 ) NOT NULL DEFAULT '-1'");
					$db->execute();
				}

				$db->setQuery("SHOW COLUMNS FROM #__rsseo_competitors WHERE Field = ".$db->q('Dmoz')."");
				if (!$db->loadResult()) {
					$db->setQuery("ALTER TABLE #__rsseo_competitors ADD Dmoz INT( 1 ) NOT NULL DEFAULT '-1'");
					$db->execute();
				}

				$db->setQuery("SHOW COLUMNS FROM #__rsseo_keywords WHERE Field = ".$db->q('KeywordAttributes')."");
				if (!$db->loadResult()) {
					$db->setQuery("ALTER TABLE #__rsseo_keywords ADD KeywordAttributes TEXT NOT NULL");
					$db->execute();
				}

				$db->setQuery("SHOW COLUMNS FROM #__rsseo_keywords WHERE Field = ".$db->q('KeywordLimit')."");
				if (!$db->loadResult()) {
					$db->setQuery("ALTER TABLE #__rsseo_keywords ADD KeywordLimit INT( 3 ) NOT NULL");
					$db->execute();
				}
				
				// ======================================
				// ============ END OLD DATA ============
				// ======================================
				
				// ========= COMPETITORS TABLE =========
				
				$db->setQuery("SHOW COLUMNS FROM #__rsseo_competitors WHERE Field = ".$db->q('ordering')."");
				if ($db->loadResult()) {
					$db->setQuery("ALTER TABLE #__rsseo_competitors DROP ".$db->qn('ordering')."");
					$db->execute();
				}
				
				$db->setQuery("SHOW COLUMNS FROM #__rsseo_competitors WHERE Field = ".$db->q('LastYahooPages')."");
				if ($db->loadResult()) {
					$db->setQuery("ALTER TABLE #__rsseo_competitors DROP ".$db->qn('LastYahooPages')."");
					$db->execute();
				}
				
				$db->setQuery("SHOW COLUMNS FROM #__rsseo_competitors WHERE Field = ".$db->q('LastYahooBacklinks')."");
				if ($db->loadResult()) {
					$db->setQuery("ALTER TABLE #__rsseo_competitors DROP ".$db->qn('LastYahooBacklinks')."");
					$db->execute();
				}
				
				$db->setQuery("SHOW COLUMNS FROM #__rsseo_competitors WHERE Field = ".$db->q('LastDateRefreshed')."");
				if ($dateref = $db->loadObject()) {				
					if ($dateref->Type == 'int(11)') {
						$db->setQuery("ALTER TABLE #__rsseo_competitors CHANGE `LastDateRefreshed` `LastDateRefreshed` VARCHAR(255) NOT NULL");
						$db->execute();
						$db->setQuery("UPDATE #__rsseo_competitors SET `LastDateRefreshed` = FROM_UNIXTIME(`LastDateRefreshed`)");
						$db->execute();
						$db->setQuery("UPDATE #__rsseo_competitors SET `LastDateRefreshed` = '0000-00-00 00:00:00' WHERE `LastDateRefreshed` = '1970-01-01 02:00:00'");
						$db->execute();					
						$db->setQuery("ALTER TABLE #__rsseo_competitors CHANGE LastDateRefreshed ".$db->qn('date')." DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00'");
						$db->execute();
					}
				}
				
				$db->setQuery("SHOW COLUMNS FROM #__rsseo_competitors WHERE Field = ".$db->q('IdCompetitor')."");
				if ($db->loadResult()) {
					$db->setQuery("ALTER TABLE #__rsseo_competitors CHANGE IdCompetitor id INT( 11 ) NOT NULL AUTO_INCREMENT");
					$db->execute();
				}
				
				$db->setQuery("SHOW COLUMNS FROM #__rsseo_competitors WHERE Field = ".$db->q('Competitor')."");
				if ($db->loadResult()) {
					$db->setQuery("ALTER TABLE #__rsseo_competitors CHANGE Competitor name VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL");
					$db->execute();
				}
				
				$db->setQuery("SHOW COLUMNS FROM #__rsseo_competitors WHERE Field = ".$db->q('parent_id')."");
				if (!$db->loadResult()) {
					$db->setQuery("ALTER TABLE #__rsseo_competitors ADD ".$db->qn('parent_id')." INT( 11 ) NOT NULL AFTER `name`");
					$db->execute();
				}
				
				$db->setQuery("SHOW COLUMNS FROM #__rsseo_competitors WHERE Field = ".$db->q('LastPageRank')."");
				if ($db->loadResult()) {
					$db->setQuery("ALTER TABLE #__rsseo_competitors CHANGE LastPageRank pagerank INT( 11 ) NOT NULL DEFAULT '-1'");
					$db->execute();
				}
					
				$db->setQuery("SHOW COLUMNS FROM #__rsseo_competitors WHERE Field = ".$db->q('LastAlexaRank')."");
				if ($db->loadResult()) {
					$db->setQuery("ALTER TABLE #__rsseo_competitors CHANGE LastAlexaRank alexa INT( 11 ) NOT NULL DEFAULT '-1'");
					$db->execute();
				}
				
				$db->setQuery("SHOW COLUMNS FROM #__rsseo_competitors WHERE Field = ".$db->q('LastTehnoratiRank')."");
				if ($db->loadResult()) {
					$db->setQuery("ALTER TABLE #__rsseo_competitors CHANGE LastTehnoratiRank technorati INT( 11 ) NOT NULL DEFAULT '-1'");
					$db->execute();
				}
				
				$db->setQuery("SHOW COLUMNS FROM #__rsseo_competitors WHERE Field = ".$db->q('LastGooglePages')."");
				if ($db->loadResult()) {
					$db->setQuery("ALTER TABLE #__rsseo_competitors CHANGE LastGooglePages googlep INT( 11 ) NOT NULL DEFAULT '-1'");
					$db->execute();
				}
				
				$db->setQuery("SHOW COLUMNS FROM #__rsseo_competitors WHERE Field = ".$db->q('LastBingPages')."");
				if ($db->loadResult()) {
					$db->setQuery("ALTER TABLE #__rsseo_competitors CHANGE LastBingPages bingp INT( 11 ) NOT NULL DEFAULT '-1'");
					$db->execute();
				}
				
				$db->setQuery("SHOW COLUMNS FROM #__rsseo_competitors WHERE Field = ".$db->q('LastGoogleBacklinks')."");
				if ($db->loadResult()) {
					$db->setQuery("ALTER TABLE #__rsseo_competitors CHANGE LastGoogleBacklinks googleb INT( 11 ) NOT NULL DEFAULT '-1'");
					$db->execute();
				}
				
				$db->setQuery("SHOW COLUMNS FROM #__rsseo_competitors WHERE Field = ".$db->q('LastBingBacklinks')."");
				if ($db->loadResult()) {
					$db->setQuery("ALTER TABLE #__rsseo_competitors CHANGE LastBingBacklinks bingb INT( 11 ) NOT NULL DEFAULT '-1'");
					$db->execute();
				}
				
				$db->setQuery("SHOW COLUMNS FROM #__rsseo_competitors WHERE Field = ".$db->q('Dmoz')."");
				if ($db->loadResult()) {
					$db->setQuery("ALTER TABLE #__rsseo_competitors CHANGE Dmoz dmoz INT( 1 ) NOT NULL DEFAULT '-1'");
					$db->execute();
				}
				
				$db->setQuery("SHOW COLUMNS FROM #__rsseo_competitors WHERE Field = ".$db->q('Tags')."");
				if ($db->loadResult()) {
					$db->setQuery("ALTER TABLE #__rsseo_competitors CHANGE Tags tags TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL");
					$db->execute();
				}
				
				$db->setQuery("SHOW INDEX FROM #__rsseo_competitors WHERE Key_name = 'Competitor'");
				if ($db->loadResult()) {
					$db->setQuery("ALTER TABLE #__rsseo_competitors DROP INDEX Competitor");
					$db->execute();
				}
				
				
				// ========= REDIRECTS TABLE =========
				
				$db->setQuery("SHOW COLUMNS FROM #__rsseo_redirects WHERE Field = ".$db->q('IdRedirect')."");
				if ($db->loadResult()) {
					$db->setQuery("ALTER TABLE #__rsseo_redirects CHANGE IdRedirect id INT( 11 ) NOT NULL AUTO_INCREMENT");
					$db->execute();
				}
				
				$db->setQuery("SHOW COLUMNS FROM #__rsseo_redirects WHERE Field = ".$db->q('RedirectFrom')."");
				if ($db->loadResult()) {
					$db->setQuery("ALTER TABLE #__rsseo_redirects CHANGE RedirectFrom ".$db->qn('from')." VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL");
					$db->execute();
				}
				
				$db->setQuery("SHOW COLUMNS FROM #__rsseo_redirects WHERE Field = ".$db->q('RedirectTo')."");
				if ($db->loadResult()) {
					$db->setQuery("ALTER TABLE #__rsseo_redirects CHANGE RedirectTo ".$db->qn('to')." VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL");
					$db->execute();
				}
				
				$db->setQuery("SHOW COLUMNS FROM #__rsseo_redirects WHERE Field = ".$db->q('RedirectType')."");
				if ($db->loadResult()) {
					$db->setQuery("ALTER TABLE #__rsseo_redirects CHANGE RedirectType ".$db->qn('type')." ENUM( '301', '302' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL");
					$db->execute();
				}
				
				// ========= KEYWORDS TABLE =========
				
				$db->setQuery("SHOW COLUMNS FROM #__rsseo_keywords WHERE Field = ".$db->q('IdKeyword')."");
				if ($db->loadResult()) {
					$db->setQuery("ALTER TABLE #__rsseo_keywords CHANGE IdKeyword id INT( 11 ) NOT NULL AUTO_INCREMENT");
					$db->execute();
				}
				
				$db->setQuery("SHOW COLUMNS FROM #__rsseo_keywords WHERE Field = ".$db->q('Keyword')."");
				if ($db->loadResult()) {
					$db->setQuery("ALTER TABLE #__rsseo_keywords CHANGE Keyword ".$db->qn('keyword')." VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL");
					$db->execute();
				}
				
				$db->setQuery("SHOW COLUMNS FROM #__rsseo_keywords WHERE Field = ".$db->q('KeywordImportance')."");
				if ($db->loadResult()) {
					$db->setQuery("ALTER TABLE #__rsseo_keywords CHANGE KeywordImportance ".$db->qn('importance')." ENUM( 'low', 'relevant', 'important', 'critical' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL");
					$db->execute();
				}
				
				$db->setQuery("SHOW COLUMNS FROM #__rsseo_keywords WHERE Field = ".$db->q('ActualKeywordPosition')."");
				if ($db->loadResult()) {
					$db->setQuery("ALTER TABLE #__rsseo_keywords CHANGE ActualKeywordPosition ".$db->qn('position')." INT( 11 ) NOT NULL");
					$db->execute();
				}
				
				$db->setQuery("SHOW COLUMNS FROM #__rsseo_keywords WHERE Field = ".$db->q('LastKeywordPosition')."");
				if ($db->loadResult()) {
					$db->setQuery("ALTER TABLE #__rsseo_keywords CHANGE LastKeywordPosition ".$db->qn('lastposition')." INT( 11 ) NOT NULL");
					$db->execute();
				}
				
				
				$db->setQuery("SHOW COLUMNS FROM #__rsseo_keywords WHERE Field = ".$db->q('DateRefreshed')."");
				if ($daterefkey = $db->loadObject()) {
					if ($daterefkey->Type == 'int(11)') {
						$db->setQuery("ALTER TABLE #__rsseo_keywords CHANGE `DateRefreshed` `DateRefreshed` VARCHAR(255) NOT NULL");
						$db->execute();
						$db->setQuery("UPDATE #__rsseo_keywords SET `DateRefreshed` = FROM_UNIXTIME(`DateRefreshed`)");
						$db->execute();
						$db->setQuery("UPDATE #__rsseo_keywords SET `DateRefreshed` = '0000-00-00 00:00:00' WHERE `DateRefreshed` = '1970-01-01 02:00:00'");
						$db->execute();					
						$db->setQuery("ALTER TABLE #__rsseo_keywords CHANGE DateRefreshed ".$db->qn('date')." DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00'");
						$db->execute();
					}
				}
				
				$db->setQuery("SHOW COLUMNS FROM #__rsseo_keywords WHERE Field = ".$db->q('KeywordBold')."");
				if ($db->loadResult()) {
					$db->setQuery("ALTER TABLE #__rsseo_keywords CHANGE KeywordBold ".$db->qn('bold')." INT( 2 ) NOT NULL");
					$db->execute();
				}
				
				$db->setQuery("SHOW COLUMNS FROM #__rsseo_keywords WHERE Field = ".$db->q('KeywordUnderline')."");
				if ($db->loadResult()) {
					$db->setQuery("ALTER TABLE #__rsseo_keywords CHANGE KeywordUnderline ".$db->qn('underline')." INT( 2 ) NOT NULL");
					$db->execute();
				}
				
				$db->setQuery("SHOW COLUMNS FROM #__rsseo_keywords WHERE Field = ".$db->q('KeywordLimit')."");
				if ($db->loadResult()) {
					$db->setQuery("ALTER TABLE #__rsseo_keywords CHANGE KeywordLimit ".$db->qn('limit')." INT( 3 ) NOT NULL");
					$db->execute();
				}
				
				$db->setQuery("SHOW COLUMNS FROM #__rsseo_keywords WHERE Field = ".$db->q('KeywordAttributes')."");
				if ($db->loadResult()) {
					$db->setQuery("ALTER TABLE #__rsseo_keywords CHANGE KeywordAttributes ".$db->qn('attributes')." TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL");
					$db->execute();
				}
				
				$db->setQuery("SHOW COLUMNS FROM #__rsseo_keywords WHERE Field = ".$db->q('KeywordLink')."");
				if ($db->loadResult()) {
					$db->setQuery("ALTER TABLE #__rsseo_keywords CHANGE KeywordLink ".$db->qn('link')." TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL");
					$db->execute();
				}
				
				// ========= PAGES TABLE =========
				
				$db->setQuery("SHOW COLUMNS FROM #__rsseo_pages WHERE Field = ".$db->q('IdPage')."");
				if ($db->loadResult()) {
					$db->setQuery("ALTER TABLE #__rsseo_pages CHANGE IdPage ".$db->qn('id')." INT( 11 ) NOT NULL AUTO_INCREMENT");
					$db->execute();
				}
				
				$db->setQuery("SHOW COLUMNS FROM #__rsseo_pages WHERE Field = ".$db->q('PageURL')."");
				if ($db->loadResult()) {
					$db->setQuery("ALTER TABLE #__rsseo_pages CHANGE PageURL ".$db->qn('url')." VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL");
					$db->execute();
				}
				
				$db->setQuery("SHOW COLUMNS FROM #__rsseo_pages WHERE Field = ".$db->q('PageTitle')."");
				if ($db->loadResult()) {
					$db->setQuery("ALTER TABLE #__rsseo_pages CHANGE PageTitle ".$db->qn('title')." TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL");
					$db->execute();
				}
				
				$db->setQuery("SHOW COLUMNS FROM #__rsseo_pages WHERE Field = ".$db->q('PageKeywords')."");
				if ($db->loadResult()) {
					$db->setQuery("ALTER TABLE #__rsseo_pages CHANGE PageKeywords ".$db->qn('keywords')." TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL");
					$db->execute();
				}
				
				$db->setQuery("SHOW COLUMNS FROM #__rsseo_pages WHERE Field = ".$db->q('PageKeywordsDensity')."");
				if ($db->loadResult()) {
					$db->setQuery("ALTER TABLE #__rsseo_pages CHANGE PageKeywordsDensity keywordsdensity TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL");
					$db->execute();
				}
				
				$db->setQuery("SHOW COLUMNS FROM #__rsseo_pages WHERE Field = ".$db->q('PageDescription')."");
				if ($db->loadResult()) {
					$db->setQuery("ALTER TABLE #__rsseo_pages CHANGE PageDescription ".$db->qn('description')." TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL");
					$db->execute();
				}
				
				$db->setQuery("SHOW COLUMNS FROM #__rsseo_pages WHERE Field = ".$db->q('PageSitemap')."");
				if ($db->loadResult()) {
					$db->setQuery("ALTER TABLE #__rsseo_pages CHANGE PageSitemap ".$db->qn('sitemap')." TINYINT( 1 ) NOT NULL");
					$db->execute();
				}
				
				$db->setQuery("SHOW COLUMNS FROM #__rsseo_pages WHERE Field = ".$db->q('PageInSitemap')."");
				if ($db->loadResult()) {
					$db->setQuery("ALTER TABLE #__rsseo_pages CHANGE PageInSitemap insitemap INT( 2 ) NOT NULL");
					$db->execute();
				}
				
				$db->setQuery("SHOW COLUMNS FROM #__rsseo_pages WHERE Field = ".$db->q('PageCrawled')."");
				if ($db->loadResult()) {
					$db->setQuery("ALTER TABLE #__rsseo_pages CHANGE PageCrawled ".$db->qn('crawled')." TINYINT( 1 ) NOT NULL");
					$db->execute();
				}
				
				$db->setQuery("SHOW COLUMNS FROM #__rsseo_pages WHERE Field = ".$db->q('DatePageCrawled')."");
				if ($daterefpag = $db->loadObject()) {
					if ($daterefpag->Type == 'int(11)') {
						$db->setQuery("ALTER TABLE #__rsseo_pages CHANGE `DatePageCrawled` `DatePageCrawled` VARCHAR(255) NOT NULL");
						$db->execute();
						$db->setQuery("UPDATE #__rsseo_pages SET `DatePageCrawled` = FROM_UNIXTIME(`DatePageCrawled`)");
						$db->execute();
						$db->setQuery("UPDATE #__rsseo_pages SET `DatePageCrawled` = '0000-00-00 00:00:00' WHERE `DatePageCrawled` = '1970-01-01 02:00:00'");
						$db->execute();					
						$db->setQuery("ALTER TABLE #__rsseo_pages CHANGE DatePageCrawled ".$db->qn('date')." DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00'");
						$db->execute();
					}
				}
				
				$db->setQuery("SHOW COLUMNS FROM #__rsseo_pages WHERE Field = ".$db->q('PageModified')."");
				if ($db->loadResult()) {
					$db->setQuery("ALTER TABLE #__rsseo_pages CHANGE PageModified ".$db->qn('modified')." INT( 3 ) NOT NULL");
					$db->execute();
				}
				
				$db->setQuery("SHOW COLUMNS FROM #__rsseo_pages WHERE Field = ".$db->q('PageLevel')."");
				if ($db->loadResult()) {
					$db->setQuery("ALTER TABLE #__rsseo_pages CHANGE PageLevel ".$db->qn('level')." TINYINT( 4 ) NOT NULL");
					$db->execute();
				}
				
				$db->setQuery("SHOW COLUMNS FROM #__rsseo_pages WHERE Field = ".$db->q('PageGrade')."");
				if ($db->loadResult()) {
					$db->setQuery("ALTER TABLE #__rsseo_pages CHANGE PageGrade ".$db->qn('grade')." FLOAT( 10, 2 ) NOT NULL");
					$db->execute();
				}
				
				$db->setQuery("SHOW COLUMNS FROM #__rsseo_pages WHERE Field = ".$db->q('imagesnoalt')."");
				if (!$db->loadResult()) {
					$db->setQuery("ALTER TABLE #__rsseo_pages ADD imagesnoalt TEXT NOT NULL AFTER priority");
					$db->execute();
				}
				
				$db->setQuery("SHOW COLUMNS FROM #__rsseo_pages WHERE Field = ".$db->q('imagesnowh')."");
				if (!$db->loadResult()) {
					$db->setQuery("ALTER TABLE #__rsseo_pages ADD imagesnowh TEXT NOT NULL AFTER imagesnoalt");
					$db->execute();
				}
				
				// ========= COMPETITORS HISTORY TABLE =========
				$db->setQuery("SHOW TABLES FROM ".$db->qn(JFactory::getConfig()->get('db'))." LIKE ".$db->q('%'.JFactory::getConfig()->get('dbprefix').'rsseo_competitors_history%')."");
				if ($db->loadResult()) {
					$db->setQuery("SELECT * FROM #__rsseo_competitors_history");
					if ($history = $db->loadObjectList()) {
						foreach ($history as $item) {
							$db->setQuery("INSERT INTO #__rsseo_competitors SET `parent_id` = ".$db->q($item->IdCompetitor).", pagerank = ".$db->q($item->PageRank).", alexa = ".$db->q($item->AlexaRank).", technorati = ".$db->q($item->TehnoratiRank).", googlep = ".$db->q($item->GooglePages).", bingp = ".$db->q($item->BingPages).", googleb = ".$db->q($item->GoogleBacklinks).", bingb = ".$db->q($item->BingBacklinks).", date = FROM_UNIXTIME(".$db->q($item->DateRefreshed).") ");
							$db->execute();
						}
					}
					
					$db->setQuery("DROP TABLE #__rsseo_competitors_history");
					$db->execute();
				}
				
				// ========= CONFIGURATION TABLE =========
				$db->setQuery("SHOW TABLES FROM ".$db->qn(JFactory::getConfig()->get('db'))." LIKE ".$db->q('%'.JFactory::getConfig()->get('dbprefix').'rsseo_config%')."");
				if ($db->loadResult()) {
					$db->setQuery("SELECT ConfigName, ConfigValue FROM #__rsseo_config");
					if ($configuration = $db->loadObjectList()) {
						$config = array();
						foreach ($configuration as $conf) {
							if ($conf->ConfigName == 'enable.debug' || $conf->ConfigName == 'enable.yahoop' || $conf->ConfigName == 'enable.yahoob' || $conf->ConfigName == 'component.heading' || $conf->ConfigName == 'content.heading' || $conf->ConfigName == 'php.folder' || $conf->ConfigName == 'enable.php')
								continue;
							
							if ($conf->ConfigName == 'sitemap_no_autolinks') $conf->ConfigName = 'sitemapauto';
							if ($conf->ConfigName == 'search.dmoz') $conf->ConfigName = 'enable_dmoz';
							$conf->ConfigName = str_replace('.','_',$conf->ConfigName);
							
							$config[$conf->ConfigName] = $conf->ConfigValue;
						}
						$config['copykeywords'] = 0;
						$config['overwritekeywords'] = 0;
						
						
						$reg = new JRegistry();
						$reg->loadArray($config);
						$confdata = $reg->toString();
						
						$query->clear();
						$query->update('`#__extensions`')->set('`params` = '.$db->quote($confdata))->where('`extension_id` = '.(int) $extension_id);
						$db->setQuery($query);
						$db->execute();
					}
					
					$db->setQuery("DROP TABLE #__rsseo_config");
					$db->execute();
				}
			}
		}
		
		if ($type == 'update' || $type == 'install') {
			// Get a new installer
			$installer = new JInstaller();
			
			// Install the system plugin
			$installer->install($parent->getParent()->getPath('source').'/extra/plugins/rsseo');
			
			$query->clear();
			$query->update('`#__extensions`')->set('`enabled` = 1')->where('`element` = '.$db->quote('rsseo'))->where('`type` = '.$db->quote('plugin'))->where('`folder` = '.$db->quote('system'));
			$db->setQuery($query);
			$db->execute();
			
			
			$sqlfile = JPATH_ADMINISTRATOR.'/components/com_rsseo/install.mysql.utf8.sql';
			$buffer = file_get_contents($sqlfile);
			if ($buffer === false) {
				JError::raiseWarning(1, JText::_('JLIB_INSTALLER_ERROR_SQL_READBUFFER'));
				return false;
			}
			
			jimport('joomla.installer.helper');
			$queries = JInstallerHelper::splitSql($buffer);
			if (count($queries) == 0) {
				// No queries to process
				return 0;
			}
			
			// Process each query in the $queries array (split out of sql file).
			foreach ($queries as $query)
			{
				$query = trim($query);
				if ($query != '' && $query{0} != '#') {
					$db->setQuery($query);
					if (!$db->execute()) {
						JError::raiseWarning(1, JText::sprintf('JLIB_INSTALLER_ERROR_SQL_ERROR', $db->stderr(true)));
						return false;
					}
				}
			}
		}
		
		$this->showInstall();
	}
	
	public function uninstall($parent)  {
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$installer	= new JInstaller();

		// Remove the xmlrpc plugin
		$query->clear();
		$query->select('`extension_id`')->from('`#__extensions`')->where('`element` = '.$db->quote('rsseo'))->where('`type` = '.$db->quote('plugin'))->where('`folder` = '.$db->quote('system'));
		$db->setQuery($query,0,1);
		$plugin = $db->loadResult();
		if ($plugin) $installer->uninstall('plugin', $plugin);
		
		$this->showUninstall();
	}
	
	protected function showInstall() {
?>
<style type="text/css">
.version-history {
	margin: 0 0 2em 0;
	padding: 0;
	list-style-type: none;
}
.version-history > li {
	margin: 0 0 0.5em 0;
	padding: 0 0 0 4em;
}
.version-new,
.version-fixed,
.version-upgraded {
	float: left;
	font-size: 0.8em;
	margin-left: -4.9em;
	width: 4.5em;
	color: white;
	text-align: center;
	font-weight: bold;
	text-transform: uppercase;
	-webkit-border-radius: 4px;
	-moz-border-radius: 4px;
	border-radius: 4px;
}

.version-new {
	background: #7dc35b;
}
.version-fixed {
	background: #e9a130;
}
.version-upgraded {
	background: #61b3de;
}

.install-ok {
	background: #7dc35b;
	color: #fff;
	padding: 3px;
}

.install-not-ok {
	background: #E9452F;
	color: #fff;
	padding: 3px;
}

#installer-left {
	float: left;
	width: 230px;
	padding: 5px;
}

#installer-right {
	float: left;
}

.com-rsseo-button {
	display: inline-block;
	background: #459300 url(components/com_rsseo/assets/images/bg-button-green.gif) top left repeat-x !important;
	border: 1px solid #459300 !important;
	padding: 2px;
	color: #fff !important;
	cursor: pointer;
	margin: 0;
	-webkit-border-radius: 5px;
    -moz-border-radius: 5px;
    border-radius: 5px;
	text-decoration: none !important;
}
</style>
<div id="installer-left">
	<img src="components/com_rsseo/assets/images/rsseo-box.png" alt="RSSeo! Box" />
</div>
<div id="installer-right">
	<p>System Plugin ...
		<b class="install-ok">Installed</b>
	</p>
	<ul class="version-history">
		<li><span class="version-upgraded">Upg</span> Joomla! 3.0 compatibility (including responsive design &amp; bootstrap compatibility).</li>
		<li><span class="version-upgraded">Upg</span> Refactored code to use less resources.</li>
		<li><span class="version-new">New</span> Show a list of images that are missing the "alt" attribute.</li>
		<li><span class="version-new">New</span> Show a list of images that are missing the "width" and "height" attributes.</li>
	</ul>
	<a class="com-rsseo-button" href="index.php?option=com_rsseo">Start using RSSeo!</a>
	<a class="com-rsseo-button" href="http://www.rsjoomla.com/support/documentation/view-knowledgebase/67-rsseo.html" target="_blank">Read the RSSeo! User Guide</a>
	<a class="com-rsseo-button" href="http://www.rsjoomla.com/customer-support/tickets.html" target="_blank">Get Support!</a>
</div>
<div style="clear: both;"></div>
	
<?php	
	}
	
	protected function showUninstall() {
		echo 'RSSeo! component has been successfully uninstaled!';
	}
}