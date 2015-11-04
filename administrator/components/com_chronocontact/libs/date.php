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
class Date{
	public static function ago($timestamp){
		$diff = time() - $timestamp;
		if($diff < 60){
			//1 minute
			return sprintf(l_('DATE_X_TIME_AGO'), $diff, l_('SECONDS'));
		}else if($diff < 3600){
			//1 hour
			return sprintf(l_('DATE_X_TIME_AGO'), round($diff/60), l_('MINUTES'));
		}else if($diff < 86400){
			//1 day
			return sprintf(l_('DATE_X_TIME_AGO'), round($diff/3600), l_('HOURS'));
		}else if($diff < 604800){
			//1 week
			return sprintf(l_('DATE_X_TIME_AGO'), round($diff/86400), l_('DAYS'));
		}else if($diff < 2592000){
			//1 month
			return sprintf(l_('DATE_X_TIME_AGO'), round($diff/604800), l_('WEEKS'));
		}else if($diff < 31536000){
			//1 year
			return sprintf(l_('DATE_X_TIME_AGO'), round($diff/2592000), l_('MONTHS'));
		}else{
			//more than 1 year
			return sprintf(l_('DATE_X_TIME_AGO'), round($diff/31536000), l_('YEARS'));
		}
	}
}