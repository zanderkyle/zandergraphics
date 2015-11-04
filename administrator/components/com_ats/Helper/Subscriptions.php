<?php
/**
 * @package   AkeebaTicketSystem
 * @copyright Copyright (c)2011-2015 Nicholas K. Dionysopoulos
 * @license   GNU General Public License version 3, or later
 */

namespace Akeeba\TicketSystem\Admin\Helper;

use FOF30\Container\Container;
use JUser;

defined('_JEXEC') or die;

class Subscriptions
{
    /**
     * Do we have a subscriptions component?
     *
     * @return bool
     */
    public static function hasSubscriptionsComponent()
    {
        static $result = null;

        if (is_null($result))
        {
            $container = Container::getInstance('com_ats');

            $container->platform->importPlugin('ats');

            $results = $container->platform->runPlugins('onAtsHasSubscriptionsComponent', array());

            $result = false;

            if (is_array($results) && !empty($results))
            {
                foreach ($results as $r)
                {
                    $result = $result || (bool)$r;
                }
            }
        }

        return $result;
    }

    /**
     * Get a list of the subscription levels (IDs and names) for a particular
     * user.
     *
     * @param JUser $user The user to check
     *
     * @return object An object with arrays for active and inactive subscriptions
     */
    public static function getSubscriptionsList(JUser $user = null)
    {
        static $cache = array();

        if ($user->username == 'system')
        {
            return (object)array(
                'active'   => array(),
                'inactive' => array(),
            );
        }

        if ( !self::hasSubscriptionsComponent())
        {
            return (object)array(
                'active'   => array(),
                'inactive' => array(),
            );
        }

        $container = Container::getInstance('com_ats');
        $platform  = $container->platform;

        if ( !($user instanceof JUser))
        {
            $user = $platform->getUser();
        }

        if ( !array_key_exists($user->id, $cache) && ($user->id <= 0))
        {
            $cache[$user->id] = (object)array(
                'active'   => array(),
                'inactive' => array()
            );
        }

        if ( !array_key_exists($user->id, $cache))
        {
            $subs = (object)array(
                'active'   => array(),
                'inactive' => array(),
            );

            $platform->importPlugin('ats');
            $results = $platform->runPlugins('onAtsGetSubscriptionsList', array($user));

            if (is_array($results) && !empty($results))
            {
                foreach ($results as $r)
                {
                    if (!is_object($r))
                    {
                        continue;
                    }

                    if (!isset($r->active) || !isset($r->inactive))
                    {
                        continue;
                    }

                    if (is_array($r->active))
                    {
                        $subs->active = array_merge($subs->active, $r->active);
                    }

                    if (is_array($r->inactive))
                    {
                        $subs->inactive = array_merge($subs->inactive, $r->inactive);
                    }
                }
            }

            $cache[$user->id] = $subs;
        }

        return $cache[$user->id];
    }
}