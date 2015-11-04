<?php
/**
* @version 1.0.0
* @package RSSeo! 1.0.0
* @copyright (C) 2009-2012 www.rsjoomla.com
* @license GPL, http://www.gnu.org/copyleft/gpl.html
*/

defined('_JEXEC') or die('Restricted access');
?>

<form action="<?php echo JRoute::_('index.php?option=com_rsseo&view=backup');?>" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">
<div class="row-fluid">
	<div class="span2">
		<?php echo $this->sidebar; ?>
	</div>
	<div class="span10">
		<?php if (!empty($this->process)) { ?>
		<?php if ($this->process == 'backup') { ?>
		<?php echo $this->backup; ?>
		<?php } else if ($this->process == 'restore') { ?>
		<?php echo $this->restore; ?>
		<center>
			<input type="file" size="50" name="rspackage">
			<button type="button" class="btn btn-info button" onclick="Joomla.submitbutton()"><?php echo JText::_('COM_RSSEO_IMPORT'); ?></button>
		</center>
		<?php } ?>
		<?php } else { ?>
		<center>
			<h3>
				<a href="<?php echo JRoute::_('index.php?option=com_rsseo&view=backup&process=backup');?>"><?php echo JText::_('COM_RSSEO_BACKUP'); ?></a> | 
				<a href="<?php echo JRoute::_('index.php?option=com_rsseo&view=backup&process=restore');?>"><?php echo JText::_('COM_RSSEO_RESTORE'); ?></a>
			</h3>
		</center>
		<?php } ?>
	</div>
</div>

<?php echo JHTML::_( 'form.token' ); ?>
<input type="hidden" name="task" value="" />
<input type="hidden" name="process" value="<?php echo $this->process; ?>" />
</form>