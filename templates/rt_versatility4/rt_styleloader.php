<?php
/**
 * @package   Versatility4 Template - RocketTheme
* @version   $Id: rt_styleloader.php 26096 2015-01-27 14:14:12Z james $
* @author    RocketTheme, LLC http://www.rockettheme.com
* @copyright Copyright (C) 2007 - 2015 RocketTheme, LLC
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 *
 * Rockettheme Versatility4 Template uses the Joomla Framework (http://www.joomla.org), a GNU/GPLv2 content management system
 *
 */
defined( '_JEXEC' ) or die( 'Restricted index access' );

// set default vars
$fontstyle = "f-" . $default_font;
$fontfamily = $font_family;
$tstyle = $default_style;
$mstyle = $menu_style;
$mtype = $menu_type;
$h3style = $h3_style;
$hwrap = $header_wrapped;
$fwrap = $footer_wrapped;
$thisurl = $this->base . rebuildQueryString($template_properties);


//array of properties to look for and store
foreach ($template_properties as $tprop) {
    $my_session = JFactory::getSession();


    if ($my_session->get($cookie_prefix.$tprop)) {
        $$tprop = $my_session->get($cookie_prefix.$tprop);
    } elseif (isset($_COOKIE[$cookie_prefix. $tprop])) {
    	$$tprop = htmlentities(JRequest::getVar($cookie_prefix. $tprop, '', 'COOKIE', 'STRING'));
    }    
}

// rebuild the querystring when needed
function rebuildQueryString($template_properties) {

  if (!empty($_SERVER['QUERY_STRING'])) {
      $parts = explode("&", $_SERVER['QUERY_STRING']);
      $newParts = array();
      foreach ($parts as $val) {
          $val_parts = explode("=", $val);
          if (!in_array($val_parts[0], $template_properties)) {
            array_push($newParts, $val);
          }
      }
      if (count($newParts) != 0) {
          $qs = implode("&amp;", $newParts);
      } else {
          return "?";
      }
      return "?" . $qs . "&amp;"; // this is your new created query string
  } else {
      return "?";
  } 
}
?>
