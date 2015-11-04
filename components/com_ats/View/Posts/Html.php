<?php
/**
 * @package   AkeebaTicketSystem
 * @copyright Copyright (c)2011-2015 Nicholas K. Dionysopoulos
 * @license   GNU General Public License version 3, or later
 */

namespace Akeeba\TicketSystem\Site\View\Posts;

use Akeeba\TicketSystem\Admin\Helper\Permissions;
use Akeeba\TicketSystem\Site\Model\Posts;

defined('_JEXEC') or die;

class Html extends \FOF30\View\DataView\Html
{
    /** @var bool Is the current user manager of the category? */
    protected $isManager = false;

    protected function onBeforeEdit()
    {
        parent::onBeforeEdit();

        $this->container->platform->importPlugin('ats');

        /** @var Posts $item */
        $item = $this->item;

        $this->isManager = Permissions::isManager($item->ticket->catid);
    }
}