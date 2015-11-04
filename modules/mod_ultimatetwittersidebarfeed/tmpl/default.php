<?php
/*------------------------------------------------------------------------
# mod_ultimatetwittersidebarfeed - Ultimate Twitter Sidebar Feed
# ------------------------------------------------------------------------
# @author - Ultimate Social Widgets
# copyright Copyright (C) 2013 Ultimate Social Widgets. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://ultimatesocialwidgets.com/
-------------------------------------------------------------------------*/
// no direct access
defined( '_JEXEC' ) or die;
//get parameters
$twitter_username = $params->get('twitter_username');
$twitter_widget_id = $params->get('twitter_widget_id');
$width = $params->get('width');
$height = $params->get('height');
$widget_theme = $params->get('widget_theme');
$links_color = $params->get('links_color');
$border_color = $params->get('border_color');
$count = 3;
$border = $params->get('border');
$scrollbar = $params->get('scrollbar');
if($border=="yes"){
	$noborders	= '';
	}
else{
	$noborders	= 'noborders ';
	}
if($scrollbar=="yes"){
	$scroll	= '';
	}
else{
	$scroll	= 'noscrollbar ';
	}

$nofooter	= 'nofooter ';



echo '<a class="twitter-timeline" data-theme="'.$widget_theme.'" data-link-color="#'.$links_color.'" data-border-color="#'.$border_color.'"  data-chrome="'.$nofooter.$noborders.$scroll.'"  '.$count.'  href="https://twitter.com/'.$twitter_username.'" data-widget-id="'.$twitter_widget_id.'" width="'.$width.'" height="'.$height.'">Tweets by @'.$twitter_username.'</a>

<script type="text/javascript">!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?\'http\':\'https\';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>';
		
	?>