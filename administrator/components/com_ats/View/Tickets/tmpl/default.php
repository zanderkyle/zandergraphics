<?php
/**
 * @package ats
 * @copyright Copyright (c)2011-2014 Nicholas K. Dionysopoulos / AkeebaBackup.com
 * @license GNU GPL v3 or later
 */

namespace Akeeba\TicketSystem\Admin\View\Tickets\Html;

// No direct access
use Akeeba\TicketSystem\Admin\Helper\Html;
use Akeeba\TicketSystem\Admin\Helper\Select;
use JHtml;
use JRoute;
use JText;

defined('_JEXEC') or die;

JHtml::_('behavior.modal');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('dropdown.init');
JHtml::_('formbehavior.chosen', 'select');

/** @var $this \FOF30\View\DataView\Html $container */
$container = $this->getContainer();
$container->template->addCSS('media://com_ats/css/backend.css');
$container->template->addJS('media://com_ats/js/adm_tickets.js', false, false, $container->mediaVersion);
$throbber   = $container->template->parsePath('media://com_ats/images/throbber.gif');

$user		= $container->platform->getUser();
$userId		= $user->id;

/** @var \Akeeba\TicketSystem\Admin\Model\Tickets $model */
$model      = $this->getModel();

$sortFields = array(
	'ats_ticket_id' => JText::_('JGRID_HEADING_ID'),
	'created_by'    => JText::_('COM_ATS_TICKETS_HEADING_USER'),
	'created_on'    => JText::_('COM_ATS_TICKETS_HEADING_CREATED'),
	'modified_on'   => JText::_('COM_ATS_TICKETS_HEADING_MODIFIED'),
	'title'         => JText::_('COM_ATS_TICKETS_HEADING_TITLE'),
	'timespent'     => JText::_('COM_ATS_TICKETS_HEADING_TIMESPENT'),
	'enabled'       => JText::_('JSTATUS'),
	'status'        => JText::_('COM_ATS_TICKETS_HEADING_STATUS'),
	'public'        => JText::_('COM_ATS_TICKETS_HEADING_PUBLIC'),
	'catid'         => JText::_('JCATEGORY'),
);

?>

<script type="text/javascript">
	Joomla.orderTable = function() {
		table = document.getElementById("sortTable");
		direction = document.getElementById("directionTable");
		order = table.options[table.selectedIndex].value;
		if (order != '<?php echo $this->lists->order ?>')
		{
			dirn = 'asc';
		}
		else {
			dirn = direction.options[direction.selectedIndex].value;
		}
		Joomla.tableOrdering(order, dirn);
	}
</script>

