<?php
/**
 * @package   AkeebaTicketSystem
 * @copyright Copyright (c)2011-2015 Nicholas K. Dionysopoulos
 * @license   GNU General Public License version 3, or later
 */

namespace Akeeba\TicketSystem\Admin\Controller;

defined('_JEXEC') or die;

use Akeeba\TicketSystem\Admin\Helper\Permissions;
use FOF30\Controller\DataController;

class Attachment extends DataController
{
    public function read()
    {
        /** @var \Akeeba\TicketSystem\Admin\Model\Attachments $model */
        $model = $this->getModel();

        if(!$model->getId())
        {
            $this->getIDsFromRequest($model);
        }

        try
        {
            $model->findOrFail();
        }
        catch(\RuntimeException $e)
        {
            // If the attachment is not found raise a 403 error
            $this->container->platform->raiseError(403, \JText::_('JLIB_APPLICATION_ERROR_ACCESS_FORBIDDEN'));
        }

        // I got the attachment, but can I really see it?
        $post   = $model->post;
        $ticket = $post->ticket;

        $isManager = Permissions::isManager($ticket->catid);

        if(Permissions::attachmentPrivate($model, $isManager, $ticket->created_by))
        {
            // Attachment is private (and you can't see it)
            $this->container->platform->raiseError(403, \JText::_('JLIB_APPLICATION_ERROR_ACCESS_FORBIDDEN'));
        }

        if(!Permissions::attachmentVisible($model, $isManager, $post))
        {
            // Attachment is not visible (and you can't see it)
            $this->container->platform->raiseError(403, \JText::_('JLIB_APPLICATION_ERROR_ACCESS_FORBIDDEN'));
        }

        $model->doDownload();
    }
}