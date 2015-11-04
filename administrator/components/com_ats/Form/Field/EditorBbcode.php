<?php
/**
 * @package   AkeebaTicketSystem
 * @copyright Copyright (c)2011-2015 Nicholas K. Dionysopoulos
 * @license   GNU General Public License version 3, or later
 */

namespace Akeeba\TicketSystem\Admin\Form\Field;

use Akeeba\TicketSystem\Admin\Helper\Editor;
use FOF30\Form\Field\Text;

defined('_JEXEC') or die();

class EditorBbcode extends Text
{
	public function getInput()
	{
		$html = Editor::showEditorBBcode('reply', 'Bbcode', $this->value, '95%', 400, 80, 20, array(), true);

		return $html;
	}
}