<form action="<?php echo JRoute::_('index.php?option=com_ats&view=Tickets'); ?>" method="post" name="adminForm" id="adminForm">
	<input type="hidden" name="task" value="browse" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="filter_order" value="<?php echo $this->lists->order; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists->order_Dir; ?>" />
	<input type="hidden" id="token" name="<?php echo $container->session->getFormToken();?>" value="1" />
	<input type="hidden" id="user" value="<?php echo $userId?>" />

	<div id="filter-bar" class="btn-toolbar">
		<div class="btn-group pull-right hidden-phone">
			<label for="limit" class="element-invisible"><?php echo JText::_('JFIELD_PLG_SEARCH_SEARCHLIMIT_DESC') ?></label>
			<?php echo $this->getPagination()->getLimitBox(); ?>
		</div>
		<?php
		$asc_sel	= ($this->lists->order_Dir == 'asc') ? 'selected="selected"' : '';
		$desc_sel	= ($this->lists->order_Dir == 'desc') ? 'selected="selected"' : '';
		?>
		<div class="btn-group pull-right hidden-phone">
			<label for="directionTable" class="element-invisible"><?php echo JText::_('JFIELD_ORDERING_DESC') ?></label>
			<select name="directionTable" id="directionTable" class="input-medium" onchange="Joomla.orderTable()">
				<option value=""><?php echo JText::_('JFIELD_ORDERING_DESC') ?></option>
				<option value="asc" <?php echo $asc_sel ?>><?php echo JText::_('JGLOBAL_ORDER_ASCENDING') ?></option>
				<option value="desc" <?php echo $desc_sel ?>><?php echo JText::_('JGLOBAL_ORDER_DESCENDING') ?></option>
			</select>
		</div>
		<div class="btn-group pull-right">
			<label for="sortTable" class="element-invisible"><?php echo JText::_('JGLOBAL_SORT_BY') ?></label>
			<select name="sortTable" id="sortTable" class="input-medium" onchange="Joomla.orderTable()">
				<option value=""><?php echo JText::_('JGLOBAL_SORT_BY') ?></option>
				<?php echo JHtml::_('select.options', $sortFields, 'value', 'text', $this->lists->order) ?>
			</select>
		</div>
	</div>
	<div class="clearfix"> </div>

	<table class="table table-striped">
		<thead>
			<tr>
				<th width="1%">
					<input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
				</th>
				<th width="1%" class="nowrap">
					<?php echo JHtml::_('grid.sort', 'JGRID_HEADING_ID', 'ats_ticket_id', $this->lists->order_Dir, $this->lists->order, 'browse') ?>
				</th>
				<th width="15%" class="nowrap">
					<?php echo JHTML::_('grid.sort', 'COM_ATS_TICKETS_HEADING_USER', 'created_by', $this->lists->order_Dir, $this->lists->order, 'browse') ?>
				</th>
				<th width="4%" class="nowrap">
					<?php echo JHtml::_('grid.sort', 'COM_ATS_TICKETS_HEADING_CREATED', 'created_on', $this->lists->order_Dir, $this->lists->order, 'browse') ?>
				</th>
				<th width="4%" class="nowrap">
					<?php echo JHtml::_('grid.sort', 'COM_ATS_TICKETS_HEADING_MODIFIED', 'modified_on', $this->lists->order_Dir, $this->lists->order, 'browse') ?>
				</th>
				<th>
					<?php echo JHtml::_('grid.sort',  'COM_ATS_TICKETS_HEADING_TITLE', 'title', $this->lists->order_Dir, $this->lists->order, 'browse') ?>
				</th>
				<?php if (ATS_PRO): ?>
				<th>
					<?php echo JHtml::_('grid.sort',  'COM_ATS_TICKETS_HEADING_TIMESPENT', 'timespent', $this->lists->order_Dir, $this->lists->order, 'browse') ?>
				</th>
				<?php endif; ?>
				<th width="5%">
					<?php echo JHtml::_('grid.sort', 'JSTATUS', 'enabled', $this->lists->order_Dir, $this->lists->order, 'browse') ?>
				</th>
				<th width="5%">
					<?php echo JHtml::_('grid.sort', 'COM_ATS_TICKETS_HEADING_STATUS', 'status', $this->lists->order_Dir, $this->lists->order, 'browse') ?>
				</th>
				<th width="5%">
					<?php echo JHtml::_('grid.sort', 'COM_ATS_TICKETS_HEADING_PUBLIC', 'public', $this->lists->order_Dir, $this->lists->order, 'browse') ?>
				</th>
				<th width="10%">
					<?php echo JHtml::_('grid.sort', 'JCATEGORY', 'catid', $this->lists->order_Dir, $this->lists->order, 'browse') ?>
				</th>
			</tr>
			<tr>
				<td></td>
				<td>
					<input type="text" class="input-mini" name="ats_ticket_id" id="ats_ticket_id"
						   value="<?php echo $this->escape($model->getState('ats_ticket_id','')); ?>"
						   title="<?php echo JText::_('JGRID_HEADING_ID'); ?>"
						   placeholder="<?php echo JText::_('JGRID_HEADING_ID'); ?>"
						/>
					<nobr>
						<button class="btn btn-mini" type="submit"><?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?></button>
					</nobr>
				</td>
				<td>
					<input type="text" class="input-small" name="username" id="username"
							value="<?php echo $this->escape($model->getState('username','')); ?>"
							title="<?php echo JText::_('COM_ATS_TICKETS_HEADING_USER'); ?>"
							placeholder="<?php echo JText::_('COM_ATS_TICKETS_HEADING_USER'); ?>"
						/>
					<nobr>
					<button class="btn btn-mini" type="submit"><?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?></button>
					<button class="btn btn-mini" type="button" onclick="akeeba.jQuery('#username').val('');this.form.submit();"><?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?></button>
					</nobr>
				</td>
				<td colspan="2">
					<input type="text" class="input-medium" name="ass_username" id="ass_username"
							value="<?php echo $this->escape($model->getState('ass_username','')); ?>"
							title="<?php echo JText::_('COM_ATS_TICKETS_ASSIGNED_TO'); ?>"
							placeholder="<?php echo JText::_('COM_ATS_TICKETS_ASSIGNED_TO'); ?>"
						/>
					<nobr>
					<button class="btn btn-mini" type="submit"><?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?></button>
					<button class="btn btn-mini" type="button" onclick="akeeba.jQuery('#ass_username').val('');this.form.submit();"><?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?></button>
					</nobr>
				</td>
				<td>
					<input type="text" name="title" id="title"
						value="<?php echo $this->escape($model->getState('title',''));?>"
						class="input-large" onchange="document.adminForm.submit();"
						placeholder="<?php echo JText::_('COM_ATS_TICKETS_HEADING_TITLE'); ?>"
						/>
					<nobr>
					<button class="btn btn-mini" type="submit"><?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?></button>
					<button class="btn btn-mini" type="button" onclick="akeeba.jQuery('#title').val('');this.form.submit();"><?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?></button>
					</nobr>
				</td>
				<?php if (ATS_PRO): ?>
				<td></td>
				<?php endif; ?>
				<td>
					<?php echo Select::published($model->getState('enabled',''), 'enabled', array('onchange'=>"this.form.submit()",'class'=>'input-small')) ?>
				</td>
				<td>
					<?php echo Select::ticketstatuses($model->getState('status',''), 'status', array('onchange'=>"this.form.submit()",'class'=>'input-small'), 'atsstatus') ?>
				</td>
				<td>
					<?php echo Select::publicstate($model->getState('public',''), 'public', array('onchange'=>"this.form.submit()",'class'=>'input-small')) ?>
				</td>
				<td>
					<select name="catid" class="input-medium" onchange="this.form.submit()">
						<option value=""><?php echo JText::_('JOPTION_SELECT_CATEGORY');?></option>
						<?php echo JHtml::_('select.options', JHtml::_('category.options', 'com_ats'), 'value', 'text', $model->getState('catid',''));?>
					</select>
				</td>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="20">
					<?php if($this->pagination->total > 0) echo $this->pagination->getListFooter() ?>
				</td>
			</tr>
		</tfoot>
		<tbody>
		<?php
        if(!$this->items)
        {
            echo '<tr><td colspan="20">'.JText::_('COM_ATS_COMMON_NORECORDS').'</td></tr>';
        }
        else
        {
		    foreach ($this->items as $i => $item)
            {
                $i++;

                $item->cat_link = JRoute::_('index.php?option=com_categories&extension=com_ats&task=edit&type=other&cid[]='. $item->catid);

                $canEdit	 = $user->authorise('core.edit',		'com_ats.category.'.$item->catid);
                $canChange	 = $user->authorise('core.edit.state',	'com_ats.category.'.$item->catid);

                $assigned_class = $item->assigned_to ? 'badge-info' : '';
                $assigned_to    = $item->assigned_to ? $model->getUser($item->assigned_to)->name : JText::_('COM_ATS_TICKETS_UNASSIGNED');

                $item->published = $item->enabled;

                $avatarURL = Html::getAvatarURL($model->getUser($item->created_by), 64);
			?>
			<tr>
				<td class="center">
					<?php echo JHtml::_('grid.id', $i, $item->ats_ticket_id); ?>
				</td>
				<td class="center">
					<?php if ($item->priority > 5): ?>
					<span class="pull-left badge badge-info" style="margin-right: 2px">
						<i class="icon-arrow-down icon-white"></i>
					</span>
					<?php elseif (($item->priority > 0) && ($item->priority < 5)): ?>
					<span class="pull-left badge badge-important" style="margin-right: 2px">
						<i class="icon-arrow-up icon-white"></i>
					</span>
					<?php endif; ?>

					<?php echo $item->ats_ticket_id; ?>
				</td>
				<td>
					<?php if($avatarURL): ?>
						<img src="<?php echo $avatarURL?>" align="left" class="gravatar" />
					<?php endif?>

					<strong><?php echo $model->getUser($item->created_by)->username ?></strong> [ <?php echo $item->created_by; ?> ]
					<p class="smallsub">
					<?php echo $model->getUser($item->created_by)->name ?><br/>
					<?php echo $model->getUser($item->created_by)->email ?>
					</p>
				</td>
				<td  class="nowrap">
					<?php echo JHtml::_('date',$item->created_on, JText::_('DATE_FORMAT_LC4')); ?>
				</td>
				<td  class="nowrap">
					<?php if($item->modified_on == '0000-00-00 00:00:00'): ?>
					&mdash;
					<?php else: ?>
					<?php echo JHtml::_('date',$item->modified_on, JText::_('DATE_FORMAT_LC4')); ?>
					<?php endif; ?>
				</td>
				<td>
					<?php if (ATS_PRO): ?>
					<span class="badge assigned_to <?php echo $assigned_class?> pull-left"><?php echo $assigned_to?></span>
					<?php endif; ?>
					<?php if ($canEdit && ATS_PRO) : ?>
						<?php echo Html::buildManagerdd(null, array('div' => 'assignto pull-right', 'a' => 'btn-mini'), $item->catid)?>
						<span class="loading btn-small" style="display:none">
							<i class="icon-ok"></i>
							<i class="icon-warning-sign"></i>
							<img src="<?php echo $throbber ?>" />
						</span>
						<input type="hidden" class="ticket_id" value="<?php echo $item->ats_ticket_id ?>" />
						<br/>
						<a href="<?php echo JRoute::_('index.php?option=com_ats&view=Ticket&task=edit&id='.(int) $item->ats_ticket_id); ?>">
							<?php echo $this->escape($item->title); ?>
						</a>
					<?php else : ?>
						<?php echo $this->escape($item->title); ?>
					<?php endif; ?>

				</td>
				<?php if (ATS_PRO): ?>
				<td  class="nowrap">
					<?php echo $item->timespent > 0 ? sprintf('%.1f', $item->timespent) : '&mdash;'; ?>
				</td>
				<?php endif; ?>
				<td class="center">
					<?php echo JHTML::_('grid.published', $item, $i); ?>
				</td>
				<td class="center">
					<?php echo Html::decodeStatus($item->status, true)?>
					<?php echo Html::createStatusDropdown(array('div_style' => 'pull-right', 'btn_style' => '', 'title' => ''))?>
					<img src="<?php echo $throbber?>" style="display:none" />
				</td>
				<td class="center public">
					<?php echo JHtml::_('jgrid.published', $item->public, $i, 'public_', $canChange);?>
				</td>
				<td class="center">
					<?php
                        echo $item->joomla_category->title;
                    ?>
				</td>
			</tr>
        <?php
            }
         };
        ?>
		</tbody>
	</table>
</form>
<input type="hidden" id="warnmessage" value="<?php echo JText::_('JLIB_HTML_PLEASE_MAKE_A_SELECTION_FROM_THE_LIST')?>" />
