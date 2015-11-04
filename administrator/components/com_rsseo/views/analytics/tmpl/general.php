<?php
/**
* @version 1.0.0
* @package RSSEO! 1.0.0
* @copyright (C) 2009-2012 www.rsjoomla.com
* @license GPL, http://www.gnu.org/copyleft/gpl.html
*/

defined('_JEXEC') or die('Restricted access'); ?>
<?php if (is_array($this->general)) { ?>
<fieldset>
	<legend><?php echo JText::_('COM_RSSEO_GA_GENERAL'); ?></legend>
	<table class="table table-striped adminlist" style="width: 65%">
		<tbody>
		<?php if (!empty($this->general)) { ?>
		<?php foreach ($this->general as $result) { ?>
			<tr class="hasTip" title="<?php echo $result->descr; ?>">
				<td style="text-align:right;"><?php echo $result->title; ?></td>
				<td class="key" style="text-align:left;"><?php echo $result->value; ?></td>
			</tr>
		<?php } ?>
		<?php } ?>
		</tbody>
	</table>
</fieldset>
<?php } else echo $this->general; ?>