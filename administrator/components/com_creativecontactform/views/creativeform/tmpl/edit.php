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
defined('_JEXEC') or die('Restircted access');

JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
?>
<script type="text/javascript">
Joomla.submitbutton = function(task) {
	var form = document.adminForm;
	if (task == 'creativeform.cancel') {
		submitform( task );
	}
	else if (task == 'creativeform.save2copy') {
		alert('Please Upgrade to Commercial Version to use this feature!');
	}
	else {
		if (form.jform_name.value != ""){
			form.jform_name.style.border = "1px solid green";
		} 
		if (form.jform_top_text.value != ""){
			form.jform_top_text.style.border = "1px solid green";
		}
		if (form.jform_send_text.value != ""){
			form.jform_send_text.style.border = "1px solid green";
		}
		if (form.jform_send_new_text.value != ""){
			form.jform_send_new_text.style.border = "1px solid green";
		}
		
		if (form.jform_name.value == ""){
			form.jform_name.style.border = "1px solid red";
			form.jform_name.focus();
		}
		else if(form.jform_top_text.value == ""){
			form.jform_top_text.style.border = "1px solid red";
			form.jform_top_text.focus();
		}
		else if(form.jform_send_text.value == ""){
			form.jform_send_text.style.border = "1px solid red";
			form.jform_send_text.focus();
		}
		else if(form.jform_send_new_text.value == ""){
			form.jform_send_new_text.style.border = "1px solid red";
			form.jform_send_new_text.focus();
		}
		else {
			submitform( task );
		}
	}
	
}
</script>
<?php if(JV == 'j2') {//////////////////////////////////////////////////////////////////////////////////////Joomla2.x/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////?>
<form action="<?php echo JRoute::_('index.php?option=com_creativecontactform&layout=edit&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="adminForm">
	<?php if((int)$this->item->id != 0 ) {?><h3 style="font-size: 16px;font-weight: normal;font-style: italic;">To manage <b>form fields</b>, go to <a href="index.php?option=com_creativecontactform&view=creativefields&filter_form_id=<?php echo $this->item->id;?>" target="_blank">fields page.</a> Read more in <a href="http://creative-solutions.net/joomla/creative-contact-form/documentation?section=form-options" target="_blank">documentation.</a></h3><?php } ?>
	<?php if(($this->max_id < 1) || ($this->item->id != 0)) {?>
	<fieldset class="adminform">
		<legend><?php echo JText::_( 'Details' ); ?></legend>
		<ul class="adminformlist">
<?php 
$qq = 0;
foreach($this->form->getFieldset() as $field): ?>
		<?php echo $field->label;?>
		<?php if($qq == 9) echo '<span style="font-size: 12px;color: rgb(221, 0, 0);font-style: italic;text-decoration: underline;display: inline-block;margin-left: 0px;">Commercial Version</span><br /><a style="float: none;margin-top: 0px;clear:both;" href="http://creative-solutions.net/joomla/creative-contact-form/demo" target="_blank">See Templates Demo</a>'; ?>
	<div class="controls"><?php echo $field->input;?></div>
	<div style="clear: both;height: 8px;">&nbsp;</div>
	<?php if($qq == 0) {?>
		<h3 style="margin-bottom: 20px;">Basic Options</h3>
	<?php }?>
	<?php if($qq == 10) {?>
		<div></div><h3 style="margin-bottom: 5px;margin-top: 25px;">Email Settings</h3>
		<div style="color: #555;font-size: 12px;"><b>Note1:</b> Usually the form works well when <u style="font-weight: bold;">all email settings are empty</u>. It will use <b>global settings</b> in that case!</div>
		<div style="color: #555;font-size: 12px;margin-top: 3px;margin-bottom: 15px;"><b>Note2:</b> Some email servers require emails to be sent from the same server as the site in. If your domian is <b style="color: rgb(126, 33, 33)">example.com</b>,<br /><span style="display: inline-block;width: 5px;"></span>then you should set <b style="text-decoration: none;color: rgb(10, 47, 161);font-style: italic">Email To</b> it to <b style="color: rgb(126, 33, 33);">email1@example.com</b>, and <b style="text-decoration: none;color: rgb(10, 47, 161);font-style: italic">Email From</b> to <b style="color: rgb(126, 33, 33);">email2@example.com</b> (different emails).</div>
	<?php }?>
	<?php if($qq == 17) {?>
		<h3 style="margin-bottom: 20px;margin-top: 25px;">Redirect Options</h3>
	<?php }?>
	<?php if($qq == 21) {?>
		<h3 style="margin-bottom: 20px;margin-top: 25px;">Send Copy Options <span style="font-size: 12px;color: rgb(221, 0, 0);font-style: italic;text-decoration: underline;display: inline-block;margin-left: 5px;">Commercial Version</span></h3>
	<?php }?>
	<?php if($qq == 23) {?>
		<h3 style="margin-bottom: 20px;margin-top: 25px;">Shake Effect Options</h3>
	<?php }?>
	<?php if($qq == 26) {?>
		<h3 style="margin-bottom: 20px;margin-top: 25px;">User Info Options <span style="font-size: 12px;color: rgb(221, 0, 0);font-style: italic;text-decoration: underline;display: inline-block;margin-left: 5px;">Commercial Version</span></h3>
	<?php }?>
	<?php if($qq == 31) {?>
		<h3 style="margin-bottom: 20px;margin-top: 25px;">Custom Styling <span style="font-size: 12px;color: rgb(221, 0, 0);font-style: italic;text-decoration: underline;display: inline-block;margin-left: 5px;">Commercial Version</span></h3>
	<?php }?>
<?php $qq ++;
endforeach; ?>
		</ul>
	</fieldset>
			<?php } else { ?>
				<div style="color: rgb(235, 9, 9);font-size: 16px;font-weight: bold;">Please Upgrade to Commercial Version to have more than one Creative Forms!</div>
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
			<?php if(($this->max_id < 1) || ($this->item->id != 0)) {?>
			<fieldset>
				<?php if((int)$this->item->id != 0 ) {?><h3 style="font-size: 16px;font-weight: normal;font-style: italic;">To manage <b>form fields</b>, go to <a href="index.php?option=com_creativecontactform&view=creativefields&filter_form_id=<?php echo $this->item->id;?>" target="_blank">fields page.</a> Read more in <a href="http://creative-solutions.net/joomla/creative-contact-form/documentation?section=form-options" target="_blank">documentation.</a></h3><?php } ?>
				<div class="tab-content">
					<div class="tab-pane active" id="details">
						<div class="control-group">
							<?php
							$qq = 0; 
							foreach($this->form->getFieldset() as $field): ?>
								<div class="control-label">
									<?php echo $field->label;?>
									<?php if($qq == 9) echo '<span style="font-size: 12px;color: rgb(221, 0, 0);font-style: italic;text-decoration: underline;display: inline-block;margin-left: 0px;">Commercial Version</span><br /><a style="float: left;margin-top: 0px;clear:both;" href="http://creative-solutions.net/joomla/creative-contact-form/demo" target="_blank">See Templates Demo</a>'; ?>
								</div>
								<div class="controls"><?php echo $field->input;?></div>
								<div style="clear: both;height: 8px;">&nbsp;</div>
								<?php if($qq == 0) {?>
									<h3 style="margin-bottom: 20px;">Basic Options</h3>
								<?php }?>
								<?php if($qq == 10) {?>
									<div></div><h3 style="margin-bottom: 5px;margin-top: 25px;">Email Settings</h3>
									<div style="color: #555;font-size: 12px;"><b>Note1:</b> Usually the form works well when <u style="font-weight: bold;">all email settings are empty</u>. It will use <b>global settings</b> in that case!</div>
									<div style="color: #555;font-size: 12px;margin-top: 3px;margin-bottom: 15px;"><b>Note2:</b> Some email servers require emails to be sent from the same server as the site in. If your domian is <b style="color: rgb(126, 33, 33)">example.com</b>,<br /><span style="display: inline-block;width: 5px;"></span>then you should set <b style="text-decoration: none;color: rgb(10, 47, 161);font-style: italic">Email To</b> it to <b style="color: rgb(126, 33, 33);">email1@example.com</b>, and <b style="text-decoration: none;color: rgb(10, 47, 161);font-style: italic">Email From</b> to <b style="color: rgb(126, 33, 33);">email2@example.com</b> (different emails).</div>
								<?php }?>
								<?php if($qq == 17) {?>
									<h3 style="margin-bottom: 20px;margin-top: 25px;">Redirect Options</h3>
								<?php }?>
								<?php if($qq == 21) {?>
									<h3 style="margin-bottom: 20px;margin-top: 25px;">Send Copy Options <span style="font-size: 12px;color: rgb(221, 0, 0);font-style: italic;text-decoration: underline;display: inline-block;margin-left: 5px;">Commercial Version</span></h3>
								<?php }?>
								<?php if($qq == 23) {?>
									<h3 style="margin-bottom: 20px;margin-top: 25px;">Shake Effect Options</h3>
								<?php }?>
								<?php if($qq == 26) {?>
									<h3 style="margin-bottom: 20px;margin-top: 25px;">User Info Options <span style="font-size: 12px;color: rgb(221, 0, 0);font-style: italic;text-decoration: underline;display: inline-block;margin-left: 5px;">Commercial Version</span></h3>
								<?php }?>
								<?php if($qq == 31) {?>
									<h3 style="margin-bottom: 20px;margin-top: 25px;">Custom Styling <span style="font-size: 12px;color: rgb(221, 0, 0);font-style: italic;text-decoration: underline;display: inline-block;margin-left: 5px;">Commercial Version</span></h3>
								<?php }?>
							<?php 
							$qq ++;
							endforeach; ?>
						</div>
					</div>
				</div>
			</fieldset>
			<?php } else { ?>
				<div style="color: rgb(235, 9, 9);font-size: 16px;font-weight: bold;">Please Upgrade to Commercial Version to have more than one Creative Forms!</div>
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
<input type="hidden" name="task" value="creativepoll.edit" />
<?php echo JHtml::_('form.token'); ?>
</form>
<?php include (JPATH_BASE.'/components/com_creativecontactform/helpers/footer.php'); ?>
<?php }?>
<style>
input, textarea, .uneditable-input {
	width: 430px;
}
div.chzn-container {
	width: 444px !important;
}
.form-horizontal .controls {
margin-left: 200px !important;
}
#jform_custom_css {
	height: 350px;
}
</style>
