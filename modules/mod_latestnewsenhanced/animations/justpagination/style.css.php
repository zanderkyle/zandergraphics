<?php 
/**
* @copyright	Copyright (C) 2011 Simplify Your Web, Inc. All rights reserved.
* @license		GNU General Public License version 3 or later; see LICENSE.txt
*/

// No direct access to this file
defined('_JEXEC') or die;

// Explicitly declare the type of content
header("Content-type: text/css; charset=UTF-8");
?>

#lnee_<?php echo $suffix; ?> .items_pagination {
	<?php if ($position_pagination == 'title') : ?>
		display: inline-block;
		<?php if ($align_pagination == 'left' || $align_pagination == 'right') : ?>
			float: <?php echo $align_pagination; ?>;
		<?php endif; ?>
	<?php else : ?>
		display: block;
		text-align: <?php echo $align_pagination; ?>;
	<?php endif; ?>
	font-size: <?php echo $pagination_size; ?>em;
}

#lnee_<?php echo $suffix; ?> .items_pagination ul {
	margin: 0;
	padding: 0;
}

#lnee_<?php echo $suffix; ?> .items_pagination li {
	display: inline;
	list-style: none;
	cursor: pointer;
}

#lnee_<?php echo $suffix; ?>.horizontal .items_pagination.left,
#lnee_<?php echo $suffix; ?>.horizontal .items_pagination.right {
	position: absolute;
	top: <?php echo $pagination_offset; ?>px;
	z-index: 100;
	text-align: center;
}
	
#lnee_<?php echo $suffix; ?>.horizontal .items_pagination.right {
	right: 0;
}	

#lnee_<?php echo $suffix; ?> .items_pagination.top,
#lnee_<?php echo $suffix; ?> .items_pagination.up {
	margin-bottom: <?php echo $pagination_offset; ?>px;
}

#lnee_<?php echo $suffix; ?> .items_pagination.bottom,
#lnee_<?php echo $suffix; ?> .items_pagination.down {
	margin-top: <?php echo $pagination_offset; ?>px;
}	

#lnee_<?php echo $suffix; ?> .items_pagination.up .page_link,
#lnee_<?php echo $suffix; ?> .items_pagination.down .page_link,
#lnee_<?php echo $suffix; ?> .items_pagination.left .page_link,
#lnee_<?php echo $suffix; ?> .items_pagination.right .page_link,
#lnee_<?php echo $suffix; ?> .items_pagination.up .ellipse,
#lnee_<?php echo $suffix; ?> .items_pagination.down .ellipse,
#lnee_<?php echo $suffix; ?> .items_pagination.left .ellipse,
#lnee_<?php echo $suffix; ?> .items_pagination.right .ellipse {
	display: none !important;
}

#lnee_<?php echo $suffix; ?> .items_pagination.up .next_link,
#lnee_<?php echo $suffix; ?> .items_pagination.left .next_link {
	display: none;
}

#lnee_<?php echo $suffix; ?> .items_pagination.down .previous_link,
#lnee_<?php echo $suffix; ?> .items_pagination.right .previous_link {
	display: none;
}

#lnee_<?php echo $suffix; ?> .items_pagination a {
	margin: 0 5px;
}

#lnee_<?php echo $suffix; ?> .items_pagination.pagination a {
	margin: 0;
}

#lnee_<?php echo $suffix; ?> .items_pagination a:hover,
#lnee_<?php echo $suffix; ?> .items_pagination a:focus {
	text-decoration: none;
}

#lnee_<?php echo $suffix; ?> .items_pagination a.no_more {
	color: #999999;
	cursor: default;
}

<?php if ($symbols) : ?>
	#lnee_<?php echo $suffix; ?> .items_pagination .page_link a:before {
		font-family: 'SYWfont';
		speak: none;
		font-style: normal;
		font-weight: normal;
		font-variant: normal;
		text-transform: none;
		line-height: 1;
	
		/* Better Font Rendering */
		-webkit-font-smoothing: antialiased;
		-moz-osx-font-smoothing: grayscale;
		
		content: "\e817";		
	}
	
	#lnee_<?php echo $suffix; ?> .items_pagination .page_link.active_page a:before {
		font-family: 'SYWfont';
		speak: none;
		font-style: normal;
		font-weight: normal;
		font-variant: normal;
		text-transform: none;
		line-height: 1;
	
		/* Better Font Rendering */
		-webkit-font-smoothing: antialiased;
		-moz-osx-font-smoothing: grayscale;
		
		content: "\e818";		
	}
<?php endif; ?>
		
#lnee_<?php echo $suffix; ?> .items_pagination .page_link.active_page {
	<?php if (!$symbols) : ?>
		text-decoration: underline;
	<?php endif; ?>
}

#lnee_<?php echo $suffix; ?> .items_pagination .page_link span {
	<?php if ($symbols) : ?>
		display: none;
	<?php endif; ?>
}

