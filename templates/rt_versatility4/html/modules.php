<?php
/**
* @version   $Id: modules.php 26096 2015-01-27 14:14:12Z james $
 * @package Joomla
* @copyright Copyright (C) 2005 - 2006 Open Source Matters. All rights reserved.
* @license GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

/**
 * This is a file to add template specific chrome to module rendering.  To use it you would
 * set the style attribute for the given module(s) include in your template to use the style
 * for each given modChrome function.
 *
 * eg.  To render a module mod_test in the sliders style, you would use the following include:
 * <jdoc:include type="module" name="test" style="slider" />
 *
 * This gives template designers ultimate control over how modules are rendered.
 *
 * NOTICE: All chrome wrapping methods should be named: modChrome_{STYLE} and take the same
 * two arguments.
 */

/*
 * Module chrome for rendering the module in a slider
 */
function modChrome_submenu($module, &$params, &$attribs)
{	
	if (!empty ($module->content) && strlen($module->content) > 40) {
	$parent_menu = JFactory::getApplication()->getMenu()->getActive()->tree[1]; ?>
		<div class="module<?php echo $params->get('moduleclass_sfx'); ?>"><div><div><div>
			<h3><?php echo JFactory::getApplication()->getMenu()->getItem($parent_menu)->title; ?> Menu</h3>
			<?php echo $module->content; ?>
		</div></div></div></div>
	<?php 
    }
}
?>