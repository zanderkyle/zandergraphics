<?php
/**
 * @package ats
 * @copyright Copyright (c)2011-2014 Nicholas K. Dionysopoulos / AkeebaBackup.com
 * @license GNU GPL v3 or later
 */

/** @var Akeeba\TicketSystem\Site\View\Tickets\Html $this */

// No direct access
defined('_JEXEC') or die;

use \Akeeba\TicketSystem\Admin\Helper\Html;
use \Akeeba\TicketSystem\Admin\Helper\Select;

// Load the required CSS
$this->getContainer()->template->addCSS('media://com_ats/css/frontend.css');
$this->getContainer()->template->addJS('media://com_ats/js/tickets.js', false, false, $this->getContainer()->mediaVersion);

$user = JFactory::getUser();

// Get the Itemid
$itemId = $this->input->getInt('Itemid',0);
if($itemId != 0)
{
	$actionURL = JUri::root().'index.php?Itemid='.$itemId;
}
else
{
	$actionURL = JUri::root().'index.php';
}


$filter_status = $this->getModel()->getState('status','');
?>
<div class="akeeba-bootstrap">

<?php echo Html::loadposition('ats-top'); ?>
<?php echo Html::loadposition('ats-tickets-top'); ?>

<?php if ($this->getPageParams()->get('show_page_heading', 1)) : ?>
	<h1>
		<?php echo $this->escape($this->category->title); ?>
	</h1>
<?php endif; ?>

<div class="ats-buttonbar">
	<?php if($this->canCreate): ?>
	<div class="ats-button ats-button-action-newticket" style="float:left">
		<span class="ats-button-wrapper">
			<span class="ats-button-pre"></span>
			<a class="btn btn-success" href="<?php echo JRoute::_('index.php?option=com_ats&view=NewTicket&category='.$this->category->id.($itemId ? '&Itemid='.$itemId : '')) ?>">
				<i class="icon-file icon-white"></i>
				<?php echo JText::_('COM_ATS_TICKETS_BUTTON_NEWTICKET') ?>
			</a>
			<span class="ats-button-post"></span>
		</span>
	</div>
	<?php endif; ?>
	<div style="float: right;">
		<?php echo Select::ticketstatuses($filter_status, 'filter-status', array('onchange'=>"document.getElementById('ats_filter_status').value = document.getElementById('filter-status')[document.getElementById('filter-status').selectedIndex].value;document.forms.atspagination.submit();"), 'filter-status') ?>
	</div>

	<div class="ats-clear"></div>
</div>

<div class="ats-clear"></div>

<?php
    if(!$this->items)
    {
        echo Html::loadposition('ats-tickets-none-top');

        echo '<p>'.JText::_('COM_ATS_TICKETS_MSG_NOTICKETS').'</p>';

        echo Html::loadposition('ats-tickets-none-bottom');
    }
    else
    {
        ?>
        <table class="table table-striped">
        <?php
            /** @var \Akeeba\TicketSystem\Site\Model\Tickets $ticket */
            foreach($this->getItems() as $ticket)
            {
                echo $this->loadAnyTemplate('site:com_ats/Tickets/default_ticket', array(
                    'ticket' => $ticket
                ));
            }
            ?>
        </table>
    <?php
    }
?>

<form id="ats-pagination" name="atspagination" action="<?php echo $actionURL ?>" method="post">
	<input type="hidden" name="option" value="com_ats" />
	<input type="hidden" name="view" value="Tickets" />
	<input type="hidden" name="category" value="<?php echo $this->category->id ?>" />
	<input type="hidden" name="status" value="<?php echo $filter_status; ?>" id="ats_filter_status" />
	<input type="hidden" name="<?php echo JFactory::getSession()->getFormToken();?>" value="1" id="token" />
	<input type="hidden" id="user" value="<?php echo JFactory::getUser()->id?>" />
	<input type="hidden" name="task" value="browse" />

	<div class="pagination">
		<p class="counter">
			<?php echo $this->pagination->getPagesCounter(); ?>
		</p>

		<?php echo $this->pagination->getPagesLinks(); ?>
	</div>
</form>

<?php
    echo Html::loadposition('ats-tickets-bottom');
    echo Html::loadposition('ats-bottom');
?>

</div>