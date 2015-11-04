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

$document = JFactory::getDocument();
$cssFile = JURI::base(true).'/components/com_creativecontactform/assets/css/colorpicker.css';
$document->addStyleSheet($cssFile, 'text/css', null, array());

$cssFile = JURI::base(true).'/components/com_creativecontactform/assets/css/layout.css';
$document->addStyleSheet($cssFile, 'text/css', null, array());

$cssFile = JURI::base(true).'/components/com_creativecontactform/assets/css/temp_'.JV.'.css';
$document->addStyleSheet($cssFile, 'text/css', null, array());

$cssFile = JURI::base(true).'/components/com_creativecontactform/assets/css/main.css';
$document->addStyleSheet($cssFile, 'text/css', null, array());

$cssFile = JURI::base(true).'/../components/com_creativecontactform/assets/css/creative-tooltip.css';
$document->addStyleSheet($cssFile, 'text/css', null, array());

$cssFile = JURI::base(true).'/../components/com_creativecontactform/assets/css/creative-datepicker.css';
$document->addStyleSheet($cssFile, 'text/css', null, array());

$jsFile = JURI::base(true).'/components/com_creativecontactform/assets/js/creativelib.js';
$document->addScript($jsFile);

$jsFile = JURI::base(true).'/components/com_creativecontactform/assets/js/colorpicker.js';
$document->addScript($jsFile);

$jsFile = JURI::base(true).'/components/com_creativecontactform/assets/js/creativecontactform.js';
//$document->addScript($jsFile);

$styles_row = $this->styles;
$styles_array = explode('|',$this->styles);
$max = 0;
foreach ($styles_array as $val) {
	$arr = explode('~',$val);
	$styles[$arr[0]] = $arr[1];
	$max = $arr[0]> $max ? $arr[0] : $max;
}

/*
*/
$keys = array_keys($styles);
sort($keys);
// print_r($keys);

?>

<?php
	$ccf_fonts_indexes_array = array(506,507,508,509,131,112,202,152,529);
	$ccf_google_requested_fonts = array();
	foreach($ccf_fonts_indexes_array as $key) {
		$ccf_googlefont = 'ccf-googlewebfont-';
		preg_match('/'.$key.'~([^\|]+)\|/',$styles_row,$m);
		$ccf_font_rule = isset($m[1]) ? $m[1] : '';

		if (strpos($ccf_font_rule,$ccf_googlefont) !== false) {
			$ccf_font_rule = str_replace($ccf_googlefont, '', $ccf_font_rule);
			$ccf_font_rule = str_replace(' ', '+', $ccf_font_rule);
			$ccf_google_requested_fonts[] = $ccf_font_rule;
		}
	}
	$ccf_google_requested_fonts = implode('|',$ccf_google_requested_fonts);
	if($ccf_google_requested_fonts != '') {
		$ccf_google_font_link = 'http://fonts.googleapis.com/css?family='.$ccf_google_requested_fonts;
		$document->addStyleSheet($ccf_google_font_link, 'text/css', null, array());
	}
?>

<?php 
if(JV == 'j2') {
	echo '<style>
	
	</style>';
}
else {
	echo '<style>
			.colorpicker input {
				background-color: transparent !important;
				border: 1px solid transparent !important;
				position: absolute !important;
				font-size: 10px !important;
				font-family: Arial, Helvetica, sans-serif !important;
				color: #898989 !important;
				top: 4px !important;
				right: 11px !important;
				text-align: right !important;
				margin: 0 !important;
				padding: 0 !important;
				height: 11px !important;
				outline: none !important;
				box-shadow: none !important;
				width: 32px !important;
				height: 12px !important;
				top: 2px !important;
			}
			.colorpicker_hex input {
				width: 38px !important;
				right: 6px !important;
			}
	</style>';
}
?>


<script type="text/javascript">

if (typeof creativecontactform_shake_count_array === 'undefined') { var creativecontactform_shake_count_array = new Array();};creativecontactform_shake_count_array[1] = "3"; if (typeof creativecontactform_shake_distanse_array === 'undefined') { var creativecontactform_shake_distanse_array = new Array();};creativecontactform_shake_distanse_array[1] = "10"; if (typeof creativecontactform_shake_duration_array === 'undefined') { var creativecontactform_shake_duration_array = new Array();};creativecontactform_shake_duration_array[1] = "300";var creativecontactform_path = "/Joomla_3.1.1/components/com_creativecontactform/"; if (typeof creativecontactform_redirect_enable_array === 'undefined') { var creativecontactform_redirect_enable_array = new Array();};creativecontactform_redirect_enable_array[1] = "0"; if (typeof creativecontactform_redirect_array === 'undefined') { var creativecontactform_redirect_array = new Array();};creativecontactform_redirect_array[1] = ""; if (typeof creativecontactform_redirect_delay_array === 'undefined') { var creativecontactform_redirect_delay_array = new Array();};creativecontactform_redirect_delay_array[1] = "0"; if (typeof creativecontactform_thank_you_text_array === 'undefined') { var creativecontactform_thank_you_text_array = new Array();};creativecontactform_thank_you_text_array[1] = "Message successfully sent"; if (typeof creativecontactform_pre_text_array === 'undefined') { var creativecontactform_pre_text_array = new Array();};creativecontactform_pre_text_array[1] = "Contact us, if you have any questions";

