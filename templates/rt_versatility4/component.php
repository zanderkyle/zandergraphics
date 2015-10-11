<?php
/**
 * @package   Versatility4 Template - RocketTheme
* @version   $Id: component.php 28375 2015-04-25 04:47:44Z reggie $
* @author    RocketTheme, LLC http://www.rockettheme.com
* @copyright Copyright (C) 2007 - 2015 RocketTheme, LLC
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 *
 * Rockettheme Versatility4 Template uses the Joomla Framework (http://www.joomla.org), a GNU/GPLv2 content management system
 *
 */

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
?>
<?php if (JRequest::getString('type')=='raw'):?>
<jdoc:include type="component" />
<?php else: ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>">
<head>
	<jdoc:include type="head" />
	<?php
	$font_family = $this->params->get("fontFamily", "versatility4");
	$fontfamily = $font_family;
	$app = JFactory::getApplication();
	$jversion = new JVersion;

	if ($jversion->RELEASE >= "3.2") :
		echo '<link rel="stylesheet" href="'.$this->baseurl.'/media/jui/css/bootstrap.min.css" type="text/css" />';
		echo '<link rel="stylesheet" href="'.$this->baseurl.'/media/jui/css/icomoon.css" type="text/css" />';
		echo '<link rel="stylesheet" href="'.$this->baseurl.'/media/jui/css/bootstrap-responsive.min.css" type="text/css" />';
		echo '<link rel="stylesheet" href="'.$this->baseurl.'/templates/'.$this->template.'/css/joomla3.css" type="text/css" />';
	endif;
	?>
	<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/css/template.css" type="text/css" />
</head>
<body class="contentpane">
	<jdoc:include type="message" />
	<jdoc:include type="component" />
</body>
</html>
<?php endif; ?>
