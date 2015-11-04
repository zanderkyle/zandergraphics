(function($) {
$(document).ready(function() {
	
	function cs_createCookie(name, value, days) {
	    var expires;

	    if (days) {
	        var date = new Date();
	        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
	        expires = "; expires=" + date.toGMTString();
	    } else {
	        expires = "";
	    }
	    document.cookie = escape(name) + "=" + escape(value) + expires + "; path=/";
	}

	function cs_readCookie(name) {
	    var nameEQ = escape(name) + "=";
	    var ca = document.cookie.split(';');
	    for (var i = 0; i < ca.length; i++) {
	        var c = ca[i];
	        while (c.charAt(0) === ' ') c = c.substring(1, c.length);
	        if (c.indexOf(nameEQ) === 0) return unescape(c.substring(nameEQ.length, c.length));
	    }
	    return null;
	}

	function cs_eraseCookie(name) {
	    createCookie(name, "", -1);
	}
	
	$('.sim_img_close').click(function() {
		$(this).parents('.sim_ext_item_wrapper').addClass('closed').hide();
		var roll = $(this).parents('.twog_extension_wrapper').attr("roll");
		var hidden_element = 'sim_ext_' + roll;
		cs_createCookie(hidden_element,1);
		
		var all_wrappers_closed = true;
		$('.twog_extension_wrapper').each(function() {
			if(!($(this).hasClass('closed'))) {
				all_wrappers_closed = false;
				return;
			}
		});
		
		if(all_wrappers_closed) {
			$('.sim_ext_title').hide();
		};
	});
	
	$('.show_similar').click(function() {
		$(this).hide();
		$('.hide_similar').show();
		$("#similar_extensions").fadeIn(400);
		show_suggested_extensions();
	});
	
	$('.hide_similar').click(function() {
		$(this).hide();
		$('.show_similar').show();
		$("#similar_extensions").fadeOut(400, function() {
			$('.twog_extension_wrapper').css('opacity',0);
		});
	});
	
	setTimeout(function() {
		if(!$("#similar_extensions").hasClass('sim_hidden')) {
			$("#similar_extensions").fadeIn(400);
			show_suggested_extensions();
			setTimeout(function() {
				$('.hide_similar').show();
			},2400);
		}
	},1200);
	
	function show_suggested_extensions() {
		
		$('.twog_extension_wrapper').css('opacity',0);
		var timeout = 400;
		var timeout_step = 400;
		$('.twog_extension_wrapper').each(function() {
			var $t = $(this);
			setTimeout(function() {
				$t.fadeTo(1,800);
			},timeout)
			timeout = timeout + 1 * timeout_step;
		});
	};
	
})
})(creativeJ);
