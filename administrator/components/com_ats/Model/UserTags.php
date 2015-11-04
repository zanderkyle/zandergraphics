<?php
/**
 * @package   AkeebaTicketSystem
 * @copyright Copyright (c)2011-2015 Nicholas K. Dionysopoulos
 * @license   GNU General Public License version 3, or later
 */

namespace Akeeba\TicketSystem\Admin\Model;

use FOF30\Container\Container;

defined('_JEXEC') or die;

/**
 * Class UserTags
 *
 * @property    string  descr   Tag description
 * @property    string  title   Tag title
 *
 * @package Akeeba\TicketSystem\Admin\Model
 */
class UserTags extends DefaultDataModel
{
    public function __construct(Container $container, array $config = array())
    {
        parent::__construct($container, $config);

        $this->addBehaviour('Filters');

        $this->addSkipCheckField('descr');
        $this->addSkipCheckField('ordering');
    }

    /**
     * Returns an array containing the tags belonging to the select user
     *
     * @param   int     $userid     Userid
     *
     * @return  array   User tag ids
     */
    public function loadTagsByUser($userid)
    {
        static $tags = array();

        if(!$userid)
        {
            return array();
        }

        if(!isset($tags[$userid]))
        {
            // I can't use JFactory::getUser since data are store inside the session. So I won't see any updated info
            // until I logout

            $db = $this->getDbo();

            $query = $db->getQuery(true)
                        ->select($db->qn('params'))
                        ->from($db->qn('#__users'))
                        ->where($db->qn('id').' = '.$db->q($userid));

            $params = $db->setQuery($query)->loadResult();
            $params = json_decode($params, true);

            if(isset($params['ats_tags']))
            {
                $tags[$userid] = $params['ats_tags'];
            }
            else
            {
                $tags[$userid] = array();
            }
        }

        return $tags[$userid];
    }

    /**
     * Decode the tag id, caching the result. We can't use relations since tags are store as serialised string inside
     * Joomla user table
     *
     * @param   int $tagId
     *
     * @return  \Akeeba\TicketSystem\Admin\Model\UserTags
     */
    public function decodeTag($tagId)
    {
        static $cache = array();

        if(!isset($cache[$tagId]))
        {
            $cache[$tagId] = clone $this->find($tagId);
        }

        return $cache[$tagId];
    }
}