<?php if ($arrows && !$pages) : ?>
	#lnee_<?php echo $suffix; ?> .items_pagination .page_link,
	#lnee_<?php echo $suffix; ?> .items_pagination .ellipse {
		display: none !important;
	}
<?php endif; ?>

/* extra bootstrap for 'around' positions */

#lnee_<?php echo $suffix; ?> .items_pagination.pagination.left ul > li:first-child > a,
#lnee_<?php echo $suffix; ?> .items_pagination.pagination.left ul > li:first-child > span,
#lnee_<?php echo $suffix; ?> .items_pagination.pagination.up ul > li:first-child > a,
#lnee_<?php echo $suffix; ?> .items_pagination.pagination.up ul > li:first-child > span {
	border-right-width: 1px;
	-webkit-border-top-right-radius: 4px;
	-moz-border-radius-topright: 4px;
	border-top-right-radius: 4px;
	-webkit-border-bottom-right-radius: 4px;
	-moz-border-radius-bottomright: 4px;
	border-bottom-right-radius: 4px;
}

#lnee_<?php echo $suffix; ?> .items_pagination.pagination-mini.left ul > li:first-child > a,
#lnee_<?php echo $suffix; ?> .items_pagination.pagination-mini.left ul > li:first-child > span,
#lnee_<?php echo $suffix; ?> .items_pagination.pagination-small.left ul > li:first-child > a,
#lnee_<?php echo $suffix; ?> .items_pagination.pagination-small.left ul > li:first-child > span,
#lnee_<?php echo $suffix; ?> .items_pagination.pagination-sm.left ul > li:first-child > a,
#lnee_<?php echo $suffix; ?> .items_pagination.pagination-sm.left ul > li:first-child > span,
#lnee_<?php echo $suffix; ?> .items_pagination.pagination-mini.up ul > li:first-child > a,
#lnee_<?php echo $suffix; ?> .items_pagination.pagination-mini.up ul > li:first-child > span,
#lnee_<?php echo $suffix; ?> .items_pagination.pagination-small.up ul > li:first-child > a,
#lnee_<?php echo $suffix; ?> .items_pagination.pagination-small.up ul > li:first-child > span,
#lnee_<?php echo $suffix; ?> .items_pagination.pagination-sm.up ul > li:first-child > a,
#lnee_<?php echo $suffix; ?> .items_pagination.pagination-sm.up ul > li:first-child > span {
	-webkit-border-top-right-radius: 3px;
	-moz-border-radius-topright: 3px;
	border-top-right-radius: 3px;
	-webkit-border-bottom-right-radius: 3px;
	-moz-border-radius-bottomright: 3px;
	border-bottom-right-radius: 3px;
}

#lnee_<?php echo $suffix; ?> .items_pagination.pagination.right ul > li:last-child > a,
#lnee_<?php echo $suffix; ?> .items_pagination.pagination.right ul > li:last-child > span,
#lnee_<?php echo $suffix; ?> .items_pagination.pagination.down ul > li:last-child > a,
#lnee_<?php echo $suffix; ?> .items_pagination.pagination.down ul > li:last-child > span {
	border-left-width: 1px;
	-webkit-border-top-left-radius: 4px;
	-moz-border-radius-topleft: 4px;
	border-top-left-radius: 4px;
	-webkit-border-bottom-left-radius: 4px;
	-moz-border-radius-bottomleft: 4px;
	border-bottom-left-radius: 4px;
}

#lnee_<?php echo $suffix; ?> .items_pagination.pagination-mini.right ul > li:last-child > a,
#lnee_<?php echo $suffix; ?> .items_pagination.pagination-mini.right ul > li:last-child > span,
#lnee_<?php echo $suffix; ?> .items_pagination.pagination-small.right ul > li:last-child > a,
#lnee_<?php echo $suffix; ?> .items_pagination.pagination-small.right ul > li:last-child > span,
#lnee_<?php echo $suffix; ?> .items_pagination.pagination-sm.right ul > li:last-child > a,
#lnee_<?php echo $suffix; ?> .items_pagination.pagination-sm.right ul > li:last-child > span,
#lnee_<?php echo $suffix; ?> .items_pagination.pagination-mini.down ul > li:last-child > a,
#lnee_<?php echo $suffix; ?> .items_pagination.pagination-mini.down ul > li:last-child > span,
#lnee_<?php echo $suffix; ?> .items_pagination.pagination-small.down ul > li:last-child > a,
#lnee_<?php echo $suffix; ?> .items_pagination.pagination-small.down ul > li:last-child > span,
#lnee_<?php echo $suffix; ?> .items_pagination.pagination-sm.down ul > li:last-child > a,
#lnee_<?php echo $suffix; ?> .items_pagination.pagination-sm.down ul > li:last-child > span {
	-webkit-border-top-left-radius: 3px;
	-moz-border-radius-topleft: 3px;
	border-top-left-radius: 3px;
	-webkit-border-bottom-left-radius: 3px;
	-moz-border-radius-bottomleft: 3px;
	border-bottom-left-radius: 3px;
}
