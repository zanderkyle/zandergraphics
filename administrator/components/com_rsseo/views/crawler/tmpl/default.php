<?php
/**
* @version 1.0.0
* @package RSSEO! 1.0.0
* @copyright (C) 2009-2012 www.rsjoomla.com
* @license GPL, http://www.gnu.org/copyleft/gpl.html
*/

defined('_JEXEC') or die('Restricted access'); ?>

<form action="<?php echo JRoute::_('index.php?option=com_rsseo&view=crawler');?>" method="post" name="adminForm" id="adminForm">
<div class="row-fluid">
	<div class="span2">
		<?php echo $this->sidebar; ?>
	</div>
	<div class="span10">
		<?php if ($this->config->crawler_enable_auto) { ?>
			<div class="rssmessage" id="rssmessage" style="display:none;"><?php echo JText::_('COM_RSSEO_CRAWLER_MESSAGE'); ?></div>
		<?php } ?>
		<table class="table table-striped adminform">
			<tr>
				<td width="300"><?php echo JText::_('COM_RSSEO_CRAWLER_URL'); ?></td>
				<td><span id="url"></span></td>
			</tr>
			<tr>
				<td width="300"><?php echo JText::_('COM_RSSEO_CRAWLER_LEVEL'); ?></td>
				<td><span id="level"></span></td>
			</tr>
			<tr>
				<td width="300"><?php echo JText::_('COM_RSSEO_CRAWLER_PAGES_SCANED'); ?></td>
				<td><span id="scaned"></span></td>
			</tr>
			<tr>
				<td width="300"><?php echo JText::_('COM_RSSEO_CRAWLER_PAGES_LEFT'); ?></td>
				<td><span id="remaining"></span></td>
			</tr>
			<tr>
				<td width="300"><?php echo JText::_('COM_RSSEO_CRAWLER_PAGES_TOTAL'); ?></td>
				<td><span id="total"></span></td>
			</tr>
			<tr>
				<td colspan="2">
					<button type="button" class="btn btn-primary button_start" onclick="rsseo_crawl(1,0);"><?php echo JText::_('COM_RSSEO_CRAWLER_START'); ?></button>
					<button type="button" class="btn btn-info button_pause" onclick="rsseo_pause();"><?php echo JText::_('COM_RSSEO_CRAWLER_PAUSE'); ?></button>
					<button type="button" class="btn btn-success button_continue" onclick="rsseo_continue();"><?php echo JText::_('COM_RSSEO_CRAWLER_CONTINUE'); ?></button>
				</td>
			</tr>
		</table>
	</div>
</div>


	<?php echo JHTML::_( 'form.token' ); ?>
	<input type="hidden" name="pause" id="pause" value="0" />
	<input type="hidden" name="auto" id="auto" value="<?php echo $this->config->crawler_enable_auto; ?>" />
	<input type="hidden" name="task" value="" />
</form>