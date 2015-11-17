<?php
/**
 * @package     Joomla.Site
 * @subpackage  Templates.protostar
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

$app             = JFactory::getApplication();
$doc             = JFactory::getDocument();
$user            = JFactory::getUser();
$this->language  = $doc->language;
$this->direction = $doc->direction;

// Getting params from template
$params = $app->getTemplate(true)->params;

// Detecting Active Variables
$option   = $app->input->getCmd('option', '');
$view     = $app->input->getCmd('view', '');
$layout   = $app->input->getCmd('layout', '');
$task     = $app->input->getCmd('task', '');
$itemid   = $app->input->getCmd('Itemid', '');
$sitename = $app->get('sitename');

if($task == "edit" || $layout == "form" )
{
	$fullWidth = 1;
}
else
{
	$fullWidth = 0;
}

// Add JavaScript Frameworks
JHtml::_('bootstrap.framework');
$doc->addScript($this->baseurl . '/templates/' . $this->template . '/js/template.js');


JHtml::_('jquery.framework');

$doc->addScriptDeclaration('
	(function ($) {

	 	$(document).ready(function(){
			$("#front-main").css({ height: $(window).innerHeight() -250 });
			  $(window).resize(function(){
			    $("#front-main").css({ height: $(window).innerHeight() -250 });
			  });
		     
		     
	 	});
		
		

	})(jQuery);
	
');

// Add Stylesheets
$doc->addStyleSheet($this->baseurl . '/templates/' . $this->template . '/css/template.css');
$doc->addStyleSheet('templates/'.$this->template.'/css/custom.css');

// Load optional RTL Bootstrap CSS
JHtml::_('bootstrap.loadCss', false, $this->direction);

// Adjusting content width
if ($this->countModules('position-7') && $this->countModules('position-8'))
{
	$span = "span6";
}
elseif ($this->countModules('position-7') && !$this->countModules('position-8'))
{
	$span = "span9";
}
elseif (!$this->countModules('position-7') && $this->countModules('position-8'))
{
	$span = "span9";
}
else
{
	$span = "span12";
}

// Logo file or site title param
if ($this->params->get('logoFile'))
{
	$logo = '<img src="' . JUri::root() . $this->params->get('logoFile') . '" alt="' . $sitename . '" />';
}
elseif ($this->params->get('sitetitle'))
{
	$logo = '<span class="site-title" title="' . $sitename . '">' . htmlspecialchars($this->params->get('sitetitle')) . '</span>';
}
else
{
	$logo = '<span class="site-title" title="' . $sitename . '">' . $sitename . '</span>';
}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<jdoc:include type="head" />
	<?php // Use of Google Font ?>
	<?php if ($this->params->get('googleFont')) : ?>
		<link href='//fonts.googleapis.com/css?family=<?php echo $this->params->get('googleFontName'); ?>' rel='stylesheet' type='text/css' />
		<style type="text/css">
			h1,h2,h3,h4,h5,h6,.site-title{
				font-family: '<?php echo str_replace('+', ' ', $this->params->get('googleFontName')); ?>', sans-serif;
			}
		</style>
	<?php endif; ?>
	<?php // Template color ?>
	<?php if ($this->params->get('templateColor')) : ?>
	<style type="text/css">
		body.site
		{
			border-top: 3px solid <?php echo $this->params->get('templateColor'); ?>;
			background-color: <?php echo $this->params->get('templateBackgroundColor'); ?>
		}
		a
		{
			color: <?php echo $this->params->get('templateColor'); ?>;
		}
		.navbar-inner, .nav-list > .active > a, .nav-list > .active > a:hover, .dropdown-menu li > a:hover, .dropdown-menu .active > a, .dropdown-menu .active > a:hover, .nav-pills > .active > a, .nav-pills > .active > a:hover,
		.btn-primary
		{
			background: <?php echo $this->params->get('templateColor'); ?>;
		}
		.navbar-inner
		{
			-moz-box-shadow: 0 1px 3px rgba(0, 0, 0, .25), inset 0 -1px 0 rgba(0, 0, 0, .1), inset 0 30px 10px rgba(0, 0, 0, .2);
			-webkit-box-shadow: 0 1px 3px rgba(0, 0, 0, .25), inset 0 -1px 0 rgba(0, 0, 0, .1), inset 0 30px 10px rgba(0, 0, 0, .2);
			box-shadow: 0 1px 3px rgba(0, 0, 0, .25), inset 0 -1px 0 rgba(0, 0, 0, .1), inset 0 30px 10px rgba(0, 0, 0, .2);
		}
	</style>
	<?php endif; ?>
	<!--[if lt IE 9]>
		<script src="<?php echo JUri::root(true); ?>/media/jui/js/html5.js"></script>
	<![endif]-->
		
</head>

<body class="site <?php echo $option
	. ' view-' . $view
	. ($layout ? ' layout-' . $layout : ' no-layout')
	. ($task ? ' task-' . $task : ' no-task')
	. ($itemid ? ' itemid-' . $itemid : '')
	. ($params->get('fluidContainer') ? ' fluid' : '');
	echo ($this->direction == 'rtl' ? ' rtl' : '');
?>">
<?php $current_url = JURI::current();
			$base_url = JURI::base(); ?>
	<!-- Body -->
	<div class="body">
		<div class="container<?php echo ($params->get('fluidContainer') ? '-fluid' : ''); ?> <?php echo ($base_url == $current_url) ? 'front-display' : 'inside-display'; ?>">
			<!-- Header -->
			<header class="header" role="banner">
				<div class="header-inner clearfix">
					<a class="brand pull-left" href="<?php echo $this->baseurl; ?>/">
						<?php echo $logo; ?>
						<?php if ($this->params->get('sitedescription')) : ?>
							<?php echo '<div class="site-description">' . htmlspecialchars($this->params->get('sitedescription')) . '</div>'; ?>
						<?php endif; ?>
					</a>
					
				</div>
			</header>
		
			<?php if ($this->countModules('position-1')) : ?>
				<nav class="navigation" role="navigation">
					<div class="navbar">
						<jdoc:include type="modules" name="position-1" style="none" />
						<div class="header-search">
							<jdoc:include type="modules" name="position-0" style="none" />
						</div>
					</div>
					
				</nav>
			<?php endif; ?>
			
			<?php 
			
			//var_dump($test); echo '<br>'; var_dump($test2);
			if ($base_url == $current_url) { 
				
			$mod1 = "display1a";
			$mod2 = "display1b";
			$mod3 = "display1c";
			$mod4 = "display1d";
			
			if ($this->countModules($mod1) && $this->countModules($mod2) && $this->countModules($mod3) && $this->countModules($mod4)) : 
				$modcount[1] = 4;
			elseif ($this->countModules($mod1) && $this->countModules($mod2) && $this->countModules($mod3)) : 
				$modcount[1] = 3;
			elseif ($this->countModules($mod1) && $this->countModules($mod2)) : 
				$modcount[1] = 2;
			elseif ($this->countModules($mod1)) : 
				$modcount[1] = 1;
			endif;	
				
			?>
			
			<div id="front-main">
				<div id="display-main-content">
					<jdoc:include type="modules" name="display-main" style="xhtml" />
				</div>
				<div class="display1-<?= $modcount[1] ?>">
					<div id="display">
						<?php if ($this->countModules('display1a')) { ?>
							<div id="display1a" class="displaybox">
								<div id="mod-container">
									<jdoc:include type="modules" name="display1a" style="none" />
								</div>
							</div>
						<?php } ?>
						<?php if ($this->countModules('display1b')) { ?>
							<div id="display1b" class="displaybox">
								<div id="mod-container">
									<jdoc:include type="modules" name="display1b" style="none" />
								</div>
							</div>
						<?php } ?>
						<?php if ($this->countModules('display1c')) { ?>
							<div id="display1c" class="displaybox">
								<div id="mod-container">
									<jdoc:include type="modules" name="display1c" style="none" />
								</div>
							</div>
						<?php } ?>
						<?php if ($this->countModules('display1d')) { ?>
							<div id="display1d" class="displaybox">
								<div id="mod-container">
									<jdoc:include type="modules" name="display1d" style="none" />
								</div>
							</div>
						<?php } ?>
						<?php if ($this->countModules('display2')) { ?>
							<div id="display2" class="displaybox">
								<div id="mod-container">
									<jdoc:include type="modules" name="display2" style="none" />
								</div>
							</div>
						<?php } ?>
					</div>
				</div>
			</div>
			
			<?php } ?>
			
		</div>	
		
		<?php 
		
		if ($base_url == $current_url) { 
		
		$pos = array('1','2','3','4');
		
		foreach ($pos as $p) {
			
			$mod1 = "front" . $p . "a";
			$mod2 = "front" . $p . "b";
			$mod3 = "front" . $p . "c";
			$mod4 = "front" . $p . "d";
			
			if ($this->countModules($mod1) && $this->countModules($mod2) && $this->countModules($mod3) && $this->countModules($mod4)) : 
				$modcount[$p] = 4;
			elseif ($this->countModules($mod1) && $this->countModules($mod2) && $this->countModules($mod3)) : 
				$modcount[$p] = 3;
			elseif ($this->countModules($mod1) && $this->countModules($mod2)) : 
				$modcount[$p] = 2;
			elseif ($this->countModules($mod1)) : 
				$modcount[$p] = 1;
			endif;
		}
		
		 ?> 
		
		<div class="container<?php echo ($params->get('fluidContainer') ? '-fluid' : ''); ?> front1-<?= $modcount[1] ?>">
			<div id="front1">
				<?php if ($this->countModules('front1a')) { ?>
					<div id="front1a" class="frontbox">
						<div id="mod-container">
							<jdoc:include type="modules" name="front1a" style="none" />
						</div>
					</div>
				<?php } ?>
				<?php if ($this->countModules('front1b')) { ?>
					<div id="front1b" class="frontbox">
						<div id="mod-container">
							<jdoc:include type="modules" name="front1b" style="none" />
						</div>
					</div>
				<?php } ?>
				<?php if ($this->countModules('front1c')) { ?>
					<div id="front1c" class="frontbox">
						<div id="mod-container">
							<jdoc:include type="modules" name="front1c" style="none" />
						</div>
					</div>
				<?php } ?>
				<?php if ($this->countModules('front1d')) { ?>
					<div id="front1d" class="frontbox">
						<div id="mod-container">
							<jdoc:include type="modules" name="front1d" style="none" />
						</div>
					</div>
				<?php } ?>
			</div>
		</div>	
		
		<div class="container<?php echo ($params->get('fluidContainer') ? '-fluid' : ''); ?> front2-<?= $modcount[2] ?>">
			<div id="front2">
				<?php if ($this->countModules('front2a')) { ?>
					<div id="front2a" class="frontbox">
						<div id="mod-container">
							<jdoc:include type="modules" name="front2a" style="none" />
						</div>
					</div>
				<?php } ?>
				<?php if ($this->countModules('front2b')) { ?>
					<div id="front2b" class="frontbox">
						<div id="mod-container">
							<jdoc:include type="modules" name="front2b" style="none" />
						</div>
					</div>
				<?php } ?>
				<?php if ($this->countModules('front2c')) { ?>
					<div id="front2c" class="frontbox">
						<div id="mod-container">
							<jdoc:include type="modules" name="front2c" style="none" />
						</div>
					</div>
				<?php } ?>
				<?php if ($this->countModules('front2d')) { ?>
					<div id="front2d" class="frontbox">
						<div id="mod-container">
							<jdoc:include type="modules" name="front2d" style="none" />
						</div>
					</div>
				<?php } ?>
			</div>
		</div>
		
		<div class="container<?php echo ($params->get('fluidContainer') ? '-fluid' : ''); ?> front3-<?= $modcount[3] ?>">
			<div id="front3">
				<?php if ($this->countModules('front3a')) { ?>
					<div id="front3a" class="frontbox">
						<div id="mod-container">
							<jdoc:include type="modules" name="front3a" style="none" />
						</div>
					</div>
				<?php } ?>
				<?php if ($this->countModules('front3b')) { ?>
					<div id="front3b" class="frontbox">
						<div id="mod-container">
							<jdoc:include type="modules" name="front3b" style="none" />
						</div>
					</div>
				<?php } ?>
				<?php if ($this->countModules('front3c')) { ?>
					<div id="front3c" class="frontbox">
						<div id="mod-container">
							<jdoc:include type="modules" name="front3c" style="none" />
						</div>
					</div>
				<?php } ?>
				<?php if ($this->countModules('front3d')) { ?>
					<div id="front3d" class="frontbox">
						<div id="mod-container">
							<jdoc:include type="modules" name="front3d" style="none" />
						</div>
					</div>
				<?php } ?>
			</div>
		</div>
		
		<div class="container<?php echo ($params->get('fluidContainer') ? '-fluid' : ''); ?> front4-<?= $modcount[4] ?>">
			<div id="front4">
				<?php if ($this->countModules('front4a')) { ?>
					<div id="front4a" class="frontbox">
						<div id="mod-container">
							<jdoc:include type="modules" name="front4a" style="none" />
						</div>
					</div>
				<?php } ?>
				<?php if ($this->countModules('front4b')) { ?>
					<div id="front4b" class="frontbox">
						<div id="mod-container">
							<jdoc:include type="modules" name="front4b" style="none" />
						</div>
					</div>
				<?php } ?>
				<?php if ($this->countModules('front4c')) { ?>
					<div id="front4c" class="frontbox">
						<div id="mod-container">
							<jdoc:include type="modules" name="front4c" style="none" />
						</div>
					</div>
				<?php } ?>
				<?php if ($this->countModules('front4d')) { ?>
					<div id="front4d" class="frontbox">
						<div id="mod-container">
							<jdoc:include type="modules" name="front4d" style="none" />
						</div>
					</div>
				<?php } ?>
			</div>
		</div>
		<?php } ?>
		
		<?php
		
		if ($base_url != $current_url) { ?>

		<div class="container<?php echo ($params->get('fluidContainer') ? '-fluid' : ''); ?> main-content">
			
			<div class="row-fluid">
				<?php if ($this->countModules('position-8')) : ?>
					<!-- Begin Sidebar -->
					<div id="sidebar" class="span3">
						<div class="sidebar-nav">
							<jdoc:include type="modules" name="position-8" style="xhtml" />
						</div>
					</div>
					<!-- End Sidebar -->
				<?php endif; ?>
				<main id="content" role="main" class="<?php echo $span; ?>">
					<!-- Begin Content -->
					<jdoc:include type="modules" name="position-3" style="xhtml" />
					<jdoc:include type="message" />
					<jdoc:include type="component" />
					<jdoc:include type="modules" name="position-2" style="none" />
					<!-- End Content -->
				</main>
				<?php if ($this->countModules('position-7')) : ?>
					<div id="aside" class="span3">
						<!-- Begin Right Sidebar -->
						<jdoc:include type="modules" name="position-7" style="well" />
						<!-- End Right Sidebar -->
					</div>
				<?php endif; ?>
			</div>
		</div>
		<?php } ?>
		
		<?php 
		
		$botpos = array('1','2','3','4');
		
		foreach ($botpos as $p) {
			
			$mod1 = "bottom" . $p . "a";
			$mod2 = "bottom" . $p . "b";
			$mod3 = "bottom" . $p . "c";
			$mod4 = "bottom" . $p . "d";
			
			if ($this->countModules($mod1) && $this->countModules($mod2) && $this->countModules($mod3) && $this->countModules($mod4)) : 
				$modcount[$p] = 4;
			elseif ($this->countModules($mod1) && $this->countModules($mod2) && $this->countModules($mod3)) : 
				$modcount[$p] = 3;
			elseif ($this->countModules($mod1) && $this->countModules($mod2)) : 
				$modcount[$p] = 2;
			elseif ($this->countModules($mod1)) : 
				$modcount[$p] = 1;
			endif;
		}
		
		 ?>
		
		<div class="container<?php echo ($params->get('fluidContainer') ? '-fluid' : ''); ?> bottom1-<?= $modcount[1] ?>">
			<div id="bottom1">
				<?php if ($this->countModules('bottom1a')) { ?>
					<div id="bottom1a" class="bottombox">
						<div id="mod-container">
							<jdoc:include type="modules" name="bottom1a" style="none" />
						</div>
					</div>
				<?php } ?>
				<?php if ($this->countModules('bottom1b')) { ?>
					<div id="bottom1b" class="bottombox">
						<div id="mod-container">
							<jdoc:include type="modules" name="bottom1b" style="none" />
						</div>
					</div>
				<?php } ?>
				<?php if ($this->countModules('bottom1c')) { ?>
					<div id="bottom1c" class="bottombox">
						<div id="mod-container">
							<jdoc:include type="modules" name="bottom1c" style="none" />
						</div>
					</div>
				<?php } ?>
				<?php if ($this->countModules('bottom1d')) { ?>
					<div id="bottom1d" class="bottombox">
						<div id="mod-container">
							<jdoc:include type="modules" name="bottom1d" style="none" />
						</div>
					</div>
				<?php } ?>
			</div>
		</div>	
		
		<div class="container<?php echo ($params->get('fluidContainer') ? '-fluid' : ''); ?> bottom2-<?= $modcount[2] ?>">
			<div id="bottom2">
				<?php if ($this->countModules('bottom2a')) { ?>
					<div id="bottom2a" class="bottombox">
						<div id="mod-container">
							<jdoc:include type="modules" name="bottom2a" style="none" />
						</div>
					</div>
				<?php } ?>
				<?php if ($this->countModules('bottom2b')) { ?>
					<div id="bottom2b" class="bottombox">
						<div id="mod-container">
							<jdoc:include type="modules" name="bottom2b" style="none" />
						</div>
					</div>
				<?php } ?>
				<?php if ($this->countModules('bottom2c')) { ?>
					<div id="bottom2c" class="bottombox">
						<div id="mod-container">
							<jdoc:include type="modules" name="bottom2c" style="none" />
						</div>
					</div>
				<?php } ?>
				<?php if ($this->countModules('bottom2d')) { ?>
					<div id="bottom2d" class="bottombox">
						<div id="mod-container">
							<jdoc:include type="modules" name="bottom2d" style="none" />
						</div>
					</div>
				<?php } ?>
			</div>
		</div>
		
		<div class="container<?php echo ($params->get('fluidContainer') ? '-fluid' : ''); ?> bottom3-<?= $modcount[3] ?>">
			<div id="bottom3">
				<?php if ($this->countModules('bottom3a')) { ?>
					<div id="bottom3a" class="bottombox">
						<div id="mod-container">
							<jdoc:include type="modules" name="bottom3a" style="none" />
						</div>
					</div>
				<?php } ?>
				<?php if ($this->countModules('bottom3b')) { ?>
					<div id="bottom3b" class="bottombox">
						<div id="mod-container">
							<jdoc:include type="modules" name="bottom3b" style="none" />
						</div>
					</div>
				<?php } ?>
				<?php if ($this->countModules('bottom3c')) { ?>
					<div id="bottom3c" class="bottombox">
						<div id="mod-container">
							<jdoc:include type="modules" name="bottom3c" style="none" />
						</div>
					</div>
				<?php } ?>
				<?php if ($this->countModules('bottom3d')) { ?>
					<div id="bottom3d" class="bottombox">
						<div id="mod-container">
							<jdoc:include type="modules" name="bottom3d" style="none" />
						</div>
					</div>
				<?php } ?>
			</div>
		</div>
		
		<div class="container<?php echo ($params->get('fluidContainer') ? '-fluid' : ''); ?> bottom4-<?= $modcount ?>">
			<div id="bottom4">
				<?php if ($this->countModules('bottom4a')) { ?>
					<div id="bottom4a" class="bottombox">
						<div id="mod-container">
							<jdoc:include type="modules" name="bottom4a" style="none" />
						</div>
					</div>
				<?php } ?>
				<?php if ($this->countModules('bottom4b')) { ?>
					<div id="bottom4b" class="bottombox">
						<div id="mod-container">
							<jdoc:include type="modules" name="bottom4b" style="none" />
						</div>
					</div>
				<?php } ?>
				<?php if ($this->countModules('bottom4c')) { ?>
					<div id="bottom4c" class="bottombox">
						<div id="mod-container">
							<jdoc:include type="modules" name="bottom4c" style="none" />
						</div>
					</div>
				<?php } ?>
				<?php if ($this->countModules('bottom4d')) { ?>
					<div id="bottom4d" class="bottombox">
						<div id="mod-container">
							<jdoc:include type="modules" name="bottom4d" style="none" />
						</div>
					</div>
				<?php } ?>
			</div>
		</div>
	</div>
	<!-- Footer -->
	<?php 
	
	if ($this->countModules('footer1a') && $this->countModules('footer1b') && $this->countModules('footer1c') && $this->countModules('footer1d')) : 
		$modcount = 4;
	elseif ($this->countModules('footer1a') && $this->countModules('footer1b') && $this->countModules('footer1c')) : 
		$modcount = 3;
	elseif ($this->countModules('footer1a') && $this->countModules('footer1b')) : 
		$modcount = 2;
	elseif ($this->countModules('footer1a')) : 
		$modcount = 1;
	endif;
	
	?>
	<footer class="footer" role="contentinfo">
		<div id="footer-1" class="container<?php echo ($params->get('fluidContainer') ? '-fluid' : ''); ?> footer1-<?= $modcount ?>">
			<div class="container<?php echo ($params->get('fluidContainer') ? '-fluid' : ''); ?> footer1 ?>">
				<div id="footer">
					<?php if ($this->countModules('footer1a')) { ?>
						<div id="footer1a" class="footerbox">
							<div id="mod-container">
								<jdoc:include type="modules" name="footer1a" style="none" />
							</div>
						</div>
					<?php } ?>
					<?php if ($this->countModules('footer1b')) { ?>
						<div id="footer1b" class="footerbox">
							<div id="mod-container">
								<jdoc:include type="modules" name="footer1b" style="none" />
							</div>
						</div>
					<?php } ?>
					<?php if ($this->countModules('footer1c')) { ?>
						<div id="footer1c" class="footerbox">
							<div id="mod-container">
								<jdoc:include type="modules" name="footer1c" style="none" />
							</div>
						</div>
					<?php } ?>
					<?php if ($this->countModules('footer1d')) { ?>
						<div id="footer1d" class="footerbox">
							<div id="mod-container">
								<jdoc:include type="modules" name="footer1d" style="none" />
							</div>
						</div>
					<?php } ?>
				</div>
			</div>
		</div>
		<div id="copyright-top" class="container<?php echo ($params->get('fluidContainer') ? '-fluid' : ''); ?>">
			<div id="inside-padding">
				<jdoc:include type="modules" name="copyright-top" style="none" />
			</div>
		</div>
		<div id="copyright" class="container<?php echo ($params->get('fluidContainer') ? '-fluid' : ''); ?>">
			<div id="inside-padding">
			<p class="pull-right">
				<a href="#top" id="back-top">
					<?php echo JText::_('TPL_PROTOSTAR_BACKTOTOP'); ?>
				</a>
			</p>
			<p>
				&copy; <?php echo date('Y'); ?> <?php echo $sitename; ?>
			</p>
		</div>
		</div>
		<jdoc:include type="modules" name="social-slider" style="none" />
	</footer>
	<jdoc:include type="modules" name="debug" style="none" />
</body>
</html>


