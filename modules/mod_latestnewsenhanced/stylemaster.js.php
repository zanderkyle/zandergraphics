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

// parameters

$item_width = 100; // %
if (isset($_GET['item_w'])) {
	$item_width = (int)$_GET['item_w'];
}

$min_width = 0; // px
if (isset($_GET['min_w'])) {
	$min_width = (int)$_GET['min_w'];
} 

$margin_min_width = 3; // px

$margin_error = 1; // px

$jquery_var = 'jQuery';
?>

<?php echo $jquery_var ?>(document).ready(function ($) {

    var item = $('#lnee_<?php echo $suffix ?> .latestnews-item');
    var itemlist = $('#lnee_<?php echo $suffix ?> .latestnews-items');
    
	if (item != null) {
        resize_news();
    }

	$(window).resize(function() {
        if (item != null) {
            resize_news();
        }
    });

    function resize_news() {

        var container_width = itemlist.width();
        
        var news_per_row = 1;
	    	
    	var news_width = Math.floor(container_width * <?php echo $item_width ?> / 100);
         
	    if (news_width < <?php echo $min_width ?>) {
	    	    
	    	if (container_width < <?php echo $min_width ?>) {
	    		news_width = container_width;
	    	} else {
	    		news_width = <?php echo $min_width ?>;
	    	}	    	
        }
        
        if (<?php echo $item_width ?> <= 50) {
	        news_per_row = Math.floor(container_width / news_width);   
	        
	        if (news_per_row == 1) {
	        	news_width = container_width;
	        } else {
	        	news_width = Math.floor(container_width / news_per_row) - (<?php echo $margin_min_width ?> * news_per_row);
	        }    
	    } else { // we can never have 2 items on the same row	    	
	        news_width = container_width;
	    }
        
        var left_for_margins = container_width - (news_per_row * news_width);
		var margin_width = Math.floor(left_for_margins / (news_per_row * 2)) - <?php echo $margin_error ?>;        
        
        item.each(function() {
            $(this).width(news_width + 'px');
            $(this).css('margin-left', margin_width +'px');
	        $(this).css('margin-right', margin_width +'px');	        
        });
	}

});
