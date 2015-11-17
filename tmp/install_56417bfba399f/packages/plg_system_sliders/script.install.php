<?php
/**
 * Install script
 *
 * @package         Sliders
 * @version         5.1.4
 *
 * @author          Peter van Westen <peter@nonumber.nl>
 * @link            http://www.nonumber.nl
 * @copyright       Copyright © 2015 NoNumber All Rights Reserved
 * @license         http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

defined('_JEXEC') or die;

require_once __DIR__ . '/script.install.helper.php';

class PlgSystemSlidersInstallerScript extends PlgSystemSlidersInstallerScriptHelper
{
	public $name = 'SLIDERS';
	public $alias = 'sliders';
	public $extension_type = 'plugin';

	public function uninstall($adapter)
	{
		$this->uninstallPlugin($this->extname, 'editors-xtd');
	}
}
