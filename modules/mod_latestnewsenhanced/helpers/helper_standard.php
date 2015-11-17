<?php
/**
 * @copyright	Copyright (C) 2011 Simplify Your Web, Inc. All rights reserved.
 * @license		GNU General Public License version 3 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

require_once (JPATH_SITE.'/components/com_content/helpers/route.php');
require_once (dirname(__FILE__).'/helper.php');

jimport('syw.tags');
jimport('syw.utilities');
jimport('syw.cache');
jimport('syw.text');

class modLatestNewsEnhancedExtendedHelperStandard
{		
	static function getList($params, $module)
	{
		// Get the dbo
		$db = JFactory::getDbo();		
		$app = JFactory::getApplication();
		
		$query = $db->getQuery(true);
		
		$query->select('DISTINCT a.id, a.title, a.alias, a.introtext, a.fulltext, '.
			'a.checked_out, a.checked_out_time, '.
			'a.catid, a.created, a.created_by, a.created_by_alias, '.
			// Use created if modified is 0
			'CASE WHEN a.modified = '.$db->quote($db->getNullDate()).' THEN a.created ELSE a.modified END as modified, '.
			'a.modified_by, uam.name as modified_by_name,'.
			// Use created if publish_up is 0
			'CASE WHEN a.publish_up = '.$db->quote($db->getNullDate()).' THEN a.created ELSE a.publish_up END as publish_up, '.
			'a.publish_down, a.images, a.urls, a.attribs, a.metadata, a.metakey, a.metadesc, a.access, a.hits, a.featured');
		
		$published = 1; // 'state = 1' only published for now
		
		// Process an Archived Article layout
		if ($published == 2) {
			// If badcats is not null, this means that the article is inside an archived category
			// In this case, the state is set to 2 to indicate Archived (even if the article state is Published)
			$query->select('CASE WHEN badcats.id is null THEN a.state ELSE 2 END AS state');
		} else {
			// Process non-archived layout
			// If badcats is not null, this means that the article is inside an unpublished category
			// In this case, the state is set to 0 to indicate Unpublished (even if the article state is Published)
			$query->select('CASE WHEN badcats.id is not null THEN 0 ELSE a.state END AS state');
		}
		
		$query->from('#__content AS a');		
		
		// join over the categories
		$query->select('c.title AS category_title, c.path AS category_route, c.access AS category_access, c.alias AS category_alias');
		$query->join('LEFT', '#__categories AS c ON c.id = a.catid');
		
		// join over the users for the author and modified_by names
		$query->select("CASE WHEN a.created_by_alias > ' ' THEN a.created_by_alias ELSE ua.name END AS author");
		$query->select("ua.email AS author_email");

		$query->join('LEFT', '#__users AS ua ON ua.id = a.created_by');
		$query->join('LEFT', '#__users AS uam ON uam.id = a.modified_by');
		
		// Get contact id IF author type is contact
		//$subQuery = $db->getQuery(true);
		//$subQuery->select('MAX(contact.id) AS id');
		//$subQuery->from('#__contact_details AS contact');
		//$subQuery->where('contact.published = 1');
		//$subQuery->where('contact.user_id = a.created_by');
		//if ($app->getLanguageFilter()) {
			//$subQuery->where('(contact.language in (' . $db->quote(JFactory::getLanguage()->getTag()) . ',' . $db->quote('*') . ') OR contact.language IS NULL)');
		//}
		
		//$query->select('(' . $subQuery . ') as contactid');
		
		// join over the categories to get parent category titles
		$query->select('parent.title as parent_title, parent.id as parent_id, parent.path as parent_route, parent.alias as parent_alias');
		$query->join('LEFT', '#__categories as parent ON parent.id = c.parent_id');
		
		// join on voting table
		$query->select('ROUND(v.rating_sum / v.rating_count, 0) AS rating, v.rating_count as rating_count');
		$query->join('LEFT', '#__content_rating AS v ON a.id = v.content_id');
		
		// join to check for category published state in parent categories up the tree
		$query->select('c.published, CASE WHEN badcats.id is null THEN c.published ELSE 0 END AS parents_published');
		
		$subquery = 'SELECT cat.id as id FROM #__categories AS cat JOIN #__categories AS parent ';
		$subquery .= 'ON cat.lft BETWEEN parent.lft AND parent.rgt ';
		$subquery .= 'WHERE parent.extension = ' . $db->quote('com_content');
				
		if ($published == 2) {
			// Find any up-path categories that are archived
			// If any up-path categories are archived, include all children in archived layout
			$subquery .= ' AND parent.published = 2 GROUP BY cat.id ';
			// Set effective state to archived if up-path category is archived
			$publishedWhere = 'CASE WHEN badcats.id is null THEN a.state ELSE 2 END';
		} else {
			// Find any up-path categories that are not published
			// If all categories are published, badcats.id will be null, and we just use the article state
			$subquery .= ' AND parent.published != 1 GROUP BY cat.id ';
			// Select state to unpublished if up-path category is unpublished
			$publishedWhere = 'CASE WHEN badcats.id is null THEN a.state ELSE 0 END';
		}
		
		$query->join('LEFT OUTER', '(' . $subquery . ') AS badcats ON badcats.id = c.id');
		
		if (is_numeric($published)) {
			// Use article state if badcats.id is null, otherwise, force 0 for unpublished
			$query->where($publishedWhere . ' = ' . (int) $published);
		} elseif (is_array($published)) {
			JArrayHelper::toInteger($published);
			$published = implode(',', $published);
			// Use article state if badcats.id is null, otherwise, force 0 for unpublished
			$query->where($publishedWhere . ' IN ('.$published.')');
		}
		
		//$query->where('a.state = 1'); // TODO needed ? NO
		//$query->where('c.published = 1');
		
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
			$query->where('(a.publish_up = '.$nullDate.' OR a.publish_up <= '.$nowDate.')');
		}
		if ($postdate == 'pending') {
			$query->where('a.publish_up > ' . $nowDate);
		}
		$query->where('(a.publish_down = '.$nullDate.' OR a.publish_down >= '.$nowDate.')');
					
		// Category filter
		
		$categories_array = $params->get('catid', array());
	
		$array_of_category_values = array_count_values($categories_array);
		if (isset($array_of_category_values['all']) && $array_of_category_values['all'] > 0) { // 'all' was selected
			// take everything, so no category selection
		} else if (isset($array_of_category_values['auto']) && $array_of_category_values['auto'] > 0) { // 'auto' was selected
			
			$option = JRequest::getCmd('option');
			$view = JRequest::getCmd('view');
				
			$categories_array = array();
				
			if ($option === 'com_content') {
				switch($view) {
					case 'category':
						$categories_array[] = JRequest::getInt('id');
						break;
					case 'categories':
						$categories_array[] = JRequest::getInt('id');
						break;
					//case 'article':
						//if ($params->get('show_on_article_page', 1)) {
						//$categories_array[] = JRequest::getInt('catid');
						//}
						//break;
				}
			}
			
			if (!empty($categories_array)) {
			
				// sub-category inclusion
				$get_sub_categories = $params->get('includesubcategories', 'no');
				if ($get_sub_categories != 'no') {
					$categories_object = JCategories::getInstance('Content');
					foreach ($categories_array as $category) {
						$category_object = $categories_object->get($category); // if category unpublished, unset
						if (isset($category_object) && $category_object->hasChildren()) {
							if ($get_sub_categories == 'all') {
								$sub_categories_array = $category_object->getChildren(true); // true is for recursive
							} else {
								$sub_categories_array = $category_object->getChildren();
							}
							foreach ($sub_categories_array as $subcategory_object) {
								$categories_array[] = $subcategory_object->id;
							}
						}
					}
					$categories_array = array_unique($categories_array);
				}
			
				$categories = implode(',', $categories_array);
				$query->where('a.catid IN ('.$categories.')');
			
			} else {
				return null; // no result if not in the category page
			}
			
		} else {
			// sub-category inclusion
			$get_sub_categories = $params->get('includesubcategories', 'no');
			if ($get_sub_categories != 'no') {
				$categories_object = JCategories::getInstance('Content');
				foreach ($categories_array as $category) {
					$category_object = $categories_object->get($category); // if category unpublished, unset
					if (isset($category_object) && $category_object->hasChildren()) {
						if ($get_sub_categories == 'all') {
							$sub_categories_array = $category_object->getChildren(true); // true is for recursive
						} else {
							$sub_categories_array = $category_object->getChildren();
						}
						foreach ($sub_categories_array as $subcategory_object) {
							$categories_array[] = $subcategory_object->id;
						}
					}
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
		
		// keys filter	
		
		$related = $params->get('related', 0);	
		
		$metakeys = array();
		$related_id = ''; // to avoid the news item to be visible in the list of related news
		$keys = '';
		
		if ($related) {
		//if ($selection == 'related') {
			
			$option = JRequest::getCmd('option');
			$view = JRequest::getCmd('view');
			$temp = JRequest::getString('id');
			$temp = explode(':', $temp);
			$id = $temp[0];
			if ($option == 'com_content' && $view == 'article' && $id) { // the content is an article page
				$query2 = $db->getQuery(true);
				$query2->select('metakey');
				$query2->from('#__content');
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
	
		//  Featured switch
		$featured = false;
		$featured_only = false;
		switch ($params->get('show_f'))
		{
			case '1': // only
				$featured = true;
				$featured_only = true;
				$query->where('a.featured = 1');
				if ($params->get('order') == 'o_asc' || $params->get('order') == 'o_dsc') {
					$query->join('LEFT', '#__content_frontpage AS fp ON fp.content_id = a.id');
				}
				break;
			case '0': // hide
				$query->where('a.featured = 0');
				break;
			case '2': // first the featured ones
				$featured = true;
				if ($params->get('order') == 'o_asc' || $params->get('order') == 'o_dsc') {
					$query->join('LEFT', '#__content_frontpage AS fp ON fp.content_id = a.id');
				}
				$ordering .= 'a.featured DESC,';
				break;
			default: // no discrimination between featured/unfeatured items
				$featured = true;
				if ($params->get('order') == 'o_asc' || $params->get('order') == 'o_dsc') {
					$query->join('LEFT', '#__content_frontpage AS fp ON fp.content_id = a.id');
				}
				break;
		}

		// Category order
		if (!$featured_only) {
			switch ($params->get( 'cat_order' )) {
				case 'o_asc' :
					$ordering .= "c.lft ASC,";
					break;
				case 'o_dsc' :
					$ordering .= "c.lft DESC,";
					break;
				default :
					break;
			}
		}
	
		// Ordering
	
		switch ($params->get('order'))
		{
			case 'o_asc': if ($featured) { $ordering .= 'CASE WHEN (a.featured = 1) THEN fp.ordering ELSE a.ordering END ASC'; } else { $ordering .= 'a.ordering ASC'; } break;
			case 'o_dsc': if ($featured) { $ordering .= 'CASE WHEN (a.featured = 1) THEN fp.ordering ELSE a.ordering END DESC'; } else { $ordering .= 'a.ordering DESC'; } break;
			
			//case 'o_asc': if ($featured_only) { $ordering .= 'fp.ordering ASC'; } else { $ordering .= 'a.ordering ASC'; } break;
			//case 'o_dsc': if ($featured_only) { $ordering .= 'fp.ordering DESC'; } else { $ordering .= 'a.ordering DESC'; } break;
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

		// Tags handling
			
		$tags = $params->get('tags', array());
		
		if (!empty($tags)) { // filter by tags
			$article_list = array();
				
			// if all selected, get all available tags
			$array_of_tag_values = array_count_values($tags);
			if (isset($array_of_tag_values['all']) && $array_of_tag_values['all'] > 0) { // 'all' was selected
				$tags = array();
				$tag_objects = SYWTags::getTags('com_content.article');
				if ($tag_objects !== false) {
					foreach ($tag_objects as $tag_object) {
						$tags[] = $tag_object->id;
					}
				}
			}
				
			if (!empty($tags)) { // will be empty if getting all tags fails
				$helper_tags = new JHelperTags;
				foreach ($items as $item) {
					$item->tags = $helper_tags->getItemTags('com_content.article', $item->id, true); // array of tag objects
						
					foreach ($item->tags as $tag) {
						if (array_search($tag->tag_id, $tags) !== false) {
							$article_list[] = $item;
							break;
						}
					}
				}
		
				$items = $article_list;
			}
		} else { // find all article tags in case they need to be shown
			$helper_tags = new JHelperTags;
			foreach ($items as $item) {
				$tags_array = $helper_tags->getItemTags('com_content.article', $item->id, true); // array of tag objects
				if (count($tags_array) > 0) {
					$item->tags = $tags_array;
				}
			}
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
			
			$item->slug = $item->id.':'.$item->alias;
			$item->catslug = $item->catid.':'.$item->category_alias;	
			$item->linktarget = '';
	
			if ($access || in_array($item->access, $authorised)) {
				// We know that user has the privilege to view the article
				$url = ContentHelperRoute::getArticleRoute($item->slug, $item->catslug);				
				if ($link_to == 'modal') {
					$url .= '&tmpl=component&print=1';
					$item->linktarget = 3;
				}				
				$item->link = JRoute::_($url);
				$item->catlink = JRoute::_(ContentHelperRoute::getCategoryRoute($item->catslug));
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
			
			if (isset($item->rating)) {
				$item->vote = $item->rating; // to avoid calls to rating plugin
				$item->vote_count = $item->rating_count;
				unset($item->rating);
				unset($item->rating_count);
			} else {
				$item->vote = null;
				$item->vote_count = null;
			}
				
			// image
			
			$result_array = array('', null); // image tag / error
			
			// Convert the images field to an array.
			$registry = new JRegistry;
			$registry->loadString($item->images);
			$images_array = $registry->toArray();
				
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