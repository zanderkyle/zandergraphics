<?php
/**
 * @package         Advanced Module Manager
 * @version         5.3.5
 *
 * @author          Peter van Westen <peter@nonumber.nl>
 * @link            http://www.nonumber.nl
 * @copyright       Copyright © 2015 NoNumber All Rights Reserved
 * @license         http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

/**
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

jimport('joomla.filesystem.file');

$this->config->show_assignto_groupusers = (int) (
	$this->config->show_assignto_usergrouplevels
);


$assignments = array(
	'menuitems',
	'homepage',
	'date',
	'groupusers',
	'languages',
	'templates',
	'urls',
	'os',
	'browsers',
	'components',
	'tags',
	'content',
);
foreach ($assignments as $i => $ass)
{
	if ($ass != 'menuitems' && (!isset($this->config->{'show_assignto_' . $ass}) || !$this->config->{'show_assignto_' . $ass}))
	{
		unset($assignments[$i]);
	}
}

$html = array();

$html[] = $this->render($this->assignments, 'assignments');

$html[] = $this->render($this->assignments, 'mirror_module');
$html[] = '<div class="clear"></div>';
$html[] = '<div id="' . rand(1000000, 9999999) . '___mirror_module.0" class="nntoggler">';

if (count($assignments) > 1)
{
	$html[] = $this->render($this->assignments, 'match_method');
	$html[] = $this->render($this->assignments, 'show_assignments');
}
else
{
	$html[] = '<input type="hidden" name="show_assignments" value="1" />';
}

foreach ($assignments as $ass)
{
	$html[] = $this->render($this->assignments, 'assignto_' . $ass);
}

$show_assignto_users = 0;
$html[] = '<input type="hidden" name="show_users" value="' . $show_assignto_users . '" />';
$html[] = '<input type="hidden" name="show_usergrouplevels" value="' . (int) $this->config->show_assignto_usergrouplevels . '" />';

$html[] = '</div>';

echo implode("\n\n", $html);
