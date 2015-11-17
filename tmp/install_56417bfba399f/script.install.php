<?php
/**
 * @package         NoNumber Installer
 * @version
 *
 * @author          Peter van Westen <peter@nonumber.nl>
 * @link            http://www.nonumber.nl
 * @copyright       Copyright © 2015 NoNumber All Rights Reserved
 * @license         http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

defined('_JEXEC') or die;

class PlgSystemNoNumberInstallerInstallerScript
{
	private $min_joomla_version = '3.4.1';
	private $min_php_version = '5.3.13';

	public function preflight($route, JAdapterInstance $adapter)
	{
		JFactory::getLanguage()->load('plg_system_nonumberinstaller', __DIR__);

		if (!$this->passMinimumJoomlaVersion())
		{
			$this->uninstallInstaller();

			return false;
		}

		if (!$this->passMinimumPHPVersion())
		{
			$this->uninstallInstaller();

			return false;
		}
	}

	public function postflight($route, JAdapterInstance $adapter)
	{
		if (!in_array($route, array('install', 'update')))
		{
			return;
		}

		$this->renameOldUpdateSites();
		$this->removeDuplicateUpdateSites();

		// First install the NoNumber Framework
		$this->installFramework();

		// Then install the rest of the packages
		if (!$this->installPackages())
		{
			// Uninstall this installer
			$this->uninstallInstaller();

			return false;
		}

		JFactory::getApplication()->enqueueMessage(JText::_('NNI_PLEASE_CLEAR_YOUR_BROWSERS_CACHE'), 'notice');

		// Uninstall this installer
		$this->uninstallInstaller();
	}

	private function renameOldUpdateSites()
	{
		$db = JFactory::getDbo();

		$query = $db->getQuery(true)
			->select('update_site_id')
			->from('#__update_sites')
			->where($db->qn('location') . ' LIKE ' . $db->q('http://cdn.download.nonumber.nl%'))
			->where($db->qn('name') . ' NOT LIKE ' . $db->q('NoNumber%'));
		$db->setQuery($query);
		$ids = $db->loadColumn();

		if (empty($ids))
		{
			return;
		}

		$query->clear()
			->update('#__update_sites')
			->set($db->qn('name') . ' = CONCAT(' . $db->q('NoNumber ') . ',' . $db->qn('name') . ')')
			->where($db->qn('update_site_id') . ' IN (' . implode(',', $ids) . ')');
		$db->setQuery($query);
		$db->execute();
	}

	private function removeDuplicateUpdateSites()
	{
		$db = JFactory::getDbo();

		$query = $db->getQuery(true)
			->select('update_site_id')
			->from('#__update_sites')
			->where($db->qn('name') . ' LIKE ' . $db->q('NoNumber%'))
			->group($db->qn('name'))
			->having('count(*) > 1');
		$db->setQuery($query);
		$ids = $db->loadColumn();

		if (empty($ids))
		{
			return;
		}

		$query->clear()
			->delete('#__update_sites')
			->where($db->qn('update_site_id') . ' IN (' . implode(',', $ids) . ')');
		$db->setQuery($query);
		$db->execute();

		$query->clear()
			->delete('#__update_sites_extensions')
			->where($db->qn('update_site_id') . ' IN (' . implode(',', $ids) . ')');
		$db->setQuery($query);
		$db->execute();
	}

	// Check if Joomla version passes minimum requirement
	private function passMinimumJoomlaVersion()
	{
		if (version_compare(JVERSION, $this->min_joomla_version, '<'))
		{
			JFactory::getApplication()->enqueueMessage(
				JText::sprintf('NNI_NOT_COMPATIBLE_UPDATE', JVERSION, $this->min_joomla_version),
				'error'
			);

			return false;
		}

		return true;
	}

	// Check if PHP version passes minimum requirement
	private function passMinimumPHPVersion()
	{

		if (version_compare(PHP_VERSION, $this->min_php_version, 'l'))
		{
			JFactory::getApplication()->enqueueMessage(
				JText::sprintf('NNI_NOT_COMPATIBLE_PHP', PHP_VERSION, $this->min_php_version),
				'error'
			);

			return false;
		}

		return true;
	}

	private function installPackages()
	{
		$packages = JFolder::folders(__DIR__ . '/packages');

		$packages = array_diff($packages, array('plg_system_nnframework'));

		foreach ($packages as $package)
		{
			if (!$this->installPackage($package))
			{
				return false;
			}
		}

		return true;
	}

	private function installPackage($package)
	{
		$tmpInstaller = new JInstaller;

		return $tmpInstaller->install(__DIR__ . '/packages/' . $package);
	}

	private function installFramework()
	{
		return $this->installPackage('plg_system_nnframework');
	}

	private function uninstallInstaller()
	{
		if (!JFolder::exists(JPATH_SITE . '/plugins/system/nonumberinstaller'))
		{
			return;
		}

		$this->deleteFolders(
			array(
				JPATH_SITE . '/plugins/system/nonumberinstaller/language',
				JPATH_SITE . '/plugins/system/nonumberinstaller',
			)
		);

		$db = JFactory::getDbo();

		$query = $db->getQuery(true)
			->delete('#__extensions')
			->where($db->quoteName('element') . ' = ' . $db->quote('nonumberinstaller'))
			->where($db->quoteName('folder') . ' = ' . $db->quote('system'))
			->where($db->quoteName('type') . ' = ' . $db->quote('plugin'));
		$db->setQuery($query);
		$db->execute();
	}

	public function deleteFolders($folders = array())
	{
		foreach ($folders as $folder)
		{
			if (!is_dir($folder))
			{
				continue;
			}

			JFolder::delete($folder);
		}
	}
}
