<?php
/**
 * @package ats
 * @copyright Copyright (c)2011-2014 Nicholas K. Dionysopoulos / AkeebaBackup.com
 * @license GNU GPL v3 or later
 */

/** @var Akeeba\TicketSystem\Site\View\Tickets\Html $this */
/** @var \Akeeba\TicketSystem\Site\Model\Tickets $ticket */

// No direct access
defined('_JEXEC') or die;

use Akeeba\TicketSystem\Admin\Helper\Format;
use Akeeba\TicketSystem\Admin\Helper\Html;

/*
 * Params incoming loadAnyTemplate:
 *
 * $ticket          Ticket to display
 * $showAssigned    Should I display the assigned user dropdown?
 * $showCategory    Should I display the category?
 * $showMy          Should I display the "my" icon?
 * $showStatus      Should I display the status?
 * $showStatusDD    Should I display the status dropdown?
 * $extraRowAttr    Additional attributes to attach to the table row
 * $extraLinkAttr   Additional attributes to attach to the ticket link
 */

if(!isset($showAgo))            $showAgo        = 0;
if(!isset($showAssigned))       $showAssigned   = 1;
if(!isset($showCategory))       $showCategory   = 0;
if(!isset($showMy))             $showMy         = 1;
if(!isset($showStatus))         $showStatus     = 1;
if(!isset($showStatusDD))       $showStatusDD   = 1;
if(!isset($extraRowAttr))       $extraRowAttr   = '';
if(!isset($extraLinkAttr))      $extraLinkAttr  = '';

$ticketURL = JRoute::_('index.php?option=com_ats&view=Ticket&id='.$ticket->ats_ticket_id);
$platform  = $this->container->platform;

$throbber = $this->container->template->parsePath('media://com_ats/images/throbber.gif');

$createdOn = Format::date2($ticket->created_on, '', true);
$createdBy = $platform->getUser($ticket->created_by)->username;

$modifiedOn = $platform->getDate($ticket->modified_on);

if($modifiedOn->toUnix() > 90000)
{
	$lastOn = $ticket->modified_on;
	$modifiedBy = $ticket->modified_by ? $ticket->modified_by : $ticket->created_by;
	$lastBy = $platform->getUser($modifiedBy)->username;
}
else
{
	$lastOn = $createdOn;
    $lastBy = $createdBy;
}

$returnURL       = base64_encode(JUri::getInstance()->toString());
$curUser	     = $platform->getUser();
$mine 		     = $curUser->id == $ticket->created_by;
$icon 		     = $ticket->public ? 'icon-eye-open' : 'icon-eye-close';
$badge 		     = $ticket->public ? 'badge-warning' : 'badge-success';
$assign_class    = $ticket->assigned_to ? 'badge-info': '';
$assigned_to     = $ticket->assigned_to ? $ticket->getUser($ticket->assigned_to)->name : JText::_('COM_ATS_TICKETS_UNASSIGNED');
$ago             = Format::timeAgo($platform->getDate($lastOn)->toUnix());
$lastOn          = Format::date2($lastOn, '', true);
?>
<tr id="ats-ticket-<?php echo $ticket->ats_ticket_id ?>" <?php echo $extraRowAttr?>>
	<td>
		<h4>
		<input type="hidden" class="ats_ticket_id" value="<?php echo $ticket->ats_ticket_id?>" />
		<div class="pull-right">
		<?php if($this->isManager && $showStatusDD):?>
				<img src="<?php echo $throbber?>" style="display:none" />
				<?php echo Html::createStatusDropdown(array('div_style' => 'pull-right', 'btn_style' => '', 'title' => ''))?>
		<?php endif;?>
        <?php if($showStatus):?>
			<span class="ats-status label <?php echo Html::getStatusClass($ticket->status)?> pull-right">
				<?php echo Html::decodeStatus($ticket->status)?>
			</span>
        <?php endif; ?>
		</div>

		<?php if($showMy && $mine): ?>
		<span class="ats-my-ticket pull-left badge " style="margin-right: 2px">
			<span class="icon-user icon-white"></span>
		</span>
		<?php endif; ?>

		<?php if ($ticket->priority > 5): ?>
		<span class="ats-priority pull-left badge badge-info" style="margin-right: 2px">
			<span class="icon-arrow-down icon-white"></span>
		</span>
		<?php elseif (($ticket->priority > 0) && ($ticket->priority < 5)): ?>
		<span class="ats-priority pull-left badge badge-important" style="margin-right: 2px">
			<span class="icon-arrow-up icon-white"></span>
		</span>
		<?php else: ?>
		<?php endif; ?>

		<span class="ats-visibility pull-left badge <?php echo $badge?>" style="margin-right: 2px">
			<span class="<?php echo $icon?> icon-white"></span>
		</span>

	<?php if($showAssigned && $this->isManager && ATS_PRO):?>
		<span class="pull-right">&nbsp;</span>
		<?php echo Html::buildManagerdd(null, array('div' => 'assignto pull-right', 'a' => 'btn-mini'), $ticket->catid)?>
		<span class="pull-right badge assigned_to <?php echo $assign_class?>" style="margin-right:5px"><?php echo $assigned_to?></span>
		<span class="loading btn-small pull-right" style="display:none">
			<i class="icon-ok"></i>
			<i class="icon-warning-sign"></i>
			<img src="<?php echo $throbber ?>" />
		</span>
		<input type="hidden" class="ticket_id" value="<?php echo $ticket->ats_ticket_id ?>" />
	<?php endif;?>
			<a href="<?php echo $ticketURL ?>" <?php echo $extraLinkAttr?> >
            <?php if($showAgo):?>
                <span class="ats-opened-ago label label-warning">
					<?php echo $ago ?>
				</span>
            <?php endif; ?>
				#<?php echo $ticket->ats_ticket_id ?>: <?php echo $this->escape($ticket->title) ?>
			</a>
		</h4>
		<div class="ats-clear"></div>

        <?php if($showCategory):?>
        <div class="ats-latest-category">
            <?php echo Format::categoryName($ticket->catid) ?>
        </div>
        <div class="ats-clear"></div>
        <?php endif; ?>
		<div>
			<span class="small pull-right">
				<?php echo JText::sprintf('COM_ATS_TICKETS_MSG_LASTPOST', $lastBy, $lastOn) ?>
			</span>
			<span class="small pull-left">
				<?php echo JText::sprintf('COM_ATS_TICKETS_MSG_CREATED', $createdBy, $createdOn) ?>
			</span>
		</div>
	</td>
</tr>