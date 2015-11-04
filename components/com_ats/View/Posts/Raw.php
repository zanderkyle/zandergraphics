<?php
/**
 * @package   AkeebaTicketSystem
 * @copyright Copyright (c)2011-2015 Nicholas K. Dionysopoulos
 * @license   GNU General Public License version 3, or later
 */

namespace Akeeba\TicketSystem\Site\View\Posts;

use Akeeba\TicketSystem\Admin\Helper\Permissions;
use Akeeba\TicketSystem\Site\Model\Posts;
use FOF30\View\DataView\Html;

defined('_JEXEC') or die;

class Raw extends Html
{
    /** @var  bool  Is the current user a manager? */
    protected $isManager;

    protected function onBeforeRead()
    {
        parent::onBeforeRead();

        /** @var Posts $item */
        $item  = $this->item;
        $catid = $item->ticket->catid;

        $this->isManager = Permissions::isManager($catid);
    }
}