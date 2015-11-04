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

$db = JFactory::getDBO();
$document = JFactory::getDocument();

$cssFile = JURI::base(true).'/components/com_creativecontactform/assets/css/creative-ui-options.css';
$document->addStyleSheet($cssFile, 'text/css', null, array());

$cssFile = JURI::base(true).'/components/com_creativecontactform/assets/css/options_styles.css';
$document->addStyleSheet($cssFile, 'text/css', null, array());

$jsFile = JURI::base(true).'/components/com_creativecontactform/assets/js/creativelib-options.js';
$document->addScript($jsFile);

$jsFile = JURI::base(true).'/components/com_creativecontactform/assets/js/creativelib-ui-options.js';
$document->addScript($jsFile);

$jsFile = JURI::base(true).'/components/com_creativecontactform/assets/js/options_functions.js';
$document->addScript($jsFile);
?>
<script type="text/javascript">
Joomla.submitbutton = function(task) {
	var form = document.adminForm;
	if (task == 'creativefield.cancel') {
		submitform( task );
	}
   	else if (task == 'creativefield.save2copy') {
		alert('Please Upgrade to Commercial Version to use this feature!');
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
			var id_type = document.getElementById("jform_id_type").value;
			var id_column_type = document.getElementById("jform_column_type").value;
			if(id_type == '13' || id_type == '14' || id_type == '15' || id_type == '16' || id_type == '17' || id_type == '18' || id_type == '19' || id_type == '20' || id_type == '21' || id_type == '22')
				alert('Please Upgrade to Commercial Version to use this field type!');
			else if(id_column_type == '1' || id_column_type == '2')
				alert('Please Upgrade to Commercial Version to use left/right Columns Wrapping!');
			else
				submitform( task );
		}
	}
}