<?php if(version_compare( JVERSION, '1.6.0', 'lt' )) { ?>
function submitbutton(task) {
<?php } else { ?>
Joomla.submitbutton = function(task) {
<?php } ?>
	var form = document.adminForm;
	if (task == 'cancel') {
		submitform( task );
	} else if (form.name.value == ""){
		form.name.style.border = "1px solid red";
		form.name.focus();
	} else {
		submitform( task );
	}
}

//admin forever
var req = false;
function refreshSession() {
    req = false;
    if(window.XMLHttpRequest && !(window.ActiveXObject)) {
        try {
            req = new XMLHttpRequest();
        } catch(e) {
            req = false;
        }
    // branch for IE/Windows ActiveX version
    } else if(window.ActiveXObject) {
        try {
            req = new ActiveXObject("Msxml2.XMLHTTP");
        } catch(e) {
            try {
                req = new ActiveXObject("Microsoft.XMLHTTP");
            } catch(e) {
                req = false;
            }
        }
    }

    if(req) {
        req.onreadystatechange = processReqChange;
        req.open("HEAD", "<?php echo JURI::base();?>", true);
        req.send();
    }
}

function processReqChange() {
    // only if req shows "loaded"
    if(req.readyState == 4) {
        // only if "OK"
        if(req.status == 200) {
            // TODO: think what can be done here
        } else {
            // TODO: think what can be done here
            //alert("There was a problem retrieving the XML data: " + req.statusText);
        }
    }
}
setInterval("refreshSession()", <?php echo $timeout = intval(JFactory::getApplication()->getCfg('lifetime') * 60 / 3 * 1000);?>);
</script>
<script type="text/javascript">
<?php if(version_compare( JVERSION, '1.6.0', 'lt' )) { ?>
function submitbutton(task) {
<?php } else { ?>
Joomla.submitbutton = function(task) {
<?php } ?>
	var form = document.adminForm;
	if (task == 'cancel') {
		submitform( task );
	} else if (form.name.value == ""){
		form.name.style.border = "1px solid red";
		form.name.focus();
	} else {
		submitform( task );
	}
}
</script>
<script>
(function($) {
	$(document).ready(function() {

		$('.creativecontactform_input_element input,.creativecontactform_input_element textarea').focus(function() {
			$(this).parents('.creativecontactform_input_element').not('.creative_error_input').addClass('focused');
		});
		$('.creativecontactform_input_element input,.creativecontactform_input_element textarea').blur(function() {
			$(this).parents('.creativecontactform_input_element').removeClass('focused');
		});


		var active_element;
		$('.colorSelector').click(function() {
			active_element = $(this);
		})
		
		//magic functions
		function create_backround_gradient() {

		}
		
		$('.colorSelector').ColorPicker({
			onBeforeShow: function () {
				$color = $(active_element).next('input').val();
				$(this).ColorPickerSetColor($color);
			},
			onShow: function (colpkr) {
				$(colpkr).fadeIn(500);
				return false;
			},
			onHide: function (colpkr) {
				$(colpkr).fadeOut(500);
				return false;
			},
			onChange: function (hsb, hex, rgb) {

				$(active_element).children('div').css('backgroundColor', '#' + hex);
				$(active_element).next('input').val('#' + hex);
				roll = $(active_element).next('input').attr('roll');

				//main wrapper
				if(roll == 0 || roll == 130) {
					if($("#elem-627").val() == 1) {

						var back = '-webkit-linear-gradient(top, ' + $("#elem-0").val() + ', '  + $("#elem-130").val() + ')';
						$(".creativecontactform_wrapper").css('background' , back);
						back = '-webkit-gradient(linear, 0% 0%, 0% 100%, from(' + $("#elem-0").val() + '), to('  + $("#elem-130").val() + '))';
						$(".creativecontactform_wrapper").css('background' , back);
						back = '-moz-linear-gradient(top, ' + $("#elem-0").val() + ', '  + $("#elem-130").val() + ')';
						$(".creativecontactform_wrapper").css('background' , back);
						back = '-ms-linear-gradient(top, ' + $("#elem-0").val() + ', '  + $("#elem-130").val() + ')';
						$(".creativecontactform_wrapper").css('background' , back);
						back = '-o-linear-gradient(top, ' + $("#elem-0").val() + ', '  + $("#elem-130").val() + ')';
						$(".creativecontactform_wrapper").css('background' , back);
						fil = ' progid:DXImageTransform.Microsoft.gradient(startColorstr=' + $("#elem-0").val() + ', endColorstr='  + $("#elem-130").val() + ')';
						$(".creativecontactform_wrapper").css('filter' , fil);

						$(".creativecontactform_wrapper").css('background-color' , $("#elem-0").val());
					}
					else {
						$(".creativecontactform_wrapper").css('background' , 'none');
						$(".creativecontactform_wrapper").css('background-color' , $("#elem-0").val());
					}

				}
				// header styles
				else if(roll == 601 || roll == 602) {
					$(".creativecontactform_header").css('backgroundColor' , '#' + hex);

					var back = '-webkit-linear-gradient(top, ' + $("#elem-601").val() + ', '  + $("#elem-602").val() + ')';
					$(".creativecontactform_header").css('background' , back);
					back = '-webkit-gradient(linear, 0% 0%, 0% 100%, from(' + $("#elem-601").val() + '), to('  + $("#elem-602").val() + '))';
					$(".creativecontactform_header").css('background' , back);
					back = '-moz-linear-gradient(top, ' + $("#elem-601").val() + ', '  + $("#elem-602").val() + ')';
					$(".creativecontactform_header").css('background' , back);
					back = '-ms-linear-gradient(top, ' + $("#elem-601").val() + ', '  + $("#elem-602").val() + ')';
					$(".creativecontactform_header").css('background' , back);
					back = '-o-linear-gradient(top, ' + $("#elem-601").val() + ', '  + $("#elem-602").val() + ')';
					$(".creativecontactform_header").css('background' , back);
					fil = ' progid:DXImageTransform.Microsoft.gradient(startColorstr=' + $("#elem-601").val() + ', endColorstr='  + $("#elem-602").val() + ')';
					$(".creativecontactform_header").css('filter' , fil);

				}
				// body styles
				else if(roll == 611 || roll == 612) {

					$(".creativecontactform_body").css('backgroundColor' , '#' + hex);

					var back = '-webkit-linear-gradient(top, ' + $("#elem-611").val() + ', '  + $("#elem-612").val() + ')';
					$(".creativecontactform_body").css('background' , back);
					back = '-webkit-gradient(linear, 0% 0%, 0% 100%, from(' + $("#elem-611").val() + '), to('  + $("#elem-612").val() + '))';
					$(".creativecontactform_body").css('background' , back);
					back = '-moz-linear-gradient(top, ' + $("#elem-611").val() + ', '  + $("#elem-612").val() + ')';
					$(".creativecontactform_body").css('background' , back);
					back = '-ms-linear-gradient(top, ' + $("#elem-611").val() + ', '  + $("#elem-612").val() + ')';
					$(".creativecontactform_body").css('background' , back);
					back = '-o-linear-gradient(top, ' + $("#elem-611").val() + ', '  + $("#elem-612").val() + ')';
					$(".creativecontactform_body").css('background' , back);
					fil = ' progid:DXImageTransform.Microsoft.gradient(startColorstr=' + $("#elem-611").val() + ', endColorstr='  + $("#elem-612").val() + ')';
					$(".creativecontactform_body").css('filter' , fil);
				}
				// footer styles
				else if(roll == 618 || roll == 619) {
					$(".creativecontactform_footer").css('backgroundColor' , '#' + hex);

					var back = '-webkit-linear-gradient(top, ' + $("#elem-618").val() + ', '  + $("#elem-619").val() + ')';
					$(".creativecontactform_footer").css('background' , back);
					back = '-webkit-gradient(linear, 0% 0%, 0% 100%, from(' + $("#elem-618").val() + '), to('  + $("#elem-619").val() + '))';
					$(".creativecontactform_footer").css('background' , back);
					back = '-moz-linear-gradient(top, ' + $("#elem-618").val() + ', '  + $("#elem-619").val() + ')';
					$(".creativecontactform_footer").css('background' , back);
					back = '-ms-linear-gradient(top, ' + $("#elem-618").val() + ', '  + $("#elem-619").val() + ')';
					$(".creativecontactform_footer").css('background' , back);
					back = '-o-linear-gradient(top, ' + $("#elem-618").val() + ', '  + $("#elem-619").val() + ')';
					$(".creativecontactform_footer").css('background' , back);
					fil = ' progid:DXImageTransform.Microsoft.gradient(startColorstr=' + $("#elem-618").val() + ', endColorstr='  + $("#elem-619").val() + ')';
					$(".creativecontactform_footer").css('filter' , fil);
				}
				else if(roll == 1) {
					$(".creativecontactform_wrapper").css('borderColor' , '#' + hex);
				}
				else if(roll == 8) {
					var boxShadow = $("#elem-9").val() + ' ' + $("#elem-10").val() + 'px '  + $("#elem-11").val() + 'px '  + $("#elem-12").val() + 'px ' + $("#elem-13").val() + 'px ' + $("#elem-8").val();
					var boxShadow_ = $("#elem-15").val() + ' ' + $("#elem-16").val() + 'px '  + $("#elem-17").val() + 'px '  + $("#elem-18").val() + 'px ' + $("#elem-19").val() + 'px  ' + $("#elem-14").val();

					$(".creativecontactform_wrapper").css('boxShadow' , boxShadow);
					$(".creativecontactform_wrapper").hover(function() {
						$(this).css('boxShadow' , boxShadow_);
					},function() {
						$(this).css('boxShadow' , boxShadow);
					});

				}
				else if(roll == 14) {
					var boxShadow = $("#elem-9").val() + ' ' + $("#elem-10").val() + 'px '  + $("#elem-11").val() + 'px '  + $("#elem-12").val() + 'px ' + $("#elem-13").val() + 'px ' + $("#elem-8").val();
					var boxShadow_ = $("#elem-15").val() + ' ' + $("#elem-16").val() + 'px '  + $("#elem-17").val() + 'px '  + $("#elem-18").val() + 'px ' + $("#elem-19").val() + 'px  ' + $("#elem-14").val();
					
					$(".creativecontactform_wrapper").css('boxShadow' , boxShadow);
					$(".creativecontactform_wrapper").hover(function() {
						$(this).css('boxShadow' , boxShadow_);
					},function() {
						$(this).css('boxShadow' , boxShadow);
					});
				}
				//top text///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
				else if(roll == 20) {
					$(".creativecontactform_title").css('color' , '#' + hex);
				}
				else if(roll == 27) {
					var textShadow = $("#elem-28").val() + 'px '  + $("#elem-29").val() + 'px '  + $("#elem-30").val() + 'px ' + $("#elem-27").val();
					$(".creativecontactform_title").css('textShadow' , textShadow);
				}
				//field text///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
				else if(roll == 31) {
					$('.creativecontactform_field_box').not('.creativecontactform_error').find(".creativecontactform_field_name").css('color' , '#' + hex);
				}
				else if(roll == 37) {
					var textShadow = $("#elem-38").val() + 'px '  + $("#elem-39").val() + 'px '  + $("#elem-40").val() + 'px ' + $("#elem-37").val();
					$('.creativecontactform_field_box').not('.creativecontactform_error').find(".creativecontactform_field_name").css('textShadow' , textShadow);
				}
				//asterisk text///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
				else if(roll == 41) {
					$(".creativecontactform_field_required").css('color' , '#' + hex);
				}
				else if(roll == 46) {
					var textShadow = $("#elem-47").val() + 'px '  + $("#elem-48").val() + 'px '  + $("#elem-49").val() + 'px ' + $("#elem-46").val();
					$(".creativecontactform_field_required").css('textShadow' , textShadow);
				}

				//creativecontactform_send////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
				else if(roll == 91 || roll == 50 ) {
					var backColor_ = $("#elem-91").val();
					$(".creativecontactform_send").not('.creativecontactform_send_hovered').css('backgroundColor' , backColor_);

					var back = '-webkit-linear-gradient(top, ' + $("#elem-91").val() + ', '  + $("#elem-50").val() + ')';
					$(".creativecontactform_send").not('.creativecontactform_send_hovered').css('background' , back);
					back = '-webkit-gradient(linear, 0% 0%, 0% 100%, from(' + $("#elem-91").val() + '), to('  + $("#elem-50").val() + '))';
					$(".creativecontactform_send").not('.creativecontactform_send_hovered').css('background' , back);
					back = '-moz-linear-gradient(top, ' + $("#elem-91").val() + ', '  + $("#elem-50").val() + ')';
					$(".creativecontactform_send").not('.creativecontactform_send_hovered').css('background' , back);
					back = '-ms-linear-gradient(top, ' + $("#elem-91").val() + ', '  + $("#elem-50").val() + ')';
					$(".creativecontactform_send").not('.creativecontactform_send_hovered').css('background' , back);
					back = '-o-linear-gradient(top, ' + $("#elem-91").val() + ', '  + $("#elem-50").val() + ')';
					$(".creativecontactform_send").not('.creativecontactform_send_hovered').css('background' , back);
					fil = ' progid:DXImageTransform.Microsoft.gradient(startColorstr=' + $("#elem-91").val() + ', endColorstr='  + $("#elem-50").val() + ')';
					$(".creativecontactform_send").not('.creativecontactform_send_hovered').css('filter' , fil);

				}
				else if(roll == 51 || roll == 52 ) {
					var backColor_ = $("#elem-51").val();
					$(".creativecontactform_send_hovered").css('backgroundColor' , backColor_);

					var back = '-webkit-linear-gradient(top, ' + $("#elem-51").val() + ', '  + $("#elem-52").val() + ')';
					$(".creativecontactform_send_hovered").css('background' , back);
					back = '-webkit-gradient(linear, 0% 0%, 0% 100%, from(' + $("#elem-51").val() + '), to('  + $("#elem-52").val() + '))';
					$(".creativecontactform_send_hovered").css('background' , back);
					back = '-moz-linear-gradient(top, ' + $("#elem-51").val() + ', '  + $("#elem-52").val() + ')';
					$(".creativecontactform_send_hovered").css('background' , back);
					back = '-ms-linear-gradient(top, ' + $("#elem-51").val() + ', '  + $("#elem-52").val() + ')';
					$(".creativecontactform_send_hovered").css('background' , back);
					back = '-o-linear-gradient(top, ' + $("#elem-51").val() + ', '  + $("#elem-52").val() + ')';
					$(".creativecontactform_send_hovered").css('background' , back);
					fil = ' progid:DXImageTransform.Microsoft.gradient(startColorstr=' + $("#elem-51").val() + ', endColorstr='  + $("#elem-52").val() + ')';
					$(".creativecontactform_send_hovered").css('filter' , fil);
				}
				else if(roll == 100) {//answer animation backgroundColor
					var borderColor_ = $("#elem-100").val();
					$(".creativecontactform_send").not('.creativecontactform_send_hovered').css('borderColor' , borderColor_);

				}
				else if(roll == 126) {
					var borderColor_ = $("#elem-126").val();
					$(".creativecontactform_send_hovered").css('borderColor' , borderColor_);
				}
				else if(roll == 94) { //
					var boxShadow_ = $("#elem-95").val() + ' ' + $("#elem-96").val() + 'px '  + $("#elem-97").val() + 'px '  + $("#elem-98").val() + 'px ' + $("#elem-99").val() + 'px ' + $("#elem-94").val();
					$(".creativecontactform_send").not('.creativecontactform_send_hovered').css('boxShadow' , boxShadow_);
				}
				else if(roll == 117) { //
					var boxShadow = $("#elem-118").val() + ' ' + $("#elem-119").val() + 'px '  + $("#elem-120").val() + 'px '  + $("#elem-121").val() + 'px ' + $("#elem-122").val() + 'px ' +  $("#elem-117").val();
					$(".creativecontactform_send_hovered").css('boxShadow' , boxShadow);
				}
				else if(roll == 106) {
					var textColor_ = $("#elem-106").val();
					$(".creativecontactform_send").not('.creativecontactform_send_hovered').css('color' , textColor_);
				}
				else if(roll == 124) {
					var textColor_ = $("#elem-124").val();
					$(".creativecontactform_send_hovered").css('color' , textColor_);
				}
				else if(roll == 113) { 
					var textShadow_ = $("#elem-114").val() + 'px '  + $("#elem-115").val() + 'px '  + $("#elem-116").val() + 'px ' + $("#elem-113").val();
					$(".creativecontactform_send").not('.creativecontactform_send_hovered').css('textShadow' , textShadow_);
				}
				else if(roll == 125) { 
					var textShadow = $("#elem-114").val() + 'px '  + $("#elem-115").val() + 'px '  + $("#elem-116").val() + 'px ' + $("#elem-125").val();
					$(".creativecontactform_send_hovered").css('textShadow' , textShadow);
				}

				//creativecontactform text inputs////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
				else if(roll == 132 || roll == 133 || roll == 157 || roll == 158) {//answer animation backgroundColor
					var backColor_ = $("#elem-132").val();
					$(".creativecontactform_input_element").not('.creative_error_input').css('backgroundColor' , backColor_);

					var back = '-webkit-linear-gradient(top, ' + $("#elem-132").val() + ', '  + $("#elem-133").val() + ')';
					$(".creativecontactform_input_element").not('.creative_error_input').not('.creativecontactform_input_element_hovered').css('background' , back);

					back = '-webkit-gradient(linear, 0% 0%, 0% 100%, from(' + $("#elem-132").val() + '), to('  + $("#elem-133").val() + '))';
					$(".creativecontactform_input_element").not('.creative_error_input').not('.creativecontactform_input_element_hovered').css('background' , back);

					back = '-moz-linear-gradient(top, ' + $("#elem-132").val() + ', '  + $("#elem-133").val() + ')';
					$(".creativecontactform_input_element").not('.creative_error_input').not('.creativecontactform_input_element_hovered').css('background' , back);

					back = '-ms-linear-gradient(top, ' + $("#elem-132").val() + ', '  + $("#elem-133").val() + ')';
					$(".creativecontactform_input_element").not('.creative_error_input').not('.creativecontactform_input_element_hovered').css('background' , back);

					back = '-o-linear-gradient(top, ' + $("#elem-132").val() + ', '  + $("#elem-133").val() + ')';
					$(".creativecontactform_input_element").not('.creative_error_input').not('.creativecontactform_input_element_hovered').css('background' , back);

					fil = ' progid:DXImageTransform.Microsoft.gradient(startColorstr=' + $("#elem-132").val() + ', endColorstr='  + $("#elem-133").val() + ')';
					$(".creativecontactform_input_element").not('.creative_error_input').not('.creativecontactform_input_element_hovered').css('filter' , fil);
					
					// hovered state

					var back = '-webkit-linear-gradient(top, ' + $("#elem-157").val() + ', '  + $("#elem-158").val() + ')';
					$(".creativecontactform_input_element_hovered").not('.creative_error_input').css('background' , back);
					back = '-webkit-gradient(linear, 0% 0%, 0% 100%, from(' + $("#elem-157").val() + '), to('  + $("#elem-158").val() + '))';
					$(".creativecontactform_input_element_hovered").not('.creative_error_input').css('background' , back);
					back = '-moz-linear-gradient(top, ' + $("#elem-157").val() + ', '  + $("#elem-158").val() + ')';
					$(".creativecontactform_input_element_hovered").not('.creative_error_input').css('background' , back);
					back = '-ms-linear-gradient(top, ' + $("#elem-157").val() + ', '  + $("#elem-158").val() + ')';
					$(".creativecontactform_input_element_hovered").not('.creative_error_input').css('background' , back);
					back = '-o-linear-gradient(top, ' + $("#elem-157").val() + ', '  + $("#elem-158").val() + ')';
					$(".creativecontactform_input_element_hovered").not('.creative_error_input').css('background' , back);
					fil = ' progid:DXImageTransform.Microsoft.gradient(startColorstr=' + $("#elem-157").val() + ', endColorstr='  + $("#elem-158").val() + ')';
					$(".creativecontactform_input_element_hovered").not('.creative_error_input').css('filter' , fil);

				}
				else if(roll == 134 || roll == 161) {//answer animation backgroundColor
					var borderColor = $("#elem-134").val();
					var borderColor_ = $("#elem-161").val();
					$(".creativecontactform_input_element").not('.creative_error_input').not('.creativecontactform_input_element_hovered').css('borderColor' , borderColor);
					
					$(".creativecontactform_input_element_hovered").css('borderColor' , borderColor_);
				}
				else if(roll == 141 || roll == 162) { 

					var boxShadow = $("#elem-142").val() + ' ' + $("#elem-143").val() + 'px '  + $("#elem-144").val() + 'px '  + $("#elem-145").val() + 'px ' + $("#elem-146").val() + 'px ' +  $("#elem-141").val();
					var boxShadow_ = $("#elem-163").val() + ' ' + $("#elem-164").val() + 'px '  + $("#elem-165").val() + 'px '  + $("#elem-166").val() + 'px ' + $("#elem-167").val() + 'px ' +  $("#elem-162").val();
					$(".creativecontactform_input_element").not('.creative_error_input').not('.creativecontactform_input_element_hovered').css('boxShadow' , boxShadow);
					
					$(".creativecontactform_input_element_hovered").css('boxShadow' , boxShadow_);
				}
				else if(roll == 147 || roll == 159) {
					var textColor = $("#elem-147").val();
					var textColor_ = $("#elem-159").val();
					$(".creativecontactform_input_element").not('.creative_error_input').not('.creativecontactform_input_element_hovered').find('input').css('color' , textColor);
					$(".creativecontactform_input_element textarea").css('color' , textColor);

					$(".creativecontactform_input_element_hovered input").css('color' , textColor_);
				}
				else if(roll == 153 || roll == 160) { 
					var textShadow = $("#elem-154").val() + 'px '  + $("#elem-155").val() + 'px '  + $("#elem-156").val() + 'px ' + $("#elem-153").val();
					var textShadow_ = $("#elem-154").val() + 'px '  + $("#elem-155").val() + 'px '  + $("#elem-156").val() + 'px ' + $("#elem-160").val();
					$(".creativecontactform_input_element").not('.creative_error_input').not('.creativecontactform_input_element_hovered').find('input').css('textShadow' , textShadow);
					$(".creativecontactform_input_element textarea").css('textShadow' , textShadow);
					
					$(".creativecontactform_input_element_hovered input").css('textShadow' , textShadow_);
				}
				//Error State////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        	
	        	else if(roll == 171) {
					$(".creativecontactform_error .creativecontactform_field_name").css('color' , '#' + hex);
				}
				else if(roll == 172) {
					var textShadow = $("#elem-173").val() + 'px '  + $("#elem-174").val() + 'px '  + $("#elem-175").val() + 'px ' + $("#elem-172").val();
					$(".creativecontactform_error .creativecontactform_field_name").css('textShadow' , textShadow);
				}
				
				else if(roll == 176 || roll == 177) {
					var backColor = $("#elem-176").val();
					$(".creativecontactform_error .creativecontactform_input_element").css('backgroundColor' , backColor);

					var back = '-webkit-linear-gradient(top, ' + $("#elem-176").val() + ', '  + $("#elem-177").val() + ')';
					$(".creativecontactform_error .creativecontactform_input_element").css('background' , back);
					back = '-webkit-gradient(linear, 0% 0%, 0% 100%, from(' + $("#elem-176").val() + '), to('  + $("#elem-177").val() + '))';
					$(".creativecontactform_error .creativecontactform_input_element").css('background' , back);
					back = '-moz-linear-gradient(top, ' + $("#elem-176").val() + ', '  + $("#elem-177").val() + ')';
					$(".creativecontactform_error .creativecontactform_input_element").css('background' , back);
					back = '-ms-linear-gradient(top, ' + $("#elem-176").val() + ', '  + $("#elem-177").val() + ')';
					$(".creativecontactform_error .creativecontactform_input_element").css('background' , back);
					back = '-o-linear-gradient(top, ' + $("#elem-176").val() + ', '  + $("#elem-177").val() + ')';
					$(".creativecontactform_error .creativecontactform_input_element").css('background' , back);
					fil = ' progid:DXImageTransform.Microsoft.gradient(startColorstr=' + $("#elem-176").val() + ', endColorstr='  + $("#elem-177").val() + ')';
					$(".creativecontactform_error .creativecontactform_input_element").css('filter' , fil);

				}
				else if(roll == 178) {
					var borderColor = $("#elem-178").val();
					$(".creativecontactform_error .creativecontactform_input_element").css('borderColor' , borderColor);
				}
				else if(roll == 179) {
					var fontColor = $("#elem-179").val();
					$(".creativecontactform_error input").css('color' , fontColor);
				}
				else if(roll == 184) { 
					var boxShadow = $("#elem-185").val() + ' ' + $("#elem-186").val() + 'px '  + $("#elem-187").val() + 'px '  + $("#elem-188").val() + 'px ' + $("#elem-189").val() + 'px ' +  $("#elem-184").val();
					$(".creativecontactform_error .creativecontactform_input_element").css('boxShadow' , boxShadow);
				}
				else if(roll == 180) { 
					var textShadow = $("#elem-181").val() + 'px '  + $("#elem-182").val() + 'px '  + $("#elem-183").val() + 'px ' + $("#elem-180").val();
					$(".creativecontactform_error input").css('textShadow' , textShadow);
				}
				/*pre text ********************************************************************************************************************************************************************************/
	        	else if(roll == 195) { 
					var borderTop = $("#elem-194").val() + 'px '  + $("#elem-196").val() + $("#elem-195").val();
					$(".creativecontactform_pre_text").css('borderTop' , borderTop);
				}
	        	else if(roll == 197) {
					$(".creativecontactform_pre_text").css('color' , $("#elem-197").val());
				}
	        	else if(roll == 203) { 
					var textShadow = $("#elem-204").val() + 'px '  + $("#elem-205").val() + 'px '  + $("#elem-206").val() + 'px ' + $("#elem-203").val();
					$(".creativecontactform_pre_text").css('textShadow' , textShadow);
				}


			/*creativecontactform_headding ********************************************************************************************************************************************************************************/
				else if(roll == 541 || roll == 542) {
					var backColor = $("#elem-541").val();
					$(".creativecontactform_heading").css('backgroundColor' , backColor);

					var back = '-webkit-linear-gradient(top, ' + $("#elem-541").val() + ', '  + $("#elem-542").val() + ')';
					$(".creativecontactform_heading").css('background' , back);
					back = '-webkit-gradient(linear, 0% 0%, 0% 100%, from(' + $("#elem-541").val() + '), to('  + $("#elem-542").val() + '))';
					$(".creativecontactform_heading").css('background' , back);
					back = '-moz-linear-gradient(top, ' + $("#elem-541").val() + ', '  + $("#elem-542").val() + ')';
					$(".creativecontactform_heading").css('background' , back);
					back = '-ms-linear-gradient(top, ' + $("#elem-541").val() + ', '  + $("#elem-542").val() + ')';
					$(".creativecontactform_heading").css('background' , back);
					back = '-o-linear-gradient(top, ' + $("#elem-541").val() + ', '  + $("#elem-542").val() + ')';
					$(".creativecontactform_heading").css('background' , back);
					fil = ' progid:DXImageTransform.Microsoft.gradient(startColorstr=' + $("#elem-541").val() + ', endColorstr='  + $("#elem-542").val() + ')';
					$(".creativecontactform_heading").css('filter' , fil);

				}
				else if(roll == 548 || roll == 549 || roll == 550 || roll == 551) { 
	        		var border_top = $("#elem-543").val() + 'px ' + $("#elem-547").val() + ' ' + $("#elem-548").val();
	        		var border_right = $("#elem-544").val() + 'px ' + $("#elem-547").val() + ' ' + $("#elem-549").val();
	        		var border_bottom = $("#elem-545").val() + 'px ' + $("#elem-547").val() + ' ' + $("#elem-550").val();
	        		var border_left = $("#elem-546").val() + 'px ' + $("#elem-547").val() + ' ' + $("#elem-551").val();
					$(".creativecontactform_heading").css('border-top' , border_top);
					$(".creativecontactform_heading").css('border-right' , border_right);
					$(".creativecontactform_heading").css('border-bottom' , border_bottom);
					$(".creativecontactform_heading").css('border-left' , border_left);
	        	}
	        	else if(roll == 531) { 
					var textShadow = $("#elem-532").val() + 'px '  + $("#elem-533").val() + 'px '  + $("#elem-534").val() + 'px ' + $("#elem-531").val();
					$(".creativecontactform_heading").css('textShadow' , textShadow);
				}
	        	else if(roll == 524) { 
					var fontColor = $("#elem-524").val();
					$(".creativecontactform_heading").css('color' ,fontColor);
				}

	        	else if(roll == 587) { 
					var fontColor = $("#elem-587").val();
					$(".creativecontactform_wrapper").css('color' ,fontColor);
				}	        	
				else if(roll == 553) { 
					var fontColor = $("#elem-553").val();
					$(".ccf_content_element_label").css('color' ,fontColor);
				}
	        	else if(roll == 558) { 
					var textShadow = $("#elem-559").val() + 'px '  + $("#elem-560").val() + 'px '  + $("#elem-561").val() + 'px ' + $("#elem-558").val();
					$(".ccf_content_element_label").css('textShadow' , textShadow);
				}
	        	else if(roll == 592) { 
					var borderBottom = $("#elem-590").val() + 'px '  + $("#elem-591").val() + ' ' + $("#elem-592").val();
					$(".ccf_content_element_label").css('border-bottom' , borderBottom);
				}

	        	else if(roll == 564) { 
					var fontColor = $("#elem-564").val();
					$(".ccf_link").css('color' ,fontColor);
				}
	        	else if(roll == 570) { 
					var textShadow = $("#elem-571").val() + 'px '  + $("#elem-572").val() + 'px '  + $("#elem-573").val() + 'px ' + $("#elem-570").val();
					$(".ccf_link").css('textShadow' , textShadow);
				}
	        	else if(roll == 569) { 
					var borderBottom = $("#elem-567").val() + 'px '  + $("#elem-568").val() + ' ' + $("#elem-569").val();
					$(".ccf_link").css('border-bottom' , borderBottom);
				}

	        	else if(roll == 574) { 
					var fontColor = $("#elem-574").val();
					$(".ccf_link_hovered").css('color' ,fontColor);
				}
	        	else if(roll == 576) { 
					var textShadow = $("#elem-577").val() + 'px '  + $("#elem-578").val() + 'px '  + $("#elem-579").val() + 'px ' + $("#elem-576").val();
					$(".ccf_link_hovered").css('textShadow' , textShadow);
				}
	        	else if(roll == 575) { 
					var borderBottom = $("#elem-567").val() + 'px '  + $("#elem-568").val() + ' ' + $("#elem-575").val();
					$(".ccf_link_hovered").css('border-bottom' , borderBottom);
				}


	        	else if(roll == 580) { 
					var fontColor = $("#elem-580").val();
					$(".ccf_content_styling").css('color' ,fontColor);
				}
	        	else if(roll == 583) { 
					var textShadow = $("#elem-584").val() + 'px '  + $("#elem-585").val() + 'px '  + $("#elem-586").val() + 'px ' + $("#elem-583").val();
					$(".ccf_content_styling").css('textShadow' , textShadow);
				}

	        	else if(roll == 609) { 
					var borderBottom = $("#elem-607").val() + 'px '  + $("#elem-608").val() + ' ' + $("#elem-609").val();
					$(".creativecontactform_header").css('border-bottom' , borderBottom);
				}
	        	else if(roll == 626) { 
					var borderTop = $("#elem-624").val() + 'px '  + $("#elem-625").val() + ' ' + $("#elem-626").val();
					$(".creativecontactform_footer").css('border-top' , borderTop);
				}
	        	
			}
		});

		//size up
		var up_int,down_int,curr_up,curr_down;
		$('.size_up').mousedown(function() {
			
			var $this = $(this);
			curr_up = parseInt($this.parent('div').prev('input').val());
			up_int = setInterval(function() {
				max_val = parseInt($this.attr("maxval"));
				val = parseInt($this.parent('div').prev('input').val());
				if(val < max_val) {
					$this.parent('div').prev('input').val(val*1 + 1);
					roll = $this.parent('div').prev('input').attr('roll');
					move_up(roll,val);
				}
			},100);
		})
		
		$('.size_up').mouseup(function() {
			clearInterval(up_int);
			var $this = $(this);
			max_val = parseInt($this.attr("maxval"));
			val = parseInt($this.parent('div').prev('input').val());
			if((val < max_val) && (curr_up == val)) {
				$this.parent('div').prev('input').val(val*1 + 1);
				roll = $this.parent('div').prev('input').attr('roll');
				move_up(roll,val);
			}
		});

		$('.size_up').mouseleave(function() {
			clearInterval(up_int);
		});

		function move_up(roll,val) {
			console.log(val);
			if(roll == 2) {
				$(".creativecontactform_wrapper").css({
					borderLeftWidth : val*1 + 1,
					borderRightWidth : val*1 + 1,
					borderBottomWidth : val*1 + 1,
					borderTopWidth : val*1 + 1
				});
			}
			else if(roll == 4) {
				$(".creativecontactform_wrapper").css('border-top-left-radius' , val*1 + 1);
			}
			else if(roll == 5) {
				$(".creativecontactform_wrapper").css('border-top-right-radius' , val*1 + 1);
			}
			else if(roll == 6) {
				$(".creativecontactform_wrapper").css('border-bottom-left-radius' , val*1 + 1);
			}
			else if(roll == 7) {
				$(".creativecontactform_wrapper").css('border-bottom-right-radius' , val*1 + 1);
			}
			else if(roll == 10 || roll == 11 || roll == 12 || roll == 13  || roll == 16  || roll == 17  || roll == 18  || roll == 19  ) { 
				var boxShadow = $("#elem-9").val() + ' ' + $("#elem-10").val() + 'px '  + $("#elem-11").val() + 'px '  + $("#elem-12").val() + 'px ' + $("#elem-13").val() + 'px ' + $("#elem-8").val();
				var boxShadow_ = $("#elem-15").val() + ' ' + $("#elem-16").val() + 'px '  + $("#elem-17").val() + 'px '  + $("#elem-18").val() + 'px ' + $("#elem-19").val() + 'px  ' + $("#elem-14").val();
				
				$(".creativecontactform_wrapper").css('boxShadow' , boxShadow);
				$(".creativecontactform_wrapper").hover(function() {
					$(this).css('boxShadow' , boxShadow_);
				},function() {
					$(this).css('boxShadow' , boxShadow);
				});
			}
			//top text
			else if(roll == 21) {
				$(".creativecontactform_title").css('fontSize' , val*1 + 1);
			}
			else if(roll == 28 || roll == 29 || roll == 30 ) {
				var textShadow = $("#elem-28").val() + 'px '  + $("#elem-29").val() + 'px '  + $("#elem-30").val() + 'px ' + $("#elem-27").val();
				$(".creativecontactform_title").css('textShadow' , textShadow);
			}
			//field text
			else if(roll == 32) {
				$(".creativecontactform_field_name").css('fontSize' , val*1 + 1);
			}
			else if(roll == 38 || roll == 39 || roll == 40) {
				var textShadow = $("#elem-38").val() + 'px '  + $("#elem-39").val() + 'px '  + $("#elem-40").val() + 'px ' + $("#elem-37").val();
				$('.creativecontactform_field_box').not('.creativecontactform_error').find(".creativecontactform_field_name").css('textShadow' , textShadow);
			}
			//asterisk text///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			else if(roll == 42) {
				$(".creativecontactform_field_required").css('fontSize' , val*1 + 1);
			}
			else if(roll == 47 || roll == 48 || roll == 49) {
				var textShadow = $("#elem-47").val() + 'px '  + $("#elem-48").val() + 'px '  + $("#elem-49").val() + 'px ' + $("#elem-46").val();
				$(".creativecontactform_field_required").css('textShadow' , textShadow);
			}

			//file upload///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			else if(roll == 597) {
				$(".creative_fileupload").css('paddingTop' , val*1 + 1);
				$(".creative_fileupload").css('paddingBottom' , val*1 + 1);
			}
			else if(roll == 598) {
				$(".creative_fileupload").css('paddingLeft' , val*1 + 1);
				$(".creative_fileupload").css('paddingRight' , val*1 + 1);
			}


			//send///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			else if(roll == 92) {
				$(".creativecontactform_send").not('.creative_fileupload').css('paddingTop' , val*1 + 1);
				$(".creativecontactform_send").not('.creative_fileupload').css('paddingBottom' , val*1 + 1);
			}
			else if(roll == 93) {
				$(".creativecontactform_send").not('.creative_fileupload').css('paddingLeft' , val*1 + 1);
				$(".creativecontactform_send").not('.creative_fileupload').css('paddingRight' , val*1 + 1);
			}
			else if(roll == 101) { //box border width
				$(".creativecontactform_send").css({
					borderLeftWidth : val*1 + 1,
					borderRightWidth : val*1 + 1,
					borderBottomWidth : val*1 + 1,
					borderTopWidth : val*1 + 1
				});
			}
			else if(roll == 102) {
				$(".creativecontactform_send").css('border-top-left-radius' , val*1 + 1);
			}
			else if(roll == 103) {
				$(".creativecontactform_send").css('border-top-right-radius' , val*1 + 1);
			}
			else if(roll == 104) {
				$(".creativecontactform_send").css('border-bottom-left-radius' , val*1 + 1);
			}
			else if(roll == 105) {
				$(".creativecontactform_send").css('border-bottom-right-radius' , val*1 + 1);
			}
			else if(roll == 96 || roll == 97 || roll == 98 || roll == 99) {
				var boxShadow_ = $("#elem-95").val() + ' ' + $("#elem-96").val() + 'px '  + $("#elem-97").val() + 'px '  + $("#elem-98").val() + 'px ' + $("#elem-99").val() + 'px ' + $("#elem-94").val();
				$(".creativecontactform_send").not('.creativecontactform_send_hovered').css('boxShadow' , boxShadow_);
			}
			else if(roll == 119 || roll == 120 || roll == 121 || roll == 122) {
				var boxShadow = $("#elem-118").val() + ' ' + $("#elem-119").val() + 'px '  + $("#elem-120").val() + 'px '  + $("#elem-121").val() + 'px ' + $("#elem-122").val() + 'px ' + $("#elem-117").val();
				$(".creativecontactform_send_hovered").css('boxShadow' , boxShadow);
			}
			else if(roll == 107) {
				$(".creativecontactform_send").css('fontSize' , val*1 + 1);
			}
			else if(roll == 114 || roll == 115 || roll == 116 ) {
				var textShadow = $("#elem-114").val() + 'px '  + $("#elem-115").val() + 'px '  + $("#elem-116").val() + 'px ' + $("#elem-125").val();
				var textShadow_ = $("#elem-114").val() + 'px '  + $("#elem-115").val() + 'px '  + $("#elem-116").val() + 'px ' + $("#elem-113").val();
				$(".creativecontactform_send").not('.creativecontactform_send_hovered').css('textShadow' , textShadow_);
				$(".creativecontactform_send_hovered").css('textShadow' , textShadow);
			}
			
			//text inputs///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			else if(roll == 135) { //box border width
				$(".creativecontactform_input_element").css({
					borderLeftWidth : val*1 + 1,
					borderRightWidth : val*1 + 1,
					borderBottomWidth : val*1 + 1,
					borderTopWidth : val*1 + 1
				});
			}
			else if(roll == 137) {
				$(".creativecontactform_input_element").css('border-top-left-radius' , val*1 + 1);
			}
			else if(roll == 138) {
				$(".creativecontactform_input_element").css('border-top-right-radius' , val*1 + 1);
			}
			else if(roll == 139) {
				$(".creativecontactform_input_element").css('border-bottom-left-radius' , val*1 + 1);
			}
			else if(roll == 140) {
				$(".creativecontactform_input_element").css('border-bottom-right-radius' , val*1 + 1);
			}

			else if(roll == 143 || roll == 144 || roll == 145 || roll == 146) { 

				var boxShadow = $("#elem-142").val() + ' ' + $("#elem-143").val() + 'px '  + $("#elem-144").val() + 'px '  + $("#elem-145").val() + 'px ' + $("#elem-146").val() + 'px ' +  $("#elem-141").val();
				var boxShadow_ = $("#elem-163").val() + ' ' + $("#elem-164").val() + 'px '  + $("#elem-165").val() + 'px '  + $("#elem-166").val() + 'px ' + $("#elem-167").val() + 'px ' +  $("#elem-162").val();
				$(".creativecontactform_input_element").not('.creative_error_input').not('.creativecontactform_input_element_hovered').css('boxShadow' , boxShadow);

			}
			else if(roll == 164 || roll == 165 || roll == 166 || roll == 167) { 
				var boxShadow_ = $("#elem-163").val() + ' ' + $("#elem-164").val() + 'px '  + $("#elem-165").val() + 'px '  + $("#elem-166").val() + 'px ' + $("#elem-167").val() + 'px ' +  $("#elem-162").val();
				$(".creativecontactform_input_element_hovered").css('boxShadow' , boxShadow_);
			}
			else if(roll == 154 || roll == 155 || roll == 156) { 
				var textShadow = $("#elem-154").val() + 'px '  + $("#elem-155").val() + 'px '  + $("#elem-156").val() + 'px ' + $("#elem-153").val();
				var textShadow_hovered = $("#elem-154").val() + 'px '  + $("#elem-155").val() + 'px '  + $("#elem-156").val() + 'px ' + $("#elem-160").val();

				$(".creativecontactform_input_element").not('.creative_error_input').not('.creativecontactform_input_element_hovered').find('input').css('textShadow' , textShadow);
				$(".creativecontactform_input_element textarea").css('textShadow' , textShadow);
				$(".creativecontactform_input_element_hovered input").css('textShadow' , textShadow_hovered);
			}


			else if(roll == 148) {
				$(".creativecontactform_input_element input,.creativecontactform_input_element textarea").css('fontSize' , val*1 + 1);
			}
			
			else if(roll == 168) {
				var w = val*1 + 1 + '%';
				$(".creativecontactform_field_box_inner").not('.creative_textarea_wrapper').css('width' , w);
			}
			else if(roll == 169) {
				var w = val*1 + 1 + '%';
				$(".creativecontactform_field_box_textarea_inner").css('width' , w);
			}
			else if(roll == 170) {
				$(".creative_textarea_wrapper").css('height' , val*1 + 1);
			}
			
			//Error State////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			else if(roll == 173 || roll == 174 || roll == 175) {
				var textShadow = $("#elem-173").val() + 'px '  + $("#elem-174").val() + 'px '  + $("#elem-175").val() + 'px ' + $("#elem-172").val();
				$(".creativecontactform_error .creativecontactform_field_name").css('textShadow' , textShadow);
			}
			else if(roll == 186 || roll == 187 || roll == 188 || roll == 189) { 
				var boxShadow = $("#elem-185").val() + ' ' + $("#elem-186").val() + 'px '  + $("#elem-187").val() + 'px '  + $("#elem-188").val() + 'px ' + $("#elem-189").val() + 'px ' +  $("#elem-184").val();
				$(".creativecontactform_error .creativecontactform_input_element").css('boxShadow' , boxShadow);
			}
			else if(roll == 181 || roll == 182 || roll == 183) { 
				var textShadow = $("#elem-181").val() + 'px '  + $("#elem-182").val() + 'px '  + $("#elem-183").val() + 'px ' + $("#elem-180").val();
				$(".creativecontactform_error input").css('textShadow' , textShadow);
			}
			/*pre text ********************************************************************************************************************************************************************************/
        	else if(roll == 190) { 
				var marginTop = $("#elem-190").val() + 'px';
				$(".creativecontactform_pre_text").css('marginTop' , marginTop);
			}
        	else if(roll == 191) { 
				var marginBottom = $("#elem-191").val() + 'px';
				$(".creativecontactform_pre_text").css('marginBottom' , marginBottom);
			}
        	else if(roll == 193) { 
				var paddingTop = $("#elem-193").val() + 'px';
				$(".creativecontactform_pre_text").css('paddingTop' , paddingTop);
			}
        	else if(roll == 192) { 
				var w = $("#elem-192").val() + '%';
				$(".creativecontactform_pre_text").css('width' , w);
			}
        	else if(roll == 198) { 
				var f = $("#elem-198").val();
				$(".creativecontactform_pre_text").css('fontSize' ,val*1 + 1);
			}
        	else if(roll == 194) { 
				var borderTop = $("#elem-194").val() + 'px '  + $("#elem-196").val() + $("#elem-195").val();
				$(".creativecontactform_pre_text").css('borderTop' , borderTop);
			}
        	else if(roll == 204 || roll == 205 || roll == 206) { 
				var textShadow = $("#elem-204").val() + 'px '  + $("#elem-205").val() + 'px '  + $("#elem-206").val() + 'px ' + $("#elem-203").val();
				$(".creativecontactform_pre_text").css('textShadow' , textShadow);
			}
			/*creativecontactform_wrapper_inner ********************************************************************************************************************************************************************************/
        	else if(roll == 207 || roll == 208 || roll == 213 || roll == 214) { 
        		var padding = $("#elem-207").val() + 'px ' + $("#elem-214").val() + 'px ' + $("#elem-213").val() + 'px ' + $("#elem-208").val() + 'px';
				$(".creativecontactform_wrapper_inner").css('padding' , padding);
			}
			/*field name ********************************************************************************************************************************************************************************/
        	else if(roll == 215 || roll == 216 || roll == 217 || roll == 218) { 
				var margin = $("#elem-215").val() + 'px ' + $("#elem-216").val() + 'px ' + $("#elem-217").val() + 'px ' + $("#elem-218").val() + 'px';
				$(".creativecontactform_field_name").css('margin' , margin);
			}
			/*creativecontactform_wrapper_inner ********************************************************************************************************************************************************************************/
        	else if(roll == 210 || roll == 211 || roll == 219 || roll == 220) { 
        		var margin = $("#elem-210").val() + 'px ' + $("#elem-211").val() + 'px ' + $("#elem-219").val() + 'px ' + $("#elem-220").val() + 'px';
				$(".creativecontactform_submit_wrapper").css('margin' , margin);
        	}
        	else if(roll == 209) { 
				var w = $("#elem-209").val() + '%';
				$(".creativecontactform_submit_wrapper").css('width' , w);
			}

			/*creativecontactform_headding ********************************************************************************************************************************************************************************/
			else if(roll == 535 || roll == 536 || roll == 537 || roll == 538) { 
        		var margin = $("#elem-535").val() + 'px ' + $("#elem-536").val() + 'px ' + $("#elem-537").val() + 'px ' + $("#elem-538").val() + 'px';
				$(".creativecontactform_heading_inner").css('margin' , margin);
        	}
			else if(roll == 539 || roll == 540) { 
        		var margin = $("#elem-539").val() + 'px  0px ' + $("#elem-540").val() + 'px 0px';
				$(".creativecontactform_heading").css('margin' , margin);
        	}
			else if(roll == 543 || roll == 544 || roll == 545 || roll == 546) { 
        		var border_top = $("#elem-543").val() + 'px ' + $("#elem-547").val() + ' ' + $("#elem-548").val();
        		var border_right = $("#elem-544").val() + 'px ' + $("#elem-547").val() + ' ' + $("#elem-549").val();
        		var border_bottom = $("#elem-545").val() + 'px ' + $("#elem-547").val() + ' ' + $("#elem-550").val();
        		var border_left = $("#elem-546").val() + 'px ' + $("#elem-547").val() + ' ' + $("#elem-551").val();
				$(".creativecontactform_heading").css('border-top' , border_top);
				$(".creativecontactform_heading").css('border-right' , border_right);
				$(".creativecontactform_heading").css('border-bottom' , border_bottom);
				$(".creativecontactform_heading").css('border-left' , border_left);
        	}
        	else if(roll == 532 || roll == 533 || roll == 534) { 
				var textShadow = $("#elem-532").val() + 'px '  + $("#elem-533").val() + 'px '  + $("#elem-534").val() + 'px ' + $("#elem-531").val();
				$(".creativecontactform_heading").css('textShadow' , textShadow);
			}
        	else if(roll == 525) { 
				var f = $("#elem-525").val() + 'px';
				$(".creativecontactform_heading").css('fontSize' ,f);
			}

			else if(roll == 588) { 
				var f = $("#elem-588").val() + 'px';
				$(".creativecontactform_wrapper").css('fontSize' ,f);
			}
			else if(roll == 554) { 
				var f = $("#elem-554").val() + 'px';
				$(".ccf_content_element_label").css('fontSize' ,f);
			}
        	else if(roll == 559 || roll == 560 || roll == 561) { 
				var textShadow = $("#elem-559").val() + 'px '  + $("#elem-560").val() + 'px '  + $("#elem-561").val() + 'px ' + $("#elem-558").val();
				$(".ccf_content_element_label").css('textShadow' , textShadow);
			}
        	else if(roll == 590) { 
				var borderBottom = $("#elem-590").val() + 'px '  + $("#elem-591").val() + ' ' + $("#elem-592").val();
				$(".ccf_content_element_label").css('border-bottom' , borderBottom);
			}
			else if(roll == 571 || roll == 572 || roll == 573) { 
				var textShadow = $("#elem-571").val() + 'px '  + $("#elem-572").val() + 'px '  + $("#elem-573").val() + 'px ' + $("#elem-570").val();
				$(".ccf_link").css('textShadow' , textShadow);
			}
        	else if(roll == 567) { 
				var borderBottom = $("#elem-567").val() + 'px '  + $("#elem-568").val() + ' ' + $("#elem-569").val();
				$(".ccf_link").css('border-bottom' , borderBottom);

				var borderBottom = $("#elem-567").val() + 'px '  + $("#elem-568").val() + ' ' + $("#elem-575").val();
				$(".ccf_link_hovered").css('border-bottom' , borderBottom);
			}
    	   	else if(roll == 577 || roll == 578 || roll == 579) { 
				var textShadow = $("#elem-577").val() + 'px '  + $("#elem-578").val() + 'px '  + $("#elem-579").val() + 'px ' + $("#elem-576").val();
				$(".ccf_link_hovered").css('textShadow' , textShadow);
			}
        	else if(roll == 584 || roll == 585 || roll == 586) { 
				var textShadow = $("#elem-584").val() + 'px '  + $("#elem-585").val() + 'px '  + $("#elem-586").val() + 'px ' + $("#elem-583").val();
				$(".ccf_content_styling").css('textShadow' , textShadow);
			}

			/*creativecontactform_header ********************************************************************************************************************************************************************************/
        	else if(roll == 603 || roll == 604 || roll == 605 || roll == 606) { 
        		var padding = $("#elem-603").val() + 'px ' + $("#elem-604").val() + 'px ' + $("#elem-605").val() + 'px ' + $("#elem-606").val() + 'px';
				$(".creativecontactform_header").css('padding' , padding);
			}
			/*creativecontactform_body  ********************************************************************************************************************************************************************************/
        	else if(roll == 613 || roll == 614 || roll == 615 || roll == 616) { 
        		var padding = $("#elem-613").val() + 'px ' + $("#elem-614").val() + 'px ' + $("#elem-615").val() + 'px ' + $("#elem-616").val() + 'px';
				$(".creativecontactform_body").css('padding' , padding);
			}
			/*creativecontactform_footer  ********************************************************************************************************************************************************************************/
        	else if(roll == 620 || roll == 621 || roll == 622 || roll == 623) { 
        		var padding = $("#elem-620").val() + 'px ' + $("#elem-621").val() + 'px ' + $("#elem-622").val() + 'px ' + $("#elem-623").val() + 'px';
				$(".creativecontactform_footer").css('padding' , padding);
			}
        	else if(roll == 607) { 
				var borderBottom = $("#elem-607").val() + 'px '  + $("#elem-608").val() + ' ' + $("#elem-609").val();
				$(".creativecontactform_header").css('border-bottom' , borderBottom);
			}
        	else if(roll == 624) { 
				var borderTop = $("#elem-624").val() + 'px '  + $("#elem-625").val() + ' ' + $("#elem-626").val();
				$(".creativecontactform_footer").css('border-top' , borderTop);
			}

		}
			
		$('.size_down').mousedown(function() {
			var $this = $(this);
			curr_down = parseInt($this.parent('div').prev('input').val());
			down_int = setInterval(function() {
				min_val = parseInt($this.attr("minval"));
				val = parseInt($this.parent('div').prev('input').val());
				if(val > min_val) {
					$this.parent('div').prev('input').val(val*1 - 1);
					roll = $this.parent('div').prev('input').attr('roll');
					move_down(roll,val);
				}
			},100);
		})
		
		$('.size_down').mouseup(function() {
			clearInterval(down_int);
			var $this = $(this);
			min_val = parseInt($this.attr("minval"));
			val = parseInt($this.parent('div').prev('input').val());
			if((val > min_val) && (curr_down == val)) {
				$this.parent('div').prev('input').val(val*1 - 1);
				roll = $this.parent('div').prev('input').attr('roll');
				move_down(roll,val);
			}
		})
		
		$('.size_down').mouseleave(function() {
			clearInterval(down_int);
		});

		function move_down(roll,val) {
			console.log(val);
			if(roll == 2) {
				$(".creativecontactform_wrapper").css({
					borderLeftWidth : val*1 - 1,
					borderRightWidth : val*1 - 1,
					borderBottomWidth : val*1 - 1,
					borderTopWidth : val*1 - 1
				});
			}
			else if(roll == 4) {
				$(".creativecontactform_wrapper").css('border-top-left-radius' , val*1 - 1);
			}
			else if(roll == 5) {
				$(".creativecontactform_wrapper").css('border-top-right-radius' , val*1 - 1);
			}
			else if(roll == 6) {
				$(".creativecontactform_wrapper").css('border-bottom-left-radius' , val*1 - 1);
			}
			else if(roll == 7) {
				$(".creativecontactform_wrapper").css('border-bottom-right-radius' , val*1 - 1);
			}
			else if(roll == 10 || roll == 11 || roll == 12 || roll == 13  || roll == 16  || roll == 17  || roll == 18  || roll == 19  ) { 
				var boxShadow = $("#elem-9").val() + ' ' + $("#elem-10").val() + 'px '  + $("#elem-11").val() + 'px '  + $("#elem-12").val() + 'px ' + $("#elem-13").val() + 'px ' + $("#elem-8").val();
				var boxShadow_ = $("#elem-15").val() + ' ' + $("#elem-16").val() + 'px '  + $("#elem-17").val() + 'px '  + $("#elem-18").val() + 'px ' + $("#elem-19").val() + 'px  ' + $("#elem-14").val();
				
				$(".creativecontactform_wrapper").css('boxShadow' , boxShadow);
				$(".creativecontactform_wrapper").hover(function() {
					$(this).css('boxShadow' , boxShadow_);
				},function() {
					$(this).css('boxShadow' , boxShadow);
				});
			}
			//top text
			else if(roll == 21) {
				$(".creativecontactform_title").css('fontSize' , val*1 - 1);
			}
			else if(roll == 28 || roll == 29 || roll == 30 ) {
				var textShadow = $("#elem-28").val() + 'px '  + $("#elem-29").val() + 'px '  + $("#elem-30").val() + 'px ' + $("#elem-27").val();
				$(".creativecontactform_title").css('textShadow' , textShadow);
			}
			//field text
			else if(roll == 32) {
				$(".creativecontactform_field_name").css('fontSize' , val*1 - 1);
			}
			else if(roll == 38 || roll == 39 || roll == 40) {
				var textShadow = $("#elem-38").val() + 'px '  + $("#elem-39").val() + 'px '  + $("#elem-40").val() + 'px ' + $("#elem-37").val();
				$('.creativecontactform_field_box').not('.creativecontactform_error').find(".creativecontactform_field_name").css('textShadow' , textShadow);
			}

			//asterisk text///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			else if(roll == 42) {
				$(".creativecontactform_field_required").css('fontSize' , val*1 + 1);
			}
			else if(roll == 47 || roll == 48 || roll == 49) {
				var textShadow = $("#elem-47").val() + 'px '  + $("#elem-48").val() + 'px '  + $("#elem-49").val() + 'px ' + $("#elem-46").val();
				$(".creativecontactform_field_required").css('textShadow' , textShadow);
			}
			//file upload///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			else if(roll == 597) {
				$(".creative_fileupload").css('paddingTop' , val*1 - 1);
				$(".creative_fileupload").css('paddingBottom' , val*1 - 1);
			}
			else if(roll == 598) {
				$(".creative_fileupload").css('paddingLeft' , val*1 - 1);
				$(".creative_fileupload").css('paddingRight' , val*1 - 1);
			}

			//send///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			else if(roll == 92) {
				$(".creativecontactform_send").not('.creative_fileupload').css('paddingTop' , val*1 - 1);
				$(".creativecontactform_send").not('.creative_fileupload').css('paddingBottom' , val*1 - 1);
			}
			else if(roll == 93) {
				$(".creativecontactform_send").not('.creative_fileupload').css('paddingLeft' , val*1 - 1);
				$(".creativecontactform_send").not('.creative_fileupload').css('paddingRight' , val*1 - 1);
			}
			else if(roll == 101) { //box border width
				$(".creativecontactform_send").css({
					borderLeftWidth : val*1 - 1,
					borderRightWidth : val*1 - 1,
					borderBottomWidth : val*1 - 1,
					borderTopWidth : val*1 - 1
				});
			}
			else if(roll == 102) {
				$(".creativecontactform_send").css('border-top-left-radius' , val*1 - 1);
			}
			else if(roll == 103) {
				$(".creativecontactform_send").css('border-top-right-radius' , val*1 - 1);
			}
			else if(roll == 104) {
				$(".creativecontactform_send").css('border-bottom-left-radius' , val*1 - 1);
			}
			else if(roll == 105) {
				$(".creativecontactform_send").css('border-bottom-right-radius' , val*1 - 1);
			}
			else if(roll == 96 || roll == 97 || roll == 98 || roll == 99) {
				var boxShadow_ = $("#elem-95").val() + ' ' + $("#elem-96").val() + 'px '  + $("#elem-97").val() + 'px '  + $("#elem-98").val() + 'px ' + $("#elem-99").val() + 'px ' + $("#elem-94").val();
				$(".creativecontactform_send").not('.creativecontactform_send_hovered').css('boxShadow' , boxShadow_);
			}
			else if(roll == 119 || roll == 120 || roll == 121 || roll == 122) {
				var boxShadow = $("#elem-118").val() + ' ' + $("#elem-119").val() + 'px '  + $("#elem-120").val() + 'px '  + $("#elem-121").val() + 'px ' + $("#elem-122").val() + 'px ' + $("#elem-117").val();
				$(".creativecontactform_send_hovered").css('boxShadow' , boxShadow);
			}
			else if(roll == 107) {
				$(".creativecontactform_send").css('fontSize' , val*1 - 1);
			}
			else if(roll == 114 || roll == 115 || roll == 116 ) {
				var textShadow = $("#elem-114").val() + 'px '  + $("#elem-115").val() + 'px '  + $("#elem-116").val() + 'px ' + $("#elem-125").val();
				var textShadow_ = $("#elem-114").val() + 'px '  + $("#elem-115").val() + 'px '  + $("#elem-116").val() + 'px ' + $("#elem-113").val();
				$(".creativecontactform_send").not('.creativecontactform_send_hovered').css('textShadow' , textShadow_);
				$(".creativecontactform_send_hovered").css('textShadow' , textShadow);
			}

			//text inputs///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			else if(roll == 135) { //box border width
				$(".creativecontactform_input_element").css({
					borderLeftWidth : val*1 - 1,
					borderRightWidth : val*1 - 1,
					borderBottomWidth : val*1 - 1,
					borderTopWidth : val*1 - 1
				});
			}
			else if(roll == 137) {
				$(".creativecontactform_input_element").css('border-top-left-radius' , val*1 - 1);
			}
			else if(roll == 138) {
				$(".creativecontactform_input_element").css('border-top-right-radius' , val*1 - 1);
			}
			else if(roll == 139) {
				$(".creativecontactform_input_element").css('border-bottom-left-radius' , val*1 - 1);
			}
			else if(roll == 140) {
				$(".creativecontactform_input_element").css('border-bottom-right-radius' , val*1 - 1);
			}

			else if(roll == 143 || roll == 144 || roll == 145 || roll == 146) { 

				var boxShadow = $("#elem-142").val() + ' ' + $("#elem-143").val() + 'px '  + $("#elem-144").val() + 'px '  + $("#elem-145").val() + 'px ' + $("#elem-146").val() + 'px ' +  $("#elem-141").val();
				var boxShadow_ = $("#elem-163").val() + ' ' + $("#elem-164").val() + 'px '  + $("#elem-165").val() + 'px '  + $("#elem-166").val() + 'px ' + $("#elem-167").val() + 'px ' +  $("#elem-162").val();
				$(".creativecontactform_input_element").not('.creative_error_input').not('.creativecontactform_input_element_hovered').css('boxShadow' , boxShadow);

			}
			else if(roll == 164 || roll == 165 || roll == 166 || roll == 167) { 
				var boxShadow_ = $("#elem-163").val() + ' ' + $("#elem-164").val() + 'px '  + $("#elem-165").val() + 'px '  + $("#elem-166").val() + 'px ' + $("#elem-167").val() + 'px ' +  $("#elem-162").val();
				$(".creativecontactform_input_element_hovered").css('boxShadow' , boxShadow_);
			}
			else if(roll == 154 || roll == 155 || roll == 156) { 
				var textShadow = $("#elem-154").val() + 'px '  + $("#elem-155").val() + 'px '  + $("#elem-156").val() + 'px ' + $("#elem-153").val();
				var textShadow_hovered = $("#elem-154").val() + 'px '  + $("#elem-155").val() + 'px '  + $("#elem-156").val() + 'px ' + $("#elem-160").val();

				$(".creativecontactform_input_element").not('.creative_error_input').not('.creativecontactform_input_element_hovered').find('input').css('textShadow' , textShadow);
				$(".creativecontactform_input_element textarea").css('textShadow' , textShadow);
				$(".creativecontactform_input_element_hovered input").css('textShadow' , textShadow_hovered);
			}

			else if(roll == 148) {
				$(".creativecontactform_input_element input,.creativecontactform_input_element textarea").css('fontSize' , val*1 - 1);
			}
			else if(roll == 168) {
				var w = val*1 - 1 + '%';
				$(".creativecontactform_field_box_inner").css('width' , w);
			}
			else if(roll == 169) {
				var w = val*1 - 1 + '%';
				$(".creativecontactform_field_box_textarea_inner").css('width' , w);
			}
			else if(roll == 170) {
				$(".creative_textarea_wrapper").css('height' , val*1 - 1);
			}
			
			//Error State////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			else if(roll == 173 || roll == 174 || roll == 175) {
				var textShadow = $("#elem-173").val() + 'px '  + $("#elem-174").val() + 'px '  + $("#elem-175").val() + 'px ' + $("#elem-172").val();
				$(".creativecontactform_error .creativecontactform_field_name").css('textShadow' , textShadow);
			}
			else if(roll == 186 || roll == 187 || roll == 188 || roll == 189) { 
				var boxShadow = $("#elem-185").val() + ' ' + $("#elem-186").val() + 'px '  + $("#elem-187").val() + 'px '  + $("#elem-188").val() + 'px ' + $("#elem-189").val() + 'px ' +  $("#elem-184").val();
				$(".creativecontactform_error .creativecontactform_input_element").css('boxShadow' , boxShadow);
			}
			else if(roll == 181 || roll == 182 || roll == 183) { 
				var textShadow = $("#elem-181").val() + 'px '  + $("#elem-182").val() + 'px '  + $("#elem-183").val() + 'px ' + $("#elem-180").val();
				$(".creativecontactform_error input").css('textShadow' , textShadow);
			}
			/*pre text ********************************************************************************************************************************************************************************/
        	else if(roll == 190) { 
				var marginTop = $("#elem-190").val() + 'px';
				$(".creativecontactform_pre_text").css('marginTop' , marginTop);
			}
        	else if(roll == 191) { 
				var marginBottom = $("#elem-191").val() + 'px';
				$(".creativecontactform_pre_text").css('marginBottom' , marginBottom);
			}
        	else if(roll == 193) { 
				var paddingTop = $("#elem-193").val() + 'px';
				$(".creativecontactform_pre_text").css('paddingTop' , paddingTop);
			}
        	else if(roll == 192) { 
				var w = $("#elem-192").val() + '%';
				$(".creativecontactform_pre_text").css('width' , w);
			}
        	else if(roll == 198) { 
				$(".creativecontactform_pre_text").css('fontSize' , val*1 - 1);
			}
        	else if(roll == 194) { 
				var borderTop = $("#elem-194").val() + 'px '  + $("#elem-196").val() + $("#elem-195").val();
				$(".creativecontactform_pre_text").css('borderTop' , borderTop);
			}
        	else if(roll == 204 || roll == 205 || roll == 206) { 
				var textShadow = $("#elem-204").val() + 'px '  + $("#elem-205").val() + 'px '  + $("#elem-206").val() + 'px ' + $("#elem-203").val();
				$(".creativecontactform_pre_text").css('textShadow' , textShadow);
			}
			/*creativecontactform_wrapper_inner ********************************************************************************************************************************************************************************/
        	else if(roll == 207 || roll == 208 || roll == 213 || roll == 214) { 
        		var padding = $("#elem-207").val() + 'px ' + $("#elem-214").val() + 'px ' + $("#elem-213").val() + 'px ' + $("#elem-208").val() + 'px';
				$(".creativecontactform_wrapper_inner").css('padding' , padding);
			}
			/*field name ********************************************************************************************************************************************************************************/
        	else if(roll == 215 || roll == 216 || roll == 217 || roll == 218) { 
				var margin = $("#elem-215").val() + 'px ' + $("#elem-216").val() + 'px ' + $("#elem-217").val() + 'px ' + $("#elem-218").val() + 'px';
				$(".creativecontactform_field_name").css('margin' , margin);
			}
			/*creativecontactform_wrapper_inner ********************************************************************************************************************************************************************************/
        	else if(roll == 210 || roll == 211 || roll == 219 || roll == 220) { 
        		var margin = $("#elem-210").val() + 'px ' + $("#elem-211").val() + 'px ' + $("#elem-219").val() + 'px ' + $("#elem-220").val() + 'px';
				$(".creativecontactform_submit_wrapper").css('margin' , margin);
        	}
        	else if(roll == 209) { 
				var w = $("#elem-209").val() + '%';
				$(".creativecontactform_submit_wrapper").css('width' , w);
			}

			/*creativecontactform_headding ********************************************************************************************************************************************************************************/
			else if(roll == 535 || roll == 536 || roll == 537 || roll == 538) { 
        		var margin = $("#elem-535").val() + 'px ' + $("#elem-536").val() + 'px ' + $("#elem-537").val() + 'px ' + $("#elem-538").val() + 'px';
				$(".creativecontactform_heading_inner").css('margin' , margin);
        	}
			else if(roll == 539 || roll == 540) { 
        		var margin = $("#elem-539").val() + 'px  0px ' + $("#elem-540").val() + 'px 0px';
				$(".creativecontactform_heading").css('margin' , margin);
        	}
			else if(roll == 543 || roll == 544 || roll == 545 || roll == 546) { 
        		var border_top = $("#elem-543").val() + 'px ' + $("#elem-547").val() + ' ' + $("#elem-548").val();
        		var border_right = $("#elem-544").val() + 'px ' + $("#elem-547").val() + ' ' + $("#elem-549").val();
        		var border_bottom = $("#elem-545").val() + 'px ' + $("#elem-547").val() + ' ' + $("#elem-550").val();
        		var border_left = $("#elem-546").val() + 'px ' + $("#elem-547").val() + ' ' + $("#elem-551").val();
				$(".creativecontactform_heading").css('border-top' , border_top);
				$(".creativecontactform_heading").css('border-right' , border_right);
				$(".creativecontactform_heading").css('border-bottom' , border_bottom);
				$(".creativecontactform_heading").css('border-left' , border_left);
        	}
        	else if(roll == 532 || roll == 533 || roll == 534) { 
				var textShadow = $("#elem-532").val() + 'px '  + $("#elem-533").val() + 'px '  + $("#elem-534").val() + 'px ' + $("#elem-531").val();
				$(".creativecontactform_heading").css('textShadow' , textShadow);
			}
        	else if(roll == 525) { 
				var f = $("#elem-525").val() + 'px';
				$(".creativecontactform_heading").css('fontSize' ,f);
			}

			else if(roll == 588) { 
				var f = $("#elem-588").val() + 'px';
				$(".creativecontactform_wrapper").css('fontSize' ,f);
			}
			else if(roll == 554) { 
				var f = $("#elem-554").val() + 'px';
				$(".ccf_content_element_label").css('fontSize' ,f);
			}
        	else if(roll == 559 || roll == 560 || roll == 561) { 
				var textShadow = $("#elem-559").val() + 'px '  + $("#elem-560").val() + 'px '  + $("#elem-561").val() + 'px ' + $("#elem-558").val();
				$(".ccf_content_element_label").css('textShadow' , textShadow);
			}
        	else if(roll == 590) { 
				var borderBottom = $("#elem-590").val() + 'px '  + $("#elem-591").val() + ' ' + $("#elem-592").val();
				$(".ccf_content_element_label").css('border-bottom' , borderBottom);
			}
			else if(roll == 571 || roll == 572 || roll == 573) { 
				var textShadow = $("#elem-571").val() + 'px '  + $("#elem-572").val() + 'px '  + $("#elem-573").val() + 'px ' + $("#elem-570").val();
				$(".ccf_link").css('textShadow' , textShadow);
			}
        	else if(roll == 567) { 
				var borderBottom = $("#elem-567").val() + 'px '  + $("#elem-568").val() + ' ' + $("#elem-569").val();
				$(".ccf_link").css('border-bottom' , borderBottom);

				var borderBottom = $("#elem-567").val() + 'px '  + $("#elem-568").val() + ' ' + $("#elem-575").val();
				$(".ccf_link_hovered").css('border-bottom' , borderBottom);
			}
    	   	else if(roll == 577 || roll == 578 || roll == 579) { 
				var textShadow = $("#elem-577").val() + 'px '  + $("#elem-578").val() + 'px '  + $("#elem-579").val() + 'px ' + $("#elem-576").val();
				$(".ccf_link_hovered").css('textShadow' , textShadow);
			}
        	else if(roll == 584 || roll == 585 || roll == 586) { 
				var textShadow = $("#elem-584").val() + 'px '  + $("#elem-585").val() + 'px '  + $("#elem-586").val() + 'px ' + $("#elem-583").val();
				$(".ccf_content_styling").css('textShadow' , textShadow);
			}

			/*creativecontactform_header ********************************************************************************************************************************************************************************/
        	else if(roll == 603 || roll == 604 || roll == 605 || roll == 606) { 
        		var padding = $("#elem-603").val() + 'px ' + $("#elem-604").val() + 'px ' + $("#elem-605").val() + 'px ' + $("#elem-606").val() + 'px';
				$(".creativecontactform_header").css('padding' , padding);
			}
			/*creativecontactform_body  ********************************************************************************************************************************************************************************/
        	else if(roll == 613 || roll == 614 || roll == 615 || roll == 616) { 
        		var padding = $("#elem-613").val() + 'px ' + $("#elem-614").val() + 'px ' + $("#elem-615").val() + 'px ' + $("#elem-616").val() + 'px';
				$(".creativecontactform_body").css('padding' , padding);
			}
			/*creativecontactform_footer  ********************************************************************************************************************************************************************************/
        	else if(roll == 620 || roll == 621 || roll == 622 || roll == 623) { 
        		var padding = $("#elem-620").val() + 'px ' + $("#elem-621").val() + 'px ' + $("#elem-622").val() + 'px ' + $("#elem-623").val() + 'px';
				$(".creativecontactform_footer").css('padding' , padding);
			}
        	else if(roll == 607) { 
				var borderBottom = $("#elem-607").val() + 'px '  + $("#elem-608").val() + ' ' + $("#elem-609").val();
				$(".creativecontactform_header").css('border-bottom' , borderBottom);
			}
        	else if(roll == 624) { 
				var borderTop = $("#elem-624").val() + 'px '  + $("#elem-625").val() + ' ' + $("#elem-626").val();
				$(".creativecontactform_footer").css('border-top' , borderTop);
			}

		}
		
		$('.creativecontactform_error').hover(function(event) {
			event.stopPropagation();
		})
		
		$('.temp_family').blur(function() {
			var val = $(this).val().replace('|','');
			val = val.replace('~','');
			$(this).val(val);
		})
		
		//main box
		$("#elem-3").change(function() {
			var borderStyle = $(this).val();
			$(".creativecontactform_wrapper").css('borderStyle' , borderStyle);
		})
		$("#elem-9").change(function() {
			var boxShadow = $("#elem-9").val() + ' ' + $("#elem-10").val() + 'px '  + $("#elem-11").val() + 'px '  + $("#elem-12").val() + 'px ' + $("#elem-13").val() + 'px ' + $("#elem-8").val();
			var boxShadow_ = $("#elem-15").val() + ' ' + $("#elem-16").val() + 'px '  + $("#elem-17").val() + 'px '  + $("#elem-18").val() + 'px ' + $("#elem-19").val() + 'px  ' + $("#elem-14").val();
			
			$(".creativecontactform_wrapper").css('boxShadow' , boxShadow);
			$(".creativecontactform_wrapper").hover(function() {
				$(this).css('boxShadow' , boxShadow_);
			},function() {
				$(this).css('boxShadow' , boxShadow);
			});
		})
		$("#elem-15").change(function() {
			var boxShadow = $("#elem-9").val() + ' ' + $("#elem-10").val() + 'px '  + $("#elem-11").val() + 'px '  + $("#elem-12").val() + 'px ' + $("#elem-13").val() + 'px ' + $("#elem-8").val();
			var boxShadow_ = $("#elem-15").val() + ' ' + $("#elem-16").val() + 'px '  + $("#elem-17").val() + 'px '  + $("#elem-18").val() + 'px ' + $("#elem-19").val() + 'px  ' + $("#elem-14").val();
			
			$(".creativecontactform_wrapper").css('boxShadow' , boxShadow);
			$(".creativecontactform_wrapper").hover(function() {
				$(this).css('boxShadow' , boxShadow_);
			},function() {
				$(this).css('boxShadow' , boxShadow);
			});
		})
		$("#elem-131").change(function() {
			var val = $(this).val();
			var font_name = $(this).find('option:selected').html();
			var ccf_font_ident = 'ccf-googlewebfont-';

			if(val.indexOf(ccf_font_ident) > -1) {
				val = val.replace(ccf_font_ident, '');
				val = val.replace(/ /g, '+');
				var font_href = 'http://fonts.googleapis.com/css?family=' + val;

				//load new css
				$("<link/>", {
				   rel: "stylesheet",
				   type: "text/css",
				   href: font_href
				}).appendTo("head");

				var google_font_war = font_name + ', sans-serif';
				$(".creativecontactform_wrapper").css('fontFamily' , google_font_war);
			}
			else 
				$(".creativecontactform_wrapper").css('fontFamily' , val);
		});
		
		//top text
		$("#elem-22").change(function() {
			$(".creativecontactform_title").css('fontWeight' , $(this).val());
		})
		$("#elem-23").change(function() {
			$(".creativecontactform_title").css('fontStyle' , $(this).val());
		})
		$("#elem-24").change(function() {
			$(".creativecontactform_title").css('textDecoration' , $(this).val());
		})
		$("#elem-25").change(function() {
			$(".creativecontactform_title").css('textAlign' , $(this).val());
		})

		$("#elem-500").change(function() {
			$(".creativecontactform_input_element input, .creativecontactform_input_element textarea").css('textAlign' , $(this).val());
		})
		$("#elem-501").change(function() {
			var m = $(this).val() == 'right' ? '0 0 0 auto' : ($(this).val() == 'center' ? '0 auto' : '0');
			$(".creativecontactform_field_box_inner,.creativecontactform_field_box_textarea_inner").css('margin' , m);
		})
		$("#elem-502").change(function() {
			$(".creativecontactform_pre_text").css('textAlign' , $(this).val());
			var mr = $(this).val() == 'right' ? '0' : ($(this).val() == 'center' ? 'auto' : '0');
			var ml = $(this).val() == 'right' ? 'auto' : ($(this).val() == 'center' ? 'auto' : '0');
			$(".creativecontactform_pre_text").css('marginLeft' , ml);
			$(".creativecontactform_pre_text").css('marginRight' , mr);

		})
		
		//field text
		$("#elem-33").change(function() {
			$(".creativecontactform_field_name").css('fontWeight' , $(this).val());
		})
		$("#elem-34").change(function() {
			$(".creativecontactform_field_name").css('fontStyle' , $(this).val());
		})
		$("#elem-35").change(function() {
			$(".creativecontactform_field_name").css('textDecoration' , $(this).val());
		})
		$("#elem-36").change(function() {
			$(".creativecontactform_field_name").css('textAlign' , $(this).val());
		})
		
		//asterisk text///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$("#elem-43").change(function() {
			$(".creativecontactform_field_required").css('fontWeight' , $(this).val());
		})
		$("#elem-44").change(function() {
			$(".creativecontactform_field_required").css('fontStyle' , $(this).val());
		})
		
		
		//Send///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$("#elem-127").change(function() {
			var borderStyle = $(this).val();
			$(".creativecontactform_send").css('borderStyle' , borderStyle);
		})
		// $("#elem-112").blur(function() {
		// 	$(".creativecontactform_send").css('fontFamily' , $(this).val());
		// });
		$("#elem-112").change(function() {
			var val = $(this).val();
			var font_name = $(this).find('option:selected').html();
			var ccf_font_ident = 'ccf-googlewebfont-';

			if(val.indexOf(ccf_font_ident) > -1) {
				val = val.replace(ccf_font_ident, '');
				val = val.replace(/ /g, '+');
				var font_href = 'http://fonts.googleapis.com/css?family=' + val;

				//load new css
				$("<link/>", {
				   rel: "stylesheet",
				   type: "text/css",
				   href: font_href
				}).appendTo("head");

				var google_font_war = font_name + ', sans-serif';
				$(".creativecontactform_send").css('fontFamily' , google_font_war);
			}
			else 
				$(".creativecontactform_send").css('fontFamily' , val);
		});
		$("#elem-108").change(function() {
			$(".creativecontactform_send").css('fontWeight' , $(this).val());
		})
		$("#elem-109").change(function() {
			$(".creativecontactform_send").css('fontStyle' , $(this).val());
		})
		$("#elem-110").change(function() {
			$(".creativecontactform_send").css('textDecoration' , $(this).val());
		})
		$("#elem-95").change(function() {
			var boxShadow_ = $("#elem-95").val() + ' ' + $("#elem-96").val() + 'px '  + $("#elem-97").val() + 'px '  + $("#elem-98").val() + 'px ' + $("#elem-99").val() + 'px ' + $("#elem-94").val();
			$(".creativecontactform_send").not('.creativecontactform_send_hovered').css('boxShadow' , boxShadow_);
		})
		$("#elem-118").change(function() {
			var boxShadow = $("#elem-118").val() + ' ' + $("#elem-119").val() + 'px '  + $("#elem-120").val() + 'px '  + $("#elem-121").val() + 'px ' + $("#elem-122").val() + 'px ' + $("#elem-117").val();
			$(".creativecontactform_send_hovered").css('boxShadow' , boxShadow);
		})
		
		//input text///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$("#elem-136").change(function() {
			var borderStyle = $(this).val();
			// $(".creativecontactform_input_element").not('.creative_error_input').css('border' , $("#elem-135").val() + 'px ' +  borderStyle + $("#elem-134").val());
			$(".creativecontactform_input_element").css('borderStyle' , borderStyle);
		})
		// $("#elem-152").blur(function() {
		// 	$(".creativecontactform_input_element input,.creativecontactform_input_element textarea").css('fontFamily' , $(this).val());
		// });
		$("#elem-152").change(function() {
			var val = $(this).val();
			var font_name = $(this).find('option:selected').html();
			var ccf_font_ident = 'ccf-googlewebfont-';

			if(val.indexOf(ccf_font_ident) > -1) {
				val = val.replace(ccf_font_ident, '');
				val = val.replace(/ /g, '+');
				var font_href = 'http://fonts.googleapis.com/css?family=' + val;

				//load new css
				$("<link/>", {
				   rel: "stylesheet",
				   type: "text/css",
				   href: font_href
				}).appendTo("head");

				var google_font_war = font_name + ', sans-serif';
				$(".creativecontactform_input_element input,.creativecontactform_input_element textarea").css('fontFamily' , google_font_war);
			}
			else 
				$(".creativecontactform_input_element input,.creativecontactform_input_element textarea").css('fontFamily' , val);
		});
		$("#elem-149").change(function() {
			$(".creativecontactform_input_element input,.creativecontactform_input_element textarea").css('fontWeight' , $(this).val());
		})
		$("#elem-150").change(function() {
			$(".creativecontactform_input_element input,.creativecontactform_input_element textarea").css('fontStyle' , $(this).val());
		})
		$("#elem-151").change(function() {
			$(".creativecontactform_input_element input,.creativecontactform_input_element textarea").css('textDecoration' , $(this).val());
		})
		$("#elem-163").change(function() {
			var boxShadow_ = $("#elem-163").val() + ' ' + $("#elem-164").val() + 'px '  + $("#elem-165").val() + 'px '  + $("#elem-166").val() + 'px ' + $("#elem-167").val() + 'px ' +  $("#elem-162").val();
			$(".creativecontactform_input_element_hovered").css('boxShadow' , boxShadow_);
		})
		$("#elem-142").change(function() {
			var boxShadow = $("#elem-142").val() + ' ' + $("#elem-143").val() + 'px '  + $("#elem-144").val() + 'px '  + $("#elem-145").val() + 'px ' + $("#elem-146").val() + 'px ' +  $("#elem-141").val();
			var boxShadow_ = $("#elem-163").val() + ' ' + $("#elem-164").val() + 'px '  + $("#elem-165").val() + 'px '  + $("#elem-166").val() + 'px ' + $("#elem-167").val() + 'px ' +  $("#elem-162").val();
			$(".creativecontactform_input_element").not('.creative_error_input').not('.creativecontactform_input_element_hovered').css('boxShadow' , boxShadow);
		})
		//Error State////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$("#elem-185").change(function() {
			var boxShadow = $("#elem-185").val() + ' ' + $("#elem-186").val() + 'px '  + $("#elem-187").val() + 'px '  + $("#elem-188").val() + 'px ' + $("#elem-189").val() + 'px ' +  $("#elem-184").val();
			$(".creativecontactform_error .creativecontactform_input_element").css('boxShadow' , boxShadow);
		})
		//Pre text////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$("#elem-196").change(function() {
			var borderTop = $("#elem-194").val() + 'px '  + $("#elem-196").val() + $("#elem-195").val();
			$(".creativecontactform_pre_text").css('borderTop' , borderTop);
		})
		// $("#elem-202").blur(function() {
		// 	$(".creativecontactform_pre_text").css('fontFamily' , $(this).val());
		// });
		$("#elem-202").change(function() {
			var val = $(this).val();
			var font_name = $(this).find('option:selected').html();
			var ccf_font_ident = 'ccf-googlewebfont-';

			if(val.indexOf(ccf_font_ident) > -1) {
				val = val.replace(ccf_font_ident, '');
				val = val.replace(/ /g, '+');
				var font_href = 'http://fonts.googleapis.com/css?family=' + val;

				//load new css
				$("<link/>", {
				   rel: "stylesheet",
				   type: "text/css",
				   href: font_href
				}).appendTo("head");

				var google_font_war = font_name + ', sans-serif';
				$(".creativecontactform_pre_text").css('fontFamily' , google_font_war);
			}
			else 
				$(".creativecontactform_pre_text").css('fontFamily' , val);
		});
		$("#elem-199").change(function() {
			$(".creativecontactform_pre_text").css('fontWeight' , $(this).val());
		})
		$("#elem-200").change(function() {
			$(".creativecontactform_pre_text").css('fontStyle' , $(this).val());
		})
		$("#elem-201").change(function() {
			$(".creativecontactform_pre_text").css('textDecoration' , $(this).val());
		})


		//heading
		$("#elem-547").change(function() {
			var border_top = $("#elem-543").val() + 'px ' + $("#elem-547").val() + ' ' + $("#elem-548").val();
    		var border_right = $("#elem-544").val() + 'px ' + $("#elem-547").val() + ' ' + $("#elem-549").val();
    		var border_bottom = $("#elem-545").val() + 'px ' + $("#elem-547").val() + ' ' + $("#elem-550").val();
    		var border_left = $("#elem-546").val() + 'px ' + $("#elem-547").val() + ' ' + $("#elem-551").val();
			$(".creativecontactform_heading").css('border-top' , border_top);
			$(".creativecontactform_heading").css('border-right' , border_right);
			$(".creativecontactform_heading").css('border-bottom' , border_bottom);
			$(".creativecontactform_heading").css('border-left' , border_left);
		});
		$("#elem-529").change(function() {
			var val = $(this).val();
			var font_name = $(this).find('option:selected').html();
			var ccf_font_ident = 'ccf-googlewebfont-';

			if(val.indexOf(ccf_font_ident) > -1) {
				val = val.replace(ccf_font_ident, '');
				val = val.replace(/ /g, '+');
				var font_href = 'http://fonts.googleapis.com/css?family=' + val;

				//load new css
				$("<link/>", {
				   rel: "stylesheet",
				   type: "text/css",
				   href: font_href
				}).appendTo("head");

				var google_font_war = font_name + ', sans-serif';
				$(".creativecontactform_heading").css('fontFamily' , google_font_war);
			}
			else 
				$(".creativecontactform_heading").css('fontFamily' , val);
		});
		$("#elem-526").change(function() {
			$(".creativecontactform_heading").css('fontWeight' , $(this).val());
		})
		$("#elem-527").change(function() {
			$(".creativecontactform_heading").css('fontStyle' , $(this).val());
		})
		$("#elem-528").change(function() {
			$(".creativecontactform_heading").css('textDecoration' , $(this).val());
		});


    	$("#elem-591").change(function() {
			var borderBottom = $("#elem-590").val() + 'px '  + $("#elem-591").val() + ' ' + $("#elem-592").val();
			$(".ccf_content_element_label").css('border-bottom' , borderBottom);
		});

    	$("#elem-568").change(function() {
			var borderBottom = $("#elem-567").val() + 'px '  + $("#elem-568").val() + ' ' + $("#elem-569").val();
			$(".ccf_link").css('border-bottom' , borderBottom);

			var borderBottom = $("#elem-567").val() + 'px '  + $("#elem-568").val() + ' ' + $("#elem-575").val();
			$(".ccf_link_hovered").css('border-bottom' , borderBottom);
		});

		$("#elem-555").change(function() {
			$(".ccf_content_element_label").css('fontWeight' , $(this).val());
		})
		$("#elem-556").change(function() {
			$(".ccf_content_element_label").css('fontStyle' , $(this).val());
		});		
		$("#elem-565").change(function() {
			$(".ccf_link").css('fontWeight' , $(this).val());
			$(".ccf_link_hovered").css('fontWeight' , $(this).val());
		})
		$("#elem-566").change(function() {
			$(".ccf_link").css('fontStyle' , $(this).val());
			$(".ccf_link_hovered").css('fontStyle' , $(this).val());
		});
		$("#elem-581").change(function() {
			$(".ccf_content_styling").css('fontWeight' , $(this).val());
		})
		$("#elem-582").change(function() {
			$(".ccf_content_styling").css('fontStyle' , $(this).val());
		});
		$("#elem-593").change(function() {
			$(".ccf_content_styling").css('textDecoration' , $(this).val());
		});
		$("#elem-594").change(function() {
			$(".ccf_link").css('textDecoration' , $(this).val());
		});		
		$("#elem-595").change(function() {
			$(".ccf_link_hovered").css('textDecoration' , $(this).val());
		});
		$("#elem-596").change(function() {
			$(".ccf_content_element_label").css('textDecoration' , $(this).val());
		});










		/*creativecontactform_wrapper_inner ********************************************************************************************************************************************************************************/
        $("#elem-212").change(function() {
    		$(".creativecontactform_send").css('float' , $(this).val());
    	});
    	/*tooltip*/
    	$("#elem-505").change(function() {
    		var val = $(this).val();
    		$(".creativecontactform_send").css('float' , $(this).val());
    		var new_class = 'the-tooltip top right ' + val;
    		$('.tooltip_inner').parent('span').attr("class",new_class);
    	});
		$("#elem-506").change(function() {
			var val = $(this).val();
			var font_name = $(this).find('option:selected').html();
			var ccf_font_ident = 'ccf-googlewebfont-';

			if(val.indexOf(ccf_font_ident) > -1) {
				val = val.replace(ccf_font_ident, '');
				val = val.replace(/ /g, '+');
				var font_href = 'http://fonts.googleapis.com/css?family=' + val;

				//load new css
				$("<link/>", {
				   rel: "stylesheet",
				   type: "text/css",
				   href: font_href
				}).appendTo("head");

				var google_font_war = font_name + ', sans-serif';
				$(".creativecontactform_title").css('fontFamily' , google_font_war);
			}
			else 
				$(".creativecontactform_title").css('fontFamily' , val);
		});
		$("#elem-507").change(function() {
			var val = $(this).val();
			var font_name = $(this).find('option:selected').html();
			var ccf_font_ident = 'ccf-googlewebfont-';

			if(val.indexOf(ccf_font_ident) > -1) {
				val = val.replace(ccf_font_ident, '');
				val = val.replace(/ /g, '+');
				var font_href = 'http://fonts.googleapis.com/css?family=' + val;

				//load new css
				$("<link/>", {
				   rel: "stylesheet",
				   type: "text/css",
				   href: font_href
				}).appendTo("head");

				var google_font_war = font_name + ', sans-serif';
				$(".creativecontactform_field_name").css('fontFamily' , google_font_war);
			}
			else 
				$(".creativecontactform_field_name").css('fontFamily' , val);
		});
		$("#elem-508").change(function() {
			var val = $(this).val();
			var font_name = $(this).find('option:selected').html();
			var ccf_font_ident = 'ccf-googlewebfont-';

			if(val.indexOf(ccf_font_ident) > -1) {
				val = val.replace(ccf_font_ident, '');
				val = val.replace(/ /g, '+');
				var font_href = 'http://fonts.googleapis.com/css?family=' + val;

				//load new css
				$("<link/>", {
				   rel: "stylesheet",
				   type: "text/css",
				   href: font_href
				}).appendTo("head");

				var google_font_war = font_name + ', sans-serif';
				$(".tooltip_inner").css('fontFamily' , google_font_war);
			}
			else 
				$(".tooltip_inner").css('fontFamily' , val);
		});
		$("#elem-509").change(function() {
			var val = $(this).val();
			var font_name = $(this).find('option:selected').html();
			var ccf_font_ident = 'ccf-googlewebfont-';

			if(val.indexOf(ccf_font_ident) > -1) {
				val = val.replace(ccf_font_ident, '');
				val = val.replace(/ /g, '+');
				var font_href = 'http://fonts.googleapis.com/css?family=' + val;

				//load new css
				$("<link/>", {
				   rel: "stylesheet",
				   type: "text/css",
				   href: font_href
				}).appendTo("head");

				var google_font_war = font_name + ', sans-serif';
				$(".creativecontactform_field_required").css('fontFamily' , google_font_war);
			}
			else 
				$(".creativecontactform_field_required").css('fontFamily' , val);
		});
		$("#elem-510").change(function() {
			var new_class = 'creativecontactform_title' + ' ' + $(this).val();
			$(".creativecontactform_title").attr("class",new_class);
		});
		$("#elem-511").change(function() {
			var new_class = 'creativecontactform_pre_text' + ' ' + $(this).val();
			$(".creativecontactform_pre_text").attr("class",new_class);
		});
		$("#elem-512").change(function() {
			var new_class = 'creativecontactform_field_name' + ' ' + $(this).val();
			$('.creativecontactform_field_box').not('.creativecontactform_field_box_hovered').not('.creativecontactform_error').find(".creativecontactform_field_name").attr("class",new_class);
		});
		$("#elem-513").change(function() {
			var new_class = 'creativecontactform_field_name' + ' ' + $(this).val();
			$('.creativecontactform_field_box_hovered').find(".creativecontactform_field_name").attr("class",new_class);
		});
		$("#elem-514").change(function() {
			var new_class = 'creativecontactform_field_name' + ' ' + $(this).val();
			$('.creativecontactform_error').find(".creativecontactform_field_name").attr("class",new_class);
		});
		$("#elem-515").change(function() {
			var new_class = 'creativecontactform_send' + ' ' + $(this).val();
			$('.creativecontactform_send').not(".creativecontactform_send_hovered").attr("class",new_class);
		});
		$("#elem-516").change(function() {
			var new_class = 'creativecontactform_send creativecontactform_send_hovered' + ' ' + $(this).val();
			$('.creativecontactform_send_hovered').attr("class",new_class);
		});
		$("#elem-530").change(function() {
			var new_class = 'creativecontactform_heading' + ' ' + $(this).val();
			$(".creativecontactform_heading").attr("class",new_class);
		});

		$("#elem-563").change(function() {
			var path_0 = $(".creative_datepicker_icon").attr("data_src");
			var icon_id = $(this).val();
			var img_path = path_0 + 'style-' + icon_id + '.png';
			$(".creative_datepicker_icon").attr("src",img_path);
		});

		$("#elem-552").change(function() {
			var icon_id = $(this).val();
			var new_class = 'ccf_sections_wrapper ccf_sections_template_' + icon_id;
			$(".ccf_sections_wrapper").attr("class",new_class);
		});

		$("#elem-608").change(function() {
			var borderBottom = $("#elem-607").val() + 'px '  + $("#elem-608").val() + ' ' + $("#elem-609").val();
			$(".creativecontactform_header").css('border-bottom' , borderBottom);
		});		
		$("#elem-625").change(function() {
			var borderTop = $("#elem-624").val() + 'px '  + $("#elem-625").val() + ' ' + $("#elem-626").val();
			$(".creativecontactform_footer").css('border-top' , borderTop);
		});
		
		$("#elem-600").change(function() {
			if($(this).val() == 0) {
				$(".creativecontactform_header").addClass('ccf_transparent');
			}
			else {
				$(".creativecontactform_header").removeClass('ccf_transparent');
			}
		});		
		$("#elem-610").change(function() {
			if($(this).val() == 0) {
				$(".creativecontactform_body").addClass('ccf_transparent');
			}
			else {
				$(".creativecontactform_body").removeClass('ccf_transparent');
			}
		});		
		$("#elem-617").change(function() {
			if($(this).val() == 0) {
				$(".creativecontactform_footer").addClass('ccf_transparent');
			}
			else {
				$(".creativecontactform_footer").removeClass('ccf_transparent');
			}
		});




		



		var top_offset = parseInt($(".preview_box").css('top'));
		top_offset_moove = top_offset == 26 ? 26 : 100;
		//animate preview
		$(window).scroll(function() {
			var off = $("#preview_dummy").offset().top;

			var off_0 = $("#c_div").offset().top;
			if(off > off_0 && !($('.answers_switcher').hasClass('active')) ) {
				delta = off - off_0 + top_offset_moove*1;
				$(".preview_box").stop(true).animate( {
					top: delta
				},500);
			}
			else {
				$(".preview_box").stop(true).animate( {
					top: top_offset
				},500);
			}
			
		})

		$('.temp_block').click(function() {
			if($(this).hasClass('closed')) {
				$(this).removeClass('closed');
				$(this).addClass('opened');
				$(this).next('div').slideDown(600);
			}
			else {
				$(this).removeClass('opened');
				$(this).addClass('closed');
				$(this).next('div').slideUp(600);
			}
		})


		//answers switcher
		$('.answers_switcher').click(function() {
			if($(this).hasClass('active')) {
				$("#answers_styles_table").height("");
				$(this).removeClass('active');
				$(this).html('Switch to Answers');

				$('.main_view').slideDown(600);
				$('.answers_view').slideUp(600);
				$('#main_styles_table').slideDown(600);
				$('#answers_styles_table').slideUp(600);
			}
			else {
				setTimeout(function() {
					var h = $("#answers_styles_table").height();
					var h1 = $('.preview_box').height();
					if(parseInt(h1) > parseInt(h))
						$("#answers_styles_table").height(h1 + 50*1);
				},650)
				
				$('.preview_box').animate({'top':'26px'},600);
				$('html, body').animate({scrollTop:0}, 600);
				$(this).addClass('active');
				$(this).html('Switch to Main View');

				$('.main_view').slideUp(600);
				$('.answers_view').slideDown(600);
				$('#main_styles_table').slideUp(600);
				$('#answers_styles_table').slideDown(600);

			}
		});

	    $.fn.shake_elem = function (options) {
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


		$('.navigate_to_option').click(function() {
			var roll = parseInt($(this).attr("roll"));
			var $scrollTo = $('#scroll_to_' + roll);

			var elem_scroll_top = $scrollTo.offset().top;


			var subhead_h_diff = parseInt($('.subhead').height()) + parseInt($('.subhead').css('margin-bottom')) + parseInt($('.subhead').css('border-bottom'));
			var subhead_h = parseInt($('.subhead').height()) + parseInt($('.subhead').css('border-bottom'));
			var navbar_h = parseInt($('.navbar').height());

			var h_offset = 5;
			var elem_scroll_top_calc = elem_scroll_top - navbar_h - subhead_h - h_offset;

			var elem_scroll_top_final = $('.subhead').hasClass('subhead-fixed') ? elem_scroll_top_calc : elem_scroll_top_calc - subhead_h_diff;

			if(!$('.subhead').length)
				elem_scroll_top_final = elem_scroll_top - h_offset;

			$("body").animate({
				scrollTop: elem_scroll_top_final
			},400);

			$('#scroll_to_' + roll).addClass('sep_td_highlighted');
			setTimeout(function() {
				$('#scroll_to_' + roll).removeClass('sep_td_highlighted');
				$('#scroll_to_' + roll).parent('div').shake_elem({'shakes': 2,'distance': 10,'duration':400});
			},1200);


		});


		// view toggler



		$("#ccf_main_view").click(function() {
			if($(this).hasClass('active'))
				return;

			$("#ccf_main_view_inner").show();
			$("#ccf_icons_view_inner").hide();
		});

		$("#ccf_icons_view").click(function() {
			if($(this).hasClass('active'))
				return;

			$("#ccf_main_view_inner").hide();
			$("#ccf_icons_view_inner").show();
		});

		$(".view_toggler_item").click(function() {
			$(".view_toggler_item").removeClass('active');
			$(this).addClass("active");
		});

	});

})(creativeJ);
</script>

