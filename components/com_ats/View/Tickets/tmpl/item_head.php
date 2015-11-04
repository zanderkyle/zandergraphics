<?php
/**
 * @package ats
 * @copyright Copyright (c)2011-2014 Nicholas K. Dionysopoulos / AkeebaBackup.com
 * @license GNU GPL v3 or later
 */

/** @var Akeeba\TicketSystem\Site\View\Tickets\Html $this */
defined('_JEXEC') or die;

use Akeeba\TicketSystem\Admin\Helper\ComponentParams;
use Akeeba\TicketSystem\Admin\Helper\Credits;
use Akeeba\TicketSystem\Admin\Helper\Html;
use Akeeba\TicketSystem\Admin\Helper\Select;

/** @var \Akeeba\TicketSystem\Site\Model\Tickets $ticket */
$ticket    = $this->item;
$itemId    = $this->input->getInt('Itemid',0);
$container = $this->getContainer();
$platform  = $container->platform;
$token	   = $container->session->getFormToken();

$throbber  = $container->template->parsePath('media://com_ats/images/throbber.gif');
$returnURL = base64_encode(JURI::getInstance()->toString());
$adminActionsURL = 'index.php?option=com_ats&view=Ticket&id='.$ticket->ats_ticket_id.'&returnurl='.$returnURL.'&Itemid='.$itemId.'&'.$token.'=1';

$printURL = clone JURI::getInstance();
$printURL->setVar('layout', 'print');
$printURL->setVar('tmpl', 'component');

$show_credits     = ComponentParams::getParam('showcredits', 0);
$credits_charged  = Credits::creditsCharged('ticket', $ticket->ats_ticket_id);
$totalcredits	  = Credits::creditsCharged('all', $ticket->ats_ticket_id);

$class 		 = 'label-important';
$assigned_to = JText::_('COM_ATS_TICKETS_UNASSIGNED');

if($ticket->assigned_to)
{
    $class 		 = 'label-info';
    $assigned_to = $container->platform->getUser($ticket->assigned_to)->name;
}

?>
<?php if ($this->pageParams->get('show_page_heading', 1) || true) : ?>
    <h1 class="ats-ticket-view-title">
        #<?php echo $this->escape($ticket->ats_ticket_id); ?> &ndash;
        <?php echo $this->escape($ticket->title); ?>
    </h1>
    <div class="ats-ticket-view-postedin">
        <?php echo JText::sprintf('COM_ATS_TICKET_LBL_POSTEDIN', $this->escape($ticket->joomla_category->title)) ?>
    </div>
<?php endif; ?>

