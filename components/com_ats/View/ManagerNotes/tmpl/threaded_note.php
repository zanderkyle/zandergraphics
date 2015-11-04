<?php
/**
 * @package ats
 * @copyright Copyright (c)2011-2014 Nicholas K. Dionysopoulos / AkeebaBackup.com
 * @license GNU GPL v3 or later
 */

/** @var \Akeeba\TicketSystem\Site\Model\ManagerNotes $item */
/** @var FOF30\View\DataView\Html $this */
defined('_JEXEC') or die;

use Akeeba\TicketSystem\Admin\Helper\Bbcode;
use Akeeba\TicketSystem\Admin\Helper\Format;
use Akeeba\TicketSystem\Admin\Helper\Html;

$container = $this->getContainer();
$platform  = $container->platform;
$postUser  = Html::getPostUser($item->created_by);
$user      = $platform->getUser();

// Get the return URL to point back to ourselves
$permalink = JUri::getInstance();
$permalink->setFragment('mn'.$item->ats_managernote_id);

// Get the token
$token = $container->session->getFormToken().'=1';

?>
<div class="ats-post ats-post-status-<?php echo $item->enabled ? 'published' : 'unpublished' ?>" id="mn<?php echo $item->ats_managernote_id ?>">
	<div class="ats-post-header">
		<span class="ats-post-header-date">
			<a href="<?php echo $permalink ?>">
				<?php echo $postUser->name ?> (<?php echo $postUser->username ?>)
				&bull;
				<?php echo Format::date($item->created_on); ?>
			</a>
		</span>
	</div>
	<div class="ats-post-body">
		<div class="ats-post-content">
			<div class="ats-post-content-html">
            <?php
                if(!$item->note_html)
                {
                    echo Bbcode::parseBBCode($item->note);
                }
                else
                {
                    echo $item->note_html;
                }
                ?>
			</div>
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
			<?php if($item->enabled): ?>
			<a class="btn" href="index.php?option=com_ats&view=ManagerNotes&task=unpublish&id=<?php echo $item->ats_managernote_id ?>&returnurl=<?php echo $returnURL ?>&<?php echo $token ?>">
				<i class="icon-lock"></i>
				<?php echo JText::_('COM_ATS_COMMON_UNPUBLISH') ?>
			</a>
			<?php else: ?>
			<a class="btn" href="index.php?option=com_ats&view=ManagerNotes&task=publish&id=<?php echo $item->ats_managernote_id ?>&returnurl=<?php echo $returnURL ?>&<?php echo $token ?>">
				<i class="icon-lock"></i>
				<?php echo JText::_('COM_ATS_COMMON_PUBLISH') ?>
			</a>
			<?php endif; ?>

			<a class="btn" href="index.php?option=com_ats&view=ManagerNotes&task=edit&id=<?php echo $item->ats_managernote_id ?>&returnurl=<?php echo $returnURL ?>&<?php echo $token ?>">
				<i class="icon-pencil"></i>
				<?php echo JText::_('COM_ATS_COMMON_EDIT') ?>
			</a>

			<a class="btn btn-danger" href="index.php?option=com_ats&view=ManagerNotes&task=remove&id=<?php echo $item->ats_managernote_id ?>&returnurl=<?php echo $returnURL ?>&<?php echo $token ?>" onclick="return confirm('<?php echo JText::_('COM_ATS_POSTS_MSG_DELETEPROMPT') ?>')">
				<i class="icon-trash icon-white"></i>
				<?php echo JText::_('COM_ATS_COMMON_DELETE') ?>
			</a>
		</div>
	</div>
</div>