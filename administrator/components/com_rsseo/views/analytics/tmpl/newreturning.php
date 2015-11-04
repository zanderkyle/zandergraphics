<?php
/**
* @version 1.0.0
* @package RSSEO! 1.0.0
* @copyright (C) 2009-2012 www.rsjoomla.com
* @license GPL, http://www.gnu.org/copyleft/gpl.html
*/

defined('_JEXEC') or die('Restricted access');?>
<?php if (is_array($this->newreturning)) { ?>
	<fieldset>
		<legend><?php echo JText::_('COM_RSSEO_GA_NEWVSRETURNING'); ?></legend>
		<table class="table table-striped adminlist">
			<thead>
				<tr>
					<th><?php echo JText::_('COM_RSSEO_GA_NVSR_VISITRORS_TYPE'); ?></th>
					<th><?php echo JText::_('COM_RSSEO_GA_NVSR_VISITS'); ?></th>
					<th><?php echo JText::_('COM_RSSEO_GA_NVSR_PAGEVISITS'); ?></th>
					<th><?php echo JText::_('COM_RSSEO_GA_NVSR_BOUNCERATE'); ?></th>
					<th><?php echo JText::_('COM_RSSEO_GA_NVSR_AVGTIME'); ?></th>
				</tr>
			</thead>
			<tbody>
			<?php if (!empty($this->newreturning)) { ?>
			<?php $i = 0; ?>
			<?php foreach ($this->newreturning as $type => $result) { ?>
				<tr class="row<?php echo $i % 2; ?>">
					<td><?php echo $type; ?></td>
					<td align="center"><?php echo $result->visits; ?></td>
					<td align="center"><?php echo $result->pagesvisits; ?></td>
					<td align="center"><?php echo $result->bouncerate; ?></td>
					<td align="center"><?php echo $result->avgtimesite; ?></td>
				</tr>
			<?php $i++; ?>
			<?php } ?>
			<?php } ?>
			</tbody>
		</table>
	</fieldset>
<?php } else echo $this->newreturning; ?>