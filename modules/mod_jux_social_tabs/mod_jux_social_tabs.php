<?php

/**
 * @version  $Id$
 * @author  JoomlaUX!
 * @package  Joomla.Site
 * @subpackage mod_jux_slideshow
 * @copyright Copyright (C) 2012 - 2013 by JoomlaUX. All rights reserved.
 * @license  http://www.gnu.org/licenses/gpl.html
 */
// no direct access
defined('_JEXEC') or die('Restricted access');

// Get mod_jux_social_tabs version 
$xml = JFactory::getXML(JPATH_SITE . '/modules/mod_jux_social_tabs/mod_jux_social_tabs.xml');
$jstVersion = (string)$xml->version;

require_once (dirname(__FILE__).'/helper.php');
$document = JFactory::getDocument();
$document->addStyleSheet(JURI::base() . 'modules/' . $module->module . '/assets/css/dcsmt.css?ver='.$jstVersion);
if (!defined('MOD_JUX_SOCIAL_MEDIA_TABS_ASSETS'))
{
    define('MOD_JUX_SOCIAL_MEDIA_TABS_ASSETS', 1);
    /* load javascript. */
    modSocialmediaHelper::javascript($params);
}

if ($params->get('mod_showTwitter', 0) == 1)
{
    modSocialmediaHelper::createTwitterLicenseKey($params);
}
// $live_site = JURI::base();
require( JModuleHelper::getLayoutPath('mod_jux_social_tabs', 'default') );

