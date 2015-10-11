<?php
/**
* @version   $Id: default_logout.php 28287 2015-04-17 17:05:31Z reggie $
* @author    RocketTheme http://www.rockettheme.com
* @copyright Copyright (C) 2007 - 2015 RocketTheme, LLC
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */
defined('_JEXEC') or die;
$app = JFactory::getApplication();
$templateName = $app->getTemplate();
include JPATH_SITE.'/templates/'.$templateName.'/html/base_override.php';