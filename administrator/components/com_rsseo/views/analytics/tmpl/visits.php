<?php
/**
* @version 1.0.0
* @package RSSEO! 1.0.0
* @copyright (C) 2009-2012 www.rsjoomla.com
* @license GPL, http://www.gnu.org/copyleft/gpl.html
*/

defined('_JEXEC') or die('Restricted access');?>
<?php if (is_array($this->visits)) { ?>
	<fieldset>
		<legend><?php echo JText::_('COM_RSSEO_GA_VISITSPERDAY'); ?></legend>
		<table class="table table-striped adminlist">
			<thead>
				<tr>
					<th width="15%"><?php echo JText::_('COM_RSSEO_GA_VPD_DATE'); ?></th>
					<th><?php echo JText::_('COM_RSSEO_GA_VPD_VISITS'); ?></th>
				</tr>
			</thead>
			<tbody>
			<?php if (!empty($this->visits)) { ?>
			<?php $i = 0; ?>
			<?php foreach ($this->visits as $date => $result) { ?>
				<tr class="row<?php echo $i % 2; ?>">
					<td><?php echo @date('l, F d, Y',$date); ?></td>
					<td>
						<div class="rss_graph" style="width: <?php echo str_replace(' ','',$result->visitspercent); ?>"></div>
						<?php echo $result->visitspercent.' ('.$result->visits.')'; ?>
					</td>
				</tr>
			<?php $i++; ?>
			<?php } ?>
			<?php } ?>
			<tbody>
		</table>
	</fieldset>
<?php } else echo $this->visits; ?>