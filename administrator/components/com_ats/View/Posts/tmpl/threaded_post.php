<?php
/**
 * @package ats
 * @copyright Copyright (c)2011-2014 Nicholas K. Dionysopoulos / AkeebaBackup.com
 * @license GNU GPL v3 or later
 */

// No direct access
use Akeeba\TicketSystem\Admin\Helper\Credits;
use Akeeba\TicketSystem\Admin\Helper\Filter;
use Akeeba\TicketSystem\Admin\Helper\Format;
use Akeeba\TicketSystem\Admin\Helper\Html;
use Akeeba\TicketSystem\Admin\Helper\Permissions;

defined('_JEXEC') or die;

$container = FOF30\Container\Container::getInstance('com_ats');
// The user who created this post
$postUser = Html::getPostUser($item->created_by);
$user     = $container->platform->getUser();

// Determine custom magic classes (determined by user groups, a la NinjaBoard - thank you Stian for the inspiration!)
$customClasses = array();
foreach($postUser->groups as $groupName => $groupID)
{
	$customClasses[] = 'ats-post-group-'.Filter::toSlug($groupName);
}

// Get the return URL to point back to ourselves
$permalink = JUri::getInstance();
$permalink->setFragment('p'.$item->ats_post_id);

$origin_icon = Html::getPostOriginIcon($item->origin);
$token       = $container->session->getFormToken().'=1';

