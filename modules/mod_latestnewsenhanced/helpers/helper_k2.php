<?php
/**
 * @copyright	Copyright (C) 2011 Simplify Your Web, Inc. All rights reserved.
 * @license		GNU General Public License version 3 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

require_once (JPATH_SITE.'/components/com_k2/helpers/route.php');
require_once (JPATH_SITE.'/components/com_k2/models/itemlist.php');
require_once (dirname(__FILE__).'/helper.php');

jimport('syw.cache');
jimport('syw.text');

class modLatestNewsEnhancedExtendedHelperK2
{
	static function getList($params, $module)
	{
		// Get the dbo
		$db = JFactory::getDbo();
		$app = JFactory::getApplication();
		
		$query = $db->getQuery(true);
		
		$subquery1 = ' CASE WHEN ';
		$subquery1 .= $query->charLength('a.alias');
		$subquery1 .= ' THEN ';
		$a_id = $query->castAsChar('a.id');
		$subquery1 .= $query->concatenate(array($a_id, 'a.alias'), ':');
		$subquery1 .= ' ELSE ';
		$subquery1 .= $a_id.' END AS slug';
		
		$subquery2 = ' CASE WHEN ';
		$subquery2 .= $query->charLength('c.alias');
		$subquery2 .= ' THEN ';
		$c_id = $query->castAsChar('c.id');
		$subquery2 .= $query->concatenate(array($c_id, 'c.alias'), ':');
		$subquery2 .= ' ELSE ';
		$subquery2 .= $c_id.' END AS cat_slug';
		
		$query->select('DISTINCT a.id, a.title, a.alias, a.introtext, a.fulltext, '.
			'a.checked_out, a.checked_out_time, '.
			'a.catid, a.created, a.created_by, a.created_by_alias, '.
			// Use created if modified is 0
			'CASE WHEN a.modified = '.$db->quote($db->getNullDate()).' THEN a.created ELSE a.modified END as modified, '.
			'a.modified_by, '.
			// Use created if publish_up is 0
			'CASE WHEN a.publish_up = '.$db->quote($db->getNullDate()).' THEN a.created ELSE a.publish_up END as publish_up, '.
			'a.publish_down, a.params, a.metadata, a.metakey, a.metadesc, a.access, a.hits, a.featured');
		
		$query->select('c.id AS cat_id, c.name AS category_title, c.alias AS cat_alias');
		$query->select($subquery1);
		$query->select($subquery2);
		$query->from('#__k2_items AS a');		
		$query->join('INNER', '#__k2_categories AS c ON c.id = a.catid');
		
		$query->where('a.published = 1 AND a.trash = 0');		
		$query->where('c.published = 1');	
		
		// Access filter
		
		$access = !JComponentHelper::getParams('com_content')->get('show_noauth');
		$authorised = JAccess::getAuthorisedViewLevels(JFactory::getUser()->get('id'));
		
		if ($access) {
			$user = JFactory::getUser();
			$groups	= implode(',', $user->getAuthorisedViewLevels());
			$query->where('a.access IN ('.$groups.')');
			$query->where('c.access IN ('.$groups.')');
		}
		
		// filter by start and end dates
		
		$nullDate = $db->Quote($db->getNullDate());
		$nowDate = $db->Quote(JFactory::getDate()->toSql());
		
		$postdate = $params->get('post_d', 'published');
		
		if ($postdate != 'fin_pen' && $postdate != 'pending') {
			$query->where('(a.publish_up = ' . $nullDate . ' OR a.publish_up <= ' . $nowDate . ')');
		}
		if ($postdate == 'pending') {
			$query->where('a.publish_up > ' . $nowDate);
		}
		$query->where('(a.publish_down = ' . $nullDate . ' OR a.publish_down >= ' . $nowDate . ')');
		
		// Category filter
		
		$categories_array = $params->get('k2catid', array());
		
		$array_of_category_values = array_count_values($categories_array);
		if (isset($array_of_category_values['all']) && $array_of_category_values['all'] > 0) { // 'all' was selected
			// take everything, so no category selection			
		} else if (isset($array_of_category_values['auto']) && $array_of_category_values['auto'] > 0) { // 'auto' was selected
				
			$option = JRequest::getCmd('option');
			$view = JRequest::getCmd('view');
			$layout = JRequest::getCmd('layout');
		
			$categories_array = array();
		
			if ($option === 'com_k2' && $view === 'itemlist' && $layout === 'category') {
				$categories_array[] = JRequest::getInt('id');
			}
			
			//if ($option === 'com_k2' && $view === 'item') {
				//if ($params->get('show_on_article_page', 1)) {
				//$categories_array[] = JRequest::getInt('catid');
				//}
			//}
				
			if (!empty($categories_array)) {
					
				// sub-category inclusion
				$get_sub_categories = $params->get('includesubcategories', 'no');
				if ($get_sub_categories != 'no') {			
					$itemListModel = K2Model::getInstance('Itemlist', 'K2Model');
					$sub_categories_array = array();
					if ($get_sub_categories == 'all') {
						$sub_categories_array = $itemListModel->getCategoryTree($categories_array);
					} else {				
						foreach ($categories_array as $category) {						
							$sub_categories_rows = $itemListModel->getCategoryFirstChildren($category);
							foreach ($sub_categories_rows as $sub_categories_row) {
								$sub_categories_array[] = $sub_categories_row->id;
							}
						}
					}
					foreach ($sub_categories_array as $subcategory) {
						$categories_array[] = $subcategory;
					}
					$categories_array = array_unique($categories_array);
				}
				
				if (!empty($categories_array)) {
					$categories = implode(',', $categories_array);
					if (!empty($categories)) {
						$query->where('a.catid IN ('.$categories.')');
					}
				}
					
			} else {
				return null; // no result if not in the category page
			}
		} else {
			// sub-category inclusion
			$get_sub_categories = $params->get('includesubcategories', 'no');
			if ($get_sub_categories != 'no') {			
				$itemListModel = K2Model::getInstance('Itemlist', 'K2Model');
				$sub_categories_array = array();
				if ($get_sub_categories == 'all') {
					$sub_categories_array = $itemListModel->getCategoryTree($categories_array);
				} else {				
					foreach ($categories_array as $category) {						
						$sub_categories_rows = $itemListModel->getCategoryFirstChildren($category);
						foreach ($sub_categories_rows as $sub_categories_row) {
							$sub_categories_array[] = $sub_categories_row->id;
						}
					}
				}
				foreach ($sub_categories_array as $subcategory) {
					$categories_array[] = $subcategory;
				}
				$categories_array = array_unique($categories_array);
			}
			
			if (!empty($categories_array)) {
				$categories = implode(',', $categories_array);
				if (!empty($categories)) {
					$query->where('a.catid IN ('.$categories.')');
				}
			}
		}
		
		// tags filter
		
		$tags_array = $params->get('k2tags', array());		
		
		if (count($tags_array) > 0) { 
			$array_of_tag_values = array_count_values($tags_array);
			
			$query->join('LEFT', '#__k2_tags_xref tags_xref ON tags_xref.itemID = a.id LEFT JOIN #__k2_tags tags ON tags.id = tags_xref.tagID');
			//$query->join('INNER', '#__k2_tags tags ON tags.id = tags_xref.tagID');
			$query->where('tags.published = 1');
			
			if (isset($array_of_tag_values['all']) && $array_of_tag_values['all'] > 0) { // 'all' was selected
				// take everything
			} else {
				if (!empty($tags_array)) {
					$tags = implode(',', $tags_array);
					if (!empty($tags)) {
						$query->where('tags.id IN ('.$tags.')');
					}
				}
			}
		}
		
		// keys filter	
		
		$related = $params->get('related', 0);		
		
		$metakeys = array();
		$related_id = ''; // to avoid the news item to be visible in the list of related news
		$keys = '';
		
		if ($related) {
			
			$option = JRequest::getCmd('option');
			$view = JRequest::getCmd('view');
			$temp = JRequest::getString('id');
			$temp = explode(':', $temp);
			$id = $temp[0];
			if ($option == 'com_k2' && $view == 'item' && $id) { // the content is an article page
				$query2 = $db->getQuery(true);
				$query2->select('metakey');
				$query2->from('#__k2_items');
				$query2->where('id = ' . (int) $id);
				$db->setQuery($query2);
				$results = trim($db->loadResult());
				if (empty($results)) {
					return array(); // won't find a related article if no key is present
				}
				$keys = explode(',', $results);
				$query2->clear();
				$related_id = $id;
			} else {
				return null; // no result if not an article page
			}
			
		} else { // meta keys can be used in conjunction with categories
			// explode the meta keys on a comma
			$keys = explode(',', $params->get('keys', ''));
		}
		
		// assemble any non-blank word(s)
		foreach ($keys as $key) {
			$key = trim($key);
			if ($key) {
				$metakeys[] = $key;
			}
		}
		
		if (!empty($metakeys)) {
			$concat_string = $query->concatenate(array('","', ' REPLACE(a.metakey, ", ", ",")', ' ","')); // remove single space after commas in keywords
			$query->where('('.$concat_string.' LIKE "%'.implode('%" OR '.$concat_string.' LIKE "%', $metakeys).'%")');
		}
		
		// do not show the article in the list of related items
		if (!empty($related_id)) {
			$query->where('a.id <> '.(int) $related_id);
		}
		
		// User filter		
		$userId = (int) JFactory::getUser()->get('id');
		switch ($params->get('user_id', 'all'))
		{
			case 'by_me':
				$query->where('a.created_by = '.$userId);
				break;
			case 'not_me':
				$query->where('a.created_by NOT IN ('.$userId.')');
				break;
			case 'all':
				break;
			default:
				break;
		}		
		
		// Filter by language
		
		if ($app->getLanguageFilter()) {
			$query->where('a.language IN ('.$db->quote(JFactory::getLanguage()->getTag()).','.$db->quote('*').')');
		}
		
		$ordering = '';
		
		// Featured switch	
		$featured = false;
		$featured_only = false;
		switch ($params->get('show_f'))
		{
			case '1': // only
				$featured = true;
				$featured_only = true;				
				$query->where('a.featured = 1');
				break;
			case '0': // hide
				$query->where('a.featured = 0');
				break;
			case '2': // first the featured ones
				$featured = true;				
				$ordering .= 'a.featured DESC,';
				break;
			default: // no discrimination between featured/unfeatured items
				$featured = true;				
				break;
		}
	
		// Category order
		if (!$featured_only) {
			switch ($params->get( 'cat_order' )) {
				case 'o_asc' :
					$ordering .= "c.ordering ASC, c.parent ASC,";
					break;
				case 'o_dsc' :
					$ordering .= "c.ordering DESC, c.parent DESC,";
					break;
				default :
					break;
			}
		}
		
		// Ordering
		
		switch ($params->get( 'order' ))
		{
			case 'o_asc': if ($featured) { $ordering .= 'CASE WHEN (a.featured = 1) THEN a.featured_ordering ELSE a.ordering END ASC'; } else { $ordering .= 'a.ordering ASC'; } break;
			case 'o_dsc': if ($featured) { $ordering .= 'CASE WHEN (a.featured = 1) THEN a.featured_ordering ELSE a.ordering END DESC'; } else { $ordering .= 'a.ordering DESC'; } break;
			case 'p_asc': $ordering .= 'a.publish_up ASC'; break;
			case 'p_dsc': $ordering .= 'a.publish_up DESC'; break;
			case 'f_asc': $ordering .= 'CASE WHEN (a.publish_down = '.$db->quote($db->getNullDate()).') THEN a.publish_up ELSE a.publish_down END ASC'; break;
			case 'f_dsc': $ordering .= 'CASE WHEN (a.publish_down = '.$db->quote($db->getNullDate()).') THEN a.publish_up ELSE a.publish_down END DESC'; break;			
			case 'm_asc': $ordering .= 'a.modified ASC, a.created ASC'; break;
			case 'm_dsc': $ordering .= 'a.modified DESC, a.created DESC'; break;
			case 'c_asc': $ordering .= 'a.created ASC'; break;
			case 'c_dsc': $ordering .= 'a.created DESC'; break;
			case 'mc_asc': $ordering .= 'a.created ASC'; break;
			case 'mc_dsc': $ordering .= 'CASE WHEN (a.modified = '.$db->quote($db->getNullDate()).') THEN a.created ELSE a.modified END DESC'; break;
			case 'random': $ordering .= 'rand()'; break;
			case 'hit': $ordering .= 'a.hits DESC'; break;
			case 'title_asc': $ordering .= 'a.title ASC'; break;
			case 'title_dsc': $ordering .= 'a.title DESC'; break;
			default: $ordering .= 'a.publish_up DESC'; break;
		}
		
		$query->order($ordering);	
		
		// include only
		
		$articles_to_include = trim($params->get('in'));
		if (!empty($articles_to_include)) {
			$query->where('a.id IN ('.$articles_to_include.')');
		}
		
		// exclude
		
		$articles_to_exclude = trim($params->get('ex'));
		if (!empty($articles_to_exclude)) {
			$query->where('a.id NOT IN ('.$articles_to_exclude.')');
		}
		
		$db->setQuery($query);
		
		$items = $db->loadObjectList();
		
		if ($error = $db->getErrorMsg()) {
			throw new Exception($error);
		}
		
		$when_no_date = $params->get('when_no_date', 0);
		$range_from = $params->get('range_from', 'now');
		$spread_from = $params->get('spread_from', 1);
		$range_to = $params->get('range_to', 'week');
		$spread_to = $params->get('spread_to', 1);
		
		$items_with_no_date = array();
		foreach ($items as $key => &$item) {	
			
			// date
			
			$item->date = $item->publish_up;
			if ($postdate == 'created') {
				$item->date = $item->created;
			} else if ($postdate == 'modified') {
				$item->date = $item->modified;
			} else if ($postdate == 'finished' || $postdate == 'fin_pen' || $postdate == 'pending') {
				$item->date = $item->publish_down;
			}		
			
			// check if date is null
			if ($item->date == $db->getNullDate()) {
				$item->date = null;
				$items_with_no_date[] = $item;
				unset($items[$key]);
			} 
		}
		
		if ($when_no_date == 1) {
			$items = array_merge($items_with_no_date, $items);
		} else if ($when_no_date == 2) {
			$items = array_merge($items, $items_with_no_date);
		}
		
		$count = trim($params->get('count', ''));
		$startat = $params->get('startat', 1);
		if ($startat < 1) {
			$startat = 1;
		}
		if (!empty($count)) {
			$items = array_slice($items, $startat - 1, $count);
		} else {
			$items = array_slice($items, $startat - 1);
		}
		
		$head_type = $params->get('head_type', 'none');
		//$postdate = $params->get('post_d', 'published');		
		
		$text_type = $params->get('text', 'intro');
		$letter_count = trim($params->get('l_count'));
		$keep_tags = $params->get('keep_tags');
		
		$author_name = $params->get('show_a', 'none');
		$show_author = ($author_name == 'none') ? false : true;
		$show_date = $params->get('show_d', 'date');
		
		$strip_tags = $params->get('strip_tags', 1);
		$crop_picture = $params->get('crop_pic', 0);
		$head_width = $params->get('head_w', 64);
		$head_height = $params->get('head_h', 64);
		
		$quality_jpg = $params->get('quality_jpg', 100);
		$quality_png = $params->get('quality_png', 0);
		$filter = $params->get('filter', 'none');
		
		if ($quality_jpg > 100) {
			$quality_jpg = 100;
		}
		if ($quality_jpg < 0) {
			$quality_jpg = 0;
		}
		
		if ($quality_png > 9) {
			$quality_png = 9;
		}
		if ($quality_png < 0) {
			$quality_png = 0;
		}
		
		$image_qualities = array('jpg' => $quality_jpg, 'png' => $quality_png);
		
		if ($head_type == "image") {
			$border_width = $params->get('border_w', 0);
			$head_width = $head_width - $border_width * 2;
			$head_height = $head_height - $border_width * 2;
		}		
		
		$clear_cache = $params->get('clear_cache', 0);
		
		$tmp_path_param = $params->get('thumb_path', 'default'); // could be 'tmp' from previous version
		$tmp_path = str_replace(JPATH_ROOT.'/', '', $app->getCfg('tmp_path'));		
		if ($tmp_path_param == 'images') {
			$media_params = JComponentHelper::getParams('com_media');
			$images_path = $media_params->get('image_path', 'images');
		
			if (SYWCache::isFolderReady(JPATH_ROOT.'/'.$images_path, 'thumbnails/lnee')) {
				$tmp_path = $images_path.'/thumbnails/lnee';
			} else {
				//$app->enqueueMessage(JText::_('MOD_LATESTNEWSENHANCEDEXTENDED_WARNING_COULDNOTCREATETMPFILEUSINGDEFAULT'), 'warning'); // may not show in the template
			}
		}		
	
		$link_to = $params->get('link_to', 'article');
		
		foreach ($items as &$item) {
			
			// links
			
			$item->linktarget = '';
			
			if ($access || in_array($item->access, $authorised)) {
				// We know that user has the privilege to view the article
				$url = K2HelperRoute::getItemRoute($item->slug, $item->cat_slug);				
				if ($link_to == 'modal') {
					$url .= '&tmpl=component&print=1';
					$item->linktarget = 3;
				}				
				$item->link = urldecode(JRoute::_($url));
				$item->catlink = urldecode(JRoute::_(K2HelperRoute::getCategoryRoute($item->cat_slug)));
			} else {
				$item->link = JRoute::_('index.php?option=com_users&view=login');
				$item->catlink = $item->link;
			}	
			
			$item->linktitle = $item->title;
			
			// title
			
			$force_one_line = $params->get('force_one_line', false);
			if (!$force_one_line) {
				$title_letter_count = trim($params->get('letter_count_title', ''));
				if (strlen($title_letter_count) > 0) {
					$item->title = SYWText::getText($item->title, 'txt', (int)$title_letter_count);
				}
			}
						
			// author
			
			$user = JFactory::getUser($item->created_by);
			if ($show_author) {				
				switch ($author_name) {
					case 'full':
						$item->author = htmlspecialchars($user->name);
						break;
					case 'alias':
						$item->author = htmlspecialchars($item->created_by_alias);
						break;
					default:
						$item->author = htmlspecialchars($user->username);
						break;
				}
			}
				
			// rating (to avoid call to rating plugin, use $item->vote)
			
			$db->setQuery('SELECT ROUND(v.rating_sum / v.rating_count, 0) AS rating, v.rating_count as rating_count FROM #__k2_rating AS v WHERE v.itemID ='.$item->id);
			$ratings = $db->loadObjectList();
				
			if ($error = $db->getErrorMsg()) {
				throw new Exception($error);
			}
				
			foreach ($ratings as $rating) {
				$item->vote = $rating->rating;
				$item->vote_count = $rating->rating_count;
			}
			
			// image
			
			$result_array = array('', null); // image tag / error
			
			if ($head_type == "image") {
				if (isset($item->fulltext))	{
					$imagesrc = modLatestNewsEnhancedExtendedHelper::getImageSrcFromArticle($item->introtext, $item->fulltext);
				} else {
					$imagesrc = modLatestNewsEnhancedExtendedHelper::getImageSrcFromArticle($item->introtext);
				}				
				if (!empty($imagesrc)) {
					$result_array = modLatestNewsEnhancedExtendedHelper::getImageTag($item->title, $module->id, $item->id, $imagesrc, $tmp_path, $clear_cache, $head_width, $head_height, $crop_picture, $image_qualities, $filter);
				}
			
			} 		
			
			$item->imagetag = $result_array[0];

			if (empty($item->imagetag)) {
				$default_picture = trim($params->get('default_pic', ''));
				if (!empty($default_picture)) {
					$default_picture = JURI::base().$default_picture;
					$result_array = modLatestNewsEnhancedExtendedHelper::getImageTag($item->title, $module->id, $item->id, $default_picture, $tmp_path, $clear_cache, $head_width, $head_height, $crop_picture, $image_qualities, $filter);
						
					$item->imagetag = $result_array[0];
					if (!empty($result_array[1])) {
						$item->error[] = $result_array[1];
					}
				}
			}			
			
			if (!empty($result_array[1])) {
				$item->error[] = $result_array[1];
			}
			
			// ago
			
			if ($show_date == 'ago' || $show_date == 'agomhd' || $show_date == 'agohm') {
						
				if ($item->date != $db->getNullDate()) {
					$details = modLatestNewsEnhancedExtendedHelper::date_to_counter($item->date, ($postdate == 'finished' || $postdate == 'fin_pen' || $postdate == 'pending') ? true : false);
				
					$item->nbr_seconds  = intval($details['secs']);
					$item->nbr_minutes  = intval($details['mins']);
					$item->nbr_hours = intval($details['hours']);
					$item->nbr_days = intval($details['days']);
					$item->nbr_months = intval($details['months']);
					$item->nbr_years = intval($details['years']);
				}	
			}
			
			// tags
			
			$query->clear();
			
			$query->select('tag.*');
			$query->from('#__k2_tags AS tag');
			$query->join('LEFT', '#__k2_tags_xref AS xref ON tag.id = xref.tagID');
			$query->where('tag.published = 1');
			$query->where('xref.itemID = '.$item->id);
			$query->order('tag.name ASC');		
			
			$db->setQuery($query);
			$tags_array = $db->loadObjectList();
			
			if ($error = $db->getErrorMsg()) {
				throw new Exception($error);
			}
			
			if (count($tags_array) > 0) {
				$item->tags = $tags_array;
			}
			
			// text
			
			$item->text = '';
			
			if ($text_type == 'intro') {
				$item->text = $item->introtext;
				
				// will trigger events from plugins
				$app->triggerEvent('onContentPrepare', array('com_content.article', &$item, &$params, 0));
			}
			
			$number_of_letters = -1;
			if ($letter_count != '') {
				$number_of_letters = (int)($letter_count);
			}
			
			if ($text_type == 'intro') {
				$item->text = SYWText::getText($item->text, 'html', $number_of_letters, $strip_tags, trim($keep_tags));
			} else {
				$item->text = SYWText::getText($item->metadesc, 'txt', $number_of_letters, false, '');
			}			
		}
		
		return $items;
	}
	
}
?>
