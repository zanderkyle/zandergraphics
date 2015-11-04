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

?>
<table class="adminlist" style="width: 100%;margin-top: 12px;clear: both;"><tr><td align="center" valign="middle" id="twoglux_ext_td" style="position: relative;height: 70px;">
	<div style="color: #0561AC;font-size: 14px;margin: 13px 0 -3px 10px;font-weight: normal;font-style: italic;">Upgrading from <b style="text-decoration: underline;">Free</b> to <b style="text-decoration: underline;">Commercial</b> is easy! It will take only <a href="<?php echo JText::_( 'COM_CREATIVECONTACTFORM_SUBMENU_BUY_PRO_VERSION_LINK' ); ?>" target="_blank" style="color: rgb(214, 0, 0);font-weight: bold;text-decoration: underline;">5 minutes!</a></div>
	<div id="twoglux_bottom_link"><a href="<?php echo JText::_( 'COM_CREATIVECONTACTFORM_SUBMENU_PROJECT_HOMEPAGE_LINK' ); ?>" target="_blank"><?php echo JText::_( 'COM_CREATIVECONTACTFORM' ); ?></a> <?php echo JText::_( 'COM_CREATIVECONTACTFORM_DEVELOPED_BY' ); ?> <a href="http://creative-solutions.net" target="_blank">Creative-Solutions.net</a></div>
	<div style="position: absolute;right: 2px;top: 19px;">
		<a href="<?php echo JText::_( 'COM_CREATIVECONTACTFORM_SUBMENU_RATE_US_LINK' ); ?>" target="_blank" id="twoglux_ext_rate" class="twoglux_ext_bottom_icon" title="<?php echo JText::_( 'COM_CREATIVECONTACTFORM_SUBMENU_RATE_US_DESCRIPTION' ); ?>">&nbsp;</a>
		<a href="<?php echo JText::_( 'COM_CREATIVECONTACTFORM_SUBMENU_PROJECT_HOMEPAGE_LINK' ); ?>" target="_blank" id="twoglux_ext_homepage" style="margin: 0 2px 0 0px;" class="twoglux_ext_bottom_icon" title="<?php echo JText::_( 'COM_CREATIVECONTACTFORM_SUBMENU_PROJECT_HOMEPAGE_DESCRIPTION' ); ?>">&nbsp;</a>
		<a href="<?php echo JText::_( 'COM_CREATIVECONTACTFORM_SUBMENU_SUPPORT_FORUM_LINK' ); ?>" target="_blank" id="twoglux_ext_support" class="twoglux_ext_bottom_icon" title="<?php echo JText::_( 'COM_CREATIVECONTACTFORM_SUBMENU_SUPPORT_FORUM_DESCRIPTION' ); ?>">&nbsp;</a>
		<a href="<?php echo JText::_( 'COM_CREATIVECONTACTFORM_SUBMENU_BUY_PRO_VERSION_LINK' ); ?>" target="_blank" id="twoglux_ext_buy" class="twoglux_ext_bottom_icon" title="<?php echo JText::_( 'COM_CREATIVECONTACTFORM_SUBMENU_BUY_PRO_VERSION_DESCRIPTION' ); ?>">&nbsp;</a>
	</div>
</td></tr></table>

<?php 
//generates similar extensions
$document = JFactory::getDocument();

$jsFile = JURI::base(true).'/components/com_creativecontactform/assets/js/creativelib.js';
$document->addScript($jsFile);

$jsFile = JURI::base(true).'/components/com_creativecontactform/assets/js/footer.js';
$document->addScript($jsFile);

$cssFile = JURI::base(true).'/components/com_creativecontactform/assets/css/creative_buttons.css';
$document->addStyleSheet($cssFile, 'text/css', null, array());

?>

<?php 
// $similar_visible = false;
$similar_visible = rand(0,10) < 3 ? true : false;
?>

