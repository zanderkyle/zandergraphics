<?php
/**
 * @package ats
 * @copyright Copyright (c)2011-2014 Nicholas K. Dionysopoulos / AkeebaBackup.com
 * @license GNU GPL v3 or later
 */

/** @var Akeeba\TicketSystem\Site\View\Jusers\Html $this */
defined('_JEXEC') or die();

JHtml::_('behavior.tooltip');

?>

<form action="<?php echo JUri::base()?>index.php" method="post" name="adminForm" id="adminForm">
<input type="hidden" name="option" value="com_ats" />
<input type="hidden" name="view" value="Jusers" />
<input type="hidden" id="task" name="task" value="browse" />
<input type="hidden" name="hidemainmenu" id="hidemainmenu" value="0" />
<input type="hidden" name="boxchecked" id="boxchecked" value="0" />
<input type="hidden" name="filter_order" id="filter_order" value="<?php echo $this->lists->order ?>" />
<input type="hidden" name="filter_order_Dir" id="filter_order_Dir" value="<?php echo $this->lists->order_Dir ?>" />
<input type="hidden" name="<?php echo $this->container->session->getFormToken();?>" value="1" />
<input type="hidden" name="tmpl" value="component" />
<input type="hidden" name="category" value="<?php echo $this->input->getInt('category', 0) ?>" />

<table id="ats-jusers" class="adminlist table table-striped" style="clear: both;">
	<thead>
		<tr>
			<th width="5"><?php echo  JText::_('#'); ?></th>
			<th width="5">ID</th>
			<th>
				<?php echo JHTML::_('grid.sort', 'COM_ATS_JUSER_USERNAME', 'username', $this->lists->order_Dir, $this->lists->order, 'browse') ?>
			</th>
			<th>
				<?php echo JHTML::_('grid.sort', 'COM_ATS_JUSER_NAME', 'name', $this->lists->order_Dir, $this->lists->order, 'browse') ?>
			</th>
			<th>
				<?php echo JHTML::_('grid.sort', 'COM_ATS_JUSER_EMAIL', 'email', $this->lists->order_Dir, $this->lists->order, 'browse') ?>
			</th>
		</tr>
		<tr>
			<td></td>
			<td></td>
			<td colspan="3">
				<input type="text" name="search" id="search"
					value="<?php echo $this->escape($this->getModel()->getState('search',''));?>"
					class="input-large" onchange="document.adminForm.submit();"
					placeholder="<?php echo JText::_('COM_ATS_JUSER_USERNAME') ?> / <?php echo JText::_('COM_ATS_JUSER_NAME') ?> / <?php echo JText::_('COM_ATS_JUSER_EMAIL') ?>"
					/>
				<nobr>
				<button class="ats-apply btn btn-mini" onclick="this.form.submit();">
					<?php echo JText::_('COM_ATS_JUSER_FILTER'); ?>
				</button>
				<button class="ats-reset btn btn-mini" onclick="document.adminForm.search.value='';this.form.submit();">
					<?php echo JText::_('COM_ATS_JUSER_RESET'); ?>
				</button>
				</nobr>
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
	<?php if(count($this->items)): ?>
	<?php $m = 1; $i = -1; ?>
	<?php foreach($this->items as $juser) :?>
	<?php
		$m = 1 - $m;
		$id = (int)$juser->id;
		$username = $this->escape($juser->username);
		$link = "window.parent.jSelectUser_userid('$id','$username');";
	?>
	<tr class="row<?php echo $m?>">
		<td><?php echo ++$i?></td>
		<td><?php echo $juser->id?></td>
		<td><a href="javascript:<?php echo $link?>"><?php echo $this->escape($juser->username)?></a></td>
		<td><a href="javascript:<?php echo $link?>"><?php echo $this->escape($juser->name)?></a></td>
		<td><a href="javascript:<?php echo $link?>"><?php echo $this->escape($juser->email)?></a></td>
	</tr>
	<?php endforeach; ?>
	<?php else: ?>
		<tr>
			<td colspan="20">
				<?php echo  JText::_('COM_ATS_COMMON_NORECORDS') ?>
			</td>
		</tr>
	<?php endif; ?>
	</tbody>
</table>
</form>