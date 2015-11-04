<?php
/**
 * @package ats
 * @copyright Copyright (c)2011-2014 Nicholas K. Dionysopoulos / AkeebaBackup.com
 * @license GNU GPL v3 or later
 */

/*
 * Params coming from including this layout with loadAnyTemplate:
 *
 * $item				: Post information
 * $returnURL			: URL to return after every "moderator" action (ie unpublish attachment)
 * $showCustomFields	: Should I display custom fields? Usually made on first post only
 * $attachmentErrors    : Errors while uploading the attachments
 *
 * $this->isManager     : Is the user a manager?
 */

/** @var \Akeeba\TicketSystem\Site\Model\Posts $item */

use Akeeba\TicketSystem\Admin\Helper\Credits;
use Akeeba\TicketSystem\Admin\Helper\Filter;
use Akeeba\TicketSystem\Admin\Helper\Format;
use Akeeba\TicketSystem\Admin\Helper\Html;
use Akeeba\TicketSystem\Admin\Helper\Permissions;

// No direct access
defined('_JEXEC') or die;

if(!isset($showCustomFields))   $showCustomFields = 0;
if(!isset($attachmentErrors))   $attachmentErrors = array();

$container = $this->getContainer();
$postUser  = Html::getPostUser($item->created_by);
$user      = JFactory::getUser();

// Determine custom magic classes (determined by user groups, a la NinjaBoard - thank you Stian for the inspiration!)
$customClasses = array();
foreach($postUser->groups as $groupName => $groupID)
{
	$customClasses[] = 'ats-post-group-'.Filter::toSlug($groupName);
}

// Get the return URL to point back to ourselves
$permalink = JRoute::_('index.php?option=com_ats&view=Ticket&id='.$item->ats_ticket_id).'#p'.$item->ats_post_id;

