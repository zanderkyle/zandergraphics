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

$container->template->addCSS('media://com_ats/css/frontend.css');
$container->template->addJS('media://com_ats/js/ticket.js', false, false, $container->mediaVersion);

$onClickSubmitPost = '';

if(!Html::ajaxReply())
{
    $onClickSubmitPost = ' onclick="javascript:ticketDoSubmit()"';
}

$user      = $platform->getUser();
$ownerUser = $platform->getUser($ticket->created_by);

// If I'm not a manager and the ticket is not closed, let's add the javascript file to handle user feedback
if (!$user->guest && $ticket->status != 'C' && !$this->isManager)
{
    $container->template->addJS('media://com_ats/js/userfeedback.js', false, false, $container->mediaVersion);
}

// Get the Itemid
$itemId = $this->input->getInt('Itemid',0);

$show_credits     = ComponentParams::getParam('showcredits', 0);
$credits_required = Credits::creditsRequired($ticket->catid, false, $ticket->public, $ticket->priority);
$enough_credits   = Credits::haveEnoughCredits($user->id, $ticket->catid, false, $ticket->public, $ticket->priority);
$totalcredits	  = Credits::creditsCharged('all', $ticket->ats_ticket_id);

$returnURL = base64_encode(JURI::getInstance()->toString());

?>

<?php echo Html::loadposition('ats-top'); ?>
<?php echo Html::loadposition('ats-posts-top'); ?>

<?php
    echo $this->loadTemplate('head');
?>

<?php
// HTML displayed right before the posts (ie Social Buttons)
$results = $container->platform->runPlugins('onBeforeTicketConversationDisplay', array($ticket));

if($results)
{
    $html = array();

    foreach($results as $result)
    {
        if($result)
        {
            $html[] = $result;
        }
    }

    if($html)
    {
        echo '<div class="ats-before-ticketconversation">'.implode("\n", $html).'</div>';
    }
}
?>

<?php if($this->isManager):?>
<ul id="atsTab"	class="nav nav-tabs">
	<li>
		<a href="#atsTabConvo" data-toggle="tab">
			<?php echo JText::_('COM_ATS_TICKETS_LEGEND_CONVO'); ?>
		</a>
	</li>
	<li>
		<a href="#atsTabManager" data-toggle="tab">
			<?php echo JText::_('COM_ATS_TICKETS_LEGEND_MANAGERNOTES'); ?>
		</a>
	</li>
</ul>
<div id="atsTabContent" class="tab-content">
	<div class="tab-pane fade" id="atsTabConvo">
<?php endif; ?>
		<div id="atsPostList">
<?php
    $posts = $ticket->posts;

    // Let's save the latest post id, so I can warn the user if anyone posted while replying
    $last_post = $posts->last();

    $this->items = $posts;
    echo $this->loadAnyTemplate('site:com_ats/Posts/threaded');
    $this->items = null;
?>
        </div>
<?php
// HTML displayed right before the posts (ie Social Buttons)
$results = $container->platform->runPlugins('onAfterTicketConversationDisplay', array($ticket));

if($results)
{
    $html = array();

    foreach($results as $result)
    {
        if($result)
        {
            $html[] = $result;
        }
    }

    if($html)
    {
        echo '<div class="ats-after-ticketconversation">'.implode("\n", $html).'</div>';
    }
}

?>
<?php if( (($ticket->status != 'C') && $this->ticketPerms['post']) || $this->isManager ): ?>
<h3 class="ats-ticket-reply-header"><?php echo JText::_('COM_ATS_POSTS_HEADING_REPLYAREA')?></h3>
<form name="ats_reply_form" action="<?php echo JURI::base() ?>index.php" method="post" enctype="multipart/form-data">
	<input type="hidden" name="option" value="com_ats" />
	<input type="hidden" name="view" value="Post" />
	<input type="hidden" name="task" value="save" />
	<input type="hidden" name="ats_ticket_id" value="<?php echo $ticket->ats_ticket_id ?>" />
	<input type="hidden" name="Itemid" value="<?php echo $itemId ?>" />
	<input type="hidden" name="returnurl" value="<?php echo $returnURL ?>" />
	<input type="hidden" name="<?php echo $token ?>" id="token" value="1" />
	<input type="hidden" name="last_ats_post_id" value="<?php echo isset($last_post->ats_post_id) ? $last_post->ats_post_id : 0 ?>" />
    <input type="hidden" id="category_id" value="<?php echo $ticket->catid ?>" />

