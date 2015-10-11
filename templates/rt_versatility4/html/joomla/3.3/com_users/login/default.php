<?php
/**
 * @package   Akiraka Template - RocketTheme
* @version   $Id: default.php 26077 2015-01-27 13:06:56Z james $
* @author    RocketTheme, LLC http://www.rockettheme.com
* @copyright Copyright (C) 2007 - 2015 RocketTheme, LLC
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 *
 * Rockettheme Akiraka Template uses the Joomla Framework (http://www.joomla.org), a GNU/GPLv2 content management system
 *
 */
 
// no direct access
defined('_JEXEC') or die;

if ($this->user->get('guest')):
	// The user is not logged in.
	echo $this->loadTemplate('login');
else:
	// The user is already logged in.
	echo $this->loadTemplate('logout');
endif;
