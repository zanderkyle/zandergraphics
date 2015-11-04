<?php
/**
 * @package ats
 * @copyright Copyright (c)2011-2014 Nicholas K. Dionysopoulos / AkeebaBackup.com
 * @license GNU GPL v3 or later
 */
use Akeeba\TicketSystem\Admin\Helper\Credits;
use Akeeba\TicketSystem\Admin\Helper\Select;

/** @var $this \Akeeba\TicketSystem\Admin\View\Tickets\Html */
defined('_JEXEC') or die;

$container = $this->getContainer();
$ownerUser = $container->platform->getUser($this->item->created_by);
$tags      = array();

if($this->item->created_by)
{
    /** @var \Akeeba\TicketSystem\Admin\Model\UserTags $usertags */
    $usertags = $container->factory->model('UserTags')->tmpInstance();
    $tags     = $usertags->loadTagsByUser($this->item->created_by);
}

?>
<div class="control-group">
    <label class="control-label"><?php echo JText::_('COM_ATS_TICKETS_USERINFO_USERNAME'); ?></label>
    <div class="controls">
        <a href="index.php?option=com_users&task=user.edit&id=<?php echo $ownerUser->id ?>" target="_blank">
            <strong><?php echo $ownerUser->username ?></strong> [<?php echo $ownerUser->id ?>]
        </a>
    </div>
</div>
<div class="control-group">
    <label class="control-label"><?php echo JText::_('COM_ATS_COMMON_USER_TAGS')?></label>
    <div class="controls">
        <?php echo Select::usertags('usertags[]', $tags, array('class' => 'advancedSelect', 'multiple' => 1))?>
    </div>
</div>
<div class="control-group">
    <label class="control-label"><?php echo JText::_('COM_ATS_TICKETS_USERINFO_NAME')?></label>
    <div class="controls">
        <?php echo $ownerUser->name ?>
    </div>
</div>
<div class="control-group">
    <label class="control-label"><?php echo JText::_('COM_ATS_TICKETS_USERINFO_EMAIL')?></label>
    <div class="controls">
        <?php echo $ownerUser->email ?>
    </div>
</div>
<div class="control-group">
    <label class="control-label"><?php echo JText::_('COM_ATS_TICKETS_USERINFO_CREDITS')?></label>
    <div class="controls">
        <?php echo Credits::creditsLeft($ownerUser->id, true) ?>
    </div>
</div>
<?php if($this->hasSubscriptions): ?>
    <div class="control-group">
        <label class="control-label"><?php echo JText::_('COM_ATS_TICKETS_USERINFO_SUBSCRIPTIONS'); ?></label>
        <div class="controls">
            <?php
            if(count($this->activesubs))
            {
                echo implode(', ', $this->activesubs);
            }
            else
            {
                echo '&mdash;';
            }
            ?>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label"><?php echo JText::_('COM_ATS_TICKETS_USERINFO_SUBSCRIPTIONS_INACTIVE'); ?></label>
        <div class="controls">
            <?php
            if(count($this->inactivesubs))
            {
                echo implode(', ', $this->inactivesubs);
            }
            else
            {
                echo '&mdash;';
            }
            ?>
        </div>
    </div>
<?php endif; ?>
<?php if (ATS_PRO): ?>
    <div style="text-align: center">
        <?php if($creditsCharged = Credits::creditsCharged('ticket', $this->item->ats_ticket_id)): ?>
            <a href="index.php?option=com_ats&view=Tickets&task=creditsrefund&id=<?php echo $this->item->ats_ticket_id ?>&<?php echo \JFactory::getSession()->getFormToken() ?>=1" class="btn btn-danger">
                <i class="icon-white icon-remove-sign"></i>
                <?php echo JText::sprintf('COM_ATS_TICKETS_CREDITS_REFUND', $creditsCharged); ?>
            </a>
        <?php else: ?>
            <a href="index.php?option=com_ats&view=Tickets&task=creditscharge&id=<?php echo $this->item->ats_ticket_id ?>&<?php echo \JFactory::getSession()->getFormToken() ?>=1" class="btn btn-success">
                <i class="icon-white icon-plus-sign"></i>
                <?php echo JText::sprintf('COM_ATS_TICKETS_CREDITS_CHARGE', Credits::creditsRequired($this->item->catid, true, $this->item->public, $this->item->priority)); ?>
            </a>
        <?php endif; ?>
    </div>
<?php endif; ?>