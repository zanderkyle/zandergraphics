<?php
/**
* @version 1.0.0
* @package RSSEO! 1.0.0
* @copyright (C) 2009-2012 www.rsjoomla.com
* @license GPL, http://www.gnu.org/licenses/gpl-2.0.html
*/

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

class rsseoMenuHelper
{
	public static function generateSitemap() {
		$db		= JFactory::getDBO();
		$query	= $db->getQuery(true);
		$return = '';
		
		// Add stylesheet
		JFactory::getDocument()->addStyleSheet(JURI::root(true).'/components/com_rsseo/style.css');
		
		// Get menus
		$menus = rsseoMenuHelper::getConfig('sitemap_menus');
		//get excluded items
		$excludes = rsseoMenuHelper::getConfig('sitemap_excludes');
		
		if (empty($menus)) {
			return $return;
		}
		
		if (!empty($menus)) {
			foreach ($menus as $menu) {
				$params = new JRegistry;
				$params->set('menutype',$menu);
				$params->set('ignore',$excludes);
				
				if ($items = self::getList($params)) {
					$html = self::render($items);
					
					if (empty($html)) {
						continue;
					}
					
					$query->clear();
					$query->select($db->qn('title'))->from($db->qn('#__menu_types'))->where($db->qn('menutype').' = '.$db->quote($menu));
					$db->setQuery($query);
					$title = $db->loadResult();
					
					$return .= '<div class="rsseo_title">'. $title .'</div>';
					$return .= $html;
				}
			}
		}
		
		return $return;
	}
	
	/**
	 * Get a list of the menu items.
	 *
	 * @param	JRegistry	$params	The module options.
	 *
	 * @return	array
	 * @since	1.5
	 */
	protected static function getConfig($name = null) {
		$component = JComponentHelper::getComponent('com_rsseo');
		$params = $component->params->toObject();
		
		if ($name != null) {
			if (isset($params->$name)) return $params->$name;
				else return false;
		}
		else return $params;
	}
	
