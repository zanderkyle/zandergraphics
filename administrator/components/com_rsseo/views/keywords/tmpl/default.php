<?php
/**
* @version 1.0.0
* @package RSSEO! 1.0.0
* @copyright (C) 2009-2012 www.rsjoomla.com
* @license GPL, http://www.gnu.org/copyleft/gpl.html
*/

defined('_JEXEC') or die('Restricted access');
JHtml::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_rsseo/helpers');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.keepalive');

$listOrder	= $this->escape($this->state->get('list.ordering'));
$listDirn	= $this->escape($this->state->get('list.direction'));
$sortFields = $this->getSortFields();
?>
<script type="text/javascript">
	Joomla.orderTable = function() {
		table = document.getElementById("sortTable");
		direction = document.getElementById("directionTable");
		order = table.options[table.selectedIndex].value;
		if (order != '<?php echo $listOrder; ?>') {
			dirn = 'asc';
		} else {
			dirn = direction.options[direction.selectedIndex].value;
		}
		Joomla.tableOrdering(order, dirn, '');
	}
</script>

<form action="<?php echo JRoute::_('index.php?option=com_rsseo&view=keywords');?>" method="post" name="adminForm" id="adminForm">
<div class="row-fluid">
	<div class="span2">
		<?php echo $this->sidebar; ?>
	</div>
	<div class="span10">
		<div id="filter-bar" class="btn-toolbar">
			<div class="filter-search btn-group pull-left">
				<label for="filter_search" class="element-invisible"><?php echo JText::_('COM_RSSEO_GLOBAL_SEARCH');?></label>
				<input type="text" name="filter_search" id="filter_search" placeholder="<?php echo JText::_('COM_RSSEO_GLOBAL_SEARCH'); ?>" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" title="<?php echo JText::_('COM_RSSEO_GLOBAL_SEARCH'); ?>" />
			</div>
			<div class="btn-group hidden-phone rsb_btns">
				<button class="btn" type="submit" title="<?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?>"><i class="icon-search"></i></button>
				<button class="btn" type="button" onclick="document.id('filter_search').value='';this.form.submit();" title="<?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?>"><i class="icon-remove"></i></button>
			</div>
			<?php if (rsseoHelper::isJ3()) { ?>
			<div class="btn-group pull-right hidden-phone">
				<label for="limit" class="element-invisible"><?php echo JText::_('JFIELD_PLG_SEARCH_SEARCHLIMIT_DESC');?></label>
				<?php echo $this->pagination->getLimitBox(); ?>
			</div>
			<div class="btn-group pull-right hidden-phone">
				<label for="directionTable" class="element-invisible"><?php echo JText::_('JFIELD_ORDERING_DESC');?></label>
				<select name="filter_order_Dir" id="directionTable" class="input-small" onchange="Joomla.orderTable()">
					<option value=""><?php echo JText::_('JFIELD_ORDERING_DESC');?></option>
					<option value="asc" <?php if (strtolower($listDirn) == 'asc') echo 'selected="selected"'; ?>><?php echo JText::_('JGLOBAL_ORDER_ASCENDING');?></option>
					<option value="desc" <?php if (strtolower($listDirn) == 'desc') echo 'selected="selected"'; ?>><?php echo JText::_('JGLOBAL_ORDER_DESCENDING');?></option>
				</select>
			</div>
			<div class="btn-group pull-right">
				<label for="sortTable" class="element-invisible"><?php echo JText::_('JGLOBAL_SORT_BY');?></label>
				<select name="filter_order" id="sortTable" class="input-medium" onchange="Joomla.orderTable()">
					<option value=""><?php echo JText::_('JGLOBAL_SORT_BY');?></option>
					<?php echo JHtml::_('select.options', $sortFields, 'value', 'text', $listOrder);?>
				</select>
			</div>
			<?php } else { ?>
			<div class="btn-group pull-right">
				<?php echo JHtml::_('icon.filter', 'filter_importance', $this->filter, JText::_('COM_RSSEO_KEYWORDS_IMPORTANCE_SELECT'), $this->state->get('filter.importance'));?>
				<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
				<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
			</div>
			<?php } ?>
			<div class="clearfix"> </div>
		</div>
		<div class="clr"> </div>
		<table class="table table-striped adminlist">
			<thead>
				<th width="1%" align="center" class="hidden-phone"><input type="checkbox" name="checkall-toggle" value="" onclick="Joomla.checkAll(this);"/></th>
				<th><?php echo JHtml::_('grid.sort', 'COM_RSSEO_KEYWORDS_KEYWORD', 'keyword', $listDirn, $listOrder); ?></th>
				<th width="15%" class="center hidden-phone" align="center"><?php echo JHtml::_('grid.sort', 'COM_RSSEO_KEYWORDS_IMPORTANCE', 'importance', $listDirn, $listOrder); ?></th>
				<th width="6%" class="center" align="center"><?php echo JHtml::_('grid.sort', 'COM_RSSEO_KEYWORDS_POSITION', 'position', $listDirn, $listOrder); ?></th>
				<th width="15%" class="center hidden-phone" align="center"><?php echo JHtml::_('grid.sort', 'COM_RSSEO_KEYWORDS_DATE', 'date', $listDirn, $listOrder); ?></th>
				<th width="3%" class="center hidden-phone" align="center"><?php echo JText::_('COM_RSSEO_GLOBAL_REFRESH'); ?></th>
				<th width="4%" align="center" class="center hidden-phone"><?php echo JHtml::_('grid.sort', 'JGRID_HEADING_ID', 'id', $listDirn, $listOrder); ?></th>
			</thead>
			<tbody>
				<?php foreach ($this->items as $i => $item) { ?>
				<tr class="row<?php echo $i % 2; ?>">
					<td class="center hidden-phone"><?php echo JHTML::_('grid.id', $i, $item->id); ?></td>
					<td class="nowrap has-context">
						<a href="<?php echo JRoute::_('index.php?option=com_rsseo&task=keyword.edit&id='.$item->id); ?>">
							<?php echo $this->escape($item->keyword); ?>
						</a> 
					</td>
					
					<td align="center" class="center hidden-phone">
						<?php echo JText::_('COM_RSSEO_KEYWORD_IMPORTANCE_'.$item->importance); ?>
					</td>
					
					<td align="center" class="center nowrap has-context">
						<span class="badge badge-<?php echo $item->badge; ?>" id="position<?php echo $item->id; ?>">
							<?php echo $item->position; ?>
						</span>
					</td>
					
					<td align="center" class="center hidden-phone">
						<span id="date<?php echo $item->id; ?>">
							<?php echo JHtml::_('date', $item->date, rsseoHelper::getConfig('global_dateformat')); ?>
						</span>
					</td>
					
					<td align="center" class="center hidden-phone">
						<a href="javascript:void(0)" onclick="rsseo_keyword(<?php echo $item->id; ?>)" id="refresh<?php echo $item->id; ?>">
							<?php echo JText::_('COM_RSSEO_GLOBAL_REFRESH'); ?>
						</a>
						<img src="<?php echo JURI::root(); ?>administrator/components/com_rsseo/assets/images/loader.gif" alt="" id="loading<?php echo $item->id; ?>" style="display:none;" />
					</td>
					
					<td align="center" class="center hidden-phone">
						<?php echo $item->id; ?>
					</td>
				</tr>
				<?php } ?>
			</tbody>
			<tfoot>
				<tr>
					<td colspan="7">
						<?php echo $this->pagination->getListFooter(); ?>
					</td>
				</tr>
			</tfoot>
		</table>
	</div>
</div>

	<?php echo JHTML::_( 'form.token' ); ?>
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="task" value="" />
</form>