?>
<div class="ats-post <?php echo implode(' ',$customClasses) ?> ats-post-status-<?php echo $item->enabled ? 'published' : 'unpublished' ?>" id="p<?php echo $item->ats_post_id ?>">
	<div class="ats-post-header">
		<span class="ats-post-header-date">
			<?php echo $origin_icon ?>
			<a href="<?php echo $permalink ?>">
				<?php echo Format::date2($item->created_on,'',true); ?>
			</a>
		</span>
		<?php if(!$item->enabled): ?>
		<span class="ats-post-header-unpublished">
			<?php echo JText::_('COM_ATS_TICKETS_MSG_UNPUBLISHEDPOSTNOTICE'); ?>
		</span>
		<?php endif; ?>
	</div>
	<div class="ats-post-userinfo">
		<?php echo $this->loadAnyTemplate('admin:com_ats/Posts/threaded_user',array('user' => $postUser));?>
	</div>
	<div class="ats-post-body">
		<div class="ats-post-content">
			<div class="ats-post-content-html">
				<?php echo $item->content_html ?>
			</div>
            <?php
            if($item->attachments): ?>
                <div class="ats-post-attachments">
				<span class="ats-post-attachments-head">
					<?php echo JText::_('COM_ATS_TICKETS_HEADING_ATTACHMENT') ?>
				</span>
                    <?php
                    foreach($item->attachments as $attachment):

                        // Can I see the attachment?
                        if(!Permissions::attachmentVisible($attachment, $this->isManager, $item))
                        {
                            continue;
                        }
                            ?>
                            <div style="margin-bottom: 3px">
                    <span class="ats-post-attachments-filename">
                        <a href="index.php?option=com_ats&view=Attachments&task=read&id=<?php echo $attachment->ats_attachment_id ?>&format=raw&<?php echo $token ?>" >
                            <?php echo $this->escape($attachment->original_filename) ?>
                        </a>
                    </span>
                                <?php if($user->authorise('core.edit.state','com_ats') || $this->isManager || ($user->authorise('core.edit.own','com_ats') && ($user->id == $item->created_by)) ): ?>
                                    &nbsp;
                                    <?php if($attachment->enabled): ?>
                                        <a class="btn btn-mini atsTooltip" data-toggle="tooltip" title="<?php echo JText::_('COM_ATS_COMMON_UNPUBLISH') ?>"
                                           href="index.php?option=com_ats&view=Attachments&task=unpublish&id=<?php echo $attachment->ats_attachment_id ?>&returnurl=<?php echo $returnURL ?>&<?php echo $token ?>">
                                            <span class="icon-lock"></span>
                                        </a>
                                    <?php else: ?>
                                        <a class="btn btn-mini atsTooltip" data-toggle="tooltip" title="<?php echo JText::_('COM_ATS_COMMON_PUBLISH') ?>"
                                           href="index.php?option=com_ats&view=Attachments&task=publish&id=<?php echo $attachment->ats_attachment_id ?>&returnurl=<?php echo $returnURL ?>&<?php echo $token ?>">
                                            <span class="icon-unlock"></span>
                                        </a>
                                    <?php endif; ?>
                                <?php endif; ?>

                                <?php if($user->authorise('core.delete','com_ats') || $this->isManager): ?>
                                    &nbsp;
                                    <a class="btn btn-danger btn-mini" href="index.php?option=com_ats&view=Attachments&task=remove&id=<?php echo $attachment->ats_attachment_id ?>&returnurl=<?php echo $returnURL ?>&<?php echo $token ?>"  onclick="return confirm('<?php echo JText::_('COM_ATS_ATTACHMENTS_MSG_DELETEPROMPT') ?>')">
                                        <span class="icon-trash icon-white"></span>
                                        <?php echo JText::_('COM_ATS_COMMON_DELETE') ?>
                                    </a>
                                <?php endif; ?>
                            </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
			<div class="ats-post-content-signature">
				<?php echo nl2br(Html::getSignature($postUser)) ?>
			</div>
		</div>
	</div>
	<div class="ats-post-footer">
		<?php if(intval($item->modified_on)): ?>
		<div class="ats-post-edits">
			<?php echo JText::sprintf('COM_ATS_TICKETS_MSG_EDITEDBYON', $container->platform->getUser($item->modified_by)->username, Format::date($item->modified_on) ) ?>
		</div>
		<?php endif; ?>
		<div class="ats-post-buttons">
			<?php if($user->authorise('core.edit.state','com_ats') || ($user->authorise('core.edit.own','com_ats') && ($user->id == $item->id)) ): ?>
			<?php if($item->enabled): ?>
			<a class="btn" href="index.php?option=com_ats&view=Posts&task=unpublish&id=<?php echo $item->ats_post_id ?>&returnurl=<?php echo $returnURL ?>&<?php echo $token ?>">
				<i class="icon-lock"></i>
				<?php echo JText::_('COM_ATS_COMMON_UNPUBLISH') ?>
			</a>
			<?php else: ?>
			<a class="btn" href="index.php?option=com_ats&view=Posts&task=publish&id=<?php echo $item->ats_post_id ?>&returnurl=<?php echo $returnURL ?>&<?php echo $token ?>">
				<i class="icon-lock"></i>
				<?php echo JText::_('COM_ATS_COMMON_PUBLISH') ?>
			</a>
			<?php endif; ?>
			<?php endif; ?>

			<?php if($this->isManager || $user->authorise('core.edit','com_ats') || ($user->authorise('core.edit.own','com_ats') && ($user->id == $item->id)) || ($withinEditingTime) ): ?>
			<a class="btn" href="index.php?option=com_ats&view=Posts&task=edit&id=<?php echo $item->ats_post_id ?>&returnurl=<?php echo $returnURL ?>&<?php echo $token ?>">
				<i class="icon-pencil"></i>
				<?php echo JText::_('COM_ATS_COMMON_EDIT') ?>
			</a>
			<?php endif ?>

			<?php if($user->authorise('core.delete','com_ats')): ?>
			<a class="btn btn-danger" href="index.php?option=com_ats&view=Posts&task=remove&id=<?php echo $item->ats_post_id ?>&returnurl=<?php echo $returnURL ?>&<?php echo $token ?>" onclick="return confirm('<?php echo JText::_('COM_ATS_POSTS_MSG_DELETEPROMPT') ?>')">
				<i class="icon-trash icon-white"></i>
				<?php echo JText::_('COM_ATS_COMMON_DELETE') ?>
			</a>
			<?php endif; ?>

			<?php if( ($this->isManager || $user->authorise('core.edit.state','com_ats')) && ATS_PRO ): ?>
			<?php $returnurl = base64_encode(JURI::getInstance()->toString()); ?>
			<?php if($creditsCharged = Credits::creditsCharged('post', $item->ats_ticket_id, $item->ats_post_id)): ?>
			<a href="index.php?option=com_ats&view=Posts&task=creditsrefund&id=<?php echo $item->ats_post_id ?>&<?php echo $token ?>&returnurl=<?php echo $returnURL?>" class="btn btn-danger">
				<i class="icon-white icon-remove-sign"></i>
				<?php echo JText::sprintf('COM_ATS_POSTS_CREDITS_REFUND', $creditsCharged); ?>
			</a>
			<?php else: ?>
			<a href="index.php?option=com_ats&view=Posts&task=creditscharge&id=<?php echo $item->ats_post_id ?>&<?php echo $token ?>&returnurl=<?php echo $returnURL?>" class="btn btn-success">
				<i class="icon-white icon-plus-sign"></i>
				<?php echo JText::_('COM_ATS_POSTS_CREDITS_CHARGE'); ?>
			</a>
			<?php endif; ?>
			<?php endif; ?>
		</div>
	</div>
</div>