<?php 
function create_accordion($txt,$state,$title='',$roll='') {
	$dis = $state == 'opened' ? '' : 'display:none;';
	echo '<tr>
			<td colspan="2">
				<div class="temp_data_container">
				<div id="scroll_to_'.$roll.'" class="temp_block '.$state.'" title="'.$title.'">'.JText::_($txt).'</div><div style="'.$dis.'margin-bottom:6px;">
					<table>';
}
function close_accordion() {
	echo '</table></div></div></td></tr>';
}
function echo_font_tr($txt,$i,$value) {
	echo '
			<tr>
            <td width="180" align="right" class="key">
                <label for="name">';
                    echo JText::_($txt);
                echo '</label>
            </td>
            <td class="st_td">
	               <input class="temp_family" value="'.$value.'" name="styles['.$i.']" roll="'.$i.'"  id="elem-'.$i.'"/>	               
            </td>
        </tr>
	';
}
function echo_select_tr($txt,$i,$values,$value) {
	echo '
			<tr>
            <td width="180" align="right" class="key">
                <label for="name">';
                    echo JText::_($txt);
                echo '</label>
            </td>
            <td class="st_td">
	               <select name="styles['.$i.']"  id="elem-'.$i.'" class="temp_select">';
                	foreach($values as $k => $val) {
                		$selected = $value == $k ? 'selected="selected"' : '';
                		echo '<option value="'.$k.'" '.$selected.'>'.$val.'</option>';
                	}
			echo '</select>	               
            </td>
        </tr>
	';
}
function echo_select_tr_with_optgroups($txt,$i,$values,$value) {
	echo '
			<tr>
            <td width="180" align="right" class="key">
                <label for="name">';
                    echo JText::_($txt);
                echo '</label>
            </td>
            <td class="st_td">
	               <select name="styles['.$i.']"  id="elem-'.$i.'" class="temp_select">';
	               $q = 0;
                	foreach($values as $label => $val_array) {
                		echo '<optgroup label="'.$label.'">';
                		foreach($val_array as $k => $val) {
                			$def_class=$q == 0 ? '' : 'googlefont';
	                		$selected = $value == $k ? 'selected="selected"' : '';
	                		echo '<option class="'.$def_class.'" value="'.$k.'" '.$selected.'>'.$val.'</option>';
                		}
                		echo '</optgroup>';
                		$q ++;
                	}
			echo '</select>	               
            </td>
        </tr>
	';
}
function echo_color_tr($txt,$i,$color) {
	echo '
			<tr>
            <td width="180" align="right" class="key">
                <label for="name">';
                    echo JText::_($txt);
                echo '</label>
            </td>
            <td class="st_td">
	               <div id="colorSelector" class="colorSelector" style="float: left;"><div style="background-color: '.$color.'"></div></div>
	               <input type="hidden" value="'.$color.'" name="styles['.$i.']" roll="'.$i.'"  id="elem-'.$i.'" />
            </td>
        </tr>
	';
}
function echo_size_tr($txt,$i,$size,$min,$max) {
	echo '
			<tr>
            <td width="180" align="right" class="key">
                <label for="name">';
                    echo JText::_($txt);
                echo '</label>
            </td>
             <td class="st_td">
            	<div class="size_container">
	            	<input class="size_input" type="text" value="'. $size .'" name="styles['.$i.']" readonly="readonly" roll="'.$i.'" id="elem-'.$i.'" />
	            	<div class="size_arrows">
	            		<div class="size_up" maxval="'.$max.'" title="'; echo JText::_( 'Up' ); echo '"></div>
	            		<div class="size_down" minval="'.$min.'" title="'; echo JText::_( 'Down' );echo '"></div>
	            	</div>
	            	<div class="pix_info">px</div>
	            </div>
            </td>
        </tr>
	';
}
function echo_size_perc_tr($txt,$i,$size,$min,$max) {
	echo '
			<tr>
            <td width="180" align="right" class="key">
                <label for="name">';
                    echo JText::_($txt);
                echo '</label>
            </td>
             <td class="st_td">
            	<div class="size_container">
	            	<input class="size_input" type="text" value="'. $size .'" name="styles['.$i.']" readonly="readonly" roll="'.$i.'" id="elem-'.$i.'" />
	            	<div class="size_arrows">
	            		<div class="size_up" maxval="'.$max.'" title="'; echo JText::_( 'Up' ); echo '"></div>
	            		<div class="size_down" minval="'.$min.'" title="'; echo JText::_( 'Down' );echo '"></div>
	            	</div>
	            	<div class="pix_info">%</div>
	            </div>
            </td>
        </tr>
	';
}

