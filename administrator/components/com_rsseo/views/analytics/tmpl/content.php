<?php
/**
* @version 1.0.0
* @package RSSEO! 1.0.0
* @copyright (C) 2009-2012 www.rsjoomla.com
* @license GPL, http://www.gnu.org/copyleft/gpl.html
*/

defined('_JEXEC') or die('Restricted access'); ?>
<?php if (is_array($this->content)) { ?>
	<fieldset>
		<legend><?php echo JText::_('COM_RSSEO_GA_CONTENT'); ?></legend>
		<table class="table table-striped adminlist">
			<thead>
				<tr>
					<th><?php echo JText::_('COM_RSSEO_GA_CONTENT_PAGE'); ?></th>
					<th><?php echo JText::_('COM_RSSEO_GA_CONTENT_PAGEVISITS'); ?></th>
					<th><?php echo JText::_('COM_RSSEO_GA_CONTENT_UNIQUEPAGEVISITS'); ?></th>
					<th><?php echo JText::_('COM_RSSEO_GA_CONTENT_AVGTIME'); ?></th>
					<th><?php echo JText::_('COM_RSSEO_GA_CONTENT_BOUNCERATE'); ?></th>
					<th><?php echo JText::_('COM_RSSEO_GA_CONTENT_EXITS'); ?></th>
				</tr>
			</thead>
			<tbody>
			<?php if (!empty($this->content)) { ?>
			<?php foreach ($this->content as $i => $result) { ?>
				<tr class="row<?php echo $i % 2; ?>">
					<td><?php echo $result->page; ?></td>
					<td align="center"><?php echo $result->pageviews; ?></td>
					<td align="center"><?php echo $result->upageviews; ?></td>
					<td align="center"><?php echo $result->avgtimesite; ?></td>
					<td align="center"><?php echo $result->bouncerate; ?></td>
					<td align="center"><?php echo $result->exits; ?></td>
				</tr>
			<?php } ?>
			<?php } ?>
			</tbody>
		</table>
	</fieldset>
<?php } else echo $this->content; ?>