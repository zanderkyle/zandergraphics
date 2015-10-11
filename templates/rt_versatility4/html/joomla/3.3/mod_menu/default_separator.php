<?php
/**
 * @package   Versatility4 Template - RocketTheme
* @version   $Id: default_separator.php 26096 2015-01-27 14:14:12Z james $
* @author    RocketTheme, LLC http://www.rockettheme.com
* @copyright Copyright (C) 2007 - 2015 RocketTheme, LLC
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 *
 * Rockettheme Versatility4 Template uses the Joomla Framework (http://www.joomla.org), a GNU/GPLv2 content management system
 *
 */

// No direct access.
defined('_JEXEC') or die;

// Note. It is important to remove spaces between elements.
if ($item->deeper) { 
	$status = 'daddy';
} else {
	$status = 'child';
}

// Note. It is important to remove spaces between elements.
$title = $item->anchor_title ? 'title="'.$item->anchor_title.'" ' : '';
if ($item->menu_image) {
		$item->params->get('menu_text', 1 ) ?
		$linktype = '<img src="'.$item->menu_image.'" alt="'.$item->title.'" /><span class="image-title">'.$item->title.'</span> ' :
		$linktype = '<img src="'.$item->menu_image.'" alt="'.$item->title.'" />';
}
else { $linktype = $item->title;
}

?><span class="separator <?php echo $status; ?>"><?php echo $title; ?><?php echo $linktype; ?></span>