function echo_textarea_tr($txt,$i,$value,$desc) {
	echo '
		<tr>
            <td colspan="2">
	            	<textarea style="width: 330px;height: 350px;resize:none;" name="styles['.$i.']" id="elem-'.$i.'">'. $value .'</textarea>
            		'.$desc.'
            </td>
        </tr>';
}

function seperate_tr($txt,$title='',$roll='') {
	echo '<tr><td colspan="2"><div class="sep_td_wrapper"><div id="scroll_to_'.$roll.'" class="sep_td" title="'.$title.'">'.$txt.'</div></div></td></tr>';
}

?>

<div style="overflow: hidden">
	<div style="color: rgb(235, 9, 9);font-size: 16px;font-weight: bold;">Please Upgrade to Commercial Version to use Template Creator Wizard!</div>
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
</div>
<div style="color: rgb(0, 85, 182);font-size: 25px;text-align: center;clear: both;margin-bottom: -15px;">Template Creator Wizard Demo</div>


<div class="col100" style="position: relative;" id="c_div">
	 <div id="preview_dummy"></div>
	 
	 <div class="preview_box">
	 	<div class="view_toggler_wrapper">
	 		<span class="view_toggler_item active" style="margin-right: 5px;" title="Switch to main view." id="ccf_main_view">Main view</span>
	 		<span class="view_toggler_item" title="Switch to icons view." id="ccf_icons_view">Icons view</span>
	 	</div>
	 	<div class="main_view">
	
			<div class="creativecontactform_wrapper " >
			<div class="creativecontactform_wrapper_inner " >

				<!-- Main View --------------------------------------------------------------------------------------------------------- -->
				<div id="ccf_main_view_inner">

					<div class="creativecontactform_header <?php if($styles[600] == 0) echo 'ccf_transparent'; ?>">
			 			<div class="creativecontactform_title <?php echo $styles[510];?>">
			 				<span style="position:relative;display: inline-block;">
			 					Contact Us
			 					<img roll="1" style="top: 2px;right: -23px;;" title="Edit: Top Text" src="<?php echo JURI::base(true);?>/components/com_creativecontactform/assets/images/tmp_edit4.png" class="navigate_to_option"/>
			 				</span>
			 			</div>
			 			<div  class="creativecontactform_pre_text <?php echo $styles[511];?>">
			 				<span style="position:relative;display: inline-block;">
			 					Contact us, if you have any questions
									<img roll="2" style="top: -2px;right: -23px;" title="Edit: Pre Text" src="<?php echo JURI::base(true);?>/components/com_creativecontactform/assets/images/tmp_edit4.png" class="navigate_to_option" />
			 				</span>
			 			</div>

						<img roll="0" style="top: -7px;right: -7px;" title="Edit: Main Box" src="<?php echo JURI::base(true);?>/components/com_creativecontactform/assets/images/tmp_edit4.png" class="navigate_to_option"/>
					</div>

					<div class="creativecontactform_body <?php if($styles[610] == 0) echo 'ccf_transparent'; ?>">
 					
				 		<div class="creativecontactform_field_box"><div class="creativecontactform_field_box_inner">
				 			<label class="creativecontactform_field_name <?php echo $styles[512];?>" for="name_0_1">
				 				<span class="creative_label_txt_wrapper">
				 					Text Input <span class="creativecontactform_field_required">*</span>
		 							<img roll="4" style="top: -2px;right: -50px;" title="Edit: Label Asterisk Symbol" src="<?php echo JURI::base(true);?>/components/com_creativecontactform/assets/images/tmp_edit4.png" class="navigate_to_option" />
		 							<img roll="3" style="top: -2px;right: -34px;" title="Edit: Label Text" src="<?php echo JURI::base(true);?>/components/com_creativecontactform/assets/images/tmp_edit4.png" class="navigate_to_option" />
				 				</span>
				 			</label>
				 			<div class="creativecontactform_input_element creativecontactform_required">
				 				<div class="creative_input_dummy_wrapper">
				 					<span class="the-tooltip top right <?php echo $st = $styles[505] == '' ? 'white' : $styles[505];?>">
				 						<span class="tooltip_inner ">
				 							Tooltip text goes here!
		 									<img roll="12" style="top: 0px;right: -23px;" title="Edit: Tooltip Style" src="<?php echo JURI::base(true);?>/components/com_creativecontactform/assets/images/tmp_edit4.png" class="navigate_to_option" />
				 						</span>
				 					</span>
				 					<input class="creative_name creativecontactform_required creative_input_reset" value="Normal state text..." type="text" id="name_0_1" name="creativecontactform_fields[1][0]">
		 							<img roll="5" style="top: 2px;right: -23px;" title="Edit: Text Inputs" src="<?php echo JURI::base(true);?>/components/com_creativecontactform/assets/images/tmp_edit4.png" class="navigate_to_option" />
				 				</div>
				 			</div>
				 		</div></div>

				 		<div class="creativecontactform_field_box creativecontactform_field_box_hovered"><div class="creativecontactform_field_box_inner">
				 			<label class="creativecontactform_field_name <?php echo $styles[513];?>" for="email_0_2">
				 				<span class="creative_label_txt_wrapper">
				 					Hover State
				 					<img roll="13" style="top: -2px;right: -23px;" title="Edit: Label Text Focus State" src="<?php echo JURI::base(true);?>/components/com_creativecontactform/assets/images/tmp_edit4.png" class="navigate_to_option" />
				 				</span>
				 			</label>
				 			<div class="creativecontactform_input_element creativecontactform_input_element_hovered">
					 			<div class="creative_input_dummy_wrapper">
					 				<input class="creative_email  creative_input_reset" value="Hover state text..." type="text" id="email_0_2" name="creativecontactform_fields[2][0]">
		 							<img roll="6" style="top: 2px;right: -23px;" title="Edit: Hover State" src="<?php echo JURI::base(true);?>/components/com_creativecontactform/assets/images/tmp_edit4.png" class="navigate_to_option" />
					 			</div>
				 			</div>
				 		</div></div>

				 		<div class="creativecontactform_field_box creativecontactform_error"><div class="creativecontactform_field_box_inner">
				 			<label class="creativecontactform_field_name <?php echo $styles[514];?>" for="phone_0_3">
				 				<span class="creative_label_txt_wrapper">
				 					Error state
				 					<img roll="14" style="top: -2px;right: -23px;" title="Edit: Label Text Error State" src="<?php echo JURI::base(true);?>/components/com_creativecontactform/assets/images/tmp_edit4.png" class="navigate_to_option" />
				 				</span>
				 			</label>
				 			<div class="creativecontactform_input_element creativecontactform_required creative_error_input">
				 				<div class="creative_input_dummy_wrapper">
				 					<input class="creative_phone  creative_input_reset creativecontactform_required" value="Error state text..." type="text" id="phone_0_3" name="creativecontactform_fields[3][0]">
		 							<img roll="7" style="top: 2px;right: -23px;" title="Edit: Error State" src="<?php echo JURI::base(true);?>/components/com_creativecontactform/assets/images/tmp_edit4.png" class="navigate_to_option" />
				 				</div>
				 			</div>
				 		</div></div>


				 		<div class="creativecontactform_field_box" style="margin: 0 !important;position: relative;"><div class="creativecontactform_field_box_inner" style="width: 100%">
				 			<div class="creativecontactform_heading <?php echo $styles[530];?>">
				 				<div class="creativecontactform_heading_inner">
				 					<span style="position: relative">
				 						Heading example
				 					</span>
				 				</div>
				 			</div>
 							<img roll="19" style="top: 5px;right: -15px;" title="Edit: Heading Styles" src="<?php echo JURI::base(true);?>/components/com_creativecontactform/assets/images/tmp_edit4.png" class="navigate_to_option" />
				 		</div></div>

				 		<div class="creativecontactform_field_box"><div class="creativecontactform_field_box_textarea_inner">
				 			<label class="creativecontactform_field_name <?php echo $styles[512];?>" for="text-area_0_5"><span class="creative_label_txt_wrapper">Textarea</span></label>
				 			<div class="creativecontactform_input_element creative_textarea_wrapper creativecontactform_required">
				 				<div class="creative_textarea_dummy_wrapper">
				 					<textarea class="creative_textarea creative_text-area creativecontactform_required creative_textarea_reset" value="" cols="30" rows="15" id="text-area_0_5" name="creativecontactform_fields[5][0]"></textarea>
		 							<img roll="11" style="top: 2px;right: -23px;" title="Edit: Textarea Styles" src="<?php echo JURI::base(true);?>/components/com_creativecontactform/assets/images/tmp_edit4.png" class="navigate_to_option" />
				 				</div>
				 			</div>
				 		</div></div>

	 				</div>

	 				<div class="creativecontactform_footer <?php if($styles[617] == 0) echo 'ccf_transparent'; ?>">
			 			<div class="creativecontactform_submit_wrapper">
				 			<div class="ccf_button_holder" style="position: relative;display: inline-block;">			
				 				<input type="button" value="Send" class="creativecontactform_send <?php echo $styles[515];?>" roll="1" />
	 							<img roll="9" style="top: 2px;right: -18px;" title="Edit: Send Button" src="<?php echo JURI::base(true);?>/components/com_creativecontactform/assets/images/tmp_edit4.png" class="navigate_to_option" />
				 				<div class="creativecontactform_clear"></div>
				 			</div>
				 			<div class="ccf_button_holder" style="position: relative;display: inline-block;margin: 0 30px;">			
				 				<input type="button" title="Send button hovered state" value="Hovered" class="creativecontactform_send creativecontactform_send_hovered <?php echo $styles[516];?>" roll="1" />
	 							<img roll="10" style="top: 2px;right: -18px;" title="Edit: Send Button Hover State" src="<?php echo JURI::base(true);?>/components/com_creativecontactform/assets/images/tmp_edit4.png" class="navigate_to_option" />
				 				<div class="creativecontactform_clear"></div>
				 			</div>
			 			</div>
			 			<div class="creativecontactform_clear"></div>
 					</div>
				</div>
	 			<!-- icons View --------------------------------------------------------------------------------------------------------- -->
	 			<div id="ccf_icons_view_inner" style="display: none;">
	 				<div class="creativecontactform_body">
		 				<div class="ccf_preview_left_col" >
		 					<!-- Left column Conent **************************************************************** -->
		 					<div class="creativecontactform_field_box"><div class="creativecontactform_field_box_inner" style="width: 100%">
	 							<div class="ccf_sections_wrapper ccf_sections_template_<?php echo $styles[552];?>">
			 						<div class="ccf_content_element">
			 							<div class="ccf_content_element_content_wrapper">
				 							<div style="margin-left: 120px;">
												<span>
													PO Box 21177 / Level 13, 2 Elizabeth St...
												</span>
											</div>
										</div>
										<div class="ccf_content_element_icon_wrapper" style="width: 120px;position: relative">
											<span class="ccf_content_icon ccf_content_icon_address"></span>
											<span class="ccf_content_element_label">Address:</span>
											<img roll="20" style="top: 3px;right: 5px;" title="Edit: Sections Styles" src="<?php echo JURI::base(true);?>/components/com_creativecontactform/assets/images/tmp_edit4.png" class="navigate_to_option" />
										</div>
									</div>

									<div class="ccf_content_element">
										<div class="ccf_content_element_content_wrapper">
											<div style="margin-left: 120px;">
												<span>
													<span class="ccf_content_styling">+00 (0) 123 456 789</span>,<br/>
													<span class="ccf_content_styling">+00 (0) 123 456 789</span>
												</span>
											</div>
										</div>
										<div class="ccf_content_element_icon_wrapper" style="width: 120px;">
											<span class="ccf_content_icon ccf_content_icon_phone"></span>
											<span class="ccf_content_element_label">Phone:</span>
										</div>
									</div>

									<div class="ccf_content_element">
										<div class="ccf_content_element_content_wrapper">
											<div style="margin-left: 120px;">
												<span>
													<span class="ccf_content_styling">+00 (0) 123 456 789</span>
												</span>
											</div>
										</div>
										<div class="ccf_content_element_icon_wrapper" style="width: 120px;">
											<span class="ccf_content_icon ccf_content_icon_mobile"></span>
											<span class="ccf_content_element_label">Mobile:</span>
										</div>
									</div>

									<div class="ccf_content_element">
										<div class="ccf_content_element_content_wrapper">
											<div style="margin-left: 120px;">
												<span>
													email1@example.com,<br/>
													email2@example.com
												</span>
											</div>
										</div>
										<div class="ccf_content_element_icon_wrapper" style="width: 120px;">
											<span class="ccf_content_icon ccf_content_icon_email"></span>
											<span class="ccf_content_element_label">E-mail:</span>
										</div>
									</div>

									<div class="ccf_content_element">
										<div class="ccf_content_element_content_wrapper">
											<div style="margin-left: 120px;">
												<span>
													<a href="http://example.com" class="ccf_link" target="_blank">Example.com</a>
												</span>
											</div>
										</div>
										<div class="ccf_content_element_icon_wrapper" style="width: 120px;">
											<span class="ccf_content_icon ccf_content_icon_link"></span>
											<span class="ccf_content_element_label">Website:</span>
										</div>
									</div>


									<div class="ccf_content_element">
										<div class="ccf_content_element_content_wrapper">
											<div style="margin-left: 120px;">
												<span>
													Info section example...
												</span>
											</div>
										</div>
										<div class="ccf_content_element_icon_wrapper" style="width: 120px;">
											<span class="ccf_content_icon ccf_content_icon_info"></span>
											<span class="ccf_content_element_label">Info:</span>
										</div>
									</div>


									<div class="ccf_content_element">
										<div class="ccf_content_element_content_wrapper">
											<div style="margin-left: 120px;">
												<span>
													Tip section example...
												</span>
											</div>
										</div>
										<div class="ccf_content_element_icon_wrapper" style="width: 120px;">
											<span class="ccf_content_icon ccf_content_icon_tip"></span>
											<span class="ccf_content_element_label">Tip:</span>
										</div>
									</div>

									<div class="ccf_content_element">
										<div class="ccf_content_element_content_wrapper">
											<div style="margin-left: 120px;">
												<span>
													Question section example...
												</span>
											</div>
										</div>
										<div class="ccf_content_element_icon_wrapper" style="width: 120px;">
											<span class="ccf_content_icon ccf_content_icon_question"></span>
											<span class="ccf_content_element_label">Question:</span>
										</div>
									</div>

									<div class="ccf_content_element">
										<div class="ccf_content_element_content_wrapper">
											<div style="margin-left: 120px;">
												<span>
													Fax section example...
												</span>
											</div>
										</div>
										<div class="ccf_content_element_icon_wrapper" style="width: 120px;">
											<span class="ccf_content_icon ccf_content_icon_fax"></span>
											<span class="ccf_content_element_label">Fax:</span>
										</div>
									</div>

									<div class="ccf_content_element">
										<div class="ccf_content_element_content_wrapper">
											<div style="margin-left: 120px;">
												<span>
													Map section example...
												</span>
											</div>
										</div>
										<div class="ccf_content_element_icon_wrapper" style="width: 120px;">
											<span class="ccf_content_icon ccf_content_icon_map"></span>
											<span class="ccf_content_element_label">Map:</span>
										</div>
									</div>

								</div>

		 					</div></div>
		 					<!-- END Left column Conent ***************************************************************** -->
		 				</div>
		 				<div class="ccf_preview_right_col">
		 					<!-- Right column Conent ******************************************************************** -->
		 						<div class="creativecontactform_field_box"><div class="creativecontactform_field_box_inner" style="width: 82%">
						 			<label class="creativecontactform_field_name <?php echo $styles[512];?>" for="name_0_1">
						 				<span class="creative_label_txt_wrapper">
						 					Datepicker <span class="creativecontactform_field_required">*</span>
						 				</span>
						 			</label>
						 			<div class="creativecontactform_input_element creativecontactform_required">
						 				<div class="creative_input_dummy_wrapper">
						 					<input class="creative_name creativecontactform_required creative_input_reset" value="" type="text" id="name_0_1" name="creativecontactform_fields[1][0]">
						 					<img class="ui-datepicker-trigger creative_datepicker_icon" data_src="<?php echo JURI::base(true);?>/../components/com_creativecontactform/assets/images/datepicker/"  src="<?php echo JURI::base(true);?>/../components/com_creativecontactform/assets/images/datepicker/style-<?php echo $styles[563];?>.png" alt="Select date" title="Select date">
				 							<img roll="21" style="top: 2px;right: -50px;" title="Edit: Text Inputs" src="<?php echo JURI::base(true);?>/components/com_creativecontactform/assets/images/tmp_edit4.png" class="navigate_to_option" />
						 				</div>
						 			</div>
						 		</div></div>

						 		<div class="creativecontactform_field_box "><div class="creativecontactform_field_box_inner" style="width: 100%">
						 			<label class="creativecontactform_field_name <?php echo $styles[512];?>" >
						 				<span class="creative_label_txt_wrapper">
						 					File Upload
						 				</span>
						 			</label>
									<div style="position: relative">			
						 				<input style="float: none;" type="button" value="Select Files..." class="creative_fileupload creativecontactform_send <?php echo $styles[515];?>" roll="1" />
			 							<img roll="25" style="top: 8px;right: -5px;" title="Edit: Send Button" src="<?php echo JURI::base(true);?>/components/com_creativecontactform/assets/images/tmp_edit4.png" class="navigate_to_option" />
						 			</div>
						 		</div></div>
								
								<div class="creativecontactform_field_box" style="margin-top: 20px !important; "><div class="creativecontactform_field_box_inner" style="width: 100%">
						 			<span style="position: relative;margin-right: 25px;">
						 				<a href="#" onclick="return false;" class="ccf_link">Links and Popup label.</a>
						 				<img roll="22" style="top: -1px;right: -15px;" title="Edit: Links" src="<?php echo JURI::base(true);?>/components/com_creativecontactform/assets/images/tmp_edit4.png" class="navigate_to_option" />
						 			</span>	
						 			<span style="position: relative;">
						 				<a href="#" onclick="return false;" class="ccf_link_hovered">Hover state.</a>
						 				<img roll="23" style="top: -1px;right: -19px;" title="Edit: Links Hover State" src="<?php echo JURI::base(true);?>/components/com_creativecontactform/assets/images/tmp_edit4.png" class="navigate_to_option" />
						 			</span>
						 		</div></div>

								<div class="creativecontactform_field_box" style="margin-top: 20px !important; "><div class="creativecontactform_field_box_inner" style="width: 100%">
						 			<span style="position: relative;">
						 				<span class="ccf_content_styling">Number styling example.</span>
						 				<img roll="24" style="top: -1px;right: -19px;" title="Edit: Links" src="<?php echo JURI::base(true);?>/components/com_creativecontactform/assets/images/tmp_edit4.png" class="navigate_to_option" />
						 			</span>	
						 		</div></div>

		 					<!-- END Right column Conent **************************************************************** -->
		 				</div>
		 				<div class="creativecontactform_clear"></div>
	 				</div>
	 			</div>
 			</div>
 			</div>
 		</div>
	 </div>
