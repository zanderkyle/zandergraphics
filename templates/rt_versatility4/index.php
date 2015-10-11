<?php
/**
 * @package   Versatility4 Template - RocketTheme
* @version   $Id: index.php 26096 2015-01-27 14:14:12Z james $
* @author    RocketTheme, LLC http://www.rockettheme.com
* @copyright Copyright (C) 2007 - 2015 RocketTheme, LLC
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 *
 * Rockettheme Versatility4 Template uses the Joomla Framework (http://www.joomla.org), a GNU/GPLv2 content management system
 *
 */
// no direct access
defined( '_JEXEC' ) or die( 'Restricted index access' );
define( 'YOURBASEPATH', dirname(__FILE__) );
require( YOURBASEPATH."/rt_styleswitcher.php");
JHTML::_( 'behavior.framework', true );
global $template_real_width, $leftcolumn_width, $rightcolumn_width, $leftbanner_width, $rightbanner_width, $tstyle;
global $show_moduleslider,$js_compatibility,$moduleslider_location;


$app 			  		= JFactory::getApplication();
$live_site        		= $app->getCfg('live_site');
$template_path 			= $this->baseurl . '/templates/' .  $this->template;
$default_style 			= $this->params->get("defaultStyle", "style2");
$menu_style				= $this->params->get("menuStyle", "menustyle3");
$header_wrapped			= ($this->params->get("headerWrapped", 0)  == 0)?"false":"true";
$footer_wrapped			= ($this->params->get("footerWrapped", 0)  == 0)?"false":"true";
$enable_ie6warn         = ($this->params->get("enableIe6warn", 0)  == 0)?"false":"true";
$font_family            = $this->params->get("fontFamily", "default");
$enable_fontspans       = ($this->params->get("enableFontspans", 1)  == 0)?"false":"true";
$template_width 		= $this->params->get("templateWidth", "958");
$leftcolumn_width		= $this->params->get("leftcolumnWidth", "220");
$rightcolumn_width		= $this->params->get("rightcolumnWidth", "220");
$leftinset_width		= $this->params->get("leftinsetWidth", "275");
$rightinset_width		= $this->params->get("rightinsetWidth", "185");
$splitmenu_col			= $this->params->get("splitmenuCol", "rightcol");
$menu_name 				= $this->params->get("menuName", "mainmenu");
$menu_type 				= $this->params->get("menuType", "splitmenu");
$default_font 			= $this->params->get("defaultFont", "default");
$show_moduleslider 		= ($this->params->get("showModuleslider", 1)  == 0)?"false":"true";
$moduleslider_location	= $this->params->get("modulesliderLocation", "mid-bottom");
$moduleslider_height	= $this->params->get("modulesliderHeight", "220");
$leftbanner_width		= $this->params->get("leftbannerWidth", "170");
$rightbanner_width		= $this->params->get("rightbannerWidth", "170");
$h3_style 				= $this->params->get("h3Style", "icon1");
$show_breadcrumbs 		= ($this->params->get("showBreadcrumbs", 0)  == 0)?"false":"true";
$show_logo		 		= ($this->params->get("showLogo", 1)  == 0)?"false":"true";
$show_textsizer		 	= ($this->params->get("showTextsizer", 1)  == 0)?"false":"true";
$show_bottomlogo 		= ($this->params->get("showbottomLogo", 1)  == 0)?"false":"true";
$js_compatibility	 	= ($this->params->get("jsCompatibility", 0)  == 0)?"false":"true";

// moomenu options
$moo_bgiframe     		= ($this->params->get("moo_bgiframe'","0") == 0)?"false":"true";
$moo_delay       		= $this->params->get("moo_delay", "500");
$moo_duration    		= $this->params->get("moo_duration", "600");
$moo_fps          		= $this->params->get("moo_fps", "200");
$moo_transition   		= $this->params->get("moo_transition", "Sine.easeOut");

$moo_bg_enabled			= ($this->params->get("moo_bg_enabled","0") == 0)?"false":"true";
$moo_bg_over_duration		= $this->params->get("moo_bg_over_duration", "100");
$moo_bg_over_transition		= $this->params->get("moo_bg_over_transition", "Expo.easeOut");
$moo_bg_out_duration		= $this->params->get("moo_bg_out_duration", "800");
$moo_bg_out_transition		= $this->params->get("moo_bg_out_transition", "Sine.easeOut");

