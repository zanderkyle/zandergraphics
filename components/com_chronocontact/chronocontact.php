<?php
/**
* COMPONENT FILE HEADER
**/
defined('_JEXEC') or die('Restricted access');
define("GCORE_SITE", "front");
require_once(dirname(dirname(dirname(__FILE__))).DIRECTORY_SEPARATOR.'administrator'.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_chronocontact'.DIRECTORY_SEPARATOR.'gcloader.php');
GCore\Bootstrap::initialize('joomla', array('component' => 'com_chronocontact'));
$mainframe = \JFactory::getApplication();

$tvout = strlen(\GCore\Libs\Request::data('tvout', null)) > 0 ? \GCore\Libs\Request::data('tvout') : '';
$controller = GCore\Libs\Request::data('cont', '');
$action = GCore\Libs\Request::data('act', '');

$ccfname = GCore\Libs\Request::data('ccfname', '');
$ccfevent = GCore\Libs\Request::data('ccfevent', '');
if(empty($ccfname)){
	$params = $mainframe->getPageParameters('com_chronocontact');
	GCore\Libs\Request::set('ccfname', $params->get('formname'));
	if(empty($event)){
		GCore\Libs\Request::set('ccfevent', $params->get('event'));
	}
}

ob_start();
echo \GCore\Libs\AppJ::call('front', 'chronocontact', $controller, $action, array());
$output = ob_get_clean();

if($tvout == 'ajax'){
	echo $output;
	$mainframe = \JFactory::getApplication();
	$mainframe->close();
}else{
	ob_start();
	echo '<div style="clear:both;"></div>';
	echo \GCore\Libs\AppJ::getSystemMessages();
	$system_output = ob_get_clean();
	echo \GCore\Libs\AppJ::getHeader();
	echo $system_output;
	echo $output;
}