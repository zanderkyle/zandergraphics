<?php
/**
 * @copyright	Copyright (C) 2011 Simplify Your Web, Inc. All rights reserved.
 * @license		GNU General Public License version 3 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

// Include the syndicate functions only once
require_once (dirname(__FILE__).'/helpers/helper.php');

jimport('joomla.filesystem.file');
jimport('syw.k2');

$list = null;

$datasource = $params->get('datasource', 'articles');
switch ($datasource) {
	case 'k2':
		if (SYWK2::exists()) {
			require_once (dirname(__FILE__).'/helpers/helper_k2.php');
			$list = modLatestNewsEnhancedExtendedHelperK2::getList($params, $module);
		} else {
			return; // wrong selection since K2 is not installed
		}
		break;
	default: 
		require_once (dirname(__FILE__).'/helpers/helper_standard.php');
		$list = modLatestNewsEnhancedExtendedHelperStandard::getList($params, $module);
}

if (empty($list)) {	
	$nodata_message = trim($params->get('nodatamessage', ''));	
	if (!empty($nodata_message)) {
		require JModuleHelper::getLayoutPath('mod_latestnewsenhanced', $params->get('layout', 'default'));
	} else {
		return;
	}
} else {

	jimport('syw.utilities');
	jimport('syw.libraries');
	jimport('syw.image');
	jimport('syw.fonts');
	jimport('syw.cache');
	
	jimport('joomla.environment.browser');
	$browser = JBrowser::getInstance();
	$browser_name = $browser->getBrowser();
	$browser_version = $browser->getVersion();
	
	SYWFonts::loadIconFont();
	
	// parameters
	
	$class_suffix = $module->id;
	
	$urlPath = JURI::base().'modules/mod_latestnewsenhanced/';
	$doc = JFactory::getDocument();
	$app = JFactory::getApplication();
	
	$show_errors = $params->get('show_errors', 0);
	
	$items_align = $params->get('align', 'v'); 
	
	$items_height = trim($params->get('items_h', ''));
	$items_width = trim($params->get('items_w', ''));
	$item_width = trim($params->get('item_w', 100));
	$item_width_unit = $params->get('item_w_u', '%');
	$min_item_width = $params->get('min_item_w', '');
	
	if ($item_width_unit == '%') {
		if ($item_width <= 0 || $item_width > 100) {
			$item_width = 100;
		}
	} else {
		if ($item_width < 0) {
			$item_width = 0;
		}
	}
	
	$text_align = $params->get('text_align', 'r');
	$title_before_head = $params->get('title_before_head', false);
	$title_html_tag = $params->get('title_tag', '4');
	
	$link_label = trim($params->get('link', ''));
	
	$follow = $params->get('follow', true);
	
	$popup_width = $params->get('popup_x', 600);
	$popup_height = $params->get('popup_y', 500);
	
	$show_title = true;
	if (trim($params->get('letter_count_title', '')) == '0') {
		$show_title = false;
	}
	$force_title_one_line = $params->get('force_one_line', false);
	
	$show_category = false;
	$link_category = true;
	switch ($params->get('show_cat', 0)) {
		case 1: $show_category = true; break; // show and link
		case 2: $show_category = true; $link_category = false; break; // show
		default: break; // hide
	}
	
	$cat_link_text = trim($params->get('cat_link', ''));
	$consolidate_category = $params->get('consol_cat', 1);
	
	$head_type = $params->get('head_type', 'none');
	$head_width = $params->get('head_w', 64);
	$head_height = $params->get('head_h', 64);
	$maintain_height = $params->get('maintain_height', 0);
	
	$show_image = false;
	$show_calendar = false;
	if ($head_type == "image") {
		$show_image = true;
	} else if ($head_type == "calendar") {
		$show_calendar = true;
	}
	
	$show_link = false;
	$show_link_label = false;
	switch ($params->get('what_to_link', '')) {
		case 'title' :
			$show_link = true;
			break;
		case 'label' :
			$show_link_label = true;
			break;
		case 'both' :		
			$show_link = true;
			$show_link_label = true;
			break;
		default :
			break;
	}
	
	$info_block_placement = $params->get('ad_place', 1);
	$append_link = $params->get('append_link', 0);
	$overall_style = $params->get('overall_style', 'original');
	$keep_space = $params->get('keep_image_space', 1);
	$alignment = ($items_align == 'v') ? 'vertical' : 'horizontal';
	$clear_css_cache = $params->get('clear_css_cache', true);
	
	// read-more skinning
	
	$extrareadmorestyle = '';
	$extrareadmoreclass = 'btn';
	
	$read_more = $params->get('readmore_type', '');
	$extrareadmoreclass .= ' '.$read_more;
	
	$read_more_size = $params->get('readmore_size', '');
	if ($read_more_size == 'small') {
		$read_more_size = ' btn-small btn-sm'; // for Bootstrap 2.3 and 3.3
	} else if ($read_more_size == 'mini') {
		$read_more_size = ' btn-mini btn-xs'; // for Bootstrap 2.3 and 3.3
	}
	
	$extrareadmoreclass .= $read_more_size;
	
	$read_more_align = $params->get('readmore_align', '');
	$extrareadmoreclass .= ' '.$read_more_align;
	
	switch ($params->get('readmore_align', '')) {
		case 'left': $extrareadmorestyle = 'text-align:left'; break;
		case 'right': $extrareadmorestyle = 'text-align:right'; break;
		case 'center': $extrareadmorestyle = 'text-align:center'; break;
		case 'btn-block': $extrareadmoreclass .= ' btn-block'; break;
		default: break;
	}
	
	if (!empty($extrareadmorestyle)) {
		$extrareadmorestyle = ' style="'.$extrareadmorestyle.'"';
	}
	
	// end read-more skinning
	
	// start downgrading styles
	
	$leading_items_count = 0;
	$percentage_of_item_size = 100;	
	$remove_head = false;
	$remove_text = false;
	$remove_details = false;
	
	// end downgrading styles
	
	$iconfont_color = str_replace('#', '', trim($params->get('iconscolor', '#000000')));
	
	// parameters image
	
	if ($show_image) {
		$border_width_pic = $params->get('border_w', 0);
		$border_color_pic = str_replace('#', '', trim($params->get('border_c_pic', '#FFFFFF')));
		
		$shadow_width_pic = $params->get('sh_w_pic', 0);
		$shadow_type_pic = $params->get('sh_type', 's');
		
		$image_bgcolor = str_replace('#', '', trim($params->get('imagebgcolor', '')));
		
		$head_width = $head_width - $border_width_pic * 2;
		$head_height = $head_height - $border_width_pic * 2;
		
		$hover_effect = $params->get('hover_effect', 0);
		//$hover_effect_class = '';
		//if ($hover_effect) {
			//$hover_effect_class = ' shrink';
		//}
	}
	
	// parameters calendar
	
	if ($show_calendar) {	
		$calendar_style = $params->get('cal_style', 'original');
		$calendar_bg = $params->get('cal_bg', '');
		$font_calendar = $params->get('fontcalendar', '');
		
		$weekday_format = $params->get('fmt_w', 'D');
		$month_format = $params->get('fmt_m', 'M');
		$day_format = $params->get('fmt_d', 'd');
		$time_format = $params->get('t_format', 'H:i');				
	
		$color1 = str_replace('#', '', trim($params->get('c1', '#3D3D3D')));
		$color2 = str_replace('#', '', trim($params->get('c2', '#494949')));
		$color3 = str_replace('#', '', trim($params->get('c3', '#494949')));
		$bgcolor11 = str_replace('#', '', trim($params->get('bgc11', '')));
		$bgcolor12 = str_replace('#', '', trim($params->get('bgc12', '')));
		$bgcolor21 = str_replace('#', '', trim($params->get('bgc21', '')));
		$bgcolor22 = str_replace('#', '', trim($params->get('bgc22', '')));
		$bgcolor31 = str_replace('#', '', trim($params->get('bgc31', '')));
		$bgcolor32 = str_replace('#', '', trim($params->get('bgc32', '')));
	
		$shadow_w_cal = $params->get('sh_w', 0);
		$border_w_cal = $params->get('border_w_cal', 0);
		$border_r_cal = $params->get('border_r', 0);
		$border_c_cal = str_replace('#', '', trim($params->get('border_c_cal', '#000000')));
		$font_ref_cal = $params->get('f_r', 14);
	
		$position_1 = $params->get('pos_1', 'w');
		$position_2 = $params->get('pos_2', 'd');
		$position_3 = $params->get('pos_3', 'm');
		$position_4 = $params->get('pos_4', 'y');
		$position_5 = $params->get('pos_5', 't');
	
		$keys = array($position_1, $position_2, $position_3, $position_4, $position_5);
		$date_params_keys = array();
		$date_params_values = array();
	
		foreach ($keys as $key) {
			switch ($key) {
				case 'w' :
					$date_params_keys[] = 'weekday';
					break;
				case 'd' :
					$date_params_keys[] = 'day';
					break;
				case 'm' :
					$date_params_keys[] = 'month';
					break;
				case 'y' :
					$date_params_keys[] = 'year';
					break;
				case 't' :
					$date_params_keys[] = 'time';
					break;
				case 'e' :
					$date_params_keys[] = 'empty';
					break;
				default : $date_params_keys[] = '';
				break;
			}
		}
	}
	
	// animation / pagination
	
	$pagination = $params->get('pagination', '');
	
	$animation = '';
	if (!empty($pagination)) { // pagination only
		$animation = 'justpagination';
	}
	
	$pagination_position_type = $params->get('pagination_pos', 'below');
	$pagination_align = $params->get('pagination_align', 'center');
	$extra_pagination_classes = $params->get('pagination_style', '');
	
	$pagination_position_top = 'top';
	$pagination_position_bottom = 'bottom';
	$pagination_specific_size = 1;
	$pagination_size = '';
	$pagination_offset = 0;
	
	if ($animation) {
	
		JHtml::_('jquery.framework');
			
		modLatestNewsEnhancedExtendedHelper::loadLibrary($animation);
		
		if (!empty($pagination)) {
			
			if ($pagination_position_type == 'around') {
				if ($items_align == 'v') {
					$pagination_position_top = 'up';
					$pagination_position_bottom = 'down';
				} else {
					$pagination_position_top = 'left';
					$pagination_position_bottom = 'right';
				}		
			}
		}
				
		$items_per_slide = $params->get('visible_items', 1);
		$num_links = $params->get('num_links', 5);
			
		$prev_type = $params->get('prev_type', '');
		$label_prev = $prev_type == 'prev' ? JText::_('JPREV') : ($prev_type == 'label' ? trim($params->get('label_prev', '')) : '');
		
		$next_type = $params->get('next_type', '');
		$label_next = $next_type == 'next' ? JText::_('JNEXT') : ($next_type == 'label' ? trim($params->get('label_next', '')) : '');
		
		$pagination_size = $params->get('pagination_size', '');
		if (!empty($extra_pagination_classes)) {
			if ($pagination_size == 'small') {
				$extra_pagination_classes .= ' pagination-small pagination-sm'; // for Bootstrap 2.3 and 3.3
			} else if ($pagination_size == 'mini') {
				$extra_pagination_classes .= ' pagination-mini pagination-sm'; // for Bootstrap 2.3 and 3.3 (no mini)
			} 
		}
		$pagination_specific_size = $params->get('pagination_specific_size', 1);
		$pagination_offset = $params->get('pagination_offset', 0);
	
		$request_path = 'animationmaster.js.php?security='.defined('_JEXEC').'&amp;suffix='.$class_suffix.'&amp;animation='.$animation;
		$request_path .= '&amp;visible='.$items_per_slide;
		if (!empty($pagination)) {
			$request_path .= '&amp;page='.$pagination;
			$request_path .= '&amp;prev='.$label_prev;
			$request_path .= '&amp;next='.$label_next;
			$request_path .= '&amp;n_l='.$num_links;
			$request_path .= '&amp;pos='.$pagination_position_type;
		}
		$request_path .= '&amp;conf='.$items_align.'&amp;head_w='.$head_width.'&amp;head_h='.$head_height;
		$request_path .= '&amp;item_w='.$item_width;
		if ($item_width_unit != '%') {
			$request_path .= '&amp;item_w_u='.$item_width_unit;
		}
		if (!empty($items_height)) {
			$request_path .= '&amp;items_h='.$items_height;
		}
		if (!empty($items_width)) {
			$request_path .= '&amp;items_w='.$items_width;
		}
			
		// caching the javascript
		$result = SYWCache::getCachedFilePath('modules/mod_latestnewsenhanced/', $request_path, 'animationmaster_'.$module->id.'.js', $clear_css_cache);
		$doc->addScript($result);
	} else {
		// remove animationmaster.js if it exists
		if (JFile::exists(JPATH_ROOT.'/modules/mod_latestnewsenhanced/animationmaster_'.$module->id.'.js')) {
			JFile::delete(JPATH_ROOT.'/modules/mod_latestnewsenhanced/animationmaster_'.$module->id.'.js');
		}
	}
	
	if (empty($animation) || $animation == 'justpagination') {
	
		// add items responsiveness	when not in an animation other than pagination
	
		if ($item_width_unit == '%' && !empty($min_item_width)) {
		
			JHtml::_('jquery.framework');
		
			$request_path = 'stylemaster.js.php?security='.defined('_JEXEC').'&amp;suffix='.$class_suffix;
			$request_path .= '&amp;item_w='.$item_width;
			$request_path .= '&amp;min_w='.$min_item_width;
		
			// caching the javascript
			$result = SYWCache::getCachedFilePath('modules/mod_latestnewsenhanced/', $request_path, 'stylemaster_'.$module->id.'.js', $clear_css_cache);
			$doc->addScript($result);
		}
	} else {
		// remove stylemaster.js if it exists
		if (JFile::exists(JPATH_ROOT.'/modules/mod_latestnewsenhanced/stylemaster_'.$module->id.'.js')) {
			JFile::delete(JPATH_ROOT.'/modules/mod_latestnewsenhanced/stylemaster_'.$module->id.'.js');
		}
	}
	
	// extra styles
	
	$extra_styles = trim($params->get('style_overrides', ''));
	if (!empty($extra_styles)) {
		$extra_styles .= ' ';
	}
	
	$extracalendarclass = 'noimage';
	if ($show_calendar && $calendar_bg) {
		$extra_styles .= "#lnee_".$class_suffix." .newshead .calendar.image {";
		$extra_styles .= "background: transparent url(".JURI::base().$calendar_bg.") top center no-repeat !important;";
		$extra_styles .= "} ";
	
		$extracalendarclass = 'image';
	} 
	
	if ($show_calendar && !empty($font_calendar)) {
		
		$font_calendar = str_replace('\'', '"', $font_calendar); // " lost, replaced by '
		
		$google_font = SYWUtilities::getGoogleFont($font_calendar); // get Google font, if any
		if ($google_font) {
			$doc->addStyleSheet('http://fonts.googleapis.com/css?family='.SYWUtilities::getSafeGoogleFont($google_font));
		}
	
		$extra_styles .= '#lnee_'.$class_suffix.' .calendar {';
		$extra_styles .= 'font-family: '.$font_calendar.' !important;';
		$extra_styles .= '} ';
	}
	
	if (!empty($extra_styles)) {
		$extra_styles = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $extra_styles); // minify the CSS code
	}
	
	// style master file (compressed CSS)
	
	$request_path = 'stylemaster.css.php?security='.defined('_JEXEC').'&amp;suffix='.$class_suffix.'&amp;overall='.$overall_style;
	
	if ($force_title_one_line) {
		$request_path .= '&amp;ftol=1';
	}
	
	$request_path .= '&amp;item_w='.$item_width;
	if ($item_width_unit != '%') {
		$request_path .= '&amp;item_w_u='.$item_width_unit;
	}
	
	if ($percentage_of_item_size > 0 && $percentage_of_item_size < 100 && $leading_items_count > 0) {
		$request_path .= '&amp;perc_item_w='.$percentage_of_item_size;
	}
	
	if (!empty($items_height)) {
		$request_path .= '&amp;items_h='.$items_height;
	}
	if (!empty($items_width)) {
		$request_path .= '&amp;items_w='.$items_width;
	}
	
	$request_path .= '&amp;head_w='.$head_width.'&amp;head_h='.$head_height;
	
	if ($maintain_height) {
		$request_path .= '&amp;mh=1';
	}
	
	$font_ref_body = $params->get('f_r_body', 14);
	if ($font_ref_body > 0) {
		$request_path .= "&amp;font_ref=".$font_ref_body;
	}
	
	if ($show_image || $show_calendar) {
		$wrap_text = $params->get('wrap', 0);
		if ($wrap_text) {
			$request_path .= "&amp;wrap=1";
		}
	}
	
	if ($show_image) {	
		if ($border_width_pic > 0) {
			$request_path .= '&amp;b_w_pic='.$border_width_pic;
			$request_path .= '&amp;b_c_pic='.$border_color_pic;
		}
		if (!empty($image_bgcolor)) {
			$request_path .= '&amp;bgc='.$image_bgcolor;
		}
		if ($shadow_width_pic > 0) {
			$request_path .= '&amp;sh_w_pic='.$shadow_width_pic;
		}
	}
	
	if ($show_calendar) {
		$request_path .= '&amp;calendar='.$calendar_style;
		$request_path .= '&amp;c1='.$color1.'&amp;c2='.$color2.'&amp;c3='.$color3.'&amp;font_ref_cal='.$font_ref_cal;
		if (!empty($bgcolor11)) {
			$request_path .= '&amp;bgc11='.$bgcolor11;
		}
		if (!empty($bgcolor12)) {
			$request_path .= '&amp;bgc12='.$bgcolor12;
		}
		if (!empty($bgcolor21)) {
			$request_path .= '&amp;bgc21='.$bgcolor21;
		}
		if (!empty($bgcolor22)) {
			$request_path .= '&amp;bgc22='.$bgcolor22;
		}
		if (!empty($bgcolor31)) {
			$request_path .= '&amp;bgc31='.$bgcolor31;
		}
		if (!empty($bgcolor32)) {
			$request_path .= '&amp;bgc32='.$bgcolor32;
		}
		if ($border_w_cal > 0) {
			$request_path .= '&amp;b_w='.$border_w_cal;
			$request_path .= '&amp;b_c='.$border_c_cal;
		}
		if ($border_r_cal > 0) {
			$request_path .= '&amp;b_r='.$border_r_cal;
		}	
		if ($shadow_w_cal > 0) {
			$request_path .= '&amp;sh_w_cal='.$shadow_w_cal;
		}
	}
	
	if ($animation) {
		$request_path .= '&amp;animation='.$animation;
		if (!empty($pagination)) {
			$request_path .= '&amp;page='.$pagination;
			if ($pagination_align != 'center') {
				$request_path .= '&amp;palign='.$pagination_align;
			}
			$request_path .= '&amp;ppos='.$pagination_position_type;
			if ($pagination_specific_size != 1 && $pagination_size == 'specific') {
				$request_path .= '&amp;ps='.$pagination_specific_size;
			}
			if ($pagination_offset > 0) {
				$request_path .= '&amp;po='.$pagination_offset;
			}
		}
	}
	
	$request_path .= '&amp;if_c='.$iconfont_color;
	
	// caching of the stylesheet
	$result = SYWCache::getCachedFilePath('modules/mod_latestnewsenhanced/', $request_path, 'stylemaster_'.$module->id.'.css', $clear_css_cache, $extra_styles);
	$doc->addStyleSheet($result);
	if (strpos($result, '.php') !== false && !empty($extra_styles)) {
		$doc->addStyleDeclaration($extra_styles);
	}
	
	modLatestNewsEnhancedExtendedHelper::loadCommonStylesheet();
	
	// handle high resolution images
	
	// if ($show_image) {
	// 	JHtml::_('jquery.framework');
	// 	SYWLibraries::load_high_res_images('#lnee_'.$module->id.' .innerpicture img'); // use .lnee to handle all instances at once
	// }
	
	// call the layout
	
	require JModuleHelper::getLayoutPath('mod_latestnewsenhanced', $params->get('layout', 'default'));
}
?>
