<?php
/**
 * @copyright	Copyright (C) 2011 Simplify Your Web, Inc. All rights reserved.
 * @license		GNU General Public License version 3 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

jimport('syw.image');
jimport('syw.utilities');
jimport('syw.libraries');
jimport('syw.k2');

jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');
jimport('joomla.utilities.date');
jimport('cms.html.string');

class modLatestNewsEnhancedExtendedHelper
{	
	static $commonStylesLoaded = false;

	static function getImageSrcFromArticle($introtext, $fulltext = '') {
	
		preg_match_all('#<img[^>]*>#i', $introtext, $img_result); // finds all images in the introtext
		if (empty($img_result[0][0]) && !empty($fulltext)) {	// maybe there are images in the fulltext...
			preg_match_all('#<img[^>]*>#i', $fulltext, $img_result); // finds all images in the fulltext
		}
	
		// TODO: if image too small (like a dot for empty space in J! 1.5), go to the next one
	
		if (!empty($img_result[0][0])) { // $img_result[0][0] is the first image found
			preg_match('/(src)=("[^"]*")/i', $img_result[0][0], $src_result); // get the src attribute
			return trim($src_result[2], '"');
		}
	
		return null;
	}
	
	/**
	* Create the image tag
	*/
	static function getImageTag($alt, $module_id, $article_id, $imagesrc, $tmp_path, $clear_cache, $head_width, $head_height, $crop_picture, $image_quality_array, $filter)
	{		
		$result = array('', null); // image tag and error
		
		$extensions = get_loaded_extensions();
		if (!in_array('gd', $extensions)) {
			// missing gd library
			$result[0] = '<img alt="'.$alt.'" src="'.$imagesrc.'" />';
			$result[1] = JText::_('MOD_LATESTNEWSENHANCEDEXTENDED_GD_NOTLOADED');
		} else if ($head_width <= 0 && $head_height <= 0) { // keep original image
			$result[0] = '<img alt="'.$alt.'" src="'.$imagesrc.'" />';
		} else {
			// URL works only if 'allow url fopen' is 'on', which is a security concern
			// retricts images to the ones found on the site, external URLs are not allowed (for security purposes)
			if (substr_count($imagesrc, 'http') <= 0) { // if the image is internal
				if (substr($imagesrc, 0, 1) == '/') {
					// take the slash off
					$imagesrc = ltrim($imagesrc, '/');
				}
			} else {
				$base = JURI::base(); // JURI::base() is http://www.mysite.com/subpath/
				$imagesrc = str_ireplace($base, '', $imagesrc);
			}
		
			// we end up with all $imagesrc paths as 'images/...'
			// if not, the URL was from an external site
			
			if (substr_count($imagesrc, 'http') > 0) { // we have an external URL
				if (!ini_get('allow_url_fopen')) {
					$result[1] = JText::sprintf('MOD_LATESTNEWSENHANCEDEXTENDED_ERROR_EXTERNALURLNOTALLOWED', $imagesrc);
					return $result;
				}
			}		
		
			$imageext = explode('.', $imagesrc);
			$imageext = $imageext[count($imageext) - 1];
			$imageext = strtolower($imageext);
				
			$filename = $tmp_path.'/thumb_'.$module_id.'_'.$article_id.'.'.$imageext;
			$imageheight = 0;
			if (is_file(JPATH_ROOT.'/'.$filename) && !$clear_cache) {
				// thumbnail already exists
				$imagesize = @getimagesize($filename); // @ to avoid warnings
				$imageheight = $imagesize[1];
			} else { // create the thumbnail
								
				$image = new SYWImage($imagesrc);
				
				if (is_null($image->getImagePath())) {
					$result[1] = JText::sprintf('MOD_LATESTNEWSENHANCEDEXTENDED_ERROR_IMAGEFILEDOESNOTEXIST', $imagesrc);
				} else if (is_null($image->getImageMimeType())) {
					$result[1] = JText::sprintf('MOD_LATESTNEWSENHANCEDEXTENDED_ERROR_UNABLETOGETIMAGEPROPERTIES', $imagesrc);
				} else if (is_null($image->getImage()) || $image->getImageWidth() == 0) {
					$result[1] = JText::sprintf('MOD_LATESTNEWSENHANCEDEXTENDED_ERROR_UNSUPPORTEDFILETYPE', $imagesrc);
				} else {
					
					switch ($imageext){
						case 'jpg': case 'jpeg': $quality = $image_quality_array['jpg']; break; // 0 to 100
						case 'png': $quality = $image_quality_array['png']; break; // compression: 0 to 9
						default : $quality = -1; break;
					}
					
					switch ($filter) {
						case 'grayscale': $filter = IMG_FILTER_GRAYSCALE; break;
						case 'sketch': $filter = IMG_FILTER_MEAN_REMOVAL; break;
						case 'negate': $filter = IMG_FILTER_NEGATE; break;
						case 'emboss': $filter = IMG_FILTER_EMBOSS; break;
						case 'edgedetect': $filter = IMG_FILTER_EDGEDETECT; break;
						default: $filter = null; break;
					}
					
					//$creation_success = $image->createThumbnail($head_width, $head_height, $crop_picture, $quality, $filter, $filename, true); // 'true' to create high-resolution thumbnails 
					$creation_success = $image->createThumbnail($head_width, $head_height, $crop_picture, $quality, $filter, $filename);
					if (!$creation_success) {
						$result[1] = JText::sprintf('MOD_LATESTNEWSENHANCEDEXTENDED_ERROR_THUMBNAILCREATIONFAILED', $imagesrc);
					} else {
						$imageheight = $image->getThumbnailHeight();
					}
				}
			}
			if (empty($result[1])) {
				$top = ($head_height - $imageheight) / 2;
				$result[0] = '<img alt="'.$alt.'" src="'.JURI::base().$filename.'" style="position:relative;top:'.$top.'px" />';
			}
		}
	
		return $result;
	}
	
	/**
	* Create the first part of the <a> tag
	*/
	static function getATag($item, $follow = true, $popup_width = '600', $popup_height = '500', $css_classes = '')
	{
		$class = '';
		if (!empty($css_classes)) {
			$class = ' class="'.$css_classes.'"';
		}
		
		$nofollow = '';
		if (!$follow) {
			$nofollow = ' rel="nofollow"';
		}
		
		switch ($item->linktarget) {
			case 1:	// open in a new window
				return '<a href="'.$item->link.'" title="'.$item->linktitle.'" target="_blank"'.$nofollow.$class.'>';
				break;		
			case 2:	// open in a popup window
				$attribs = 'toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width='.$popup_width.',height='.$popup_height;
				return '<a href="'.$item->link.'" title="'.$item->linktitle.'" onclick="window.open(this.href, \'targetWindow\', \''.$attribs.'\'); return false;"'.$class.'>';
				break;
			case 3:	// open in a modal window
				JHtml::_('behavior.modal', 'a.modal');				
				$classes = 'modal';	
				if (!empty($css_classes)) {
					$classes .= ' '.$css_classes;
				}	
				return '<a class="'.$classes.'" href="'.$item->link.'" title="'.$item->linktitle.'" rel="{handler: \'iframe\', size: {x:'.$popup_width.', y:'.$popup_height.'}}">';
				break;
			default: // open in parent window
				return '<a href="'.$item->link.'" title="'.$item->linktitle.'"'.$nofollow.$class.'>';
				break;
		}
	}	
	
	static function date_to_counter($date, $date_in_future = false) {
	
		$date_origin = new JDate($date);
		$now = new JDate(); // now
		
		$difference = $date_origin->diff($now); // object PHP 5.3 [y] => 0 [m] => 0 [d] => 26 [h] => 23 [i] => 11 [s] => 32 [invert] => 0 [days] => 26 
	
		return array('years' => $difference->y, 'months' => $difference->m, 'days' => $difference->d, 'hours' => $difference->h, 'mins' => $difference->i, 'secs' => $difference->s);
	}
	
	/**
	 * Get block information
	 * 
	 * @param unknown $params
	 * @param unknown $item
	 * @param unknown $item_params
	 * @return string
	 */
	static function getInfoBlock($params, $item, $item_params) {
		
		$info_block = '';
		
		$infos = array();
		
		if ($params->get('info_1', 'none') != 'none') {	
			$infos[] = array($params->get('info_1', 'none'), $params->get('prepend_1'), ($params->get('show_icons_1', 0) == 1 ? true : false));
			if ($params->get('new_line_1', 0) == 1) {
				$infos[] = array('newline', '', false);
			}
		}
		if ($params->get('info_2', 'none') != 'none') {	
			$infos[] = array($params->get('info_2', 'none'), $params->get('prepend_2'), ($params->get('show_icons_2', 0) == 1 ? true : false));
			if ($params->get('new_line_2', 0) == 1) {
				$infos[] = array('newline', '', false);
			}
		}
		if ($params->get('info_3', 'none') != 'none') {
			$infos[] = array($params->get('info_3', 'none'), $params->get('prepend_3'), ($params->get('show_icons_3', 0) == 1 ? true : false));
			if ($params->get('new_line_3', 0) == 1) {
				$infos[] = array('newline', '', false);
			}
		}
		if ($params->get('info_4', 'none') != 'none') {
			$infos[] = array($params->get('info_4', 'none'), $params->get('prepend_4'), ($params->get('show_icons_4', 0) == 1 ? true : false));
			if ($params->get('new_line_4', 0) == 1) {
				$infos[] = array('newline', '', false);
			}
		}
		if ($params->get('info_5', 'none') != 'none') {
			$infos[] = array($params->get('info_5', 'none'), $params->get('prepend_5'), ($params->get('show_icons_5', 0) == 1 ? true : false));
			if ($params->get('new_line_5', 0) == 1) {
				$infos[] = array('newline', '', false);
			}
		}
		
		if (empty($infos)) {
			return $info_block;
		}	
		
		$show_date = $params->get('show_d', 'date');
		$date_format = $params->get('d_format', 'd F Y');
		$time_format = $params->get('t_format', 'H:i');
		$postdate = $params->get('post_date', 'published');
		
		$separator = htmlspecialchars($params->get('separator', ''));	
		
		$info_block .= '<p class="newsextra">';	
		$has_info_from_previous_detail = false;	
		
		foreach ($infos as $key => $value) {
			
			switch ($value[0]) {
				case 'newline':
					$info_block .= '</p><p class="newsextra">';
					$has_info_from_previous_detail = false;
					break;
					
				case 'hits':
					
					//if ($item_params->get('show_hits')) {
						if ($has_info_from_previous_detail) {
							if (!empty($separator)) {
								$info_block .= '<span class="delimiter">'.$separator.'</span>';
							} else {
								$info_block .= '<span class="delimiter">&nbsp;</span>';
							}
						}		
		
						$info_block .= '<span class="detail detail_hits">';			
						
						if ($value[2]) {							
							$info_block .= '<i class="SYWicon-eye"></i>';
						}					
						
						$info_block .= '<span class="news_hits">';
						
						$prepend = $value[1];					
						
						if (!empty($prepend)) {
							$info_block .= $prepend;
						}
						
						$info_block .= JText::sprintf('MOD_LATESTNEWSENHANCEDEXTENDED_HITS', $item->hits);
											
						$info_block .= '</span>';
						
						$info_block .= '</span>';
						
						$has_info_from_previous_detail = true;
					//}
					break;
					
				case 'rating':
					
					//if ($item_params->get('show_vote')) {
						if ($has_info_from_previous_detail) {
							if (!empty($separator)) {
								$info_block .= '<span class="delimiter">'.$separator.'</span>';
							} else {
								$info_block .= '<span class="delimiter">&nbsp;</span>';
							}
						}			
		
						$info_block .= '<span class="detail detail_rating">';
						
						if ($value[2]) {
								
							if (!empty($item->vote)) {
								if ($item->vote == 5) {
									$info_block .= '<i class="SYWicon-star3"></i>';
								} else {
									$info_block .= '<i class="SYWicon-star2"></i>';
								}
							} else {
								$info_block .= '<i class="SYWicon-star"></i>';
							}
						}		
						
						$info_block .= '<span class="news_rating">';
						
						$prepend = $value[1];
						
						if (!empty($prepend)) {
							$info_block .= $prepend;
						}
						
						if (!empty($item->vote)) {
							$info_block .= JText::sprintf('MOD_LATESTNEWSENHANCEDEXTENDED_RATING', $item->vote);
							$info_block .= JText::sprintf('MOD_LATESTNEWSENHANCEDEXTENDED_FROMUSERS', $item->vote_count);												
							//$info_block .= $item->vote.'/5 '.JText::sprintf('MOD_LATESTNEWSENHANCEDEXTENDED_FROMUSERS', $item->vote_count);
						} else {
							$info_block .= JText::_('MOD_LATESTNEWSENHANCEDEXTENDED_NORATING');
						}
						
						$info_block .= '</span>';
						
						$info_block .= '</span>';
						
						$has_info_from_previous_detail = true;
					//}
					break;
					
				case 'author':
					
					//if ($item_params->get('show_author')) {
						if ($has_info_from_previous_detail) {
							if (!empty($separator)) {
								$info_block .= '<span class="delimiter">'.$separator.'</span>';
							} else {
								$info_block .= '<span class="delimiter">&nbsp;</span>';
							}
						}
		
						$info_block .= '<span class="detail detail_author">';
							
						if ($value[2]) {							
							$info_block .= '<i class="SYWicon-user"></i>';
						}	
						
						$info_block .= '<span class="news_author">';
						
						$prepend = $value[1];
						
						if (!empty($prepend)) {
							$info_block .= $prepend;
						}
						
						$info_block .= $item->author;
						
						$info_block .= '</span>';
						
						$info_block .= '</span>';
						
						$has_info_from_previous_detail = true;
					//}
					break;
					
				case 'keywords':
					if (!empty($item->metakey)) {
						if ($has_info_from_previous_detail) {
							if (!empty($separator)) {
								$info_block .= '<span class="delimiter">'.$separator.'</span>';
							} else {
								$info_block .= '<span class="delimiter">&nbsp;</span>';
							}
						}
						
						$info_block .= '<span class="detail detail_keywords">';
						
						if ($value[2]) {								
							$info_block .= '<i class="SYWicon-tag"></i>';
						}	
						
						$info_block .= '<span class="news_keywords">';
						
						$prepend = $value[1];
						
						if (!empty($prepend)) {
							$info_block .= $prepend;
						}
						
						$info_block .= $item->metakey;
						
						$info_block .= '</span>';
						
						$info_block .= '</span>';
						
						$has_info_from_previous_detail = true;
					}
					break;
					
				case 'category':
				case 'linkedcategory':
					
					//if ($item_params->get('show_category')) {
					
						if ($has_info_from_previous_detail) {
							if (!empty($separator)) {
								$info_block .= '<span class="delimiter">'.$separator.'</span>';
							} else {
								$info_block .= '<span class="delimiter">&nbsp;</span>';
							}
						}
						
						$info_block .= '<span class="detail detail_category">';
							
						if ($value[2]) {						
							if ($value[0] == 'category') {
								$info_block .= '<i class="SYWicon-folder"></i>';
							} else {
								$info_block .= '<i class="SYWicon-folder-open"></i>';
							}
						}	
							
						$info_block .= '<span class="news_category">';
						
						$prepend = $value[1];
						
						if (!empty($prepend)) {
							$info_block .= $prepend;
						}
						
						// if ($item_params->get('link_category')
						if ($value[0] == 'category') {
							$info_block .= $item->category_title;
						} else {
							$info_block .= '<a href="'.$item->catlink.'">';
							$info_block .= $item->category_title;
							$info_block .= '</a>';
						}
											
						$info_block .= '</span>';
						
						$info_block .= '</span>';
							
						$has_info_from_previous_detail = true;
					//}
					break;
						
				case 'date':
					if (empty($item->date)) {
						$info_block .= '<span class="news_nodate"></span>';
					} else {
						if ($has_info_from_previous_detail) {
							if (!empty($separator)) {
								$info_block .= '<span class="delimiter">'.$separator.'</span>';
							} else {
								$info_block .= '<span class="delimiter">&nbsp;</span>';
							}
						}
						
						$info_block .= '<span class="detail detail_date">';
						
						if ($value[2]) {							
							$info_block .= '<i class="SYWicon-calendar"></i>';
						}	
						
						$info_block .= '<span class="news_date">';
						
						$prepend = $value[1];
						
						if (!empty($prepend)) {
							$info_block .= $prepend;
						}
						
						if ($show_date == 'date') {
							$info_block .= JHTML::_('date', $item->date, $date_format);
						} else if ($show_date == 'ago') {							
							if ($item->nbr_years > 0) {
								if ($postdate == 'finished' || $postdate == 'fin_pen' || $postdate == 'pending') {
									$info_block .= JText::sprintf('MOD_LATESTNEWSENHANCEDEXTENDED_INYEARSMONTHSDAYSONLY', $item->nbr_years, $item->nbr_months, $item->nbr_days);
								} else {
									$info_block .= JText::sprintf('MOD_LATESTNEWSENHANCEDEXTENDED_YEARSMONTHSDAYSAGO', $item->nbr_years, $item->nbr_months, $item->nbr_days);
								}
							} else if ($item->nbr_months > 0) {
								if ($postdate == 'finished' || $postdate == 'fin_pen' || $postdate == 'pending') {
									$info_block .= JText::sprintf('MOD_LATESTNEWSENHANCEDEXTENDED_INMONTHSDAYSONLY', $item->nbr_months, $item->nbr_days);
								} else {
									$info_block .= JText::sprintf('MOD_LATESTNEWSENHANCEDEXTENDED_MONTHSDAYSAGO', $item->nbr_months, $item->nbr_days);
								}
							} else if ($item->nbr_days == 0) {
								$info_block .= JText::_('MOD_LATESTNEWSENHANCEDEXTENDED_TODAY');
							} else if ($item->nbr_days == 1) {
								if ($postdate == 'finished' || $postdate == 'fin_pen' || $postdate == 'pending') {
									$info_block .= JText::_('MOD_LATESTNEWSENHANCEDEXTENDED_TOMORROW');
								} else {
									$info_block .= JText::_('MOD_LATESTNEWSENHANCEDEXTENDED_YESTERDAY');
								}
							} else {
								if ($postdate == 'finished' || $postdate == 'fin_pen' || $postdate == 'pending') {
									$info_block .= JText::sprintf('MOD_LATESTNEWSENHANCEDEXTENDED_INDAYSONLY', $item->nbr_days);
								} else {
									$info_block .= JText::sprintf('MOD_LATESTNEWSENHANCEDEXTENDED_DAYSAGO', $item->nbr_days);
								}
							}
						} else if ($show_date == 'agomhd') {							
							if ($item->nbr_years > 0) {
								if ($postdate == 'finished' || $postdate == 'fin_pen' || $postdate == 'pending') {
									$info_block .= JText::sprintf('MOD_LATESTNEWSENHANCEDEXTENDED_INYEARSMONTHSDAYSONLY', $item->nbr_years, $item->nbr_months, $item->nbr_days);
								} else {
									$info_block .= JText::sprintf('MOD_LATESTNEWSENHANCEDEXTENDED_YEARSMONTHSDAYSAGO', $item->nbr_years, $item->nbr_months, $item->nbr_days);
								}
							} else if ($item->nbr_months > 0) {
								if ($postdate == 'finished' || $postdate == 'fin_pen' || $postdate == 'pending') {
									$info_block .= JText::sprintf('MOD_LATESTNEWSENHANCEDEXTENDED_INMONTHSDAYSONLY', $item->nbr_months, $item->nbr_days);
								} else {
									$info_block .= JText::sprintf('MOD_LATESTNEWSENHANCEDEXTENDED_MONTHSDAYSAGO', $item->nbr_months, $item->nbr_days);
								}
							} else if ($item->nbr_days > 0) {
								if ($item->nbr_days == 1) {
									if ($postdate == 'finished' || $postdate == 'fin_pen' || $postdate == 'pending') {
										$info_block .= JText::_('MOD_LATESTNEWSENHANCEDEXTENDED_INADAY');
									} else {
										$info_block .= JText::_('MOD_LATESTNEWSENHANCEDEXTENDED_DAYAGO');
									}
								} else {
									if ($postdate == 'finished' || $postdate == 'fin_pen' || $postdate == 'pending') {
										$info_block .= JText::sprintf('MOD_LATESTNEWSENHANCEDEXTENDED_INDAYSONLY', $item->nbr_days);
									} else {
										$info_block .= JText::sprintf('MOD_LATESTNEWSENHANCEDEXTENDED_DAYSAGO', $item->nbr_days);
									}
 								}
							} else if ($item->nbr_hours > 0) {
								if ($item->nbr_hours == 1) {
									if ($postdate == 'finished' || $postdate == 'fin_pen' || $postdate == 'pending') {
										$info_block .= JText::_('MOD_LATESTNEWSENHANCEDEXTENDED_INANHOUR');
									} else {
										$info_block .= JText::_('MOD_LATESTNEWSENHANCEDEXTENDED_HOURAGO');
									}
								} else {
									if ($postdate == 'finished' || $postdate == 'fin_pen' || $postdate == 'pending') {
										$info_block .= JText::sprintf('MOD_LATESTNEWSENHANCEDEXTENDED_INHOURS', $item->nbr_hours);
									} else {
										$info_block .= JText::sprintf('MOD_LATESTNEWSENHANCEDEXTENDED_HOURSAGO', $item->nbr_hours);
									}
								}
							} else {
								if ($item->nbr_minutes == 1) {
									if ($postdate == 'finished' || $postdate == 'fin_pen' || $postdate == 'pending') {
										$info_block .= JText::_('MOD_LATESTNEWSENHANCEDEXTENDED_INAMINUTE');
									} else {
										$info_block .= JText::_('MOD_LATESTNEWSENHANCEDEXTENDED_MINUTEAGO');
									}
								} else {
									if ($postdate == 'finished' || $postdate == 'fin_pen' || $postdate == 'pending') {
										$info_block .= JText::sprintf('MOD_LATESTNEWSENHANCEDEXTENDED_INMINUTES', $item->nbr_minutes);
									} else {
										$info_block .= JText::sprintf('MOD_LATESTNEWSENHANCEDEXTENDED_MINUTESAGO', $item->nbr_minutes);
									}
								}
							}
						} else {
							if ($item->nbr_years > 0) {
								if ($postdate == 'finished' || $postdate == 'fin_pen' || $postdate == 'pending') {
									$info_block .= JText::sprintf('MOD_LATESTNEWSENHANCEDEXTENDED_INYEARSMONTHSDAYSHOURSMINUTES', $item->nbr_years, $item->nbr_months, $item->nbr_days, $item->nbr_hours, $item->nbr_minutes);
								} else {
									$info_block .= JText::sprintf('MOD_LATESTNEWSENHANCEDEXTENDED_YEARSMONTHSDAYSHOURSMINUTESAGO', $item->nbr_years, $item->nbr_months, $item->nbr_days, $item->nbr_hours, $item->nbr_minutes);
								}
							} elseif ($item->nbr_months > 0) {
								if ($postdate == 'finished' || $postdate == 'fin_pen' || $postdate == 'pending') {
									$info_block .= JText::sprintf('MOD_LATESTNEWSENHANCEDEXTENDED_INMONTHSDAYSHOURSMINUTES', $item->nbr_months, $item->nbr_days, $item->nbr_hours, $item->nbr_minutes);
								} else {
									$info_block .= JText::sprintf('MOD_LATESTNEWSENHANCEDEXTENDED_MONTHSDAYSHOURSMINUTESAGO', $item->nbr_months, $item->nbr_days, $item->nbr_hours, $item->nbr_minutes);
								}
							} else if ($item->nbr_days > 0) {
								if ($postdate == 'finished' || $postdate == 'fin_pen' || $postdate == 'pending') {
									$info_block .= JText::sprintf('MOD_LATESTNEWSENHANCEDEXTENDED_INDAYSHOURSMINUTES', $item->nbr_days, $item->nbr_hours, $item->nbr_minutes);
								} else {
									$info_block .= JText::sprintf('MOD_LATESTNEWSENHANCEDEXTENDED_DAYSHOURSMINUTESAGO', $item->nbr_days, $item->nbr_hours, $item->nbr_minutes);
								}
							} else if ($item->nbr_hours > 0) {
								if ($postdate == 'finished' || $postdate == 'fin_pen' || $postdate == 'pending') {
									$info_block .= JText::sprintf('MOD_LATESTNEWSENHANCEDEXTENDED_INHOURSMINUTES', $item->nbr_hours, $item->nbr_minutes);
								} else {
									$info_block .= JText::sprintf('MOD_LATESTNEWSENHANCEDEXTENDED_HOURSMINUTESAGO', $item->nbr_hours, $item->nbr_minutes);
								}
							} else {
								if ($item->nbr_minutes == 1) {
									if ($postdate == 'finished' || $postdate == 'fin_pen' || $postdate == 'pending') {
										$info_block .= JText::_('MOD_LATESTNEWSENHANCEDEXTENDED_INAMINUTE');
									} else {
										$info_block .= JText::_('MOD_LATESTNEWSENHANCEDEXTENDED_MINUTEAGO');
									}
								} else {
									if ($postdate == 'finished' || $postdate == 'fin_pen' || $postdate == 'pending') {
										$info_block .= JText::sprintf('MOD_LATESTNEWSENHANCEDEXTENDED_INMINUTES', $item->nbr_minutes);
									} else {
										$info_block .= JText::sprintf('MOD_LATESTNEWSENHANCEDEXTENDED_MINUTESAGO', $item->nbr_minutes);
									}
								}
							}
						}
						
						$info_block .= '</span>';
						
						$info_block .= '</span>';
						
						$has_info_from_previous_detail = true;
					}
					break;
					
				case 'time':
					if (empty($item->date)) {
						$info_block .= '<span class="news_notime"></span>';
					} else {
						if ($has_info_from_previous_detail) {
							if (!empty($separator)) {
								$info_block .= '<span class="delimiter">'.$separator.'</span>';
							} else {
								$info_block .= '<span class="delimiter">&nbsp;</span>';
							}
						}
						
						$info_block .= '<span class="detail detail_time">';
				
						if ($value[2]) {								
							$info_block .= '<i class="SYWicon-clock"></i>';
						}
				
						$info_block .= '<span class="news_time">';
				
						$prepend = $value[1];
				
						if (!empty($prepend)) {
							$info_block .= $prepend;
						}
				
						$info_block .= JHTML::_('date', $item->date, $time_format);
				
						$info_block .= '</span>';
						
						$info_block .= '</span>';
				
						$has_info_from_previous_detail = true;
					}
					break;
					
				default:
					break;
			}
		}
		
		$info_block .= '</p>';
		
		// remove potential <p class="newsextra"></p> when no data is available
		$info_block = str_replace('<p class="newsextra"></p>', '', $info_block);		
		
		return $info_block;
	}
	
	/**
	* Load plugin if needed by animation
	*/
	static function loadLibrary($animation)
	{
		if ($animation === 'cover' || $animation === 'fade' || $animation === 'scroll') {
			
			SYWLibraries::loadCarousel();
			
		} else if ($animation === 'justpagination') {
			
			SYWLibraries::loadPagination();
			
		} else {
			require_once (dirname(__FILE__).'/helper_'.$animation.'.php');
			
			$class = 'modLatestNewsEnhancedExtendedHelper'.ucfirst($animation);
			$instance = new $class();
			$instance->load_library();
		}	
	}
	
	/**
	 * Load common stylesheet to all module instances
	 */
	static function loadCommonStylesheet() {
		
		if (self::$commonStylesLoaded) {
			return;
		}
		
		$doc = JFactory::getDocument();
		$doc->addStyleSheet(JURI::base(true).'/modules/mod_latestnewsenhanced/styles/common_styles.css');
		
		self::$commonStylesLoaded = true;
	}
	
}
?>