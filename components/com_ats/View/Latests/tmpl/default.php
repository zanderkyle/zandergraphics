<?php
/**
 * @package ats
 * @copyright Copyright (c)2011-2014 Nicholas K. Dionysopoulos / AkeebaBackup.com
 * @license GNU GPL v3 or later
 */

/** @var Akeeba\TicketSystem\Site\View\Latests\Html $this */
defined('_JEXEC') or die;

use Akeeba\TicketSystem\Admin\Helper\Html;
use Akeeba\TicketSystem\Admin\Helper\Permissions;
use Akeeba\TicketSystem\Admin\Helper\Select;

JHtml::_('formbehavior.chosen', 'select.atscategory');

$container = $this->getContainer();
$template  = $container->template;

$template->addCSS('media://com_ats/css/frontend.css');
$template->addJS('media://com_ats/js/tickets.js', false, false, $container->mediaVersion);
$template->addJS('media://com_ats/js/latestopen-keyboard.js', false, false, $container->mediaVersion);

$js = <<<JS
if(typeof(akeeba) == 'undefined') {
	var akeeba = {};
}
if(typeof(akeeba.jQuery) == 'undefined') {
	akeeba.jQuery = jQuery.noConflict();
}

akeeba.jQuery(document).ready(function($){
    $('#category').change(function(){
    	$('#ats-pagination').submit();
    });
})
JS;

$template->addJSInline($js);

// Get the Itemid
$itemId = $this->input->getInt('Itemid',0);
if($itemId != 0) {
	$actionURL = JUri::root().'index.php?Itemid='.$itemId;
} else {
	$actionURL = JUri::root().'index.php';
}

?>
<?php echo Html::loadposition('ats-top'); ?>
<?php echo Html::loadposition('ats-latest-top'); ?>
<?php if ($this->getPageParams()->get('show_page_heading', 1)) : ?>
	<h1>
		<?php echo JText::_('COM_ATS_LATEST_TITLE'); ?>
	</h1>
<?php endif; ?>

<form id="ats-pagination" name="latestTicketsForm" action="<?php echo $actionURL ?>" method="post">
	<input type="hidden" name="option" value="com_ats" />
	<input type="hidden" name="view" value="Latests" />
	<input type="hidden" name="<?php echo $container->session->getFormToken();?>" value="1" id="token" />
	<input type="hidden" id="user" value="<?php echo $container->platform->getUser()->id ?>" />

	<div class="ats-latests-filters">
		<div class="pull-right">
			<span>
				<?php echo Select::getCategories($this->getModel()->getState('category'), 'category', array('class' => 'atscategory'))?>
			</span>
		</div>

		<div class="ats-clear"></div>
	</div>


<?php if(!count($this->items)): ?>
<?php echo Html::loadposition('ats-latest-none-top'); ?>
<p>
	<?php echo JText::_('COM_ATS_LATEST_MSG_NOTICKETS') ?>
</p>
<?php echo Html::loadposition('ats-latest-none-bottom'); ?>

<?php else: ?>
<table class="table table-striped ats-latestopen-table">
	<thead>
<?php
	$counter = 0;
    /** @var \Akeeba\TicketSystem\Site\Model\Tickets $ticket */
    foreach($this->items as $ticket)
    {
        $this->isManager = Permissions::isManager($ticket->catid);
        $counter++;

        echo $this->loadAnyTemplate('site:com_ats/Tickets/default_ticket', array(
            'ticket'         => $ticket,
            'showAgo'        => 1,
            'showCategory'   => 1,
            'showStatusDD'   => 0,
            'showStatus'     => 0,
            'extraRowAttr'   => 'data-latestopentid="'.$ticket->ats_ticket_id.'" data-latestopensequence="'.$counter.'"',
            'extraLinkAttr'  => 'id="ats-latestopenlink-'.$counter.'"'
        ));
	}
?>
	</thead>
</table>

    <div class="pagination">
        <p class="counter">
            <?php echo $this->pagination->getPagesCounter(); ?>
        </p>
        <?php echo $this->pagination->getPagesLinks(); ?>
    </div>
<?php endif; ?>
</form>
<?php echo Html::loadposition('ats-latest-bottom'); ?>
<?php echo Html::loadposition('ats-bottom'); ?>