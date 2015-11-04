<?php
/**
* @version 1.0.0
* @package RSSEO! 1.0.0
* @copyright (C) 2009-2012 www.rsjoomla.com
* @license GPL, http://www.gnu.org/licenses/gpl-2.0.html
*/

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

require_once JPATH_COMPONENT.'/helper.php';
$params	= JFactory::getApplication()->getParams('com_rsseo');

if ($params->get('show_page_heading', 1))
	echo '<h1>'.$params->get('page_heading').'</h1>';

echo rsseoMenuHelper::generateSitemap();