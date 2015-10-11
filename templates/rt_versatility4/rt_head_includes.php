<?php
/**
 * @package   Versatility4 Template - RocketTheme
* @version   $Id: rt_head_includes.php 28375 2015-04-25 04:47:44Z reggie $
* @author    RocketTheme, LLC http://www.rockettheme.com
* @copyright Copyright (C) 2007 - 2015 RocketTheme, LLC
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 *
 * Rockettheme Versatility4 Template uses the Joomla Framework (http://www.joomla.org), a GNU/GPLv2 content management system
 *
 */
// This information has been pulled out of index.php to make the template more readible.
//
// This data goes between the <head></head> tags of the template

$app = JFactory::getApplication();
$jversion = new JVersion;

if ((($app->input->getString('layout') == 'edit') or ($app->input->getString('controller') == 'config.display.modules')) and ($jversion->RELEASE >= "3.2")) :
    echo '<link rel="stylesheet" href="'.$this->baseurl.'/media/jui/css/bootstrap.min.css" type="text/css" />';
    echo '<link rel="stylesheet" href="'.$this->baseurl.'/media/jui/css/icomoon.css" type="text/css" />';
    echo '<link rel="stylesheet" href="'.$this->baseurl.'/media/jui/css/bootstrap-extended.css" type="text/css" />';
    echo '<link rel="stylesheet" href="'.$this->baseurl.'/templates/'.$this->template.'/css/joomla3.css" type="text/css" />';
endif;

?>

<link rel="shortcut icon" href="<?php echo $this->baseurl; ?>/images/favicon.ico" />
<link href="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template?>/css/template.css" rel="stylesheet" type="text/css" />
<link href="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template?>/css/<?php echo $mstyle; ?>.css" rel="stylesheet" type="text/css" />
<link href="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template?>/css/<?php echo $tstyle; ?>.css" rel="stylesheet" type="text/css" />
<link href="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template?>/css/typography.css" rel="stylesheet" type="text/css" />
<link href="<?php echo $this->baseurl ?>/templates/system/css/system.css" rel="stylesheet" type="text/css" />
<link href="<?php echo $this->baseurl ?>/templates/system/css/general.css" rel="stylesheet" type="text/css" />
<?php if($show_moduleslider=="true" and $js_compatibility=="false") : ?>
<link href="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template?>/css/rokslidestrip.css" rel="stylesheet" type="text/css" />
<?php endif; ?>
<?php if($mtype=="moomenu" or $mtype=="suckerfish") :?>
<link href="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template?>/css/rokmoomenu.css" rel="stylesheet" type="text/css" />
<?php endif; ?>
<style type="text/css">
	div.wrapper { <?php echo $template_width; ?>padding:0;}
	#leftcol { width:<?php echo $leftcolumn_width; ?>px;padding:0;}
	#rightcol { width:<?php echo $rightcolumn_width; ?>px;padding:0;}
	#maincol { margin-left:<?php echo $leftcolumn_width; ?>px;margin-right:<?php echo $rightcolumn_width; ?>px;padding:0;}
	#mainblock {margin-left:<?php echo $leftbanner_width; ?>px;margin-right:<?php echo $rightbanner_width; ?>px;padding:0;}
	#leftbanner { width:<?php echo $leftbanner_width; ?>px;padding:0;}
	#rightbanner { width:<?php echo $rightbanner_width; ?>px;padding:0;}
	#moduleslider-size { height:<?php echo $moduleslider_height; ?>px;}
	#inset-block-left { width:<?php echo $leftinset_width; ?>px;padding:0;}
	#inset-block-right { width:<?php echo $rightinset_width; ?>px;padding:0;}
	#maincontent-block { margin-right:<?php echo $rightinset_width; ?>px;margin-left:<?php echo $leftinset_width; ?>px;padding:0;}
