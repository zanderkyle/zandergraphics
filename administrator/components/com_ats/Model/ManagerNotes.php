<?php
/**
 * @package   AkeebaTicketSystem
 * @copyright Copyright (c)2011-2015 Nicholas K. Dionysopoulos
 * @license   GNU General Public License version 3, or later
 */
namespace Akeeba\TicketSystem\Admin\Model;

use Akeeba\TicketSystem\Admin\Helper\Bbcode;
use FOF30\Container\Container;

defined('_JEXEC') or die;

/**
 * @property    int     ats_managernote_id      Primary key
 * @property    string  note_html               HTML content of the note
 * @property    string  note                    Raw BBCode content of the note
 * @property    int     ats_ticket_id           Linked ticket
 *
 * @property-read   Tickets     ticket      Linked ticket
 */
class ManagerNotes extends DefaultDataModel
{
    public function __construct(Container $container, array $config = array())
    {
        parent::__construct($container, $config);

        $this->belongsTo('ticket', 'Tickets', 'ats_ticket_id', 'ats_ticket_id');
    }

    public function check()
    {
        // Process the content
        $content = htmlentities($this->note, ENT_NOQUOTES, 'UTF-8', false);
        $content = str_replace('<', '&lt;', $content);
        $content = str_replace('>', '&gt;', $content);

        if(!empty($content))
        {
            $this->note_html = Bbcode::parseBBCode($content);
        }
    }
}