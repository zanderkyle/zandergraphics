<?php
/**
* @version 1.0.0
* @package RSSEO! 1.0.0
* @copyright (C) 2009-2012 www.rsjoomla.com
* @license GPL, http://www.gnu.org/licenses/gpl-2.0.html
*/
defined( '_JEXEC' ) or die( 'Restricted access' );

// ACL Check
if (!JFactory::getUser()->authorise('core.manage', 'com_rsseo'))
	return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));

require_once(JPATH_COMPONENT.'/helpers/rsseo.php');
require_once(JPATH_COMPONENT.'/helpers/adapter/adapter.php');
require_once(JPATH_COMPONENT.'/controller.php');

JHTML::_('behavior.framework');

// Load scripts
rsseoHelper::setScripts('administrator');
// Check for keywords config
rsseoHelper::keywords();

$controller	= JControllerLegacy::getInstance('RSSeo');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();