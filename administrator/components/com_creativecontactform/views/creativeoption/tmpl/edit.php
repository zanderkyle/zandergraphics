<?php
/**
 * Joomla! component Creative Contact Form
 *
 * @version $Id: 2012-04-05 14:30:25 svn $
 * @author creative-solutions.net
 * @package Creative Contact Form
 * @subpackage com_creativecontactform
 * @license GNU/GPL
 *
 */

// no direct access
defined('_JEXEC') or die('Restircted access');;

JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
?>
<script type="text/javascript">
Joomla.submitbutton = function(task) {
	var form = document.adminForm;
	if (task == 'creativeoption.cancel') {
		submitform( task );
	}
	else {
		if (form.jform_name.value != ""){
			form.jform_name.style.border = "1px solid green";
		} 
		
		if (form.jform_name.value == ""){
			form.jform_name.style.border = "1px solid red";
			form.jform_name.focus();
		} 
		else {
			submitform( task );
		}
	}
	
}
</script>
<?php if(JV == 'j2') {//////////////////////////////////////////////////////////////////////////////////////Joomla2.x/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////?>
<form action="<?php echo JRoute::_('index.php?option=com_creativecontactform&layout=edit&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="adminForm">
	<?php if(($this->max_id < 2) || ($this->item->id != 0)) {?>
	<fieldset class="adminform">
		<legend><?php echo JText::_( 'COM_CREATIVECONTACTFORM_DETAILS' ); ?></legend>
		<ul class="adminformlist">
<?php foreach($this->form->getFieldset() as $field): ?>
			<li><?php echo $field->label;echo $field->input;?></li>
<?php endforeach; ?>
		</ul>
	</fieldset>
			<?php } else { ?>
				<div style="color: rgb(235, 9, 9);font-size: 16px;font-weight: bold;"><?php echo JText::_('COM_CREATIVECONTACTFORM_PLEASE_UPGRADE_TO_HAVE_MORE_THAN_TWO_FORMS');?></div>
					<div id="cpanel" style="float: left;">
					<div class="icon" style="float: right;">
					<a href="<?php echo JText::_( 'COM_CREATIVECONTACTFORM_SUBMENU_BUY_PRO_VERSION_LINK' ); ?>" target="_blank" title="<?php echo JText::_( 'COM_CREATIVECONTACTFORM_SUBMENU_BUY_PRO_VERSION_DESCRIPTION' ); ?>">
					<table style="width: 100%;height: 100%;text-decoration: none;">
					<tr>
					<td align="center" valign="middle">
					<img src="components/com_creativecontactform/assets/images/shopping_cart.png" /><br />
											<?php echo JText::_( 'COM_CREATIVECONTACTFORM_SUBMENU_BUY_PRO_VERSION' ); ?>
										</td>
									</tr>
								</table>
							</a>
						</div>
					</div>
			<?php }?>
	<div>
		<input type="hidden" name="task" value="creativepoll.edit" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>
<?php include (JPATH_BASE.'/components/com_creativecontactform/helpers/footer.php'); ?>
<?php }elseif(JV == 'j3') {//////////////////////////////////////////////////////////////////////////////////////Joomla3.x/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////?>
<?php 
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select');
?>
<form action="<?php echo JRoute::_('index.php?option=com_creativecontactform&layout=edit&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="adminForm" class="form-validate form-horizontal">
	<div class="row-fluid">
		<!-- Begin Newsfeed -->
		<div class="span10 form-horizontal">
			<?php if(true) {?>
			<fieldset>
				<div class="tab-content">
					<div class="tab-pane active" id="details">
						<div class="control-group">
							<?php foreach($this->form->getFieldset() as $field): ?>
								<div class="control-label"><?php echo $field->label;?></div>
								<div class="controls"><?php echo $field->input;?></div>
								<div style="clear: both;height: 8px;">&nbsp;</div>
							<?php endforeach; ?>
						</div>
					</div>
				</div>
			</fieldset>
			<?php } else { ?>
				<div style="color: rgb(235, 9, 9);font-size: 16px;font-weight: bold;"><?php echo JText::_('COM_CREATIVECONTACTFORM_PLEASE_UPGRADE_TO_HAVE_MORE_THAN_TWO_FORMS');?></div>
					<div id="cpanel" style="float: left;">
					<div class="icon" style="float: right;">
					<a href="<?php echo JText::_( 'COM_CREATIVECONTACTFORM_SUBMENU_BUY_PRO_VERSION_LINK' ); ?>" target="_blank" title="<?php echo JText::_( 'COM_CREATIVECONTACTFORM_SUBMENU_BUY_PRO_VERSION_DESCRIPTION' ); ?>">
					<table style="width: 100%;height: 100%;text-decoration: none;">
					<tr>
					<td align="center" valign="middle">
					<img src="components/com_creativecontactform/assets/images/shopping_cart.png" /><br />
											<?php echo JText::_( 'COM_CREATIVECONTACTFORM_SUBMENU_BUY_PRO_VERSION' ); ?>
										</td>
									</tr>
								</table>
							</a>
						</div>
					</div>
			<?php }?>
		</div>
	</div>
<input type="hidden" name="task" value="creativeoption.edit" />
<?php echo JHtml::_('form.token'); ?>
</form>
<?php include (JPATH_BASE.'/components/com_creativecontactform/helpers/footer.php'); ?>
<?php }?>
