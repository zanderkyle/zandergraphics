<?php
/**
 * @package   ats
 * @copyright Copyright (c)2011-2014 Nicholas K. Dionysopoulos / AkeebaBackup.com
 * @license   GNU GPL v3 or later
 */

namespace Akeeba\TicketSystem\Site\Helper;

use FOF30\Container\Container;

defined('_JEXEC') or die;

class Router
{
	static function getAndPop(&$query, $key, $default = null)
	{
		if (array_key_exists($key, $query))
		{
			$value = $query[$key];
			unset($query[$key]);

			return $value;
		}
		else
		{
			return $default;
		}
	}

	/**
	 * Finds a menu whose query parameters match those in $qoptions
	 *
	 * @param   array  $qoptions  The query parameters to look for
	 * @param   array  $params    The menu parameters to look for
	 *
	 * @return  null|object  Null if not found, or the menu item if we did find it
	 */
	static public function findMenu($qoptions = array(), $params = null)
	{
		// Convert $qoptions to an object
		if (empty($qoptions) || !is_array($qoptions))
		{
			$qoptions = array();
		}

		$menus = \JMenu::getInstance('site');
		$menuitem = $menus->getActive();

		// First check the current menu item (fastest shortcut!)
		if (is_object($menuitem))
		{
			if (self::checkMenu($menuitem, $qoptions, $params))
			{
				return $menuitem;
			}
		}

		foreach ($menus->getMenu() as $item)
		{
			if (self::checkMenu($item, $qoptions, $params))
			{
				return $item;
			}
		}

		return null;
	}

	/**
	 * Checks if a menu item conforms to the query options and parameters specified
	 *
	 * @param object $menu     A menu item
	 * @param array  $qoptions The query options to look for
	 * @param array  $params   The menu parameters to look for
	 *
	 * @return bool
	 */
	static public function checkMenu($menu, $qoptions, $params = null)
	{
		static $languages = array();

        $container = Container::getInstance('com_ats');

		if (empty($languages))
		{
			$languages = array('*');

			if (!$container->platform->isCli() && !$container->platform->isBackend())
			{
                /** @var \JApplicationSite $app */
                $app = \JFactory::getApplication();

				if ($app->getLanguageFilter())
				{
					// Get default site language
					$lg = \JFactory::getLanguage();

					$languages = array(
						$lg->getTag(),
						'*'
					);

					$languages = array_unique($languages);
				}
			}
		}

		if (isset($qoptions['lang']))
		{
			if (!empty($qoptions['lang']))
			{
				$languages = array($qoptions['lang']);
			}

			unset($qoptions['lang']);
		}

		if (!empty($menu->language))
		{
			if (!in_array($menu->language, $languages))
			{
				return false;
			}
		}

		$query = is_object($menu) ? $menu->query : array();

		foreach ($qoptions as $key => $value)
		{
			// A null value was requested. Huh.
			if (is_null($value))
			{
				// If the key is set and is not empty it's not the menu item you're looking for
				if (isset($query[$key]) && !empty($query[$key]))
				{
					return false;
				}

				continue;
			}

			if (!isset($query[$key]))
			{
				return false;
			}

			if ($key == 'view')
			{
				// Treat views case-insensitive
				if (strtolower($query[$key]) != strtolower($value))
				{
					return false;
				}
			}
			elseif ($query[$key] != $value)
			{
				return false;
			}
		}

		if (!is_null($params))
		{
			$menus = \JMenu::getInstance('site');
			$check = $menu->params instanceof \JRegistry ? $menu->params : $menus->getParams($menu->id);

			foreach ($params as $key => $value)
			{
				if (is_null($value))
				{
					continue;
				}

				if ($check->get($key) != $value)
				{
					return false;
				}
			}
		}

		return true;
	}

	static public function preconditionSegments($segments)
	{
		$newSegments = array();

		if (!empty($segments))
		{
			foreach ($segments as $segment)
			{
				if (strstr($segment, ':'))
				{
					$segment = str_replace(':', '-', $segment);
				}

				if (is_array($segment))
				{
					$newSegments[] = implode('-', $segment);
				}
				else
				{
					$newSegments[] = $segment;
				}
			}
		}

		return $newSegments;
	}
}