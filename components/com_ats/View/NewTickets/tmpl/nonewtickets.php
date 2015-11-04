<?php
/**
 * @package ats
 * @copyright Copyright (c)2011-2014 Nicholas K. Dionysopoulos / AkeebaBackup.com
 * @license GNU GPL v3 or later
 */

/** @var Akeeba\TicketSystem\Site\View\NewTickets\Html $this */
defined('_JEXEC') or die;

use Akeeba\TicketSystem\Admin\Helper\Html;

// Load the required CSS
$this->getContainer()->template->addCSS('media://com_ats/css/frontend.css');

?>
<div class="akeeba-bootstrap">

<?php echo Html::loadposition('ats-top'); ?>
<?php echo Html::loadposition('ats-newticket-top'); ?>

<?php if ($this->getPageParams()->get('show_page_heading', 1)) : ?>
	<h1>
		<?php echo JText::sprintf('COM_ATS_NEWTICKET_TITLE', $this->escape($this->category->title)); ?>
	</h1>
<?php endif; ?>

<?php echo Html::loadposition('ats-replyarea-overlay'); ?>
<?php echo Html::loadposition('ats-nonewtickets'); ?>

<?php echo Html::loadposition('ats-offline'); ?>

<?php echo Html::loadposition('ats-newticket-bottom'); ?>
<?php echo Html::loadposition('ats-bottom'); ?>

</div>