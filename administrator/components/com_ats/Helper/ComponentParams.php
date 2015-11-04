<?php
/**
 * @package   AkeebaTicketSystem
 * @copyright Copyright (c)2011-2015 Nicholas K. Dionysopoulos
 * @license   GNU General Public License version 3, or later
 */

namespace Akeeba\TicketSystem\Admin\Helper;

use JComponentHelper;
use JFactory;
use JLoader;

defined('_JEXEC') or die;

/**
 * A helper class to quickly get the component parameters
 */
abstract class ComponentParams
{

	/**
	 * Cached component parameters
	 *
	 * @var \Joomla\Registry\Registry
	 */
	private static $params = null;

    /**
     * Clears the internal cache, forcing the object to relaod the params directly from the database
     */
    public static function purge()
    {
        self::$params = null;
    }

	/**
	 * Returns the value of a component configuration parameter
	 *
	 * @param   string $key     The parameter to get
	 * @param   mixed  $default Default value
	 *
	 * @return  mixed
	 */
	public static function getParam($key, $default = null)
	{
		if (!is_object(self::$params))
		{
            $db = JFactory::getDBO();
            $query = $db->getQuery(true)
                        ->select('params')
                        ->from($db->qn('#__extensions'))
                        ->where($db->qn('element').'='.$db->q('com_ats'))
                        ->where($db->qn('type').'='.$db->q('component'));
            $rawparams = $db->setQuery($query)->loadResult();

            self::$params = new \JRegistry();
            self::$params->loadString($rawparams, 'JSON');
		}

		return self::$params->get($key, $default);
	}

	/**
	 * Sets the value of a component configuration parameter
	 *
	 * @param   string $key    The parameter to set
	 * @param   mixed  $value  The value to set
	 *
	 * @return  void
	 */
	public static function setParam($key, $value)
	{
		if (!is_object(self::$params))
		{
            // Force loading of the internal cache
			self::getParam('foobar', '');
		}

		self::$params->set($key, $value);

		$db   = JFactory::getDBO();
		$data = self::$params->toString();

		$sql  = $db->getQuery(true)
		           ->update($db->qn('#__extensions'))
		           ->set($db->qn('params') . ' = ' . $db->q($data))
		           ->where($db->qn('element') . ' = ' . $db->q('com_ats'))
		           ->where($db->qn('type') . ' = ' . $db->q('component'));

		$db->setQuery($sql);

		try
		{
			$db->execute();
		}
		catch (\Exception $e)
		{
			// Don't sweat if it fails
		}
	}

	public static function getCustomTicketStatuses()
	{
		static $statuses = '';

		if(is_array($statuses))
		{
			return $statuses;
		}

		$custom = self::getParam('customStatuses', '');
		$custom = str_replace("\\n" , "\n", $custom);
		$custom = str_replace("\r"  , "\n", $custom);
		$custom = str_replace("\n\n", "\n", $custom);

		$lines = explode("\n", $custom);

		foreach($lines as $line)
		{
			$parts = explode('=', $line);
			if(count($parts) != 2) continue;

			$statuses[$parts[0]] = $parts[1];
		}

		// Uh oh, there are no custom status, let's set an empty array, so next time I can skip the above lines
		if(!$statuses)
		{
			$statuses = array();
		}

		return $statuses;
	}
}