<?php header("Content-type: text/css"); ?>
<?php
/**
 * @package   Versatility4 Template - RocketTheme
* @version   $Id: template_ie6.php 26096 2015-01-27 14:14:12Z james $
* @author    RocketTheme, LLC http://www.rockettheme.com
* @copyright Copyright (C) 2007 - 2015 RocketTheme, LLC
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 *
 * Rockettheme Versatility4 Template uses the Joomla Framework (http://www.joomla.org), a GNU/GPLv2 content management system
 *
 */
$template_path = dirname( dirname( $_SERVER['SCRIPT_NAME'] ) );
?>
/** IE6 is a hunk of crap!!! due to limitations in the CSS capabilities of IE, these hacks are required **/


#ff-optima h1,#ff-optima h2,#ff-optima h3,#ff-optima h4,#ff-optima h5,#ff-optima h6, #ff-lucida h1,#ff-lucida h2,#ff-lucida h3,#ff-lucida h4,#ff-lucida h5,#ff-lucida h6 {letter-spacing: -0.07em;}
body#ff-optima, body#ff-lucida {letter-spacing: -0.03em;}
body#ff-georgia, body#ff-georgia.f-default {font-size: 12px;}
.menu span {cursor:pointer;}
#horiz-menu li.sfHover li.sfHover {background: none;}
#submenu-container {padding-bottom: 0;}
.menustyle1 #horiz-menu {filter:progid:DXImageTransform.Microsoft.AlphaImageLoader(src='<?php echo $template_path; ?>/images/horiz-menu-bar.png', sizingMethod='scale');background-image: none;zoom: 1;}
.menustyle1 #horiz-menu a, .menustyle1 #horiz-menu .separator {background: none;line-height: 41px;}
.menustyle1 #submenu-container {filter:progid:DXImageTransform.Microsoft.AlphaImageLoader(src='<?php echo $template_path; ?>/images/sub-menu-bar.png', sizingMethod='scale');background-image: none;zoom: 1;}
.menustyle2 #horiz-menu {filter:progid:DXImageTransform.Microsoft.AlphaImageLoader(src='<?php echo $template_path; ?>/images/horiz-menu-bar.png', sizingMethod='scale');background-image: none;zoom: 1;}
.menustyle2 #horiz-menu a, .menustyle2 #horiz-menu .separator {background: none;}
.menustyle2 #horiz-menu li.active a, .menustyle2 #horiz-menu li.active .separator {filter:progid:DXImageTransform.Microsoft.AlphaImageLoader(src='<?php echo $template_path; ?>images/2-horiz-menu-active.png', sizingMethod='scale');background-image: none;zoom: 1;}
.menustyle3 #horiz-menu {filter:progid:DXImageTransform.Microsoft.AlphaImageLoader(src='<?php echo $template_path; ?>/images/3-horiz-menu-bar.png', sizingMethod='scale');background-image: none;zoom: 1;}
.menustyle3 #submenu-container {filter:progid:DXImageTransform.Microsoft.AlphaImageLoader(src='<?php echo $template_path; ?>/images/sub-menu-bar.png', sizingMethod='scale');background-image: none;zoom: 1;}
.menustyle3 #horiz-menu li a, .menustyle3 #sub-menu li a {margin-left: 2px;}
#searchmod {height: 28px;}
#searchmod .inputbox {float: none;}
#top-menu {margin-top: -15px;}
#header-divider, .div-spacer {font-size: 0;line-height: 0;padding: 0,margin: 0;}
.sidenav-block, .moduletable-hilite7, .moduletable_menu {position: relative;z-index: 2;}
.sidenav-block ul, .moduletable_menu ul {position: relative; z-index: 10;}
.sidenav-block-right {background: none;}
.sidenav-block-left {background: none;}
#bottom {position:relative; z-index: 10;width:100%;}
#mainblock, #main-body, #maincol, #leftcol, #rightcol, #leftcol .padding, #rightcol .padding, #main-bar, #bottom, .module, .moduletable, .rotator-module, .pathway-block, .variation-chooser, #moduleslider-size, #moduleslider-size .alt-bottom, #mainmodules, #mainmodules2, #mainmodules3, #mainmodules4, .main-height, .sidenav-block, .module-hilite1, .moduletable-hilite1, .module-hilite2, .moduletable-hilite2, .module-hilite3, .moduletable-hilite3, .module-hilite4, .moduletable-hilite4, .module-hilite5, .moduletable-hilite5, .module-hilite6, .moduletable-hilite6, .module-hilite7, .moduletable-hilite7, .module-hilite8, .moduletable-hilite8, .module-hilite9, .moduletable-hilite9, .module-clean, .moduletable-clean #tabmodules, .maincontent-surround, .maincontent-surround .padding, #inset-block-left, #inset-block-right, #maincontent-block, #mainmodules .block, div.block.first, div.block.middle, div.block.last, .module div, .module div div, .module div div div, #headerblock-right, #headerblock-right .padding, #top-menu, #searchmod, #frame, #header-divider, #bottommodules, div.number-block1, div.number-block2, div.number-block3, #news-rotator .story, #news-rotator .image, #news-rotator-bg, #news-rotator-bg2, .moduletable-rotator .feature-block, #news-rotator .image div, #news-rotator .story .padding, a.child, a.sublevel, #header-bar, .wrapper, #bottom-menu {zoom: 1;}
.module input.button, .moduletable input.button, .module-hilite1 input.button, module-hilite2 input.button, module-hilite3 input.button, .module-hilite4 input.button, .module-hilite5 input.button, .module-hilite6 input.button, .module-hilite7 input.button, .module-hilite8 input.button, .module-hilite9 input.button, .module-clean input.button, .moduletable-hilite1 input.button, .moduletable-hilite2 input.button, .moduletable-hilite3 input.button, .moduletable-hilite4 input.button, .moduletable-hilite5 input.button, .moduletable-hilite6 input.button, .moduletable-hilite7 input.button, .moduletable-hilite8 input.button, .moduletable-hilite9 input.button, .moduletable-clean input.button {font-size: 8px;}
#rokslide-toolbar li {float: left;}
#rokslide-toolbar li span {float: left;padding: 0;}
a.readon2 {text-indent: 0;text-align: center;line-height: 150%;padding: 0;}
#horiz-menu li li a, #horiz-menu li.active li a, #horiz-menu li li .separator, #horiz-menu li.active li .separator {width:100%;}
#horiz-menu ul ul li a.daddy, #horiz-menu ul ul li a.daddy:hover, #horiz-menu ul li.active.parent ul li a.daddy, #horiz-menu ul ul li .separator.daddy, #horiz-menu ul ul li .separator.daddy:hover, #horiz-menu ul li.active.parent ul li .separator.daddy {background-position: 90% 60%;}
#bottom-menu .menu-bottom a, #bottom-menu .menu-bottom a#active_menu.mainlevel {padding: 0 20px 0 0;margin: 0 10px 0 0;line-height: 50px;}
#news-rotator, #news-rotator-bg, #news-rotator-bg2, .moduletable-rotator .rokstories-layout2 .story-stripe {z-index: 100;position:relative;}
#news-rotator .story {margin-left: 0;left: 0;}
#news-rotator .story {top: 15px;position: relative;z-index: 100;}
#news-rotator .story-block {height: 100%;z-index: 100;position:relative;}
#news-rotator a {z-index: 200;}
#news-rotator .image {position: absolute;left: 2px;z-index:100;}
#news-rotator .controls {z-index:210;}
#sub-menu a, #sub-menu .separator {margin-right:5px;}
a.child, a.sublevel {background-position: 0 12px !important;}
#maincol {margin-left:0px;margin-right:0px;float:left;}
/*.module, .moduletable, .module-clean  .moduletable-clean {position:relative;}*/
#mainmodules .module h3, #mainmodules .moduletable h3, #mainmodules .module-clean h3, #mainmodules .moduletable-clean h3, #mainmodules2 .module h3, #mainmodules2 .moduletable h3, #mainmodules2 .module-clean h3, #mainmodules2 .moduletable-clean h3 {margin:0;position:relative;top:-15px;left:-15px;margin-right:-30px;padding-bottom: 8px;}