	protected static function getList($params) {
		$app = JFactory::getApplication();
		$menu = $app->getMenu();

		// Get active menu item
		$active = self::getActive();
		$user = JFactory::getUser();
		$levels = $user->getAuthorisedViewLevels();
		asort($levels);
		$path    = $active->tree;
		$start   = 1;
		$end     = 0;
		$showAll = 1;
		$items   = $menu->getItems('menutype', $params->get('menutype'));
		$ignored = $params->get('ignore');

		$lastitem = 0;
		
		if ($items)
		{
			$remove = array();
			if (!empty($ignored)) {
				foreach($items as $i => $item) {
					if (in_array($item->id, $ignored)) {
						if ($elements = $menu->getItems('parent_id', (int) $item->id)) {
							foreach ($elements as $element) {
								$remove[] = $element->id;
							}
						}
						
						$remove[] = $item->id;
					}
				}
			}
			
			foreach($items as $i => $item)
			{
				if (in_array($item->id,$remove)) {
					unset($items[$i]);
					continue;
				}
				
				
				if (($start && $start > $item->level)
					|| ($end && $item->level > $end)
					|| (!$showAll && $item->level > 1 && !in_array($item->parent_id, $path))
					|| ($start > 1 && !in_array($item->tree[$start - 2], $path)))
				{
					unset($items[$i]);
					continue;
				}

				$item->deeper     = false;
				$item->shallower  = false;
				$item->level_diff = 0;

				if (isset($items[$lastitem]))
				{
					$items[$lastitem]->deeper     = ($item->level > $items[$lastitem]->level);
					$items[$lastitem]->shallower  = ($item->level < $items[$lastitem]->level);
					$items[$lastitem]->level_diff = ($items[$lastitem]->level - $item->level);
				}

				$item->parent = (boolean) $menu->getItems('parent_id', (int) $item->id, true);

				$lastitem     = $i;
				$item->active = false;
				$item->flink  = $item->link;

				// Reverted back for CMS version 2.5.6
				switch ($item->type)
				{
					case 'separator':
						// No further action needed.
						continue;

					case 'url':
						if ((strpos($item->link, 'index.php?') === 0) && (strpos($item->link, 'Itemid=') === false))
						{
							// If this is an internal Joomla link, ensure the Itemid is set.
							$item->flink = $item->link . '&Itemid=' . $item->id;
						}
						break;

					case 'alias':
						// If this is an alias use the item id stored in the parameters to make the link.
						$item->flink = 'index.php?Itemid=' . $item->params->get('aliasoptions');
						break;

					default:
						$router = JSite::getRouter();
						if ($router->getMode() == JROUTER_MODE_SEF)
						{
							$item->flink = 'index.php?Itemid=' . $item->id;
						}
						else
						{
							$item->flink .= '&Itemid=' . $item->id;
						}
						break;
				}

				if (strcasecmp(substr($item->flink, 0, 4), 'http') && (strpos($item->flink, 'index.php?') !== false))
				{
					$item->flink = JRoute::_($item->flink, true, $item->params->get('secure'));
				}
				else
				{
					$item->flink = JRoute::_($item->flink);
				}

				// We prevent the double encoding because for some reason the $item is shared for menu modules and we get double encoding
				// when the cause of that is found the argument should be removed
				$item->title        = htmlspecialchars($item->title, ENT_COMPAT, 'UTF-8', false);
				$item->anchor_css   = htmlspecialchars($item->params->get('menu-anchor_css', ''), ENT_COMPAT, 'UTF-8', false);
				$item->anchor_title = htmlspecialchars($item->params->get('menu-anchor_title', ''), ENT_COMPAT, 'UTF-8', false);
				$item->menu_image   = $item->params->get('menu_image', '') ? htmlspecialchars($item->params->get('menu_image', ''), ENT_COMPAT, 'UTF-8', false) : '';
			}

			if (isset($items[$lastitem]))
			{
				$items[$lastitem]->deeper     = (($start?$start:1) > $items[$lastitem]->level);
				$items[$lastitem]->shallower  = (($start?$start:1) < $items[$lastitem]->level);
				$items[$lastitem]->level_diff = ($items[$lastitem]->level - ($start?$start:1));
			}
		}
		
		return $items;
	}
	
	/**
	 * Get active menu item.
	 *
	 * @return	object
	 * @since	3.0
	 */
	protected static function getActive() {
		$menu = JFactory::getApplication()->getMenu();

		// If no active menu, use current or default
		$active = ($menu->getActive()) ? $menu->getActive() : $menu->getDefault();

		return $active;
	}
	
	/**
	 * Render HTML
	 *
	 * @return	object
	 * @since	3.0
	 */
	protected static function render($items) {
		$html[] = '<ul class="rsseo_links">';
		
		foreach ($items as $i => &$item) {
			$class = 'item-'.$item->id;

			if ($item->deeper) {
				$class .= ' deeper';
			}

			if ($item->parent) {
				$class .= ' parent';
			}

			if (!empty($class)) {
				$class = ' class="'.trim($class) .'"';
			}

			$html[] = '<li'.$class.'>';

			// Render the menu item.
			switch ($item->type) {
				case 'separator':
					$html[] = '<span class="separator">'.$item->title.'</span>';
				break;
				
				case 'component':
					$html[] = '<a href="'.$item->flink.'">'.$item->title.'</a>';
				break;

				case 'url':
				default:				
					$html[] = '<a href="'.JFilterOutput::ampReplace(htmlspecialchars($item->flink)).'">'.$item->title.'</a>';
				break;
			}

			// The next item is deeper.
			if ($item->deeper) {
				$html[] = '<ul class="rsseo_links_small">';
			}
			// The next item is shallower.
			elseif ($item->shallower) {
				$html[] = '</li>';
				$html[] = str_repeat('</ul></li>', $item->level_diff);
			}
			// The next item is on the same level.
			else {
				$html[] = '</li>';
			}
		}
		
		$html[] = '</ul>';
		
		return implode("\n", $html);
	}
}