<?php if($show_credits && !$this->isManager):
$creditsleft = Credits::creditsLeft($user->id, true) ?>
<div class="alert alert-info" id="ats-newticket-pubnote-public">
	<a class="close" data-dismiss="alert" href="#">×</a>
	<?php echo JText::sprintf('COM_ATS_COMMON_CREDITSLEFT', $creditsleft) ?>
</div>
<?php endif; ?>

<?php if($this->isManager && ATS_PRO ):?>
<div class="alert alert-success">
    <div class="ats-total-timespent">
	    <?php echo JText::sprintf('COM_ATS_TICKET_TIMESPENT_MSG', $ticket->timespent); ?>
    </div>
	<?php if ($show_credits): ?>
	<div class="ats-total-credits">
	    <?php echo JText::sprintf('COM_ATS_TICKET_TOTALCREDITS_MSG', $totalcredits); ?>
    </div>
	<?php endif; ?>
</div>
<?php endif; ?>

<?php if($ticket->public): ?>
<div class="alert" id="ats-newticket-pubnote-public">
	<?php echo JText::_('COM_ATS_NEWTICKET_MSG_PUBNOTE_PUBLIC') ?>
<?php else: ?>
<div class="alert alert-success" id="ats-newticket-pubnote-private">
	<?php echo JText::_('COM_ATS_NEWTICKET_MSG_PUBNOTE_PRIVATE') ?>
<?php endif; ?>

<?php
    if(!$this->isManager && $show_credits && ATS_PRO)
    {
        if($credits_required)
        {
            echo JText::sprintf('COM_ATS_NEWPOST_LBL_COST', $credits_required);
        }
        else
        {
            echo JText::_('COM_ATS_NEWPOST_LBL_NOCOST');
        }
    }
    ?>
</div>

<?php if( $this->isManager && ($ticket->status == 'C') ): ?>
<div id="ats-closed-warning-admin" class="alert alert-error">
	<?php echo JText::_('COM_ATS_TICKET_MSG_CLOSEDNOTICE_CLOSED_ADMIN') ?>
</div>
<?php endif; ?>

<?php if($this->input->getInt('warn_reply', 0)):?>
<div class="alert">
	<?php echo JText::_('COM_ATS_TICKET_REPLY_POSTED_WRITING');?>
</div>
<?php endif;?>

<?php
if((!$enough_credits && !$this->isManager))
{
    echo $this->loadAnyTemplate('site:com_ats/Posts/notenoughcredits');
}
elseif((!$this->isManager && ComponentParams::getParam('noreplies', 0)))
{
    echo $this->loadAnyTemplate('site:com_ats/Posts/noreplies');
}
else
{
    // If the ticket is already assigned to another manager, warn the user
    if($ticket->assigned_to && $this->isManager && $ticket->assigned_to != $user->id):?>
        <div id="ats-assignedto-warning" class="alert alert-danger">
            <?php echo JText::sprintf('COM_ATS_TICKET_ALREADY_ASSIGNED_WARN', $container->platform->getUser($ticket->assigned_to)->name)?>
        </div>
    <?php endif;?>
    <div id="ats-reply-form-area">
        <?php
            echo $this->loadAnyTemplate('site:com_ats/Posts/post',array(
                'allow_attachment'	=> $this->ticketPerms['attachment'],
                'direct'			=> true,
                'category'			=> $ticket->catid,
            ));
        ?>

        <div id="ats-reply-postbutton-nojs">
            <button type="submit"><?php echo JText::_('COM_ATS_TICKET_LBL_DOPOST')?></button>
        </div>
        <?php if(!JComponentHelper::getParams('com_ats')->get('noreplies', 0) || $this->isManager):?>
            <div class="ats-buttonbar">
                <div class="ats-button ats-button-action-postticket">
                    <button class="btn btn-primary" id="ticketSubmit" type="submit" <?php echo $onClickSubmitPost ?>>
                        <?php echo JText::_('COM_ATS_TICKET_LBL_DOPOST') ?>
                    </button>
                </div>
            </div>
        <?php endif; ?>
    </div>
<?php
}
?>
</form>

