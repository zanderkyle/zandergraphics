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

class Com_ReReplacerInstallerScript extends Com_ReReplacerInstallerScriptHelper
{
	public $name = 'REREPLACER';
	public $alias = 'rereplacer';
	public $extension_type = 'component';

	public function uninstall($adapter)
	{
		$this->uninstallPlugin($this->extname, 'system');
	}

	public function onAfterInstall()
	{
		$this->createTable();
		$this->fixOldFormatInDatabase();
		$this->deleteOldFiles();
	}

	public function createTable()
	{
		$query = "CREATE TABLE IF NOT EXISTS `#__rereplacer` (
			`id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
			`name` TEXT NOT NULL,
			`description` TEXT NOT NULL,
			`search` TEXT NOT NULL,
			`replace` TEXT NOT NULL,
			`area` TEXT NOT NULL,
			`params` TEXT NOT NULL,
			`published` TINYINT(1) NOT NULL DEFAULT '0',
			`ordering` INT(11) NOT NULL DEFAULT '0',
			`checked_out` INT(11) UNSIGNED NOT NULL DEFAULT '0',
			`checked_out_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
			PRIMARY KEY  (`id`),
			KEY `id` (`id`,`published`)
		) DEFAULT CHARSET=utf8;";
		$this->db->setQuery($query);
		$this->db->execute();
	}

	public function fixOldFormatInDatabase()
	{
		// convert old J1.5 params syntax to new
		$query = $this->db->getQuery(true);
		$query->select('r.id, r.params')
			->from('#__rereplacer as r')
			->where('r.params REGEXP ' . $this->db->quote('^[^\{]'));
		$this->db->setQuery($query);
		$rows = $this->db->loadObjectList();
		foreach ($rows as $row)
		{
			if (empty($row->params))
			{
				continue;
			}
			$row->params = str_replace('assignto_secscats', 'assignto_cats', $row->params);
			$row->params = str_replace('flexicontent', 'fc', $row->params);

			$params = JRegistryFormat::getInstance('INI')->stringToObject($row->params);
			foreach ($params as $key => $val)
			{
				if (is_string($val) && !(strpos($val, '|') === false))
				{
					$params->{$key} = explode('|', $val);
				}
			}
			if (!empty($params->assignto_cats_selection))
			{
				foreach ($params->assignto_cats_selection as $key => $val)
				{
					if (!(strpos($val, ':') === false))
					{
						$params->assignto_cats_selection[$key] = substr($val, strpos($val, ':') + 1);
					}
				}
			}
			$query = $this->db->getQuery(true);
			$query->update('#__rereplacer as r')
				->set('r.params = ' . $this->db->quote(json_encode($params)))
				->where('r.id = ' . (int) $row->id);
			$this->db->setQuery($query);
			$this->db->execute();
		}

		// concatenates the sef and non-sef url fields
		$query = $this->db->getQuery(true);
		$query->update('#__rereplacer as r')
			->set(
				'r.params = replace( replace( replace( replace( `params`,'
				. $this->db->quote('"assignto_urls_selection_sef"') . ',' . $this->db->quote('"assignto_urls_selection"') . '),'
				. $this->db->quote('"assignto_urls_selection":"","assignto_browsers"') . ',' . $this->db->quote('"assignto_browsers"') . '),'
				. $this->db->quote('","show_url_field":"0","assignto_urls_selection":"') . ',' . $this->db->quote('\n') . '),'
				. $this->db->quote('","show_url_field":"1","assignto_urls_selection":"') . ',' . $this->db->quote('\n') . ')'
			)
			->where('r.params LIKE ' . $this->db->quote('%"assignto_urls_selection_sef"%'));
		$this->db->setQuery($query);
		$this->db->execute();

		// add url_regex value to filled in url fields
		$query = $this->db->getQuery(true);
		$query->update('#__rereplacer as r')
			->set(
				'r.params = replace( replace( replace( replace( `params`,'
				. $this->db->quote('"assignto_os"') . ',' . $this->db->quote('"assignto_urls_regex":"1","assignto_os"') . '),'
				. $this->db->quote('"","assignto_urls_regex":"1"') . ',' . $this->db->quote('""') . '),'
				. $this->db->quote('"assignto_urls_regex":"0","assignto_urls_regex":"1"') . ',' . $this->db->quote('"assignto_urls_regex":"0"') . '),'
				. $this->db->quote('"assignto_urls_regex":"1","assignto_urls_regex":"1"') . ',' . $this->db->quote('"assignto_urls_regex":"1"') . ')'
			)
			->where('r.params LIKE ' . $this->db->quote('%"assignto_urls":"1"%'))
			->where('r.params NOT LIKE ' . $this->db->quote('%"assignto_urls_regex"%'));
		$this->db->setQuery($query);
		$this->db->execute();
	}

	private function deleteOldFiles()
	{
		$this->deleteFolders(
			array(
				JPATH_SITE . '/components/com_rereplacer',
			)
		);
	}

}