</script>
<?php if(JV == 'j2') {//////////////////////////////////////////////////////////////////////////////////////Joomla2.x/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////?>
<form action="<?php echo JRoute::_('index.php?option=com_creativecontactform&layout=edit&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="adminForm">
	<?php if(($this->max_id < 5) || ($this->item->id != 0)) {?>
	<fieldset class="adminform" style="border-bottom: none !important;">
		<legend><?php echo JText::_( 'Details' ); ?></legend>
		<ul class="adminformlist">
		<?php 
		$k = 1;
		foreach($this->form->getFieldset() as $field) {
			if($this->item->id_type== '' && ($k == 3 || $k == 7)) {//add option, hide tooltip, hide required
				$k++;
				continue;	
			}
			if($this->item->id_type== '' && $k == 9) {//add option
				break;	
			}

			// text field types
			$field_types_array = array(1,2,3,4,5,6,7,8);
			$field_type_values = array(1,2,3,4,5,6,7,8,9,10);
			if(in_array($this->item->id_type,$field_types_array) && !in_array($k,$field_type_values)){
				$k++;
				continue;
			}

			//select, m-select
			$field_types_array = array(9,10);
			$field_type_values = array(1,2,4,5,6,7,8,9,10,11,12,13,14,15,16);
			if(in_array($this->item->id_type,$field_types_array) && !in_array($k,$field_type_values)){
				$k++;
				continue;
			}
			//radio, checkbox
			$field_types_array = array(11,12);
			$field_type_values = array(1,2,4,5,6,7,8,9,10,11,12);
			if(in_array($this->item->id_type,$field_types_array) && !in_array($k,$field_type_values)){
				$k++;
				continue;
			}

			//captcha
			$field_types_array = array(13);
			$field_type_values = array(1,2,4,5,6,8,9,10,24);
			if(in_array($this->item->id_type,$field_types_array) && !in_array($k,$field_type_values)){
				$k++;
				continue;
			}

			//file upload
			$field_types_array = array(14);
			$field_type_values = array(1,2,4,5,6,7,8,9,10,12,17,18,19,20,21,22,23);
			if(in_array($this->item->id_type,$field_types_array) && !in_array($k,$field_type_values)){
				$k++;
				continue;
			}

			//datepicker
			$field_types_array = array(15);
			$field_type_values = array(1,2,4,5,6,7,8,9,10,25,26,29,30,31,32,33,34,35);
			if(in_array($this->item->id_type,$field_types_array) && !in_array($k,$field_type_values)){
				$k++;
				continue;
			}

			//custom html
			$field_types_array = array(16);
			$field_type_values = array(1,2,4,5,6,7,8,9,10,12,36);
			if(in_array($this->item->id_type,$field_types_array) && !in_array($k,$field_type_values)){
				$k++;
				continue;
			}

			//Heading
			$field_types_array = array(17);
			$field_type_values = array(1,2,4,5,6,8,9,10,37);
			if(in_array($this->item->id_type,$field_types_array) && !in_array($k,$field_type_values)){
				$k++;
				continue;
			}

			//google maps
			$field_types_array = array(18);
			$field_type_values = array(1,2,4,5,6,7,8,9,10,12,38);
			if(in_array($this->item->id_type,$field_types_array) && !in_array($k,$field_type_values)){
				$k++;
				continue;
			}

			//google reCAPTCHA
			$field_types_array = array(19);
			$field_type_values = array(1,2,4,5,6,8,9,10,39,40,42,43);
			if(in_array($this->item->id_type,$field_types_array) && !in_array($k,$field_type_values)){
				$k++;
				continue;
			}

			//contact data
			$field_types_array = array(20);
			$field_type_values = array(1,2,4,5,6,8,9,10,12,44,45);
			if(in_array($this->item->id_type,$field_types_array) && !in_array($k,$field_type_values)){
				$k++;
				continue;
			}

			//creative popup
			$field_types_array = array(21);
			$field_type_values = array(1,2,4,5,8,46,47);
			if(in_array($this->item->id_type,$field_types_array) && !in_array($k,$field_type_values)){
				$k++;
				continue;
			}



			if($k == 11 && in_array($this->item->id_type,array(9,10,11,12))) {
				echo '<h3 style="margin-bottom: 15px;">Option parameters</h3>';
			}								
			if($k == 39 && in_array($this->item->id_type,array(19))) {
				echo '<h3 style="margin-bottom: 15px;">Google reCAPCTHA parameters. <a style="font-size: 16px;font-style: italic;" href="https://www.google.com/recaptcha/intro/index.html" target="_blank">Get reCAPTCHA</a>
					<span style="font-size: 14px;font-style: italic;display: block;margin-top: 10px;font-weight: normal;"><a href="http://creative-solutions.net/joomla/creative-contact-form/documentation?section=google-recaptcha" target="_blank">Read more in documentation!</a></span>
					</h3>';
			}
			if($k == 38 && in_array($this->item->id_type,array(18))) {
				echo '<h3 style="margin-bottom: 15px;">Google Maps parameters. <a style="font-size: 16px;font-style: italic;" href="https://www.google.com/maps" target="_blank">Create a basic map</a> or <a style="font-size: 16px;font-style: italic;" href="https://www.google.com/maps/d/?pli=1" target="_blank">Go to new maps.</a>
					<span style="font-size: 14px;font-style: italic;display: block;margin-top: 10px;font-weight: normal;"><a href="http://creative-solutions.net/joomla/creative-contact-form/documentation?section=google-maps" target="_blank">Read more in documentation!</a></span>
					</h3>';
			}
			if($k == 44 && in_array($this->item->id_type,array(20))) {
				echo '<div style="margin-top: 10px;font-size: 18px;font-weight: bold;">Contact Data:</div>
						<div style="margin-top: 10px;margin-bottom: 20px;">To add a section, use the following structure:<br />
							<span style="font-weight: bold;font-style: italic;margin-top: 5px;display: inline-block;">
								{section icon="<span style="color: rgb(0, 24, 192)">section_icon</span>" label="<span style="color: rgb(0, 24, 192)">section_label</span>"}<span style="color: rgb(215, 0, 0);">section_content</span>{/section}
							</span>
							<span style="display: block;margin-top: 5px;">
								<span style="color: rgb(155, 36, 36);font-weight: bold">Icon</span>: The icon of section. Possible values: <span style="font-style: italic;text-decoration: underline;font-weight: bold">address</span>, <span style="font-style: italic;text-decoration: underline;font-weight: bold">phone</span>, <span style="font-style: italic;text-decoration: underline;font-weight: bold">mobile</span>, <span style="font-style: italic;text-decoration: underline;font-weight: bold">fax</span>, <span style="font-style: italic;text-decoration: underline;font-weight: bold">email</span>, <span style="font-style: italic;text-decoration: underline;font-weight: bold">link</span>, <span style="font-style: italic;text-decoration: underline;font-weight: bold">tip</span>, <span style="font-style: italic;text-decoration: underline;font-weight: bold">info</span>, <span style="font-style: italic;text-decoration: underline;font-weight: bold">question</span>, <span style="font-style: italic;text-decoration: underline;font-weight: bold">map</span>.
							</span>
							<span style="display: block;margin-top: 2px;">
								<span style="color: rgb(155, 36, 36);font-weight: bold">Label</span>: The label of section. Width of section label can be configured through <u>Label Width</u> option.
							</span>
							<span style="display: block;margin-top: 2px;">
								<span style="color: rgb(155, 36, 36);font-weight: bold">Section Content</span>: The content of section. HTML is allowed! To apply number styling, use {num}{/num} structure. 
							</span>
							<span style="display: block;margin-top: 2px;">
								<span style="color: rgb(155, 36, 36);font-weight: bold">Example:</span>
								<span style="font-weight: bold;font-style: italic;">
									{section icon="<span style="color: rgb(0, 24, 192)">phone</span>" label="<span style="color: rgb(0, 24, 192)">Phone:</span>"}<span style="color: rgb(215, 0, 0);">+00 (0) 123 456 789</span>{/section}
								</span>
							</span>
							<span style="font-size: 14px;font-style: italic;display: block;margin-top: 10px;"><a href="http://creative-solutions.net/joomla/creative-contact-form/documentation?section=contact-data" target="_blank">Read more in documentation!</a></span>
						</div>
						';
			}
			if($k == 46 && in_array($this->item->id_type,array(21))) {
				echo '<div style="margin-top: 10px;font-size: 18px;font-weight: bold;">Creative Popup:</div>
						<div style="margin-top: 10px;margin-bottom: 20px;">To use this popup, insert the following code where you need to add a popup link:<br />
							<span style="font-weight: bold;font-style: italic;margin-top: 5px;display: inline-block;">
								{creative_popup id="<span style="color: rgb(215, 0, 0);">'.$this->item->id.'</span>" size="800X600"}<span style="color: rgb(0, 24, 192)">Popup Link Text</span>{/creative_popup}
							</span>
							<span style="display: block;margin-top: 7px;">
								<span style="font-weight: normal">To use template heading use this code:<br /></span>
								<span style="font-weight: bold;font-style: italic;">
									{heading}<span style="color: rgb(0, 24, 192);">heading text...</span>{/heading}
								</span>
							</span>
							<span style="display: block;margin-top: 7px;">
								<span style="font-weight: normal">To render sections use this code:<br /></span>
								<span style="font-weight: bold;font-style: italic;">
									{section icon="<span style="color: rgb(215, 0, 0)">info</span>" label="<span style="color: rgb(215, 0, 0)">Info:</span>"}<span style="color: rgb(0, 24, 192);">content...</span>{/section}
								</span>
							</span>
							<span style="display:block;margin-top:5px;color:#777;font-size: 11px;"><b>Note:</b> Place popup fields at the end of form!</span>
							<span style="font-size: 14px;font-style: italic;display: block;margin-top: 10px;"><a href="http://creative-solutions.net/joomla/creative-contact-form/documentation?section=creative-popup" target="_blank">Read more in documentation!</a></span>
						</div>
						';
			}


			$controls_css = $k == 36 || $k == 44 || $k == 46 ? 'style="width: 444px"' : '';
			?>
			<div class="control-label ccf_<?php echo $k;?>">
				<?php echo $field->label;?>
				<?php if($k == 27) echo '<a style="float: left;margin-top: 7px;clear:both;" href="http://creative-solutions.net/joomla/creative-contact-form/documentation#datepicker_styles" target="_blank">See Templates Demo</a>'; ?>
				<?php if($k == 28) echo '<a style="float: left;margin-top: 7px;clear:both;" href="http://creative-solutions.net/joomla/creative-contact-form/documentation#datepicker_icons" target="_blank">See Icons Demo</a>'; ?>
			</div>
			<div class="controls" <?php echo $controls_css?>><?php echo $field->input;?></div>
			<div style="clear: both;height: 8px;">&nbsp;</div>
			<?php 
			 $k ++;
		} ?>
		</ul>
			<?php } else { ?>
				<div style="color: rgb(235, 9, 9);font-size: 16px;font-weight: bold;">Please Upgrade to Commercial Version to have more than 5 fields.</div>
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
		<input type="hidden" name="task" value="creativefield.add" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</fieldset>
</form>
	
	<?php 
//options
if(in_array($this->item->id_type,array(9,10,11,12))) {
echo '<div style="border-top: none !important;margin: -11px 9px 0 10px;padding-left: 5px;border: 1px #ccc solid;">';
	if(isset($_GET['load_countries'])) {
		$query = "
		SELECT
		spo.name,
		spo.id,
		spo.ordering,
		spo.showrow
		FROM
		`#__creative_form_options` spo
		WHERE spo.id_parent = '".$this->item->id."'";
		$db->setQuery($query);
		$childs_array_current = $db->loadAssocList();
		
		if (sizeof($childs_array_current) == 0) {
			$query = 
					"
						INSERT INTO `#__creative_form_options` (`id`, `id_parent`, `name`, `value`, `ordering`, `showrow`, `selected`) VALUES 
							(NULL, '".$this->item->id."', 'Afghanistan', 'Afghanistan', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Albania', 'Albania', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Algeria', 'Algeria', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'American Samoa', 'American Samoa', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Andorra', 'Andorra', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Angola', 'Angola', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Anguilla', 'Anguilla', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Antarctica', 'Antarctica', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Antigua and Barbuda', 'Antigua and Barbuda', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Argentina', 'Argentina', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Armenia', 'Armenia', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Aruba', 'Aruba', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Australia', 'Australia', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Austria', 'Austria', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Azerbaijan', 'Azerbaijan', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Bahamas', 'Bahamas', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Bahrain', 'Bahrain', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Bangladesh', 'Bangladesh', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Barbados', 'Barbados', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Belarus', 'Belarus', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Belgium', 'Belgium', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Belize', 'Belize', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Benin', 'Benin', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Bermuda', 'Bermuda', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Bhutan', 'Bhutan', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Bolivia', 'Bolivia', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Bosnia and Herzegowina', 'Bosnia and Herzegowina', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Botswana', 'Botswana', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Bouvet Island', 'Bouvet Island', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Brazil', 'Brazil', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'British Indian Ocean Territory', 'British Indian Ocean Territory', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Brunei Darussalam', 'Brunei Darussalam', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Bulgaria', 'Bulgaria', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Burkina Faso', 'Burkina Faso', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Burundi', 'Burundi', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Cambodia', 'Cambodia', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Cameroon', 'Cameroon', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Canada', 'Canada', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Cape Verde', 'Cape Verde', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Cayman Islands', 'Cayman Islands', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Central African Republic', 'Central African Republic', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Chad', 'Chad', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Chile', 'Chile', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'China', 'China', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Christmas Island', 'Christmas Island', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Cocos (Keeling) Islands', 'Cocos (Keeling) Islands', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Colombia', 'Colombia', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Comoros', 'Comoros', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Congo', 'Congo', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Cook Islands', 'Cook Islands', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Costa Rica', 'Costa Rica', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Cote D\'Ivoire', 'Cote DIvoire', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Croatia', 'Croatia', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Cuba', 'Cuba', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Cyprus', 'Cyprus', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Czech Republic', 'Czech Republic', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Democratic Republic of Congo', 'Democratic Republic of Congo', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Denmark', 'Denmark', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Djibouti', 'Djibouti', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Dominica', 'Dominica', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Dominican Republic', 'Dominican Republic', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'East Timor', 'East Timor', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Ecuador', 'Ecuador', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Egypt', 'Egypt', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'El Salvador', 'El Salvador', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Equatorial Guinea', 'Equatorial Guinea', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Eritrea', 'Eritrea', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Estonia', 'Estonia', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Ethiopia', 'Ethiopia', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Falkland Islands (Malvinas)', 'Falkland Islands (Malvinas)', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Faroe Islands', 'Faroe Islands', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Fiji', 'Fiji', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Finland', 'Finland', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'France', 'France', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'France, Metropolitan', 'France, Metropolitan', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'French Guiana', 'French Guiana', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'French Polynesia', 'French Polynesia', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'French Southern Territories', 'French Southern Territories', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Gabon', 'Gabon', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Gambia', 'Gambia', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Georgia', 'Georgia', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Germany', 'Germany', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Ghana', 'Ghana', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Gibraltar', 'Gibraltar', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Greece', 'Greece', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Greenland', 'Greenland', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Grenada', 'Grenada', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Guadeloupe', 'Guadeloupe', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Guam', 'Guam', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Guatemala', 'Guatemala', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Guinea', 'Guinea', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Guinea-bissau', 'Guinea-bissau', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Guyana', 'Guyana', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Haiti', 'Haiti', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Heard and Mc Donald Islands', 'Heard and Mc Donald Islands', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Honduras', 'Honduras', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Hong Kong', 'Hong Kong', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Hungary', 'Hungary', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Iceland', 'Iceland', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'India', 'India', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Indonesia', 'Indonesia', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Iran', 'Iran', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Iraq', 'Iraq', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Ireland', 'Ireland', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Israel', 'Israel', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Italy', 'Italy', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Jamaica', 'Jamaica', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Japan', 'Japan', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Jordan', 'Jordan', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Kazakhstan', 'Kazakhstan', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Kenya', 'Kenya', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Kiribati', 'Kiribati', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Korea', 'Korea', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Kuwait', 'Kuwait', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Kyrgyzstan', 'Kyrgyzstan', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Lao People\'s Democratic Republic', 'Lao Peoples Democratic Republic', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Latvia', 'Latvia', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Lebanon', 'Lebanon', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Lesotho', 'Lesotho', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Liberia', 'Liberia', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Libyan Arab Jamahiriya', 'Libyan Arab Jamahiriya', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Liechtenstein', 'Liechtenstein', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Lithuania', 'Lithuania', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Luxembourg', 'Luxembourg', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Macau', 'Macau', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Macedonia', 'Macedonia', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Madagascar', 'Madagascar', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Malawi', 'Malawi', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Malaysia', 'Malaysia', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Maldives', 'Maldives', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Mali', 'Mali', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Malta', 'Malta', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Marshall Islands', 'Marshall Islands', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Martinique', 'Martinique', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Mauritania', 'Mauritania', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Mauritius', 'Mauritius', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Mayotte', 'Mayotte', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Mexico', 'Mexico', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Micronesia, Federated States of', 'Micronesia, Federated States of', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Moldova', 'Moldova', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Monaco', 'Monaco', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Mongolia', 'Mongolia', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Montserrat', 'Montserrat', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Morocco', 'Morocco', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Mozambique', 'Mozambique', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Myanmar', 'Myanmar', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Namibia', 'Namibia', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Nauru', 'Nauru', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Nepal', 'Nepal', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Netherlands', 'Netherlands', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Netherlands Antilles', 'Netherlands Antilles', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'New Caledonia', 'New Caledonia', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'New Zealand', 'New Zealand', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Nicaragua', 'Nicaragua', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Niger', 'Niger', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Nigeria', 'Nigeria', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Niue', 'Niue', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Norfolk Island', 'Norfolk Island', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'North Korea', 'North Korea', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Northern Mariana Islands', 'Northern Mariana Islands', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Norway', 'Norway', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Oman', 'Oman', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Pakistan', 'Pakistan', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Palau', 'Palau', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Panama', 'Panama', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Papua New Guinea', 'Papua New Guinea', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Paraguay', 'Paraguay', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Peru', 'Peru', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Philippines', 'Philippines', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Pitcairn', 'Pitcairn', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Poland', 'Poland', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Portugal', 'Portugal', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Puerto Rico', 'Puerto Rico', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Qatar', 'Qatar', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Reunion', 'Reunion', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Romania', 'Romania', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Russian Federation', 'Russian Federation', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Rwanda', 'Rwanda', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Saint Kitts and Nevis', 'Saint Kitts and Nevis', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Saint Lucia', 'Saint Lucia', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Saint Vincent and the Grenadines', 'Saint Vincent and the Grenadines', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Samoa', 'Samoa', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'San Marino', 'San Marino', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Sao Tome and Principe', 'Sao Tome and Principe', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Saudi Arabia', 'Saudi Arabia', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Senegal', 'Senegal', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Seychelles', 'Seychelles', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Sierra Leone', 'Sierra Leone', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Singapore', 'Singapore', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Slovak Republic', 'Slovak Republic', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Slovenia', 'Slovenia', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Solomon Islands', 'Solomon Islands', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Somalia', 'Somalia', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'South Africa', 'South Africa', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'South Georgia &amp; South Sandwich Islands', 'South Georgia & South Sandwich Islands', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Spain', 'Spain', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Sri Lanka', 'Sri Lanka', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'St. Helena', 'St. Helena', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'St. Pierre and Miquelon', 'St. Pierre and Miquelon', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Sudan', 'Sudan', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Suriname', 'Suriname', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Svalbard and Jan Mayen Islands', 'Svalbard and Jan Mayen Islands', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Swaziland', 'Swaziland', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Sweden', 'Sweden', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Switzerland', 'Switzerland', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Syrian Arab Republic', 'Syrian Arab Republic', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Taiwan', 'Taiwan', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Tajikistan', 'Tajikistan', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Tanzania', 'Tanzania', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Thailand', 'Thailand', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Togo', 'Togo', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Tokelau', 'Tokelau', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Tonga', 'Tonga', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Trinidad and Tobago', 'Trinidad and Tobago', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Tunisia', 'Tunisia', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Turkey', 'Turkey', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Turkmenistan', 'Turkmenistan', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Turks and Caicos Islands', 'Turks and Caicos Islands', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Tuvalu', 'Tuvalu', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Uganda', 'Uganda', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Ukraine', 'Ukraine', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'United Arab Emirates', 'United Arab Emirates', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'United Kingdom', 'United Kingdom', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'United States', 'United States', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'United States Minor Outlying Islands', 'United States Minor Outlying Islands', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Uruguay', 'Uruguay', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Uzbekistan', 'Uzbekistan', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Vanuatu', 'Vanuatu', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Vatican City State (Holy See)', 'Vatican City State (Holy See)', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Venezuela', 'Venezuela', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Viet Nam', 'Viet Nam', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Virgin Islands (British)', 'Virgin Islands (British)', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Virgin Islands (U.S.)', 'Virgin Islands (U.S.)', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Wallis and Futuna Islands', 'Wallis and Futuna Islands', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Western Sahara', 'Western Sahara', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Yemen', 'Yemen', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Yugoslavia', 'Yugoslavia', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Zambia', 'Zambia', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Zimbabwe', 'Zimbabwe', '0', '1', '0')
					";
			$db->setQuery($query);
			$db->query();
		}
			
	}
	$query = "
				SELECT
					spo.name,
					spo.id,
					spo.ordering,
					spo.showrow
				FROM
					`#__creative_form_options` spo
				WHERE spo.id_parent = '".$this->item->id."'
				ORDER BY ";
	if($this->item->ordering_field == 0)
		$query .= "spo.ordering";
	else
		$query .= "spo.name";
	$db->setQuery($query);
	$childs_array = $db->loadAssocList();
	
	echo '<H3 style="margin-top: 0px;">Options</H3>';
	
	echo '<div class="options_wrapper">';
	
		echo '<div class="menus_header">';
			echo  '<img src="components/com_creativecontactform/assets/images/new_page.gif" class="new_submenu_img" title="New Option" menu_id="'.$this->item->id.'" />';
			if (sizeof($childs_array) == 0) {
				echo  '<img src="components/com_creativecontactform/assets/images/country.png" class="load_countries_data" title="Load countries data? (239 countries)" />';
			}
		echo '</div>';
		
		$disabled_ordering = $this->item->ordering_field == 1 ? 'disabledordering' : '';

		
		
		
		echo '<div class="menu_tree">';
		echo '<ul id="sortable_menu">';
		if (sizeof($childs_array) > 0)
		{
			foreach ($childs_array as $key => $value)
			{
				$show = $value['showrow'];
				$class = " ui-state-default text";
				$show_class = $show == 1 ? 'hide' : 'show';
				$show_title = $show == 0 ? 'Publish option' : 'Unpublish option';
				
				echo '<li class="'.$class.'" id="option_li_'.$value["id"].'">';
					echo '<div class="option_item" id="option_'.$value["id"].'">'.$value["name"].'</div>';
					echo '<div class="menu_moove '.$disabled_ordering.'" title="Move option" >&nbsp;</div>';
					echo '<div id="edit_'.$value["id"].'" menu_id="'.$value["id"].'" class="edit" title="Edit option" >&nbsp;</div>';
					echo '<div id="showrow_'.$value["id"].'" menu_id="'.$value["id"].'" class="'.$show_class.'" title="'.$show_title.'" >&nbsp;</div>';
					echo '<div id="remove_'.$value["id"].'" menu_id="'.$value["id"].'" class="delete" title="Delete option" >&nbsp;</div>';
				echo '</li>';
			}
		}
		echo '</div>';
		echo '</ul>';
	echo '</div>';
echo '</div>';
}
?>
<div id="edit_menu_data" style="display: none;">
	<div id="ajax_loader">&nbsp;</div>
	<div id="dialog_inner_wrapper"></div>
	<input type="hidden" value="" id="menu_id" />
</div>
	
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
			<?php if(($this->max_id < 5) || ($this->item->id != 0)) {?>
			<fieldset>
				<div class="tab-content">
					<div class="tab-pane active" id="details">
						<div class="control-group">
							<?php 
							$k = 1;
							foreach($this->form->getFieldset() as $field) {
								if($this->item->id_type== '' && ($k == 3 || $k == 7)) {//add option, hide tooltip, hide required
									$k++;
									continue;	
								}
								if($this->item->id_type== '' && $k == 9) {//add option
									break;	
								}

								// text field types
								$field_types_array = array(1,2,3,4,5,6,7,8);
								$field_type_values = array(1,2,3,4,5,6,7,8,9,10);
								if(in_array($this->item->id_type,$field_types_array) && !in_array($k,$field_type_values)){
									$k++;
									continue;
								}

								//select, m-select
								$field_types_array = array(9,10);
								$field_type_values = array(1,2,4,5,6,7,8,9,10,11,12,13,14,15,16);
								if(in_array($this->item->id_type,$field_types_array) && !in_array($k,$field_type_values)){
									$k++;
									continue;
								}
								//radio, checkbox
								$field_types_array = array(11,12);
								$field_type_values = array(1,2,4,5,6,7,8,9,10,11,12);
								if(in_array($this->item->id_type,$field_types_array) && !in_array($k,$field_type_values)){
									$k++;
									continue;
								}

								//captcha
								$field_types_array = array(13);
								$field_type_values = array(1,2,4,5,6,8,9,10,24);
								if(in_array($this->item->id_type,$field_types_array) && !in_array($k,$field_type_values)){
									$k++;
									continue;
								}

								//file upload
								$field_types_array = array(14);
								$field_type_values = array(1,2,4,5,6,7,8,9,10,12,17,18,19,20,21,22,23);
								if(in_array($this->item->id_type,$field_types_array) && !in_array($k,$field_type_values)){
									$k++;
									continue;
								}

								//datepicker
								$field_types_array = array(15);
								$field_type_values = array(1,2,4,5,6,7,8,9,10,25,26,29,30,31,32,33,34,35);
								if(in_array($this->item->id_type,$field_types_array) && !in_array($k,$field_type_values)){
									$k++;
									continue;
								}

								//custom html
								$field_types_array = array(16);
								$field_type_values = array(1,2,4,5,6,7,8,9,10,12,36);
								if(in_array($this->item->id_type,$field_types_array) && !in_array($k,$field_type_values)){
									$k++;
									continue;
								}

								//Heading
								$field_types_array = array(17);
								$field_type_values = array(1,2,4,5,6,8,9,10,37);
								if(in_array($this->item->id_type,$field_types_array) && !in_array($k,$field_type_values)){
									$k++;
									continue;
								}

								//google maps
								$field_types_array = array(18);
								$field_type_values = array(1,2,4,5,6,7,8,9,10,12,38);
								if(in_array($this->item->id_type,$field_types_array) && !in_array($k,$field_type_values)){
									$k++;
									continue;
								}

								//google reCAPTCHA
								$field_types_array = array(19);
								$field_type_values = array(1,2,4,5,6,8,9,10,39,40,42,43);
								if(in_array($this->item->id_type,$field_types_array) && !in_array($k,$field_type_values)){
									$k++;
									continue;
								}

								//contact data
								$field_types_array = array(20);
								$field_type_values = array(1,2,4,5,6,8,9,10,12,44,45);
								if(in_array($this->item->id_type,$field_types_array) && !in_array($k,$field_type_values)){
									$k++;
									continue;
								}

								//creative popup
								$field_types_array = array(21);
								$field_type_values = array(1,2,4,5,8,46,47);
								if(in_array($this->item->id_type,$field_types_array) && !in_array($k,$field_type_values)){
									$k++;
									continue;
								}



								if($k == 11 && in_array($this->item->id_type,array(9,10,11,12))) {
									echo '<h3 style="margin-bottom: 15px;">Option parameters</h3>';
								}								
								if($k == 39 && in_array($this->item->id_type,array(19))) {
									echo '<h3 style="margin-bottom: 15px;">Google reCAPCTHA parameters. <a style="font-size: 16px;font-style: italic;" href="https://www.google.com/recaptcha/intro/index.html" target="_blank">Get reCAPTCHA</a>
										<span style="font-size: 14px;font-style: italic;display: block;margin-top: 10px;font-weight: normal;"><a href="http://creative-solutions.net/joomla/creative-contact-form/documentation?section=google-recaptcha" target="_blank">Read more in documentation!</a></span>
										</h3>';
								}
								if($k == 38 && in_array($this->item->id_type,array(18))) {
									echo '<h3 style="margin-bottom: 15px;">Google Maps parameters. <a style="font-size: 16px;font-style: italic;" href="https://www.google.com/maps" target="_blank">Create a basic map</a> or <a style="font-size: 16px;font-style: italic;" href="https://www.google.com/maps/d/?pli=1" target="_blank">Go to new maps.</a>
										<span style="font-size: 14px;font-style: italic;display: block;margin-top: 10px;font-weight: normal;"><a href="http://creative-solutions.net/joomla/creative-contact-form/documentation?section=google-maps" target="_blank">Read more in documentation!</a></span>
										</h3>';
								}
								if($k == 44 && in_array($this->item->id_type,array(20))) {
									echo '<div style="margin-top: 10px;font-size: 18px;font-weight: bold;">Contact Data:</div>
											<div style="margin-top: 10px;margin-bottom: 20px;">To add a section, use the following structure:<br />
												<span style="font-weight: bold;font-style: italic;margin-top: 5px;display: inline-block;">
													{section icon="<span style="color: rgb(0, 24, 192)">section_icon</span>" label="<span style="color: rgb(0, 24, 192)">section_label</span>"}<span style="color: rgb(215, 0, 0);">section_content</span>{/section}
												</span>
												<span style="display: block;margin-top: 5px;">
													<span style="color: rgb(155, 36, 36);font-weight: bold">Icon</span>: The icon of section. Possible values: <span style="font-style: italic;text-decoration: underline;font-weight: bold">address</span>, <span style="font-style: italic;text-decoration: underline;font-weight: bold">phone</span>, <span style="font-style: italic;text-decoration: underline;font-weight: bold">mobile</span>, <span style="font-style: italic;text-decoration: underline;font-weight: bold">fax</span>, <span style="font-style: italic;text-decoration: underline;font-weight: bold">email</span>, <span style="font-style: italic;text-decoration: underline;font-weight: bold">link</span>, <span style="font-style: italic;text-decoration: underline;font-weight: bold">tip</span>, <span style="font-style: italic;text-decoration: underline;font-weight: bold">info</span>, <span style="font-style: italic;text-decoration: underline;font-weight: bold">question</span>, <span style="font-style: italic;text-decoration: underline;font-weight: bold">map</span>.
												</span>
												<span style="display: block;margin-top: 2px;">
													<span style="color: rgb(155, 36, 36);font-weight: bold">Label</span>: The label of section. Width of section label can be configured through <u>Label Width</u> option.
												</span>
												<span style="display: block;margin-top: 2px;">
													<span style="color: rgb(155, 36, 36);font-weight: bold">Section Content</span>: The content of section. HTML is allowed! To apply number styling, use {num}{/num} structure. 
												</span>
												<span style="display: block;margin-top: 2px;">
													<span style="color: rgb(155, 36, 36);font-weight: bold">Example:</span>
													<span style="font-weight: bold;font-style: italic;">
														{section icon="<span style="color: rgb(0, 24, 192)">phone</span>" label="<span style="color: rgb(0, 24, 192)">Phone:</span>"}<span style="color: rgb(215, 0, 0);">+00 (0) 123 456 789</span>{/section}
													</span>
												</span>
												<span style="font-size: 14px;font-style: italic;display: block;margin-top: 10px;"><a href="http://creative-solutions.net/joomla/creative-contact-form/documentation?section=contact-data" target="_blank">Read more in documentation!</a></span>
											</div>
											';
								}
								if($k == 46 && in_array($this->item->id_type,array(21))) {
									echo '<div style="margin-top: 10px;font-size: 18px;font-weight: bold;">Creative Popup:</div>
											<div style="margin-top: 10px;margin-bottom: 20px;">To use this popup, insert the following code where you need to add a popup link:<br />
												<span style="font-weight: bold;font-style: italic;margin-top: 5px;display: inline-block;">
													{creative_popup id="<span style="color: rgb(215, 0, 0);">'.$this->item->id.'</span>" size="800X600"}<span style="color: rgb(0, 24, 192)">Popup Link Text</span>{/creative_popup}
												</span>
												<span style="display: block;margin-top: 7px;">
													<span style="font-weight: normal">To use template heading use this code:<br /></span>
													<span style="font-weight: bold;font-style: italic;">
														{heading}<span style="color: rgb(0, 24, 192);">heading text...</span>{/heading}
													</span>
												</span>
												<span style="display: block;margin-top: 7px;">
													<span style="font-weight: normal">To render sections use this code:<br /></span>
													<span style="font-weight: bold;font-style: italic;">
														{section icon="<span style="color: rgb(215, 0, 0)">info</span>" label="<span style="color: rgb(215, 0, 0)">Info:</span>"}<span style="color: rgb(0, 24, 192);">content...</span>{/section}
													</span>
												</span>
												<span style="display:block;margin-top:5px;color:#777;font-size: 11px;"><b>Note:</b> Place popup fields at the end of form!</span>
												<span style="font-size: 14px;font-style: italic;display: block;margin-top: 10px;"><a href="http://creative-solutions.net/joomla/creative-contact-form/documentation?section=creative-popup" target="_blank">Read more in documentation!</a></span>
											</div>
											';
								}


								$controls_css = $k == 36 || $k == 44 || $k == 46 ? 'style="width: 444px"' : '';
								?>
								<div class="control-label ccf_<?php echo $k;?>">
									<?php echo $field->label;?>
									<?php if($k == 27) echo '<a style="float: left;margin-top: 7px;clear:both;" href="http://creative-solutions.net/joomla/creative-contact-form/documentation#datepicker_styles" target="_blank">See Templates Demo</a>'; ?>
									<?php if($k == 28) echo '<a style="float: left;margin-top: 7px;clear:both;" href="http://creative-solutions.net/joomla/creative-contact-form/documentation#datepicker_icons" target="_blank">See Icons Demo</a>'; ?>
								</div>
								<div class="controls" <?php echo $controls_css?>><?php echo $field->input;?></div>
								<div style="clear: both;height: 8px;">&nbsp;</div>
								<?php 
								 $k ++;
							} ?>
							<?php 
								
							?>
						</div>
					</div>
				</div>
			</fieldset>
			<?php } else { ?>
				<div style="color: rgb(235, 9, 9);font-size: 16px;font-weight: bold;">Please Upgrade to Commercial Version to have more than 5 fields.</div>
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
<input type="hidden" name="task" value="creativefield.add" />
<?php echo JHtml::_('form.token'); ?>
</form>
<?php 
//options
if(in_array($this->item->id_type,array(9,10,11,12))) {
	if(isset($_GET['load_countries'])) {
		$query = "
		SELECT
		spo.name,
		spo.id,
		spo.ordering,
		spo.showrow
		FROM
		`#__creative_form_options` spo
		WHERE spo.id_parent = '".$this->item->id."'";
		$db->setQuery($query);
		$childs_array_current = $db->loadAssocList();
		
		if (sizeof($childs_array_current) == 0) {
			$query = 
					"
						INSERT INTO `#__creative_form_options` (`id`, `id_parent`, `name`, `value`, `ordering`, `showrow`, `selected`) VALUES 
							(NULL, '".$this->item->id."', 'Afghanistan', 'Afghanistan', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Albania', 'Albania', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Algeria', 'Algeria', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'American Samoa', 'American Samoa', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Andorra', 'Andorra', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Angola', 'Angola', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Anguilla', 'Anguilla', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Antarctica', 'Antarctica', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Antigua and Barbuda', 'Antigua and Barbuda', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Argentina', 'Argentina', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Armenia', 'Armenia', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Aruba', 'Aruba', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Australia', 'Australia', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Austria', 'Austria', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Azerbaijan', 'Azerbaijan', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Bahamas', 'Bahamas', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Bahrain', 'Bahrain', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Bangladesh', 'Bangladesh', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Barbados', 'Barbados', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Belarus', 'Belarus', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Belgium', 'Belgium', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Belize', 'Belize', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Benin', 'Benin', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Bermuda', 'Bermuda', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Bhutan', 'Bhutan', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Bolivia', 'Bolivia', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Bosnia and Herzegowina', 'Bosnia and Herzegowina', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Botswana', 'Botswana', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Bouvet Island', 'Bouvet Island', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Brazil', 'Brazil', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'British Indian Ocean Territory', 'British Indian Ocean Territory', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Brunei Darussalam', 'Brunei Darussalam', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Bulgaria', 'Bulgaria', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Burkina Faso', 'Burkina Faso', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Burundi', 'Burundi', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Cambodia', 'Cambodia', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Cameroon', 'Cameroon', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Canada', 'Canada', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Cape Verde', 'Cape Verde', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Cayman Islands', 'Cayman Islands', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Central African Republic', 'Central African Republic', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Chad', 'Chad', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Chile', 'Chile', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'China', 'China', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Christmas Island', 'Christmas Island', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Cocos (Keeling) Islands', 'Cocos (Keeling) Islands', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Colombia', 'Colombia', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Comoros', 'Comoros', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Congo', 'Congo', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Cook Islands', 'Cook Islands', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Costa Rica', 'Costa Rica', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Cote D\'Ivoire', 'Cote DIvoire', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Croatia', 'Croatia', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Cuba', 'Cuba', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Cyprus', 'Cyprus', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Czech Republic', 'Czech Republic', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Democratic Republic of Congo', 'Democratic Republic of Congo', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Denmark', 'Denmark', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Djibouti', 'Djibouti', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Dominica', 'Dominica', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Dominican Republic', 'Dominican Republic', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'East Timor', 'East Timor', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Ecuador', 'Ecuador', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Egypt', 'Egypt', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'El Salvador', 'El Salvador', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Equatorial Guinea', 'Equatorial Guinea', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Eritrea', 'Eritrea', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Estonia', 'Estonia', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Ethiopia', 'Ethiopia', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Falkland Islands (Malvinas)', 'Falkland Islands (Malvinas)', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Faroe Islands', 'Faroe Islands', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Fiji', 'Fiji', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Finland', 'Finland', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'France', 'France', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'France, Metropolitan', 'France, Metropolitan', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'French Guiana', 'French Guiana', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'French Polynesia', 'French Polynesia', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'French Southern Territories', 'French Southern Territories', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Gabon', 'Gabon', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Gambia', 'Gambia', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Georgia', 'Georgia', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Germany', 'Germany', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Ghana', 'Ghana', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Gibraltar', 'Gibraltar', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Greece', 'Greece', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Greenland', 'Greenland', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Grenada', 'Grenada', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Guadeloupe', 'Guadeloupe', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Guam', 'Guam', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Guatemala', 'Guatemala', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Guinea', 'Guinea', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Guinea-bissau', 'Guinea-bissau', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Guyana', 'Guyana', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Haiti', 'Haiti', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Heard and Mc Donald Islands', 'Heard and Mc Donald Islands', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Honduras', 'Honduras', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Hong Kong', 'Hong Kong', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Hungary', 'Hungary', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Iceland', 'Iceland', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'India', 'India', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Indonesia', 'Indonesia', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Iran', 'Iran', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Iraq', 'Iraq', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Ireland', 'Ireland', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Israel', 'Israel', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Italy', 'Italy', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Jamaica', 'Jamaica', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Japan', 'Japan', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Jordan', 'Jordan', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Kazakhstan', 'Kazakhstan', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Kenya', 'Kenya', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Kiribati', 'Kiribati', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Korea', 'Korea', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Kuwait', 'Kuwait', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Kyrgyzstan', 'Kyrgyzstan', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Lao People\'s Democratic Republic', 'Lao Peoples Democratic Republic', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Latvia', 'Latvia', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Lebanon', 'Lebanon', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Lesotho', 'Lesotho', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Liberia', 'Liberia', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Libyan Arab Jamahiriya', 'Libyan Arab Jamahiriya', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Liechtenstein', 'Liechtenstein', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Lithuania', 'Lithuania', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Luxembourg', 'Luxembourg', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Macau', 'Macau', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Macedonia', 'Macedonia', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Madagascar', 'Madagascar', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Malawi', 'Malawi', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Malaysia', 'Malaysia', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Maldives', 'Maldives', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Mali', 'Mali', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Malta', 'Malta', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Marshall Islands', 'Marshall Islands', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Martinique', 'Martinique', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Mauritania', 'Mauritania', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Mauritius', 'Mauritius', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Mayotte', 'Mayotte', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Mexico', 'Mexico', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Micronesia', 'Micronesia', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Moldova', 'Moldova', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Monaco', 'Monaco', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Mongolia', 'Mongolia', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Montserrat', 'Montserrat', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Morocco', 'Morocco', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Mozambique', 'Mozambique', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Myanmar', 'Myanmar', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Namibia', 'Namibia', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Nauru', 'Nauru', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Nepal', 'Nepal', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Netherlands', 'Netherlands', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Netherlands Antilles', 'Netherlands Antilles', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'New Caledonia', 'New Caledonia', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'New Zealand', 'New Zealand', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Nicaragua', 'Nicaragua', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Niger', 'Niger', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Nigeria', 'Nigeria', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Niue', 'Niue', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Norfolk Island', 'Norfolk Island', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'North Korea', 'North Korea', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Northern Mariana Islands', 'Northern Mariana Islands', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Norway', 'Norway', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Oman', 'Oman', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Pakistan', 'Pakistan', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Palau', 'Palau', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Panama', 'Panama', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Papua New Guinea', 'Papua New Guinea', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Paraguay', 'Paraguay', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Peru', 'Peru', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Philippines', 'Philippines', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Pitcairn', 'Pitcairn', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Poland', 'Poland', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Portugal', 'Portugal', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Puerto Rico', 'Puerto Rico', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Qatar', 'Qatar', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Reunion', 'Reunion', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Romania', 'Romania', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Russian Federation', 'Russian Federation', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Rwanda', 'Rwanda', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Saint Kitts and Nevis', 'Saint Kitts and Nevis', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Saint Lucia', 'Saint Lucia', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Saint Vincent and the Grenadines', 'Saint Vincent and the Grenadines', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Samoa', 'Samoa', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'San Marino', 'San Marino', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Sao Tome and Principe', 'Sao Tome and Principe', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Saudi Arabia', 'Saudi Arabia', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Senegal', 'Senegal', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Seychelles', 'Seychelles', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Sierra Leone', 'Sierra Leone', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Singapore', 'Singapore', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Slovak Republic', 'Slovak Republic', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Slovenia', 'Slovenia', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Solomon Islands', 'Solomon Islands', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Somalia', 'Somalia', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'South Africa', 'South Africa', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'South Georgia &amp; South Sandwich Islands', 'South Georgia & South Sandwich Islands', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Spain', 'Spain', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Sri Lanka', 'Sri Lanka', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'St. Helena', 'St. Helena', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'St. Pierre and Miquelon', 'St. Pierre and Miquelon', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Sudan', 'Sudan', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Suriname', 'Suriname', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Svalbard and Jan Mayen Islands', 'Svalbard and Jan Mayen Islands', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Swaziland', 'Swaziland', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Sweden', 'Sweden', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Switzerland', 'Switzerland', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Syrian Arab Republic', 'Syrian Arab Republic', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Taiwan', 'Taiwan', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Tajikistan', 'Tajikistan', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Tanzania', 'Tanzania', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Thailand', 'Thailand', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Togo', 'Togo', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Tokelau', 'Tokelau', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Tonga', 'Tonga', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Trinidad and Tobago', 'Trinidad and Tobago', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Tunisia', 'Tunisia', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Turkey', 'Turkey', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Turkmenistan', 'Turkmenistan', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Turks and Caicos Islands', 'Turks and Caicos Islands', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Tuvalu', 'Tuvalu', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Uganda', 'Uganda', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Ukraine', 'Ukraine', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'United Arab Emirates', 'United Arab Emirates', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'United Kingdom', 'United Kingdom', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'United States', 'United States', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'United States Minor Outlying Islands', 'United States Minor Outlying Islands', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Uruguay', 'Uruguay', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Uzbekistan', 'Uzbekistan', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Vanuatu', 'Vanuatu', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Vatican City State (Holy See)', 'Vatican City State (Holy See)', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Venezuela', 'Venezuela', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Viet Nam', 'Viet Nam', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Virgin Islands (British)', 'Virgin Islands (British)', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Virgin Islands (U.S.)', 'Virgin Islands (U.S.)', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Wallis and Futuna Islands', 'Wallis and Futuna Islands', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Western Sahara', 'Western Sahara', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Yemen', 'Yemen', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Yugoslavia', 'Yugoslavia', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Zambia', 'Zambia', '0', '1', '0'),
			                (NULL, '".$this->item->id."', 'Zimbabwe', 'Zimbabwe', '0', '1', '0')
					";
			$db->setQuery($query);
			$db->query();
		}
			
	}
	$query = "
				SELECT
					spo.name,
					spo.id,
					spo.ordering,
					spo.showrow
				FROM
					`#__creative_form_options` spo
				WHERE spo.id_parent = '".$this->item->id."'
				ORDER BY ";
	if($this->item->ordering_field == 0)
		$query .= "spo.ordering";
	else
		$query .= "spo.name";
	$db->setQuery($query);
	$childs_array = $db->loadAssocList();
	
	echo '<H3 style="margin-top: -30px;">Options</H3>';
	
	echo '<div class="options_wrapper">';
	
		echo '<div class="menus_header">';
			echo  '<img src="components/com_creativecontactform/assets/images/new_page.gif" class="new_submenu_img" title="New Option" menu_id="'.$this->item->id.'" />';
			if (sizeof($childs_array) == 0) {
				echo  '<img src="components/com_creativecontactform/assets/images/country.png" class="load_countries_data" title="Load countries data? (239 countries)" />';
			}
		echo '</div>';
		
		$disabled_ordering = $this->item->ordering_field == 1 ? 'disabledordering' : '';

		echo '<div class="menu_tree">';
		echo '<ul id="sortable_menu">';
		if (sizeof($childs_array) > 0)
		{
			foreach ($childs_array as $key => $value)
			{
				$show = $value['showrow'];
				$class = " ui-state-default text";
				$show_class = $show == 1 ? 'hide' : 'show';
				$show_title = $show == 0 ? 'Publish option' : 'Unpublish option';
				
				echo '<li class="'.$class.'" id="option_li_'.$value["id"].'">';
					echo '<div class="option_item" id="option_'.$value["id"].'">'.$value["name"].'</div>';
					echo '<div class="menu_moove '.$disabled_ordering.'" title="Move option" >&nbsp;</div>';
					echo '<div id="edit_'.$value["id"].'" menu_id="'.$value["id"].'" class="edit" title="Edit option" >&nbsp;</div>';
					echo '<div id="showrow_'.$value["id"].'" menu_id="'.$value["id"].'" class="'.$show_class.'" title="'.$show_title.'" >&nbsp;</div>';
					echo '<div id="remove_'.$value["id"].'" menu_id="'.$value["id"].'" class="delete" title="Delete option" >&nbsp;</div>';
				echo '</li>';
			}
		}
		echo '</div>';
		echo '</ul>';
	echo '</div>';
}
?>
<div id="edit_menu_data" style="display: none;">
	<div id="ajax_loader">&nbsp;</div>
	<div id="dialog_inner_wrapper"></div>
	<input type="hidden" value="" id="menu_id" />
</div>

<?php include (JPATH_BASE.'/components/com_creativecontactform/helpers/footer.php'); ?>
<?php }?>

<script type="text/javascript">
(function($) {
$(document).ready(function() {

	var form_id = <?php echo $_GET["filter_form_id"];?>;
	var item_id = <?php echo (int)$this->item->id;?>;
	if(item_id == 0) {
		$("#jform_id_form").val(form_id).show();
		$("#jform_id_form").next('div').remove();
	}



	})
})(creativeJ);
</script>

<style>
.ui-widget-overlay {
background: black url(components/com_creativecontactform/assets/images/ui/noise_pattern.jpg) 50% 50% repeat ;
opacity: .80;
filter: Alpha(Opacity=80);
}
#jform_custom_html_ifr {
	height: 200px !important;
}
#jform_contact_data_ifr {
	height: 200px !important;
}
#jform_creative_popup_ifr {
	height: 200px !important;
}
.editor {
	overflow: visible !important; 
}
#jform_contact_data {
	height: 250px;
}
#jform_creative_popup_embed {
	height: 200px;
}

input, textarea, .uneditable-input {
	width: 430px;
}
div.chzn-container {
	width: 444px !important;
}
select {
	width: 444px;
}
.form-horizontal .controls {
margin-left: 200px !important;
}
.options_wrapper {
width: 645px !important;
}
</style>

