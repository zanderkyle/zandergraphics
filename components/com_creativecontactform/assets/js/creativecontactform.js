(function($) {
$(document).ready(function() {

	function ccf_set_sc_res() {
		var screenWidth = window.screen.width,
    		screenHeight = window.screen.height;

    	var sc_res = screenWidth + 'X' + screenHeight;
    	$('.ccf_sc_res').val(sc_res);
	}
	ccf_set_sc_res();
	
	function check_pro_version($elem) {

		$elem_1 = $elem.find('.powered_by');
		$elem_2 = $elem.find('.powered_by a');
		
		var creative_font_size_1 = parseInt($elem_1.css('font-size'));
		var creative_top_1 = parseInt($elem_1.css('top'));
		var creative_left_1 = parseInt($elem_1.css('left'));
		var creative_bottom_1 = parseInt($elem_1.css('bottom'));
		var creative_right_1 = parseInt($elem_1.css('right'));
		var creative_text_indent_1 = parseInt($elem_1.css('text-indent'));
		var creative_margin_top_1 = parseInt($elem_1.css('margin-top'));
		var creative_margin_bottom_1 = parseInt($elem_1.css('margin-bottom'));
		var creative_margin_left_1 = parseInt($elem_1.css('margin-left'));
		var creative_margin_right_1 = parseInt($elem_1.css('margin-right'));
		var creative_display_1 = $elem_1.css('display');
		var creative_position_1 = $elem_1.css('position');
		var creative_width_1 = parseInt($elem_1.css('width'));
		var creative_height_1 = parseInt($elem_1.css('height'));
		var creative_visibility_1 = $elem_1.css('visibility');
		var creative_overflow_1 = $elem_1.css('overflow');
		var creative_zindex_1 = parseInt($elem_1.css('z-index'));
		var creative_font_size_2 = parseInt($elem_2.css('font-size'));
		var creative_top_2 = parseInt($elem_2.css('top'));
		var creative_left_2 = parseInt($elem_2.css('left'));
		var creative_bottom_2 = parseInt($elem_2.css('bottom'));
		var creative_right_2 = parseInt($elem_2.css('right'));
		var creative_text_indent_2 = parseInt($elem_2.css('text-indent'));
		var creative_margin_top_2 = parseInt($elem_2.css('margin-top'));
		var creative_margin_right_2 = parseInt($elem_2.css('margin-right'));
		var creative_margin_bottom_2 = parseInt($elem_2.css('margin-bottom'));
		var creative_margin_left_2 = parseInt($elem_2.css('margin-left'));
		var creative_display_2 = $elem_2.css('display');
		var creative_position_2 = $elem_2.css('position');
		var creative_width_2 = parseInt($elem_2.css('width'));
		var creative_height_2 = parseInt($elem_2.css('height'));
		var creative_visibility_2 = $elem_2.css('visibility');
		var creative_overflow_2 = $elem_2.css('overflow');
		var creative_zindex_2 = parseInt($elem_2.css('z-index'));
		
		var txt1 = $.trim($elem_1.html().replace(/<[^>]+>.*?<\/[^>]+>/gi, ''));
		var txt2 = $.trim($elem_2.html().replace(/<[^>]+>.*?<\/[^>]+>/gi, ''));
		var txt1_l = parseInt(txt1.length);
		var txt2_l = parseInt(txt2.length);
		
		var a_href = $elem_2.attr("href").replace('http://','');
		a_href = a_href.replace('www.','');
		a_href = $.trim(a_href.replace('www',''));
		a_href_l = parseInt(a_href.length);
		
		if(
				creative_font_size_1 == '12' && creative_top_1 == '0' && creative_left_1 == '0' && creative_bottom_1 == '0' && creative_right_1 == '0' && creative_text_indent_1 == '0' && creative_margin_top_1 == '4' && creative_margin_right_1 == '0' && creative_margin_bottom_1 == '0' && creative_margin_left_1 == '0' && 
				creative_display_1 == 'block' && creative_position_1 == 'relative' && creative_width_1 > '20' && creative_height_1 > '10' && creative_visibility_1 == 'visible' && creative_overflow_1 == 'visible' && creative_zindex_1 == '10' && 
				creative_font_size_2 == '14' && creative_top_2 == '0' && creative_left_2 == '0' && creative_bottom_2 == '0' && creative_right_2 == '0' && creative_text_indent_2 == '0' && creative_margin_top_2 == '0' && creative_margin_right_2 == '0' && creative_margin_bottom_2 == '0' && creative_margin_left_2 == '0' && 
				creative_display_2 != 'none' && creative_position_2 == 'relative' && creative_width_2 > '20' && creative_height_2 > '10' && creative_visibility_2 == 'visible' && creative_overflow_2 == 'visible' && creative_zindex_2 == '10' && 
				txt1 != '' && txt2 == 'Creative Contact Form' && a_href == 'creative-solutions.net/joomla/creative-contact-form'
		)
			return true;
		return false;
	};
	
	var disableBlur = false;
	
    $.fn.shake = function (options) {
        // defaults
        var settings = {
            'shakes': 3,
            'distance': 10,
            'duration':300
        };
        // merge options
        if (options) {
            $.extend(settings, options);
        };
        // make it so
        var pos;
        return this.each(function () {
            $this = $(this);
            // position if necessary
            pos = $this.css('position');
            if (!pos || pos === 'static') {
                $this.css('position', 'relative');
            };
            // shake it
            for (var x = 1; x <= settings.shakes; x++) {
                $this.animate({ left: settings.distance * -1 }, (settings.duration / settings.shakes) / 4)
                    .animate({ left: settings.distance }, (settings.duration / settings.shakes) / 2)
                    .animate({ left: 0 }, (settings.duration / settings.shakes) / 4);
            };
        });
    };
    
	//function to validate name types
	 function validate_name($t,shakeEnable) {
	    var required = $t.hasClass('creativecontactform_required') ? true : false;
	    var value = $.trim( $t.val() );
	    if((!required && value == '') || value.length > 0)
	    	$t.parents('.creativecontactform_field_box').removeClass('creativecontactform_error');
		else {
			$t.parents('.creativecontactform_field_box').addClass('creativecontactform_error');
			if(shakeEnable) {
				 var form_id = $t.parents('.creativecontactform_wrapper').find(".creativecontactform_send").attr("roll");
				 var creativecontactform_shake_count = creativecontactform_shake_count_array[form_id];
				 var creativecontactform_shake_distanse = creativecontactform_shake_distanse_array[form_id];
				 var creativecontactform_shake_duration = creativecontactform_shake_duration_array[form_id];
				$t.parents('.creativecontactform_input_element').shake({'shakes': creativecontactform_shake_count,'distance': creativecontactform_shake_distanse,'duration':creativecontactform_shake_duration});
			 }
		}

		create_validation_effects($t.parents('.creativecontactform_wrapper'));
	 };
	 
	 //function to validate address types
	 function validate_address($t,shakeEnable) {
		 var required = $t.hasClass('creativecontactform_required') ? true : false;
		 var value = $.trim( $t.val() );
		 if((!required && value == '') || value.length > 0)
			 $t.parents('.creativecontactform_field_box').removeClass('creativecontactform_error');
		 else {
			 $t.parents('.creativecontactform_field_box').addClass('creativecontactform_error');
			 if(shakeEnable) {
				 var form_id = $t.parents('.creativecontactform_wrapper').find(".creativecontactform_send").attr("roll");
				 var creativecontactform_shake_count = creativecontactform_shake_count_array[form_id];
				 var creativecontactform_shake_distanse = creativecontactform_shake_distanse_array[form_id];
				 var creativecontactform_shake_duration = creativecontactform_shake_duration_array[form_id];
				$t.parents('.creativecontactform_input_element').shake({'shakes': creativecontactform_shake_count,'distance': creativecontactform_shake_distanse,'duration':creativecontactform_shake_duration});
			 }
		 }

		 create_validation_effects($t.parents('.creativecontactform_wrapper'));
	 };
	 
	 //function to validate text-input types
	 function validate_simple_text($t,shakeEnable) {
		 var required = $t.hasClass('creativecontactform_required') ? true : false;
		 var value = $.trim( $t.val() );
		 if((!required && value == '') || value.length > 0)
			 $t.parents('.creativecontactform_field_box').removeClass('creativecontactform_error');
		 else {
			 $t.parents('.creativecontactform_field_box').addClass('creativecontactform_error');
			 if(shakeEnable) {
				 var form_id = $t.parents('.creativecontactform_wrapper').find(".creativecontactform_send").attr("roll");
				 var creativecontactform_shake_count = creativecontactform_shake_count_array[form_id];
				 var creativecontactform_shake_distanse = creativecontactform_shake_distanse_array[form_id];
				 var creativecontactform_shake_duration = creativecontactform_shake_duration_array[form_id];
				$t.parents('.creativecontactform_input_element').shake({'shakes': creativecontactform_shake_count,'distance': creativecontactform_shake_distanse,'duration':creativecontactform_shake_duration});
			 }
		 }

		 create_validation_effects($t.parents('.creativecontactform_wrapper'));
	 };

 	 //function to validate datepicker types
	 function validate_datepicker($t,shakeEnable) {
		 var required = $t.hasClass('creativecontactform_required') ? true : false;
		 var value = $.trim( $t.val() );
		 if((!required && value == '') || value.length > 0)
			 $t.parents('.creativecontactform_field_box').removeClass('creativecontactform_error');
		 else {
			 $t.parents('.creativecontactform_field_box').addClass('creativecontactform_error');
			 if(shakeEnable) {
				 var form_id = $t.parents('.creativecontactform_wrapper').find(".creativecontactform_send").attr("roll");
				 var creativecontactform_shake_count = creativecontactform_shake_count_array[form_id];
				 var creativecontactform_shake_distanse = creativecontactform_shake_distanse_array[form_id];
				 var creativecontactform_shake_duration = creativecontactform_shake_duration_array[form_id];
				$t.parents('.creativecontactform_input_element').shake({'shakes': creativecontactform_shake_count,'distance': creativecontactform_shake_distanse,'duration':creativecontactform_shake_duration});
			 }
		 }

		 create_validation_effects($t.parents('.creativecontactform_wrapper'));
	 };
	 
	 //function to validate text-input types
	 function validate_text_area($t,shakeEnable) {
		 var required = $t.hasClass('creativecontactform_required') ? true : false;
		 var value = $.trim( $t.val() );
		 if((!required && value == '') || value.length > 0)
			 $t.parents('.creativecontactform_field_box').removeClass('creativecontactform_error');
		 else {
			 $t.parents('.creativecontactform_field_box').addClass('creativecontactform_error');
			 if(shakeEnable) {
				 var form_id = $t.parents('.creativecontactform_wrapper').find(".creativecontactform_send").attr("roll");
				 var creativecontactform_shake_count = creativecontactform_shake_count_array[form_id];
				 var creativecontactform_shake_distanse = creativecontactform_shake_distanse_array[form_id];
				 var creativecontactform_shake_duration = creativecontactform_shake_duration_array[form_id];
				$t.parents('.creativecontactform_input_element').shake({'shakes': creativecontactform_shake_count,'distance': creativecontactform_shake_distanse,'duration':creativecontactform_shake_duration});
			 }
		 }

		 create_validation_effects($t.parents('.creativecontactform_wrapper'));
	 };
	 
	 //function to validate name types
	 function validate_email($t,shakeEnable) {
		 var required = $t.hasClass('creativecontactform_required') ? true : false;
		 var value = $.trim( $t.val() );
		 var i = /^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i.test(value);
		 if((!required && value == '') || i)
			 $t.parents('.creativecontactform_field_box').removeClass('creativecontactform_error');
		 else {
			 $t.parents('.creativecontactform_field_box').addClass('creativecontactform_error');
			 if(shakeEnable) {
				 var form_id = $t.parents('.creativecontactform_wrapper').find(".creativecontactform_send").attr("roll");
				 var creativecontactform_shake_count = creativecontactform_shake_count_array[form_id];
				 var creativecontactform_shake_distanse = creativecontactform_shake_distanse_array[form_id];
				 var creativecontactform_shake_duration = creativecontactform_shake_duration_array[form_id];
				$t.parents('.creativecontactform_input_element').shake({'shakes': creativecontactform_shake_count,'distance': creativecontactform_shake_distanse,'duration':creativecontactform_shake_duration});
			 }
		 }

		 create_validation_effects($t.parents('.creativecontactform_wrapper'));
	 };
	 
	 //function to validate phone types
	 function validate_phone($t,shakeEnable) {
		 var required = $t.hasClass('creativecontactform_required') ? true : false;
		 var value = $.trim( $t.val() );
		 var i = /^[0-9\-\(\)\_\:\+ ]+$/i.test(value);
		 if((!required && value == '') || i)
			 $t.parents('.creativecontactform_field_box').removeClass('creativecontactform_error');
		 else {
			 $t.parents('.creativecontactform_field_box').addClass('creativecontactform_error');
			 if(shakeEnable) {
				 var form_id = $t.parents('.creativecontactform_wrapper').find(".creativecontactform_send").attr("roll");
				 var creativecontactform_shake_count = creativecontactform_shake_count_array[form_id];
				 var creativecontactform_shake_distanse = creativecontactform_shake_distanse_array[form_id];
				 var creativecontactform_shake_duration = creativecontactform_shake_duration_array[form_id];
				$t.parents('.creativecontactform_input_element').shake({'shakes': creativecontactform_shake_count,'distance': creativecontactform_shake_distanse,'duration':creativecontactform_shake_duration});
			 }
		 }

		 create_validation_effects($t.parents('.creativecontactform_wrapper'));
	 };
	 
	 //function to validate number types
	 function validate_number($t,shakeEnable) {
		 var required = $t.hasClass('creativecontactform_required') ? true : false;
		 var value = $.trim( $t.val() );
		 var i = /^[0-9]+$/i.test(value);
		 if((!required && value == '') || i)
			 $t.parents('.creativecontactform_field_box').removeClass('creativecontactform_error');
		 else {
			 $t.parents('.creativecontactform_field_box').addClass('creativecontactform_error');
			 if(shakeEnable) {
				 var form_id = $t.parents('.creativecontactform_wrapper').find(".creativecontactform_send").attr("roll");
				 var creativecontactform_shake_count = creativecontactform_shake_count_array[form_id];
				 var creativecontactform_shake_distanse = creativecontactform_shake_distanse_array[form_id];
				 var creativecontactform_shake_duration = creativecontactform_shake_duration_array[form_id];
				$t.parents('.creativecontactform_input_element').shake({'shakes': creativecontactform_shake_count,'distance': creativecontactform_shake_distanse,'duration':creativecontactform_shake_duration});
			 }
		 }

		 create_validation_effects($t.parents('.creativecontactform_wrapper'));
	 };
	 
	 //function to validate url types
	 function validate_url($t,shakeEnable) {
		 var required = $t.hasClass('creativecontactform_required') ? true : false;
		 var value = $.trim( $t.val() );
		 var i = /^(((ht|f){1}(tp:[/][/]){1})|((www.){1}))[-a-zA-Z0-9@:%_\+.~#?&\/\/=]+$/i.test(value);

		 if((!required && value == '') || i)
			 $t.parents('.creativecontactform_field_box').removeClass('creativecontactform_error');
		 else {
			 $t.parents('.creativecontactform_field_box').addClass('creativecontactform_error');
			 if(shakeEnable) {
				 var form_id = $t.parents('.creativecontactform_wrapper').find(".creativecontactform_send").attr("roll");
				 var creativecontactform_shake_count = creativecontactform_shake_count_array[form_id];
				 var creativecontactform_shake_distanse = creativecontactform_shake_distanse_array[form_id];
				 var creativecontactform_shake_duration = creativecontactform_shake_duration_array[form_id];
				$t.parents('.creativecontactform_input_element').shake({'shakes': creativecontactform_shake_count,'distance': creativecontactform_shake_distanse,'duration':creativecontactform_shake_duration});
			 }
		 }

		 create_validation_effects($t.parents('.creativecontactform_wrapper'));
	 };
	 
	 //function to validate select types
	 function validate_select($t,shakeEnable) {
		 var required = $t.hasClass('creativecontactform_required') ? true : false;
		 $t.prev('select').addClass('sss');
		 var value = '';
		 $t.prev('select').find('option').each(function() {
			 var sel = $(this).attr('selected');
			 if(sel == 'selected')
				 value = $(this).val();
		 });
		 if(!required || value !='creative_empty')
			 $t.parents('.creativecontactform_field_box').removeClass('creativecontactform_error');
		 else {
			 $t.parents('.creativecontactform_field_box').addClass('creativecontactform_error');
			 if(shakeEnable) {
				 var form_id = $t.parents('.creativecontactform_wrapper').find(".creativecontactform_send").attr("roll");
				 var creativecontactform_shake_count = creativecontactform_shake_count_array[form_id];
				 var creativecontactform_shake_distanse = creativecontactform_shake_distanse_array[form_id];
				 var creativecontactform_shake_duration = creativecontactform_shake_duration_array[form_id];
				 $t.shake({'shakes': creativecontactform_shake_count,'distance': creativecontactform_shake_distanse,'duration':creativecontactform_shake_duration});
			 }
		 }

		 create_validation_effects($t.parents('.creativecontactform_wrapper'));
	 };
	 
	 //function to validate multiple select types
	 function validate_multiple_select($t,shakeEnable) {
		 var required = $t.hasClass('creativecontactform_required') ? true : false;
		 var selected = $t.prev('select').find('option:first-child').attr("selected");
		 
		 var has_option = selected == 'selected' ? false : true;
		 
		 if(!required || has_option)
			 $t.parents('.creativecontactform_field_box').removeClass('creativecontactform_error');
		 else {
			 $t.parents('.creativecontactform_field_box').addClass('creativecontactform_error');
			 if(shakeEnable) {
				 var form_id = $t.parents('.creativecontactform_wrapper').find(".creativecontactform_send").attr("roll");
				 var creativecontactform_shake_count = creativecontactform_shake_count_array[form_id];
				 var creativecontactform_shake_distanse = creativecontactform_shake_distanse_array[form_id];
				 var creativecontactform_shake_duration = creativecontactform_shake_duration_array[form_id];
				 $t.shake({'shakes': creativecontactform_shake_count,'distance': creativecontactform_shake_distanse,'duration':creativecontactform_shake_duration});
			 }
		 }

		 create_validation_effects($t.parents('.creativecontactform_wrapper'));
	 };
	 
	 //function to validate radio types
	 function validate_radio($t,shakeEnable) {
		 var required = $t.hasClass('creativecontactform_required') ? true : false;
		 var checked_count = 0;
		 $t.find('input.creative_ch_r_element').each(function() {
			 if($(this).attr('checked') == 'checked')
				 checked_count ++;
		 });
		 
		 if(!required || checked_count == 1)
			 $t.removeClass('creativecontactform_error');
		 else {
			 $t.addClass('creativecontactform_error');
			 if(shakeEnable) {
				 var form_id = $t.parents('.creativecontactform_wrapper').find(".creativecontactform_send").attr("roll");
				 var creativecontactform_shake_count = creativecontactform_shake_count_array[form_id];
				 var creativecontactform_shake_distanse = creativecontactform_shake_distanse_array[form_id];
				 var creativecontactform_shake_duration = creativecontactform_shake_duration_array[form_id];
				 $t.find('.answer_input').shake({'shakes': creativecontactform_shake_count,'distance': creativecontactform_shake_distanse,'duration':creativecontactform_shake_duration});
			 }
		 }

		 create_validation_effects($t.parents('.creativecontactform_wrapper'));
	 };
	 
	 //function to validate checkbox types
	 function validate_checkbox($t,shakeEnable) {
		 var required = $t.hasClass('creativecontactform_required') ? true : false;
		 var checked_count = 0;
		 $t.find('input.creative_ch_r_element').each(function() {
			 if($(this).attr('checked') == 'checked')
				 checked_count ++;
		 });
		 if(!required || checked_count >= 1)
			 $t.removeClass('creativecontactform_error');
		 else {
			 $t.addClass('creativecontactform_error');
			 if(shakeEnable) {
				 var form_id = $t.parents('.creativecontactform_wrapper').find(".creativecontactform_send").attr("roll");
				 var creativecontactform_shake_count = creativecontactform_shake_count_array[form_id];
				 var creativecontactform_shake_distanse = creativecontactform_shake_distanse_array[form_id];
				 var creativecontactform_shake_duration = creativecontactform_shake_duration_array[form_id];
				 $t.find('.answer_input').shake({'shakes': creativecontactform_shake_count,'distance': creativecontactform_shake_distanse,'duration':creativecontactform_shake_duration});
			 }
		 }

		 create_validation_effects($t.parents('.creativecontactform_wrapper'));
	 };
	 
	 //function to validate file upload
	 function validate_file_upload($t,shakeEnable) {
		 var required = $t.hasClass('creativecontactform_required') ? true : false;
		 var uploads_count = $t.find('.creative_active_upload').length;
		 if(!required || uploads_count >= 1)
			 $t.removeClass('creativecontactform_error');
		 else {
			 $t.addClass('creativecontactform_error');
			 if(shakeEnable) {
				 var form_id = $t.parents('.creativecontactform_wrapper').find(".creativecontactform_send").attr("roll");
				 var creativecontactform_shake_count = creativecontactform_shake_count_array[form_id];
				 var creativecontactform_shake_distanse = creativecontactform_shake_distanse_array[form_id];
				 var creativecontactform_shake_duration = creativecontactform_shake_duration_array[form_id];
				 $t.find('.creative_fileupload').shake({'shakes': creativecontactform_shake_count,'distance': creativecontactform_shake_distanse,'duration':creativecontactform_shake_duration});
			 }
		 }

		 create_validation_effects($t.parents('.creativecontactform_wrapper'));
	 };
	 
	 //function to validate captcha
	 function validate_captcha($t,shakeEnable) {
		 var required = $t.hasClass('creativecontactform_required') ? true : false;
		 var captcha_length = $.trim($t.val()).length;
		 if(!required || captcha_length >= 3)
			 $t.parents('.creativecontactform_field_box').removeClass('creativecontactform_error');
		 else {
			 $t.parents('.creativecontactform_field_box').addClass('creativecontactform_error');
			 if(shakeEnable) {
				 var form_id = $t.parents('.creativecontactform_wrapper').find(".creativecontactform_send").attr("roll");
				 var creativecontactform_shake_count = creativecontactform_shake_count_array[form_id];
				 var creativecontactform_shake_distanse = creativecontactform_shake_distanse_array[form_id];
				 var creativecontactform_shake_duration = creativecontactform_shake_duration_array[form_id];
				 $t.parents('.creativecontactform_input_element').shake({'shakes': creativecontactform_shake_count,'distance': creativecontactform_shake_distanse,'duration':creativecontactform_shake_duration});
			 }
		 }

		 create_validation_effects($t.parents('.creativecontactform_wrapper'));
	 };

 	 //function to validate google recaptcha
	 function validate_ccf_recaptcha($t,shakeEnable) {
		 var captcha_tested = parseInt($t.val());
		 if(captcha_tested == 1)
			 $t.parents('.creativecontactform_field_box').removeClass('creativecontactform_error');
		 else {
			 $t.parents('.creativecontactform_field_box').addClass('creativecontactform_error');
			 if(shakeEnable) {
				 var form_id = $t.parents('.creativecontactform_wrapper').find(".creativecontactform_send").attr("roll");
				 var creativecontactform_shake_count = creativecontactform_shake_count_array[form_id];
				 var creativecontactform_shake_distanse = creativecontactform_shake_distanse_array[form_id];
				 var creativecontactform_shake_duration = creativecontactform_shake_duration_array[form_id];
				 $t.prev('.ccf_recaptcha_wrapper').shake({'shakes': creativecontactform_shake_count,'distance': creativecontactform_shake_distanse,'duration':creativecontactform_shake_duration});
			 }
		 }

		 // create_validation_effects($t.parents('.creativecontactform_wrapper'));
	 };
			
	function creativecontactform_make_validation($c) {
		
		//validate name types
		$c.parents('.creativecontactform_wrapper').find(".creative_name").each(function() {
			validate_name($(this),true);
		});
		
		//validate email types
		$c.parents('.creativecontactform_wrapper').find(".creative_email").each(function() {
			validate_email($(this),true);
		});
		
		//validate address types
		$c.parents('.creativecontactform_wrapper').find(".creative_address").each(function() {
			validate_address($(this),true);
		});
		
		//validate text-input types
		$c.parents('.creativecontactform_wrapper').find(".creative_text-input").each(function() {
			validate_simple_text($(this),true);
		});
		
		//validate phone types
		$c.parents('.creativecontactform_wrapper').find(".creative_phone").each(function() {
			validate_phone($(this),true);
		});
		
		//validate text area types
		$c.parents('.creativecontactform_wrapper').find(".creative_text-area").each(function() {
			validate_text_area($(this),true);
		});
		
		//validate number types
		$c.parents('.creativecontactform_wrapper').find(".creative_number").each(function() {
			validate_number($(this),true);
		});
		
		//validate number types
		$c.parents('.creativecontactform_wrapper').find(".creative_url").each(function() {
			validate_url($(this),true);
		});
		
		//validate select
		$c.parents('.creativecontactform_wrapper').find(".single_select").each(function() {
			validate_select($(this),true);
		});
		
		//validate multiple select
		$c.parents('.creativecontactform_wrapper').find(".multiple_select").each(function() {
			validate_multiple_select($(this),true);
		});
		
		//validate radio
		$c.parents('.creativecontactform_wrapper').find(".creative_radio").each(function() {
			validate_radio($(this),true);
		});
		
		//validate checkbox
		$c.parents('.creativecontactform_wrapper').find(".creative_checkbox").each(function() {
			validate_checkbox($(this),true);
		});
		
		//validate file upload
		$c.parents('.creativecontactform_wrapper').find(".creative_file-upload").each(function() {
			validate_file_upload($(this),true);
		});
		
		//validate captcha
		$c.parents('.creativecontactform_wrapper').find("input.creative_captcha").each(function() {
			validate_captcha($(this),true);
		});

		//validate google recaptchacaptcha
		$c.parents('.creativecontactform_wrapper').find("input.ccf_recaptcha").each(function() {
			validate_ccf_recaptcha($(this),true);
		});

		//validate datepicker
		$c.parents('.creativecontactform_wrapper').find("input.creative_datepicker").each(function() {
			validate_datepicker($(this),true);
		});

		create_validation_effects($c.parents('.creativecontactform_wrapper'));
	};
	
	$('.creativecontactform_send').click(function() {
		var form_id = $(this).attr("roll");
		
		if(!check_pro_version($(this).parents('.creativecontactform_wrapper'))) {
			// ccf_make_alert('Please upgrade to PRO version to hide the backlink','creative_error',form_id);
			return false;
		};
		
		//animate loading
		var loading_element = $(this).parents('.creativecontactform_wrapper').find('.creativecontactform_loading_wrapper');
		var pre_element = $(this).parents('.creativecontactform_wrapper').find('.creativecontactform_pre_text');
		var send_button = $(this).parents('.creativecontactform_wrapper').find('.creativecontactform_send');
		var send_new_button = $(this).parents('.creativecontactform_wrapper').find('.creativecontactform_send_new');
		var captcha_text = $(this).parents('.creativecontactform_wrapper').find('.creative_captcha_info').html();
		$wrapper_element = $(this).parents('.creativecontactform_wrapper');
		var recaptcha_text = 'Error validating reCAPTCHA';
		var $captcha_reload_elemen = $(this).parents('.creativecontactform_wrapper').find('.reload_creative_captcha');

		creativecontactform_make_validation($(this));
		var errors_count = parseInt($(this).parents('.creativecontactform_wrapper').find('.creativecontactform_error').length);
		if(errors_count != 0) {
			var $first_errored = $(this).parents('.creativecontactform_wrapper').find('.creativecontactform_error:first').find('input');
			$first_errored.addClass('mouseentered');
			$first_errored.focus();
			setTimeout(function() {
				$first_errored.trigger('mousedown');
			},500);
		}
		else {
			animate_loading_start(loading_element);
			var creativecontactform_data = $(this).parents('form').serialize();
			console.log(creativecontactform_data);

			$('.will_be_creative_select').find('option').each(function() {
				// alert($(this).attr("selected"));
			})
			//send request
			$.ajax
			({
				url: creativecontactform_path + 'sendmail.php',
				type: "post",
				data: creativecontactform_data,
				dataType: "json",
				success: function(data)
				{
					if(data[0].invalid == 'invalid_token') {
						ccf_make_alert('Invalid Token','creative_error',form_id);
						animate_loading_end(loading_element);
						return;
					}
					else if(data[0].invalid == 'invalid_captcha') {
						ccf_make_alert(captcha_text,'creative_error',form_id);
						$captcha_reload_elemen.trigger('click');
						animate_loading_end(loading_element);
						return;
					}				
					else if(data[0].invalid == 'invalid_recaptcha') {
						ccf_make_alert(recaptcha_text,'creative_error',form_id);
						animate_loading_end(loading_element);

						// reset recaptcha
						var rcp_id = $wrapper_element.find('.creative_timing_google-recaptcha').attr("rcp_id");
						grecaptcha.reset(rcp_id);
						$wrapper_element.find('.ccf_recaptcha').val("0");
						return;
					};
					
					//animate back
					animate_loading_end(loading_element);
					
					//replace buttons 
					send_new_button.removeClass('creativecontactform_hidden');
					send_button.addClass('creativecontactform_hidden');
					
					//show thank you text
					if(creativecontactform_thank_you_text_array[form_id] != '') {
						ccf_make_alert(creativecontactform_thank_you_text_array[form_id],'creative_success',form_id);
					};
					
					//generates console info
					var l = data[0].info.length;
					for(var i  = 0;i <= l; i++) {
						if(data[0].info[i] != undefined)
							console.log(data[0].info[i])
					};
					
					//check redirect
					if(creativecontactform_redirect_enable_array[form_id] == 1) {
						if(creativecontactform_redirect_array[form_id] != '') {
							setTimeout(function() {
								window.location.href = creativecontactform_redirect_array[form_id];
							},parseInt(creativecontactform_redirect_delay_array[form_id]));
						}
					};

				},
				error: function(xhr, status, error)
				{
					//var err = eval("(" + xhr.responseText + ")");
					console.log(xhr.responseText);
					ccf_make_alert('Server error','creative_error',form_id);
					animate_loading_end(loading_element);
				}
			});
		}
	});
			
	$('.creativecontactform_send_new').click(function() {
		var form_id = $(this).attr("roll");
		
		var $wrapper = $(this).parents('.creativecontactform_wrapper');
		var loading_element = $(this).parents('.creativecontactform_wrapper').find('.creativecontactform_loading_wrapper');
		var pre_element = $(this).parents('.creativecontactform_wrapper').find('.creativecontactform_pre_text');
		var creativecontactform_field_box = $(this).parents('.creativecontactform_wrapper').find('.creativecontactform_field_box');
		var send_button = $(this).parents('.creativecontactform_wrapper').find('.creativecontactform_send');
		var send_new_button = $(this).parents('.creativecontactform_wrapper').find('.creativecontactform_send_new');
		var creativecontactform_input_element  = $(this).parents('.creativecontactform_wrapper').find('.creative_input_reset');
		var creativecontactform_textarea_element  = $(this).parents('.creativecontactform_wrapper').find('.creative_textarea_reset');
		var $captcha_reload_elemen = $(this).parents('.creativecontactform_wrapper').find('.reload_creative_captcha');
		
		animate_loading_start(loading_element);
		$.ajax
		({
			url: creativecontactform_path + 'sendmail.php',
			type: "get",
			data: 'get_token=1',
			success: function(data)
			{
				//animate back
				animate_loading_end(loading_element);
				
				//set token
				$('.creativecontactform_token').attr("name",data);
				
				//replace buttons
				send_new_button.addClass('creativecontactform_hidden');
				send_button.removeClass('creativecontactform_hidden');
				
				//clear inputs
				creativecontactform_input_element.val('');
				creativecontactform_textarea_element.val('');
				
				//set predefined values
				$wrapper.find('.creative_name').each(function() {
					var p_v = $(this).attr("pre_value");
					if(p_v != '')
						$(this).val(p_v);
				});
				$wrapper.find('.creative_email').each(function() {
					var p_v = $(this).attr("pre_value");
					if(p_v != '')
						$(this).val(p_v);
				});
				
				//clear errors
				setTimeout(function() {
					$wrapper.find('.creativecontactform_error').removeClass('creativecontactform_error');
				},150);
				
				//checkboxes, radiobuttons
				var form_checked_ids = Array();
				$wrapper.find('.creativeform_twoglux_styled').each(function() {
					var $this = $(this);
					var id = $this.attr("id");
					var checked = $this.attr("pre_val");
					
					var coming_id = 'creativeform_twoglux_styled_' + id;
					if(checked == 'checked')
						form_checked_ids.push(coming_id);
					
					$this.removeAttr("checked");
				});
				
				$wrapper.find('.creativeform_twoglux_styled_checkbox.twoglux_checked').each(function() {
					$(this).trigger("mousedown");
					$(this).trigger("mouseup");
				});
				$wrapper.find('.creativeform_twoglux_styled_radio.twoglux_checked').each(function() {
					$(this).removeClass('twoglux_checked').find('.radio_part1').css('opacity',0);
				});
				
				setTimeout(function() {
					for(tt in form_checked_ids) {
						var id = form_checked_ids[tt];
						if(typeof(id) !== "function") {
							$("#" + id).trigger("mousedown");
							$("#" + id).trigger("mouseup");
						}
					}
				},100);
				
				//creative select, multiple select
				
				//get pre selected values
				var form_selected_option_ids = Array();
				$wrapper.find('.will_be_creative_select').each(function() {
					  var $this = $(this);
					  $this.find('option').each(function(i) {
						  var $opt = $(this);
						  var opt_id = $opt.attr("id");
						  var selected = $opt.attr('pre_val');
						  if(selected == 'selected')
							  form_selected_option_ids.push(opt_id);
					  });
				});
				setTimeout(function() {
					for(var tt in form_selected_option_ids) {
						if(typeof(form_selected_option_ids[tt]) !== "function") {
							$("#sel_" + form_selected_option_ids[tt]).trigger("click");
						}
					}
				},100);
				
				//reset select types
				$wrapper.find('.ccf_close_icon').each(function() {
					  allow_add_closed = false;
					  $(this).parents('.creative_select').prev('select').find('option').removeAttr('selected');
					  $(this).parents('.creative_select').prev('select').find('.def_value').attr('selected','selected');
					  var def_val = $(this).parents('.creative_select').prev('select').find('.def_value').html();
					  $(this).parents('.creative_select').find('.creative_selected_option').html(def_val);
					  $(this).parents('.creative_select').find('.creative_select_option').removeClass('selected');
					  $(this).hide();
				});
				//reset multiple select types
				$wrapper.find('.multiple_select.creative_select').each(function() {
					$(this).prev('select').find('option').removeAttr('selected');
					$(this).prev('select').find('.def_value').attr('selected','selected');
					var def_val = $(this).prev('select').find('.def_value').html();
					$(this).find('.creative_selected_option').html(def_val);
				});
				
				//reset search
				$wrapper.find('.creative_search_input').each(function() {
					$(this).val('').trigger('keyup');
				});
				
				//reset uploads
				$wrapper.find('.creative_uploaded_files').html('');
				
				//reload captcha(s)
				$wrapper.find('.reload_creative_captcha').trigger('click');

				// reset recaptcha
				if($wrapper.find('.ccf_recaptcha').val() == 1) {
					$wrapper.find('.ccf_recaptcha').val("0");
					var rcp_id = $wrapper.find('.creative_timing_google-recaptcha').attr("rcp_id");
					grecaptcha.reset(rcp_id);
				}
				
			},
			error: function()
			{
				ccf_make_alert('Error','creative_error',form_id);
				$captcha_reload_elemen.trigger('click');
				animate_loading_end(loading_element);
			}
		});
	});
	
	function animate_loading_start($elem) {
		$elem
		.css({opacity:0,display:'block'})
		.stop()
		.animate({
			opacity: 1
		},400);
	};
	function animate_loading_end($elem) {
		$elem
		.stop()
		.animate({
			opacity: 0
		},400,function(){
			$(this).hide();
		});
	};
	
	//blur validation
	$(".creative_name").blur(function() {
		validate_name($(this),false);
	});	
	$(".creative_datepicker").blur(function() {
		//validate_datepicker($(this),false);
	});
	$(".creative_email").blur(function() {
		validate_email($(this),false);
	});
	$(".creative_address").blur(function() {
		validate_address($(this),false);
	});
	$(".creative_text-input").blur(function() {
		validate_simple_text($(this),false);
	});
	$(".creative_phone").blur(function() {
		validate_phone($(this),false);
	});
	$(".creative_text-area").blur(function() {
		validate_text_area($(this),false);
	});
	$(".creative_number").blur(function() {
		validate_number($(this),false);
	});
	$(".creative_url").blur(function() {
		validate_url($(this),false);
	});
	$("input.creative_captcha").blur(function() {
		validate_captcha($(this),false);
	});
	
	$('.creativecontactform_input_element input,.creativecontactform_input_element textarea').focus(function() {
		$(this).parents('.creativecontactform_input_element').addClass('focused');
	});
	$('.creativecontactform_input_element input,.creativecontactform_input_element textarea').blur(function() {
		$(this).parents('.creativecontactform_input_element').removeClass('focused');
	});
	
///////////////////////////////////////////////////////////////Creative Checkboxes/////////////////////////////////////////////////////////////////////////////////
	var checked_ids = Array();
	$('.creativeform_twoglux_styled').each(function() {
		var $this = $(this);
		var type = $this.attr("type");
		var color = $this.attr("data-color");
		var name = $this.attr("name");
		var id = $this.attr("id");
		var uniq_index = $this.attr("uniq_index");
		var checked = $this.attr("pre_val");
		
		var coming_id = 'creativeform_twoglux_styled_' + id;
		if(checked == 'checked')
			checked_ids.push(coming_id);
		$this.wrap('<div class="creativeform_twoglux_styled_input_wrapper" />');
		 
		if(type == 'radio')
			 var inner_img_html = '<div class="radio_part1 ' + color + '_radio_part1 unselectable" >&nbsp;</div>';
		else
			 var inner_img_html = '<div class="checkbox_part1 ' + color + '_checkbox_part1 unselectable" >&nbsp;</div><div class="checkbox_part2 ' + color + '_checkbox_part2 unselectable">&nbsp;</div>';
			 
		var creativeform_twoglux_styled_html = '<a id="' + coming_id + '" class="creativeform_twoglux_styled_element creativeform_twoglux_styled_' + color + ' creativeform_twoglux_styled_' + type + ' unselectable a_' + uniq_index + '">' + inner_img_html + '</a>';
			 
		$this.after(creativeform_twoglux_styled_html);
		 
		$this.hide();
	  });
	
	setTimeout(function() {
		for(tt in checked_ids) {
			var id = checked_ids[tt];
			if(typeof(id) !== "function") {
				$("#" + id).trigger("mousedown");
				$("#" + id).trigger("mouseup");
			}
		}
	},200);
	  
	  $('.creativeform_twoglux_styled_element').on('mouseenter', function() {
		  make_mouseenter($(this));
	  });
	  
	   $('.creativeform_twoglux_styled_element').on('mouseleave', function() {
		   make_mouseleave($(this))
	  });
	  
	  function make_mouseenter($elem) {
		  if($elem.hasClass('creativeform_twoglux_styled_radio'))
			  $elem.addClass('creativeform_twoglux_styled_radio_hovered');
		  else
			  $elem.addClass('creativeform_twoglux_styled_checkbox_hovered');
	  };
	  function make_mouseleave($elem) {
		  if($elem.hasClass('creativeform_twoglux_styled_radio'))
			  $elem.removeClass('creativeform_twoglux_styled_radio_hovered');
		  else
			  $elem.removeClass('creativeform_twoglux_styled_checkbox_hovered');
	  };
	  
	  var creativeanimatetime = 200;
	  var last_event = 'up';
	  var last_event_radio = 'up';
	  var body_mouse_up_enabled = false;
	  
	  //////////////////////////////////////////////////////////////////////MOVE FUNCTIONS////////////////////////////////////////
	  function animate_checkbox1_down($elem) {
		  $elem.animate({height: 9},creativeanimatetime);
	  };
	  function animate_checkbox1_up($elem) {
		  //uncheck element
		  $elem.parent('a').removeClass('twoglux_checked');
		  $elem.parent('a').prev('input').attr("checked",false);
		  
		  $elem.animate({height: 0},creativeanimatetime);
		  
	  };
	  function animate_checkbox2_up($elem) {
		  $elem.animate({height: 12},creativeanimatetime);
		  
		  //check element
		  $elem.parent('a').addClass('twoglux_checked');
		  $elem.parent('a').prev('input').attr("checked",true);
		  
	  };
	  function animate_checkbox2_down($elem) {
		  $elem.animate({height: 0},creativeanimatetime);
	  };
	  
	  //////////////////////////////////////////////////////////////////////MOUSEDOWN////////////////////////////////////////
	  $('.creativeform_twoglux_styled_checkbox').on('mousedown',function() {
		  //check if checkbox checked
		  if($(this).hasClass('twoglux_checked'))
		  	animate_checkbox2_down($(this).find('.checkbox_part2'));
		  else
		  	animate_checkbox1_down($(this).find('.checkbox_part1'));
		  
		  last_event = 'down';
		  body_mouse_up_enabled = true;
	  });
	  //////////////////////////////////////////////////////////////////////MOUSEUP//////////////////////////////////////////
	  $('.creativeform_twoglux_styled_checkbox').on('mouseup',function() {
		  if(last_event == 'down') {
			  //check if checkbox checked
			  if($(this).hasClass('twoglux_checked'))
			  	animate_checkbox1_up($(this).find('.checkbox_part1'));
			  else
			  	animate_checkbox2_up($(this).find('.checkbox_part2'));
		  }
		  
		  last_event = 'up';
		  body_mouse_up_enabled = false;
		  
		  validate_checkbox($(this).parents('.creative_checkbox'),false);
	  });
	  
	  //////////////////////////////////////////////////////////RADIOBUTTONS//////////////////////////////////////////////////////////////
	  $('.radio_part1').css('opacity','0');
	  $('.creativeform_twoglux_styled_radio').on('mousedown',function() {
		  //check if checkbox checked
		  if(!($(this).hasClass('twoglux_checked'))) {
			  $(this).find('.radio_part1').fadeTo(creativeanimatetime, 0.5);
		  }
		  
		  last_event_radio = 'down';
		  body_mouse_up_enabled = true;
	  });
	  $('.creativeform_twoglux_styled_radio').on('mouseup',function() {
		  if(last_event_radio == 'down') {
		  //check if checkbox checked
			  if(!($(this).hasClass('twoglux_checked'))) {
				  $(this).addClass('twoglux_checked');
				  var uniq_index = $(this).prev('input').attr("uniq_index");
				  $('.' + uniq_index).removeAttr("checked");
				  
				  $(this).prev('input').attr("checked",true);
				  
				  //fixing strange bug with predefined radio value and riggering click event
				  var val = $(this).prev('input').val();
				  var name = $(this).prev('input').attr("name");
				  name = name.replace('remove_this_part','');
				  $(this).parents('.creative_radio').find('.bug_fixer').remove();
				  var radio_new_val_input_html = '<input class="bug_fixer" type="hidden" name="' + name + '" value="' + val + '" />';
				  $(this).parents('.creative_radio').find('.creativecontactform_field_name').after(radio_new_val_input_html);
				  
				  
				  $('.a_' + uniq_index).removeClass('twoglux_checked');
				  $(this).addClass('twoglux_checked');
				  
				  $('.a_' + uniq_index).not($(this)).find('.radio_part1').fadeTo(creativeanimatetime, 0);
				  $(this).find('.radio_part1').fadeTo(creativeanimatetime, 1);
			  }
		  };
		  
		  last_event_radio = 'up';
		  body_mouse_up_enabled = false;
		  
		  validate_radio($(this).parents('.creativecontactform_field_box'),false);
	  });
	  //////////////////////////////////////////////////////////////OTHER////////////////////////////////////////////////////////////////////////////////
	  //fixing bug in firefox
	  $('.creativeform_twoglux_styled_input_wrapper').bind("dragstart", function() {
		     return false;
		});
	  $("body").on('mouseup',function() {
		  if(body_mouse_up_enabled) {
			  //checkbox
			  var $elems = $('.creativeform_twoglux_styled_element').not('.twoglux_checked').find('.checkbox_part1');
			  animate_checkbox1_up($elems);
			  
			  var $elems = $('.creativeform_twoglux_styled_element.twoglux_checked').find('.checkbox_part2');
			  animate_checkbox2_up($elems);
			  
			  var $elems = $('.creativeform_twoglux_styled_element').not('.twoglux_checked').find('.radio_part1');
			  $elems.fadeTo(creativeanimatetime, 0);
		  }
	  });
	  
	  //trigger events for label
	  $('.twoglux_label').on('mouseenter', function() {
			var uniq_index = $(this).attr("uniq_index");
			make_mouseenter($("#creativeform_twoglux_styled_" + uniq_index));
	  });
	  $('.twoglux_label').on('mouseleave',function() {
			var uniq_index = $(this).attr("uniq_index");
			make_mouseleave($("#creativeform_twoglux_styled_" + uniq_index));
	  });
	  $('.twoglux_label').on('mousedown',function(e) {
	  		if($(e.target).hasClass('ccf_popup_link'))
	  			return;
			var uniq_index = $(this).attr("uniq_index");
			$("#creativeform_twoglux_styled_" + uniq_index).trigger("mousedown");
	  });
	  $('.twoglux_label').on('mouseup',function(e) {
	  		if($(e.target).hasClass('ccf_popup_link'))
	  			return;
			var uniq_index = $(this).attr("uniq_index");
			$("#creativeform_twoglux_styled_" + uniq_index).trigger("mouseup");
	  });
	
	  //////////////////////////////////////////////////////////////CREATIVE SELECT////////////////////////////////////////////////////////////////////////////////
	  var selected_option_ids = Array();
	  $('.will_be_creative_select').each(function() {
		  var $this = $(this);
		  var multiple = $this.attr("multiple");
		  var creative_def_val = '';
		  var req_class = $(this).hasClass("creativecontactform_required") ? 'creativecontactform_required' : '';
		  var special_width = $this.attr("special_width");
		  var special_width_rule = special_width != '' ? 'style="width: ' + special_width + ' !important"' : '';
		  var select_no_match_text =$this.attr("select_no_match_text");
		  var show_search = $this.attr("show_search") == 'show' ? '' : 'style="display: none"';
		  var show_scroll_after = parseInt($this.attr("scroll_after"));
		  
		  var select_type = multiple == 'multiple' ? 'multiple_select' : 'single_select';
		  
		  var form_id = $(this).parents('.creativecontactform_wrapper').find('.creativecontactform_send').attr('roll');
		  
		  //get options creative html
		  var inner_htm = '<div class="creative_options_wrapper wrapper_of_' +  select_type + '"><div '+ show_search +' class="creative_search"><input class="creative_search_input" type="text" value="" /><span class="creative_search_icon">&nbsp;</span></div><div class="creative_scrollbar creative_scoll_inner_wrapper"><div class="creative_scrollbar_mask creative_scroll_inner"><div class="creative_select_empty_option do_not_hide_onclcik">' + select_no_match_text + ' "<span class="do_not_hide_onclcik"></span>"</div>';
		  $this.find('option').each(function(i) {
			  var $opt = $(this);
			  var htm = $opt.html();
			  var opt_id = $opt.attr("id");
			  var val = $opt.val();
			  if(val == 'creative_empty')
				  creative_def_val = htm;
			  if(val != 'creative_empty')
				  inner_htm += '<div id="sel_' + opt_id + '" opt_id="' + opt_id + '" class="creative_select_option option_of_' + select_type + '"><span class="creative_option_state wrapper_of_' +  select_type + '">&nbsp;</span><span class="creative_opt_value wrapper_of_' +  select_type + '">' + htm + '</span></div>';
			  
			  var selected = $opt.attr('selected');
			  if(selected == 'selected')
				  selected_option_ids.push(opt_id);
		  });
		  inner_htm += '</div><div class="creative_scrollbar_draggable"><a href="#" class="draggable"></a></div><div class="creative_clear"></div></div></div>';
		  
		  var creative_select_html = '<div class="creative_select creativecontactform_input_element ' + select_type + ' '+ req_class +'"><div class="ccf_select_icon closed"></div><div class="ccf_close_icon"></div><div class="creative_input_dummy_wrapper creative_selected_option">' + creative_def_val + '</div>' + inner_htm + '</div>';
		  
		  $this.after(creative_select_html);
			 
		  $this.hide();
	  });
	  
	 	setTimeout(function() {
			$(".creativecontactform_wrapper").each(function() {
				var scrollbar_popup_style = $(this).attr("scrollbar_popup_style");
				var scrollbar_content_style = $(this).attr("scrollbar_content_style");

				$(".creative_scrollbar").mCustomScrollbar({
					mouseWheel:{
						enable:true
					},
					scrollButtons:{
						enable:true
					},
					theme:scrollbar_popup_style
				});				
				$(".creative_content_scrollbar").mCustomScrollbar({
					mouseWheel:{
						enable:true
					},
					scrollButtons:{
						enable:true
					},
					theme:scrollbar_content_style
				});
			});
		},150);

	  setTimeout(function() {
		  $('.creative_select').each(function() {
			  generate_max_height($(this));
		  });
	  },50);
	  
	  function generate_max_height($elem) {
		  var show_scroll_after = parseInt($elem.prev('select').attr("scroll_after"));
		  $elem.find('.creative_options_wrapper').css({'visibility':'hidden','display':'block'});
		  
		  var h = $elem.find('.creative_select_option:first').height();
		  var p_top = parseFloat($elem.find('.creative_select_option:first').css('padding-top'));
		  var p_bottom = parseFloat($elem.find('.creative_select_option:first').css('padding-bottom'));
		  var total_h = show_scroll_after * (h + 1*p_top + 1*p_bottom);
		  $elem.find('.creative_scoll_inner_wrapper').css({'max-height':total_h});
		  
		  $elem.find('.creative_options_wrapper').css({'visibility':'visible','display':'none'});
	  };
	  
	  
	  setTimeout(function() {
		  for(var tt in selected_option_ids) {
			  if(typeof(selected_option_ids[tt]) !== "function") {
				  $("#sel_" + selected_option_ids[tt]).trigger("click");
			  }
		  }
	  },10);
	  
	  $("body").click(function(e) {
		  if(! $(e.target).hasClass('creative_selected_option') && ! $(e.target).hasClass('ccf_close_icon') && ! $(e.target).hasClass('option_of_multiple_select') && ! $(e.target).hasClass('wrapper_of_multiple_select') && ! $(e.target).hasClass('ccf_select_icon') && ! $(e.target).hasClass('creative_search') && ! $(e.target).hasClass('creative_search_input') && ! $(e.target).hasClass('do_not_hide_onclcik') && ! $(e.target).hasClass('mCSB_buttonDown') && ! $(e.target).hasClass('mCSB_buttonUp') && ! $(e.target).hasClass('mCSB_dragger_bar') && ! $(e.target).hasClass('mCSB_draggerContainer') && ! $(e.target).hasClass('mCustomScrollBox') && ! $(e.target).hasClass('mCSB_dragger') && ! $(e.target).hasClass('mCSB_draggerRail') && ! $(e.target).hasClass('creative_scrollbar')) {   
			  close_creative_options($('.creative_select'));
		  }
	  });
	  var allow_add_closed = true;
	  $('.creative_select .creative_input_dummy_wrapper,.creative_select .ccf_select_icon').on('click', function() {
		  var $elem = $(this).parents('.creative_select');
		  if($elem.hasClass('opened')) {
			  allow_add_closed = false;
			  close_creative_options($elem);
		  }
		  else {
			  $('.creative_select.opened').removeClass('focused').removeClass('opened').find('.ccf_select_icon').removeClass('opened').addClass('closed').parents('.creative_select').find('.creative_options_wrapper').hide();
			  open_creative_options($elem);

			  $('.creative_search_input').focus();
		  }
	  });
	  
	  $(window).scroll(function() {
		  replace_creative_optios_wrapper($('.creative_select.opened'));
	  });
	  $(window).resize(function() {
		  replace_creative_optios_wrapper($('.creative_select.opened'));
	  });
	  
	  function replace_creative_optios_wrapper($elem) {
		  var offset = $elem.offset();
		  if(offset == null)
			  return;
		  var offset_top = parseFloat(offset.top);
		  var scroll_top = parseFloat($(window).scrollTop());
		  var w_h = parseFloat($(window).height());
		  var offset_bottom = w_h - 33 - (offset_top - scroll_top);
		  
		  if($elem.find('.creative_options_wrapper').css('display') == 'none') {
			  $elem.find('.creative_options_wrapper').css({'visibility':'hidden','display':'block'});
			  var elem_h = parseFloat($elem.find('.creative_options_wrapper').height()) + 2*1;
			  $elem.find('.creative_options_wrapper').css({'visibility':'visible','display':'none'});
		  }
		  else {
			  var elem_h = parseFloat($elem.find('.creative_options_wrapper').height()) + 2*1;
		  };
		  
		  if(offset_bottom > elem_h + 10*1) {
			  $elem.find('.creative_options_wrapper').css({'top':'100%','bottom':'auto'});
		  }
		  else {
			  $elem.find('.creative_options_wrapper').css({'bottom':'100%','top':'auto'});
		  }
	  };
	  
	  function open_creative_options($elem) {
		  $elem.addClass('opened');
		  $elem.removeClass('closed');
		  $elem.addClass('focused');
		  $elem.find('.ccf_select_icon').removeClass('closed').addClass('opened');
		  
		  replace_creative_optios_wrapper($elem);
		  $elem.find('.creative_options_wrapper').stop().fadeIn(400);
	  };
	  function close_creative_options($elem) {
		  if(allow_add_closed)
			  $elem.addClass('closed');
		  $elem.removeClass('opened');
		  $elem.removeClass('focused');
		  $elem.find('.ccf_select_icon').removeClass('opened').addClass('closed');
		  setTimeout(function() {
			  allow_add_closed = true;
			  $elem.removeClass('closed');
		  },500);
		  
		  $elem.find('.creative_options_wrapper').stop().fadeOut(400);
	  };
	  
	  //single select options check, uncheck
	  $(".single_select .creative_select_option").on('click', function() {
		  if(!$(this).hasClass('selected')) {
			  var select_original = $(this).parents('.creative_select').prev('select');
			  select_original.find('option').removeAttr('selected');
			  var id = $(this).attr("opt_id");
			  $("#" + id).prop("selected",true);
			  var sel_val = $("#" + id).val();
			  select_original.val(sel_val);
			  $(this).parent('div').find('.creative_select_option').removeClass('selected');
			  $(this).addClass('selected');
			  
			  var val = $(this).find('.creative_opt_value').html();
			  val = val.replace('<b>','');
			  val = val.replace('</b>','');
			  $(this).parents('.creative_select ').find('.creative_selected_option').html(val);
			  $(this).parents('.creative_select ').find('.ccf_close_icon').show();
			  
			  validate_select($(this).parents('.creative_select'),false);
		  }
		  else {
			  return;
		  }
	  });
	  //multiple select options check, uncheck
	  $(".multiple_select .creative_select_option").on('click', function() {
		  if(!$(this).hasClass('selected')) {
			  var select_original = $(this).parents('.creative_select').prev('select');
			  var id = $(this).attr("opt_id");
			  // $("#" + id).attr("selected","selected");
			  $(this).addClass('selected');
			  
			  make_multiple_select_value($(this).parents('.creative_select'));
		  }
		  else {
			  var select_original = $(this).parent('div').parent('div').prev('select');
			  var id = $(this).attr("opt_id");
			  // $("#" + id).removeAttr("selected");
			  $(this).removeClass('selected');
			  
			  var val = $(this).find('.creative_opt_value').html();
			  $(this).parents('.creative_select').find('.creative_selected_option').html(val);
			  
			  make_multiple_select_value($(this).parents('.creative_select'));
		  }
		  validate_multiple_select($(this).parents('.creative_select'),false);
	  });
			  
	  function make_multiple_select_value($elem) {
		  var count = 0;
		  var count_selected =  $elem.find('.creative_select_option.selected').length;
		  var selected_text = '';
		  $elem.prev('select').find('option').removeAttr('selected');

		  $elem.find('.creative_select_option.selected').each(function() {
			  var id = $(this).attr("opt_id");
			  var val = $(this).find('.creative_opt_value').html();
			  val = val.replace('<b>','');
			  val = val.replace('</b>','');
			  $("#" + id).prop("selected",true);
			  selected_text += val;
			  if(count != count_selected - 1)
				  selected_text += ', ';
			  
			  $elem.find('.creative_selected_option').html(selected_text);
			  count ++;
		  });
		  if(count == 0) {
			  $elem.prev('select').find('option').removeAttr('selected');
			  $elem.prev('select').find('.def_value').prop("selected",true);
			  var def_val = $elem.prev('select').find('.def_value').html();
			  $elem.find('.creative_selected_option').html(def_val);
		  };
	  };
	  
	  $('.ccf_close_icon').on('click', function() {
		  allow_add_closed = false;
		  $(this).parents('.creative_select').prev('select').find('option').removeAttr('selected');
		  $(this).parents('.creative_select').prev('select').find('.def_value').attr('selected','selected');
		  var def_val = $(this).parents('.creative_select').prev('select').find('.def_value').html();
		  $(this).parents('.creative_select').find('.creative_selected_option').html(def_val);
		  $(this).parents('.creative_select').find('.creative_select_option').removeClass('selected');
		  $(this).hide();
		  validate_select($(this).parents('.creative_select'),false);
	  });
	  
	  $('.creative_search_input').on('keyup',function() {
		  var val = $.trim($(this).val());
		  if(val == '') {
			  make_search($(this).parents('.creative_select'),val);
			  return;
		  }
		  else {
			  make_search($(this).parents('.creative_select'),val);
		  };
	  });
	  
	  $('.creative_search_input').on('focus', function() {
		 $(this).parent('div').addClass('focused'); 
	  });
	  $('.creative_search_input').on('blur', function() {
		  $(this).parent('div').removeClass('focused'); 
	  });
	  
	  function escapeRegExp(str) {
		  return str.replace(/[\-\[\]\/\{\}\(\)\*\+\?\.\\\^\$\|]/g, "\\$&");
	  };
	  
	  function make_search($elem,val) {
		  var c = 0;
		  $elem.find('.creative_select_option').each(function() {
			  var val_1 = $(this).find('.creative_opt_value').html();
			  val_1 = val_1.replace('<b>','');
			  val_1 = val_1.replace('</b>','');
			  var escaped_val = escapeRegExp(val);
			  var pattern = new RegExp('^' + escaped_val + '| ' + escaped_val,'gi');
			  if(pattern.test(val_1)) {

		  			var val_lc = val.toLowerCase();
		  			var val_1_lc = val_1.toLowerCase();
					var start_pos = parseInt(val_1_lc.indexOf(val_lc));
					var end_pos = start_pos + parseInt(val_lc.length) + 3*1;

					var new_val = val_1.substr(0, start_pos) + '<b>' + val_1.substr(start_pos);
					new_val = new_val.substr(0, end_pos) + '</b>' + new_val.substr(end_pos);

					$(this).find('.creative_opt_value ').html(new_val);
					$(this).show();
					c ++;
			  }
			  else {
				  $(this).removeClass('selected');
				  $(this).hide();
			  }
		  });
		  if(c == 0) {
			  $elem.find('.creative_select_empty_option').show().find('span').html(val);
		  }
		  else {
			  $elem.find('.creative_select_empty_option').hide();
		  };
		  // make_scrolling($elem);
		  make_multiple_select_value($elem);
	  };
	  
/////////////////////////////////////////////////////////////////////////CREATIVE CAPTCHA//////////////////////////////////////////////////////////
	  
	  $('.reload_creative_captcha').click(function(e) {
		  var fid = $(this).attr('fid');
		  var captcha_path = creativecontactform_juri + '/components/com_creativecontactform/captcha.php?fid=' + fid + '&r=' + Math.random();
		  var holder = $(this).attr('holder');
		  $(this).prev('img').attr('src',captcha_path);
		  
		  if (e.originalEvent !== undefined) {
			  $(this).parents('.creativecontactform_field_box').find('input.creative_captcha:last').focus();
		  }
	  });
/////////////////////////////////////////////////////////////////////////CREATIVE UPLOAD//////////////////////////////////////////////////////////
	  $('.creative_fileupload_submit').mousedown(function() {
		  var upload_maxfilesize = parseFloat($(this).parents('.creative_fileupload_wrapper').find('.creative_upload_info').attr("upload_maxfilesize")) * 1048576;
		  var upload_minfilesize = parseFloat($(this).parents('.creative_fileupload_wrapper').find('.creative_upload_info').attr("upload_minfilesize"));
		  var upload_acceptfiletypes = $(this).parents('.creative_fileupload_wrapper').find('.creative_upload_info').attr("upload_acceptfiletypes");
		  var upload_acceptfiletypes_final = '(\.|\/)(' + upload_acceptfiletypes + ')$';
		  var upload_acceptfiletypes_final_pattern = new RegExp(upload_acceptfiletypes_final,'i');
		  var upload_url = creativecontactform_juri + '/index.php?option=com_creativecontactform&view=creativeupload&format=raw';
		    $(this).fileupload({
		        minFileSize: upload_minfilesize,
		        maxFileSize: upload_maxfilesize,
		    	acceptFileTypes: upload_acceptfiletypes_final_pattern,
		        url: upload_url,
		        dataType: 'json'
		   });
	  });
	  
	    $('.creative_fileupload_submit').on('fileuploaddone',  function (e, data) {
	        	var $uploaded_files_wrapper =  $(this).parents('.creative_fileupload_wrapper').find('.creative_uploaded_files');
	            $.each(data.result.files, function (index, file) {
	            	var size_unit = 'MB';
	            	var size_formated = file.size / 1048576;
	            	if(size_formated < 1) {
	            		size_formated = size_formated * 1024;
	            		size_unit = 'KB';
	            	};
	            	size_formated = size_formated.toFixed(1);
	            	var inner_htm = '<div class="creative_uploaded_file_item" ><input type="hidden" class="creative_active_upload" name="creativecontactform_upload[]" value="'+ file.name +'" /><div class="creative_uploaded_icon"></div><div class="creative_uploaded_file">'+ file.name + ' (' + size_formated + size_unit + ')</div><div class="creative_remove_uploaded"></div></div>';
	            	$uploaded_files_wrapper.append(inner_htm);
	            });
	            
	            var $progress_wrapper =  $(this).parents('.creative_fileupload_wrapper').find('.creative_progress');
	            setTimeout(function() {
            		$progress_wrapper.animate({'height': 0},600);
            		$progress_wrapper.find('.bar').css({'width': 0});
            		validate_file_upload($uploaded_files_wrapper.parents('.creative_file-upload'),false);
            	},2000);
	            
	            $('.creative_remove_uploaded').on('click', function() {
    		    	$(this).parent('.creative_uploaded_file_item').animate({'height': 0}, function() {$(this).hide();});
    		    	var removed_input_name = 'creativecontactform_removed_upload[]';
    		    	$(this).parent('.creative_uploaded_file_item').find('.creative_active_upload').addClass('creative_removed_upload').removeClass('creative_active_upload').attr("name",removed_input_name);
    		    	validate_file_upload($(this).parents('.creative_file-upload'),false);
	            });
	   }).on('fileuploadprocessalways', function (e, data) {
	        var index = data.index,
	            file = data.files[index],
	            original_error_message = '';
	        if (file.error) {
	        	if(file.error == 'error_message_file_types')
	        		original_error_message =  $(this).parents('.creative_fileupload_wrapper').find('.creative_upload_info').attr("upload_acceptfiletypes_message");
	        	else if(file.error == 'error_message_file_large')
	        		original_error_message =  $(this).parents('.creative_fileupload_wrapper').find('.creative_upload_info').attr("upload_maxfilesize_message");
	        	else if(file.error == 'error_message_file_small')
	        		original_error_message =  $(this).parents('.creative_fileupload_wrapper').find('.creative_upload_info').attr("upload_minfilesize_message");
	        	
	        	var form_id = $(this).parents('.creativecontactform_wrapper').find('.creativecontactform_form_id').val();
	            ccf_make_alert(original_error_message,'creative_error',form_id);
	        };
	   }).on('fileuploadprogressall',  function (e, data) {
	        	var $progress_wrapper =  $(this).parents('.creative_fileupload_wrapper').find('.creative_progress');
	            var progress = parseInt(data.loaded / data.total * 100, 10);
	            $progress_wrapper.find('.bar').css(
	                'width',
	                progress + '%'
	            );
	   }).on('fileuploadstart',  function (e, data) {
	        	var $progress_wrapper =  $(this).parents('.creative_fileupload_wrapper').find('.creative_progress');
	        	$progress_wrapper.animate({'height': 15},600);
	   }).on('fileuploadfail', function (e, data) {
		   console.log(data);
		   console.log(data.result);
		   
		   var form_id = $(this).parents('.creativecontactform_wrapper').find('.creativecontactform_form_id').val();
           ccf_make_alert('Error uploading file','creative_error',form_id);
	   });
	    
/////////////////////////////////////////////////////////////////////////////////////CREATIVE Datepicker////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$( ".creative_datepicker").each(function() {
			var $this = $(this);

			//get options
			var $options_element = $this.parent('div');

			var datepicker_date_format = $options_element.attr("datepicker_date_format");
			var datepicker_animation = $options_element.attr("datepicker_animation");
			var datepicker_style = 'creative_datepicker_style_' + parseInt($options_element.attr("datepicker_style"));
			var datepicker_icon_style = $options_element.attr("datepicker_icon_style");
			var datepicker_show_icon = $options_element.attr("datepicker_show_icon");
			var datepicker_input_readonly = $options_element.attr("datepicker_input_readonly");
			var datepicker_number_months = parseInt($options_element.attr("datepicker_number_months"));
			var datepicker_mindate = $options_element.attr("datepicker_mindate");
			var datepicker_maxdate = $options_element.attr("datepicker_maxdate");
			var datepicker_changemonths = parseInt($options_element.attr("datepicker_changemonths")) == 0 ? false : true;
			var datepicker_changeyears = parseInt($options_element.attr("datepicker_changeyears")) == 0 ? false : true;
			var datepicker_juri = $options_element.attr("juri");
			var datepicker_img = datepicker_juri + '/components/com_creativecontactform/assets/images/datepicker/style-' + datepicker_icon_style + '.png';

			var show_on = datepicker_show_icon == 0 ? 'focus' : (datepicker_input_readonly == 0 ? 'both' : 'both');

			var patt = new RegExp("^[0-9]{4}:[0-9]{4}$", "i");
			var dateRangeExists = patt.test(datepicker_mindate);
			var yearRange = dateRangeExists ? datepicker_mindate : '';
			datepicker_mindate = dateRangeExists ? '' : datepicker_mindate;

			//add datepicker
			if(yearRange == '') {
				$this.datepicker(
				{
				 	showOtherMonths: true,
	  				selectOtherMonths: false,
				  	changeMonth: datepicker_changemonths,
	      			changeYear: datepicker_changeyears,
					minDate: datepicker_mindate, 
					maxDate: datepicker_maxdate,
					showAnim: datepicker_animation,
					dateFormat: datepicker_date_format,
					numberOfMonths: datepicker_number_months,
				  	showOn: show_on,
	      			buttonImage: datepicker_img,
	      			buttonImageOnly: true,
	      			buttonText: "Select date",
					beforeShow: function(input, inst) {
		       			$('#ui-datepicker-div').wrap('<div class="creative_datepicker_wrapper ' + datepicker_style + '"></div>')
				   }
				});
			}
			else {
				$this.datepicker(
				{
				 	showOtherMonths: true,
	  				selectOtherMonths: false,
				  	changeMonth: datepicker_changemonths,
	      			changeYear: datepicker_changeyears,
	      			yearRange: yearRange,
					minDate: datepicker_mindate, 
					maxDate: datepicker_maxdate,
					showAnim: datepicker_animation,
					dateFormat: datepicker_date_format,
					numberOfMonths: datepicker_number_months,
				  	showOn: show_on,
	      			buttonImage: datepicker_img,
	      			buttonImageOnly: true,
	      			buttonText: "Select date",
					beforeShow: function(input, inst) {
		       			$('#ui-datepicker-div').wrap('<div class="creative_datepicker_wrapper ' + datepicker_style + '"></div>')
				   }
				});
			}
		});

/////////////////////////////////////////////////////////////////////////////////////CREATIVE tooltip////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

		$('.creative_input_reset,.creative_textarea_reset').focus(function() {
			if(!$(this).hasClass('mouseentered'))
				$(this).trigger('mousedown');
		});

		$('.creative_input_reset,.creative_textarea_reset').mousedown(function() {
			
			$(this).addClass('mouseentered');
			var $tooltip = $(this).parent('div').find('.tooltip_inner');
			$tooltip.css('display','block');
			setTimeout(function() {
				$tooltip.removeClass('creative_tooltip_ivisible');
				$tooltip.addClass('cr_rotate');
			},100);
		});
	 	$('.creative_input_reset,.creative_textarea_reset').blur(function() {
			$(this).removeClass('mouseentered');
			var $tooltip = $(this).parent('div').find('.tooltip_inner');
			$tooltip.addClass('creative_tooltip_ivisible');
			$tooltip.removeClass('cr_rotate');
			setTimeout(function() {
				$tooltip.css('display','none');
			},500);
		});
/////////////////////////////////////////////////////////////////////////////////////CREATIVE box animations////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$('.creative_wrapper_animation_state_1').addClass('creative_wrapper_animation_state_2');
		$('.creative_header_animation_state_1').addClass('creative_header_animation_state_2');
		$('.creative_field_box_animation_state_1').addClass('creative_field_box_animation_state_2');
		$('.creative_footer_animation_state_1').addClass('creative_footer_animation_state_2');

		setTimeout(function() {
			$('.creative_wrapper_animation_state_1').removeClass('creative_wrapper_animation_state_1').removeClass('creative_wrapper_animation_state_2');
			$('.creative_header_animation_state_1').removeClass('creative_header_animation_state_1').removeClass('creative_header_animation_state_2');
			$('.creative_field_box_animation_state_1').removeClass('creative_field_box_animation_state_1').removeClass('creative_field_box_animation_state_2');
			$('.creative_footer_animation_state_1').removeClass('creative_footer_animation_state_1').removeClass('creative_footer_animation_state_2');
		},3000);


/////////////////////////////////////////////////////////////////////////Correct bugs///////////////////////////////////////////////////////////////////////////////////////////////////////////
//IE bug
		//function to check if browser is ie, or not
		var is_creative_ie = (function(){

		    var undef,
		        v = 3,
		        div = document.createElement('div'),
		        all = div.getElementsByTagName('i');

		    while (
		        div.innerHTML = '<!--[if gt IE ' + (++v) + ']><i></i><![endif]-->',
		        all[0]
		    );

		    return v > 4 ? v : undef;

		}());
		if(is_creative_ie) {
			$('.creativecontactform_input_element,.creativecontactform_wrapper,.creativecontactform_send,.creativecontactform_send_new').css('border-radius','0');
		};

/////////////////////////////////////////// CENTER ALIGNED CHECKBOXES ///////////////////////////////////////////////////////
		// $('.creative_checkbox_wrapper.centered').each(function() {
		// 	var elem_w = parseInt($(this).find('.creative_checkbox_label_wrapper').width());
		// 	elem_w += 68;
		// 	$(this).width(elem_w);
		// });
		// $(window).resize(function() {
		// 	$('.creative_checkbox_wrapper.centered').each(function() {
		// 		var elem_w = parseInt($(this).find('.creative_checkbox_label_wrapper').width());
		// 		elem_w += 68;
		// 	});
		// });

	// Font effect functions////////////////////////////////////////////////
	// var ccf_isChrome = /Chrome/.test(navigator.userAgent) && /Google Inc/.test(navigator.vendor);
	// var ccf_isSafari = /Safari/.test(navigator.userAgent) && /Apple Computer/.test(navigator.vendor);
	// var ccf_webkit_supported = ccf_isChrome || ccf_isSafari ? true : false;

	$('.creative_input_reset').on('focus',function() {
		//check if animation enabled
		if($(this).parents('.creativecontactform_wrapper').attr('focus_anim_enabled') == 0)
			return;

		if($(this).parents('.creativecontactform_field_box').hasClass('creativecontactform_error'))
			return;

		var $label = $(this).parents('.creativecontactform_field_box').find('label');
		var normal_class = $label.attr("normal_effect_class");
		var hover_class = $label.attr("hover_effect_class");
		var error_class = $label.attr("error_effect_class");

		$label.removeClass(normal_class).addClass(hover_class);
	});		
	$('.creative_textarea_reset').on('focus',function() {
		//check if animation enabled
		if($(this).parents('.creativecontactform_wrapper').attr('focus_anim_enabled') == 0)
			return;

		if($(this).parents('.creativecontactform_field_box').hasClass('creativecontactform_error'))
			return;

		var $label = $(this).parents('.creativecontactform_field_box').find('label');
		var normal_class = $label.attr("normal_effect_class");
		var hover_class = $label.attr("hover_effect_class");
		var error_class = $label.attr("error_effect_class");

		$label.removeClass(normal_class).addClass(hover_class);
	});	

	$('.creative_input_reset').on('blur',function() {
		//check if animation enabled
		if($(this).parents('.creativecontactform_wrapper').attr('focus_anim_enabled') == 0)
			return;
		
		var $label = $(this).parents('.creativecontactform_field_box').find('label');

		var normal_class = $label.attr("normal_effect_class");
		var hover_class = $label.attr("hover_effect_class");
		var error_class = $label.attr("error_effect_class");

		$label.removeClass(hover_class).addClass(normal_class);
	});	
	$('.creative_textarea_reset').on('blur',function() {
		//check if animation enabled
		if($(this).parents('.creativecontactform_wrapper').attr('focus_anim_enabled') == 0)
			return;

		var $label = $(this).parents('.creativecontactform_field_box').find('label');

		var normal_class = $label.attr("normal_effect_class");
		var hover_class = $label.attr("hover_effect_class");
		var error_class = $label.attr("error_effect_class");

		$label.removeClass(hover_class).addClass(normal_class);
	});

	function create_validation_effects($wrapper) {
		//check if animation enabled
		if($wrapper.attr('error_anim_enabled') == 0)
			return;

		$wrapper.find('.creativecontactform_field_box').each(function() {
			var $label = $(this).find('label').not('.twoglux_label');

			var normal_class = $label.attr("normal_effect_class");
			var hover_class = $label.attr("hover_effect_class");
			var error_class = $label.attr("error_effect_class");

			if($(this).hasClass('creativecontactform_error'))
				$label.removeClass(hover_class).removeClass(normal_class).addClass(error_class);
			else
				$label.removeClass(hover_class).removeClass(error_class).addClass(normal_class);
		})
	};

	$('.creativecontactform_send').hover(function() {
		var normal_class = $(this).attr("normal_effect_class");
		var hover_class = $(this).attr("hover_effect_class");

		$(this).removeClass(normal_class).addClass(hover_class);
	},function() {
		var normal_class = $(this).attr("normal_effect_class");
		var hover_class = $(this).attr("hover_effect_class");

		$(this).removeClass(hover_class).addClass(normal_class);
	});

	$('.creative_fileupload').hover(function() {
		var normal_class = $(this).attr("normal_effect_class");
		var hover_class = $(this).attr("hover_effect_class");
		$(this).removeClass(normal_class).addClass(hover_class);
	},function() {
		var normal_class = $(this).attr("normal_effect_class");
		var hover_class = $(this).attr("hover_effect_class");
		$(this).removeClass(hover_class).addClass(normal_class);
	});

/////////////////////////////////////////////////////////////////////////////////////CREATIVE ALERT////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	  //alert box ////////////////////////////////////////////////////////////////////////////////////////
		//function to create shadow
		function ccf_create_shadow() {
			var $shadow = '<div id="creative_shadow"></div>';
			$('body').css('position','relative').append($shadow);
			var w_width = parseInt($(window).width());
			var w_height = parseInt($(window).height());

			$("#creative_shadow")
			.css( {
				'position' : 'fixed',
				'top' : '0',
				'right' : '0',
				'bottom' : '0',
				'left' : '0',
				'z-index' : '10000',
				'opacity' : '0',
				'width' : w_width + 'px',
				'height' : w_height + 'px',
				'backgroundColor' : '#000'
			})
			.fadeTo(200,'0.7');
		};

		function ccf_resize_shadow() {
			if($('#creative_shadow').length == 0) 
				return;

			var w_width = parseInt($(window).width());
			var w_height = parseInt($(window).height());

			$("#creative_shadow")
			.css( {
				'width' : w_width + 'px',
				'height' : w_height + 'px'
			})
		}
		
		function ccf_make_alert(txt,type,form_id) {
			//create shadow
			ccf_create_shadow();
			var close_text = close_alert_text[form_id];
			
			//make alert
			var $alert_body = '<div id="creative_alert_wrapper"><div id="creative_alert_body" class="' + type + '">' + txt + '</div><input type="button" id="close_creative_alert" value="'+ close_text +'" /></div>';
			$('body').append($alert_body);
			var scollTop = $(window).scrollTop();
			var w_width = $(window).width();
			var w_height = $(window).height();
			var s_height = $("#creative_alert_wrapper").height();
			
			var alert_left = (w_width - 420) / 2;
			var alert_top = (w_height - s_height) / 2;
			
			$("#creative_alert_wrapper")
			.css( {
				'top' : -1 * (s_height  + 55 * 1) + scollTop * 1,
				'left' : alert_left
			})
			.stop()
			.animate({
				'top' : alert_top + scollTop * 1
			},450,'easeOutBack',function() {
				//$(this).css('position','fixed');
			});
		};
		
		function ccf_remove_alert_box() {
			if($('#creative_alert_wrapper').length == 0) 
				return;

			$("#creative_shadow").stop().fadeTo(200,0,function() {$(this).remove();});
			$("#creative_alert_wrapper").stop().fadeTo(200,0,function() {$(this).remove();});
		};
		
		function ccf_move_alert_box() {
			if($('#creative_alert_wrapper').length == 0) 
				return;

			var scollTop = $(window).scrollTop();
			var w_width = $(window).width();
			var w_height = $(window).height();
			var s_height = $("#creative_alert_wrapper").height();
			
			
			var alert_left = (w_width - 420) / 2;
			var alert_top = (w_height - s_height) / 2;
			
			$("#creative_alert_wrapper")
			.stop()
			.animate({
				'top' : alert_top + scollTop * 1,
				'left' : alert_left
			},450,'easeOutBack',function() {
				//$(this).css('position','fixed');
			});
		};
		
		$(document).on('click','#close_creative_alert,#creative_shadow', function() {
			ccf_remove_alert_box();
		});
		
		$(window).resize(function() {
			ccf_resize_shadow();
			ccf_move_alert_box();
			ccf_move_popup_box();
		});
		$(window).scroll(function() {
			ccf_move_alert_box();
			ccf_move_popup_box();
		});

////////////////////////////////////////////// creative popup //////////////////////////////////////////////////////////
	$(".ccf_popup_link").click(function() {
		var form_id = $(this).parents('.creativecontactform_wrapper').find('.creativecontactform_send').attr("roll");
		var popup_id = parseInt($(this).attr("popup_id"));
		var popup_w = parseInt($(this).attr("w"));
		var popup_h = parseInt($(this).attr("h"));
		ccf_make_popup(popup_id,popup_w,popup_h,form_id);
	});

	function ccf_make_popup(popup_id,popup_w,popup_h,form_id) {
		//create shadow
		ccf_create_shadow();
		
		//make popup
		var popup_html = $("#popup_" + popup_id).html();
		var $popup_body = '<div id="creative_popup_wrapper" style="width: ' + popup_w + 'px;height: ' + popup_h + 'px;"><div class="ccf_close_popup"><div class="ccf_close_popup_icon"></div><div class="ccf_close_popup_bg"></div></div><div class="ccf_popup_inner_wrapper creative_form_' + form_id + '">' + popup_html + '</div></div>';
		$('body').append($popup_body);

		var scrollbar_popup_style = $(".creativecontactform_wrapper.creative_form_" + form_id).attr("scrollbar_popup_style");

		$(".ccf_popup_inner_wrapper").mCustomScrollbar({
			mouseWheel:{
				enable:true
			},
			scrollButtons:{
				enable:true
			},
			theme:scrollbar_popup_style
		});

		var scollTop = $(window).scrollTop();
		var w_width = $(window).width();
		var w_height = $(window).height();
		
		var alert_left = (w_width - popup_w) / 2;
		var alert_top = (w_height - popup_h) / 2;

		alert_left = alert_left < 0 ? 0 : alert_left;
		alert_top = alert_top < 0 ? 0 : alert_top;

		if(alert_left  == 0 || alert_top == 0) {
			$("#creative_popup_wrapper").addClass('disableScroll');
		}

		$("#creative_popup_wrapper")
		.css( {
			'top' : -1 * (popup_h  + 55 * 1) + scollTop * 1,
			'left' : alert_left
		})
		.stop()
		.animate({
			'top' : alert_top + scollTop * 1
		},450,'easeOutBack',function() {
			//$(this).css('position','fixed');

		});
	};

	function ccf_move_popup_box() {
		if($('#creative_shadow').length == 0) 
			return;

		var scollTop = $(window).scrollTop();
		var w_width = $(window).width();
		var w_height = $(window).height();
		var popup_w = $("#creative_popup_wrapper").width();
		var popup_h = $("#creative_popup_wrapper").height();
		
		var alert_left = (w_width - popup_w) / 2;
		var alert_top = (w_height - popup_h) / 2;

		alert_left = alert_left < 0 ? 0 : alert_left;
		alert_top = alert_top < 0 ? 0 : alert_top;

		if(!$("#creative_popup_wrapper").hasClass('disableScroll')) {
			$("#creative_popup_wrapper")
			.stop()
			.animate({
				'top' : alert_top + scollTop * 1,
				'left' : alert_left
			},450,'easeOutBack',function() {
				//$(this).css('position','fixed');
			});
		}

		if(alert_left  == 0 || alert_top == 0) {
			$("#creative_popup_wrapper").addClass('disableScroll');
		}
		else
			$("#creative_popup_wrapper").removeClass('disableScroll');
	};

	function ccf_remove_popup_box() {
		if($('#creative_shadow').length == 0) 
			return;

		$("#creative_shadow").stop().fadeTo(200,0,function() {$(this).remove();});
		$("#creative_popup_wrapper").stop().fadeTo(200,0,function() {$(this).remove();});
	};

	$(document).on('click','.ccf_close_popup,#creative_shadow', function() {
		ccf_remove_popup_box();
	});


})
})(creativeJ);

