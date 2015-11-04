<?php
/**
 * @package ats
 * @copyright Copyright (c)2011-2014 Nicholas K. Dionysopoulos / AkeebaBackup.com
 * @license GNU GPL v3 or later
 */

/** @var Akeeba\TicketSystem\Site\View\Tickets\Html $this */

// No direct access
defined('_JEXEC') or die;

use Akeeba\TicketSystem\Admin\Helper\ComponentParams;
use Akeeba\TicketSystem\Admin\Helper\Credits;
use Akeeba\TicketSystem\Admin\Helper\Html;

JHTML::_('behavior.modal');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('behavior.keepalive');

/** @var \Akeeba\TicketSystem\Site\Model\Tickets $ticket */
$ticket    = $this->item;
$container = $this->getContainer();
$platform  = $container->platform;
$token	   = $container->session->getFormToken();

$container->template->addCSS('media://com_ats/css/print.css');

$user      = $platform->getUser();
$ownerUser = $platform->getUser($ticket->created_by);
$component = JComponentHelper::getParams('com_ats');

$isManager = $this->isManager;

// Get the Itemid
$itemId = $this->input->getInt('Itemid',0);

$show_credits     = ComponentParams::getParam('showcredits', 0);
$credits_required = Credits::creditsRequired($ticket->catid, false, $ticket->public, $ticket->priority);
$enough_credits   = Credits::haveEnoughCredits($user->id, $ticket->catid, false, $ticket->public, $ticket->priority);
$totalcredits	  = Credits::creditsCharged('all', $ticket->ats_ticket_id);

$returnURL = base64_encode(JURI::getInstance()->toString());

if($this->item->assigned_to)
{
	$class 		 = 'label-info';
	$assigned_to = JUser::getInstance($this->item->assigned_to)->name;
}
else
{
	$class 		 = 'label-important';
	$assigned_to = JText::_('COM_ATS_TICKETS_UNASSIGNED');
}
?>

<h1 class="ats-ticket-view-sitename">
	<?php echo JFactory::getConfig()->get('sitename') ?>
</h1>

<h2 class="ats-ticket-view-title">
	<span class="ats-ticket-view-title-number">
	#<?php echo $this->escape($this->item->ats_ticket_id); ?>
	</span>
	<?php echo $this->escape($this->item->title); ?>
</h2>

<h3 class="ats-ticket-view-postedin">
	<?php echo JText::sprintf('COM_ATS_TICKET_LBL_POSTEDIN', $this->escape($ticket->joomla_category->title)) ?>
</h3>

<?php if($isManager && ATS_PRO ):?>
<div class="ats-ticket-view-stats">
	<?php echo JText::sprintf('COM_ATS_TICKET_TIMESPENT_MSG', $this->item->timespent); ?>
	<?php if ($show_credits): ?>
	<br/>
	<?php echo JText::sprintf('COM_ATS_TICKET_TOTALCREDITS_MSG', $totalcredits); ?>
	<?php endif; ?>
</div>
<?php endif; ?>

<?php if($this->item->public): ?>
<div class="ats-newticket-pubnote ats-newticket-pubnote-public">
	<?php echo JText::_('COM_ATS_NEWTICKET_MSG_PUBNOTE_PUBLIC') ?>
</div>
<?php else: ?>
<div class="ats-newticket-pubnote ats-newticket-pubnote-private">
	<?php echo JText::_('COM_ATS_NEWTICKET_MSG_PUBNOTE_PRIVATE') ?>
</div>
<?php endif; ?>

<?php if( $isManager && ($this->item->status == 'C') ): ?>
<div class="ats-ticket-closed">
	<?php echo JText::_('COM_ATS_TICKET_MSG_CLOSEDNOTICE_CLOSED_ADMIN') ?>
</div>
<?php endif; ?>

<?php
$posts = $ticket->posts;
$this->items = $posts;
echo $this->loadAnyTemplate('site:com_ats/Posts/print');
$this->items = null;
?>