</div>


	
<form action="index.php" method="post" name="adminForm" id="adminForm">
    <fieldset class="adminform" style="position: relative;">
        <legend><?php echo JText::_( 'Custom Styles' ); ?></legend>
        <div id="main_styles_table">
	        <table class="temp_table">
	        <?php seperate_tr("Template Name");
	        	create_accordion('Name','closed');?>
	        <tr>
	            <td width="180" align="right" class="key" style="width: 230px;">
	                <label for="name">
	                    <?php echo JText::_( 'Name' ); ?>:
	                </label>
	            </td>
	            <td class="st_td">
	                <input class="text_area" type="text" name="name" id="name" size="60" maxlength="250" value="<?php echo $this->item->name;?>" />
	            </td>
	            <?php close_accordion();?>
	        </tr>
	        <?php 
	        	$fonts_array = array(
		        				"Standard Fonts" => array(
		        					"inherit" => "Use Parent Font",
			        				"Arial, Helvetica, sans-serif" => "Arial",
			        				"'Comic Sans MS', cursive, sans-serif" => "Comic Sans MS",
			        				"Impact, Charcoal, sans-serif" => "Impact",
			        				"'Lucida Sans Unicode', 'Lucida Grande', sans-serif" => "Lucida Sans Unicode",
			        				"Tahoma, Geneva, sans-serif" => "Tahoma",
			        				"'Trebuchet MS', Helvetica, sans-serif" => "Trebuchet MS",
			        				"Verdana, Geneva, sans-serif" => "Verdana",

			        				"Georgia, serif" => "Georgia",
			        				"'Palatino Linotype', 'Book Antiqua', Palatino, serif" => "Palatino Linotype",
			        				"'Times New Roman', Times, serif" => "Times New Roman",
			        				
			        				"'Courier New', Courier, monospace" => "Courier New",
			        				"Monaco, monospace" => "Monaco",
			        				"'Lucida Console', monospace" => "Lucida Console",
	        					),
	        					"Google Web Fonts" => array(
									"ccf-googlewebfont-ABeeZee" => "ABeeZee",
									"ccf-googlewebfont-Abel" => "Abel",
									// "Abril Fatface" => "Abril Fatface",
									"ccf-googlewebfont-Aclonica" => "Aclonica",
									"ccf-googlewebfont-Acme" => "Acme",
									"ccf-googlewebfont-Actor" => "Actor",
									"ccf-googlewebfont-Adamina" => "Adamina",
									"ccf-googlewebfont-Advent Pro" => "Advent Pro",
									"ccf-googlewebfont-Aguafina Script" => "Aguafina Script",
									"ccf-googlewebfont-Akronim" => "Akronim",
									"ccf-googlewebfont-Aladin" => "Aladin",
									"ccf-googlewebfont-Aldrich" => "Aldrich",
									"ccf-googlewebfont-Alef" => "Alef",
									"ccf-googlewebfont-Alegreya" => "Alegreya",
									"ccf-googlewebfont-Alegreya SC" => "Alegreya SC",
									"ccf-googlewebfont-Alegreya Sans" => "Alegreya Sans",
									"ccf-googlewebfont-Alegreya Sans SC" => "Alegreya Sans SC",
									"ccf-googlewebfont-Alex Brush" => "Alex Brush",
									"ccf-googlewebfont-Alfa Slab One" => "Alfa Slab One",
									"ccf-googlewebfont-Alice" => "Alice",
									"ccf-googlewebfont-Alike" => "Alike",
									"ccf-googlewebfont-Alike Angular" => "Alike Angular",
									"ccf-googlewebfont-Allan" => "Allan",
									"ccf-googlewebfont-Allerta" => "Allerta",
									"ccf-googlewebfont-Allerta Stencil" => "Allerta Stencil",
									"ccf-googlewebfont-Allura" => "Allura",
									"ccf-googlewebfont-Almendra" => "Almendra",
									"ccf-googlewebfont-Almendra Display" => "Almendra Display",
									"ccf-googlewebfont-Almendra SC" => "Almendra SC",
									"ccf-googlewebfont-Amarante" => "Amarante",
									"ccf-googlewebfont-Amaranth" => "Amaranth",
									"ccf-googlewebfont-Amatic SC" => "Amatic SC",
									"ccf-googlewebfont-Amethysta" => "Amethysta",
									"ccf-googlewebfont-Anaheim" => "Anaheim",
									"ccf-googlewebfont-Andada" => "Andada",
									"ccf-googlewebfont-Andika" => "Andika",
									"ccf-googlewebfont-Angkor" => "Angkor",
									"ccf-googlewebfont-Annie Use Your Telescope" => "Annie Use Your Telescope",
									"ccf-googlewebfont-Anonymous Pro" => "Anonymous Pro",
									"ccf-googlewebfont-Antic" => "Antic",
									"ccf-googlewebfont-Antic Didone" => "Antic Didone",
									"ccf-googlewebfont-Antic Slab" => "Antic Slab",
									"ccf-googlewebfont-Anton" => "Anton",
									"ccf-googlewebfont-Arapey" => "Arapey",
									"ccf-googlewebfont-Arbutus" => "Arbutus",
									"ccf-googlewebfont-Arbutus Slab" => "Arbutus Slab",
									"ccf-googlewebfont-Architects Daughter" => "Architects Daughter",
									"ccf-googlewebfont-Archivo Black" => "Archivo Black",
									"ccf-googlewebfont-Archivo Narrow" => "Archivo Narrow",
									"ccf-googlewebfont-Arimo" => "Arimo",
									"ccf-googlewebfont-Arizonia" => "Arizonia",
									"ccf-googlewebfont-Armata" => "Armata",
									"ccf-googlewebfont-Artifika" => "Artifika",
									"ccf-googlewebfont-Arvo" => "Arvo",
									"ccf-googlewebfont-Asap" => "Asap",
									"ccf-googlewebfont-Asset" => "Asset",
									"ccf-googlewebfont-Astloch" => "Astloch",
									"ccf-googlewebfont-Asul" => "Asul",
									"ccf-googlewebfont-Atomic Age" => "Atomic Age",
									"ccf-googlewebfont-Aubrey" => "Aubrey",
									"ccf-googlewebfont-Audiowide" => "Audiowide",
									"ccf-googlewebfont-Autour One" => "Autour One",
									"ccf-googlewebfont-Average" => "Average",
									"ccf-googlewebfont-Average Sans" => "Average Sans",
									"ccf-googlewebfont-Averia Gruesa Libre" => "Averia Gruesa Libre",
									"ccf-googlewebfont-Averia Libre" => "Averia Libre",
									"ccf-googlewebfont-Averia Sans Libre" => "Averia Sans Libre",
									"ccf-googlewebfont-Averia Serif Libre" => "Averia Serif Libre",
									"ccf-googlewebfont-Bad Script" => "Bad Script",
									"ccf-googlewebfont-Balthazar" => "Balthazar",
									"ccf-googlewebfont-Bangers" => "Bangers",
									"ccf-googlewebfont-Basic" => "Basic",
									"ccf-googlewebfont-Battambang" => "Battambang",
									"ccf-googlewebfont-Baumans" => "Baumans",
									"ccf-googlewebfont-Bayon" => "Bayon",
									"ccf-googlewebfont-Belgrano" => "Belgrano",
									"ccf-googlewebfont-Belleza" => "Belleza",
									"ccf-googlewebfont-BenchNine" => "BenchNine",
									"ccf-googlewebfont-Bentham" => "Bentham",
									"ccf-googlewebfont-Berkshire Swash" => "Berkshire Swash",
									"ccf-googlewebfont-Bevan" => "Bevan",
									"ccf-googlewebfont-Bigelow Rules" => "Bigelow Rules",
									"ccf-googlewebfont-Bigshot One" => "Bigshot One",
									"ccf-googlewebfont-Bilbo" => "Bilbo",
									"ccf-googlewebfont-Bilbo Swash Caps" => "Bilbo Swash Caps",
									"ccf-googlewebfont-Bitter" => "Bitter",
									"ccf-googlewebfont-Black Ops One" => "Black Ops One",
									"ccf-googlewebfont-Bokor" => "Bokor",
									"ccf-googlewebfont-Bonbon" => "Bonbon",
									"ccf-googlewebfont-Boogaloo" => "Boogaloo",
									"ccf-googlewebfont-Bowlby One" => "Bowlby One",
									"ccf-googlewebfont-Bowlby One SC" => "Bowlby One SC",
									"ccf-googlewebfont-Brawler" => "Brawler",
									"ccf-googlewebfont-Bree Serif" => "Bree Serif",
									"ccf-googlewebfont-Bubblegum Sans" => "Bubblegum Sans",
									"ccf-googlewebfont-Bubbler One" => "Bubbler One",
									"ccf-googlewebfont-Buda" => "Buda",
									"ccf-googlewebfont-Buenard" => "Buenard",
									"ccf-googlewebfont-Butcherman" => "Butcherman",
									"ccf-googlewebfont-Butterfly Kids" => "Butterfly Kids",
									"ccf-googlewebfont-Cabin" => "Cabin",
									"ccf-googlewebfont-Cabin Condensed" => "Cabin Condensed",
									"ccf-googlewebfont-Cabin Sketch" => "Cabin Sketch",
									"ccf-googlewebfont-Caesar Dressing" => "Caesar Dressing",
									"ccf-googlewebfont-Cagliostro" => "Cagliostro",
									"ccf-googlewebfont-Calligraffitti" => "Calligraffitti",
									"ccf-googlewebfont-Cambo" => "Cambo",
									"ccf-googlewebfont-Candal" => "Candal",
									"ccf-googlewebfont-Cantarell" => "Cantarell",
									"ccf-googlewebfont-Cantata One" => "Cantata One",
									"ccf-googlewebfont-Cantora One" => "Cantora One",
									"ccf-googlewebfont-Capriola" => "Capriola",
									"ccf-googlewebfont-Cardo" => "Cardo",
									"ccf-googlewebfont-Carme" => "Carme",
									"ccf-googlewebfont-Carrois Gothic" => "Carrois Gothic",
									"ccf-googlewebfont-Carrois Gothic SC" => "Carrois Gothic SC",
									"ccf-googlewebfont-Carter One" => "Carter One",
									"ccf-googlewebfont-Caudex" => "Caudex",
									"ccf-googlewebfont-Cedarville Cursive" => "Cedarville Cursive",
									"ccf-googlewebfont-Ceviche One" => "Ceviche One",
									"ccf-googlewebfont-Changa One" => "Changa One",
									"ccf-googlewebfont-Chango" => "Chango",
									"ccf-googlewebfont-Chau Philomene One" => "Chau Philomene One",
									"ccf-googlewebfont-Chela One" => "Chela One",
									"ccf-googlewebfont-Chelsea Market" => "Chelsea Market",
									"ccf-googlewebfont-Chenla" => "Chenla",
									"ccf-googlewebfont-Cherry Cream Soda" => "Cherry Cream Soda",
									"ccf-googlewebfont-Cherry Swash" => "Cherry Swash",
									"ccf-googlewebfont-Chewy" => "Chewy",
									"ccf-googlewebfont-Chicle" => "Chicle",
									"ccf-googlewebfont-Chivo" => "Chivo",
									"ccf-googlewebfont-Cinzel" => "Cinzel",
									"ccf-googlewebfont-Cinzel Decorative" => "Cinzel Decorative",
									"ccf-googlewebfont-Clicker Script" => "Clicker Script",
									"ccf-googlewebfont-Coda" => "Coda",
									"ccf-googlewebfont-Coda Caption" => "Coda Caption",
									"ccf-googlewebfont-Codystar" => "Codystar",
									"ccf-googlewebfont-Combo" => "Combo",
									"ccf-googlewebfont-Comfortaa" => "Comfortaa",
									"ccf-googlewebfont-Coming Soon" => "Coming Soon",
									"ccf-googlewebfont-Concert One" => "Concert One",
									"ccf-googlewebfont-Condiment" => "Condiment",
									"ccf-googlewebfont-Content" => "Content",
									"ccf-googlewebfont-Contrail One" => "Contrail One",
									"ccf-googlewebfont-Convergence" => "Convergence",
									"ccf-googlewebfont-Cookie" => "Cookie",
									"ccf-googlewebfont-Copse" => "Copse",
									"ccf-googlewebfont-Corben" => "Corben",
									"ccf-googlewebfont-Courgette" => "Courgette",
									"ccf-googlewebfont-Cousine" => "Cousine",
									"ccf-googlewebfont-Coustard" => "Coustard",
									"ccf-googlewebfont-Covered By Your Grace" => "Covered By Your Grace",
									"ccf-googlewebfont-Crafty Girls" => "Crafty Girls",
									"ccf-googlewebfont-Creepster" => "Creepster",
									"ccf-googlewebfont-Crete Round" => "Crete Round",
									"ccf-googlewebfont-Crimson Text" => "Crimson Text",
									"ccf-googlewebfont-Croissant One" => "Croissant One",
									"ccf-googlewebfont-Crushed" => "Crushed",
									"ccf-googlewebfont-Cuprum" => "Cuprum",
									"ccf-googlewebfont-Cutive" => "Cutive",
									"ccf-googlewebfont-Cutive Mono" => "Cutive Mono",
									"ccf-googlewebfont-Damion" => "Damion",
									"ccf-googlewebfont-Dancing Script" => "Dancing Script",
									"ccf-googlewebfont-Dangrek" => "Dangrek",
									"ccf-googlewebfont-Dawning of a New Day" => "Dawning of a New Day",
									"ccf-googlewebfont-Days One" => "Days One",
									"ccf-googlewebfont-Delius" => "Delius",
									"ccf-googlewebfont-Delius Swash Caps" => "Delius Swash Caps",
									"ccf-googlewebfont-Delius Unicase" => "Delius Unicase",
									"ccf-googlewebfont-Della Respira" => "Della Respira",
									"ccf-googlewebfont-Denk One" => "Denk One",
									"ccf-googlewebfont-Devonshire" => "Devonshire",
									"ccf-googlewebfont-Didact Gothic" => "Didact Gothic",
									"ccf-googlewebfont-Diplomata" => "Diplomata",
									"ccf-googlewebfont-Diplomata SC" => "Diplomata SC",
									"ccf-googlewebfont-Domine" => "Domine",
									"ccf-googlewebfont-Donegal One" => "Donegal One",
									"ccf-googlewebfont-Doppio One" => "Doppio One",
									"ccf-googlewebfont-Dorsa" => "Dorsa",
									"ccf-googlewebfont-Dosis" => "Dosis",
									"ccf-googlewebfont-Dr Sugiyama" => "Dr Sugiyama",
									"ccf-googlewebfont-Droid Sans" => "Droid Sans",
									"ccf-googlewebfont-Droid Sans Mono" => "Droid Sans Mono",
									"ccf-googlewebfont-Droid Serif" => "Droid Serif",
									"ccf-googlewebfont-Duru Sans" => "Duru Sans",
									"ccf-googlewebfont-Dynalight" => "Dynalight",
									"ccf-googlewebfont-EB Garamond" => "EB Garamond",
									"ccf-googlewebfont-Eagle Lake" => "Eagle Lake",
									"ccf-googlewebfont-Eater" => "Eater",
									"ccf-googlewebfont-Economica" => "Economica",
									"ccf-googlewebfont-Ek Mukta" => "Ek Mukta",
									"ccf-googlewebfont-Electrolize" => "Electrolize",
									"ccf-googlewebfont-Elsie" => "Elsie",
									"ccf-googlewebfont-Elsie Swash Caps" => "Elsie Swash Caps",
									"ccf-googlewebfont-Emblema One" => "Emblema One",
									"ccf-googlewebfont-Emilys Candy" => "Emilys Candy",
									"ccf-googlewebfont-Engagement" => "Engagement",
									"ccf-googlewebfont-Englebert" => "Englebert",
									"ccf-googlewebfont-Enriqueta" => "Enriqueta",
									"ccf-googlewebfont-Erica One" => "Erica One",
									"ccf-googlewebfont-Esteban" => "Esteban",
									"ccf-googlewebfont-Euphoria Script" => "Euphoria Script",
									"ccf-googlewebfont-Ewert" => "Ewert",
									"ccf-googlewebfont-Exo" => "Exo",
									"ccf-googlewebfont-Exo 2" => "Exo 2",
									"ccf-googlewebfont-Expletus Sans" => "Expletus Sans",
									"ccf-googlewebfont-Fanwood Text" => "Fanwood Text",
									"ccf-googlewebfont-Fascinate" => "Fascinate",
									"ccf-googlewebfont-Fascinate Inline" => "Fascinate Inline",
									"ccf-googlewebfont-Faster One" => "Faster One",
									"ccf-googlewebfont-Fasthand" => "Fasthand",
									"ccf-googlewebfont-Fauna One" => "Fauna One",
									"ccf-googlewebfont-Federant" => "Federant",
									"ccf-googlewebfont-Federo" => "Federo",
									"ccf-googlewebfont-Felipa" => "Felipa",
									"ccf-googlewebfont-Fenix" => "Fenix",
									"ccf-googlewebfont-Finger Paint" => "Finger Paint",
									"ccf-googlewebfont-Fira Mono" => "Fira Mono",
									"ccf-googlewebfont-Fira Sans" => "Fira Sans",
									"ccf-googlewebfont-Fjalla One" => "Fjalla One",
									"ccf-googlewebfont-Fjord One" => "Fjord One",
									"ccf-googlewebfont-Flamenco" => "Flamenco",
									"ccf-googlewebfont-Flavors" => "Flavors",
									"ccf-googlewebfont-Fondamento" => "Fondamento",
									"ccf-googlewebfont-Fontdiner Swanky" => "Fontdiner Swanky",
									"ccf-googlewebfont-Forum" => "Forum",
									"ccf-googlewebfont-Francois One" => "Francois One",
									"ccf-googlewebfont-Freckle Face" => "Freckle Face",
									"ccf-googlewebfont-Fredericka the Great" => "Fredericka the Great",
									"ccf-googlewebfont-Fredoka One" => "Fredoka One",
									"ccf-googlewebfont-Freehand" => "Freehand",
									"ccf-googlewebfont-Fresca" => "Fresca",
									"ccf-googlewebfont-Frijole" => "Frijole",
									"ccf-googlewebfont-Fruktur" => "Fruktur",
									"ccf-googlewebfont-Fugaz One" => "Fugaz One",
									"ccf-googlewebfont-GFS Didot" => "GFS Didot",
									"ccf-googlewebfont-GFS Neohellenic" => "GFS Neohellenic",
									"ccf-googlewebfont-Gabriela" => "Gabriela",
									"ccf-googlewebfont-Gafata" => "Gafata",
									"ccf-googlewebfont-Galdeano" => "Galdeano",
									"ccf-googlewebfont-Galindo" => "Galindo",
									"ccf-googlewebfont-Gentium Basic" => "Gentium Basic",
									"ccf-googlewebfont-Gentium Book Basic" => "Gentium Book Basic",
									"ccf-googlewebfont-Geo" => "Geo",
									"ccf-googlewebfont-Geostar" => "Geostar",
									"ccf-googlewebfont-Geostar Fill" => "Geostar Fill",
									"ccf-googlewebfont-Germania One" => "Germania One",
									"ccf-googlewebfont-Gilda Display" => "Gilda Display",
									"ccf-googlewebfont-Give You Glory" => "Give You Glory",
									"ccf-googlewebfont-Glass Antiqua" => "Glass Antiqua",
									"ccf-googlewebfont-Glegoo" => "Glegoo",
									"ccf-googlewebfont-Gloria Hallelujah" => "Gloria Hallelujah",
									"ccf-googlewebfont-Goblin One" => "Goblin One",
									"ccf-googlewebfont-Gochi Hand" => "Gochi Hand",
									"ccf-googlewebfont-Gorditas" => "Gorditas",
									"ccf-googlewebfont-Goudy Bookletter 1911" => "Goudy Bookletter 1911",
									"ccf-googlewebfont-Graduate" => "Graduate",
									"ccf-googlewebfont-Grand Hotel" => "Grand Hotel",
									"ccf-googlewebfont-Gravitas One" => "Gravitas One",
									"ccf-googlewebfont-Great Vibes" => "Great Vibes",
									"ccf-googlewebfont-Griffy" => "Griffy",
									"ccf-googlewebfont-Gruppo" => "Gruppo",
									"ccf-googlewebfont-Gudea" => "Gudea",
									"ccf-googlewebfont-Habibi" => "Habibi",
									"ccf-googlewebfont-Halant" => "Halant",
									"ccf-googlewebfont-Hammersmith One" => "Hammersmith One",
									"ccf-googlewebfont-Hanalei" => "Hanalei",
									"ccf-googlewebfont-Hanalei Fill" => "Hanalei Fill",
									"ccf-googlewebfont-Handlee" => "Handlee",
									"ccf-googlewebfont-Hanuman" => "Hanuman",
									"ccf-googlewebfont-Happy Monkey" => "Happy Monkey",
									"ccf-googlewebfont-Headland One" => "Headland One",
									"ccf-googlewebfont-Henny Penny" => "Henny Penny",
									"ccf-googlewebfont-Herr Von Muellerhoff" => "Herr Von Muellerhoff",
									"ccf-googlewebfont-Hind" => "Hind",
									"ccf-googlewebfont-Holtwood One SC" => "Holtwood One SC",
									"ccf-googlewebfont-Homemade Apple" => "Homemade Apple",
									"ccf-googlewebfont-Homenaje" => "Homenaje",
									"ccf-googlewebfont-IM Fell DW Pica" => "IM Fell DW Pica",
									"ccf-googlewebfont-IM Fell DW Pica SC" => "IM Fell DW Pica SC",
									"ccf-googlewebfont-IM Fell Double Pica" => "IM Fell Double Pica",
									"ccf-googlewebfont-IM Fell Double Pica SC" => "IM Fell Double Pica SC",
									"ccf-googlewebfont-IM Fell English" => "IM Fell English",
									"ccf-googlewebfont-IM Fell English SC" => "IM Fell English SC",
									"ccf-googlewebfont-IM Fell French Canon" => "IM Fell French Canon",
									"ccf-googlewebfont-IM Fell French Canon SC" => "IM Fell French Canon SC",
									"ccf-googlewebfont-IM Fell Great Primer" => "IM Fell Great Primer",
									"ccf-googlewebfont-IM Fell Great Primer SC" => "IM Fell Great Primer SC",
									"ccf-googlewebfont-Iceberg" => "Iceberg",
									"ccf-googlewebfont-Iceland" => "Iceland",
									"ccf-googlewebfont-Imprima" => "Imprima",
									"ccf-googlewebfont-Inconsolata" => "Inconsolata",
									"ccf-googlewebfont-Inder" => "Inder",
									"ccf-googlewebfont-Indie Flower" => "Indie Flower",
									"ccf-googlewebfont-Inika" => "Inika",
									"ccf-googlewebfont-Irish Grover" => "Irish Grover",
									"ccf-googlewebfont-Istok Web" => "Istok Web",
									"ccf-googlewebfont-Italiana" => "Italiana",
									"ccf-googlewebfont-Italianno" => "Italianno",
									"ccf-googlewebfont-Jacques Francois" => "Jacques Francois",
									"ccf-googlewebfont-Jacques Francois Shadow" => "Jacques Francois Shadow",
									"ccf-googlewebfont-Jim Nightshade" => "Jim Nightshade",
									"ccf-googlewebfont-Jockey One" => "Jockey One",
									"ccf-googlewebfont-Jolly Lodger" => "Jolly Lodger",
									"ccf-googlewebfont-Josefin Sans" => "Josefin Sans",
									"ccf-googlewebfont-Josefin Slab" => "Josefin Slab",
									"ccf-googlewebfont-Joti One" => "Joti One",
									"ccf-googlewebfont-Judson" => "Judson",
									"ccf-googlewebfont-Julee" => "Julee",
									"ccf-googlewebfont-Julius Sans One" => "Julius Sans One",
									"ccf-googlewebfont-Junge" => "Junge",
									"ccf-googlewebfont-Jura" => "Jura",
									"ccf-googlewebfont-Just Another Hand" => "Just Another Hand",
									"ccf-googlewebfont-Just Me Again Down Here" => "Just Me Again Down Here",
									"ccf-googlewebfont-Kalam" => "Kalam",
									"ccf-googlewebfont-Kameron" => "Kameron",
									"ccf-googlewebfont-Kantumruy" => "Kantumruy",
									"ccf-googlewebfont-Karla" => "Karla",
									"ccf-googlewebfont-Karma" => "Karma",
									"ccf-googlewebfont-Kaushan Script" => "Kaushan Script",
									"ccf-googlewebfont-Kavoon" => "Kavoon",
									"ccf-googlewebfont-Kdam Thmor" => "Kdam Thmor",
									"ccf-googlewebfont-Keania One" => "Keania One",
									"ccf-googlewebfont-Kelly Slab" => "Kelly Slab",
									"ccf-googlewebfont-Kenia" => "Kenia",
									"ccf-googlewebfont-Khand" => "Khand",
									"ccf-googlewebfont-Khmer" => "Khmer",
									"ccf-googlewebfont-Kite One" => "Kite One",
									"ccf-googlewebfont-Knewave" => "Knewave",
									"ccf-googlewebfont-Kotta One" => "Kotta One",
									"ccf-googlewebfont-Koulen" => "Koulen",
									"ccf-googlewebfont-Kranky" => "Kranky",
									"ccf-googlewebfont-Kreon" => "Kreon",
									"ccf-googlewebfont-Kristi" => "Kristi",
									"ccf-googlewebfont-Krona One" => "Krona One",
									"ccf-googlewebfont-La Belle Aurore" => "La Belle Aurore",
									"ccf-googlewebfont-Laila" => "Laila",
									"ccf-googlewebfont-Lancelot" => "Lancelot",
									"ccf-googlewebfont-Lato" => "Lato",
									"ccf-googlewebfont-League Script" => "League Script",
									"ccf-googlewebfont-Leckerli One" => "Leckerli One",
									"ccf-googlewebfont-Ledger" => "Ledger",
									"ccf-googlewebfont-Lekton" => "Lekton",
									"ccf-googlewebfont-Lemon" => "Lemon",
									"ccf-googlewebfont-Libre Baskerville" => "Libre Baskerville",
									"ccf-googlewebfont-Life Savers" => "Life Savers",
									"ccf-googlewebfont-Lilita One" => "Lilita One",
									"ccf-googlewebfont-Lily Script One" => "Lily Script One",
									"ccf-googlewebfont-Limelight" => "Limelight",
									"ccf-googlewebfont-Linden Hill" => "Linden Hill",
									"ccf-googlewebfont-Lobster" => "Lobster",
									"ccf-googlewebfont-Lobster Two" => "Lobster Two",
									"ccf-googlewebfont-Londrina Outline" => "Londrina Outline",
									"ccf-googlewebfont-Londrina Shadow" => "Londrina Shadow",
									"ccf-googlewebfont-Londrina Sketch" => "Londrina Sketch",
									"ccf-googlewebfont-Londrina Solid" => "Londrina Solid",
									"ccf-googlewebfont-Lora" => "Lora",
									"ccf-googlewebfont-Love Ya Like A Sister" => "Love Ya Like A Sister",
									"ccf-googlewebfont-Loved by the King" => "Loved by the King",
									"ccf-googlewebfont-Lovers Quarrel" => "Lovers Quarrel",
									"ccf-googlewebfont-Luckiest Guy" => "Luckiest Guy",
									"ccf-googlewebfont-Lusitana" => "Lusitana",
									"ccf-googlewebfont-Lustria" => "Lustria",
									"ccf-googlewebfont-Macondo" => "Macondo",
									"ccf-googlewebfont-Macondo Swash Caps" => "Macondo Swash Caps",
									"ccf-googlewebfont-Magra" => "Magra",
									"ccf-googlewebfont-Maiden Orange" => "Maiden Orange",
									"ccf-googlewebfont-Mako" => "Mako",
									"ccf-googlewebfont-Marcellus" => "Marcellus",
									"ccf-googlewebfont-Marcellus SC" => "Marcellus SC",
									"ccf-googlewebfont-Marck Script" => "Marck Script",
									"ccf-googlewebfont-Margarine" => "Margarine",
									"ccf-googlewebfont-Marko One" => "Marko One",
									"ccf-googlewebfont-Marmelad" => "Marmelad",
									"ccf-googlewebfont-Marvel" => "Marvel",
									"ccf-googlewebfont-Mate" => "Mate",
									"ccf-googlewebfont-Mate SC" => "Mate SC",
									"ccf-googlewebfont-Maven Pro" => "Maven Pro",
									"ccf-googlewebfont-McLaren" => "McLaren",
									"ccf-googlewebfont-Meddon" => "Meddon",
									"ccf-googlewebfont-MedievalSharp" => "MedievalSharp",
									"ccf-googlewebfont-Medula One" => "Medula One",
									"ccf-googlewebfont-Megrim" => "Megrim",
									"ccf-googlewebfont-Meie Script" => "Meie Script",
									"ccf-googlewebfont-Merienda" => "Merienda",
									"ccf-googlewebfont-Merienda One" => "Merienda One",
									"ccf-googlewebfont-Merriweather" => "Merriweather",
									"ccf-googlewebfont-Merriweather Sans" => "Merriweather Sans",
									"ccf-googlewebfont-Metal" => "Metal",
									"ccf-googlewebfont-Metal Mania" => "Metal Mania",
									"ccf-googlewebfont-Metamorphous" => "Metamorphous",
									"ccf-googlewebfont-Metrophobic" => "Metrophobic",
									"ccf-googlewebfont-Michroma" => "Michroma",
									"ccf-googlewebfont-Milonga" => "Milonga",
									"ccf-googlewebfont-Miltonian" => "Miltonian",
									"ccf-googlewebfont-Miltonian Tattoo" => "Miltonian Tattoo",
									"ccf-googlewebfont-Miniver" => "Miniver",
									"ccf-googlewebfont-Miss Fajardose" => "Miss Fajardose",
									"ccf-googlewebfont-Modern Antiqua" => "Modern Antiqua",
									"ccf-googlewebfont-Molengo" => "Molengo",
									"ccf-googlewebfont-Molle" => "Molle",
									"ccf-googlewebfont-Monda" => "Monda",
									"ccf-googlewebfont-Monofett" => "Monofett",
									"ccf-googlewebfont-Monoton" => "Monoton",
									"ccf-googlewebfont-Monsieur La Doulaise" => "Monsieur La Doulaise",
									"ccf-googlewebfont-Montaga" => "Montaga",
									"ccf-googlewebfont-Montez" => "Montez",
									"ccf-googlewebfont-Montserrat" => "Montserrat",
									"ccf-googlewebfont-Montserrat Alternates" => "Montserrat Alternates",
									"ccf-googlewebfont-Montserrat Subrayada" => "Montserrat Subrayada",
									"ccf-googlewebfont-Moul" => "Moul",
									"ccf-googlewebfont-Moulpali" => "Moulpali",
									"ccf-googlewebfont-Mountains of Christmas" => "Mountains of Christmas",
									"ccf-googlewebfont-Mouse Memoirs" => "Mouse Memoirs",
									"ccf-googlewebfont-Mr Bedfort" => "Mr Bedfort",
									"ccf-googlewebfont-Mr Dafoe" => "Mr Dafoe",
									"ccf-googlewebfont-Mr De Haviland" => "Mr De Haviland",
									"ccf-googlewebfont-Mrs Saint Delafield" => "Mrs Saint Delafield",
									"ccf-googlewebfont-Mrs Sheppards" => "Mrs Sheppards",
									"ccf-googlewebfont-Muli" => "Muli",
									"ccf-googlewebfont-Mystery Quest" => "Mystery Quest",
									"ccf-googlewebfont-Neucha" => "Neucha",
									"ccf-googlewebfont-Neuton" => "Neuton",
									"ccf-googlewebfont-New Rocker" => "New Rocker",
									"ccf-googlewebfont-News Cycle" => "News Cycle",
									"ccf-googlewebfont-Niconne" => "Niconne",
									"ccf-googlewebfont-Nixie One" => "Nixie One",
									"ccf-googlewebfont-Nobile" => "Nobile",
									"ccf-googlewebfont-Nokora" => "Nokora",
									"ccf-googlewebfont-Norican" => "Norican",
									"ccf-googlewebfont-Nosifer" => "Nosifer",
									"ccf-googlewebfont-Nothing You Could Do" => "Nothing You Could Do",
									"ccf-googlewebfont-Noticia Text" => "Noticia Text",
									"ccf-googlewebfont-Noto Sans" => "Noto Sans",
									"ccf-googlewebfont-Noto Serif" => "Noto Serif",
									"ccf-googlewebfont-Nova Cut" => "Nova Cut",
									"ccf-googlewebfont-Nova Flat" => "Nova Flat",
									"ccf-googlewebfont-Nova Mono" => "Nova Mono",
									"ccf-googlewebfont-Nova Oval" => "Nova Oval",
									"ccf-googlewebfont-Nova Round" => "Nova Round",
									"ccf-googlewebfont-Nova Script" => "Nova Script",
									"ccf-googlewebfont-Nova Slim" => "Nova Slim",
									"ccf-googlewebfont-Nova Square" => "Nova Square",
									"ccf-googlewebfont-Numans" => "Numans",
									"ccf-googlewebfont-Nunito" => "Nunito",
									"ccf-googlewebfont-Odor Mean Chey" => "Odor Mean Chey",
									"ccf-googlewebfont-Offside" => "Offside",
									"ccf-googlewebfont-Old Standard TT" => "Old Standard TT",
									"ccf-googlewebfont-Oldenburg" => "Oldenburg",
									"ccf-googlewebfont-Oleo Script" => "Oleo Script",
									"ccf-googlewebfont-Oleo Script Swash Caps" => "Oleo Script Swash Caps",
									"ccf-googlewebfont-Open Sans" => "Open Sans",
									"ccf-googlewebfont-Open Sans Condensed" => "Open Sans Condensed",
									"ccf-googlewebfont-Oranienbaum" => "Oranienbaum",
									"ccf-googlewebfont-Orbitron" => "Orbitron",
									"ccf-googlewebfont-Oregano" => "Oregano",
									"ccf-googlewebfont-Orienta" => "Orienta",
									"ccf-googlewebfont-Original Surfer" => "Original Surfer",
									"ccf-googlewebfont-Oswald" => "Oswald",
									"ccf-googlewebfont-Over the Rainbow" => "Over the Rainbow",
									"ccf-googlewebfont-Overlock" => "Overlock",
									"ccf-googlewebfont-Overlock SC" => "Overlock SC",
									"ccf-googlewebfont-Ovo" => "Ovo",
									"ccf-googlewebfont-Oxygen" => "Oxygen",
									"ccf-googlewebfont-Oxygen Mono" => "Oxygen Mono",
									"ccf-googlewebfont-PT Mono" => "PT Mono",
									"ccf-googlewebfont-PT Sans" => "PT Sans",
									"ccf-googlewebfont-PT Sans Caption" => "PT Sans Caption",
									"ccf-googlewebfont-PT Sans Narrow" => "PT Sans Narrow",
									"ccf-googlewebfont-PT Serif" => "PT Serif",
									"ccf-googlewebfont-PT Serif Caption" => "PT Serif Caption",
									"ccf-googlewebfont-Pacifico" => "Pacifico",
									"ccf-googlewebfont-Paprika" => "Paprika",
									"ccf-googlewebfont-Parisienne" => "Parisienne",
									"ccf-googlewebfont-Passero One" => "Passero One",
									"ccf-googlewebfont-Passion One" => "Passion One",
									"ccf-googlewebfont-Pathway Gothic One" => "Pathway Gothic One",
									"ccf-googlewebfont-Patrick Hand" => "Patrick Hand",
									"ccf-googlewebfont-Patrick Hand SC" => "Patrick Hand SC",
									"ccf-googlewebfont-Patua One" => "Patua One",
									"ccf-googlewebfont-Paytone One" => "Paytone One",
									"ccf-googlewebfont-Peralta" => "Peralta",
									"ccf-googlewebfont-Permanent Marker" => "Permanent Marker",
									"ccf-googlewebfont-Petit Formal Script" => "Petit Formal Script",
									"ccf-googlewebfont-Petrona" => "Petrona",
									"ccf-googlewebfont-Philosopher" => "Philosopher",
									"ccf-googlewebfont-Piedra" => "Piedra",
									"ccf-googlewebfont-Pinyon Script" => "Pinyon Script",
									"ccf-googlewebfont-Pirata One" => "Pirata One",
									"ccf-googlewebfont-Plaster" => "Plaster",
									"ccf-googlewebfont-Play" => "Play",
									"ccf-googlewebfont-Playball" => "Playball",
									"ccf-googlewebfont-Playfair Display" => "Playfair Display",
									"ccf-googlewebfont-Playfair Display SC" => "Playfair Display SC",
									"ccf-googlewebfont-Podkova" => "Podkova",
									"ccf-googlewebfont-Poiret One" => "Poiret One",
									"ccf-googlewebfont-Poller One" => "Poller One",
									"ccf-googlewebfont-Poly" => "Poly",
									"ccf-googlewebfont-Pompiere" => "Pompiere",
									"ccf-googlewebfont-Pontano Sans" => "Pontano Sans",
									"ccf-googlewebfont-Port Lligat Sans" => "Port Lligat Sans",
									"ccf-googlewebfont-Port Lligat Slab" => "Port Lligat Slab",
									"ccf-googlewebfont-Prata" => "Prata",
									"ccf-googlewebfont-Preahvihear" => "Preahvihear",
									"ccf-googlewebfont-Press Start 2P" => "Press Start 2P",
									"ccf-googlewebfont-Princess Sofia" => "Princess Sofia",
									"ccf-googlewebfont-Prociono" => "Prociono",
									"ccf-googlewebfont-Prosto One" => "Prosto One",
									"ccf-googlewebfont-Puritan" => "Puritan",
									"ccf-googlewebfont-Purple Purse" => "Purple Purse",
									"ccf-googlewebfont-Quando" => "Quando",
									"ccf-googlewebfont-Quantico" => "Quantico",
									"ccf-googlewebfont-Quattrocento" => "Quattrocento",
									"ccf-googlewebfont-Quattrocento Sans" => "Quattrocento Sans",
									"ccf-googlewebfont-Questrial" => "Questrial",
									"ccf-googlewebfont-Quicksand" => "Quicksand",
									"ccf-googlewebfont-Quintessential" => "Quintessential",
									"ccf-googlewebfont-Qwigley" => "Qwigley",
									"ccf-googlewebfont-Racing Sans One" => "Racing Sans One",
									"ccf-googlewebfont-Radley" => "Radley",
									"ccf-googlewebfont-Rajdhani" => "Rajdhani",
									"ccf-googlewebfont-Raleway" => "Raleway",
									"ccf-googlewebfont-Raleway Dots" => "Raleway Dots",
									"ccf-googlewebfont-Rambla" => "Rambla",
									"ccf-googlewebfont-Rammetto One" => "Rammetto One",
									"ccf-googlewebfont-Ranchers" => "Ranchers",
									"ccf-googlewebfont-Rancho" => "Rancho",
									"ccf-googlewebfont-Rationale" => "Rationale",
									"ccf-googlewebfont-Redressed" => "Redressed",
									"ccf-googlewebfont-Reenie Beanie" => "Reenie Beanie",
									"ccf-googlewebfont-Revalia" => "Revalia",
									"ccf-googlewebfont-Ribeye" => "Ribeye",
									"ccf-googlewebfont-Ribeye Marrow" => "Ribeye Marrow",
									"ccf-googlewebfont-Righteous" => "Righteous",
									"ccf-googlewebfont-Risque" => "Risque",
									"ccf-googlewebfont-Roboto" => "Roboto",
									"ccf-googlewebfont-Roboto Condensed" => "Roboto Condensed",
									"ccf-googlewebfont-Roboto Slab" => "Roboto Slab",
									"ccf-googlewebfont-Rochester" => "Rochester",
									"ccf-googlewebfont-Rock Salt" => "Rock Salt",
									"ccf-googlewebfont-Rokkitt" => "Rokkitt",
									"ccf-googlewebfont-Romanesco" => "Romanesco",
									"ccf-googlewebfont-Ropa Sans" => "Ropa Sans",
									"ccf-googlewebfont-Rosario" => "Rosario",
									"ccf-googlewebfont-Rosarivo" => "Rosarivo",
									"ccf-googlewebfont-Rouge Script" => "Rouge Script",
									"ccf-googlewebfont-Rozha One" => "Rozha One",
									"ccf-googlewebfont-Rubik Mono One" => "Rubik Mono One",
									"ccf-googlewebfont-Rubik One" => "Rubik One",
									"ccf-googlewebfont-Ruda" => "Ruda",
									"ccf-googlewebfont-Rufina" => "Rufina",
									"ccf-googlewebfont-Ruge Boogie" => "Ruge Boogie",
									"ccf-googlewebfont-Ruluko" => "Ruluko",
									"ccf-googlewebfont-Rum Raisin" => "Rum Raisin",
									"ccf-googlewebfont-Ruslan Display" => "Ruslan Display",
									"ccf-googlewebfont-Russo One" => "Russo One",
									"ccf-googlewebfont-Ruthie" => "Ruthie",
									"ccf-googlewebfont-Rye" => "Rye",
									"ccf-googlewebfont-Sacramento" => "Sacramento",
									"ccf-googlewebfont-Sail" => "Sail",
									"ccf-googlewebfont-Salsa" => "Salsa",
									"ccf-googlewebfont-Sanchez" => "Sanchez",
									"ccf-googlewebfont-Sancreek" => "Sancreek",
									"ccf-googlewebfont-Sansita One" => "Sansita One",
									"ccf-googlewebfont-Sarina" => "Sarina",
									"ccf-googlewebfont-Sarpanch" => "Sarpanch",
									"ccf-googlewebfont-Satisfy" => "Satisfy",
									"ccf-googlewebfont-Scada" => "Scada",
									"ccf-googlewebfont-Schoolbell" => "Schoolbell",
									"ccf-googlewebfont-Seaweed Script" => "Seaweed Script",
									"ccf-googlewebfont-Sevillana" => "Sevillana",
									"ccf-googlewebfont-Seymour One" => "Seymour One",
									"ccf-googlewebfont-Shadows Into Light" => "Shadows Into Light",
									"ccf-googlewebfont-Shadows Into Light Two" => "Shadows Into Light Two",
									"ccf-googlewebfont-Shanti" => "Shanti",
									"ccf-googlewebfont-Share" => "Share",
									"ccf-googlewebfont-Share Tech" => "Share Tech",
									"ccf-googlewebfont-Share Tech Mono" => "Share Tech Mono",
									"ccf-googlewebfont-Shojumaru" => "Shojumaru",
									"ccf-googlewebfont-Short Stack" => "Short Stack",
									"ccf-googlewebfont-Siemreap" => "Siemreap",
									"ccf-googlewebfont-Sigmar One" => "Sigmar One",
									"ccf-googlewebfont-Signika" => "Signika",
									"ccf-googlewebfont-Signika Negative" => "Signika Negative",
									"ccf-googlewebfont-Simonetta" => "Simonetta",
									"ccf-googlewebfont-Sintony" => "Sintony",
									"ccf-googlewebfont-Sirin Stencil" => "Sirin Stencil",
									"ccf-googlewebfont-Six Caps" => "Six Caps",
									"ccf-googlewebfont-Skranji" => "Skranji",
									"ccf-googlewebfont-Slabo 13px" => "Slabo 13px",
									"ccf-googlewebfont-Slabo 27px" => "Slabo 27px",
									"ccf-googlewebfont-Slackey" => "Slackey",
									"ccf-googlewebfont-Smokum" => "Smokum",
									"ccf-googlewebfont-Smythe" => "Smythe",
									"ccf-googlewebfont-Sniglet" => "Sniglet",
									"ccf-googlewebfont-Snippet" => "Snippet",
									"ccf-googlewebfont-Snowburst One" => "Snowburst One",
									"ccf-googlewebfont-Sofadi One" => "Sofadi One",
									"ccf-googlewebfont-Sofia" => "Sofia",
									"ccf-googlewebfont-Sonsie One" => "Sonsie One",
									"ccf-googlewebfont-Sorts Mill Goudy" => "Sorts Mill Goudy",
									"ccf-googlewebfont-Source Code Pro" => "Source Code Pro",
									"ccf-googlewebfont-Source Sans Pro" => "Source Sans Pro",
									"ccf-googlewebfont-Source Serif Pro" => "Source Serif Pro",
									"ccf-googlewebfont-Special Elite" => "Special Elite",
									"ccf-googlewebfont-Spicy Rice" => "Spicy Rice",
									"ccf-googlewebfont-Spinnaker" => "Spinnaker",
									"ccf-googlewebfont-Spirax" => "Spirax",
									"ccf-googlewebfont-Squada One" => "Squada One",
									"ccf-googlewebfont-Stalemate" => "Stalemate",
									"ccf-googlewebfont-Stalinist One" => "Stalinist One",
									"ccf-googlewebfont-Stardos Stencil" => "Stardos Stencil",
									"ccf-googlewebfont-Stint Ultra Condensed" => "Stint Ultra Condensed",
									"ccf-googlewebfont-Stint Ultra Expanded" => "Stint Ultra Expanded",
									"ccf-googlewebfont-Stoke" => "Stoke",
									"ccf-googlewebfont-Strait" => "Strait",
									"ccf-googlewebfont-Sue Ellen Francisco" => "Sue Ellen Francisco",
									"ccf-googlewebfont-Sunshiney" => "Sunshiney",
									"ccf-googlewebfont-Supermercado One" => "Supermercado One",
									"ccf-googlewebfont-Suwannaphum" => "Suwannaphum",
									"ccf-googlewebfont-Swanky and Moo Moo" => "Swanky and Moo Moo",
									"ccf-googlewebfont-Syncopate" => "Syncopate",
									"ccf-googlewebfont-Tangerine" => "Tangerine",
									"ccf-googlewebfont-Taprom" => "Taprom",
									"ccf-googlewebfont-Tauri" => "Tauri",
									"ccf-googlewebfont-Teko" => "Teko",
									"ccf-googlewebfont-Telex" => "Telex",
									"ccf-googlewebfont-Tenor Sans" => "Tenor Sans",
									"ccf-googlewebfont-Text Me One" => "Text Me One",
									"ccf-googlewebfont-The Girl Next Door" => "The Girl Next Door",
									"ccf-googlewebfont-Tienne" => "Tienne",
									"ccf-googlewebfont-Tinos" => "Tinos",
									"ccf-googlewebfont-Titan One" => "Titan One",
									"ccf-googlewebfont-Titillium Web" => "Titillium Web",
									"ccf-googlewebfont-Trade Winds" => "Trade Winds",
									"ccf-googlewebfont-Trocchi" => "Trocchi",
									"ccf-googlewebfont-Trochut" => "Trochut",
									"ccf-googlewebfont-Trykker" => "Trykker",
									"ccf-googlewebfont-Tulpen One" => "Tulpen One",
									"ccf-googlewebfont-Ubuntu" => "Ubuntu",
									"ccf-googlewebfont-Ubuntu Condensed" => "Ubuntu Condensed",
									"ccf-googlewebfont-Ubuntu Mono" => "Ubuntu Mono",
									"ccf-googlewebfont-Ultra" => "Ultra",
									"ccf-googlewebfont-Uncial Antiqua" => "Uncial Antiqua",
									"ccf-googlewebfont-Underdog" => "Underdog",
									"ccf-googlewebfont-Unica One" => "Unica One",
									"ccf-googlewebfont-UnifrakturCook" => "UnifrakturCook",
									"ccf-googlewebfont-UnifrakturMaguntia" => "UnifrakturMaguntia",
									"ccf-googlewebfont-Unkempt" => "Unkempt",
									"ccf-googlewebfont-Unlock" => "Unlock",
									"ccf-googlewebfont-Unna" => "Unna",
									"ccf-googlewebfont-VT323" => "VT323",
									"ccf-googlewebfont-Vampiro One" => "Vampiro One",
									"ccf-googlewebfont-Varela" => "Varela",
									"ccf-googlewebfont-Varela Round" => "Varela Round",
									"ccf-googlewebfont-Vast Shadow" => "Vast Shadow",
									"ccf-googlewebfont-Vesper Libre" => "Vesper Libre",
									"ccf-googlewebfont-Vibur" => "Vibur",
									"ccf-googlewebfont-Vidaloka" => "Vidaloka",
									"ccf-googlewebfont-Viga" => "Viga",
									"ccf-googlewebfont-Voces" => "Voces",
									"ccf-googlewebfont-Volkhov" => "Volkhov",
									"ccf-googlewebfont-Vollkorn" => "Vollkorn",
									"ccf-googlewebfont-Voltaire" => "Voltaire",
									"ccf-googlewebfont-Waiting for the Sunrise" => "Waiting for the Sunrise",
									"ccf-googlewebfont-Wallpoet" => "Wallpoet",
									"ccf-googlewebfont-Walter Turncoat" => "Walter Turncoat",
									"ccf-googlewebfont-Warnes" => "Warnes",
									"ccf-googlewebfont-Wellfleet" => "Wellfleet",
									"ccf-googlewebfont-Wendy One" => "Wendy One",
									"ccf-googlewebfont-Wire One" => "Wire One",
									"ccf-googlewebfont-Yanone Kaffeesatz" => "Yanone Kaffeesatz",
									"ccf-googlewebfont-Yellowtail" => "Yellowtail",
									"ccf-googlewebfont-Yeseva One" => "Yeseva One",
									"ccf-googlewebfont-Yesteryear" => "Yesteryear",
									"ccf-googlewebfont-Zeyada" => "Zeyada"
        						)
	        				);

							$font_effects = array
	        							(
	        								"ccf_font_effect_none" => "None",
	        								"ccf_font_effect_emboss" => "Emboss",
	        								"ccf_font_effect_fire" => "Fire",
	        								"ccf_font_effect_fire_animation" => "Fire Animation",
	        								"ccf_font_effect_neon" => "Neon",
	        								"ccf_font_effect_outline" => "Outline",
	        								"ccf_font_effect_shadow_multiple" => "Shadow Multiple",
	        								"ccf_font_effect_3d" => "3D",
	        								"ccf_font_effect_3d_float" => "3D Float"
        								);
	        	
	        	//Main Box//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	        	seperate_tr("Main Box",'','0');
	        	create_accordion('Global Styles','closed');
	        		echo_color_tr('Font Color :','587',$styles[587]);
	        		echo_size_tr('Font Size:','588',$styles[588],'0','50');
		        	echo_select_tr_with_optgroups('Font Family','131',$fonts_array,$styles[131]);
	        		$global_icon_styles = array(
	        								"1" => "Style 1 (Black)",
	        								"2" => "Style 2 (White)"
        									);
	        		echo_select_tr('Icons Style','589',$global_icon_styles,$styles[589]);
	        		$crollbar_styles = array(
	        								"dark-thin" => "dark-thin",
	        								"light-thin" => "light-thin",
	        								"inset-dark" => "inset-dark",
	        								"inset" => "inset",
	        								"inset-2-dark" => "inset-2-dark",
	        								"inset-2" => "inset-2",
	        								"inset-3-dark" => "inset-3-dark",
	        								"inset-3" => "inset-3",
	        								"rounded-dark" => "rounded-dark",
	        								"rounded" => "rounded",
	        								"rounded-dots-dark" => "rounded-dots-dark",
	        								"rounded-dots" => "rounded-dots",
	        								"3d-dark" => "3d-dark",
	        								"3d" => "3d",
	        								"3d-thick-dark" => "3d-thick-dark",
	        								"3d-thick" => "3d-thick",
        									);
	        		echo_select_tr('Scrollbar Style (popup) <a href="http://creative-solutions.net/joomla/creative-contact-form/documentation?section=creative-scrollbar" target="_blank">See Demo</a>','629',$crollbar_styles,$styles[629]);
	        		echo_select_tr('Scrollbar Style (content) <a href="http://creative-solutions.net/joomla/creative-contact-form/documentation?section=creative-scrollbar" target="_blank">See Demo</a>','630',$crollbar_styles,$styles[630]);
	        	close_accordion();

	        	create_accordion('Wrapper Styles','closed');
		        	echo_select_tr('Use Background Gradient','627',array("0" => "No","1" => "Yes"),$styles[627]);
		        	echo_color_tr('Backround Color Start:','0',$styles[0]);
		        	echo_color_tr('Backround Color End:','130',$styles[130]);
		        	echo_size_perc_tr('Left Column Width:','517',$styles[517],'0','80');
		        	echo_size_perc_tr('Right Column Width:','518',$styles[518],'0','80');
		        	

		        	echo_color_tr('Border Color:','1',$styles[1]);
		        	echo_size_tr('Border Size:','2',$styles[2],'0','30');
		        	echo_select_tr('Border Style','3',array("solid" => "Solid", "dotted" => "Dotted","dashed" => "Dashed", "double" => "Double", "groove" => "Groove", "ridge" => "Ridge", "inset" => "Inset", "outset" => "Outset"),$styles[3]);
		        	echo_size_tr('Border Top Left Radius:','4',$styles[4],'0','80');
		        	echo_size_tr('Border Top Right Radius:','5',$styles[5],'0','80');
		        	echo_size_tr('Border Bottom Left Radius:','6',$styles[6],'0','80');
		        	echo_size_tr('Border Bottom Right Radius:','7',$styles[7],'0','80');
	        	close_accordion();
	        	
	        	create_accordion('Box Shadow','closed');
		        	echo_color_tr('Box Shadow Color:','8',$styles[8]);
		        	echo_select_tr('Box Shadow Type','9',array("" => "Default","inset" => "Inset"),$styles[9]);
		        	echo_size_tr('Box Shadow Horizontal Offset:','10',$styles[10],'-80','80');
		        	echo_size_tr('Box Shadow Vertical Offset:','11',$styles[11],'-80','80');
		        	echo_size_tr('Box Shadow Blur Radius:','12',$styles[12],'-120','120');
		        	echo_size_tr('Box Shadow Spread Radius:','13',$styles[13],'-120','120');
		        	echo_color_tr('Box Shadow Hover Color:','14',$styles[14]);
		        	echo_select_tr('Box Shadow Hover Type','15',array("" => "Default","inset" => "Inset"),$styles[15]);
		        	echo_size_tr('Box Shadow Hover Horizontal Offset:','16',$styles[16],'-80','80');
		        	echo_size_tr('Box Shadow Hover Vertical Offset:','17',$styles[17],'-80','80');
		        	echo_size_tr('Box Shadow Hover Blur Radius:','18',$styles[18],'-120','120');
		        	echo_size_tr('Box Shadow Hover Spread Radius:','19',$styles[19],'-120','120');
	        	close_accordion();
	        	
	        	create_accordion('Header Styles','closed','');
	        		echo_select_tr('Use Background','600',array("0" => "No","1" => "Yes"),$styles[600]);
		        	echo_color_tr('Backround Color Start:','601',$styles[601]);
		        	echo_color_tr('Backround Color End:','602',$styles[602]);
		        	
		        	echo_size_tr('Top Offset:','603',$styles[603],'0','300');
		        	echo_size_tr('Right Offset:','604',$styles[604],'0','300');
		        	echo_size_tr('Bottom Offset:','605',$styles[605],'0','300');
		        	echo_size_tr('Left Offset:','606',$styles[606],'0','300');

		        	echo_size_tr('Border Bottom Size:','607',$styles[607],'0','30');
		        	echo_select_tr('Border Bottom Style','608',array("solid" => "Solid", "dotted" => "Dotted","dashed" => "Dashed", "double" => "Double", "groove" => "Groove", "ridge" => "Ridge", "inset" => "Inset", "outset" => "Outset"),$styles[608]);
		        	echo_color_tr('Border Bottom Color:','609',$styles[609]);

	        	close_accordion();

	        	create_accordion('Body Styles','closed','');
	        		echo_select_tr('Use Background','610',array("0" => "No","1" => "Yes"),$styles[610]);
		        	echo_color_tr('Backround Color Start:','611',$styles[611]);
		        	echo_color_tr('Backround Color End:','612',$styles[612]);
		        	
		        	echo_size_tr('Top Offset:','613',$styles[613],'0','300');
		        	echo_size_tr('Right Offset:','614',$styles[614],'0','300');
		        	echo_size_tr('Bottom Offset:','615',$styles[615],'0','300');
		        	echo_size_tr('Left Offset:','616',$styles[616],'0','300');
	        	close_accordion();

	        	create_accordion('Footer Styles','closed','');
	        		echo_select_tr('Use Background','617',array("0" => "No","1" => "Yes"),$styles[617]);
		        	echo_color_tr('Backround Color Start:','618',$styles[618]);
		        	echo_color_tr('Backround Color End:','619',$styles[619]);
		        	
		        	echo_size_tr('Top Offset:','620',$styles[620],'0','300');
		        	echo_size_tr('Right Offset:','621',$styles[621],'0','300');
		        	echo_size_tr('Bottom Offset:','622',$styles[622],'0','300');
		        	echo_size_tr('Left Offset:','623',$styles[623],'0','300');

		        	echo_size_tr('Border Top Size:','624',$styles[624],'0','30');
		        	echo_select_tr('Border Top Style','625',array("solid" => "Solid", "dotted" => "Dotted","dashed" => "Dashed", "double" => "Double", "groove" => "Groove", "ridge" => "Ridge", "inset" => "Inset", "outset" => "Outset"),$styles[625]);
		        	echo_color_tr('Border Top Color:','626',$styles[626]);
	        	close_accordion();



	        	
	        	//Top Text////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	        	seperate_tr("Top text",'','1');
	        	create_accordion('Font Styles','closed');
	        		echo_color_tr('Font Color:','20',$styles[20]);
	        		echo_size_tr('Font Size:','21',$styles[21],'8','70');
	        		echo_select_tr('Font Weight','22',array("normal" => "Normal","bold" => "Bold"),$styles[22]);
	        		echo_select_tr('Font Style','23',array("normal" => "Normal","italic" => "Italic"),$styles[23]);
	        		echo_select_tr('Text Decoration','24',array("none" => "None","underline" => "Underline","overline" => "Overline","line-through"=>"Line Through"),$styles[24]);
	        		echo_select_tr('Text Align','25',array("left" => "Left","right" => "Right","center" => "Center"),$styles[25]);
	        		echo_select_tr_with_optgroups('Font Family','506',$fonts_array,$styles[506]);
	        		echo_select_tr('Font Effect','510',$font_effects,$styles[510]);
	        	close_accordion();
	        	create_accordion('Text Shadow','closed');
	        		echo_color_tr('Text Shadow Color:','27',$styles[27]);
	        		echo_size_tr('Text Shadow Horizontal Offset:','28',$styles[28],'-50','50');
	        		echo_size_tr('Text Shadow Vertical Offset:','29',$styles[29],'-50','50');
	        		echo_size_tr('Text Shadow Blur Radius:','30',$styles[30],'0','50');
	        	close_accordion();
	        	
	        	//pre text
	        	seperate_tr("Pre-text",'','2');
	        	create_accordion('Styles','closed','');
		        	echo_size_tr('Offset Top:','190',$styles[190],'-500','500');
		        	echo_size_tr('Offset Bottom:','191',$styles[191],'-500','500');
		        	echo_size_perc_tr('Width:','192',$styles[192],'0','100');
		        	echo_select_tr('Text Align','502',array("left" => "Left","right" => "Right","center" => "Center"),$styles[502]);
	        	close_accordion();
	        	create_accordion('Horizontal Line','closed');
		        	echo_size_tr('Horizontal Line Offset:','193',$styles[193],'-500','500');
		        	echo_size_tr('Horizontal Line Size:','194',$styles[194],'0','10');
		        	echo_color_tr('Horizontal Line Color:','195',$styles[195]);
		        	echo_select_tr('Horizontal Line Style','196',array("solid" => "Solid", "dotted" => "Dotted","dashed" => "Dashed", "double" => "Double", "groove" => "Groove", "ridge" => "Ridge", "inset" => "Inset", "outset" => "Outset"),$styles[196]);
	        	close_accordion();
	        	create_accordion('Font Styles','closed');
		        	echo_color_tr('Font Color:','197',$styles[197]);
		        	echo_size_tr('Font Size:','198',$styles[198],'8','70');
		        	echo_select_tr('Font Weight','199',array("normal" => "Normal","bold" => "Bold"),$styles[199]);
		        	echo_select_tr('Font Style','200',array("normal" => "Normal","italic" => "Italic"),$styles[200]);
		        	echo_select_tr('Text Decoration','201',array("none" => "None","underline" => "Underline","overline" => "Overline","line-through"=>"Line Through"),$styles[201]);
		        	echo_select_tr_with_optgroups('Font Family','202',$fonts_array,$styles[202]);
		        	echo_select_tr('Font Effect','511',$font_effects,$styles[511]);

	        	close_accordion();
	        	create_accordion('Text Shadow','closed');
		        	echo_color_tr('Text Shadow Color:','203',$styles[203]);
		        	echo_size_tr('Text Shadow Horizontal Offset:','204',$styles[204],'-50','50');
		        	echo_size_tr('Text Shadow Vertical Offset:','205',$styles[205],'-50','50');
		        	echo_size_tr('Text Shadow Blur Radius:','206',$styles[206],'0','50');
	        	close_accordion();
	        	
	        	//Label text////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	        	seperate_tr("Label text",'','3');
	        	create_accordion('Offsets','closed');
		        	echo_size_tr('Top Offset:','215',$styles[215],'-50','50');
		        	echo_size_tr('Right Offset:','216',$styles[216],'-50','50');
		        	echo_size_tr('Bottom Offset:','217',$styles[217],'-50','50');
		        	echo_size_tr('Left Offset:','218',$styles[218],'-50','50');
	        	close_accordion();
	        	create_accordion('Font Styles','closed');
	        		echo_color_tr('Font Color:','31',$styles[31]);
	        		echo_size_tr('Font Size:','32',$styles[32],'8','70');
	        		echo_select_tr('Font Weight','33',array("normal" => "Normal","bold" => "Bold"),$styles[33]);
	        		echo_select_tr('Font Style','34',array("normal" => "Normal","italic" => "Italic"),$styles[34]);
	        		echo_select_tr('Text Decoration','35',array("none" => "None","underline" => "Underline","overline" => "Overline","line-through"=>"Line Through"),$styles[35]);
	        		echo_select_tr('Text Align','36',array("left" => "Left","right" => "Right","center" => "Center"),$styles[36]);
	        		echo_select_tr_with_optgroups('Font Family','507',$fonts_array,$styles[507]);
	        		echo_select_tr('Font Effect','512',$font_effects,$styles[512]);


	        	close_accordion();
	        	create_accordion('Text Shadow','closed');
	        		echo_color_tr('Text Shadow Color:','37',$styles[37]);
	        		echo_size_tr('Text Shadow Horizontal Offset:','38',$styles[38],'-50','50');
	        		echo_size_tr('Text Shadow Vertical Offset:','39',$styles[39],'-50','50');
	        		echo_size_tr('Text Shadow Blur Radius:','40',$styles[40],'0','50');
	        	close_accordion();
	        	
	        	//Asterisk Styles////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	        	seperate_tr("Asterisk Styles",'','4');
	        	create_accordion('Font Styles','closed');
	        		echo_color_tr('Font Color:','41',$styles[41]);
	        		echo_size_tr('Font Size:','42',$styles[42],'8','70');
	        		echo_select_tr('Font Weight','43',array("normal" => "Normal","bold" => "Bold"),$styles[43]);
	        		echo_select_tr('Font Style','44',array("normal" => "Normal","italic" => "Italic"),$styles[44]);
	        		echo_select_tr_with_optgroups('Font Family','509',$fonts_array,$styles[509]);
	        	close_accordion();
	        	create_accordion('Text Shadow','closed');
	        		echo_color_tr('Text Shadow Color:','46',$styles[46]);
	        		echo_size_tr('Text Shadow Horizontal Offset:','47',$styles[47],'-50','50');
	        		echo_size_tr('Text Shadow Vertical Offset:','48',$styles[48],'-50','50');
	        		echo_size_tr('Text Shadow Blur Radius:','49',$styles[49],'0','50');
	        	close_accordion();
	        	
				//Tooltip Styles////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	        	seperate_tr("Tooltip Styles",'','12');
	        	create_accordion('Styles','closed');
	        		echo_select_tr('Tooltip Color','505',array("white"=>"White","apple-green" => "Apple Green","apricot" => "Apricot","black" => "Black","bright-lavender" => "Bright Lavender","carrot-orange" => "Carrot Orange","dark-midnight-blue" => "Dark Midnight Blue","eggplant" => "Eggplant","forest-green" => "Forest Green","magic-mint" => "Magic Mint","mustard" => "Mustard","sienna" => "Sienna","sky-blue" => "Sky Blue","sunset"=>"Sunset"),$styles[505]);
	        		echo_select_tr_with_optgroups('Font Family','508',$fonts_array,$styles[508]);
	        	close_accordion();

	        	//Input Elements /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	        	seperate_tr("Text Inputs",'','5');
	        	create_accordion('Styles','closed','Background Color, Paddings');
		        	echo_color_tr('Background Color Start:','132',$styles[132]);
		        	echo_color_tr('Background Color End:','133',$styles[133]);
		        	echo_size_perc_tr('Input Width:','168',$styles[168],'10','100');
		        	echo_size_perc_tr('Input Width(Left Column):','519',$styles[519],'10','100');
		        	echo_size_perc_tr('Input Width(Right Column):','520',$styles[520],'10','100');

		        	echo_select_tr('Text Align','500',array("left" => "Left","right" => "Right","center" => "Center"),$styles[500]);
		        	echo_select_tr('Box Align','501',array("left" => "Left","right" => "Right","center" => "Center"),$styles[501]);
	        	close_accordion();

	        	create_accordion('Border','closed');
		        	echo_color_tr('Border Color:','134',$styles[134]);
		        	echo_size_tr('Border Size:','135',$styles[135],'0','3');
		        	echo_select_tr('Border Style','136',array("solid" => "Solid", "dotted" => "Dotted","dashed" => "Dashed", "double" => "Double", "groove" => "Groove", "ridge" => "Ridge", "inset" => "Inset", "outset" => "Outset"),$styles[136]);
		        	echo_size_tr('Border Top Left Radius:','137',$styles[137],'0','80');
		        	echo_size_tr('Border Top Right Radius:','138',$styles[138],'0','80');
		        	echo_size_tr('Border Bottom Left Radius:','139',$styles[139],'0','80');
		        	echo_size_tr('Border Bottom Right Radius:','140',$styles[140],'0','80');
	        	close_accordion();
	        	
	        	create_accordion('Box Shadow','closed');
		        	echo_color_tr('Box Shadow Color:','141',$styles[141]);
		        	echo_select_tr('Box Shadow Type','142',array("" => "Default","inset" => "Inset"),$styles[142]);
		        	echo_size_tr('Box Shadow Horizontal Offset:','143',$styles[143],'-80','80');
		        	echo_size_tr('Box Shadow Vertical Offset:','144',$styles[144],'-80','80');
		        	echo_size_tr('Box Shadow Blur Radius:','145',$styles[145],'-120','120');
		        	echo_size_tr('Box Shadow Spread Radius:','146',$styles[146],'-120','120');
	        	close_accordion();
	        	
	        	create_accordion('Font Styles','closed');
		        	echo_color_tr('Font Color:','147',$styles[147]);
		        	echo_size_tr('Font Size:','148',$styles[148],'8','70');
		        	echo_select_tr('Font Weight','149',array("normal" => "Normal","bold" => "Bold"),$styles[149]);
		        	echo_select_tr('Font Style','150',array("normal" => "Normal","italic" => "Italic"),$styles[150]);
		        	echo_select_tr('Text Decoration','151',array("none" => "None","underline" => "Underline","overline" => "Overline","line-through"=>"Line Through"),$styles[151]);
		        	// echo_font_tr('Font Family','152',$styles[152]);
		        	echo_select_tr_with_optgroups('Font Family','152',$fonts_array,$styles[152]);
	        	close_accordion();
	        	create_accordion('Text Shadow','closed');
		        	echo_color_tr('Text Shadow Color:','153',$styles[153]);
		        	echo_size_tr('Text Shadow Horizontal Offset:','154',$styles[154],'-50','50');
		        	echo_size_tr('Text Shadow Vertical Offset:','155',$styles[155],'-50','50');
		        	echo_size_tr('Text Shadow Blur Radius:','156',$styles[156],'0','50');
	        	close_accordion();


	        	seperate_tr("Text Inputs Hover State",'','6');
	        	create_accordion('Styles','closed','Shadow, Background Color, Font Color');
	        		echo_color_tr('Background Color Start:','157',$styles[157]);
	        		echo_color_tr('Background Color End:','158',$styles[158]);
	        		echo_color_tr('Text Color:','159',$styles[159]);
	        		echo_color_tr('Text Shadow Color:','160',$styles[160]);
	        		echo_color_tr('Border Color:','161',$styles[161]);
		        	echo_color_tr('Box Shadow Color:','162',$styles[162]);
		        	echo_select_tr('Box Shadow Type','163',array("" => "Default","inset" => "Inset"),$styles[163]);
		        	echo_size_tr('Box Shadow Horizontal Offset:','164',$styles[164],'-80','80');
		        	echo_size_tr('Box Shadow Vertical Offset:','165',$styles[165],'-80','80');
		        	echo_size_tr('Box Shadow Blur Radius:','166',$styles[166],'-120','120');
		        	echo_size_tr('Box Shadow Spread Radius:','167',$styles[167],'-120','120');
	        	close_accordion();

	        	//Label text hover State/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	        	seperate_tr("Label Text Focus State",'','13');
	        	create_accordion('Font Styles','closed','');
		        	echo_select_tr('Font Effect','513',$font_effects,$styles[513]);
	        	close_accordion();
	        	
	        	//Error State/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	        	seperate_tr("Text Inputs Error State",'','7');
	        	
	        	create_accordion('Input Styles','closed','');
		        	echo_color_tr('Background Color Start:','176',$styles[176]);
		        	echo_color_tr('Background Color End:','177',$styles[177]);
		        	echo_color_tr('Border Color:','178',$styles[178]);
		        	echo_color_tr('Font Color:','179',$styles[179]);
	        	close_accordion();
	        	
	        	create_accordion('Text Shadow','closed');
		        	echo_color_tr('Text Shadow Color:','180',$styles[180]);
		        	echo_size_tr('Text Shadow Horizontal Offset:','181',$styles[181],'-50','50');
		        	echo_size_tr('Text Shadow Vertical Offset:','182',$styles[182],'-50','50');
		        	echo_size_tr('Text Shadow Blur Radius:','183',$styles[183],'0','50');
	        	close_accordion();
	        	
	        	create_accordion('Box Shadow','closed');
		        	echo_color_tr('Box Shadow Color:','184',$styles[184]);
		        	echo_select_tr('Box Shadow Type','185',array("" => "Default","inset" => "Inset"),$styles[185]);
		        	echo_size_tr('Box Shadow Horizontal Offset:','186',$styles[186],'-80','80');
		        	echo_size_tr('Box Shadow Vertical Offset:','187',$styles[187],'-80','80');
		        	echo_size_tr('Box Shadow Blur Radius:','188',$styles[188],'-120','120');
		        	echo_size_tr('Box Shadow Spread Radius:','189',$styles[189],'-120','120');
	        	close_accordion();

				//Label Text Error State/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	        	seperate_tr("Label Text Error State",'','14');

	        	create_accordion('Font Styles','closed');
		        	echo_color_tr('Font Color:','171',$styles[171]);
		        	echo_select_tr('Font Effect','514',$font_effects,$styles[514]);
		        	echo_color_tr('Text Shadow Color:','172',$styles[172]);
		        	echo_size_tr('Text Shadow Horizontal Offset:','173',$styles[173],'-50','50');
		        	echo_size_tr('Text Shadow Vertical Offset:','174',$styles[174],'-50','50');
		        	echo_size_tr('Text Shadow Blur Radius:','175',$styles[175],'0','50');
	        	close_accordion();

	        	//textarea button/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	        	seperate_tr("Textarea",'','11');
	        	create_accordion('Styles','closed','');
		        	echo_size_perc_tr('Textarea Width:','169',$styles[169],'10','100');
		        	echo_size_perc_tr('Textarea Width(Left Column):','521',$styles[521],'10','100');
		        	echo_size_perc_tr('Textarea Width(Right Column):','522',$styles[522],'10','100');
		        	echo_size_tr('Textarea Height:','170',$styles[170],'10','500');
		        	echo_size_tr('Textarea Height(Left, Right columns):','523',$styles[523],'10','500');
	        	close_accordion();
	        	
	        	//Heading text/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	        	seperate_tr("Heading",'','19');
	        	create_accordion('Styles','closed');
		        	echo_size_tr('Padding Top:','535',$styles[535],'-50','50');
		        	echo_size_tr('Padding Right:','536',$styles[536],'-50','50');
		        	echo_size_tr('Padding Bottom:','537',$styles[537],'-50','50');
		        	echo_size_tr('Padding Left:','538',$styles[538],'-50','50');

		        	echo_size_tr('Margin Top:','539',$styles[539],'-50','50');
		        	echo_size_tr('Margin Bottom:','540',$styles[540],'-50','50');

		        	echo_color_tr('Background Color Start:','541',$styles[541]);
		        	echo_color_tr('Background Color End:','542',$styles[542]);
	        	close_accordion();
	        	create_accordion('Border Styles','closed');
		        	echo_size_tr('Border Top Size:','543',$styles[543],'-50','50');
		        	echo_size_tr('Border Right Size:','544',$styles[544],'-50','50');
		        	echo_size_tr('Border Bottom Size:','545',$styles[545],'-50','50');
		        	echo_size_tr('Border Left Size:','546',$styles[546],'-50','50');

		        	echo_select_tr('Border Style','547',array("solid" => "Solid", "dotted" => "Dotted","dashed" => "Dashed", "double" => "Double", "groove" => "Groove", "ridge" => "Ridge", "inset" => "Inset", "outset" => "Outset"),$styles[547]);

		        	echo_color_tr('Border Top Color:','548',$styles[548]);
		        	echo_color_tr('Border Right Color:','549',$styles[549]);
		        	echo_color_tr('Border Bottom Color:','550',$styles[550]);
		        	echo_color_tr('Border Left Color:','551',$styles[551]);
	        	close_accordion();
	        	create_accordion('Font Styles','closed');
		        	echo_color_tr('Font Color:','524',$styles[524]);
		        	echo_size_tr('Font Size:','525',$styles[525],'8','70');
		        	echo_select_tr('Font Weight','526',array("normal" => "Normal","bold" => "Bold"),$styles[526]);
		        	echo_select_tr('Font Style','527',array("normal" => "Normal","italic" => "Italic"),$styles[527]);
		        	echo_select_tr('Text Decoration','528',array("none" => "None","underline" => "Underline","overline" => "Overline","line-through"=>"Line Through"),$styles[528]);
		        	echo_select_tr_with_optgroups('Font Family','529',$fonts_array,$styles[529]);
		        	echo_select_tr('Font Effect','530',$font_effects,$styles[530]);
	        	close_accordion();
	        	create_accordion('Text Shadow','closed');
		        	echo_color_tr('Text Shadow Color:','531',$styles[531]);
		        	echo_size_tr('Text Shadow Horizontal Offset:','532',$styles[532],'-50','50');
		        	echo_size_tr('Text Shadow Vertical Offset:','533',$styles[533],'-50','50');
		        	echo_size_tr('Text Shadow Blur Radius:','534',$styles[534],'0','50');
	        	close_accordion();

	        	//send button/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	        	seperate_tr("Send Button",'','9');
	        	create_accordion('Styles','closed','Background Color, Paddings');
		        	echo_color_tr('Background Color Start:','91',$styles[91]);
		        	echo_color_tr('Background Color End:','50',$styles[50]);
		        	echo_select_tr('Button Alignment','212',array("left" => "Left", "right" => "Right"),$styles[212]);
		        	echo_size_tr('Padding Top,Bottom:','92',$styles[92],'0','30');
		        	echo_size_tr('Padding Left,Right:','93',$styles[93],'0','500');
		        	echo_size_perc_tr('Wrapper Width:','209',$styles[209],'0','100');
	        	close_accordion();
	        	
	        	create_accordion('Border','closed');
		        	echo_color_tr('Border Color:','100',$styles[100]);
		        	echo_size_tr('Border Size:','101',$styles[101],'0','3');
		        	echo_select_tr('Border Style','127',array("solid" => "Solid", "dotted" => "Dotted","dashed" => "Dashed", "double" => "Double", "groove" => "Groove", "ridge" => "Ridge", "inset" => "Inset", "outset" => "Outset"),$styles[127]);
		        	echo_size_tr('Border Top Left Radius:','102',$styles[102],'0','80');
		        	echo_size_tr('Border Top Right Radius:','103',$styles[103],'0','80');
		        	echo_size_tr('Border Bottom Left Radius:','104',$styles[104],'0','80');
		        	echo_size_tr('Border Bottom Right Radius:','105',$styles[105],'0','80');
	        	close_accordion();
	        	
	        	create_accordion('Box Shadow','closed');
		        	echo_color_tr('Box Shadow Color:','94',$styles[94]);
		        	echo_select_tr('Box Shadow Type','95',array("" => "Default","inset" => "Inset"),$styles[95]);
		        	echo_size_tr('Box Shadow Horizontal Offset:','96',$styles[96],'-80','80');
		        	echo_size_tr('Box Shadow Vertical Offset:','97',$styles[97],'-80','80');
		        	echo_size_tr('Box Shadow Blur Radius:','98',$styles[98],'-120','120');
		        	echo_size_tr('Box Shadow Spread Radius:','99',$styles[99],'-120','120');
	        	close_accordion();
	        	
	        	create_accordion('Font Styles','closed');
		        	echo_color_tr('Font Color:','106',$styles[106]);
		        	echo_size_tr('Font Size:','107',$styles[107],'8','70');
		        	echo_select_tr('Font Weight','108',array("normal" => "Normal","bold" => "Bold"),$styles[108]);
		        	echo_select_tr('Font Style','109',array("normal" => "Normal","italic" => "Italic"),$styles[109]);
		        	echo_select_tr('Text Decoration','110',array("none" => "None","underline" => "Underline","overline" => "Overline","line-through"=>"Line Through"),$styles[110]);
		        	// echo_font_tr('Font Family','112',$styles[112]);
		        	echo_select_tr_with_optgroups('Font Family','112',$fonts_array,$styles[112]);
		        	echo_select_tr('Font Effect','515',$font_effects,$styles[515]);
	        	close_accordion();
	        	create_accordion('Text Shadow','closed');
		        	echo_color_tr('Text Shadow Color:','113',$styles[113]);
		        	echo_size_tr('Text Shadow Horizontal Offset:','114',$styles[114],'-50','50');
		        	echo_size_tr('Text Shadow Vertical Offset:','115',$styles[115],'-50','50');
		        	echo_size_tr('Text Shadow Blur Radius:','116',$styles[116],'0','50');
	        	close_accordion();

	        	seperate_tr("Send Button Hover State",'','10');
	        	create_accordion('Hover State','closed','Shadow, Background Color, Font Color');
		        	echo_color_tr('Background Color Start:','51',$styles[51]);
		        	echo_color_tr('Background Color End:','52',$styles[52]);
		        	echo_color_tr('Text Color:','124',$styles[124]);
		        	echo_select_tr('Font Effect','516',$font_effects,$styles[516]);
		        	echo_color_tr('Text Shadow Color:','125',$styles[125]);
		        	echo_color_tr('Border Color:','126',$styles[126]);
		        	echo_color_tr('Box Shadow Color:','117',$styles[117]);
		        	echo_select_tr('Box Shadow Type','118',array("" => "Default","inset" => "Inset"),$styles[118]);
		        	echo_size_tr('Box Shadow Horizontal Offset:','119',$styles[119],'-80','80');
		        	echo_size_tr('Box Shadow Vertical Offset:','120',$styles[120],'-80','80');
		        	echo_size_tr('Box Shadow Blur Radius:','121',$styles[121],'-120','120');
		        	echo_size_tr('Box Shadow Spread Radius:','122',$styles[122],'-120','120');
	        	close_accordion();

	        	seperate_tr("Sections Styles (Icons View)",'','20');
	        	create_accordion('Icons','closed','');
	        		echo_select_tr('Icon Template (no preview)','552',array("1" => "Template 1","2" => "Template 2","3" => "Template 3","4" => "Template 4"),$styles[552]);
	        	close_accordion();	        	
	        	create_accordion('Label Styles','closed','');
	        		echo_color_tr('Font Color:','553',$styles[553]);
		        	echo_size_tr('Font Size:','554',$styles[554],'8','70');
		        	echo_select_tr('Font Weight','555',array("normal" => "Normal","bold" => "Bold"),$styles[555]);
		        	echo_select_tr('Font Style','556',array("normal" => "Normal","italic" => "Italic"),$styles[556]);
		        	echo_select_tr('Text Decoration','596',array("none" => "None","underline" => "Underline","overline" => "Overline","line-through"=>"Line Through"),$styles[596]);
		        	echo_size_tr('Border Bottom Size:','590',$styles[590],'0','3');
		        	echo_select_tr('Border Bottom Style','591',array("solid" => "Solid", "dotted" => "Dotted","dashed" => "Dashed", "double" => "Double", "groove" => "Groove", "ridge" => "Ridge", "inset" => "Inset", "outset" => "Outset"),$styles[591]);
		        	echo_color_tr('Border Bottom Color:','592',$styles[592]);

		        	echo_color_tr('Text Shadow Color:','558',$styles[558]);
		        	echo_size_tr('Text Shadow Horizontal Offset:','559',$styles[559],'-50','50');
		        	echo_size_tr('Text Shadow Vertical Offset:','560',$styles[560],'-50','50');
		        	echo_size_tr('Text Shadow Blur Radius:','561',$styles[561],'0','50');
	        	close_accordion();

	        	seperate_tr("Datepicker Styles (Icons View)",'','21');
	        	create_accordion('Styles','closed','');
	        		$datepicker_icon_styles = array(
	        								"1" => "Style 1 (Blue-Black)",
	        								"2" => "Style 2 (Blue-Black)",
	        								"3" => "Style 3 (Black)",
	        								"4" => "Style 4 (Black)",
	        								"5" => "Style 5 (Black)",
	        								"6" => "Style 6 (Black)",
	        								"7" => "Style 7 (Grey)",
	        								"8" => "Style 8 (Blue)",
	        								"9" => "Style 9 (Blue)",
	        								"10" => "Style 10 (Blue)",
	        								"11" => "Style 11 (Blue)",
	        								"12" => "Style 12 (Blue)",
	        								"13" => "Style 13 (Blue)",
	        								"14" => "Style 14 (Blue)",
	        								"15" => "Style 15 (Blue)",
	        								"16" => "Style 16 (Blue)",
	        								"17" => "Style 17 (Blue)",
	        								"18" => "Style 18 (Blue)",
	        								"19" => "Style 19 (Red)",
	        								"20" => "Style 20 (Red)",
	        								"21" => "Style 21 (Red)",
	        								"22" => "Style 22 (Red)",
	        								"23" => "Style 23 (Red)",
	        								"24" => "Style 24 (Red)",
	        								"25" => "Style 25 (Green)",
	        								"26" => "Style 26 (Green)",
	        								"27" => "Style 27 (Green)",
	        								"28" => "Style 28 (Orange)",
	        								"29" => "Style 29 (Orange)",
	        								"30" => "Style 30 (Orange)"
        									);
	        		echo_select_tr('Datepicker Icon','563',$datepicker_icon_styles,$styles[563]);

	        		$datepicker_styles = array(
	        								"1" => "Style 1 (Grey)",
	        								"2" => "Style 2 (Black)",
	        								"3" => "Style 3 (Melon)",
	        								"4" => "Style 4 (Red)",
	        								"5" => "Style 5 (Lite Green)",
	        								"6" => "Style 6 (Dark Red)",
	        								"7" => "Style 7 (Lite Blue)",
	        								"8" => "Style 8 (Grey - Green buttons)"
        									);
	        		echo_select_tr('Datepicker Style (no preview)','562',$datepicker_styles,$styles[562]);
	        	close_accordion();

	        	seperate_tr("File Upload Button Styles (Icons View)",'','25');
	        	create_accordion('Paddings','closed','');
		        	echo_size_tr('Padding Top, Bottom:','597',$styles[597],'0','200');
		        	echo_size_tr('Padding Left, Right:','598',$styles[598],'0','200');
	        	close_accordion();

	        	seperate_tr("Link Styles (Icons View)",'','22');
	        	create_accordion('Styles','closed','');
	        		echo_color_tr('Font Color:','564',$styles[564]);
		        	echo_select_tr('Font Weight','565',array("normal" => "Normal","bold" => "Bold"),$styles[565]);
		        	echo_select_tr('Font Style','566',array("normal" => "Normal","italic" => "Italic"),$styles[566]);
		        	echo_select_tr('Text Decoration','594',array("none" => "None","underline" => "Underline","overline" => "Overline","line-through"=>"Line Through"),$styles[594]);
		        	echo_size_tr('Border Bottom Size:','567',$styles[567],'0','3');
		        	echo_select_tr('Border Bottom Style','568',array("solid" => "Solid", "dotted" => "Dotted","dashed" => "Dashed", "double" => "Double", "groove" => "Groove", "ridge" => "Ridge", "inset" => "Inset", "outset" => "Outset"),$styles[568]);
		        	echo_color_tr('Border Bottom Color:','569',$styles[569]);

	        		echo_color_tr('Text Shadow Color:','570',$styles[570]);
		        	echo_size_tr('Text Shadow Horizontal Offset:','571',$styles[571],'-50','50');
		        	echo_size_tr('Text Shadow Vertical Offset:','572',$styles[572],'-50','50');
		        	echo_size_tr('Text Shadow Blur Radius:','573',$styles[573],'0','50');
	        	close_accordion();


	        	seperate_tr("Link Styles Hover State (Icons View)",'','23');
	        	create_accordion('Styles','closed','');
	        		echo_color_tr('Font Color:','574',$styles[574]);
	        		echo_select_tr('Text Decoration','595',array("none" => "None","underline" => "Underline","overline" => "Overline","line-through"=>"Line Through"),$styles[595]);
		        	echo_color_tr('Border Bottom Color:','575',$styles[575]);

	        		echo_color_tr('Text Shadow Color:','576',$styles[576]);
		        	echo_size_tr('Text Shadow Horizontal Offset:','577',$styles[577],'-50','50');
		        	echo_size_tr('Text Shadow Vertical Offset:','578',$styles[578],'-50','50');
		        	echo_size_tr('Text Shadow Blur Radius:','579',$styles[579],'0','50');
	        	close_accordion();

	        	seperate_tr("Number Styling Styles (Icons View)",'','24');
	        	create_accordion('Styles','closed','');
	        		echo_color_tr('Font Color:','580',$styles[580]);
		        	echo_select_tr('Font Weight','581',array("normal" => "Normal","bold" => "Bold"),$styles[581]);
		        	echo_select_tr('Font Style','582',array("normal" => "Normal","italic" => "Italic"),$styles[582]);
		        	echo_select_tr('Text Decoration','593',array("none" => "None","underline" => "Underline","overline" => "Overline","line-through"=>"Line Through"),$styles[593]);

	        		echo_color_tr('Text Shadow Color:','583',$styles[583]);
		        	echo_size_tr('Text Shadow Horizontal Offset:','584',$styles[584],'-50','50');
		        	echo_size_tr('Text Shadow Vertical Offset:','585',$styles[585],'-50','50');
		        	echo_size_tr('Text Shadow Blur Radius:','586',$styles[586],'0','50');
	        	close_accordion();

	        	seperate_tr("Custom Rules",'','26');
	        	create_accordion('CSS','closed','');
	        	echo_textarea_tr('Custom Styles:','599',$styles[599],'<div style="font-size: 12px;color: #777;">Note: As images path use <span style="color: rgb(3, 67, 166);font-style: italic;">ccf_img_path</span>, which will load images<br />from following directory: <span style="color: rgb(3, 67, 166);font-style: italic;">ROOT/components/<br />com_creativecontactform/assets/images/bg_images</span></div>');
	        	close_accordion();
	        	create_accordion('JavaScript','closed','');
	        	echo_textarea_tr('Custom Styles:','628',$styles[628],'<div style="font-size: 12px;color: #777;">Note: jQuery is allowed! It inserts scrip in document.ready...</div>');
	        	close_accordion();
	        ?>
	    </table>
	  </div>
    </fieldset>
