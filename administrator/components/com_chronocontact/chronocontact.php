<?php
/**
* COMPONENT FILE HEADER
**/
defined('_JEXEC') or die('Restricted access');
//basic checks
$success = array();
$fails = array();
if(version_compare(PHP_VERSION, '5.3.0') >= 0){
	$success[] = "PHP 5.3.0 or later found.";
}else{
	$fails[] = "Your PHP version is outdated: ".PHP_VERSION;
}
if(phpversion('pdo') !== false){
	$success[] = "PDO Extension is available and enabled.";
	if(in_array('mysql', \PDO::getAvailableDrivers())){
		$success[] = "PDO MYSQL Driver is available.";
	}else{
		$fails[] = "PDO MYSQL Driver couldn't be found.";
	}
}else{
	$fails[] = "PDO Extension is NOT available or may be disabled.";
}
if(!empty($fails)){
	JError::raiseWarning(100, "Your PHP version should be 5.3 or later, you must have the PDO extension and PDO MYSQL extension enabled in your PHP config.");
}
//end basic checks
if(empty($fails)){
	define("GCORE_SITE", "admin");
	require_once(dirname(__FILE__).DIRECTORY_SEPARATOR.'gcloader.php');
	GCore\Bootstrap::initialize('joomla', array('component' => 'com_chronocontact'));

	$tvout = strlen(\GCore\Libs\Request::data('tvout', null)) > 0 ? \GCore\Libs\Request::data('tvout') : '';
	$controller = GCore\Libs\Request::data('cont', '');
	$action = GCore\Libs\Request::data('act', '');

	ob_start();
	echo \GCore\Libs\AppJ::call('admin', 'chronocontact', $controller, $action, array());
	$output = ob_get_clean();

	if($tvout == 'ajax'){
		echo $output;
		$mainframe = \JFactory::getApplication();
		$mainframe->close();
	}else{		
		ob_start();
		echo \GCore\Helpers\Module::render(array('type' => 'toolbar', 'site' => 'admin'));
		echo '<div style="clear:both;"></div>';
		echo \GCore\Libs\AppJ::getSystemMessages();
		$system_output = ob_get_clean();
		echo \GCore\Libs\AppJ::getHeader();
		echo $system_output;
		echo $output;
	}
}