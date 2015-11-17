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
	
#lnee_<?php echo $suffix; ?> ul.latestnews-items li.active {
	opacity: 0.5;				
	-ms-filter:"progid:DXImageTransform.Microsoft.Alpha(Opacity=50)"; /* IE8 */
}

#lnee_<?php echo $suffix; ?> ul.latestnews-items li.full {	
	<?php if ($item_width_unit == '%' && $item_width > 50) : ?>
		margin-right: <?php echo ((100 - $item_width) / 2); ?>%;
		margin-left: <?php echo ((100 - $item_width) / 2); ?>%;
	<?php endif; ?>
}

#lnee_<?php echo $suffix; ?>.horizontal ul.latestnews-items li.full {	
	float: left;
}

#lnee_<?php echo $suffix; ?> ul.latestnews-items li.downgraded {
	border-top: 1px solid #CCCCCC;
	padding-top: 5px;
	margin-top: 5px;

	width: <?php echo $downgraded_item_width; ?><?php echo $downgraded_item_width_unit; ?>;
}

	#lnee_<?php echo $suffix; ?> .innernews {
		padding: 2px;
	}
		
		#lnee_<?php echo $suffix; ?> .newshead.picturetype {
			position: relative;
			max-width: 100%;
		}
		
		#lnee_<?php echo $suffix; ?> .head_left .newshead {
			float: left;
			margin: 0 8px 0 0;
		}
		
		#lnee_<?php echo $suffix; ?> .head_right .newshead {
			float: right;
			margin: 0 0 0 8px;
		}
		
			#lnee_<?php echo $suffix; ?> .newshead .picture,
			#lnee_<?php echo $suffix; ?> .newshead .nopicture {
				<?php if ($pic_border_width > 0) : ?>
					border: <?php echo $pic_border_width ?>px solid <?php echo $pic_border_color ?>;						
					-webkit-box-sizing: border-box;
					-moz-box-sizing: border-box;
					box-sizing: border-box;
				<?php endif; ?>
			}

		#lnee_<?php echo $suffix; ?> .newsinfooverhead {
			display: none;
		}
			
		<?php if (!$wrap) : ?>					
			#lnee_<?php echo $suffix; ?> .newsinfo {
				overflow: hidden;
			}
			
			#lnee_<?php echo $suffix; ?> .head_left .newsinfo.noimagespace {
				margin-left: 0 !important;
			}
			
			#lnee_<?php echo $suffix; ?> .head_right .newsinfo.noimagespace {
				margin-right: 0 !important;
			}			
		<?php endif; ?>
		
			#lnee_<?php echo $suffix; ?> .newstitle {
				font-weight: bold;
			}
			
			#lnee_<?php echo $suffix; ?> .newsextra {
				font-size: 0.8em;
			}

				


/* CSS3 animations */

/* Shrink */
#lnee_<?php echo $suffix; ?> .shrink {
	/*display: inline-block;*/
	-webkit-transition-duration: 0.3s;
	transition-duration: 0.3s;
	-webkit-transition-property: transform;
	transition-property: transform;
	-webkit-transform: translateZ(0);
	transform: translateZ(0);
	box-shadow: 0 0 1px rgba(0, 0, 0, 0);
}

#lnee_<?php echo $suffix; ?> .shrink:hover, 
#lnee_<?php echo $suffix; ?> .shrink:focus, 
#lnee_<?php echo $suffix; ?> .shrink:active {
	-webkit-transform: scale(0.9);
	-ms-transform: scale(0.9);
	transform: scale(0.9);
}				
				
				
				
				
				
#lnee_<?php echo $suffix; ?> .shadow.simple .picturetype {
	padding: <?php echo (intval($pic_shadow_width) + 2) ?>px;
	box-sizing: border-box; /* should use padding-box */
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
}

#lnee_<?php echo $suffix; ?> .shadow.simple .picture,
#lnee_<?php echo $suffix; ?> .shadow.simple .nopicture {
	box-shadow: 0 0 <?php echo $pic_shadow_width; ?>px rgba(0, 0, 0, 0.8);
	-moz-box-shadow: 0 0 <?php echo $pic_shadow_width; ?>px rgba(0, 0, 0, 0.8);
	-webkit-box-shadow: 0 0 <?php echo $pic_shadow_width; ?>px rgba(0, 0, 0, 0.8);
	/* IE 7 AND 8 DO NOT SUPPORT BLUR PROPERTY OF SHADOWS */
}




#lnee_<?php echo $suffix; ?> .shadow.vshapeleft .picturetype,
#lnee_<?php echo $suffix; ?> .shadow.vshaperight .picturetype {
	margin-bottom: <?php echo $pic_shadow_width; ?>px;
}

#lnee_<?php echo $suffix; ?> .shadow.vshapeleft .picturetype:before, 
#lnee_<?php echo $suffix; ?> .shadow.vshaperight .picturetype:after {
    
    content: "";
    
    position: absolute;
    top: 80%; 
    left: 20px; 
    bottom: <?php echo $pic_shadow_width; ?>px;  
    width: 50%;
    
    -webkit-transform: rotate(-3deg);
	-moz-transform: rotate(-3deg);
	-o-transform: rotate(-3deg);
	-ms-transform: rotate(-3deg);
	transform: rotate(-3deg);
    
    background-color: transparent;
	-webkit-box-shadow: 0 <?php echo $pic_shadow_width; ?>px 10px #777;
	-moz-box-shadow: 0 <?php echo $pic_shadow_width; ?>px 10px #777;
	box-shadow: 0 <?php echo $pic_shadow_width; ?>px 10px #777;
}

#lnee_<?php echo $suffix; ?> .shadow.vshaperight .picturetype:after {
    left: auto;
    right: 20px;
    
    -webkit-transform: rotate(3deg);
	-moz-transform: rotate(3deg);
	-o-transform: rotate(3deg);
	-ms-transform: rotate(3deg);
	transform: rotate(3deg);
}

#lnee_<?php echo $suffix; ?> .shadow.vshapeleft .picture,
#lnee_<?php echo $suffix; ?> .shadow.vshaperight .picture,
#lnee_<?php echo $suffix; ?> .shadow.vshapeleft .nopicture,
#lnee_<?php echo $suffix; ?> .shadow.vshaperight .nopicture {
	z-index: 1;
	position: relative;
}
