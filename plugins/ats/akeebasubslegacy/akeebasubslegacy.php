<?php
/**
 * @package        ats
 * @copyright      Copyright (c)2010-2014 Nicholas K. Dionysopoulos / AkeebaBackup.com
 * @license        GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
 */

defined('_JEXEC') or die();

/**
 * Akeeba Subscriptions integration for Akeeba Ticket System
 *
 * Tip: You can fork this plugin to create integrations with other subscription components / services.
 */
class plgAtsAkeebasubslegacy extends JPlugin
{

	/** @var null|bool Is Akeeba Subscriptions installed on this site? */
	private $hasSubscriptionsComponent = null;

	/** @var array Active and inactive subscriptions per user ID */
	private $subsPerUser = array();

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
			$this->hasSubscriptionsComponent = (JFolder::exists(JPATH_ADMINISTRATOR . '/components/com_akeebasubs') && !JFolder::exists(JPATH_ADMINISTRATOR . '/components/com_akeebasubs/View'));

			if ($this->hasSubscriptionsComponent && !defined('AKEEBASUBS_VERSION'))
			{
				include_once JPATH_ADMINISTRATOR . '/components/com_akeebasubs/version.php';
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

		if (!array_key_exists($user->id, $this->subsPerUser) && ($user->id <= 0))
		{
			$this->subsPerUser[ $user->id ] = (object) array(
				'active'   => array(),
				'inactive' => array()
			);
		}

		// Inside this if-block we fetch the actual data from the subscriptions component
		if (!array_key_exists($user->id, $this->subsPerUser))
		{
			// Get a list of active and inactive subscriptions
			$subs = F0FModel::getTmpInstance('Subscriptions', 'AkeebasubsModel')
			                ->limit(0)
			                ->limitstart(0)
			                ->paystate('C')
			                ->user_id($user->id)
			                ->getList();

			$subscriptions_active   = array();
			$subscriptions_inactive = array();

			// Separate the active from the inactive subscriptions
			if (!empty($subs))
			{
				foreach ($subs as $sid => $sub)
				{
					$name = $sub->title;

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
			$this->subsPerUser[ $user->id ] = (object) array(
				'active'   => $subscriptions_active,
				'inactive' => $subscriptions_inactive
			);
		}

		// Return the subscriptions list for this user from the cache
		return $this->subsPerUser[ $user->id ];
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
			if (!class_exists('AkeebasubsHelperSelect'))
			{
				@include_once JPATH_ADMINISTRATOR . '/components/com_akeebasubs/helpers/select.php';
			}

			$kuser = clone F0FModel::getTmpInstance('Users', 'AkeebasubsModel')->getTable();
			$kuser->load(array('user_id' => $userid));

			$cache[$userid] = array($kuser->country, '');

			if (class_exists('AkeebasubsHelperSelect'))
			{
				$cache[$userid][1] = AkeebasubsHelperSelect::decodeCountry($cache[$userid][0]);
			}
		}

		// The user is not registered inside Akeeba Subscriptions
		if (!$cache[ $userid ])
		{
			return null;
		}

		return $cache[ $userid ];
	}
}