/* ie6 warning */
#iewarn {background: #C6D3DA url(../images/error.png) 10px 20px no-repeat;position: relative;z-index: 1;opacity: 0;margin: -150px auto 0;font-size: 110%;color: #001D29;z-index: 8000;}
#iewarn div {position: relative;border-top: 5px solid #95B8C9;border-bottom: 5px solid #95B8C9;padding: 10px 80px 10px 220px;}
#iewarn h4 {color: #900;font-weight: bold;line-height: 120%;}
#iewarn a {color: #296AC6;font-weight: bold;}
#iewarn_close {background: url(../images/close.png) 50% 50% no-repeat;display: block;cursor: pointer;position: absolute;width: 61px;height: 21px;top: 25px;right: 12px;}
#iewarn_close.cHover {background: url(../images/close_hover.png) 50% 50% no-repeat;}
/* end ie6 warning */

/*
   NEW PURE CSS PNG FIX SOLUTION  
   use class="png" to implement 
*/

html .png,
div .png,
div.arrow {
	azimuth: expression(
		this.pngSet?this.pngSet=true:(this.nodeName == "IMG" && this.src.toLowerCase().indexOf('.png')>-1?(this.runtimeStyle.backgroundImage = "none",
		this.runtimeStyle.filter = "progid:DXImageTransform.Microsoft.AlphaImageLoader(src='" + this.src + "', sizingMethod='image')",
		this.src = "<?php echo $template_path; ?>/images/blank.gif"):(this.origBg = this.origBg? this.origBg :this.currentStyle.backgroundImage.toString().replace('url("','').replace('")',''),
		this.runtimeStyle.filter = "progid:DXImageTransform.Microsoft.AlphaImageLoader(src='" + this.origBg + "', sizingMethod='crop')",
		this.runtimeStyle.backgroundImage = "none")),this.pngSet=true
	);
}

/* page peel overrides for demo site */
a.fliptip {display: block;z-index: 100000;position: relative;}