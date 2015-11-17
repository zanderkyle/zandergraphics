<?php
/**
 * @copyright	Copyright (C) 2011 Simplify Your Web, Inc. All rights reserved.
 * @license		GNU General Public License version 3 or later; see LICENSE.txt
 */

// no direct access
defined( '_JEXEC' ) or die;

// Grab module id from the request
$suffix = $_GET['suffix'];

$position = '';
if (isset($_GET['pos'])) {
	$position = $_GET['pos'];
}

$extra_classes = '';
if (isset($_GET['classes'])) {
	$extra_classes = ' '.$_GET['classes'];
}
?>

<div class="items_pagination<?php echo $extra_classes; ?><?php echo empty($position) ? '' : ' '.$position; ?>"></div>
