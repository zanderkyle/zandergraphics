<?php 
/**
* @copyright	Copyright (C) 2011 Simplify Your Web, Inc. All rights reserved.
* @license		GNU General Public License version 3 or later; see LICENSE.txt
*/

$security = 0;
if (isset($_GET["$security"])) {
	$security = $_GET['security'];
}

define('_JEXEC', $security);

// No direct access to this file
defined('_JEXEC') or die;

// Explicitly declare the type of content
header("Content-type: text/css; charset=UTF-8");
    
// Grab module id from the request
$suffix = $_GET['suffix']; 

// style

$overall = 'original';
if (isset($_GET['overall'])) {
	$overall = $_GET['overall'];
}

// calendar

$calendar = '';
if (isset($_GET['calendar'])) {
	$calendar = $_GET['calendar'];
}

// animation

$animation = '';
if (isset($_GET['animation'])) {
	$animation = $_GET['animation'];
}

// general parameters

$item_width = 100;
if (isset($_GET['item_w'])) {
	$item_width = $_GET['item_w'];
}

$item_width_unit = '%';
$margin_in_perc = 0;
if (isset($_GET['item_w_u'])) {
	$item_width_unit = $_GET['item_w_u'];
}

if ($item_width_unit == '%') {
	$news_per_row = (int)(100 / $item_width);
	$left_for_margins = 100 - ($news_per_row * $item_width);
	$margin_in_perc = $left_for_margins / ($news_per_row * 2);
}

$percentage_of_item_width = 100; // %
$downgraded_item_width = 100; // %
$downgraded_item_width_unit = '%';

if (isset($_GET['perc_item_w'])) {
	$percentage_of_item_width = $_GET['perc_item_w'];
}

if ($item_width_unit == '%') {
	
	$downgraded_news_per_row = (int)(100 / $percentage_of_item_width);
	$downgraded_item_width = $item_width * $percentage_of_item_width / 100;
	
	if ($downgraded_news_per_row > 1) {
		$left_for_margins = 100 - (($news_per_row - 1) * $item_width + $downgraded_news_per_row * $downgraded_item_width);
		$margin_in_perc = $left_for_margins / (($news_per_row + $downgraded_news_per_row - 1) * 2);
	}
} else { // calculate width in pixels
	$downgraded_item_width_unit = 'px';
	$downgraded_item_width = (int)($item_width * $percentage_of_item_width / 100);
}

$items_height ='';
if (isset($_GET['items_h'])) {
	$items_height = $_GET['items_h'];
}

$items_width = '';
if (isset($_GET['items_w'])) {
	$items_width = $_GET['items_w'];
}

$head_width = 0;
if (isset($_GET['head_w'])) {
	$head_width = (int)$_GET['head_w'];
}

$head_height = 0;
if (isset($_GET['head_h'])) {
	$head_height = (int)$_GET['head_h'];
}

$maintain_height = false;
if (isset($_GET['mh'])) {
	$maintain_height = true;
}

$force_title_one_line = false;
if (isset($_GET['ftol'])) {
	$force_title_one_line = true;
}

$font_ref_body = -1;
if (isset($_GET['font_ref'])) {
	$font_ref_body = $_GET['font_ref'];
}

$iconfont_color = '#000000';
if (isset($_GET['if_c'])) {
	$iconfont_color = '#'.$_GET['if_c'];
}

//$active_color = 'transparent';
//if (isset($_GET['act_c'])) {
	//$active_color = '#'.$_GET['act_c'];
//}

$wrap = false;
if (isset($_GET['wrap'])) {
	$wrap = true;
}

// picture

$bgcolor = 'transparent';
if (isset($_GET['bgc'])) {
	$bgcolor = '#'.$_GET['bgc'];
}

$pic_border_width = 0;
if (isset($_GET['b_w_pic'])) {
	$pic_border_width = (int)$_GET['b_w_pic'];
}

$pic_border_color = '#FFFFFF';
if (isset($_GET['b_c_pic'])) {
	$pic_border_color = '#'.$_GET['b_c_pic'];
}

$pic_shadow_width = 0;
if (isset($_GET['sh_w_pic'])) {
	$pic_shadow_width = (int)$_GET['sh_w_pic'];
}

