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

error_reporting(0);
//header('Content-type: application/json');

$id = (int)$_POST['menu_id'];
$type = $_POST['type'];
if($type == 'get_data') {
	//get form configuration
	$query = "
	SELECT
		spo.`name`,
		spo.`value`,
		spo.`selected`
	FROM
		`#__creative_form_options` spo
	WHERE spo.id = '$id'";
	$db->setQuery($query);
	$option_data = $db->loadAssoc();
	$option_name = htmlspecialchars($option_data['name'],ENT_QUOTES);
	$option_value = htmlspecialchars($option_data['value'],ENT_QUOTES);
	$option_selected = $option_data['selected'];
	?>
	<form method="post" action="" enctype="multipart/form-data" id="menu_edit_form">
		<div id=menus_info_tabs>
			<ul>
				<li><a href="#tabs-1">Option data</a></li>
			</ul>
			<div id="tabs-1" style="background-color: #fff3d6">
				<table border="0" cellpadding="2" cellspacing="1" style="margin: 8px 2px 3px 2px;padding: 0;width:100%">
					<tr>
						<td style="width: 100px;">
							Name
						</td>
						<td>
							<input type="text" id="new_title" name="new_title" value="<?php echo $option_name;?>"/>
						</td>
					</tr>
					<tr>
						<td style="width: 100px;">
							Value
						</td>
						<td>
							<input type="text" id="new_value" name="new_value" value="<?php echo $option_value;?>"/>
						</td>
					</tr>
					<tr>
						<td style="width: 100px;">
							Selected
						</td>
						<td valign="middle">
							<input type="radio" value="0" <?php if($option_selected == 0) echo 'checked="checked"';?> name="option_selected"  id="check_option_0"/> <label style="display: inline-block;" for="check_option_0">No</label>&nbsp;&nbsp;
							<input type="radio" value="1" <?php if($option_selected == 1) echo 'checked="checked"';?> name="option_selected" id="check_option_1"/> <label style="display: inline-block;" for="check_option_1" >Yes</label>
						</td>
					</tr>
					<tr>
						<td colspan="2" align="right" style="margin-top: 10px;">
							<button id="submit_options_form" class="btn btn-small btn-success"><i class="icon-apply icon-white"></i>Save</button>
						</td>
					</tr>
				</table>
			</div>
		</div>
	</form>
	<?php
}
elseif($type == 'new_option_data') {
	//get form configuration
	?>
	<form method="post" action="" enctype="multipart/form-data" id="menu_edit_form">
		<div id=menus_info_tabs>
			<ul>
				<li><a href="#tabs-1">Option data</a></li>
			</ul>
			<div id="tabs-1" style="background-color: #fff3d6">
				<table border="0" cellpadding="2" cellspacing="1" style="margin: 8px 2px 3px 2px;padding: 0;width:100%">
					<tr>
						<td style="width: 100px;">
							Name
						</td>
						<td>
							<input type="text" id="new_title" name="new_title" value="" placeholder="Option name"/>
						</td>
					</tr>
					<tr>
						<td style="width: 100px;">
							Selected
						</td>
						<td valign="middle">
							<input type="radio" value="0" checked="checked" name="option_selected" id="check_option_0"/> <label style="display: inline-block;" for="check_option_0">No</label>&nbsp;&nbsp;
							<input type="radio" value="1"  name="option_selected" id="check_option_1"/> <label style="display: inline-block;" for="check_option_1" >Yes</label>
						</td>
					</tr>
					<tr>
						<td colspan="2" align="right" style="margin-top: 10px;">
							<button id="submit_new_option_form" class="btn btn-small btn-success"><i class="icon-apply icon-white"></i>Add</button>
						</td>
					</tr>
				</table>
			</div>
		</div>
	</form>
	<?php
}
elseif($type == 'save_data') {
	$name = JRequest::getVar('name');
	$value = JRequest::getVar('value');
	$selected = (int)$_POST['selected'];
	//get form configuration
	$query = "
	UPDATE
		`#__creative_form_options`
	SET
		`name` = '$name',
		`value` = '$value',
		`selected` = '$selected'
	WHERE id = '$id'";
	$db->setQuery($query);
	$db->query();
}
elseif($type == 'save_new_option_data') {
	$name = JRequest::getVar('name');
	$value = $name;
	$selected = (int)$_POST['selected'];
	
	//get ordering
	$query = "SELECT MAX(`ordering`) maxorder FROM `#__creative_form_options` WHERE `id_parent` = '$id'";
	$db->setQuery($query);
	$ordering = $db->loadResult();
	$ordering ++;
	$ordering = (int)$ordering;
	
	$query = "
	INSERT INTO 
		`#__creative_form_options` (`name`,`value`,`selected`,`ordering`,`id_parent`)
	VALUES 
		('$name','$value','$selected','$ordering','$id')";
	$db->setQuery($query);
	$db->query();
	$insertid = $db->insertid();
	
	?>
	<li class=" ui-state-default text" id="option_li_<?php echo $insertid;?>">
		<div class="option_item" id="option_<?php echo $insertid;?>"><?php echo htmlspecialchars($name, ENT_QUOTES);?></div>
		<div class="menu_moove" title="Move option" >&nbsp;</div>
		<div id="edit_<?php echo $insertid;?>" menu_id="<?php echo $insertid;?>" class="edit" title="Edit option" >&nbsp;</div>
		<div id="showrow_<?php echo $insertid;?>" menu_id="<?php echo $insertid;?>" class="hide" title="Unpublish option" >&nbsp;</div>
		<div id="remove_<?php echo $insertid;?>" menu_id="<?php echo $insertid;?>" class="delete" title="Delete option" >&nbsp;</div>
	</li>
	<?php 
}
elseif($type == 'show_unpublish_wrapper') {
	//get form configuration
	?>
	<div style="background-color: #fff3d6;padding: 15px;">
		<div style="margin: 5px 5px 15px 5px;text-align: center">Unpublish option?</div>
		<button id="submit_hide_option" class="btn btn-small btn-success"><i class="icon-apply icon-white"></i>Unpublish</button>
	</div>
	<?php 
}
elseif($type == 'show_publish_wrapper') {
	//get form configuration
	?>
	<div style="background-color: #fff3d6;padding: 15px;">
		<div style="margin: 5px 5px 15px 5px;text-align: center">Publish option?</div>
		<button id="submit_show_option" class="btn btn-small btn-success"><i class="icon-apply icon-white"></i>Publish</button>
	</div>
	<?php 
}
elseif($type == 'show_delete_wrapper') {
	//get form configuration
	?>
	<div style="background-color: #fff3d6;padding: 15px;">
		<div style="margin: 5px 5px 15px 5px;text-align: center">Delete option?</div>
		<button id="submit_delete_option" class="btn btn-small btn-success"><i class="icon-apply icon-white"></i>Delete</button>
	</div>
	<?php 
}
elseif($type == 'unpublish_data') {
	//get form configuration
	$query = "
	UPDATE
		`#__creative_form_options`
	SET
		`showrow` = '0'
	WHERE id = '$id'";
	$db->setQuery($query);
	$db->query();
}
elseif($type == 'delete_data') {
	//get form configuration
	$query = "
	DELETE FROM 
		`#__creative_form_options`
	WHERE id = '$id'";
	$db->setQuery($query);
	$db->query();
}
elseif($type == 'publish_data') {
	//get form configuration
	$query = "
	UPDATE
		`#__creative_form_options`
	SET
		`showrow` = '1'
	WHERE id = '$id'";
	$db->setQuery($query);
	$db->query();
}
elseif($type == 'reorder') {
	//get form configuration
	$order = str_replace('option_li_','',$_POST[order]);
	$order_array = explode(',',$order);
	$query ="UPDATE `#__creative_form_options` SET `ordering` = CASE `id`";
	foreach ($order_array as $key => $val)
	{
		$ord = $key+1;
		$query .= "WHEN ".$val." THEN '".$ord."'";
	}
	$query .= " END WHERE `id` IN (".$order.")";
	$db->setQuery($query);
	$db->query();
}

exit();
?>