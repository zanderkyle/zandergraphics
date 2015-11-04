<?php
/**
 * Install script
 *
 * @package         Tabs
 * @version         5.1.3
 *
 * @author          Peter van Westen <peter@nonumber.nl>
 * @link            http://www.nonumber.nl
 * @copyright       Copyright © 2015 NoNumber All Rights Reserved
 * @license         http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

defined('_JEXEC') or die;

require_once __DIR__ . '/script.install.helper.php';

class PlgEditorsXtdTabsInstallerScript extends PlgEditorsXtdTabsInstallerScriptHelper
{
	public $name = 'TABS';
	public $alias = 'tabs';
	public $extension_type = 'plugin';
	public $plugin_folder = 'editors-xtd';

	public function uninstall($adapter)
	{
		$this->uninstallPlugin($this->extname, 'system');
	}
}
