<?php
/**
* @version 1.0.0
* @package RSSEO! 1.0.0
* @copyright (C) 2009-2012 www.rsjoomla.com
* @license GPL, http://www.gnu.org/copyleft/gpl.html
*/

defined('_JEXEC') or die('Restricted access');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.keepalive');
?>

<script type="text/javascript">
	Joomla.submitbutton = function(task)
	{
		if (task == 'keyword.cancel' || document.formvalidator.isValid(document.id('adminForm'))) {
			Joomla.submitform(task, document.getElementById('adminForm'));
		} else {
			alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED'));?>');
		}
	}
</script>

<form action="<?php echo JRoute::_('index.php?option=com_rsseo&view=keyword&layout=edit&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="adminForm" autocomplete="off" class="form-validate form-horizontal">
	<div class="row-fluid">
		<?php echo JHtml::_('rsfieldset.start', 'adminform'); ?>
		<?php echo JHtml::_('rsfieldset.element', $this->form->getLabel('keyword'), $this->form->getInput('keyword')); ?>
		<?php echo JHtml::_('rsfieldset.element', $this->form->getLabel('importance'), $this->form->getInput('importance')); ?>
		<?php echo JHtml::_('rsfieldset.element', $this->form->getLabel('bold'), $this->form->getInput('bold')); ?>
		<?php echo JHtml::_('rsfieldset.element', $this->form->getLabel('underline'), $this->form->getInput('underline')); ?>
		<?php echo JHtml::_('rsfieldset.element', $this->form->getLabel('limit'), $this->form->getInput('limit')); ?>
		<?php echo JHtml::_('rsfieldset.element', $this->form->getLabel('link'), $this->form->getInput('link')); ?>
		<?php echo JHtml::_('rsfieldset.element', $this->form->getLabel('attributes'), $this->form->getInput('attributes')); ?>
		<?php echo JHtml::_('rsfieldset.end'); ?>
	</div>

	<?php echo JHTML::_('form.token'); ?>
	<input type="hidden" name="task" value="" />
	<?php echo $this->form->getInput('id'); ?>
	<?php echo JHTML::_('behavior.keepalive'); ?>
</form>