</style>	
<?php if (rok_isIe()) :?>
<!--[if IE 7]>
<link href="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template?>/css/template_ie7.css" rel="stylesheet" type="text/css" />	
<![endif]-->	
<?php endif; ?>
<?php if (rok_isIe(6)) :?>
<!--[if lte IE 6]>
<link href="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template?>/css/template_ie6.php" rel="stylesheet" type="text/css" />
<![endif]-->
<?php endif; ?>
<script type="text/javascript" src="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template?>/js/roksameheight.js"></script>   
<?php if($show_moduleslider=="true" and $js_compatibility=="false"):?>
<script type="text/javascript" src="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template?>/js/rokslidestrip.js"></script>
<?php endif; ?>
<?php if($enable_fontspans=="true" and $js_compatibility=="false") :?>
<script type="text/javascript" src="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template?>/js/rokfonts.js"></script>
<script type="text/javascript">
	window.addEvent('domready', function() {
		var modules = ['module','moduletable', 'module-menu', 'module-hilite1', 'moduletable-hilite1', 'module-hilite2', 'moduletable-hilite2', 'module-hilite3', 'moduletable-hilite3', 'module-hilite4', 'moduletable-hilite4', 'module-hilite5', 'moduletable-hilite5', 'module-hilite6', 'moduletable-hilite6', 'module-hilite7', 'moduletable-hilite7', 'module-hilite8', 'moduletable-hilite8', 'module-hilite9', 'moduletable-hilite9', 'module-clean', 'moduletable-clean', 'submenu-block', 'moduletable_menu'];
		var header = "h3";
		RokBuildSpans(modules, header);
	});
</script>
<?php endif; ?>
<?php if($mtype=="moomenu" and $js_compatibility=="false") :?>
<script type="text/javascript" src="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template?>/js/rokmoomenu.js"></script>
<script type="text/javascript" src="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template?>/js/mootools.bgiframe.js"></script>
<script type="text/javascript">
window.addEvent('domready', function() {
	new Rokmoomenu('ul.menutop ', {
		bgiframe: <?php echo $moo_bgiframe; ?>,
		delay: <?php echo $moo_delay; ?>,
		animate: {
			props: ['height', 'opacity'],
			opts: {
				duration: <?php echo $moo_duration; ?>,
				fps: <?php echo $moo_fps; ?>,
				transition: Fx.Transitions.<?php echo $moo_transition; ?>
			}
		},
		bg: {
			enabled: <?php echo $moo_bg_enabled; ?>,
			overEffect: {
				duration: <?php echo $moo_bg_over_duration; ?>,
				transition: Fx.Transitions.<?php echo $moo_bg_over_transition; ?>
			},
			outEffect: {
				duration: <?php echo $moo_bg_out_duration; ?>,
				transition: Fx.Transitions.<?php echo $moo_bg_out_transition; ?>
			}
		},
		submenus: {
			enabled: <?php echo $moo_sub_enabled; ?>,
			overEffect: {
				duration: <?php echo $moo_sub_over_duration; ?>,
				transition: Fx.Transitions.<?php echo $moo_sub_over_transition; ?>
			},
			outEffect: {
				duration: <?php echo $moo_sub_out_duration; ?>,
				transition: Fx.Transitions.<?php echo $moo_sub_out_transition; ?>
			},
			offsets: {
				top: <?php echo $moo_sub_offsets_top; ?>,
				right: <?php echo $moo_sub_offsets_right; ?>,
				bottom: <?php echo $moo_sub_offsets_bottom; ?>,
				left: <?php echo $moo_sub_offsets_left; ?>
			}
		}
	});
});
</script>
<?php endif; ?>
<?php if((rok_isIe(6) or rok_isIe(7)) and ($mtype=="suckerfish" or $mtype=="splitmenu")) :
  echo "<script type=\"text/javascript\" src=\"" . $this->baseurl . "/templates/" . $this->template . "/js/ie_suckerfish.js\"></script>\n";
endif; ?>