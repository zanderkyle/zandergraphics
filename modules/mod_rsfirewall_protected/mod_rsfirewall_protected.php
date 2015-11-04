<?php
/**
* @version 1.0.0
* @package Protected by RSFirewall! Module 1.0.0
* @copyright (C) 2013 www.rsjoomla.com
* @license GPL, http://www.gnu.org/licenses/gpl-2.0.html
*/

defined('_JEXEC') or die('Restricted access');

$doc = JFactory::getDocument();
$doc->addStyleSheet(JURI::root(true).'/modules/mod_rsfirewall_protected/assets/css/style.css');

$class = '';
if ($params->get('moduleclass_sfx')) {
	$class = ' class="'.htmlentities($params->get('moduleclass_sfx'), ENT_COMPAT, 'utf-8').'"';
}
$size = htmlentities($params->get('size'), ENT_COMPAT, 'utf-8');

require JModuleHelper::getLayoutPath('mod_rsfirewall_protected');