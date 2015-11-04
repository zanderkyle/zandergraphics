<?php
/**
* @version 1.0.0
* @package RSSeo! 1.0.0
* @copyright (C) 2009-2012 www.rsjoomla.com
* @license GPL, http://www.gnu.org/copyleft/gpl.html
*/

defined('_JEXEC') or die('Restricted access');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.keepalive');

$listOrder	= $this->escape($this->state->get('list.ordering'));
$listDirn	= $this->escape($this->state->get('list.direction'));
$parent		= $this->escape($this->state->get('filter.parent'));
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
	
	Joomla.submitbutton = function(task) {
		if (task == 'back') {
			$('filter_parent').value = 0;
			Joomla.submitform();
			return false;
		} else if (task == 'compete') {
			competitor_value = $$('input[name^=cid[]]:checked')[0].value;
			competitor = $('competitor'+competitor_value).text;
			competitor = competitor.replace(/(^\s+|\s+$)/g,'');
			competitor = competitor.replace(/.*?:\/\//g, '');
			competitor = competitor.replace('www.','');
			window.open('http://siteanalytics.compete.com/'+competitor,'_blank');
		} else {
			Joomla.submitform(task);
		}
	}
</script>

<div class="row-fluid">
	<div class="span2">
		<?php echo $this->sidebar; ?>
	</div>
	<div class="span10">
		<form action="<?php echo JRoute::_('index.php?option=com_rsseo&view=competitors');?>" method="post" name="adminForm" id="adminForm">
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
				<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
				<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
				<?php } ?>
				<div class="clearfix"> </div>
			</div>
			<div class="clr"> </div>
			<table class="table table-striped adminlist">
				<thead>
					<th width="1%" align="center" class="small hidden-phone"><input type="checkbox" name="checkall-toggle" value="" onclick="Joomla.checkAll(this);"/></th>
					<?php if (!$parent) { ?>
					<th width="2%" class="small hidden-phone"><?php echo JText::_('COM_RSSEO_COMPETITORS_HISTORY'); ?></th>
					<th class="small"><?php echo JHtml::_('grid.sort', 'COM_RSSEO_COMPETITORS_COMPETITOR', 'name', $listDirn, $listOrder); ?></th>
					<?php } ?>
					<?php if ($this->config->enable_pr) { ?><th class="center small hidden-phone"><?php echo JHtml::_('grid.sort', 'COM_RSSEO_COMPETITORS_PAGE_RANK', 'pagerank', $listDirn, $listOrder); ?></th><?php } ?>
					<?php if ($this->config->enable_googlep) { ?><th class="center small hidden-phone"><?php echo JHtml::_('grid.sort', 'COM_RSSEO_COMPETITORS_GOOGLE_PAGES', 'googlep', $listDirn, $listOrder); ?></th><?php } ?>
					<?php if ($this->config->enable_googleb) { ?><th class="center small hidden-phone"><?php echo JHtml::_('grid.sort','COM_RSSEO_COMPETITORS_GOOGLE_BACKLINKS', 'googleb', $listDirn, $listOrder); ?></th><?php } ?>
					<?php if ($this->config->enable_bingp) { ?><th class="center small hidden-phone"><?php echo JHtml::_('grid.sort', 'COM_RSSEO_COMPETITORS_BING_PAGES', 'bingp', $listDirn, $listOrder); ?></th><?php } ?>
					<?php if ($this->config->enable_bingb) { ?><th class="center small hidden-phone"><?php echo JHtml::_('grid.sort', 'COM_RSSEO_COMPETITORS_BING_BACKLINKS', 'bingb', $listDirn, $listOrder); ?></th><?php } ?>
					<?php if ($this->config->enable_alexa) { ?><th class="center small hidden-phone"><?php echo JHtml::_('grid.sort', 'COM_RSSEO_COMPETITORS_ALEXA_RANK', 'alexa', $listDirn, $listOrder); ?></th><?php } ?>
					<?php if ($this->config->enable_tehnorati) { ?><th class="center small hidden-phone"><?php echo JHtml::_('grid.sort', 'COM_RSSEO_COMPETITORS_TECHNORATI_RANK', 'technorati', $listDirn, $listOrder); ?></th><?php } ?>
					<?php if ($this->config->enable_dmoz) { ?><th class="center small hidden-phone"><?php echo JHtml::_('grid.sort', 'COM_RSSEO_COMPETITORS_DMOZ_RANK', 'dmoz', $listDirn, $listOrder); ?></th><?php } ?>
					<th class="small center hidden-phone" align="center"><?php echo JHtml::_('grid.sort', 'COM_RSSEO_COMPETITORS_DATE', 'date', $listDirn, $listOrder); ?></th>
					<?php if (!$parent) { ?>
					<th class="small center hidden-phone" align="center"><?php echo JText::_('COM_RSSEO_GLOBAL_REFRESH'); ?></th>
					<?php } ?>
					<th width="1%" align="center" class="small center hidden-phone"><?php echo JHtml::_('grid.sort', 'JGRID_HEADING_ID', 'id', $listDirn, $listOrder); ?></th>
				</thead>
				<tbody>
					<?php foreach ($this->items as $i => $item) { ?>
					<tr class="row<?php echo $i % 2; ?>">
						<td class="center small hidden-phone"><?php echo JHTML::_('grid.id', $i, $item->id); ?></td>
						<?php if (!$parent) { ?>
						<td align="center" class="center small hidden-phone">
							<a href="javascript:void(0)" onclick="rsseo_history(<?php echo $item->id; ?>)">
								<span class="icon-history"></span>
							</a>
						</td>
						<td class="nowrap small has-context">
							<a href="<?php echo JRoute::_('index.php?option=com_rsseo&task=competitor.edit&id='.$item->id); ?>" id="competitor<?php echo $item->id; ?>">
								<?php echo $this->escape($item->name); ?>
							</a>
						</td>
						<?php } ?>
						<?php if ($this->config->enable_pr) { ?>
						<td align="center" class="center small hidden-phone">
							<span class="badge badge-<?php echo $item->pagerankbadge; ?>" id="pagerank<?php echo $item->id; ?>">
								<?php echo $item->pagerank; ?>
							</span>
						</td>
						<?php } ?>
						
						<?php if ($this->config->enable_googlep) { ?>
						<td align="center" class="center small hidden-phone">
							<span class="badge badge-<?php echo $item->googlepbadge; ?>" id="googlep<?php echo $item->id; ?>">
								<?php echo $item->googlep; ?>
							</span>
						</td>
						<?php } ?>
						
						<?php if ($this->config->enable_googleb) { ?>
						<td align="center" class="center small hidden-phone">
							<span class="badge badge-<?php echo $item->googlebbadge; ?>" id="googleb<?php echo $item->id; ?>">
								<?php echo $item->googleb; ?>
							</span>
						</td>
						<?php } ?>
						
						<?php if ($this->config->enable_bingp) { ?>
						<td align="center" class="center small hidden-phone">
							<span class="badge badge-<?php echo $item->bingpbadge; ?>" id="bingp<?php echo $item->id; ?>">
								<?php echo $item->bingp; ?>
							</span>
						</td>
						<?php } ?>
						
						<?php if ($this->config->enable_bingb) { ?>
						<td align="center" class="center small hidden-phone">
							<span class="badge badge-<?php echo $item->bingbbadge; ?>" id="bingb<?php echo $item->id; ?>">
								<?php echo $item->bingb; ?>
							</span>
						</td>
						<?php } ?>
						
						<?php if ($this->config->enable_alexa) { ?>
						<td align="center" class="center small hidden-phone">
							<span class="badge badge-<?php echo $item->alexabadge; ?>" id="alexa<?php echo $item->id; ?>">
								<?php echo $item->alexa; ?>
							</span>
						</td>
						<?php } ?>
						
						<?php if ($this->config->enable_tehnorati) { ?>
						<td align="center" class="center small hidden-phone">
							<span class="badge badge-<?php echo $item->technoratibadge; ?>" id="technorati<?php echo $item->id; ?>">
								<?php echo $item->technorati; ?>
							</span>
						</td>
						<?php } ?>
						
						<?php if ($this->config->enable_dmoz) { ?>
						<td align="center" class="center small hidden-phone">
							<span class="badge badge-<?php echo $item->dmozbadge; ?>" id="dmoz<?php echo $item->id; ?>">
								<?php
									if ($item->dmoz == -1) 
										echo '-';
									else if ($item->dmoz == 1) 
										echo JText::_('JYES');
									else if ($item->dmoz == 0) 
										echo JText::_('JNO');
								?>
							</span>
						</td>
						<?php } ?>
						
						<td align="center" class="center small hidden-phone">
							<span id="date<?php echo $item->id; ?>">
								<?php echo JHtml::_('date', $item->date, $this->config->global_dateformat); ?>
							</span>
						</td>
						
						<?php if (!$parent) { ?>
						<td align="center" class="center small hidden-phone">
							<a href="javascript:void(0)" onclick="rsseo_competitor(<?php echo $item->id; ?>)" id="refresh<?php echo $item->id; ?>">
								<?php echo JText::_('COM_RSSEO_GLOBAL_REFRESH'); ?>
							</a>
							<img src="<?php echo JURI::root(); ?>administrator/components/com_rsseo/assets/images/loader.gif" alt="" id="loading<?php echo $item->id; ?>" style="display:none;" />
						</td>
						<?php } ?>
						
						<td align="center" class="center small hidden-phone">
							<?php echo $item->id; ?>
						</td>
					</tr>
					<?php } ?>
				</tbody>
				<tfoot>
					<tr>
						<td colspan="15">
							<?php echo $this->pagination->getListFooter(); ?>
						</td>
					</tr>
				</tfoot>
			</table>
			
			<?php echo JHTML::_( 'form.token' ); ?>
			<input type="hidden" name="boxchecked" value="0" />
			<input type="hidden" name="task" value="" />
			<input type="hidden" name="filter_parent" id="filter_parent" value="<?php echo $this->state->get('filter.parent'); ?>" />
		</form>
	</div>
</div>