<?php 
	if(!$similar_visible && !(isset($_COOKIE["sim_ext_1"]) && isset($_COOKIE["sim_ext_2"]) && isset($_COOKIE["sim_ext_3"]) )){
		$show_visible = '';
	}
	else {
		$show_visible = 'style="display: none;"';
	}
?>
<div class="show_similar" <?php echo $show_visible;?>>Show Suggested Extensions!</div>
<div class="hide_similar" style="display: none;">Hide Suggested Extensions!</div>

<div id="similar_extensions" <?php if(!$similar_visible) echo 'style="display: none"; class="sim_hidden"';?>>

<?php 

if(!(isset($_COOKIE["sim_ext_1"]) && isset($_COOKIE["sim_ext_2"]) && isset($_COOKIE["sim_ext_3"]) )) {?>
<div class="sim_ext_title">Extensions You Will Like!</div>
<?php }?>

<?php $sim_ext_path = JURI::base(true).'/components/com_creativecontactform/assets/images/similar_extensions/';?>

<div id="similar_ext_wrapper">

<?php if(!isset($_COOKIE["sim_ext_1"])) {?>
	<div class="sim_ext_item_wrapper">
		<div class="twog_extension_wrapper" id="sim_ext_1" roll="1">
			<img  class="sim_img_close" title="Close Suggestion" alt="Close Suggestion" src="<?php echo $sim_ext_path;?>close.png" />
			<div class="twog_extension_wrapper_inner">
				<div class="ext_top_wrapper">
					<a href="http://creative-solutions.net/joomla/creative-image-slider" target="_blank" class="sim_ext_link">Creative Image Slider</a>
					<img  class="sim_img_popular" title="Popular" alt="Popular" src="<?php echo $sim_ext_path;?>jed_status_popular.png" />
				</div>
				<a href="http://creative-solutions.net/joomla/creative-image-slider" target="_blank" class="sim_ext_link"><img class="sim_ext_img" src="<?php echo $sim_ext_path;?>cis.png" /></a>
				<div class="sim_line1">
					<img class="sim_img_compat" title="Joomla! 2.5.X compatible" alt="Joomla! 2.5.X compatible" src="<?php echo $sim_ext_path;?>compat_25.png" />
					<img class="sim_img_compat" title="Joomla! 3.X.X compatible" alt="Joomla! 3.X.X compatible" src="<?php echo $sim_ext_path;?>compat_30.png" style="margin-left: -2px;" />
					
					<div style="display: inline-block;">
						<img class="sim_img_star" title="" alt="" src="<?php echo $sim_ext_path;?>star_10.png" style="margin-left: 4px;" />
						<img class="sim_img_star" title="" alt="" src="<?php echo $sim_ext_path;?>star_10.png" style="margin-left: -4px;" />
						<img class="sim_img_star" title="" alt="" src="<?php echo $sim_ext_path;?>star_10.png" style="margin-left: -4px;" />
						<img class="sim_img_star" title="" alt="" src="<?php echo $sim_ext_path;?>star_10.png" style="margin-left: -4px;" />
						<img class="sim_img_star" title="" alt="" src="<?php echo $sim_ext_path;?>star_10.png" style="margin-left: -4px;" />
					</div>
					
					<div style="display: inline-block;float: right;">
						<img class="sim_img_type" title="Plugin" alt="" src="<?php echo $sim_ext_path;?>ext_plugin_mini.png" style="margin-left: 2px;" />
						<img class="sim_img_type" title="Module" alt="" src="<?php echo $sim_ext_path;?>ext_mod_mini.png" style="margin-left: 2px;" />
						<img class="sim_img_type" title="Component" alt="" src="<?php echo $sim_ext_path;?>ext_com_mini.png" style="margin-left: 2px;" />
					</div>
				</div>
				
				<div class="sim_ext_desc">
					Creative Image Slider is a responsive jQuery image slider with amazing visual effects. It uses horizontal scrolling to make the slider more creative and attractive.
					<br />It is packed with a live-preview wizard to create fantastic sliders in a matter of seconds without coding.
				</div>
				
				<div class="sim_links">
					<a style="" target="_blank" href="http://creative-solutions.net/joomla/creative-image-slider-demo" class="creative_btn creative_btn-green creative_btn-mini"><i class="creative_icon-white creative_icon-play"></i> Live Demo</a>
					<a style="" target="_blank" href="http://creative-solutions.net/joomla/creative-image-slider" class="creative_btn creative_btn-red creative_btn-mini"><i class="creative_icon-white creative_icon-download"></i> Download</a>
				</div>
				
			</div>
		</div>
	</div>
<?php }?>
<?php if(!isset($_COOKIE["sim_ext_2"])) {?>
	<div class="sim_ext_item_wrapper">
		<div class="twog_extension_wrapper" id="sim_ext_2" roll="2">
			<img  class="sim_img_close" title="Close Suggestion" alt="Close Suggestion" src="<?php echo $sim_ext_path;?>close.png" />
			<div class="twog_extension_wrapper_inner">
				<div class="ext_top_wrapper">
					<a href="http://creative-solutions.net/joomla/creative-social-widget" target="_blank" class="sim_ext_link">Creative Social Widget</a>
					<img  class="sim_img_popular" title="Popular" alt="Popular" src="<?php echo $sim_ext_path;?>jed_status_popular.png" />
				</div>
				<a href="http://creative-solutions.net/joomla/creative-social-widget" target="_blank" class="sim_ext_link"><img class="sim_ext_img" src="<?php echo $sim_ext_path;?>creative-social-widget.png" /></a>
				<div class="sim_line1">
					<img class="sim_img_compat" title="Joomla! 2.5.X compatible" alt="Joomla! 2.5.X compatible" src="<?php echo $sim_ext_path;?>compat_25.png" />
					<img class="sim_img_compat" title="Joomla! 3.X.X compatible" alt="Joomla! 3.X.X compatible" src="<?php echo $sim_ext_path;?>compat_30.png" style="margin-left: -2px;" />
					
					<div style="display: inline-block;">
						<img class="sim_img_star" title="" alt="" src="<?php echo $sim_ext_path;?>star_10.png" style="margin-left: 4px;" />
						<img class="sim_img_star" title="" alt="" src="<?php echo $sim_ext_path;?>star_10.png" style="margin-left: -4px;" />
						<img class="sim_img_star" title="" alt="" src="<?php echo $sim_ext_path;?>star_10.png" style="margin-left: -4px;" />
						<img class="sim_img_star" title="" alt="" src="<?php echo $sim_ext_path;?>star_10.png" style="margin-left: -4px;" />
						<img class="sim_img_star" title="" alt="" src="<?php echo $sim_ext_path;?>star_10.png" style="margin-left: -4px;" />
					</div>
					
					<div style="display: inline-block;float: right;">
						<img class="sim_img_type" title="Module" alt="" src="<?php echo $sim_ext_path;?>ext_mod_mini.png" style="margin-left: 2px;" />
						<img class="sim_img_type" title="Component" alt="" src="<?php echo $sim_ext_path;?>ext_com_mini.png" style="margin-left: 2px;" />
					</div>
				</div>
				
				<div class="sim_ext_desc">
					Creative Social Widget - Show your presence in social web! Show your presence in social web in very original and creative way.
				</div>
				
				<div class="sim_links">
					<a style="" target="_blank" href="http://creative-solutions.net/joomla/creative-social-widget/demo" class="creative_btn creative_btn-green creative_btn-mini"><i class="creative_icon-white creative_icon-play"></i> Live Demo</a>
					<a style="" target="_blank" href="http://creative-solutions.net/joomla/creative-social-widget" class="creative_btn creative_btn-red creative_btn-mini"><i class="creative_icon-white creative_icon-download"></i> Download</a>
				</div>
				
			</div>
		</div>
	</div>
<?php }?>
<?php if(!isset($_COOKIE["sim_ext_3"])) {?>
	<div class="sim_ext_item_wrapper">
		<div class="twog_extension_wrapper" id="sim_ext_3" roll="3">
			<img  class="sim_img_close" title="Close Suggestion" alt="Close Suggestion" src="<?php echo $sim_ext_path;?>close.png" />
			<div class="twog_extension_wrapper_inner">
				<div class="ext_top_wrapper">
					<a href="http://creative-solutions.net/joomla/gspeech/demo" target="_blank" class="sim_ext_link">GSpeech</a>
					<img  class="sim_img_popular" title="Popular" alt="Popular" src="<?php echo $sim_ext_path;?>jed_status_popular.png" />
				</div>
				<a href="http://creative-solutions.net/joomla/gspeech/demo" target="_blank" class="sim_ext_link"><img class="sim_ext_img" src="<?php echo $sim_ext_path;?>gspeech.png" /></a>
				<div class="sim_line1">
					<img class="sim_img_compat" title="Joomla! 2.5.X compatible" alt="Joomla! 2.5.X compatible" src="<?php echo $sim_ext_path;?>compat_25.png" />
					<img class="sim_img_compat" title="Joomla! 3.X.X compatible" alt="Joomla! 3.X.X compatible" src="<?php echo $sim_ext_path;?>compat_30.png" style="margin-left: -2px;" />
					
					<div style="display: inline-block;">
						<img class="sim_img_star" title="" alt="" src="<?php echo $sim_ext_path;?>star_10.png" style="margin-left: 4px;" />
						<img class="sim_img_star" title="" alt="" src="<?php echo $sim_ext_path;?>star_10.png" style="margin-left: -4px;" />
						<img class="sim_img_star" title="" alt="" src="<?php echo $sim_ext_path;?>star_10.png" style="margin-left: -4px;" />
						<img class="sim_img_star" title="" alt="" src="<?php echo $sim_ext_path;?>star_10.png" style="margin-left: -4px;" />
						<img class="sim_img_star" title="" alt="" src="<?php echo $sim_ext_path;?>star_10.png" style="margin-left: -4px;" />
					</div>
					
					<div style="display: inline-block;float: right;">
						<img class="sim_img_type" title="Plugin" alt="" src="<?php echo $sim_ext_path;?>ext_plugin_mini.png" style="margin-left: 2px;" />
					</div>
				</div>
				
				<div class="sim_ext_desc">
					How it would be wonderful, if your visitors could listen any selected text from your site? We made that possible. We use Google power to provide you the best quality of automatic text to speech service. Enjoy !
				</div>
				
				<div class="sim_links">
					<a style="" target="_blank" href="http://creative-solutions.net/joomla/gspeech/demo" class="creative_btn creative_btn-green creative_btn-mini"><i class="creative_icon-white creative_icon-play"></i> Live Demo</a>
					<a style="" target="_blank" href="http://creative-solutions.net/joomla/gspeech" class="creative_btn creative_btn-red creative_btn-mini"><i class="creative_icon-white creative_icon-download"></i> Download</a>
				</div>
				
			</div>
		</div>
	</div>
<?php }?>
</div>

