<?php
/**
* @version 1.0.0
* @package RSSeo! 1.0.0
* @copyright (C) 2009-2012 www.rsjoomla.com
* @license GPL, http://www.gnu.org/copyleft/gpl.html
*/

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

class rsseoControllerSitemap extends JControllerAdmin
{
	/**
	 * Constructor.
	 *
	 * @param	array	$config	An optional associative array of configuration settings.

	 * @return	rsseoControllerSitemap
	 * @see		JController
	 * @since	1.6
	 */
	public function __construct($config = array()) {
		parent::__construct($config);
	}
	
	/**
	 * Proxy for getModel.
	 *
	 * @param	string	$name	The name of the model.
	 * @param	string	$prefix	The prefix for the PHP class name.
	 *
	 * @return	JModel
	 * @since	1.6
	 */
	public function getModel($name = 'Sitemap', $prefix = 'rsseoModel', $config = array('ignore_request' => true)) {
		$model = parent::getModel($name, $prefix, $config);
		return $model;
	}
	
	/**
	 *	Attempt to create the sitemap.xml and ror.xml
	 *
	 */
	public function create() {
		jimport('joomla.filesystem.file');
		jimport('joomla.filesystem.path');
		
		$type	= JFactory::getApplication()->input->getCmd('file');
		$empty	= '';
		$file	= JPATH_SITE.'/'.$type.'.xml';
		
		if ($create = JFile::write($file,$empty)) {
			if (JPath::canChmod($file)) {
				JPath::setPermissions($file,'0777');
			}
			echo 1;
		} else {
			echo 0;
		}
		
		JFactory::getApplication()->close();
	}
	
	/**
	 *	Generate the XML sitemap
	 *
	 */
	public function generate() {
		require_once JPATH_ADMINISTRATOR.'/components/com_rsseo/helpers/sitemap.php';
		
		$db			= JFactory::getDBO();
		$query		= $db->getQuery(true);
		$jinput 	= JFactory::getApplication()->input;
		$new		= $jinput->getInt('new',0);
		$protocol	= $jinput->getInt('protocol',0);
		$modified	= $jinput->getCmd('modified','');
		$auto		= $jinput->getInt('auto',0);
		
		// Get a new instance of the Sitemap class
		$sitemap = sitemapHelper::getInstance($new, $protocol, $modified, $auto);
		
		$query->clear();
		$query->select($db->quoteName('id').', '.$db->quoteName('url').', '.$db->quoteName('title').', '.$db->quoteName('level').', '.$db->quoteName('priority').', '.$db->quoteName('frequency'));
		$query->from($db->quoteName('#__rsseo_pages'))->where($db->quoteName('sitemap').' = 0')->where($db->quoteName('insitemap').' = 1')->where($db->qn('published').' != -1');
		if (!$auto) $query->where($db->quoteName('level').' != 127');
		$query->order($db->quoteName('level'));
		$db->setQuery($query,0,500);
		
		$sitemap->setHeader();
		
		if ($pages = $db->loadObjectList()) {
			foreach ($pages as $page) {
				$sitemap->add($page);
				
				$query->clear();
				$query->update($db->quoteName('#__rsseo_pages'))->set($db->quoteName('sitemap').' = 1')->where($db->quoteName('id').' = '.$db->quote($page->id));
				$db->setQuery($query);
				$db->execute();
			}
		} else {
			$sitemap->close();
			echo 'finish';
			JFactory::getApplication()->close();
		}
		
		$query->clear();
		$query->select('COUNT(id)')->from($db->quoteName('#__rsseo_pages'))->where($db->quoteName('insitemap').' = 1')->where($db->qn('published').' != -1');
		if (!$auto) $query->where($db->quoteName('level').' != 127');
		$db->setQuery($query);
		$total = (int) $db->loadResult();
		
		$query->clear();
		$query->select('COUNT(id)')->from($db->quoteName('#__rsseo_pages'))->where($db->quoteName('sitemap').' = 1')->where($db->quoteName('insitemap').' = 1')->where($db->qn('published').' != -1');
		if (!$auto) $query->where($db->quoteName('level').' != 127');
		$db->setQuery($query);
		$processed = (int) $db->loadResult();
		
		echo ceil ($processed * 100 / $total);
		JFactory::getApplication()->close();
	}
	
	/**
	 *	Create the HTML sitemap
	 *
	 */
	public function html() {
		$db			= JFactory::getDBO();
		$query		= $db->getQuery(true);
		$jinput		= JFactory::getApplication()->input;
		$menus		= $jinput->get('menus',array(),'array');
		$exclude	= $jinput->get('exclude',array(),'array');
		
		$component	= JComponentHelper::getComponent('com_rsseo');
		$cparams	= $component->params;
		
		if ($cparams instanceof JRegistry) {
			$cparams->set('sitemap_menus', $menus);
			$cparams->set('sitemap_excludes', $exclude);
			$query->clear();
			$query->update($db->quoteName('#__extensions'));
			$query->set($db->quoteName('params'). ' = '.$db->quote((string) $cparams));
			$query->where($db->quoteName('extension_id'). ' = '. $db->quote($component->id));
			
			$db->setQuery($query);
			$db->execute();
		}
		
		$this->setMessage(JText::_('COM_RSSEO_HTML_SITEMAP_CREATED'));
		return $this->setRedirect('index.php?option=com_rsseo&view=sitemap');
	}
}