$origin_icon = Html::getPostOriginIcon($item->origin);
$token       = $container->session->getFormToken().'=1';
$withinEditingTime = Permissions::editGraceTime($item);
?>
<div class="ats-post <?php echo implode(' ',$customClasses) ?> ats-post-status-<?php echo $item->enabled ? 'published' : 'unpublished' ?>" id="p<?php echo $item->ats_post_id ?>">
	<div class="ats-post-header">
		<span class="ats-post-header-date">
			<?php echo $origin_icon ?>
			<a href="<?php echo $permalink ?>">
				<?php echo Format::date2($item->created_on,'',true); ?>
			</a>
			<?php if ($item->timespent): ?>
			<span class="badge badge-info">
				<?php echo JText::sprintf('COM_ATS_POSTS_TIMESPENT_MSG', $item->timespent); ?>
			</span>
			<?php endif; ?>
		</span>
		<?php if(!$item->enabled): ?>
		<span class="ats-post-header-unpublished">
			<?php echo JText::_('COM_ATS_TICKETS_MSG_UNPUBLISHEDPOSTNOTICE'); ?>
		</span>
		<?php endif; ?>
		<?php if ($this->isManager): ?>
		<span class="ats-post-header-fullname pull-right">
			<?php echo $postUser->name; ?>
		</span>
		<?php endif; ?>
	</div>
	<div class="ats-post-userinfo">
		<?php echo $this->loadAnyTemplate('site:com_ats/Posts/threaded_user',array('user' => $postUser));?>
	</div>
	<div class="ats-post-body">
		<div class="ats-post-content">
			<div class="ats-post-content-html">
                <?php if (!empty($attachmentErrors)): ?>
                    <div class="alert alert-error alert-danger">
                        <h4>
                            <span class="icon icon-exclamation-sign glyphicon glyphicon-exclamation-sign"></span>
                            <?php echo JText::_('COM_ATS_POSTS_ERR_ATTACHMENTERRORS'); ?>
                        </h4>
                        <ul>
                            <?php foreach ($attachmentErrors as $errorMessage): ?>
                                <li>
                                    <?php echo $this->escape($errorMessage); ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
				<?php echo $item->content_html ?>
			</div>
		<?php
			if($showCustomFields && ATS_PRO):
                /** @var \Akeeba\TicketSystem\Site\Model\Tickets $ticket */
                $ticket = $item->ticket;
                $customFields = $ticket->loadCustomFields();

				if($customFields):
		?>
			<div class="ats-post-customfields">
				<h6><?php echo JText::_('COM_ATS_TITLE_CUSTOMFIELDS')?></h6>
				<dl class="dl-horizontal" style="margin-bottom:5px">
		<?php foreach ($customFields as $field):?>
					<dt style="width:320px;margin-right:10px"><?php echo $field['label']?></dt>
					<dd><?php echo $field['value'] ? $field['value'] : '&nbsp;'?></dd>
		<?php endforeach;?>
				</dl>
			</div>
			<?php endif;?>
		<?php endif;?>
        <?php
            if($item->attachments):
                // Since we are evaluating the attachments one by one, we have to use a flag to know when to display the header
                $shownAttachmentsHeader = false;

                    foreach($item->attachments as $attachment):

                        // If the attachment is private, just skip it
                        if (Permissions::attachmentPrivate($attachment, $this->isManager, $item->ticket->created_by))
                        {
                            continue;
                        }

                        // If the user can't see the attachment, just skip it
                        if (!Permissions::attachmentVisible($attachment, $this->isManager, $item))
                        {
                            continue;
                        }

                        if (!$shownAttachmentsHeader):
                            $shownAttachmentsHeader = true;
        ?>
			<div class="ats-post-attachments">
				<span class="ats-post-attachments-head">
					<?php echo JText::_('COM_ATS_TICKETS_HEADING_ATTACHMENT') ?>
				</span>
				        <?php endif; ?>

                <div style="margin-bottom: 3px">
                    <span class="ats-post-attachments-filename">
                        <a href="index.php?option=com_ats&view=Attachment&task=read&id=<?php echo $attachment->ats_attachment_id ?>&format=raw&<?php echo $token ?>" >
                            <?php echo $this->escape($attachment->original_filename) ?>
                        </a>
                    </span>

                    <?php if($user->authorise('core.edit.state','com_ats') || $this->isManager || ($user->authorise('core.edit.own','com_ats') && ($user->id == $item->created_by)) ): ?>
                    &nbsp;
                    <?php if($attachment->enabled): ?>
                    <a class="btn btn-mini atsTooltip" data-toggle="tooltip" title="<?php echo JText::_('COM_ATS_COMMON_UNPUBLISH') ?>"
                        href="index.php?option=com_ats&view=Attachment&task=unpublish&id=<?php echo $attachment->ats_attachment_id ?>&returnurl=<?php echo $returnURL ?>&<?php echo $token ?>">
                        <span class="icon-lock"></span>
                    </a>
                    <?php else: ?>
                    <a class="btn btn-mini atsTooltip" data-toggle="tooltip" title="<?php echo JText::_('COM_ATS_COMMON_PUBLISH') ?>"
                        href="index.php?option=com_ats&view=Attachment&task=publish&id=<?php echo $attachment->ats_attachment_id ?>&returnurl=<?php echo $returnURL ?>&<?php echo $token ?>">
                        <span class="icon-unlock"></span>
                    </a>
                    <?php endif; ?>
                    <?php endif; ?>

                    <?php if(JFactory::getUser()->authorise('core.delete','com_ats') || $this->isManager): ?>
                    &nbsp;
                    <a class="btn btn-danger btn-mini" href="index.php?option=com_ats&view=Attachment&task=remove&id=<?php echo $attachment->ats_attachment_id ?>&returnurl=<?php echo $returnURL ?>&<?php echo $token ?>"  onclick="return confirm('<?php echo JText::_('COM_ATS_ATTACHMENTS_MSG_DELETEPROMPT') ?>')">
                        <span class="icon-trash icon-white"></span>
                        <?php echo JText::_('COM_ATS_COMMON_DELETE') ?>
                    </a>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
        <?php if ($shownAttachmentsHeader): ?>
			</div>
        <?php endif; ?>
        <?php endif; ?>
			<div class="ats-post-content-signature">
				<?php echo nl2br(Html::getSignature($postUser)) ?>
			</div>
		</div>
	</div>
	<div class="ats-post-footer">
		<?php if(($item->modified_on != '0000-00-00 00:00:00') && !empty($item->modified_on)): ?>
		<div class="ats-post-edits">
			<?php echo JText::sprintf('COM_ATS_TICKETS_MSG_EDITEDBYON', JUser::getInstance($item->modified_by)->username, Format::date($item->modified_on) ) ?>
		</div>
		<?php endif; ?>
		<div class="ats-post-buttons">
			<div class="btn-group pull-left">

				<?php if($user->authorise('core.edit.state','com_ats') || $this->isManager ): ?>
				<?php if($item->enabled): ?>
				<a class="btn btn-warning atsTooltip" data-toggle="tooltip" title="<?php echo JText::_('COM_ATS_COMMON_UNPUBLISH') ?>"
					href="index.php?option=com_ats&view=Post&task=unpublish&id=<?php echo $item->ats_post_id ?>&returnurl=<?php echo $returnURL ?>&<?php echo $token ?>">
					<span class="icon-lock icon-white"></span>
				</a>
				<?php else: ?>
				<a class="btn btn-warning atsTooltip" data-toggle="tooltip" title="<?php echo JText::_('COM_ATS_COMMON_PUBLISH') ?>"
					href="index.php?option=com_ats&view=Post&task=publish&id=<?php echo $item->ats_post_id ?>&returnurl=<?php echo $returnURL ?>&<?php echo $token ?>">
					<span class="icon-unlock icon-white"></span>
				</a>
				<?php endif; ?>
				<?php endif; ?>

				<?php if($this->isManager || $user->authorise('core.edit','com_ats') || ($withinEditingTime) ): ?>
				<a class="btn atsTooltip" data-toggle="tooltip" title="<?php echo JText::_('COM_ATS_COMMON_EDIT') ?>"
					href="index.php?option=com_ats&view=Post&task=edit&id=<?php echo $item->ats_post_id ?>&returnurl=<?php echo $returnURL ?>&<?php echo $token ?>">
					<span class="icon-pencil"></span>
				</a>
				<?php endif ?>

				<?php if(JFactory::getUser()->authorise('core.delete','com_ats') || $this->isManager): ?>
				<a class="btn btn-danger atsTooltip" data-toggle="tooltip" title="<?php echo JText::_('COM_ATS_COMMON_DELETE') ?>"
					href="index.php?option=com_ats&view=Post&task=remove&id=<?php echo $item->ats_post_id ?>&returnurl=<?php echo $returnURL ?>&<?php echo $token ?>" onclick="return confirm('<?php echo JText::_('COM_ATS_POSTS_MSG_DELETEPROMPT') ?>')">
					<span class="icon-trash icon-white"></span>
				</a>
				<?php endif; ?>
			</div>

			<?php if( ($this->isManager || $user->authorise('core.edit.state','com_ats')) && ATS_PRO ): ?>
				&nbsp;
				<?php $returnurl = base64_encode(JURI::getInstance()->toString()); ?>
				<?php
					// Please note that if the category doesn't charge the user to post, the following statements always fails, so the
					// "Charge credits" button is displayed, even if we won't charge anything ever! Should we change this behaviour?
					// Should we display them only when the credit feature is on?
				?>
				<?php if($creditsCharged = Credits::creditsCharged('post', $item->ats_ticket_id, $item->ats_post_id)): ?>
				<a href="index.php?option=com_ats&view=Post&task=creditsrefund&id=<?php echo $item->ats_post_id ?>&<?php echo $token ?>&returnurl=<?php echo $returnURL?>"
					class="btn btn-danger atsTooltip" data-toggle="tooltip" title="<?php echo JText::sprintf('COM_ATS_POSTS_CREDITS_REFUND', $creditsCharged); ?>">
					<span class="icon-white icon-remove-sign"></span>
					<?php echo $creditsCharged; ?>
				</a>
				<?php else: ?>
				<a href="index.php?option=com_ats&view=Post&task=creditscharge&id=<?php echo $item->ats_post_id ?>&<?php echo $token ?>&returnurl=<?php echo $returnURL?>"
					class="btn btn-success atsTooltip" data-toggle="tooltip" title="<?php echo JText::_('COM_ATS_POSTS_CREDITS_CHARGE'); ?>">
					<span class="icon-white icon-plus-sign"></span>
				</a>
				<?php endif; ?>
			<?php endif; ?>
            <div class="ats-clear"></div>
		</div>
	</div>
</div>