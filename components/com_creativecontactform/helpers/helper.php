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

class CreativecontactformHelper
{

	//function to add scripts/styles
	private function add_scripts() {

		$version = '3.0.0';

		$document = JFactory::getDocument();

		$types_array = $this->types_array;
		$form_id = $this->form_id;

		$cssFile = JURI::base(true).'/components/com_creativecontactform/assets/css/main.css?version='.$version;
		$document->addStyleSheet($cssFile, 'text/css', null, array());

		$cssFile = JURI::base(true).'/components/com_creativecontactform/assets/css/creativecss-ui.css';
		$document->addStyleSheet($cssFile, 'text/css', null, array());

		$cssFile = JURI::base(true).'/components/com_creativecontactform/assets/css/creative-scroll.css';
		$document->addStyleSheet($cssFile, 'text/css', null, array());

		$cssFile = JURI::base(true).'/components/com_creativecontactform/assets/css/template.css';
		$document->addStyleSheet($cssFile, 'text/css', null, array());

		$jsFile = JURI::base(true).'/components/com_creativecontactform/assets/js/creativelib.js';
		$document->addScript($jsFile);

		$jsFile = JURI::base(true).'/components/com_creativecontactform/assets/js/creativelib-ui.js';
		$document->addScript($jsFile);

		$jsFile = JURI::base(true).'/components/com_creativecontactform/assets/js/creative-mousewheel.js';
		$document->addScript($jsFile);

		$jsFile = JURI::base(true).'/components/com_creativecontactform/assets/js/creative-scroll.js';
		$document->addScript($jsFile);

		$jsFile = JURI::base(true).'/components/com_creativecontactform/assets/js/creativecontactform.js?version='.$version;
		$document->addScript($jsFile);

	}

	private function get_data() {
		$db = JFactory::getDBO();

		//get field types array/////////////////////////////////////////////////////////////////////////////////////////////////
		$query = "
					SELECT
					sp.id,
					st.name as type
					FROM
					`#__creative_fields` sp
					JOIN `#__creative_field_types` st ON st.id = sp.id_type
					WHERE sp.published = '1'
					AND sp.id_form = '".$this->form_id."'
					ORDER BY sp.ordering,sp.id
				";
		$db->setQuery($query);
		$types_array_data = $db->loadAssocList();
		$types_array_index = 1;
		$types_array = array();
		if(is_array($types_array_data)) {
			foreach($types_array_data as $type) {
				$types_array[$types_array_index] = strtolower(str_replace(' ','-',str_replace('-','',$type['type'])));
				$types_array_index ++;
			}
		}
		$this->types_array = $types_array;

		// set field index
		if(!isset($this->field_index))
			$this->field_index = 1;

		//get form data/////////////////////////////////////////////////////////////////////////////////////////////////
		$query = "
					SELECT
					sp.`id_template`,
					sp.name,
					sp.top_text,
					sp.pre_text,
					sp.thank_you_text,
					sp.send_text,
					sp.send_new_text,
					sp.close_alert_text,
					sp.form_width,
					sp.redirect,
					sp.redirect_itemid,
					sp.redirect_url,
					sp.redirect_delay,
					sp.shake_count,
					sp.shake_distanse,
					sp.send_copy_enable,
					sp.send_copy_text,
					sp.shake_duration,
					sp.custom_css,
					st.styles
					FROM
					`#__creative_forms` sp
					LEFT JOIN `#__contact_templates` st ON st.id = sp.id_template
					WHERE sp.published = '1'
					AND sp.id = '".$this->form_id."'";
		$db->setQuery($query);
		$this->form_data = $db->loadAssoc();

		//sp.id_template

		//get fields data/////////////////////////////////////////////////////////////////////////////////////////////////
		$query = "
					SELECT
					sp.id,
					sp.name,
					sp.tooltip_text,
					sp.required,
					sp.ordering_field,
					sp.select_default_text,
					sp.show_parent_label,
					sp.select_no_match_text,
					sp.width,
					sp.field_margin_top,
					sp.select_show_scroll_after,
					sp.select_show_search_after,
					sp.upload_button_text,
					sp.upload_minfilesize,
					sp.upload_maxfilesize,
					sp.upload_acceptfiletypes,
					sp.upload_minfilesize_message,
					sp.upload_maxfilesize_message,
					sp.upload_acceptfiletypes_message,
					sp.captcha_wrong_message,

					sp.datepicker_date_format,
					sp.datepicker_animation,
					sp.datepicker_style,
					sp.datepicker_icon_style,
					sp.datepicker_show_icon,
					sp.datepicker_input_readonly,
					sp.datepicker_number_months,
					sp.datepicker_mindate,
					sp.datepicker_maxdate,
					sp.datepicker_changemonths,
					sp.datepicker_changeyears,
					sp.column_type,
					sp.custom_html,
					sp.google_maps,
					sp.heading,
					sp.recaptcha_site_key,
					sp.recaptcha_wrong_message,
					sp.recaptcha_theme,
					sp.recaptcha_type,
					sp.contact_data,
					sp.contact_data_width,
					sp.creative_popup,
					sp.creative_popup_embed,

					st.name as type
				FROM
					`#__creative_fields` sp
				JOIN `#__creative_field_types` st ON st.id = sp.id_type
				WHERE sp.published = '1'
				AND sp.id_form = '".$this->form_id."'
				ORDER BY sp.ordering,sp.id
		";
		$db->setQuery($query);
		$this->field_data = $db->loadAssocList();

		//get fields data/////////////////////////////////////////////////////////////////////////////////////////////////
		$REMOTE_ADDR = null;
		$this->remote_addr = $REMOTE_ADDR;
	}