</div>
 
<div class="clr"></div>
 
<input type="hidden" name="option" value="com_creativecontactform" />
<input type="hidden" name="id" value="<?php echo $this->item->id; ?>" />
<input type="hidden" name="task" value="template.edit" />
<?php echo JHtml::_('form.token'); ?>
</form>

<style>

/*// */
.creativecontactform_wrapper {
	border: <?php echo $styles[2];?>px <?php echo $styles[3];?> <?php echo $styles[1];?>;
	background-color: <?php echo $styles[0];?>;

	<?php if($styles[627] == '1') {?>
	filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='<?php echo $styles[0];?>', endColorstr='<?php echo $styles[130];?>'); /* for IE */
	background: -webkit-gradient(linear, 0% 0%, 0% 100%, from(<?php echo $styles[0];?>), to(<?php echo $styles[130];?>));/* Safari 4-5, Chrome 1-9 */
	background: -webkit-linear-gradient(top, <?php echo $styles[0];?>, <?php echo $styles[130];?>); /* Safari 5.1, Chrome 10+ */
	background: -moz-linear-gradient(top, <?php echo $styles[0];?>, <?php echo $styles[130];?>);/* Firefox 3.6+ */
	background: -ms-linear-gradient(top, <?php echo $styles[0];?>, <?php echo $styles[130];?>);/* IE 10 */
	background: -o-linear-gradient(top, <?php echo $styles[0];?>, <?php echo $styles[130];?>);/* Opera 11.10+ */
	<?php }?>
	
	-moz-box-shadow: <?php echo $styles[9];?> <?php echo $styles[10];?>px <?php echo $styles[11];?>px <?php echo $styles[12];?>px <?php echo $styles[13];?>px  <?php echo $styles[8];?>;
	-webkit-box-shadow: <?php echo $styles[9];?> <?php echo $styles[10];?>px <?php echo $styles[11];?>px <?php echo $styles[12];?>px <?php echo $styles[13];?>px  <?php echo $styles[8];?>;
	box-shadow: <?php echo $styles[9];?> <?php echo $styles[10];?>px <?php echo $styles[11];?>px <?php echo $styles[12];?>px <?php echo $styles[13];?>px  <?php echo $styles[8];?>;
	
	-webkit-border-top-left-radius: <?php echo $styles[4];?>px;
	-moz-border-radius-topleft: <?php echo $styles[4];?>px;
	border-top-left-radius: <?php echo $styles[4];?>px;
	
	-webkit-border-top-right-radius: <?php echo $styles[5];?>px;
	-moz-border-radius-topright: <?php echo $styles[5];?>px;
	border-top-right-radius: <?php echo $styles[5];?>px;
	
	-webkit-border-bottom-left-radius: <?php echo $styles[6];?>px;
	-moz-border-radius-bottomleft: <?php echo $styles[6];?>px;
	border-bottom-left-radius: <?php echo $styles[6];?>px;
	
	-webkit-border-bottom-right-radius: <?php echo $styles[7];?>px;
	-moz-border-radius-bottomright: <?php echo $styles[7];?>px;
	border-bottom-right-radius: <?php echo $styles[7];?>px;
	<?php 

		$ccf_googlefont = 'ccf-googlewebfont-';
		$ccf_font_rule = $styles[131];
		if (strpos($ccf_font_rule,$ccf_googlefont) !== false) {
			$ccf_font_rule = str_replace($ccf_googlefont, '', $ccf_font_rule);
			$ccf_font_rule .= ', sans-serif';
		}
	?>
	font-family: <?php echo $ccf_font_rule;?>;

	color: <?php echo $styles[587]?>;
	font-size: <?php echo $styles[588]?>px;
}
.creativecontactform_wrapper:hover {
	-moz-box-shadow: <?php echo $styles[15];?> <?php echo $styles[16];?>px <?php echo $styles[17];?>px <?php echo $styles[18];?>px <?php echo $styles[19];?>px  <?php echo $styles[14];?>;
	-webkit-box-shadow: <?php echo $styles[15];?> <?php echo $styles[16];?>px <?php echo $styles[17];?>px <?php echo $styles[18];?>px <?php echo $styles[19];?>px  <?php echo $styles[14];?>;
	box-shadow: <?php echo $styles[15];?> <?php echo $styles[16];?>px <?php echo $styles[17];?>px <?php echo $styles[18];?>px <?php echo $styles[19];?>px  <?php echo $styles[14];?>;
}
.creativecontactform_header {
	-webkit-border-top-left-radius: <?php echo $styles[4];?>px;
	-moz-border-radius-topleft: <?php echo $styles[4];?>px;
	border-top-left-radius: <?php echo $styles[4];?>px;
	
	-webkit-border-top-right-radius: <?php echo $styles[5];?>px;
	-moz-border-radius-topright: <?php echo $styles[5];?>px;
	border-top-right-radius: <?php echo $styles[5];?>px;
}
.creativecontactform_footer {
	-webkit-border-bottom-left-radius: <?php echo $styles[6];?>px;
	-moz-border-radius-bottomleft: <?php echo $styles[6];?>px;
	border-bottom-left-radius: <?php echo $styles[6];?>px;
	
	-webkit-border-bottom-right-radius: <?php echo $styles[7];?>px;
	-moz-border-radius-bottomright: <?php echo $styles[7];?>px;
	border-bottom-right-radius: <?php echo $styles[7];?>px;
}

