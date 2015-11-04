<?php
/**
 * @package ats
 * @copyright Copyright (c)2011-2014 Nicholas K. Dionysopoulos / AkeebaBackup.com
 * @license GNU GPL v3 or later
 */

/** @var Akeeba\TicketSystem\Site\View\NewTickets\Html $this */
defined('_JEXEC') or die;

use Akeeba\TicketSystem\Admin\Helper\Select;

$itemId = $this->input->getInt('Itemid', 0);

?>
<h3><?php echo JText::_('COM_ATS_NEWTICKET_HEAD_SELECTCATEGORY') ?></h3>

<p><?php echo JText::_('COM_ATS_NEWTICKET_MSG_SELECTCATEGORY') ?></p>

<form action="index.php" method="GET">
	<input type="hidden" name="option" value="com_ats" />
	<input type="hidden" name="view" value="NewTicket" />
	<?php if ($itemId): ?>
	<input type="hidden" name="Itemid" value="<?php echo $itemId ?>" />
	<?php endif; ?>
	<input type="hidden" name="<?php echo $this->getContainer()->session->getFormToken();?>" value="1" id="token" />

	<p>
	<?php echo Select::getCategories($this->getModel()->getState('category'), 'category', array('class' => 'atscategory'))?>
	</p>

	<button type="submit" class="btn btn-success">
		<?php echo JText::_('COM_ATS_TICKETS_BUTTON_NEWTICKET'); ?>
	</button>
</form>