// calendar

$cal_shadow_width = 0;
if (isset($_GET['sh_w_cal'])) {
	$cal_shadow_width = (int)$_GET['sh_w_cal'];
}

$cal_border_width = 0;
if (isset($_GET['b_w'])) {
	$cal_border_width = $_GET['b_w'];
}

$cal_border_radius = 0;
if (isset($_GET['b_r'])) {
	$cal_border_radius = $_GET['b_r'];
}

$cal_border_color = '#000000';
if (isset($_GET['b_c'])) {
	$cal_border_color = '#'.$_GET['b_c'];
}

$color = '#3D3D3D';
if (isset($_GET['c1'])) {
	$color = '#'.$_GET['c1'];
}

$bgcolor1 = 'transparent';
if (isset($_GET['bgc11'])) {
	$bgcolor1 = '#'.$_GET['bgc11'];
}

$bgcolor2 = 'transparent';
if (isset($_GET['bgc12'])) {
	$bgcolor2 = '#'.$_GET['bgc12'];
}

$color_top = '#494949';
if (isset($_GET['c2'])) {
	$color_top = '#'.$_GET['c2'];
}

$bgcolor1_top = 'transparent';
if (isset($_GET['bgc21'])) {
	$bgcolor1_top = '#'.$_GET['bgc21'];
}

$bgcolor2_top = 'transparent';
if (isset($_GET['bgc22'])) {
	$bgcolor2_top = '#'.$_GET['bgc22'];
}

$color_bottom = '#494949';
if (isset($_GET['c3'])) {
	$color_bottom = '#'.$_GET['c3'];
}

$bgcolor1_bottom = 'transparent';
if (isset($_GET['bgc31'])) {
	$bgcolor1_bottom = '#'.$_GET['bgc31'];
}

$bgcolor2_bottom = 'transparent';
if (isset($_GET['bgc32'])) {
	$bgcolor2_bottom = '#'.$_GET['bgc32'];
}

$font_ref_cal = 14;
if (isset($_GET['font_ref_cal'])) {
	$font_ref_cal = $_GET['font_ref_cal'];
}

$font_ratio = 1; // floatval($head_height) / 80; // 1em base for a height of 80px

// pagination for animation

$paginate = false;
$symbols = false;
$pages = false;
$arrows = false;
if (isset($_GET['page'])) {
	switch ($_GET['page']) {
		case 'p':
			$paginate = true;
			$pages = true;
			break;
		case 's':
			$paginate = true;
			$pages = true;
			$symbols = true;
			break;
		case 'pn': 
			$paginate = true;
			$arrows = true;
			break;
		case 'ppn':
			$paginate = true;
			$arrows = true;
			$pages = true;
			break;
		case 'psn':
			$paginate = true;
			$symbols = true;
			$arrows = true;
			$pages = true;
			break;
		default:
			$paginate = false;
			$symbols = false;
			$arrows = false;
			$pages = false;
			break;
	}
}

$align_pagination = 'center';
if (isset($_GET['palign'])) {
	$align_pagination = $_GET['palign'];
}

$position_pagination = '';
if (isset($_GET['ppos'])) {
	$position_pagination = $_GET['ppos'];
}

$pagination_size = 1;
if (isset($_GET['ps'])) {
	$pagination_size = $_GET['ps'];
}

$pagination_offset = 0;
if (isset($_GET['po'])) {
	$pagination_offset = $_GET['po'];
}

// calculated variables

// CSS
  
$links = array();

$links[] = 'styles/style.css.php';
$links[] = 'styles/overall/'.$overall.'/style.css.php';
if (!empty($calendar)) {
	$links[] = 'styles/calendar/'.$calendar.'/style.css.php';
}
if (!empty($animation)) {
	$links[] = 'animations/'.$animation.'/style.css.php';
}
  
function compress($buffer) {
	/* remove comments */
	$buffer = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $buffer);
	/* remove tabs, spaces, newlines, etc. */
	$buffer = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $buffer);
	return $buffer;
}

ob_start("compress");
	
foreach ($links as $link) {
	include $link;
}

ob_end_flush();
?>