.creativecontactform_header {
	padding:  <?php echo $styles[603];?>px  <?php echo $styles[604];?>px <?php echo $styles[605];?>px <?php echo $styles[606];?>px;
	border-bottom: <?php echo $styles[607];?>px <?php echo $styles[608];?> <?php echo $styles[609];?>;

	<?php if($styles[600] == '1') {?>
	background-color: <?php echo $styles[601];?>;
	filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='<?php echo $styles[601];?>', endColorstr='<?php echo $styles[602];?>'); /* for IE */
	background: -webkit-gradient(linear, 0% 0%, 0% 100%, from(<?php echo $styles[601];?>), to(<?php echo $styles[602];?>));/* Safari 4-5, Chrome 1-9 */
	background: -webkit-linear-gradient(top, <?php echo $styles[601];?>, <?php echo $styles[602];?>); /* Safari 5.1, Chrome 10+ */
	background: -moz-linear-gradient(top, <?php echo $styles[601];?>, <?php echo $styles[602];?>);/* Firefox 3.6+ */
	background: -ms-linear-gradient(top, <?php echo $styles[601];?>, <?php echo $styles[602];?>);/* IE 10 */
	background: -o-linear-gradient(top, <?php echo $styles[601];?>, <?php echo $styles[602];?>);/* Opera 11.10+ */
	<?php }?>

}
.creativecontactform_body {
	padding:  <?php echo $styles[613];?>px  <?php echo $styles[614];?>px <?php echo $styles[615];?>px <?php echo $styles[616];?>px;

	<?php if($styles[610] == '1') {?>
	background-color: <?php echo $styles[611];?>;
	filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='<?php echo $styles[611];?>', endColorstr='<?php echo $styles[612];?>'); /* for IE */
	background: -webkit-gradient(linear, 0% 0%, 0% 100%, from(<?php echo $styles[611];?>), to(<?php echo $styles[612];?>));/* Safari 4-5, Chrome 1-9 */
	background: -webkit-linear-gradient(top, <?php echo $styles[611];?>, <?php echo $styles[612];?>); /* Safari 5.1, Chrome 10+ */
	background: -moz-linear-gradient(top, <?php echo $styles[611];?>, <?php echo $styles[612];?>);/* Firefox 3.6+ */
	background: -ms-linear-gradient(top, <?php echo $styles[611];?>, <?php echo $styles[612];?>);/* IE 10 */
	background: -o-linear-gradient(top, <?php echo $styles[611];?>, <?php echo $styles[612];?>);/* Opera 11.10+ */
	<?php }?>
}
.creativecontactform_footer {
	padding:  <?php echo $styles[620];?>px  <?php echo $styles[621];?>px <?php echo $styles[622];?>px <?php echo $styles[623];?>px;
	border-top: <?php echo $styles[624];?>px <?php echo $styles[625];?> <?php echo $styles[626];?>;

	<?php if($styles[617] == '1') {?>
	background-color: <?php echo $styles[618];?>;
	filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='<?php echo $styles[618];?>', endColorstr='<?php echo $styles[619];?>'); /* for IE */
	background: -webkit-gradient(linear, 0% 0%, 0% 100%, from(<?php echo $styles[618];?>), to(<?php echo $styles[619];?>));/* Safari 4-5, Chrome 1-9 */
	background: -webkit-linear-gradient(top, <?php echo $styles[618];?>, <?php echo $styles[619];?>); /* Safari 5.1, Chrome 10+ */
	background: -moz-linear-gradient(top, <?php echo $styles[618];?>, <?php echo $styles[619];?>);/* Firefox 3.6+ */
	background: -ms-linear-gradient(top, <?php echo $styles[618];?>, <?php echo $styles[619];?>);/* IE 10 */
	background: -o-linear-gradient(top, <?php echo $styles[618];?>, <?php echo $styles[619];?>);/* Opera 11.10+ */
	<?php }?>
}