</div>





<style>
#similar_ext_wrapper {
	text-align: left;
	margin-bottom: 30px;
}
.sim_ext_item_wrapper {
	display: inline-block;
	width: 30%;
	margin: 30px 10px 0px 10px;
	min-height: 120px;
	vertical-align: top;
	position: relative;
}
.twog_extension_wrapper {
	border-radius: 6px;
	border-top: 1px solid #E0DEB2;
	text-align: left;
	box-shadow: 0 1px 2px 0px #918A5F;
	background-color: rgba(255, 251, 226, 0.3);
	-webkit-transition: all linear 0.2s;
	-moz-transition: all linear 0.2s;
	-o-transition: all linear 0.2s;
	transition: all linear 0.2s;

	opacity: 0;
	filter: alpha(opacity=0);
	
}
.twog_extension_wrapper:hover {
	border-radius: 12px;
	border-top: 1px solid #DFDB8A;
	box-shadow: 0 2px 3px 1px rgba(143, 135, 81, 0.64);
	background-color: rgba(255, 251, 226, 0.99);
}
.sim_ext_title {
	color: #0561AC;
	font-size: 18px;
	margin: 35px 0 -15px 0;
	font-style: italic;
	text-align: center;
}
.sim_ext_img {
	float: left;
	padding: 8px;
	box-shadow: 0 1px 2px 1px #999;
	-webkit-box-shadow: 0 1px 2px 1px #999;
	-moz-box-shadow: 0 1px 2px 1px #999;
	border-radius: 3px;
	margin: 0 10px 5px 0;
	border: none;
	background-color: #fff;
	
	-webkit-transition: all linear 0.2s;
	-moz-transition: all linear 0.2s;
	-o-transition: all linear 0.2s;
	transition: all linear 0.2s;
}
.sim_ext_img:hover {
	box-shadow: 0 1px 2px 2px rgba(153, 153, 153, 0.54);
	-webkit-box-shadow: 0 1px 2px 2px rgba(153, 153, 153, 0.54);
	-moz-box-shadow: 0 1px 2px 2px rgba(153, 153, 153, 0.54);
	border-radius: 6px;
	background-color: #FFECAE;
}
.sim_ext_link {
	color: #136AA5;
	font-weight: normal;
	font-size: 18px;
	
	-webkit-transition: all linear 0.2s;
	-moz-transition: all linear 0.2s;
	-o-transition: all linear 0.2s;
	transition: all linear 0.2s;
}
.sim_ext_link:hover {
	color: rgb(209, 0, 0);
	text-decoration: none;
}
.twog_extension_wrapper_inner {
	padding: 10px 16px 10px 16px;
	overflow: hidden;
}
.ext_top_wrapper {
	margin-bottom: 8px;
}
.sim_img_popular {
	border: none;
	display: inline-block;
	margin: -5px 0 0 2px;
}
.sim_img_popular:hover {
	margin-top: -7px;
}

