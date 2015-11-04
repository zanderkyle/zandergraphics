<?php
/**
* @version 1.0.0
* @package RSSEO! 1.0.0
* @copyright (C) 2009-2012 www.rsjoomla.com
* @license GPL, http://www.gnu.org/licenses/gpl-2.0.html
*/

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

class RSSeoController extends JControllerLegacy
{
	public function __construct() {
		parent::__construct();
		JTable::addIncludePath(JPATH_ADMINISTRATOR.'/components/com_rsseo/tables');
	}

	/**
	 * Method to display a view.
	 *
	 * @param	boolean			If true, the view output will be cached
	 * @param	array			An array of safe url parameters and their variable types, for valid values see {@link JFilterInput::clean()}.
	 *
	 * @return	JController		This object to support chaining.
	 * @since	1.5
	 */
	public function display($cachable = false, $urlparams = false) {
		rsseoHelper::addSubmenu(JFactory::getApplication()->input->getCmd('view'));
		
		parent::display();
		return $this;
	}
	
	/**
	 *	Method to display the RSSeo! Dashboard
	 *
	 * @return void
	 */
	public function main() {
		return $this->setRedirect('index.php?option=com_rsseo');
	}
	
	/**
	 *	Method to check a page loading time and size
	 *
	 * @return string
	 */
	public function pagecheck() {
		require_once JPATH_SITE.'/administrator/components/com_rsseo/helpers/class.webpagesize.php';
		$db		= JFactory::getDBO();
		$query	= $db->getQuery(true);
		$id		= JFactory::getApplication()->input->getInt('id');
		
		$query->clear();
		$query->select('`url`')->from('`#__rsseo_pages`')->where('`id` = '.$id);
		$db->setQuery($query);
		$url = $db->loadResult();
		
		set_time_limit(100);
		$size = new WebpageSize(JURI::root().$url);
		$page_size = $size->sizeofpage();
		$time_total = $size->getTime();
		$page_load = number_format($time_total,3);
	
		echo JText::sprintf('COM_RSSEO_PAGE_SIZE_DESCR',$page_size,$id)."RSDELIMITER".JText::sprintf('COM_RSSEO_PAGE_TIME_DESCR',$page_load);
		JFactory::getApplication()->close();
	}
	
	/**
	 *	Method to search for pages
	 *
	 * @return string
	 */
	public function search() {
		$db		= JFactory::getDBO();
		$query	= $db->getQuery(true);
		$search	= JFactory::getApplication()->input->getString('search');
		$html	= array();
		
		$query->select($db->quoteName('title').','.$db->quoteName('url'))->from($db->quoteName('#__rsseo_pages'))->where($db->quoteName('url').' LIKE '.$db->quote('%'.$search.'%').' OR '.$db->quoteName('title').' LIKE '.$db->quote('%'.$search.'%'));
		$db->setQuery($query);
		$results = $db->loadObjectList();
		
		$html[] = '<li class="rss_close"><a href="javascript:void(0);" onclick="closeCanonicalSearch();">'.JText::_('COM_RSSEO_GLOBAL_CLOSE').'</a></li>';
		
		if (!empty($results)) {
			foreach ($results as $result) {
				$url = JURI::root().$result->url;
				$html[] = '<li><a href="javascript:void(0);" onclick="addCanonical(\''.$url.'\')">'.$result->title.'<br/>'.$url.'</a></li>';
			}
		} else $html[] = '<li>'.JText::_('COM_RSSEO_NO_RESULTS').'</li>';
		
		echo implode("\n",$html);
		JFactory::getApplication()->close();
	}
	
	/**
	 *	Method to check for connectivity
	 *
	 * @return void
	 */
	public function connectivity() {
		$google = JFactory::getApplication()->input->getInt('google',0);
		
		if ($google) {
			require_once JPATH_ADMINISTRATOR. '/components/com_rsseo/helpers/google.php';
			$google = new RSSeoGoogle('http://www.rsjoomla.com');
			$response = $google->check();
		
			if ($response === true) {
				JFactory::getApplication()->redirect('index.php?option=com_rsseo',JText::_('COM_RSSEO_CONNECTIVITY_OK'));
			} else {
				echo $response;
				JFactory::getApplication()->close();
			}
		} else {
			$msg	= '';
			$ok		= array();
			$notok	= array();
			if ($connection = rsseoHelper::fopen(JURI::root(), 1, true)) {
				foreach ($connection as $method => $valid) {
					if ($valid)
						$ok[] = $method;
					else $notok[] = $method;
				}
			}
			
			if (empty($notok) && !empty($ok)) {
				$msg = JText::_('COM_RSSEO_CONNECTIVITY_OK');
			} else if (empty($ok)) {
				$msg = JText::_('COM_RSSEO_CONNECTIVITY_ERROR');
			} else if (!empty($ok) && !empty($notok)) {
				$msg = JText::sprintf('COM_RSSEO_CONNECTIVITY_MESSAGE', implode(',',$notok), implode(',',$ok));
			}
			
			JFactory::getApplication()->redirect('index.php?option=com_rsseo', $msg);
		}
	}
	
	/**
	 *	Method to crawl a page
	 *
	 * @return void
	 */
	public function crawl() {
		$initialize = JFactory::getApplication()->input->getInt('init');
		$id			= JFactory::getApplication()->input->getInt('id');
		$original	= JFactory::getApplication()->input->getInt('original',0);
		
		require_once JPATH_ADMINISTRATOR. '/components/com_rsseo/helpers/crawler.php';
		$crawler = crawlerHelper::getInstance($initialize, $id, $original);
		echo $crawler->crawl();
		JFactory::getApplication()->close();
	}
}