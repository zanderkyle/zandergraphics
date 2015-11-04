<?php
/**
 * @package ats
 * @copyright Copyright (c)2011-2014 Nicholas K. Dionysopoulos / AkeebaBackup.com
 * @license GNU GPL v3 or later
 */

/** @var Akeeba\TicketSystem\Site\View\Mies\Html $this */
defined('_JEXEC') or die;

use Akeeba\TicketSystem\Admin\Helper\Html;
use Akeeba\TicketSystem\Admin\Helper\Permissions;
use Akeeba\TicketSystem\Admin\Helper\Select;

$container = $this->getContainer();

$container->template->addCSS('media://com_ats/css/frontend.css');

$model  = $this->getModel();
$itemId = $this->input->getInt('Itemid',0);

if($itemId != 0)
{
	$actionURL = JUri::root().'index.php?Itemid='.$itemId;
}
else
{
	$actionURL = JUri::root().'index.php';
}

$category = $model->getState('category', 0);

$newTicketLink = JRoute::_('index.php?option=com_ats&view=NewTicket' .
	($category ? '&category=' . $category : '') .
	($itemId? '&Itemid=' . $itemId : '')
);

$js = <<<JS
if(typeof(akeeba) == 'undefined') {
	var akeeba = {};
}
if(typeof(akeeba.jQuery) == 'undefined') {
	akeeba.jQuery = jQuery.noConflict();
}

akeeba.jQuery(document).ready(function($){
    $('#reset').click(function(){
        $('#title').val('');
        $('#category').val('');
    });

    $('#category').change(function(){
        $('#adminForm').submit();
    });
})
JS;

$container->template->addJSInline($js);
?>
<div class="akeeba-bootstrap">
<?php echo Html::loadposition('ats-top'); ?>
<?php echo Html::loadposition('ats-mytickets-top'); ?>

<?php
    if ($this->getPageParams()->get('show_page_heading', 1)) :

        $title = JText::_('COM_ATS_MY_TITLE');

        if(Permissions::isManager())
        {
            if($userid = $this->input->getInt('userid'))
            {
                $title = JText::sprintf('COM_ATS_TICKETS_BY', $container->platform->getUser($userid)->username);
            }
        }
?>
	<h1>
		<?php echo $title ?>
	</h1>
<?php endif; ?>
<form id="adminForm" name="adminForm" class="form-search" action="<?php echo $actionURL ?>" method="post">
    <input type="hidden" name="option" value="com_ats" />
    <input type="hidden" name="view" value="Mies" />
    <input type="hidden" name="userid" value="<?php echo $this->input->getInt('userid', $this->container->platform->getUser()->id)?>"/>

    <div class="ats-mytickets-filters" style="margin-bottom: 10px">
        <div class="pull-left">
            <input type="text" name="title" id="title" value="<?php echo $model->getState('title')?>" placeholder="<?php echo JText::_('COM_ATS_TICKETS_HEADING_TITLE')?>" class="input-medium search-query" />
            <button class="btn btn-mini" id="ats-search" title="<?php echo JText::_('COM_ATS_COMMON_SEARCH')?>">
				<span class="icon icon-search glyphicon glyphicon-search"></span>
			</button>
            <button class="btn btn-mini" id="reset" title="<?php echo JText::_('COM_ATS_COMMON_RESET')?>">
				<span class="icon icon-cancel glyphicon glyphicon-remove"></span>
			</button>
			<a href="<?php echo $newTicketLink ?>" class="btn btn-success">
				<span class="icon-file icon-white"></span>
				<?php echo JText::_('COM_ATS_TICKETS_BUTTON_NEWTICKET'); ?>
			</a>
        </div>
        <div class="pull-right">
            <?php echo Select::getCategories($model->getState('category'))?>
        </div>

        <div class="ats-clear"></div>
    </div>

<?php if(!count($this->items)): ?>
<?php echo Html::loadposition('ats-mytickets-none-top'); ?>
<p>
	<?php echo JText::_('COM_ATS_MYTICKETS_MSG_NOTICKETS') ?>
</p>
<?php echo Html::loadposition('ats-mytickets-none-bottom'); ?>

<?php else: ?>
    <table class="table table-striped">
        <tbody>
    <?php
    foreach($this->items as $ticket)
    {
        echo $this->loadAnyTemplate('site:com_ats/Tickets/default_ticket', array(
            'ticket' => $ticket,
            'showMy' => 0
        ));
    }?>
        </tbody>
    </table>

    <div class="pagination">
            <p class="counter">
                <?php echo $this->pagination->getPagesCounter(); ?>
            </p>

        <?php echo $this->pagination->getPagesLinks(); ?>
    </div>
<?php endif; ?>
</form>

<?php echo Html::loadposition('ats-mytickets-bottom'); ?>
<?php echo Html::loadposition('ats-bottom'); ?>
</div>