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
header("Content-type: text/javascript; charset=UTF-8");
    
// Grab module id from the request
$suffix = $_GET['suffix'];

$module = '#lnee_'.$suffix;

// jQuery var

$jQuery_var = 'jQuery';

// general parameters

$horizontal = false;
if (isset($_GET['conf'])) {
	$horizontal = ($_GET['conf'] === 'h') ? true : false;
}

$item_width = 100;
if (isset($_GET['item_w'])) {
	$item_width = $_GET['item_w'];
}

$item_width_unit = '%';
$margin_in_perc = 0;
if (isset($_GET['item_w_u'])) {
	$item_width_unit = $_GET['item_w_u'];
}

if ($item_width_unit == '%' && $item_width > 0 && $item_width < 100) {
	$news_per_row = (int)(100 / $item_width);
	$left_for_margins = 100 - ($news_per_row * $item_width);
	$margin_in_perc = $left_for_margins / ($news_per_row * 2);
}

$items_height ='';
if (isset($_GET['items_h'])) {
	$items_height = $_GET['items_h'];
}

$items_width = '';
if (isset($_GET['items_w'])) {
	$items_width = $_GET['items_w'];
}

$head_width = '';
if (isset($_GET['head_w'])) {
	$head_width = $_GET['head_w'];
}

$head_height = '';
if (isset($_GET['head_h'])) {
	$head_height = $_GET['head_h'];
}

// animation parameters

$animation = '';
if (isset($_GET['animation'])) {
	$animation = $_GET['animation'];
}

$direction = 'left'; 
if (!$horizontal) {
	$direction = 'up';
}
if (isset($_GET['dir'])) {
	switch ($_GET['dir']) {
		case 'l' : 
			$direction = 'left'; 
			if (!$horizontal) {
				$direction = 'up';
			}
			break;
		case 'r' : 
			$direction = 'right'; 
			if (!$horizontal) {
				$direction = 'down';
			}
			break;
		case 't' : 
			$direction = 'up'; 
			if ($horizontal) {
				$direction = 'left';
			}
			break;
		case 'b' : 
			$direction = 'down';  
			if ($horizontal) {
				$direction = 'right';
			}
			break;
		default : 
			$direction = 'left';  
			if (!$horizontal) {
				$direction = 'up';
			}
			break;
	}
}

$auto = true;
if (isset($_GET['auto'])) {
	$auto = ($_GET['auto'] === '1') ? true : false;
}

$speed = '1000';
if (isset($_GET['speed'])) {
	$speed = $_GET['speed'];
}

$interval = '3000';
if (isset($_GET['interval'])) {
	$interval = $_GET['interval'];
}

$visibleatonce = '1';
if (isset($_GET['visible'])) {
	$visibleatonce = $_GET['visible'];
}

$moveatonce = $visibleatonce;
if (isset($_GET['move'])) {
	$moveatonce = $_GET['move'];
}

$num_links = '5';
if (isset($_GET['n_l'])) {
	$num_links = $_GET['n_l'];
}

$prev_label = '';
if (isset($_GET['prev'])) {
	$prev_label = $_GET['prev'];
}

$next_label = '';
if (isset($_GET['next'])) {
	$next_label = $_GET['next'];
}

$pages = false;
$arrows = false;
if (isset($_GET['page'])) {
	switch ($_GET['page']) {
		case 'p':
		case 's':
			$pages = true;
			break;
		case 'pn': 
			$arrows = true;
			break;
		case 'ppn':
		case 'psn':
			$arrows = true;
			$pages = true;
			break;
		default:
			$pages = false;
			$arrows = false;
			break;
	}
}

$position = 'below';
if (isset($_GET['pos'])) {
	$position = $_GET['pos'];
}

//

$warning_items = '';
$warnings = false;

//
 
if (!empty($animation)) {
	include 'animations/'.$animation.'/'.$animation.'.js.php';
}
?>
