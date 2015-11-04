<?php
/**
 * @package ats
 * @copyright Copyright (c)2011-2014 Nicholas K. Dionysopoulos / AkeebaBackup.com
 * @license GNU GPL v3 or later
 */

/** @var $this \Akeeba\TicketSystem\Admin\View\Tickets\Html */
use Akeeba\TicketSystem\Admin\Helper\Select;

defined('_JEXEC') or die;

JHtml::_('behavior.modal');

$container = $this->getContainer();
$tags      = array();

if($this->item->created_by)
{
    /** @var \Akeeba\TicketSystem\Admin\Model\UserTags $usertags */
    $usertags = $container->factory->model('UserTags')->tmpInstance();
    $tags     = $usertags->loadTagsByUser($this->item->created_by);
}

$js = <<<JS
function jSelectUser_userid(id, username)
{
	document.getElementById('created_by').value = id;
	document.getElementById('created_by_visible').value = username;

    akeeba.jQuery.ajax('index.php?option=com_ats&view=UserTags&task=getbyuser&user=' + id,{
        dataType : 'json',
        success : function(response){
            // On Joomla 2.5 I have "chosen", in 3.2 I have "liszt"
            akeeba.jQuery('#usertags').val(response).trigger('liszt:updated').trigger('chosen:updated');
        }
    });

	try {
		document.getElementById('sbox-window').close();
	} catch(err) {
		SqueezeBox.close();
	}
}

(function($){
	$(document).ready(function(){
		$('button.akmodal').click(function(e){
			SqueezeBox.fromElement(document.getElementById('userselect'), {
				parse: 'rel'
			});
			return false;
		})
	})
})(akeeba.jQuery);
JS;

$container->template->addJSInline($js);

?>
<div class="control-group">
    <label class="control-label"><?php echo JText::_('COM_ATS_COMMON_USERSELECT_LBL')?></label>
    <div class="controls">
        <input type="hidden" name="created_by" id="created_by" value="<?php echo $this->item->created_by?>" />
        <input type="text" class="input-medium" name="xxx_userid" id="created_by_visible" value="<?php echo $this->item->created_by ? $container->platform->getUser($this->item->created_by)->username : '' ?>" disabled="disabled" />
        <button onclick="return false;" class="btn btn-mini akmodal"><?php echo JText::_('COM_ATS_COMMON_SELECTUSER')?></button>
        <a class="akmodal" style="display: none" id="userselect" href="index.php?option=com_users&view=users&layout=modal&tmpl=component&field=userid" rel="{handler: 'iframe', size: {x: 800, y: 500}}">Select</a>
    </div>
</div>
<div class="control-group">
    <label class="control-label"><?php echo JText::_('COM_ATS_COMMON_USER_TAGS')?></label>
    <div class="controls">
        <?php echo Select::usertags('usertags[]', $tags, array('class' => 'advancedSelect', 'multiple' => 'multiple', 'size' => 5))?>
    </div>
</div>
