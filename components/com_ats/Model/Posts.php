<?php
/**
 * @package   AkeebaTicketSystem
 * @copyright Copyright (c)2011-2015 Nicholas K. Dionysopoulos
 * @license   GNU General Public License version 3, or later
 */

namespace Akeeba\TicketSystem\Site\Model;

use FOF30\Container\Container;

defined('_JEXEC') or die;

/**
 * Class Posts
 *
 * We simply expose the backend model to the frontend. Since we're using the BasicFactory, there are no security issues,
 * public visitors can't reach this model
 *
 * @package Akeeba\TicketSystem\Site\Model
 */
class Posts extends \Akeeba\TicketSystem\Admin\Model\Posts
{
    public function __construct(Container $container, array $config = array())
    {
        parent::__construct($container, $config);

        $this->with(array('ticket'));
    }

    public function tmpInstance()
    {
        parent::tmpInstance();

        // Manually reapply the eager loading relation, I always need it even after resetting the model
        return $this->with(array('ticket'));
    }

    /**
     * Checks if the post is the first one
     *
     * @param   int   $post_id   Post id
     *
     * @return  bool
     */
    public function isFirstOne($post_id = null)
    {
        if($post_id)
        {
            $this->find($post_id);
        }

        $db = $this->getDbo();
        // Do I have a post previous of this one?
        $query = $db->getQuery(true)
                    ->select('COUNT(*)')
                    ->from('#__ats_posts')
                    ->where('ats_ticket_id = '.$this->ats_ticket_id)
                    ->where('ats_post_id < '.$this->ats_post_id);

        return !($db->setQuery($query)->loadResult());
    }
}
