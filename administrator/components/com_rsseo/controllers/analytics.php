<?php
/**
* @version 1.0.0
* @package RSSeo! 1.0.0
* @copyright (C) 2009-2012 www.rsjoomla.com
* @license GPL, http://www.gnu.org/copyleft/gpl.html
*/

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );


class rsseoControllerAnalytics extends JControllerAdmin
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
	 * Method to save config 
	 *
	 */
	public function save() {
		$db			= JFactory::getDBO();
		$app		= JFactory::getApplication();
		$query		= $db->getQuery(true);
		
		$query->clear();
		$query->select($db->qn('extension_id'));
		$query->select($db->qn('params'));
		$query->from('#__extensions');
		$query->where($query->qn('type') . ' = ' . $db->quote('component'));
		$query->where($query->qn('element') . ' = ' . $db->quote('com_rsseo'));
		$db->setQuery($query);
		$component = $db->loadObject();
		
		$registry = new JRegistry;
		$registry->loadString($component->params);
		$registry->set('ga_account', $app->input->get('account'));
		$registry->set('ga_start', $app->input->get('rsstart'));
		$registry->set('ga_end', $app->input->get('rsend'));
		
		$query->clear();
		$query->update($db->quoteName('#__extensions'));
		$query->set($db->quoteName('params'). ' = '.$db->quote((string) $registry));
		$query->where($db->quoteName('extension_id'). ' = '. $db->quote($component->extension_id));
		
		$db->setQuery($query);
		$db->execute();
		
		$app->redirect('index.php?option=com_rsseo&view=analytics');
	}
}