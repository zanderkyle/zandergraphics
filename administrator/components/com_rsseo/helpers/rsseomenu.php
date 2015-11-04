<?php
/**
* @version 1.0.0
* @package RSSEO! 1.0.0
* @copyright (C) 2009-2012 www.rsjoomla.com
* @license GPL, http://www.gnu.org/licenses/gpl-2.0.html
*/

defined('_JEXEC') or die('Restricted access');

abstract class JHtmlRSSeoMenu {
	
	protected static $menus = null;
	protected static $items = null;
	
	/**
	 * Get a list of the available menus.
	 *
	 * @return  string
	 *
	 * @since   11.1
	 */
	public static function menus() {
		if (empty(self::$menus)) {
			$db = JFactory::getDbo();
			$query = $db->getQuery(true);
			$query->select('menutype AS value, title AS text');
			$query->from($db->quoteName('#__menu_types'));
			$query->order('title');
			$db->setQuery($query);
			self::$menus = $db->loadObjectList();
		}

		return self::$menus;
	}
	
	/**
	 * Returns an array of menu items grouped by menu.
	 *
	 * @param   array  $config  An array of configuration options.
	 *
	 * @return  array
	 */
	public static function menuitems($config = array()) {
		if (empty(self::$items)) {
			$db = JFactory::getDbo();
			$query = $db->getQuery(true);
			$query->select('menutype AS value, title AS text');
			$query->from($db->quoteName('#__menu_types'));
			$query->order('title');
			$db->setQuery($query);
			$menus = $db->loadObjectList();

			$query->clear();
			$query->select('a.id AS value, a.title AS text, a.level, a.menutype');
			$query->from('#__menu AS a');
			$query->where('a.parent_id > 0');
			$query->where('a.type <> ' . $db->quote('url'));
			$query->where('a.client_id = 0');

			// Filter on the published state
			if (isset($config['published'])) {
				if (is_numeric($config['published'])) {
					$query->where('a.published = ' . (int) $config['published']);
				} elseif ($config['published'] === '') {
					$query->where('a.published IN (0,1)');
				}
			}

			$query->order('a.lft');

			$db->setQuery($query);
			$items = $db->loadObjectList();

			// Collate menu items based on menutype
			$lookup = array();
			foreach ($items as &$item) {
				if (!isset($lookup[$item->menutype])) {
					$lookup[$item->menutype] = array();
				}
				$lookup[$item->menutype][] = &$item;

				$item->text = str_repeat('- ', $item->level) . $item->text;
			}
			self::$items = array();

			foreach ($menus as &$menu) {
				// Start group:
				self::$items[] = JHtml::_('select.optgroup', $menu->text);

				// Menu items:
				if (isset($lookup[$menu->value])) {
					foreach ($lookup[$menu->value] as &$item) {
						self::$items[] = JHtml::_('select.option', $item->value, $item->text);
					}
				}

				// Finish group:
				self::$items[] = JHtml::_('select.optgroup', $menu->text);
			}
		}

		return self::$items;
	}
	
	/**
	 * Displays an HTML select list of menu items.
	 *
	 * @param   string  $name      The name of the control.
	 * @param   string  $selected  The value of the selected option.
	 * @param   string  $attribs   Attributes for the control.
	 * @param   array   $config    An array of options for the control.
	 *
	 * @return  string
	 */
	public static function menuitemlist($name, $selected = null, $attribs = null, $config = array()) {
		static $count;

		$options = self::menuitems($config);

		return JHtml::_(
			'select.genericlist', $options, $name,
			array(
				'id' => isset($config['id']) ? $config['id'] : 'assetgroups_' . (++$count),
				'list.attr' => (is_null($attribs) ? 'class="inputbox" size="1"' : $attribs),
				'list.select' => $selected,
				'list.translate' => false
			)
		);
	}
}