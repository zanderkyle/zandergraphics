<?php
/**
* ChronoCMS version 1.0
* Copyright (c) 2012 ChronoCMS.com, All rights reserved.
* Author: (ChronoCMS.com Team)
* license: Please read LICENSE.txt
* Visit http://www.ChronoCMS.com for regular updates and information.
**/
namespace GCore\Extensions\Chronocontact\Actions\Credits;
defined('_JEXEC') or die('Restricted access');
defined("GCORE_SITE") or die;
Class Credits {
	function execute(&$form, $config = array(), $action_id = null){
		$settings_model = new \GCore\Admin\Models\Extension();
		$settings = $settings_model->find('first', array('conditions' => array('name' => 'chronocontact')));
		if((int)$settings['Extension']['settings']['validated'] != 1){
			echo '<br '.'/><br'.' /><a href="http://www.chronoengine.com" target="_blank"><stron'.'g>Pow'.'ere'.'d b'.'y Chr'.'onoC'.'ont'.'act '.'- C'.'op'.'yr'.'ight'.' (c'.') 20'.'12 C'.'hr'.'ono'.'E'.'ng'.'ine.'.'com</st'.'rong></a><b'.'r />';
		}
	}
}