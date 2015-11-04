(function($) {
$(document).ready(function() {
	
	
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
        }
        // make it so
        var pos;
        return this.each(function () {
            $this = $(this);
            // position if necessary
            pos = $this.css('position');
            if (!pos || pos === 'static') {
                $this.css('position', 'relative');
            }
            // shake it
            for (var x = 1; x <= settings.shakes; x++) {
                $this.animate({ left: settings.distance * -1 }, (settings.duration / settings.shakes) / 4)
                    .animate({ left: settings.distance }, (settings.duration / settings.shakes) / 2)
                    .animate({ left: 0 }, (settings.duration / settings.shakes) / 4);
            }
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
	 };
	 
	 //function to validate simple-text types
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
	 };
	 
	 //function to validate simple-text types
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
	 };
			
	function creativecontactform_make_validation($c) {

		return;
		
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
		
		//validate simple-text types
		$c.parents('.creativecontactform_wrapper').find(".creative_simple-text").each(function() {
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
	}
	
	$('.creativecontactform_send').click(function() {

		return;
		
		var form_id = $(this).attr("roll");
		//animate loading
		var loading_element = $(this).parents('.creativecontactform_wrapper').children('.creativecontactform_loading_wrapper');
		var pre_element = $(this).parents('.creativecontactform_wrapper').find('.creativecontactform_pre_text');
		var send_button = $(this).parents('.creativecontactform_wrapper').find('.creativecontactform_send');
		var send_new_button = $(this).parents('.creativecontactform_wrapper').find('.creativecontactform_send_new');

		creativecontactform_make_validation($(this));
		var errors_count = parseInt($(this).parents('.creativecontactform_wrapper').find('.creativecontactform_error').length);
		if(errors_count != 0) {
			$(this).parents('.creativecontactform_wrapper').find('.creativecontactform_error:first').find('input').focus();
		}
		else {
			alert('works!');
		}
	});
			
	$('.creativecontactform_send_new').click(function() {
		var form_id = $(this).attr("roll");
		
		var loading_element = $(this).parents('.creativecontactform_wrapper').children('.creativecontactform_loading_wrapper');
		var pre_element = $(this).parents('.creativecontactform_wrapper').find('.creativecontactform_pre_text');
		var creativecontactform_field_box = $(this).parents('.creativecontactform_wrapper').find('.creativecontactform_field_box');
		var send_button = $(this).parents('.creativecontactform_wrapper').find('.creativecontactform_send');
		var send_new_button = $(this).parents('.creativecontactform_wrapper').find('.creativecontactform_send_new');
		var creativecontactform_input_element  = $(this).parents('.creativecontactform_wrapper').find('.creative_input_reset');
		var creativecontactform_textarea_element  = $(this).parents('.creativecontactform_wrapper').find('.creative_textarea_reset');
		
		alert('works!');
	});
	
	function animate_loading_start($elem) {
		$elem
		.css({opacity:0,display:'block'})
		.stop()
		.animate({
			opacity: 0.25
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
	
	// //blur validation
	// $(".creative_name").blur(function() {
	// 	validate_name($(this),false);
	// });
	// $(".creative_email").blur(function() {
	// 	validate_email($(this),false);
	// });
	// $(".creative_address").blur(function() {
	// 	validate_address($(this),false);
	// });
	// $(".creative_simple-text").blur(function() {
	// 	validate_simple_text($(this),false);
	// });
	// $(".creative_phone").blur(function() {
	// 	validate_phone($(this),false);
	// });
	// $(".creative_text-area").blur(function() {
	// 	validate_text_area($(this),false);
	// });
	// $(".creative_number").blur(function() {
	// 	validate_number($(this),false);
	// });
	// $(".creative_url").blur(function() {
	// 	validate_url($(this),false);
	// });
	
	// $('.creativecontactform_input_element input,.creativecontactform_input_element textarea').focus(function() {
	// 	$(this).parents('.creativecontactform_input_element').addClass('focused');
	// });
	// $('.creativecontactform_input_element input,.creativecontactform_input_element textarea').blur(function() {
	// 	$(this).parents('.creativecontactform_input_element').removeClass('focused');
	// });
	
	
})

})(creativeJ);