<?php if($this->isManager): ?>
	</div>
	<div class="tab-pane fade" id="atsTabManager">
	<?php

        $this->items = $ticket->manager_notes;
        echo $this->loadAnyTemplate('site:com_ats/ManagerNotes/threaded');
        $this->items = null;
	?>

    <h3 class="ats-ticket-reply-header"><?php echo JText::_('COM_ATS_POSTS_HEADING_MANAGERNOTEAREA')?></h3>
    <?php
        echo $this->loadAnyTemplate('site:com_ats/ManagerNotes/form');
    ?>

	</div>
</div>
<?php
$container->template->addJSInline( <<<JS

;// This comment is intentionally put here to prevent badly written plugins from causing a Javascript error
// due to missing trailing semicolon and/or newline in their code.
(function($) {
	$('#atsTab a').click(function (e) {
		e.preventDefault();
		$(this).tab('show');
	})
	$(document).ready(function(){
		$('#atsTab a:first').tab('show');
	});
})(akeeba.jQuery);
JS
);
endif; ?>

<?php $container->template->addJSInline( <<<JS

;// This comment is intentionally put here to prevent badly written plugins from causing a Javascript error
// due to missing trailing semicolon and/or newline in their code.
var ats_ticket_cansubmit = false;

function ticketBeforeSubmit()
{
	if(!ats_ticket_cansubmit) return false;
	return true;
}

function ticketDoSubmit()
{
	ats_ticket_cansubmit = true;
	document.forms.ats_reply_form.submit();
}

(function($) {
	$(document).ready(function()	{
		document.getElementById('ats-reply-postbutton-nojs').style.display = 'none';
		$('div.akeeba-bootstrap .atsTooltip').tooltip({placement: 'bottom'});
	});

})(akeeba.jQuery);
JS
);

elseif($ticket->status == 'C'): ?>
<div class="ats-ticket-closednotice">
	<?php echo JText::_('COM_ATS_TICKET_MSG_CLOSEDNOTICE_CLOSED') ?>
</div>
<?php endif; ?>

<?php echo Html::loadposition('ats-posts-bottom'); ?>
<?php echo Html::loadposition('ats-bottom'); ?>

<?php if(ComponentParams::getParam('userfeedback', 0) && $ticket->status != 'C' && !$this->isManager && ATS_PRO):?>
    <div style="display: none">
        <div id="akeeba-modal">
            <div class="akeeba-bootstrap" style="text-align: center">
                <h3><?php echo JText::_('COM_ATS_TICKET_PLEASE_RATE')?></h3>
                <p><?php echo JText::_('COM_ATS_TICKET_PLEASE_RATE_EXPLAIN')?></p>
                <div class="btn-group" data-toggle="buttons-radio" style="margin:20px 0 40px">
                    <button type="button" class="btn rating" data-rating="1">1 <?php echo JText::_('COM_ATS_TICKET_PLEASE_RATE_BAD') ?></button>
                    <button type="button" class="btn rating" data-rating="2">2</button>
                    <button type="button" class="btn rating" data-rating="3">3</button>
                    <button type="button" class="btn rating" data-rating="4">4</button>
                    <button type="button" class="btn rating" data-rating="5">5 <?php echo JText::_('COM_ATS_TICKET_PLEASE_RATE_GREAT') ?></button>
                </div>

                <div style="text-align: right">
                    <button type="button" class="btn" onclick="javascript:closeModal()"><?php echo JText::_('JCANCEL')?></button>
                    <button type="button" class="btn btn-inverse" onclick="javascript:submitFeedback()"><?php echo JText::_('JSUBMIT')?></button>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>
<div style="display: none">
	<div id="ajaxFormFeedback">
		<div>
			<div class="progress progress-striped">
				<div class="bar" style="width:0;"></div>
			</div>
			<div class="messageHolder" style="display:none;text-align: center">
				<div class="message"></div>
				<input type="button" class="closeUpload btn" value="Ok"/>
                <input type="button" class="reloadTicketList btn" style="display: none" value="<?php echo JText::_('COM_ATS_COMMON_RELOAD')?>"/>
			</div>
		</div>
	</div>
</div>
