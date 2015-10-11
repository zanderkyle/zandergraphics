<?php
/**
 * @package   Versatility4 Template - RocketTheme
* @version   $Id: rt_styleswitcher.php 26096 2015-01-27 14:14:12Z james $
* @author    RocketTheme, LLC http://www.rockettheme.com
* @copyright Copyright (C) 2007 - 2015 RocketTheme, LLC
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 *
 * Rockettheme Versatility4 Template uses the Joomla Framework (http://www.joomla.org), a GNU/GPLv2 content management system
 *
 */
defined( '_JEXEC' ) or die( 'Restricted index access' );

$cookie_prefix = "versatility4j17-";
$cookie_time = time()+31536000;
$template_properties = array('fontstyle','fontfamily','tstyle','mstyle','mtype','h3style','hwrap','fwrap');

foreach ($template_properties as $tprop) {
    $my_session = JFactory::getSession();
	
	if (isset($_REQUEST[$tprop])) {
	    $$tprop = htmlentities(JRequest::getString($tprop, null, 'get'));
	
		// support the font size toggler
		if ($$tprop=="f-smaller" || $$tprop =="f-larger") {
			$fsize = "f-default";
	
		 	if ($my_session->get($cookie_prefix. $tprop)) {
				 $fsize = $my_session->get($cookie_prefix. $tprop);
			 } elseif (isset($_COOKIE[$cookie_prefix. $tprop])) {
				 $fsize = htmlentities(JRequest::getVar($cookie_prefix. $tprop, '', 'COOKIE', 'STRING'));
			 }
			if ($$tprop=="f-smaller" && $fsize=="f-default") $$tprop = "f-small";
			elseif ($$tprop=="f-smaller" && $fsize=="f-small") $$tprop = "f-small";
			elseif ($$tprop=="f-smaller" && $fsize=="f-large") $$tprop = "f-default";
			elseif ($$tprop=="f-larger" && $fsize=="f-large") $$tprop = "f-large";
			elseif ($$tprop=="f-larger" && $fsize=="f-default") $$tprop = "f-large";
			elseif ($$tprop=="f-larger" && $fsize=="f-small") $$tprop = "f-default";
		}	
	
    	$my_session->set($cookie_prefix.$tprop, $$tprop);
    	setcookie ($cookie_prefix. $tprop, $$tprop, $cookie_time, '/', false);   
    	global $$tprop; 
    }
}

?>
