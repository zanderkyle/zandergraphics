<?php
/**
 * @package   AkeebaTicketSystem
 * @copyright Copyright (c)2011-2015 Nicholas K. Dionysopoulos
 * @license   GNU General Public License version 3, or later
 */

namespace Akeeba\TicketSystem\Admin\Form\Field;

use FOF30\Form\Field\Text;
use JText;

defined('_JEXEC') or die();

class AutorepliesKeywords extends Text
{
    public function getRepeatable()
    {
        $html = array();

        if($this->item->keywords_title)
        {
            $html[] = '<em>'.JText::_('COM_ATS_AUTOREPLIES_KEYWORDS_TITLE').'</em>';
        }

        if($this->item->keywords_text)
        {
            $html[] = '<em>'.JText::_('COM_ATS_AUTOREPLIES_KEYWORDS_TEXT').'</em>';
        }

        return implode('<br/>', $html);
    }
}
