<?php
/**
* ChronoCMS version 1.0
* Copyright (c) 2012 ChronoCMS.com, All rights reserved.
* Author: (ChronoCMS.com Team)
* license: Please read LICENSE.txt
* Visit http://www.ChronoCMS.com for regular updates and information.
**/
namespace GCore\Libs;
defined('_JEXEC') or die('Restricted access');
defined("GCORE_SITE") or die;
class Env {

	public static function redirect($url){
		header('Location: '.str_replace('&amp;', '&', $url));
		ob_end_flush();
		exit();
	}
}