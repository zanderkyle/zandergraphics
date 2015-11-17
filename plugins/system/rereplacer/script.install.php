<?php
/**
 * Install script
 *
 * @package         ReReplacer
 * @version         6.1.2
 *
 * @author          Peter van Westen <peter@nonumber.nl>
 * @link            http://www.nonumber.nl
 * @copyright       Copyright © 2015 NoNumber All Rights Reserved
 * @license         http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

defined('_JEXEC') or die;

require_once __DIR__ . '/script.install.helper.php';

class PlgSystemReReplacerInstallerScript extends PlgSystemReReplacerInstallerScriptHelper
{
	public $name = 'REREPLACER';
	public $alias = 'rereplacer';
	public $extension_type = 'plugin';

	public function uninstall($adapter)
	{
		$this->uninstallComponent($this->extname);
	}
}
