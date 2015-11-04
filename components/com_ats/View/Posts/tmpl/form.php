<?php
/**
 * @package ats
 * @copyright Copyright (c)2011-2014 Nicholas K. Dionysopoulos / AkeebaBackup.com
 * @license GNU GPL v3 or later
 */
use Akeeba\TicketSystem\Admin\Helper\Editor;
use Akeeba\TicketSystem\Admin\Helper\Select;
use Akeeba\TicketSystem\Site\Model\Posts;

/** @var Akeeba\TicketSystem\Site\View\Posts\Html $this */
defined('_JEXEC') or die;

// This file is loaded when editing existing tickets
JHtml::_('formbehavior.chosen');

$template = $this->container->template;
$template->addCSS('media://com_ats/css/frontend.css');
$template->addJSInline("\n;//\nfunction addToValidationFetchQueue(myfunction){}");
$template->addJSInline("\n;//\nfunction addToValidationQueue(myfunction){}");

/** @var Posts $post */
$post = $this->item;

if(!isset($returnURL))
{
	$returnURLtemp = $this->input->getString('returnurl',null);

	if(!empty($returnURLtemp))
    {
        $returnURL = $returnURLtemp;
    }
}

/** @var \Akeeba\TicketSystem\Admin\Model\UserTags $tagModel */
$tagModel = $this->container->factory->model('UserTags')->tmpInstance();
$tags     = $tagModel->loadTagsByUser($this->item->created_by);

?>
<div class="akeeba-bootstrap">
<div class="ats-ticket-replyarea">
<form class="form-horizontal" action="<?php echo JURI::base() ?>index.php" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">
	<input type="hidden" name="option" value="com_ats" />
	<input type="hidden" name="view" value="Post" />
	<input type="hidden" name="task" value="save" />
	<input type="hidden" name="ats_ticket_id" value="<?php echo $post->ats_ticket_id ?>" />
	<input type="hidden" name="ats_post_id" value="<?php echo $post->ats_post_id ?>" />
	<input type="hidden" name="<?php echo $this->getContainer()->session->getFormToken();?>" value="1" />
	<?php if(isset($returnURL)): ?>
	<input type="hidden" name="returnurl" value="<?php echo $returnURL ?>" />
	<?php endif; ?>

<?php
// Allow custom field edit only if I'm a manger and this is the fist post of the thread
if($this->isManager && $post->isFirstOne())
{
	$args      = array_merge($post->ticket->getData(), array('catid' => $post->ticket->catid));
	$jResponse = $this->container->platform->runPlugins('onTicketFormRenderPerCatFields', array($args));

	if(is_array($jResponse) && !empty($jResponse))
	{
		foreach($jResponse as $customFields)
		{
			if(is_array($customFields) && !empty($customFields))
			{
				foreach($customFields as $field)
				{
					if(array_key_exists('isValid', $field) && $this->input->getInt('formsubmit', 0))
					{
						$customField_class = $field['isValid'] ? (array_key_exists('validLabel', $field) ? 'success' : '') : 'error';
					}
					else
					{
						$customField_class = '';
					}
	?>
		<div class="control-group <?php echo $customField_class ?>">
			<label for="<?php echo $field['id']?>" class="control-label">
				<?php echo $field['label']?>
			</label>
			<div class="controls">
				<?php echo $field['elementHTML']?>
				<?php if(array_key_exists('validLabel', $field) && $this->input->getInt('formsubmit', 0)):?>
				<span id="<?php echo $field['id']?>_valid" class="help-inline"
					  style="<?php if(!$field['isValid']):?>display:none<?php endif?>">
						  <?php echo $field['validLabel']?>
				</span>
				<?php endif;?>
				<?php if(array_key_exists('invalidLabel', $field) && $this->input->getInt('formsubmit', 0)):?>
				<span id="<?php echo $field['id']?>_invalid" class="help-inline"
					  style="<?php if($field['isValid']):?>display:none<?php endif?>">
						  <?php echo $field['invalidLabel']?>
				</span>
				<?php endif;?>
			</div>
		</div>

	<?php
				}
			}
		}
	}
}
	?>

	<div class="ats-ticket-replyarea-content bbcode">
		<?php
			if(Editor::isEditorBBcode())
            {
				$name = 'content';
                $id   = 'bbcode';
                $contents = $post->content;
			}
            else
            {
				$name = 'content_html';
                $id   = 'ats-content';
                $contents = $post->content_html;
			}

			Editor::showEditor($name, $id, $contents, '95%', 400, 80, 20);
		?>
	</div>

<?php if($this->isManager && ATS_PRO): ?>
	<div class="ats-ticket-replyarea-timetracking form-inline">
        <div class="control-group">
		<label for="ats-timespent" class="control-label"><?php echo JText::_('COM_ATS_POSTS_LBL_TIMESPENT'); ?></label>
            <div class="controls">
                <span class="input-append">
                    <input type="text" name="timespent" id="ats-timespent" class="input-small" value="<?php echo $this->item->timespent ?>" />
                    <span class="add-on"><?php echo JText::_('COM_ATS_POSTS_LBL_TIMESPENT_MINUTES') ?></span>
                </span>
            </div>
        </div>
	</div>
<?php endif; ?>
<?php if($this->isManager) : ?>
    <div class="control-group">
        <label for="usertags" class="control-label"><?php echo JText::_('COM_ATS_COMMON_USER_TAGS')?></label>
        <div class="controls">
            <?php echo Select::usertags('usertags[]', $tags, array('class' => 'advancedSelect input-large', 'multiple' => 'multiple', 'size' => 5))?>
        </div>
    </div>
<?php endif; ?>

	<input class="btn btn-primary" type="submit" value="<?php echo JText::_('COM_ATS_POSTS_MSG_POST') ?>" />
</form>
</div>
</div>