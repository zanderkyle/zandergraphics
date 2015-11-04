<?php
/**
* ChronoCMS version 1.0
* Copyright (c) 2012 ChronoCMS.com, All rights reserved.
* Author: (ChronoCMS.com Team)
* license: Please read LICENSE.txt
* Visit http://www.ChronoCMS.com for regular updates and information.
**/
namespace GCore\Admin\Extensions\Chronocontact\Helpers;
defined('_JEXEC') or die('Restricted access');
defined("GCORE_SITE") or die;
class FormsConfig {
	
	function load_config($action, $action_id = null, $data = array(), $extra_config = array()){
		$classname = 'GCore\Extensions\Chronocontact\Actions\\'.\GCore\Libs\Str::camilize($action).'\\'.\GCore\Libs\Str::camilize($action);
		${$classname} = new $classname();
		
		//check defaults
		if(!isset(${$classname}->defaults)){
			${$classname}->defaults = array();
		}
		//if new action with no config
		if(!isset($data[$action][$action_id])){
			$data[$action][$action_id] = array();
		}
		
		ob_start();
		${$classname}->config($data[$action][$action_id], $extra_config);
		$config = ob_get_clean();
		//check stored data
		if(!empty($action_id)){
			$config = str_replace('{N}', $action_id, $config);
			$data[$action][$action_id] = array_merge(${$classname}->defaults, $data[$action][$action_id]);
		}else{
			$data[$action]['{N}'] = ${$classname}->defaults;//array_merge(${$classname}->defaults, $data[$action]/*['{N}']*/);	
		}
		//republish data
		$config = \GCore\Helpers\DataLoader::load($config, array('config' => $data));
		echo $config;
	}
}
?>