<?php if($this->isManager || $this->ticketPerms['close']): ?>
    <div class="btn-toolbar">
        <?php if($this->isManager): ?>
            <div class="btn-group">
                <a id="ats-print-ticket" href="<?php echo $printURL ?>" class="btn atsTooltip" data-toggle="tooltip" title="<?php echo JText::_('COM_ATS_TICKETS_PRINTVIEW') ?>" target="_blank">
                    <span class="icon-print"></span>
                </a>
            </div>

            <div class="btn-group">
                <?php if(!$ticket->public):?>
                    <a id="ats-makepublic-ticket" class="btn atsTooltip" href="<?php echo JRoute::_($adminActionsURL.'&task=public_publish') ?>"
                       data-toggle="tooltip" title="<?php echo JText::_('COM_ATS_TICKET_LBL_MAKEPUBLIC') ?>">
                        <span class="icon-eye-close"></span>
                    </a>
                <?php else: ?>
                    <a id="ats-makeprivate-ticket" class="btn atsTooltip" href="<?php echo JRoute::_($adminActionsURL.'&task=public_unpublish') ?>"
                       data-toggle="tooltip" title="<?php echo JText::_('COM_ATS_TICKET_LBL_MAKEPRIVATE') ?>">
                        <span class="icon-eye-open"></span>
                    </a>
                <?php endif; ?>
            </div>

            <div class="btn-group">
                <?php if(!$ticket->enabled):?>
                    <a id="ats-publish-ticket" class="btn atsTooltip btn-success" href="<?php echo JRoute::_($adminActionsURL.'&task=publish') ?>"
                       data-toggle="tooltip" title="<?php echo JText::_('COM_ATS_TICKET_LBL_PUBLISH') ?>">
                        <span class="icon-lock icon-white"></span>
                    </a>
                <?php else: ?>
                    <a id="ats-unpublish-ticket" class="btn atsTooltip btn-warning" href="<?php echo JRoute::_($adminActionsURL.'&task=unpublish') ?>"
                       data-toggle="tooltip" title="<?php echo JText::_('COM_ATS_TICKET_LBL_UNPUBLISH') ?>">
                        <span class="icon-lock icon-white"></span>
                    </a>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <div class="pull-right">
            <?php if($this->isManager):?>
                <img src="<?php echo $throbber?>" style="display:none" />
                <?php echo Html::createStatusDropdown(array('div_style' => 'pull-right', 'btn_style' => '', 'title' => ''))?>
            <?php endif;?>
            <span id="ats-status" class="label <?php echo Html::getStatusClass($ticket->status)?> pull-right">
				<?php echo Html::decodeStatus($ticket->status)?>
			</span>
        </div>

        <?php if($this->isManager && ATS_PRO):?>
        <div class="assign-wrapper pull-right">
            <span class="loading btn-small" style="display:none">
                <i class="icon-ok"></i>
                <i class="icon-warning-sign"></i>
                <img src="<?php echo $throbber ?>" />
            </span>
            <?php echo Html::buildManagerdd(null, array('div' => 'assignto pull-right', 'a' => 'btn-mini'), $ticket->catid)?>
            <span id="ats-assigned-to" class="pull-right label <?php echo $class?>"><?php echo $assigned_to?></span>
        </div>
        <?php endif;?>

        <?php
        if($ticket->status != 'C'):

            // I'm the admin or the user feedback is switched off
            if($this->isManager || !ComponentParams::getParam('userfeedback', 0) || !ATS_PRO)
            {
                $class = '';
                $href  = JRoute::_($adminActionsURL.'&task=close');
                $extra = '';
            }
            elseif($this->ticketPerms['close'])
            {
                $class = 'user-feedback';
                $href  = '#akeeba-modal';
                $extra = 'rel="boxed" data-submiturl="'.JRoute::_($adminActionsURL.'&task=close').'"';
            }
            ?>
            <?php if($this->isManager): ?>
            <div class="btn-group">
                <a id="ats-admin-close" class="btn atsTooltip btn-inverse <?php echo $class?>" href="<?php echo $href?>" <?php echo $extra ?>
                   data-toggle="tooltip" title="<?php echo JText::_('COM_ATS_TICKET_LBL_CLOSE') ?>"
                    >
                    <span class="icon-off icon-white"></span>
                </a>
            </div>
            <?php else: ?>
            <div class="btn-group">
                <a id="ats-user-close" class="btn btn-inverse <?php echo $class?>" href="<?php echo $href?>" <?php echo $extra ?> >
                    <span class="icon-off icon-white"></span>
                    <?php echo JText::_('COM_ATS_TICKET_LBL_CLOSE') ?>
                </a>
            </div>
            <?php endif; ?>
        <?php elseif($this->isManager): // Only administrators can re-open a closed issue ?>
            <div class="btn-group">
                <a id="ats-reopen" class="btn atsTooltip" href="<?php echo JRoute::_($adminActionsURL.'&task=reopen') ?>"
                   data-toggle="tooltip" title="<?php echo JText::_('COM_ATS_TICKET_LBL_REOPEN') ?>"
                    >
                    <span class="icon-retweet"></span>
                </a>
            </div>
        <?php endif; ?>

        <?php if($this->isManager):?>
            <div class="btn-group">
                <form name="ats_moveticket_form" id="ats_moveticket_form" action="<?php echo JURI::base() ?>index.php" method="post" class="form form-inline">
                    <input type="hidden" name="option" value="com_ats" />
                    <input type="hidden" name="view" value="Tickets" />
                    <input type="hidden" name="task" value="move" />
                    <input type="hidden" name="id" value="<?php echo $ticket->ats_ticket_id ?>" />
                    <input type="hidden" name="Itemid" value="<?php echo $itemId ?>" />
                    <input type="hidden" name="returnurl" value="<?php echo $returnURL ?>" />
                    <input type="hidden" name="<?php echo $token?>" value="1" />
                    <?php echo Select::getCategories($ticket->catid, 'catid', array('class' => 'input-medium')); ?>
                    <button class="btn btn-primary">
                        <span class="icon-white icon-arrow-right"></span>
                    </button>
                </form>
            </div>
        <?php endif;?>

        <?php if($this->isManager && $show_credits && ATS_PRO ):?>
            <?php if($credits_charged): ?>
                <a href="index.php?option=com_ats&view=Ticket&task=creditsrefund&id=<?php echo $ticket->ats_ticket_id ?>&<?php echo $token ?>=1&returnurl=<?php echo $returnURL ?>"
                   class="btn btn-danger atsTooltip" data-toggle="tooltip" title="<?php echo JText::sprintf('COM_ATS_TICKETS_CREDITS_REFUND', $credits_charged) ?>">
                    <span class="icon-white icon-minus-sign"></span>
                    <?php echo $credits_charged; ?>
                </a>
            <?php else: ?>
                <a href="index.php?option=com_ats&view=Ticket&task=creditscharge&id=<?php echo $ticket->ats_ticket_id ?>&<?php echo $token ?>=1&returnurl=<?php echo $returnURL ?>"
                   class="btn btn-success atsTooltip" data-toggle="tooltip" title="<?php echo JText::sprintf('COM_ATS_TICKETS_CREDITS_CHARGE', Credits::creditsRequired($ticket->catid, true, $ticket->public, $ticket->priority)) ?>">
                    <span class="icon-white icon-plus-sign"></span>
                    <?php echo Credits::creditsRequired($ticket->catid, true, $ticket->public, $ticket->priority); ?>
                </a>
            <?php endif; ?>
        <?php endif; ?>
    </div>
    <?php if($show_credits && ATS_PRO): ?>
        <div class="alert alert-info">
            <?php echo JText::sprintf('COM_ATS_COMMON_USERCREDITSLEFT', Credits::creditsLeft($ticket->created_by)) ?>
        </div>
    <?php endif; ?>
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
    </div>
<?php else: ?>
    <div class="alert alert-success" id="ats-newticket-pubnote-private">
        <?php echo JText::_('COM_ATS_NEWTICKET_MSG_PUBNOTE_PRIVATE') ?>
    </div>
<?php endif; ?>