$moo_sub_enabled		= ($this->params->get("moo_sub_enabled","0") == 0)?"false":"true";
$moo_sub_over_duration		= $this->params->get("moo_sub_over_duration", "50");
$moo_sub_over_transition	= $this->params->get("moo_sub_over_transition", "Expo.easeOut");
$moo_sub_out_duration		= $this->params->get("moo_sub_out_duration", "600");
$moo_sub_out_transition		= $this->params->get("moo_sub_out_transition", "Sine.easeIn");
$moo_sub_offsets_top		= $this->params->get("moo_sub_offsets_top", "5");
$moo_sub_offsets_right		= $this->params->get("moo_sub_offsets_right", "15");
$moo_sub_offsets_bottom		= $this->params->get("moo_sub_offsets_bottom", "5");
$moo_sub_offsets_left		= $this->params->get("moo_sub_offsets_left", "5");
	
	// module slider configuration
	
$max_mods_per_row			= $this->params->get("maxModsPerRow", 5);
$ms_title1					= $this->params->get("msTitle1", "Tab One");	
$ms_title2					= $this->params->get("msTitle2", "Tab Two");	
$ms_title3					= $this->params->get("msTitle3", "Tab Three");	
$ms_title4					= $this->params->get("msTitle4", "Tab Four");	
$ms_title5					= $this->params->get("msTitle5", "Tab Five");
$ms_title6					= $this->params->get("msTitle6", "Tab Six");
$ms_title7					= $this->params->get("msTitle7", "Tab Seven");
$ms_title8					= $this->params->get("msTitle8", "Tab Eight");
$ms_title9					= $this->params->get("msTitle9", "Tab Nine");
$ms_title10					= $this->params->get("msTitle10", "Tab Ten");	
$ms_module1					= $this->params->get("msModule1", "user11");		
$ms_module2					= $this->params->get("msModule2", "user12");			
$ms_module3					= $this->params->get("msModule3", "user13");			
$ms_module4					= $this->params->get("msModule4", "user14");			
$ms_module5					= $this->params->get("msModule5", "user15");
$ms_module6					= $this->params->get("msModule6", "user16");
$ms_module7					= $this->params->get("msModule7", "user17");
$ms_module8					= $this->params->get("msModule8", "user18");
$ms_module9					= $this->params->get("msModule9", "user19");
$ms_module10				= $this->params->get("msModule10", "user20");
								
	require(YOURBASEPATH . "/rt_styleloader.php");
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" >
	<head>
		<jdoc:include type="head" />
		<?php
		require(YOURBASEPATH."/rt_tabmodules.php");
		require( YOURBASEPATH."/rt_utils.php");
		require( YOURBASEPATH."/rt_head_includes.php");

	?>
	</head>
	<body id="ff-<?php echo $fontfamily; ?>" class="<?php echo $fontstyle; ?> <?php echo $tstyle; ?> <?php echo $mstyle; ?> <?php echo $h3style; ?> iehandle">
		<?php if($this->countModules('banner1')) : ?>
		<div id="topbanner"><div class="wrapper">
			<jdoc:include type="modules" name="banner1" style="xhtml" />
		</div></div>
		<?php endif; ?>
		<!-- Begin Header -->
		<?php if($hwrap == "true") : ?>
		<div class="wrapper">
		<?php endif; ?>
		<div id="header-bar">
			<?php if($hwrap == "false") : ?>
			<div class="wrapper">
				<?php endif; ?>
				<?php if($show_logo == "true") : ?>
				<a href="<?php echo $this->baseurl; ?>" class="nounder"><img src="<?php echo $template_path; ?>/images/blank.gif" border="0" alt="" id="logo" /></a>
				<?php elseif($show_logo =="false" and $this->countModules('icon')) : ?>
				<div class="logo-module">
					<jdoc:include type="modules" name="icon" style="xhtml" />
				</div>
				<?php endif; ?>
				<div id="headerblock-right"><div class="padding">
					<?php if ($show_textsizer=="true") : ?>
					<div id="accessibility"><div id="buttons">
						<a href="<?php echo JROUTE::_($thisurl . "fontstyle=f-larger"); ?>" title="Increase size" class="large"><span class="button">&nbsp;</span></a>
						<a href="<?php echo JROUTE::_($thisurl . "fontstyle=f-smaller"); ?>" title="Decrease size" class="small"><span class="button">&nbsp;</span></a>
					</div></div>
					<?php endif; ?>
					<?php if ($this->countModules('top')) : ?>
					<div id="top-menu">
						<jdoc:include type="modules" name="top" style="xhtml" />
					</div>
					<?php endif; ?>
					<?php if ($this->countModules('search')) : ?>
					<div id="searchmod">
						<jdoc:include type="modules" name="search" style="xhtml" />
					</div>
					<?php endif; ?>
					<div class="clr"></div>
				</div></div>
			<?php if($hwrap == "false") : ?>
			</div>
			<?php endif; ?>
		</div>
		<?php if($hwrap == "true") : ?>
		</div>
		<div class="wrapper">
		<?php endif; ?>
		<?php if($mtype == "splitmenu" and $subnav != "0") : ?>
		<div id="submenu-container">
		<?php endif; ?>
		<?php if($mtype != "none") : ?>
		<div id="horiz-menu" class="<?php echo $mtype; ?>">
			<?php if($hwrap == "false") : ?>
			<div class="wrapper">
			<?php endif; ?>
			<?php if($mtype != "module") : ?>
				<?php echo $topnav; ?>
			<?php else: ?>
				<jdoc:include type="modules" name="toolbar" style="none" />
			<?php endif; ?>
			<?php if($hwrap == "false") : ?>
			</div>
			<?php endif; ?>
		</div>
		<?php else: ?>
		<div id="header-divider"></div>
		<?php endif; ?>
		<?php if($mtype == "splitmenu" and $subnav != "0") : ?>
		<div id="sub-menu">
		<?php if($hwrap == "false") : ?>
			<div class="wrapper">
		<?php endif; ?>
			<?php echo $subnav; ?>
		<?php if($hwrap == "false") : ?>
		</div>
		<?php endif; ?>
		</div>
		<?php endif; ?>
		<?php if($mtype == "splitmenu" and $subnav != "0") : ?>
		</div>
		<?php endif; ?>
		<?php if($hwrap == "true") : ?>
		</div>
		<?php endif; ?>
		<?php if($mtype != "none") : ?>
		<div class="div-spacer"></div>
		<?php endif; ?>
		<!-- End Header -->
		<div class="wrapper">
		<?php if($this->countModules('banner3')) : ?>
		<div id="rightbanner"><div class="padding">
			<jdoc:include type="modules" name="banner3" style="xhtml" />
		</div></div>
		<?php endif; ?>
		<?php if($this->countModules('banner2')) : ?>
		<div id="leftbanner"><div class="padding">
			<jdoc:include type="modules" name="banner2" style="xhtml" />
		</div></div>
		<?php endif; ?>
		<!-- Begin Main Page -->
			<div id="mainblock">
				<?php if ($this->countModules('showcase')) : ?>
				<div class="showcase-module"><jdoc:include type="modules" name="showcase" style="xhtml" /></div>
				<?php endif; ?>
				<div id="main-body">
					<!-- Begin Right Column -->
					<?php if ($rightcolumn_width != 0) : ?>
					<div id="rightcol">
						<div class="main-height">
							<?php if ($sidenav and $splitmenu_col=="rightcol") : ?>
							<div class="sidenav-block">
								<?php if($hwrap == "false") : ?>
								<div class="sidenav-block-right">
								<?php endif; ?>
									<div class="sidenav-block-left">
									<?php echo $sidenav; ?>
									</div>
								<?php if($hwrap == "false") : ?>
								</div>
								<?php endif; ?>
							</div>
							<?php endif; ?>
							<?php if (showModuleSlider("right")) : ?>
							<div id="moduleslider-size">
    							<?php displayTabs($this); ?>
    						</div>
    						<?php endif; ?>
							<jdoc:include type="modules" name="right" style="xhtml" />
						</div>
					</div>
					<?php endif; ?>
					<!-- End Right Column -->
					<!-- Begin Left Column -->
					<?php if ($leftcolumn_width != 0) : ?>
					<div id="leftcol">
						<div class="main-height">
							<?php if ($sidenav and $splitmenu_col=="leftcol") : ?>
							<div class="sidenav-block">
								<?php if($hwrap == "false") : ?>
								<div class="sidenav-block-right">
								<?php endif; ?>
									<div class="sidenav-block-left">
									<?php echo $sidenav; ?>
									</div>
								<?php if($hwrap == "false") : ?>
								</div>
								<?php endif; ?>
							</div>
							<?php endif; ?>
							<?php if (showModuleSlider("left")) : ?>
							<div id="moduleslider-size">
    							<?php displayTabs($this); ?>
    						</div>
    						<?php endif; ?>
							<jdoc:include type="modules" name="left" style="xhtml" />
						</div>
					</div>
					<?php endif; ?>
					<!-- End Left Column -->
					<!-- Begin Main Column -->
					<div id="maincol">
						<div class="main-height">
							<?php if ($show_breadcrumbs == "true") : ?>
							<div id="pathway">
								<jdoc:include type="modules" name="breadcrumbs" style="xhtml" />
							</div>
							<?php endif; ?>
							<?php if ($this->countModules('showcase2')) : ?>
							<div class="showcase-module2">
								<jdoc:include type="modules" name="showcase2" style="xhtml" />
							</div>
							<?php endif; ?>
							<?php $mClasses = modulesClasses('case1'); if ($this->countModules('user1') or $this->countModules('user2') or $this->countModules('user3')) : ?>
							<div id="mainmodules" class="spacer<?php echo $mainmod_width; ?>">
								<?php if ($this->countModules('user1')) : ?>
								<div class="block <?php echo $mClasses['user1'][0]; ?>" style="width: <?php echo $mClasses['user1'][1]; ?>px">
									<jdoc:include type="modules" name="user1" style="rounded" />
								</div>
								<?php endif; ?>
								<?php if ($this->countModules('user2')) : ?>
								<div class="block <?php echo $mClasses['user2'][0]; ?>" style="width: <?php echo $mClasses['user2'][1]; ?>px">
									<jdoc:include type="modules" name="user2" style="rounded" />
								</div>
								<?php endif; ?>
								<?php if ($this->countModules('user3')) : ?>
								<div class="block <?php echo $mClasses['user3'][0]; ?>" style="width: <?php echo $mClasses['user3'][1]; ?>px">
									<jdoc:include type="modules" name="user3" style="rounded" />
								</div>
								<?php endif; ?>
							</div>
							<?php endif; ?>
							<?php if ($this->countModules('banner')) : ?>
							<div class="banner-module">
								<jdoc:include type="modules" name="banner" style="xhtml" />
							</div>
							<?php endif; ?>
								<?php if (showModuleSlider("mid-top")) : ?>
								<div id="moduleslider-size">
	    							<?php displayTabs($this); ?>
	    						</div>
	    						<?php endif; ?>
							<div class="maincontent-surround"><div class="padding">
								<?php if ($this->countModules('inset2')) : ?>
								<div id="inset-block-right"><div class="right-padding">
									<jdoc:include type="modules" name="inset2" style="xhtml" />
								</div></div>
								<?php endif; ?>
								<?php if ($this->countModules('inset')) : ?>
								<div id="inset-block-left"><div class="left-padding">
									<jdoc:include type="modules" name="inset" style="xhtml" />
								</div></div>
								<?php endif; ?>
								<div id="maincontent-block">
									<jdoc:include type="message" />
									<jdoc:include type="component" />
								</div>
							</div></div>
							<?php if (showModuleSlider("mid-bottom")) : ?>
							<div id="moduleslider-size" class="alt">
    							<?php displayTabs($this); ?>
    						</div>
    						<?php endif; ?>
							<?php if ($this->countModules('banner4')) : ?>
							<div class="banner-module2">
								<jdoc:include type="modules" name="banner4" style="xhtml" />
							</div>
							<?php endif; ?>
							<?php $mClasses = modulesClasses('case2'); if ($this->countModules('user4') or $this->countModules('user5') or $this->countModules('user6')) : ?>
							<div id="mainmodules2" class="spacer<?php echo $mainmod2_width; ?>">
								<?php if ($this->countModules('user4')) : ?>
								<div class="block <?php echo $mClasses['user4'][0]; ?>" style="width: <?php echo $mClasses['user4'][1]; ?>px">
									<jdoc:include type="modules" name="user4" style="rounded" />
								</div>
								<?php endif; ?>
								<?php if ($this->countModules('user5')) : ?>
								<div class="block <?php echo $mClasses['user5'][0]; ?>" style="width: <?php echo $mClasses['user5'][1]; ?>px">
									<jdoc:include type="modules" name="user5" style="rounded" />
								</div>
								<?php endif; ?>
								<?php if ($this->countModules('user6')) : ?>
								<div class="block <?php echo $mClasses['user6'][0]; ?>" style="width: <?php echo $mClasses['user6'][1]; ?>px">
									<jdoc:include type="modules" name="user6" style="rounded" />
								</div>
								<?php endif; ?>
							</div>
							<?php endif; ?>
						</div>
					</div>
					<!-- End Main Column -->
					<div class="clr"></div>
				</div>
		<!-- End Main Page -->
		<?php if (showModuleSlider("bottom")) : ?>
		<div id="moduleslider-size" class="alt-bottom">
			<?php displayTabs($this); ?>
		</div>
		<?php endif; ?>
		<?php if ($this->countModules('advert1') or $this->countModules('advert2') or $this->countModules('advert3')) : ?>
		<div id="mainmodules3" class="spacer<?php echo $mainmod3_width; ?>">
			<?php if ($this->countModules('advert1')) : ?>
			<div class="block">
				<jdoc:include type="modules" name="advert1" style="rounded" />
			</div>
			<?php endif; ?>
			<?php if ($this->countModules('advert2')) : ?>
			<div class="block">
				<jdoc:include type="modules" name="advert2" style="rounded" />
			</div>
			<?php endif; ?>
			<?php if ($this->countModules('advert3')) : ?>
			<div class="block">
				<jdoc:include type="modules" name="advert3" style="rounded" />
			</div>
			<?php endif; ?>
		</div>
		<?php endif; ?>
		<?php if ($this->countModules('advert4') or $this->countModules('advert5') or $this->countModules('advert6')) : ?>
		<div id="mainmodules4" class="spacer<?php echo $mainmod4_width; ?>">
			<?php if ($this->countModules('advert4')) : ?>
			<div class="block">
				<jdoc:include type="modules" name="advert4" style="rounded" />
			</div>
			<?php endif; ?>
			<?php if ($this->countModules('advert5')) : ?>
			<div class="block">
				<jdoc:include type="modules" name="advert5" style="rounded" />
			</div>
			<?php endif; ?>
			<?php if ($this->countModules('advert6')) : ?>
			<div class="block">
				<jdoc:include type="modules" name="advert6" style="rounded" />
			</div>
			<?php endif; ?>
		</div>
		<?php endif; ?>
		</div>
		<?php if ($this->countModules('user7')=="0" and $this->countModules('user8')=="0" and $this->countModules('user9')=="0" and $this->countModules('user10')=="0" and $this->countModules('footer')=="0" and $this->countModules('bottom')=="0" and $this->countModules('debug')=="0" and $show_bottomlogo == "true") : ?>
			<div class="rocket-block">
				<a href="http://www.rockettheme.com/" title="RocketTheme Joomla Template Club" class="nounder"><img src="<?php echo $template_path; ?>/images/blank.gif" alt="RocketTheme Joomla Templates" id="rocket2" /></a>
			</div>
		<?php endif; ?>
		</div>
		<!-- Begin Bottom Section -->
		<?php if($fwrap == "true") : ?>
		<div class="wrapper">
		<?php endif; ?>
		<?php if ($this->countModules('bottom')) : ?>
		<div id="bottom-menu"><div class="wrapper">
			<jdoc:include type="modules" name="bottom" style="xhtml" />
		</div></div>
		<?php endif; ?>
		<?php if ($this->countModules('user7') or $this->countModules('user8') or $this->countModules('user9') or $this->countModules('user10') or $this->countModules('footer') or $this->countModules('debug')) : ?>
		<div id="bottom">
			<?php if($fwrap == "false") : ?>
			<div class="wrapper">
			<?php endif; ?>
				<div id="bottommodules" class="spacer<?php echo $bottommod_width; ?>">
					<?php if ($this->countModules('user7')) : ?>
					<div class="block">
						<jdoc:include type="modules" name="user7" style="rounded" />
					</div>
					<?php endif; ?>
					<?php if ($this->countModules('user8')) : ?>
					<div class="block">
						<jdoc:include type="modules" name="user8" style="rounded" />
					</div>
					<?php endif; ?>
					<?php if ($this->countModules('user9')) : ?>
					<div class="block">
						<jdoc:include type="modules" name="user9" style="rounded" />
					</div>
					<?php endif; ?>
					<?php if ($this->countModules('user10')) : ?>
					<div class="block">
						<jdoc:include type="modules" name="user10" style="rounded" />
					</div>
					<?php endif; ?>
				</div>
				<?php if ($this->countModules('footer')) : ?>
				<div class="footer-module">
					<jdoc:include type="modules" name="footer" style="xhtml" />
				</div>
				<?php endif; ?>
				<?php if ($this->countModules('debug')) : ?>
					<jdoc:include type="modules" name="debug" style="none" />
				<?php endif; ?>
				<?php if($show_bottomlogo == "true") : ?>
				<div class="rocket-block">
					<a href="http://www.rockettheme.com/" title="RocketTheme Joomla Template Club" class="nounder"><img src="<?php echo $template_path; ?>/images/blank.gif" alt="RocketTheme Joomla Templates" id="rocket" /></a>
				</div>
				<?php endif; ?>
			<?php if($fwrap == "false") : ?>
			</div>
			<?php endif; ?>
		</div>
		<?php if($fwrap == "true") : ?>
		</div>
		<?php endif; ?>
		<?php endif; ?>
		<!-- End Bottom Section -->
	</body>
</html>