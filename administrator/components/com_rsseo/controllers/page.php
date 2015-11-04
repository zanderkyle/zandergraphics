<?php
/**
* @version 1.0.0
* @package RSSeo! 1.0.0
* @copyright (C) 2009-2012 www.rsjoomla.com
* @license GPL, http://www.gnu.org/copyleft/gpl.html
*/

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

class rsseoControllerPage extends JControllerForm
{
	/**
	 * Class constructor.
	 *
	 * @param   array  $config  A named array of configuration variables.
	 *
	 * @since	1.6
	 */
	public function __construct() {
		parent::__construct();
	}
	
	public function refresh() {
		$jinput = JFactory::getApplication()->input->get('jform',array(),'array');
		require_once JPATH_ADMINISTRATOR. '/components/com_rsseo/helpers/crawler.php';
		$crawler = crawlerHelper::getInstance(0, (int) $jinput['id']);
		$crawler->crawl();
		
		return $this->setRedirect('index.php?option=com_rsseo&view=page&layout=edit&id='.$jinput['id']);
	}
}