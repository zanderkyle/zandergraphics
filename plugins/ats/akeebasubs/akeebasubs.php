<?php
/**
 * @package        ats
 * @copyright      Copyright (c)2010-2014 Nicholas K. Dionysopoulos / AkeebaBackup.com
 * @license        GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
 */

defined('_JEXEC') or die();

use FOF30\Container\Container;

/**
 * Akeeba Subscriptions integration for Akeeba Ticket System
 *
 * Tip: You can fork this plugin to create integrations with other subscription components / services.
 */
class plgAtsAkeebasubs extends JPlugin
{

	/** @var null|bool Is Akeeba Subscriptions installed on this site? */
	private $hasSubscriptionsComponent = null;

	/** @var array Active and inactive subscriptions per user ID */
	private static $subsPerUser = array();

    public function __construct(&$subject, $config = array())
    {
        parent::__construct($subject, $config);

        if (!defined('FOF30_INCLUDED') && !@include_once(JPATH_LIBRARIES . '/fof30/include.php'))
        {
            throw new RuntimeException('FOF 3.0 is not installed', 500);
        }
    }

	/**
	 * Is the Akeeba Subscriptions component installed?
	 *
	 * @return  bool  True if Akeeba Subscriptions is installed
	 */
	public function onAtsHasSubscriptionsComponent()
	{
		if (is_null($this->hasSubscriptionsComponent))
		{
			JLoader::import('joomla.filesystem.folder');
			$this->hasSubscriptionsComponent = JFolder::exists(JPATH_ADMINISTRATOR . '/components/com_akeebasubs');

			if ($this->hasSubscriptionsComponent && !defined('AKEEBASUBS_VERSION'))
			{
				include_once JPATH_ADMINISTRATOR . '/components/com_akeebasubs/version.php';
			}

			if ($this->hasSubscriptionsComponent)
			{
				$this->hasSubscriptionsComponent = JFolder::exists(JPATH_ADMINISTRATOR . '/components/com_akeebasubs/View');
			}
		}

		return $this->hasSubscriptionsComponent;
	}

	/**
	 * Get a list of the subscription levels (IDs and names) for a particular user account.
	 *
	 * @param   JUser $user The user to check
	 *
	 * @return  array  An object with arrays for active and inactive subscriptions
	 */
	public function onAtsGetSubscriptionsList(JUser $user = null)
	{
		// Sanity checks: please do not remove the following four if-blocks if you fork this plugin.
		if ($user->username == 'system')
		{
			return (object) array(
				'active'   => array(),
				'inactive' => array(),
			);
		}

		if (!$this->onAtsHasSubscriptionsComponent())
		{
			return (object) array(
				'active'   => array(),
				'inactive' => array(),
			);
		}

		if (!($user instanceof JUser))
		{
			$user = JFactory::getUser();
		}

		if (!array_key_exists($user->id, self::$subsPerUser) && ($user->id <= 0))
		{
            self::$subsPerUser[ $user->id ] = (object) array(
				'active'   => array(),
				'inactive' => array()
			);
		}

		// Inside this if-block we fetch the actual data from the subscriptions component
		if (!array_key_exists($user->id, self::$subsPerUser))
		{
            $container = Container::getInstance('com_akeebasubs');
            $model = $container->factory->model('Subscriptions')->tmpInstance();

            $model->with(array('level'));

			// Get a list of active and inactive subscriptions
			$subs = $model
			                ->limit(0)
			                ->limitstart(0)
			                ->paystate('C')
			                ->user_id($user->id)
			                ->get();

			$subscriptions_active   = array();
			$subscriptions_inactive = array();

			// Separate the active from the inactive subscriptions
			if (!empty($subs))
			{
				foreach ($subs as $sid => $sub)
				{
					$name = $sub->level->title;

					if ($sub->enabled)
					{
						if (!in_array($name, $subscriptions_active))
						{
							$subscriptions_active[] = $name;
						}
					}
					else
					{
						if (!in_array($name, $subscriptions_inactive))
						{
							$subscriptions_inactive[] = $name;
						}
					}
				}
			}

			// Add the subscriptions of this user to the internal cache. This is required to avoid unnecessary database
			// queries.
            self::$subsPerUser[ $user->id ] = (object) array(
				'active'   => $subscriptions_active,
				'inactive' => $subscriptions_inactive
			);
		}

		// Return the subscriptions list for this user from the cache
		return self::$subsPerUser[ $user->id ];
	}

	/**
	 * Returns the country of the user from the data stored in Akeeba Subscriptions
	 *
	 * @param   int  $userid    Joomla user id
	 * @param   bool $isManager Is the current user a manager?
	 *
	 * @return  array|null  An array with the country code and name, or null if we can't display it
	 */
	public function onAtsGetSubscriptionCountry($userid, $isManager)
	{
		static $cache = array();

		if (!$this->onAtsHasSubscriptionsComponent())
		{
			return null;
		}

		// I don't want to display the flag
		if ($this->params->get('displayCountry', 2) == 0)
		{
			return null;
		}
		// Display it to managers only
		elseif ($this->params->get('displayCountry', 2) == 2 && !$isManager)
		{
			return null;
		}

		if (!isset($cache[ $userid ]))
		{
            $container = Container::getInstance('com_akeebasubs');
            $kuser     = $container->factory->model('Users')->tmpInstance();

			$kuser->find(array('user_id' => $userid));

			$cache[$userid] = array($kuser->country, '');

            $cache[$userid][1] = \Akeeba\Subscriptions\Admin\Helper\Select::decodeCountry($cache[$userid][0]);
		}

		// The user is not registered inside Akeeba Subscriptions
		if (!$cache[ $userid ])
		{
			return null;
		}

		return $cache[ $userid ];
	}
}