<?php
/**
* @version 1.0.0
* @package RSSEO! 1.0.0
* @copyright (C) 2009-2012 www.rsjoomla.com
* @license GPL, http://www.gnu.org/copyleft/gpl.html
*/

defined('_JEXEC') or die('Restricted access');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.keepalive');
?>

<form action="<?php echo JRoute::_('index.php?option=com_rsseo&view=page&layout=details&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="adminForm" autocomplete="off" class="form-validate form-horizontal">
	<div class="row-fluid">
		<div class="span12">
			<strong><?php echo JText::_('COM_RSSEO_GLOBAL_URL'); ?></strong>: <a href="<?php echo JURI::root().$this->item->url; ?>" target="_blank"><?php echo JURI::root().$this->item->url; ?></a>
			<table class="<?php echo rsseoHelper::isJ3() ? 'table table-striped' : 'adminlist'; ?>">
				<thead>
					<tr>
						<th width="1%">#</th>
						<th><?php echo JText::_('COM_RSSEO_PAGE_ELEMENT'); ?></th>
						<th><?php echo JText::_('COM_RSSEO_PAGE_ELEMENT_FILESIZE'); ?></th>
						<th><?php echo JText::_('COM_RSSEO_PAGE_ELEMENT_FREQUENCY'); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php if (!empty($this->details['pages'])) { ?>
					<?php $i = 1; ?>
					<?php foreach ($this->details['pages'] as $page) { ?>
					<tr>
						<td><?php echo $i; ?></td>
						<td><?php echo $page->url; ?></td>
						<td><?php echo $page->size; ?></td>
						<td><?php echo $page->freq; ?></td>
					</tr>
					<?php $i++; ?>
					<?php } ?>
					<tr>
						<td colspan="2"><strong><?php echo JText::_('COM_RSSEO_GLOBAL_TOTAL'); ?></strong></td>
						<td colspan="2"><strong><?php echo $this->details['total']; ?></strong></td>
					</tr>
					<?php } ?>
				</tbody>
			</table>
		</div>
	</div>

	<?php echo JHTML::_('form.token'); ?>
	<input type="hidden" name="task" value="" />
	<?php echo JHTML::_('behavior.keepalive'); ?>
</form>