<?php
/**
 * @package ats
 * @copyright Copyright (c)2011-2014 Nicholas K. Dionysopoulos / AkeebaBackup.com
 * @license GNU GPL v3 or later
 */

/** @var    FOF30\View\DataView\Html    $this */
defined('_JEXEC') or die;

use Akeeba\TicketSystem\Admin\Helper\ComponentParams;
use Akeeba\TicketSystem\Admin\Helper\Editor;
use Akeeba\TicketSystem\Admin\Helper\Html;
use Akeeba\TicketSystem\Admin\Helper\Permissions;
use Akeeba\TicketSystem\Admin\Helper\Select;

/*
 * Params passed by the loadAnyTemplate function
 *
 * $category            : Category id
 * $direct              : ???
 * $allow_attachment    : Are attachments allowed?
 * $post_content        : Content of the post stored in the session (previous failed save)
 */

if(!isset($category))           $category = 0;
if(!isset($direct))             $direct = false;
if(!isset($allow_attachment))   $allow_attachment = false;
if(!isset($post_content))       $post_content = '';

JHtml::_('formbehavior.chosen');
JHTML::_('behavior.tooltip');
JHTML::_('behavior.modal');

$isAdmin       = JFactory::getUser()->authorise('core.admin', 'com_ats') || JFactory::getUser()->authorise('core.manage', 'com_ats');
$isManager     = Permissions::isManager($category);
$uploadLimit   = Html::getUploadLimits();
$allowedExt    = Html::allowedExtensions();
$moduleContent = '';

if(!$isManager)
{
    $moduleContent = Html::loadposition('ats-replyarea-overlay');
}

$tags        = array();

if($this->item->created_by)
{
    /** @var \Akeeba\TicketSystem\Admin\Model\UserTags $tagModel */
    $tagModel = $this->container->factory->model('UserTags')->tmpInstance();
    $tags     = $tagModel->loadTagsByUser($this->item->created_by);
}

if($allow_attachment)
{
    $js = <<<JS
akeeba.jQuery(document).ready(function(jQuery){
    jQuery('#addAttachment').click(function(){
        if(jQuery('input[name="attachedfile[]"]').length >= 10)
        {
            return false;
        }

        var clone = jQuery('.attachmentWrapper').first().clone();
        clone.children('input').val('');
        clone.children('a').css('display', 'inline-block');

        clone.appendTo('.attachmentHolder');
    })

    jQuery('.attachmentHolder').on('click', '.attachmentWrapper a', function(){
        jQuery(this).parent().remove();
    });
})
JS;

    $this->container->template->addJSInline($js);
}

?>
<?php if(!empty($moduleContent)): ?>
    <div id="ats-replyarea-overlay">
        <?php echo $moduleContent ?>
        <p>
            <a href="javascript:void(0);" id="ats-replyarea-overlay-close" class="btn btn-success">
                <i class="icon-ok icon-white"></i>
                <?php echo JText::_('COM_ATS_NEWTICKET_INSTANTREPLY_CLOSEMESSAGE'); ?>
            </a>
        </p>
    </div>
<?php endif ?>

    <div class="controls">
        <?php if(Editor::isEditorBBcode()): ?>
            <div class="ats-ticket-content-info help-block">
                <?php echo JText::_('COM_ATS_NEWTICKET_LBL_YOURREQUEST_INFO'); ?>
            </div>
        <?php endif; ?>
    </div>
    <div class="ats-ticket-replyarea-content bbcode">
        <?php
        $contents = '';

        if(Editor::isEditorBBcode())
        {
            $name = $direct ? 'content' : 'post[content]';
            $id = 'bbcode';
        }
        else
        {
            $name = $direct ? 'content_html' : 'post[content_html]';
            $id = 'ats-content';
        }

        $params 				 = array();
        $params['buckets']  	 = $isAdmin && ATS_PRO;
        $params['ats_ticket_id'] = $this->item->ats_ticket_id;

        if($isManager)
        {
            $params['cannedreplies'] = true;

            if (isset($category))
            {
                $params['category'] = $category;
            }
        }

        Editor::showEditor($name, $id, $post_content, '95%', 400, 80, 20, $params);
        ?>
    </div>

<?php if($isManager || $allow_attachment): ?>
    <div class="ats-clear"></div>
    <div class="form-horizontal">
<?php endif; ?>

<?php if($isManager): ?>
    <?php if(ATS_PRO): ?>
        <?php if(ComponentParams::getParam('showcredits', 0)):?>
            <div class="control-group">
                <label for="ats-credits-charge" class="control-label"><?php echo JText::_('COM_ATS_POSTS_CREDITS_CHARGE')?></label>
                <div class="controls">
				<span class="input-append">
					<input type="text" name="extracredits" id="ats-credits-charge" class="input-small" value="" />
					<span class="add-on"><?php echo JText::_('COM_ATS_TICKETS_USERINFO_CREDITS') ?></span>
				</span>
                </div>
            </div>
        <?php endif;?>

        <div class="control-group">
            <label for="ats-timespent" class="control-label"><?php echo JText::_('COM_ATS_POSTS_LBL_TIMESPENT'); ?></label>
            <div class="controls">
				<span class="input-append">
					<input type="text" name="timespent" id="ats-timespent" class="input-small" value="" />
					<span class="add-on"><?php echo JText::_('COM_ATS_POSTS_LBL_TIMESPENT_MINUTES') ?></span>
				</span>
            </div>
        </div>
    <?php endif; ?>
    <div class="control-group">
        <label for="usertags" class="control-label"><?php echo JText::_('COM_ATS_COMMON_USER_TAGS')?></label>
        <div class="controls">
            <?php echo Select::usertags('usertags[]', $tags, array('class' => 'advancedSelect input-large', 'multiple' => 'multiple', 'size' => 5))?>
        </div>
    </div>
<?php endif; ?>

<?php if($allow_attachment): ?>
    <div class="ats-ticket-replyarea-attachment control-group">
        <label for="attachedfile" class="control-label"><?php echo JText::_('COM_ATS_NEWTICKET_LBL_ATTACHMENT'); ?></label>
        <div class="controls">
            <div class="attachmentHolder">
                <div class="attachmentWrapper">
                    <input type="file" name="attachedfile[]" size="10" />
                    <a class="btn btn-mini btn-inverse" href="javascript:void(0)" style="display: none"><?php echo JText::_('COM_ATS_COMMON_REMOVE')?></a>
                </div>
            </div>
			<span class="help-block">
				<?php echo JText::sprintf('COM_ATS_NEWTICKET_LBL_ATTACHMENT_MAXSIZE',$uploadLimit); ?>
			</span>
            <span class="help-block">
				<?php echo JText::sprintf('COM_ATS_NEWTICKET_LBL_ATTACHMENT_ALLOWED_EXTENSIONS', implode(', ',$allowedExt)); ?>
			</span>

            <a href="javascript:void(0);" class="btn" id="addAttachment"><?php echo JText::_('COM_ATS_POSTS_ADD_ATTACHMENT')?></a>
        </div>
    </div>
<?php endif; ?>

<?php if($isManager || $allow_attachment): ?>
    </div>
<?php endif; ?>

<?php
if(!empty($moduleContent))
{
    JFactory::getDocument()->addScriptDeclaration( <<<JS

;// This comment is intentionally put here to prevent badly written plugins from causing a Javascript error
// due to missing trailing semicolon and/or newline in their code.
(function($) {
	$(document).ready(function()	{
		$('#ats-replyarea-overlay-close').click(function(e){
			$('#ats-replyarea-overlay').hide();
		});
	});
})(akeeba.jQuery);
JS
    );
}