	private function print_fields_array_html($field_data) {

		$db = JFactory::getDBO();
		//get data/////////////////////////////////////////////////////////////////////////////////////////////////////////
		$this->get_data();

		//add scripts/////////////////////////////////////////////////////////////////////////////////////////////////////
		if($this->type != 'plugin')
			$this->add_scripts();
		
		//Get variables////////////////////////////////////////////////////////////////////////////////////////////////////
		$module_id = $this->module_id;
		$form_data = $this->form_data;
		$types_array = $this->types_array;
		$form_id = $this->form_id;
		$templateid = $form_data['id_template'];
		$styles_row = $form_data['styles'];

		$tooltip_style = 'white';

		$top_text_font_effect = '';
		$heading_text_font_effect = '';
		$pre_text_font_effect = '';
		$label_text_font_effect = '';
		$label_hover_text_font_effect = '';
		$label_error_text_font_effect = '';
		$send_font_effect = '';
		$send_hover_font_effect = '';

		$this->heading_text_font_effect = $heading_text_font_effect;


		$focus_anim_enabled = $label_text_font_effect == $label_hover_text_font_effect ? 0 : 1;
		$error_anim_enabled = $label_text_font_effect == $label_error_text_font_effect ? 0 : 1;

		$user = JFactory::getUser();

		$is_textarea_exist = false;
		foreach($field_data as $field) {
			$field_index = $this->field_index;

			$field_width = $field['width'] != '' ? 'style="width: '.$field['width'].' !important"' : '';
			$field_width_select = $field['width'] != '' ? $field['width'] : '';
			$field_margin = $field['field_margin_top'] != '' ? 'style="margin: '.$field['field_margin_top'].' !important"' : '';
			
			$field_name = stripslashes($field['name']);
			
			$field_tooltip_text = stripslashes($field['tooltip_text']);
			$field_type = strtolower(str_replace(' ','-',str_replace('-','',$field['type'])));
			$element_id = $field_type.'_'.$module_id.'_'.$field['id'];
			$required_classname = $field['required'] == 1 ? 'creativecontactform_required' : '';
			$required_symbol = $field['required'] == 1 ? ' <span class="creativecontactform_field_required">*</span>' : '';
			$predefined_value = $field_type == 'name' ? $user->name : ($field_type == 'email' ? $user->email : '');
			
			if($field_type == 'text-area') 
				$is_textarea_exist = true;
			//input html
			$input_type_text_arrays = array('text-input','name','address','email','phone','number','url');
			if(in_array($field_type,$input_type_text_arrays)) {
				$input_html = '<div class="creativecontactform_input_element '.$required_classname.'"><div class="creative_input_dummy_wrapper">';
				$input_html .= '<input class="creative_'.$field_type.' '.$required_classname.' creative_input_reset" pre_value="'.str_replace('"','',$predefined_value).'" value="'.str_replace('"','',$predefined_value).'" type="text" id="'.$element_id.'" name="creativecontactform_fields['.$field_index.'][0]"></div></div>';
			}
			
			elseif($field_type == 'text-area') {
				$input_html = '<div class="creativecontactform_input_element creative_textarea_wrapper '.$required_classname.'"><div class="creative_textarea_dummy_wrapper">';
				$input_html .= '<textarea class="creative_textarea creative_'.$field_type.' '.$required_classname.' creative_textarea_reset" value="'.$predefined_value.'" cols="30" rows="15" id="'.$element_id.'" name="creativecontactform_fields['.$field_index.'][0]"></textarea></div></div>';
			}
			elseif($field_type == 'select' || $field_type == 'multiple-select' || $field_type == 'radio' || $field_type == 'checkbox') {
				//get child options
				$query = "
							SELECT
 							spo.name,
 							spo.id,
 							spo.value,
 							spo.selected
							FROM
								`#__creative_form_options` spo
							WHERE spo.id_parent = '".$field['id']."'
							AND spo.showrow = '1'
							ORDER BY ";
							if($field['ordering_field'] == 0)
								$query .= "spo.ordering";
							else
								$query .= "spo.name";
				$db->setQuery($query);
				$childs_array = $db->loadAssocList();
				if (sizeof($childs_array) > 0)
				{
					$childs_length = sizeof($childs_array);
					if($field_type == 'select' || $field_type == 'multiple-select') {
						$selected_count = 0;
						foreach ($childs_array as $key => $value)
						{
							if($value['selected'] == 1) {
								$selected_count= 1;
								break;
							}
						}
						$def_selection = $selected_count == 0 ? 'selected="selected"' : '';
						
						$show_search = $childs_length >= $field["select_show_search_after"] ? 'show' : 'hide';
						$scroll_after = (int)$field["select_show_scroll_after"] > 3 ? (int)$field["select_show_scroll_after"] : 3;
						
						$multile_info = $field_type == 'multiple-select' ? 'multiple="multiple"' : '';
						$multile_info_val = $field_type == 'multiple-select' ? '[]' : '';
						$input_html = '<select show_search="'.$show_search.'" scroll_after="'.$scroll_after.'" special_width="'.$field_width_select.'" select_no_match_text="'.stripslashes(str_replace('"','',$field["select_no_match_text"])).'" class="will_be_creative_select '.$required_classname.'" '.$multile_info.' name="creativecontactform_fields['.$field_index.'][0]'.$multile_info_val.'">';
						$input_html .= '<option '.$def_selection.' class="def_value" value="creative_empty">'.$field["select_default_text"].'</option>';
						$selected = '';
						$pre_val='';
						$seted_value = false;
						foreach ($childs_array as $key => $value)
						{
							if(!$seted_value && $field_type == 'select' && $value['selected'] == '1') {
								$selected = 'selected="selected"';
								$pre_val = 'pre_val="selected"';
								$seted_value = true;
							}
							elseif($field_type == 'multiple-select'  &&  $value['selected'] == '1') {
								$selected = 'selected="selected"';
								$pre_val = 'pre_val="selected"';
							}
							else {
								$selected = '';
								$pre_val = '';
							}
							
							$input_html .= '<option id="'.$module_id.'_'.$field["id"].'_'.$value["id"].'" value="'.stripslashes(str_replace('"','',$value["value"])).'" '.$selected.' '.$pre_val.'>'.stripslashes($value["name"]).'</option>';
						}
						$input_html .= '</select>';
					}
					elseif($field_type == 'radio' || $field_type == 'checkbox') {

						$input_html = '';
						$colors_array = array("black","blue","red","litegreen","yellow","liteblue","green","crimson","litecrimson");
						$selected = '';
						$pre_val='';
						$seted_value = false;
						foreach ($childs_array as $key => $value)
						{
							if($field_type == 'radio' && !$seted_value && $value['selected'] == '1') {
								$selected = 'checked="checked"';
								$pre_val = 'pre_val="checked"';
								$seted_value = true;
							}
							elseif($field_type == 'checkbox'  &&  $value['selected'] == '1') {
								$selected = 'checked="checked"';
							$pre_val = 'pre_val="checked"';	 											
							}
							else {
								$selected = '';
							$pre_val = '';	 											
							}
							
							$data_color_index = $key % 8;

							$current_label = stripslashes($value["name"]);
							
							$label_class = $field['show_parent_label'] == 0 ? 'without_parent_label' : '';
							$req_symbol = ($field['show_parent_label'] == 0 && $key == 0) ? $required_symbol : '';
							$input_html .= '<div class="creative_checkbox_wrapper centered"><div class="answer_name"><label uniq_index="'.$module_id.'_'.$field["id"].'_'.$value["id"].'" class="twoglux_label '.$label_class.'"><span class="creative_checkbox_label_wrapper">'.$current_label.' '.$req_symbol.'</span></label></div>';
							$input_html .= '<div class="answer_input">';
							
							if($field_type == 'radio')
								$input_html .= '<input '.$selected.' '.$pre_val.' id="'.$module_id.'_'.$field["id"].'_'.$value["id"].'" type="radio" class="creative_ch_r_element creativeform_twoglux_styled elem_'.$module_id.'_'.$field["id"].'" value="'.stripslashes(str_replace('"','',$value["value"])).'" uniq_index="elem_'.$module_id.'_'.$field["id"].'" name="remove_this_partcreativecontactform_fields['.$field_index.'][0]" data-color="'.$colors_array[$data_color_index].'" />';
							else
								$input_html .= '<input '.$selected.' '.$pre_val.' id="'.$module_id.'_'.$field["id"].'_'.$value["id"].'" type="checkbox" class="creative_ch_r_element creativeform_twoglux_styled" value="'.stripslashes(str_replace('"','',$value["value"])).'" name="creativecontactform_fields['.$field_index.'][0][]" data-color="'.$colors_array[$data_color_index].'" />';
							
							$input_html .= '</div></div><div class="creative_clear"></div>';
						}
					}
				}
				else {
					$input_html = 'There are no options to be shown.';
				}
			}
			

			$hidden_field_types = array('file-upload','captcha','custom-html','heading','google-maps','google-recaptcha','contact-data','social-links','creative-popup');
			if(!in_array($field_type,$hidden_field_types)) {
				$input_html .= '<input type="hidden" name="creativecontactform_fields['.$field_index.'][1]" value="'.stripslashes(str_replace('"','',$field_name)).'" />';
				$input_html .= '<input type="hidden" name="creativecontactform_fields['.$field_index.'][2]" value="'.$field_type.'" />';
			}
			
			//start printing html
			$radio_checkbox_class = ($field_type == 'radio' || $field_type == 'checkbox' || $field_type == 'file-upload') ? 'creative_'.$field_type : '';
			$radio_checkbox_req_class = ($field_type == 'radio' || $field_type == 'checkbox'  || $field_type == 'file-upload') ? $required_classname : '';

			if($field_type == 'radio' || $field_type == 'checkbox' || $field_type == 'file-upload' || $field_type == 'captcha') {
				$box_inner_class = $is_textarea_exist ? 'creativecontactform_field_box_textarea_inner' : 'creativecontactform_field_box_inner';
			}
			else {
				$box_inner_class = $field_type == 'text-area' ? 'creativecontactform_field_box_textarea_inner' : 'creativecontactform_field_box_inner';

			}

			//start printing boxes
			$wrapper_id = $field_type == 'google-recaptcha' ? $element_id : '';
			$field_box_style = $field_type == 'creative-popup' ? 'style="display: none"' : '';
				echo '<div id="'.$wrapper_id.'" '.$field_margin.' class="creativecontactform_field_box creative_hidden_animation_block_state1 creative_timing_'.$field_index.' creative_timing_'.$field_type.' '.$radio_checkbox_class.' '.$radio_checkbox_req_class.'"><div '.$field_width.' class="'.$box_inner_class.'">';
					$show_label = ($field['show_parent_label'] == 0 || $field_type == 'heading') ? 'style="display:none !important"' : '';
					echo '<label normal_effect_class="'.$label_text_font_effect.'" hover_effect_class="'.$label_hover_text_font_effect.'" error_effect_class="'.$label_error_text_font_effect.'" class="creativecontactform_field_name '.$label_text_font_effect.'" for="'.$element_id.'" '.$show_label.'><span class="creative_label_txt_wrapper">'.$field_name;
					if($field_type == 'captcha')
						echo ' <span class="creativecontactform_field_required">*</span></label>';
					else	
						echo $required_symbol.'</span></label>';
					echo $input_html;
				echo '</div></div>';
			// echo '</div>';
			
			$this->field_index ++;
		}
	}
	
