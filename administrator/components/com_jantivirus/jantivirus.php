<?php
/**
 * @package	Antivirus
 * @copyright	Copyright (C) 2014 SiteGuarding.com. All rights reserved.
 * @license	GNU General Public License version 2 or later
 */
defined('_JEXEC') or die('Restricted access');
 
// Access check.
if (!JFactory::getUser()->authorise('core.manage', 'com_jantivirus')) 
{
	return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
}

// Add javascript
$document =& JFactory::getDocument();
$document->addScript('../media/com_jantivirus/js/jquery-1.10.1.min.js');
$document->addScriptDeclaration( 'jQuery.noConflict();' );
$document->addStyleSheet('../media/com_jantivirus/css/jantivirus.css');
$document->addStyleSheet('../media/com_jantivirus/css/semantic.css');
 
// require helper file
JLoader::register('jAntiVirusHelper', dirname(__FILE__).'/helpers/jantivirus.php');

require_once( dirname(__FILE__)."/classes/HttpClient.class.php");
require_once( dirname(__FILE__)."/classes/sgantivirus.class.php");
 
// import joomla controller library
jimport('joomla.application.component.controller');
 
// Get an instance of the controller
$controller = JControllerLegacy::getInstance('jAntiVirus');
 
// Perform the Request task
$controller->execute(JRequest::getCmd('task'));
 
// Redirect if set by the controller
$controller->redirect();
