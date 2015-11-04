<?php
/**
* ChronoCMS version 1.0
* Copyright (c) 2012 ChronoCMS.com, All rights reserved.
* Author: (ChronoCMS.com Team)
* license: Please read LICENSE.txt
* Visit http://www.ChronoCMS.com for regular updates and information.
**/
namespace GCore;
defined('_JEXEC') or die('Restricted access');
defined("GCORE_SITE") or die;

class Bootstrap {
	const VERSION = 1;
	const UPDATE = 1;
	public static function initialize($plathform = '', $params = array()){
		switch ($plathform){
			default:
				//CONSTANTS
				define('GCORE_FRONT_PATH', dirname(__FILE__).DS);
				define('GCORE_FRONT_URL', \GCore\Libs\Url::root());
				define('GCORE_ADMIN_PATH', dirname(__FILE__).DS.'admin'.DS);
				define('GCORE_ADMIN_URL', \GCore\Libs\Url::root()."admin/");
				
				define('GSITE_PATH', constant('GCORE_'.strtoupper(GCORE_SITE).'_PATH'));
				define('GSITE_URL', constant('GCORE_'.strtoupper(GCORE_SITE).'_URL'));
				//initialize language
				\GCore\Libs\Lang::initialize();
				//SET ERROR CONFIG
				if((int)Libs\Base::getConfig('error_reporting') != 1){
					error_reporting((int)Libs\Base::getConfig('error_reporting'));
				}
				if((bool)Libs\Base::getConfig('debug') === true){
					\GCore\Libs\Error::initialize();
				}
			break;
		}
		
		if($plathform == 'joomla'){
			$mainframe = \JFactory::getApplication();
			\GCore\Libs\Base::setConfig('db_host', $mainframe->getCfg('host'));
			$dbtype = ($mainframe->getCfg('dbtype') == 'mysqli' ? 'mysql' : $mainframe->getCfg('dbtype'));
			\GCore\Libs\Base::setConfig('db_type', $dbtype);
			\GCore\Libs\Base::setConfig('db_name', $mainframe->getCfg('db'));
			\GCore\Libs\Base::setConfig('db_user', $mainframe->getCfg('user'));
			\GCore\Libs\Base::setConfig('db_pass', $mainframe->getCfg('password'));
			\GCore\Libs\Base::setConfig('db_prefix', $mainframe->getCfg('dbprefix'));
			define('GSITE_PLATFORM', 'joomla');
			define('GCORE_ROOT_URL', str_replace('/administrator/components/'.$params['component'], '', GCORE_FRONT_URL));
		}else{
			define('GSITE_PLATFORM', '');
			define('GCORE_ROOT_URL', GCORE_FRONT_URL);
		}
	}
}