	public function render_html()
	{
		$db = JFactory::getDBO();
		$document = JFactory::getDocument();

		//get data/////////////////////////////////////////////////////////////////////////////////////////////////////////
		$this->get_data();

		//add scripts/////////////////////////////////////////////////////////////////////////////////////////////////////
		if($this->type != 'plugin')
			$this->add_scripts();
		
		//Get variables////////////////////////////////////////////////////////////////////////////////////////////////////
		$module_id = $this->module_id;
		$form_data = $this->form_data;
		$field_data = $this->field_data;
		$types_array = $this->types_array;
		$form_id = $this->form_id;
		$templateid = $form_data['id_template'];
		$styles_row = $form_data['styles'];

		$this->section_id = 0;

		$tooltip_style = '';
		$tooltip_style = '';

		$scrollbar_popup_style = 'dark-thin';
		$scrollbar_content_style = 'dark-thin';

		$ccf_global_icons_style = 1;
		$ccf_sections_icons_style = '';

		$top_text_font_effect = '';
		$heading_text_font_effect = '';
		$pre_text_font_effect = '';
		$label_text_font_effect = '';
		$label_hover_text_font_effect = '';
		$label_error_text_font_effect = '';
		$send_font_effect = '';
		$send_hover_font_effect = '';

		$focus_anim_enabled = 0;
		$error_anim_enabled = 0;

		$REMOTE_ADDR = $this->remote_addr;
		$user = JFactory::getUser();

		$toptxt = $form_data['top_text'];
		$pretxt = stripcslashes($form_data['pre_text']);

		$form_width = $form_data['form_width'];
		$custom_css = $form_data['custom_css'];
		$redirect_enable =  $form_data['redirect'];
		$redirect = '';
		if ($redirect_enable) {
			$redirectItemId = (int) $form_data['redirect_itemid'] == 0 ? 1 : (int) $form_data['redirect_itemid'];
			$redirectUrl = $form_data['redirect_url'];
			if ($redirectUrl != '') {
				$redirect = JRoute::_($redirectUrl, false);
			} else {
				$redirect = JRoute::_('index.php?Itemid='.$redirectItemId);
			}
		}
		$redirect_delay = (int) $form_data['redirect_delay'];
		$thank_you_text = htmlspecialchars($form_data['thank_you_text'],ENT_QUOTES);
		$send_text = htmlspecialchars($form_data['send_text'],ENT_QUOTES);
		$send_new_text = htmlspecialchars($form_data['send_new_text'],ENT_QUOTES);
		$close_alert_text = htmlspecialchars($form_data['close_alert_text'],ENT_QUOTES);

		//validation options
		$shake_count = (int) $form_data['shake_count'];
		$shake_distanse = (int) $form_data['shake_distanse'];
		$shake_duration = (int) $form_data['shake_duration'];

		//send copy options
		$send_copy_enable= (int) $form_data['send_copy_enable'];
		$send_copy_text=htmlspecialchars($form_data['send_copy_text'],ENT_QUOTES);

		//strat rendering html///////////////////////////////////////////////////////////////////////////////////////////////
		ob_start();
		if(sizeof($field_data) > 0) {
			?>
			<?php // echo $ccf_google_link;?>
			<div class="creativecontactform_wrapper creative_wrapper_animation_state_1 creative_form_module creative_form_<?php echo $form_id;?> ccf_icon_<?php echo $ccf_global_icons_style;?> ccf_sections_template_<?php echo $ccf_sections_icons_style;?>" <?php if($form_width != '') { echo 'style="width: '.$form_width.' !important"'; }?> focus_anim_enabled="<?php echo $focus_anim_enabled;?>" error_anim_enabled="<?php echo $error_anim_enabled;?>" scrollbar_popup_style="<?php echo $scrollbar_popup_style;?>"  scrollbar_content_style="<?php echo $scrollbar_content_style;?>">
				<div class="creativecontactform_wrapper_inner">
				<div class="creativecontactform_loading_wrapper"><table style="border: none;width: 100%;height: 100%"><tr><td align="center" valign="middle"><img src="<?php echo JURI::base(true).'/components/com_creativecontactform/assets/images/ajax-loader.gif';?>" /></td></tr></table></div>
	 			
	 			<div class="creativecontactform_header creative_header_animation_state_1">
		 			<div class="creativecontactform_title <?php echo $top_text_font_effect;?>"><?php echo $toptxt;?></div>
		 			<?php if($pretxt != '') {?><div class="creativecontactform_pre_text <?php echo $pre_text_font_effect;?>"><?php echo $pretxt;?></div><?php }?>
	 			</div>
 				<form class="creativecontactform_form">
		 			<div class="creativecontactform_body creative_body_animation_state_1">
					 		<?php 
			 				if(sizeof($field_data) > 0) {

		 						// split data
		 						$fields_final_array = array();
		 						$k = 0;
		 						$separate_col_0 = false;
		 						$separate_col_12 = false;
		 						foreach($field_data as $field) {
		 							$column_type = $field['column_type'];
		 							if($column_type != 0) {
		 								if($separate_col_12) {
		 									$k ++;
		 									$separate_col_12 = false;
		 								}

		 								$fields_final_array[$k][$column_type][] = $field;
		 								$separate_col_0 = true;
		 							}
		 							else {
		 								if($separate_col_0) {
		 									$k ++;
		 									$separate_col_0 = false;
		 								}

		 								$fields_final_array[$k][$column_type][] = $field;
	 									$separate_col_12 = true;

		 							}
		 						}
		 						foreach($fields_final_array as $k => $field_columns_array) {
		 							// echo '<div style="clear: both">'.$k.'</div>';
		 							//print left columns
		 							if(isset($field_columns_array['1']) || isset($field_columns_array['2'])) {
			 							echo '<div class="creative_field_box_wrapper creative_field_box_wrapper_1 creative_field_box_animation_state_1"><div class="creative_field_box_wrapper_1_inner">';
			 								if(isset($field_columns_array['1'])) {
			 									$this->print_fields_array_html($field_columns_array['1']);
			 								}
			 							echo '</div></div>';
			 						}
			 						if(isset($field_columns_array['2'])) {
			 							//print right column
			 							echo '<div class="creative_field_box_wrapper creative_field_box_wrapper_2 creative_field_box_animation_state_1"><div class="creative_field_box_wrapper_2_inner">';
			 								if(isset($field_columns_array['2'])) {
			 									$this->print_fields_array_html($field_columns_array['2']);
			 								}
			 							echo '</div></div>';
			 						}
		 							// print both columns
		 							if(isset($field_columns_array['0'])) {
			 							//print right column
			 							echo '<div class="creativecontactform_clear"></div><div class="creative_field_box_wrapper creative_field_box_wrapper_0 creative_field_box_animation_state_1">';
			 								if(isset($field_columns_array['0'])) {
			 									$this->print_fields_array_html($field_columns_array['0']);
			 								}
			 							echo '</div>';
			 						}
		 						}

			 				}
			 				
			 			
			 				?>
						<div class="creative_clear"></div>
		 			</div>
		 			<div class="creativecontactform_footer creative_footer_animation_state_1">
			 			<div class="creativecontactform_submit_wrapper creative_button_animation_state_1">
			 				<input type="button" value="<?php echo $send_text;?>" class="creativecontactform_send <?php echo $send_font_effect;?>" roll="<?php echo $form_id;?>" normal_effect_class="<?php echo $send_font_effect;?>" hover_effect_class="<?php echo $send_hover_font_effect;?>"/>
			 				<input type="button" value="<?php echo $send_new_text;?>" class="creativecontactform_send_new creativecontactform_hidden <?php echo $send_font_effect;?>"  roll="<?php echo $form_id;?>" normal_effect_class="<?php echo $send_font_effect;?>" hover_effect_class="<?php echo $send_hover_font_effect;?>"/>
			 				<div class="creativecontactform_clear"></div>
			 			</div>
			 			<?php echo '<div class="creative_clear">&nbsp;</div><div class="powered_by powered_by_'.$templateid.'">Powered By <a href="http://creative-solutions.net/joomla/creative-contact-form" target="_blank">Creative Contact Form</a></div><div class="creative_clear">&nbsp;</div>';?>
			 			<input type="hidden" name="<?php echo JSession::getFormToken();?>" class="creativecontactform_token" value="1" />
			 			<input type="hidden" value="<?php echo $module_id;?>" class="creativecontactform_module_id" name="creativecontactform_module_id" />
			 			<input type="hidden" value="<?php echo $form_id;?>" class="creativecontactform_form_id" name="creativecontactform_form_id" />
		 			</div>
	 			</form>
	 		</div>
	 		</div>

	 		<?php
			//including custom javascript/////////////////////////////////////////////////////////////////////////////////////////////////
			$jsInclude = ' if (typeof creativecontactform_shake_count_array === \'undefined\') { var creativecontactform_shake_count_array = new Array();};';
			$jsInclude .= 'creativecontactform_shake_count_array['.$form_id.'] = "'.$shake_count.'";';

			$jsInclude .= ' if (typeof creativecontactform_shake_distanse_array === \'undefined\') { var creativecontactform_shake_distanse_array = new Array();};';
			$jsInclude .= 'creativecontactform_shake_distanse_array['.$form_id.'] = "'.$shake_distanse.'";';

			$jsInclude .= ' if (typeof creativecontactform_shake_duration_array === \'undefined\') { var creativecontactform_shake_duration_array = new Array();};';
			$jsInclude .= 'creativecontactform_shake_duration_array['.$form_id.'] = "'.$shake_duration.'";';

			$jsInclude .= 'var creativecontactform_path = "'.JURI::base(true).'/components/com_creativecontactform/";';

			$jsInclude .= ' if (typeof creativecontactform_redirect_enable_array === \'undefined\') { var creativecontactform_redirect_enable_array = new Array();};';
			$jsInclude .= 'creativecontactform_redirect_enable_array['.$form_id.'] = "'.$redirect_enable.'";';

			$jsInclude .= ' if (typeof creativecontactform_redirect_array === \'undefined\') { var creativecontactform_redirect_array = new Array();};';
			$jsInclude .= 'creativecontactform_redirect_array['.$form_id.'] = "'.$redirect.'";';

			$jsInclude .= ' if (typeof creativecontactform_redirect_delay_array === \'undefined\') { var creativecontactform_redirect_delay_array = new Array();};';
			$jsInclude .= 'creativecontactform_redirect_delay_array['.$form_id.'] = "'.$redirect_delay.'";';

			$jsInclude .= ' if (typeof creativecontactform_thank_you_text_array === \'undefined\') { var creativecontactform_thank_you_text_array = new Array();};';
			$jsInclude .= 'creativecontactform_thank_you_text_array['.$form_id.'] = "'.$thank_you_text.'";';

			$jsInclude .= ' if (typeof close_alert_text === \'undefined\') { var close_alert_text = new Array();};';
			$jsInclude .= 'close_alert_text['.$form_id.'] = "'.$close_alert_text.'";';

			$jsInclude .= 'creativecontactform_juri = "'.JURI::base( true ).'";';

			if($this->type != 'plugin') {
				$document = JFactory::getDocument();
				$document->addScriptDeclaration ( $jsInclude );
			}
			else {
				echo $jstoinclude = '<script type="text/javascript">'.$jsInclude.'</script>';
			}
			
		}
		else {
			echo 'Creative Contact Form: There is nothing to show!';
		}
 		?>

		<?php
		return $render_html = ob_get_clean();
	}
}
