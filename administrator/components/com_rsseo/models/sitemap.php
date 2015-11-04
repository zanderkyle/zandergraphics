<?php
/**
* @version 1.0.0
* @package RSSeo! 1.0.0
* @copyright (C) 2009-2012 www.rsjoomla.com
* @license GPL, http://www.gnu.org/copyleft/gpl.html
*/

defined('_JEXEC') or die('Restricted access');
jimport( 'joomla.application.component.model' );

class rsseoModelSitemap extends JModelAdmin
{
	/**
	 * @var		string	The prefix to use with controller messages.
	 * @since	1.6
	 */
	protected $text_prefix = 'COM_RSSEO';
	
	/**
	 * Method to get the record form.
	 *
	 * @param	array	$data		Data for the form.
	 * @param	boolean	$loadData	True if the form is to load its own data (default case), false if not.
	 *
	 * @return	mixed	A JForm object on success, false on failure
	 * @since	1.6
	 */
	public function getForm($data = array(), $loadData = true) {
		// Get the form.
		$form = $this->loadForm('com_rsseo.sitemap', 'sitemap', array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form))
			return false;
		
		$form->setValue('modified',null,JHtml::_('date','now','Y-m-d'));
		$form->setValue('auto',null,rsseoHelper::getConfig('sitemapauto'));
		
		return $form;
	}
	
	/**
	 *	Method to get the percentage of processed pages
	*/
	public function getPercent() {
		$db		= JFactory::getDBO();
		$query	= $db->getQuery(true);
		$auto	= rsseoHelper::getConfig('sitemapauto');
		
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
		
		return $total > 0 ? ceil($processed * 100 / $total) : 0;
	}
}