.creativecontactform_title {
	color: <?php echo $styles[20];?>;
	font-size: <?php echo $styles[21];?>px;
	font-style: <?php echo $styles[23];?>;
	font-weight: <?php echo $styles[22];?>;
	text-align: <?php echo $styles[25];?>;
	text-decoration: <?php echo $styles[24];?>;
	text-shadow: <?php echo $styles[28];?>px <?php echo $styles[29];?>px <?php echo $styles[30];?>px <?php echo $styles[27];?>;
	<?php 

		$ccf_googlefont = 'ccf-googlewebfont-';
		$ccf_font_rule = $styles[506];
		if (strpos($ccf_font_rule,$ccf_googlefont) !== false) {
			$ccf_font_rule = str_replace($ccf_googlefont, '', $ccf_font_rule);
			$ccf_font_rule .= ', sans-serif';
		}
	?>
	font-family: <?php echo $ccf_font_rule;?>
}

.creativecontactform_field_name {
	color: <?php echo $styles[31];?>;
	font-size: <?php echo $styles[32];?>px;
	font-style: <?php echo $styles[34];?>;
	font-weight: <?php echo $styles[33];?>;
	text-align: <?php echo $styles[36];?>;
	text-decoration: <?php echo $styles[35];?>;
	text-shadow: <?php echo $styles[38];?>px <?php echo $styles[39];?>px <?php echo $styles[40];?>px <?php echo $styles[37];?>;
	margin:  <?php echo $styles[215];?>px  <?php echo $styles[216];?>px <?php echo $styles[217];?>px <?php echo $styles[218];?>px;
	<?php 

		$ccf_googlefont = 'ccf-googlewebfont-';
		$ccf_font_rule = $styles[507];
		if (strpos($ccf_font_rule,$ccf_googlefont) !== false) {
			$ccf_font_rule = str_replace($ccf_googlefont, '', $ccf_font_rule);
			$ccf_font_rule .= ', sans-serif';
		}
	?>
	font-family: <?php echo $ccf_font_rule;?>
}

.creativecontactform_field_required {
	color: <?php echo $styles[41];?>;
	font-size: <?php echo $styles[42];?>px;
	font-style: <?php echo $styles[44];?>;
	font-weight: <?php echo $styles[43];?>;
	text-shadow: <?php echo $styles[47];?>px <?php echo $styles[48];?>px <?php echo $styles[49];?>px <?php echo $styles[46];?>;
}
.ccf_button_holder {
	float: <?php echo $styles[212];?>;
}

.creativecontactform_send {
	background-color: <?php echo $styles[91];?>;
	filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='<?php echo $styles[91];?>', endColorstr='<?php echo $styles[50];?>'); /* for IE */
	background: -webkit-gradient(linear, 0% 0%, 0% 100%, from(<?php echo $styles[91];?>), to(<?php echo $styles[50];?>));/* Safari 4-5, Chrome 1-9 */
	background: -webkit-linear-gradient(top, <?php echo $styles[91];?>, <?php echo $styles[50];?>); /* Safari 5.1, Chrome 10+ */
	background: -moz-linear-gradient(top, <?php echo $styles[91];?>, <?php echo $styles[50];?>);/* Firefox 3.6+ */
	background: -ms-linear-gradient(top, <?php echo $styles[91];?>, <?php echo $styles[50];?>);/* IE 10 */
	background: -o-linear-gradient(top, <?php echo $styles[91];?>, <?php echo $styles[50];?>);/* Opera 11.10+ */
	
	padding: <?php echo $styles[92];?>px <?php echo $styles[93];?>px;
	-moz-box-shadow: <?php echo $styles[95];?> <?php echo $styles[96];?>px <?php echo $styles[97];?>px <?php echo $styles[98];?>px <?php echo $styles[99];?>px  <?php echo $styles[94];?>;	
	-webkit-box-shadow: <?php echo $styles[95];?> <?php echo $styles[96];?>px <?php echo $styles[97];?>px <?php echo $styles[98];?>px <?php echo $styles[99];?>px  <?php echo $styles[94];?>;	
	box-shadow: <?php echo $styles[95];?> <?php echo $styles[96];?>px <?php echo $styles[97];?>px <?php echo $styles[98];?>px <?php echo $styles[99];?>px  <?php echo $styles[94];?>;	
	border-style: <?php echo $styles[127];?>;
	border-width: <?php echo $styles[101];?>px;
	border-color: <?php echo $styles[100];?>;
	
	-webkit-border-top-left-radius: <?php echo $styles[102];?>px;
	-moz-border-radius-topleft: <?php echo $styles[102];?>px;
	border-top-left-radius: <?php echo $styles[102];?>px;
	
	-webkit-border-top-right-radius: <?php echo $styles[103];?>px;
	-moz-border-radius-topright: <?php echo $styles[103];?>px;
	border-top-right-radius: <?php echo $styles[103];?>px;
	
	-webkit-border-bottom-left-radius: <?php echo $styles[104];?>px;
	-moz-border-radius-bottomleft: <?php echo $styles[104];?>px;
	border-bottom-left-radius: <?php echo $styles[104];?>px;
	
	-webkit-border-bottom-right-radius: <?php echo $styles[105];?>px;
	-moz-border-radius-bottomright: <?php echo $styles[105];?>px;
	border-bottom-right-radius: <?php echo $styles[105];?>px;
	float: <?php echo $styles[212];?>;

	font-size: <?php echo $styles[107];?>px;
	color: <?php echo $styles[106];?>;
	font-style: <?php echo $styles[109];?>;
	font-weight: <?php echo $styles[108];?>;
	text-decoration: <?php echo $styles[110];?>;
	text-shadow: <?php echo $styles[114];?>px <?php echo $styles[115];?>px <?php echo $styles[116];?>px <?php echo $styles[113];?>;
	<?php 

		$ccf_googlefont = 'ccf-googlewebfont-';
		$ccf_font_rule = $styles[112];
		if (strpos($ccf_font_rule,$ccf_googlefont) !== false) {
			$ccf_font_rule = str_replace($ccf_googlefont, '', $ccf_font_rule);
			$ccf_font_rule .= ', sans-serif';
		}
	?>
	font-family: <?php echo $ccf_font_rule;?>

}
.creativecontactform_send_hovered {
	background-color: <?php echo $styles[51];?>;
	filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='<?php echo $styles[51];?>', endColorstr='<?php echo $styles[52];?>'); /* for IE */
	background: -webkit-gradient(linear, 0% 0%, 0% 100%, from(<?php echo $styles[51];?>), to(<?php echo $styles[52];?>));/* Safari 4-5, Chrome 1-9 */
	background: -webkit-linear-gradient(top, <?php echo $styles[51];?>, <?php echo $styles[52];?>); /* Safari 5.1, Chrome 10+ */
	background: -moz-linear-gradient(top, <?php echo $styles[51];?>, <?php echo $styles[52];?>);/* Firefox 3.6+ */
	background: -ms-linear-gradient(top, <?php echo $styles[51];?>, <?php echo $styles[52];?>);/* IE 10 */
	background: -o-linear-gradient(top, <?php echo $styles[51];?>, <?php echo $styles[52];?>);/* Opera 11.10+ */
	
	color: <?php echo $styles[124];?>;
	text-shadow: <?php echo $styles[114];?>px <?php echo $styles[115];?>px <?php echo $styles[116];?>px <?php echo $styles[125];?>;
	-moz-box-shadow: <?php echo $styles[118];?> <?php echo $styles[119];?>px <?php echo $styles[120];?>px <?php echo $styles[121];?>px <?php echo $styles[122];?>px  <?php echo $styles[117];?>;
	-webkit-box-shadow: <?php echo $styles[118];?> <?php echo $styles[119];?>px <?php echo $styles[120];?>px <?php echo $styles[121];?>px <?php echo $styles[122];?>px  <?php echo $styles[117];?>;
	box-shadow: <?php echo $styles[118];?> <?php echo $styles[119];?>px <?php echo $styles[120];?>px <?php echo $styles[121];?>px <?php echo $styles[122];?>px  <?php echo $styles[117];?>;
	border-style: <?php echo $styles[127];?>;
	border-width: <?php echo $styles[101];?>px;
	border-color: <?php echo $styles[126];?>;
}
.creative_fileupload {
	padding: <?php echo $styles[597];?>px <?php echo $styles[598];?>px;
}
		        	
.creativecontactform_submit_wrapper {
	width: 	<?php echo $styles[209];?>%;
}


.creativecontactform_field_box_inner {
	width:<?php echo $styles[168];?>%;
	<?php $box_margin = $styles[501] == 'right' ? '0 0 0 auto' : ($styles[501] == 'center' ? '0 auto' : '0');  ?>
	margin: <?php echo $box_margin;?>;
}
.creativecontactform_input_element {
	
	background-color: <?php echo $styles[132];?>;
	filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='<?php echo $styles[132];?>', endColorstr='<?php echo $styles[133];?>'); /* for IE */
	background: -webkit-gradient(linear, 0% 0%, 0% 100%, from(<?php echo $styles[132];?>), to(<?php echo $styles[133];?>));/* Safari 4-5, Chrome 1-9 */
	background: -webkit-linear-gradient(top, <?php echo $styles[132];?>, <?php echo $styles[133];?>); /* Safari 5.1, Chrome 10+ */
	background: -moz-linear-gradient(top, <?php echo $styles[132];?>, <?php echo $styles[133];?>);/* Firefox 3.6+ */
	background: -ms-linear-gradient(top, <?php echo $styles[132];?>, <?php echo $styles[133];?>);/* IE 10 */
	background: -o-linear-gradient(top, <?php echo $styles[132];?>, <?php echo $styles[133];?>);/* Opera 11.10+ */
	
	-moz-box-shadow: <?php echo $styles[142];?> <?php echo $styles[143];?>px <?php echo $styles[144];?>px <?php echo $styles[145];?>px <?php echo $styles[146];?>px  <?php echo $styles[141];?>;	
	-webkit-box-shadow: <?php echo $styles[142];?> <?php echo $styles[143];?>px <?php echo $styles[144];?>px <?php echo $styles[145];?>px <?php echo $styles[146];?>px  <?php echo $styles[141];?>;		
	box-shadow: <?php echo $styles[142];?> <?php echo $styles[143];?>px <?php echo $styles[144];?>px <?php echo $styles[145];?>px <?php echo $styles[146];?>px  <?php echo $styles[141];?>;		
	border-style: <?php echo $styles[136];?>;
	border-width: <?php echo $styles[135];?>px;
	border-color: <?php echo $styles[134];?>;
	
	-webkit-border-top-left-radius: <?php echo $styles[137];?>px;
	-moz-border-radius-topleft: <?php echo $styles[137];?>px;
	border-top-left-radius: <?php echo $styles[137];?>px;
	
	-webkit-border-top-right-radius: <?php echo $styles[138];?>px;
	-moz-border-radius-topright: <?php echo $styles[138];?>px;
	border-top-right-radius: <?php echo $styles[138];?>px;
	
	-webkit-border-bottom-left-radius: <?php echo $styles[139];?>px;
	-moz-border-radius-bottomleft: <?php echo $styles[139];?>px;
	border-bottom-left-radius: <?php echo $styles[139];?>px;
	
	-webkit-border-bottom-right-radius: <?php echo $styles[140];?>px;
	-moz-border-radius-bottomright: <?php echo $styles[140];?>px;
	border-bottom-right-radius: <?php echo $styles[140];?>px;

}
.creativecontactform_input_element input,.creativecontactform_input_element textarea{
	font-size: <?php echo $styles[148];?>px;
	color: <?php echo $styles[147];?>;
	font-style: <?php echo $styles[150];?>;
	font-weight: <?php echo $styles[149];?>;
	text-decoration: <?php echo $styles[151];?>;
	text-shadow: <?php echo $styles[154];?>px <?php echo $styles[155];?>px <?php echo $styles[156];?>px <?php echo $styles[153];?>;
	text-align: <?php echo $styles[500];?>;
	<?php 

		$ccf_googlefont = 'ccf-googlewebfont-';
		$ccf_font_rule = $styles[152];
		if (strpos($ccf_font_rule,$ccf_googlefont) !== false) {
			$ccf_font_rule = str_replace($ccf_googlefont, '', $ccf_font_rule);
			$ccf_font_rule .= ', sans-serif';
		}
	?>
	font-family: <?php echo $ccf_font_rule;?>
}

/*.creativecontactform_input_element:hover,.creativecontactform_input_element:focus,.creativecontactform_input_element.focused {*/
.creativecontactform_input_element_hovered {
	background-color: <?php echo $styles[157];?>;
	filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='<?php echo $styles[157];?>', endColorstr='<?php echo $styles[158];?>'); /* for IE */
	background: -webkit-gradient(linear, 0% 0%, 0% 100%, from(<?php echo $styles[157];?>), to(<?php echo $styles[158];?>));/* Safari 4-5, Chrome 1-9 */
	background: -webkit-linear-gradient(top, <?php echo $styles[157];?>, <?php echo $styles[158];?>); /* Safari 5.1, Chrome 10+ */
	background: -moz-linear-gradient(top, <?php echo $styles[157];?>, <?php echo $styles[158];?>);/* Firefox 3.6+ */
	background: -ms-linear-gradient(top, <?php echo $styles[157];?>, <?php echo $styles[158];?>);/* IE 10 */
	background: -o-linear-gradient(top, <?php echo $styles[157];?>, <?php echo $styles[158];?>);/* Opera 11.10+ */
	
	-moz-box-shadow: <?php echo $styles[163];?> <?php echo $styles[164];?>px <?php echo $styles[165];?>px <?php echo $styles[166];?>px <?php echo $styles[167];?>px  <?php echo $styles[162];?>;
	-webkit-box-shadow: <?php echo $styles[163];?> <?php echo $styles[164];?>px <?php echo $styles[165];?>px <?php echo $styles[166];?>px <?php echo $styles[167];?>px  <?php echo $styles[162];?>;
	box-shadow: <?php echo $styles[163];?> <?php echo $styles[164];?>px <?php echo $styles[165];?>px <?php echo $styles[166];?>px <?php echo $styles[167];?>px  <?php echo $styles[162];?>;
	border-style: <?php echo $styles[136];?>;
	border-width: <?php echo $styles[135];?>px;
	border-color: <?php echo $styles[161];?>;
}
/*.creativecontactform_input_element input:hover,.creativecontactform_input_element input:focus,.creativecontactform_input_element textarea:hover,.creativecontactform_input_element textarea:focus,.creativecontactform_input_element.focused input,.creativecontactform_input_element.focused textarea {*/
.creativecontactform_input_element_hovered input {
	color: <?php echo $styles[159];?>;
	text-shadow: <?php echo $styles[154];?>px <?php echo $styles[155];?>px <?php echo $styles[156];?>px <?php echo $styles[160];?>;
}
.creativecontactform_field_box_textarea_inner {
	width:<?php echo $styles[169];?>%;
	<?php $box_margin = $styles[501] == 'right' ? '0 0 0 auto' : ($styles[501] == 'center' ? '0 auto' : '0');  ?>
	margin: <?php echo $box_margin;?>;
}
.creative_textarea_wrapper {
	width:100% !important;
	height:<?php echo $styles[170];?>px;
}

.creativecontactform_error .creativecontactform_field_name,.creativecontactform_error .creativecontactform_field_name:hover {
	color: <?php echo $styles[171];?>;
	text-shadow: <?php echo $styles[173];?>px <?php echo $styles[174];?>px <?php echo $styles[175];?>px <?php echo $styles[172];?>;
}
.creativecontactform_error .creativecontactform_input_element,.creativecontactform_error .creativecontactform_input_element:hover {
	background-color: <?php echo $styles[176];?>;
	filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='<?php echo $styles[176];?>', endColorstr='<?php echo $styles[177];?>'); /* for IE */
	background: -webkit-gradient(linear, 0% 0%, 0% 100%, from(<?php echo $styles[176];?>), to(<?php echo $styles[177];?>));/* Safari 4-5, Chrome 1-9 */
	background: -webkit-linear-gradient(top, <?php echo $styles[176];?>, <?php echo $styles[177];?>); /* Safari 5.1, Chrome 10+ */
	background: -moz-linear-gradient(top, <?php echo $styles[176];?>, <?php echo $styles[177];?>);/* Firefox 3.6+ */
	background: -ms-linear-gradient(top, <?php echo $styles[176];?>, <?php echo $styles[177];?>);/* IE 10 */
	background: -o-linear-gradient(top, <?php echo $styles[176];?>, <?php echo $styles[177];?>);/* Opera 11.10+ */
	
	-moz-box-shadow: <?php echo $styles[185];?> <?php echo $styles[186];?>px <?php echo $styles[187];?>px <?php echo $styles[188];?>px <?php echo $styles[189];?>px  <?php echo $styles[184];?>;	
	-webkit-box-shadow: <?php echo $styles[185];?> <?php echo $styles[186];?>px <?php echo $styles[187];?>px <?php echo $styles[188];?>px <?php echo $styles[189];?>px  <?php echo $styles[184];?>;		
	box-shadow: <?php echo $styles[185];?> <?php echo $styles[186];?>px <?php echo $styles[187];?>px <?php echo $styles[188];?>px <?php echo $styles[189];?>px  <?php echo $styles[184];?>;		
	border-color: <?php echo $styles[178];?>;
	
}
.creativecontactform_error input,.creativecontactform_error input:hover, .creativecontactform_error .focused input:hover, .creativecontactform_error .focused input, .creativecontactform_error textarea,.creativecontactform_error textarea:hover {
	
	color: <?php echo $styles[179];?>;
	text-shadow: <?php echo $styles[181];?>px <?php echo $styles[182];?>px <?php echo $styles[183];?>px <?php echo $styles[180];?>;
}

.creativecontactform_pre_text {
	margin-top: <?php echo $styles[190];?>px;
	margin-bottom: <?php echo $styles[191];?>px;

	<?php $mr =$styles[502] == 'right' ? '0' : ($styles[502] == 'center' ? 'auto' : '0');?>
	<?php $ml = $styles[502] == 'right' ? 'auto' : ($styles[502] == 'center' ? 'auto' : '0');?>
	margin-right: <?php echo $mr;?>;
	margin-left: <?php echo $ml;?>;

	padding: <?php echo $styles[193];?>px 0 0 0;
	width: <?php echo $styles[192];?>%;
	
	font-size: <?php echo $styles[198];?>px;
	color: <?php echo $styles[197];?>;
	font-style: <?php echo $styles[200];?>;
	font-weight: <?php echo $styles[199];?>;
	text-decoration: <?php echo $styles[201];?>;
	text-shadow: <?php echo $styles[204];?>px <?php echo $styles[205];?>px <?php echo $styles[206];?>px <?php echo $styles[203];?>;
	text-align: <?php echo $styles[502];?>;
	
	border-top: <?php echo $styles[194];?>px <?php echo $styles[196];?> <?php echo $styles[195];?>;
	<?php 

		$ccf_googlefont = 'ccf-googlewebfont-';
		$ccf_font_rule = $styles[202];
		if (strpos($ccf_font_rule,$ccf_googlefont) !== false) {
			$ccf_font_rule = str_replace($ccf_googlefont, '', $ccf_font_rule);
			$ccf_font_rule .= ', sans-serif';
		}
	?>
	font-family: <?php echo $ccf_font_rule;?>
}
.creativecontactform_wrapper .tooltip_inner {
	<?php 

		$ccf_googlefont = 'ccf-googlewebfont-';
		$ccf_font_rule = $styles[508];
		if (strpos($ccf_font_rule,$ccf_googlefont) !== false) {
			$ccf_font_rule = str_replace($ccf_googlefont, '', $ccf_font_rule);
			$ccf_font_rule .= ', sans-serif';
		}
	?>
	font-family: <?php echo $ccf_font_rule;?>
}
.creativecontactform_field_required {
	<?php 

		$ccf_googlefont = 'ccf-googlewebfont-';
		$ccf_font_rule = $styles[509];
		if (strpos($ccf_font_rule,$ccf_googlefont) !== false) {
			$ccf_font_rule = str_replace($ccf_googlefont, '', $ccf_font_rule);
			$ccf_font_rule .= ', sans-serif';
		}
	?>
	font-family: <?php echo $ccf_font_rule;?>
}
/*************************************************RTL rules*******************************************************************************************/

<?php
if($styles[501] == 'right') {?>
.creativecontactform_wrapper .answer_name {
	float: right!important;
	text-align: right !important;
}
 .answer_input {
	float: right !important;
	margin-right: -100%;
}
 .creativecontactform_field_required {
	left: -12px !important;
}
 .the-tooltip.right > .tooltip_inner {
left: 0 !important;
padding: 3px 16px 4px 8px;
text-align: right;
}
 .the-tooltip.right > .tooltip_inner:after, .the-tooltip.right > .tooltip_inner:before {
	left: 0;
}
 .creative_input_dummy_wrapper img.ui-datepicker-trigger {
	left: -29px;
}

/***fileupload**/
 .creative_progress .bar {
float: right;
}
 .creative_fileupload_wrapper {
	text-align: right;
}
 .creative_uploaded_file {
	float: right;	
}
 .creative_remove_uploaded {
	float: right;	
}
 .creative_uploaded_icon {
	float: right;
}
/***captcha**/
 img.creative_captcha{
	float: right;
	margin: 3px 0px 5px 5px !important;
}
 .reload_creative_captcha {
	float: right;
}
 .creative_timing_captcha  {
	text-align: right;
}
<?php }
else { ?>
.creativecontactform_wrapper .answer_name {
	float: left!important;
	text-align: left !important;
}
 .answer_input {
	float: left !important;
	margin-left: -100%;
}
 .creativecontactform_field_required {
	right: -12px !important;
}
 .the-tooltip.right > .tooltip_inner {
right: 0 !important;
padding: 3px 16px 4px 8px;
text-align: left;
}
 .the-tooltip.right > .tooltip_inner:after, .the-tooltip.right > .tooltip_inner:before {
	right: 0;
}
 .creative_input_dummy_wrapper img.ui-datepicker-trigger {
	right: -29px;
}
/***fileupload**/
 .creative_progress .bar {
float: left;
}
 .creative_fileupload_wrapper {
	text-align: left;
}
.creative_uploaded_file {
	float: left;	
}
 .creative_remove_uploaded {
	float: left;	
}
 .creative_uploaded_icon {
	float: left;
}
/***captcha**/
 img.creative_captcha{
	float: left;
	margin: 3px 5pxpx 5px 0px !important;
}
 .reload_creative_captcha {
	float: left;
}
 .creative_timing_captcha  {
	text-align: left;
}
<?php }?>
.creativecontactform_heading { 
	width: 100% !important;
}
.creativecontactform_heading_inner {
	margin: <?php echo $styles[535];?>px <?php echo $styles[536];?>px <?php echo $styles[537];?>px <?php echo $styles[538];?>px;
	
}
.creativecontactform_heading {
	line-height: 1;
	overflow: hidden;
	font-size: <?php echo $styles[525];?>px;
	color: <?php echo $styles[524];?>;
	font-style: <?php echo $styles[527];?>;
	font-weight: <?php echo $styles[526];?>;
	text-decoration: <?php echo $styles[528];?>;
	text-shadow: <?php echo $styles[532];?>px <?php echo $styles[533];?>px <?php echo $styles[534];?>px <?php echo $styles[531];?>;

	margin: <?php echo $styles[539];?>px 0 <?php echo $styles[540];?>px 0;
	
	border-top: <?php echo $styles[543];?>px <?php echo $styles[547];?> <?php echo $styles[548];?>;
	border-right: <?php echo $styles[544];?>px <?php echo $styles[547];?> <?php echo $styles[549];?>;
	border-bottom: <?php echo $styles[545];?>px <?php echo $styles[547];?> <?php echo $styles[550];?>;
	border-left: <?php echo $styles[546];?>px <?php echo $styles[547];?> <?php echo $styles[551];?>;

	background-color: <?php echo $styles[541];?>;
	filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='<?php echo $styles[541];?>', endColorstr='<?php echo $styles[542];?>'); /* for IE */
	background: -webkit-gradient(linear, 0% 0%, 0% 100%, from(<?php echo $styles[541];?>), to(<?php echo $styles[542];?>));/* Safari 4-5, Chrome 1-9 */
	background: -webkit-linear-gradient(top, <?php echo $styles[541];?>, <?php echo $styles[542];?>); /* Safari 5.1, Chrome 10+ */
	background: -moz-linear-gradient(top, <?php echo $styles[541];?>, <?php echo $styles[542];?>);/* Firefox 3.6+ */
	background: -ms-linear-gradient(top, <?php echo $styles[541];?>, <?php echo $styles[542];?>);/* IE 10 */
	background: -o-linear-gradient(top, <?php echo $styles[541];?>, <?php echo $styles[542];?>);/* Opera 11.10+ */

	<?php 

		$ccf_googlefont = 'ccf-googlewebfont-';
		$ccf_font_rule = $styles[529];
		if (strpos($ccf_font_rule,$ccf_googlefont) !== false) {
			$ccf_font_rule = str_replace($ccf_googlefont, '', $ccf_font_rule);
			$ccf_font_rule .= ', sans-serif';
		}
	?>
	font-family: <?php echo $ccf_font_rule;?>
}



/* **************************** Sections ,Links ,number styles*******************************************************************/
.ccf_content_element_label {
	font-size: <?php echo $styles[554];?>px;
	color: <?php echo $styles[553];?>;
	font-style: <?php echo $styles[556];?>;
	font-weight: <?php echo $styles[555];?>;
	text-shadow: <?php echo $styles[559];?>px <?php echo $styles[560];?>px <?php echo $styles[561];?>px <?php echo $styles[558];?>;
	border-bottom: <?php echo $styles[590];?>px <?php echo $styles[591];?> <?php echo $styles[592];?>;
	text-decoration: <?php echo $styles[596];?>;
}
a.ccf_link {
	color: <?php echo $styles[564];?>;
	font-style: <?php echo $styles[566];?>;
	font-weight: <?php echo $styles[565];?>;
	text-shadow: <?php echo $styles[571];?>px <?php echo $styles[572];?>px <?php echo $styles[573];?>px <?php echo $styles[570];?>;
	border-bottom: <?php echo $styles[567];?>px <?php echo $styles[568];?> <?php echo $styles[569];?>;

	text-decoration: <?php echo $styles[594];?>;
}
a.ccf_link:hover {
	color: <?php echo $styles[564];?>;
	font-style: <?php echo $styles[566];?>;
	font-weight: <?php echo $styles[565];?>;
	text-shadow: <?php echo $styles[571];?>px <?php echo $styles[572];?>px <?php echo $styles[573];?>px <?php echo $styles[570];?>;
	border-bottom: <?php echo $styles[567];?>px <?php echo $styles[568];?> <?php echo $styles[569];?>;

	text-decoration: <?php echo $styles[594];?>;
}
a.ccf_link_hovered {
	color: <?php echo $styles[574];?>;
	text-shadow: <?php echo $styles[577];?>px <?php echo $styles[578];?>px <?php echo $styles[579];?>px <?php echo $styles[576];?>;
	border-bottom: <?php echo $styles[567];?>px <?php echo $styles[568];?> <?php echo $styles[575];?>;

	font-style: <?php echo $styles[566];?>;
	font-weight: <?php echo $styles[565];?>;
	text-decoration: <?php echo $styles[595];?>;
}
a.ccf_link_hovered:hover {
	color: <?php echo $styles[574];?>;
	text-shadow: <?php echo $styles[577];?>px <?php echo $styles[578];?>px <?php echo $styles[579];?>px <?php echo $styles[576];?>;
	border-bottom: <?php echo $styles[567];?>px <?php echo $styles[568];?> <?php echo $styles[575];?>;

	font-style: <?php echo $styles[566];?>;
	font-weight: <?php echo $styles[565];?>;
	text-decoration: <?php echo $styles[595];?>;
}

.ccf_content_styling {
	color: <?php echo $styles[580];?>;
	font-style: <?php echo $styles[582];?>;
	font-weight: <?php echo $styles[581];?>;
	text-shadow: <?php echo $styles[584];?>px <?php echo $styles[585];?>px <?php echo $styles[586];?>px <?php echo $styles[583];?>;
	text-decoration: <?php echo $styles[593];?>;
}


/*custom styles*/
<?php 
$custom_styles = str_replace('ccf_img_path',JURI::base(true).'/../components/com_creativecontactform/assets/images/bg_images',$styles[599]);
echo $custom_styles = str_replace('.creative_form_FORM_ID','',$custom_styles);
?>

</style>
<?php include (JPATH_BASE.'/components/com_creativecontactform/helpers/footer.php'); ?>