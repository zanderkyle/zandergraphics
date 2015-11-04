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
 * $showCustomFields	: Should I display custom fields? Usually made on first post only
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

$container = $this->getContainer();
$postUser  = Html::getPostUser($item->created_by);
$user      = JFactory::getUser();

// Determine custom magic classes (determined by user groups, a la NinjaBoard - thank you Stian for the inspiration!)
$customClasses = array();
foreach($postUser->groups as $groupName => $groupID)
{
	$customClasses[] = 'ats-post-group-'.Filter::toSlug($groupName);
}

$origin_icon = Html::getPostOriginIcon($item->origin);
$token       = $container->session->getFormToken().'=1';
$withinEditingTime = Permissions::editGraceTime($item);

?>
<div class="ats-post <?php echo implode(' ',$customClasses) ?>">
	<div class="ats-post-header">
		<span class="ats-post-header-date">
			<?php echo $origin_icon ?>
			<?php echo Format::date2($item->created_on,'',true); ?>
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
		<span class="ats-post-header-fullname pull-right">
			<?php echo $postUser->name; ?>
		</span>
	</div>
	<div class="ats-post-body">
		<div class="ats-post-content">
			<div class="ats-post-content-html">
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
			<?php if($item->attachments): $shownAttachmentsHeader = false; ?>
            <?php foreach($item->attachments as $attachment):

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
				<?php if($attachment->enabled): ?>
				<span class="ats-post-attachments-filename">
					<a href="index.php?option=com_ats&view=attachment&task=read&id=<?php echo $attachment->ats_attachment_id ?>&format=raw&<?php echo $token ?>" >
						<?php echo $this->escape($attachment->original_filename) ?>
					</a>
				</span>
				<?php endif; ?>
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
	</div>
</div>