.sim_line1 {
	clear: none;
}
.sim_img_compat {
	margin: -7px 0 0 0px;
}
.sim_img_compat:hover {
	margin-top: -9px;
}
.sim_img_star {
	margin: -9px 0 0 0;
}
.sim_img_star:hover {
	margin-top: -11px;
}
.sim_img_type {
	margin: 0px 0 0 0;
	float: right;
}
.sim_img_type:hover {
	margin-top: -1px;
}
.sim_ext_desc {
	margin: 5px 0 0 0;
	min-height: 76px;
}

.sim_links {
	text-align: right;
	margin-top: 0px;
}
.sim_links a{
	text-align: right;
	margin-top: 8px !important;
}
.sim_links a {
	opacity: 0.9;
	-webkit-transition: all linear 0.2s;
	-moz-transition: all linear 0.2s;
	-o-transition: all linear 0.2s;
	transition: all linear 0.2s;
}
.sim_links a:hover {
	opacity: 1;
}

.sim_img_close {
	width: 16px;
	position: absolute;
	top: -7px;
	right: -5px;
	opacity: 0.8;
	cursor: pointer;
	-webkit-transition: all linear 0.2s;
	-moz-transition: all linear 0.2s;
	-o-transition: all linear 0.2s;
	transition: all linear 0.2s;
}
.sim_img_close:hover {
	opacity: 1;
	top: -8px;
}

.show_similar {
	margin: 15px 0 0 5px;
	color: #0561AC;
	font-size: 15px;
	font-style: italic;
	cursor: pointer;
}
.hide_similar {
	margin: 15px 0 0 5px;
	color: #0561AC;
	font-size: 15px;
	font-style: italic;
	cursor: pointer;
}
#similar_extensions {
	display: none;
}
</style>