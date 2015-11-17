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
				
	#lnee_<?php echo $suffix; ?> ul.latestnews-items {	    
	    <?php if ($items_height) : ?>
	    	height: <?php echo $items_height; ?>px;
	    	overflow-y: auto;
	    <?php endif; ?>
	    <?php if ($items_width) : ?>
	    	<?php if ($item_width_unit == 'px') : ?>
	    		min-width: <?php echo $item_width; ?>px;	
	    	<?php endif; ?>
	    	max-width: <?php echo $items_width; ?>px;	    
	    	margin-left: auto;
	    	margin-right: auto;
	    <?php endif; ?>
	}
	
	#lnee_<?php echo $suffix; ?> ul.latestnews-items.degraded {	}
	
	#lnee_<?php echo $suffix; ?> ul.latestnews-items li {
		<?php if ($font_ref_body > 0) : ?>
			font-size: <?php echo $font_ref_body; ?>px; 
		<?php else : ?>
			font-size: medium;
		<?php endif; ?>
    	width: <?php echo $item_width; ?><?php echo $item_width_unit; ?>;
    	<?php if ($item_width_unit == '%') : ?>
    		margin-left: <?php echo $margin_in_perc; ?>%;
			margin-right: <?php echo $margin_in_perc; ?>%;
    	<?php else : ?>
    		margin-left: auto;
    		margin-right: auto;
    	<?php endif; ?>
	}
	
		#lnee_<?php echo $suffix; ?> .news {
			<?php if ($item_width_unit == '%') : ?>
				width: 100%;
			<?php else : ?>
				width: <?php echo $item_width; ?>px;
			<?php endif; ?>
		}
				
				#lnee_<?php echo $suffix; ?> .newshead.calendartype {
					font-size: <?php echo $font_ref_cal; ?>px; /* the base size for the calendar */
				}
				
					#lnee_<?php echo $suffix; ?> .newshead .picture,
					#lnee_<?php echo $suffix; ?> .newshead .nopicture {
						<?php if ($head_width > 0) : ?>
							max-width: <?php echo $head_width; ?>px;
						<?php endif; ?>
						<?php if ($head_height > 0) : ?>
							max-height: <?php echo $head_height; ?>px;
						<?php endif; ?>
						<?php if ($maintain_height && $head_height > 0) : ?>
							height: <?php echo $head_height; ?>px;
							min-height: <?php echo $head_height; ?>px;
						<?php endif; ?>
					}
					
					#lnee_<?php echo $suffix; ?> .newshead .nodate {
						width: <?php echo $head_width; ?>px;
						max-width: <?php echo $head_width; ?>px;
						height: <?php echo $head_height; ?>px;
						min-height: <?php echo $head_height; ?>px;
					}
					
					#lnee_<?php echo $suffix; ?> .newshead .calendar {
						width: <?php echo $head_width; ?>px;
						max-width: <?php echo $head_width; ?>px;
					}
					
					#lnee_<?php echo $suffix; ?> .newshead .calendar.image {
						height: <?php echo $head_height; ?>px;
					}
	
					#lnee_<?php echo $suffix; ?> .newshead .picture,
					#lnee_<?php echo $suffix; ?> .newshead .nopicture {
						background-color: <?php echo $bgcolor; ?>;
					}
					
					#lnee_<?php echo $suffix; ?> .newshead .nopicture span {
						<?php if ($head_width > 0) : ?>
							width: <?php echo $head_width; ?>px;
						<?php endif; ?>
						<?php if ($head_height > 0) : ?>
							height: <?php echo $head_height; ?>px;
						<?php endif; ?>
					}
				
					<?php if ($force_title_one_line) : ?>						
						#lnee_<?php echo $suffix; ?> .newstitle span {
			    			display: block; 
			    			white-space: nowrap; 
			    			text-overflow: ellipsis; 
			    			overflow: hidden; 
						}
					<?php endif; ?>
					
						#lnee_<?php echo $suffix; ?> .newsextra [class^="SYWicon-"], 
						#lnee_<?php echo $suffix; ?> .newsextra [class*=" SYWicon-"] {
						    color: <?php echo $iconfont_color; ?>;
						}
