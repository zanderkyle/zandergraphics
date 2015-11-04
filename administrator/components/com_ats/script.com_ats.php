<?php
/**
 * @package ats
 * @copyright Copyright (c)2011-2014 Nicholas K. Dionysopoulos / AkeebaBackup.com
 * @license GNU GPL v3 or later
 */

// no direct access
defined('_JEXEC') or die();

// Load FOF if not already loaded
if (!defined('FOF30_INCLUDED') && !@include_once(JPATH_LIBRARIES . '/fof30/include.php'))
{
	throw new RuntimeException('This component requires FOF 3.0.');
}

class Com_AtsInstallerScript extends \FOF30\Utils\InstallScript
{
	/**
	 * The component's name
	 *
	 * @var   string
	 */
	protected $componentName = 'com_ats';

	/**
	 * The title of the component (printed on installation and uninstallation messages)
	 *
	 * @var string
	 */
	protected $componentTitle = 'Akeeba Ticket System';

	/**
	 * The minimum PHP version required to install this extension
	 *
	 * @var   string
	 */
	protected $minimumPHPVersion = '5.3.10';

	/**
	 * The minimum Joomla! version required to install this extension
	 *
	 * @var   string
	 */
	protected $minimumJoomlaVersion = '3.4.0';

	/**
	 * Obsolete files and folders to remove from both paid and free releases. This is used when you refactor code and
	 * some files inevitably become obsolete and need to be removed.
	 *
	 * @var   array
	 */
	protected $removeFilesAllVersions = array(
		'files'	=> array(
			'cache/com_ats.updates.php',
			'cache/com_ats.updates.ini',
			'administrator/cache/com_ats.updates.php',
			'administrator/cache/com_ats.updates.ini',
            'plugins/ats/postemail/test.email',

			// Obsolete Joomla! 2.5 support
		    'media/com_ats/css/chosen.min.css',
		    'media/com_ats/css/chosen-sprite.png',
		    'media/com_ats/js/chosen.jquery.min.js',

			// After migration to FOF 3
			'administrator/components/com_ats/dispatcher.php',
			'administrator/components/com_ats/toolbar.php',
			'administrator/components/com_ats/index.html',
			'administrator/components/com_ats/assets/cacert.pem',
			'administrator/components/com_ats/assets/index.html',
			'administrator/components/com_ats/helpers/autooffline.php',
			'administrator/components/com_ats/helpers/avatar.php',
			'administrator/components/com_ats/helpers/bbcode.php',
			'administrator/components/com_ats/helpers/credits.php',
			'administrator/components/com_ats/helpers/editor.php',
			'administrator/components/com_ats/helpers/filter.php',
			'administrator/components/com_ats/helpers/format.php',
			'administrator/components/com_ats/helpers/html.php',
			'administrator/components/com_ats/helpers/jsonlib.php',
			'administrator/components/com_ats/helpers/mail.php',
			'administrator/components/com_ats/helpers/select.php',
			'administrator/components/com_ats/helpers/signature.php',
			'administrator/components/com_ats/helpers/subscriptions.php',
			'administrator/components/com_ats/models/autoreplies.php',
			'administrator/components/com_ats/models/buckets.php',
			'administrator/components/com_ats/models/cannedreplies.php',
			'administrator/components/com_ats/models/cpanels.php',
			'administrator/components/com_ats/models/creditconsumptions.php',
			'administrator/components/com_ats/models/credittransactions.php',
			'administrator/components/com_ats/models/emailtemplates.php',
			'administrator/components/com_ats/models/index.html',
			'administrator/components/com_ats/models/jusers.php',
			'administrator/components/com_ats/models/managernotes.php',
			'administrator/components/com_ats/models/posts.php',
			'administrator/components/com_ats/models/stats.php',
			'administrator/components/com_ats/models/tickets.php',
			'administrator/components/com_ats/models/updates.php',
			'administrator/components/com_ats/models/usertags.php',
		),
		'folders' => array(
			// After migration to FOF 3
			'administrator/components/com_ats/assets/customfields',
			'administrator/components/com_ats/assets/mailer',
			'administrator/components/com_ats/controllers',
			'administrator/components/com_ats/fields',
			'administrator/components/com_ats/fof',
			'administrator/components/com_ats/tables',
			'administrator/components/com_ats/views',
			'components/com_ats/controllers',
			'components/com_ats/helpers',
			'components/com_ats/models',
			'components/com_ats/views/assignedticket',
			'components/com_ats/views/bucket',
			'components/com_ats/views/bucketreply',
			'components/com_ats/views/cannedreplies',
			'components/com_ats/views/jusers',
			'components/com_ats/views/latests',
			'components/com_ats/views/managernote',
			'components/com_ats/views/managernotes',
			'components/com_ats/views/my',
			'components/com_ats/views/newticket',
			'components/com_ats/views/post',
			'components/com_ats/views/posts',
			'components/com_ats/views/ticket',
		)
	);

	public function postflight($type, $parent)
	{
		// Remove the update sites for this component on installation. The update sites are now handled at the package
		// level.
		$this->removeObsoleteUpdateSites($parent);

        // If the attachments folder is not there, let's make it
        if(!JFolder::exists(JPATH_ROOT.'/media/com_ats/attachments'))
        {
            JFolder::create(JPATH_ROOT.'/media/com_ats/attachments');
        }

		// Call the parent method
		parent::postflight($type, $parent);
	}

	/**
	 * Renders the post-installation message
	 *
	 * @param  \JInstallerAdapterComponent
	 */
	protected function renderPostInstallation($parent)
	{
		$this->warnAboutJSNPowerAdmin();
?>

<h1>Akeeba Ticket System</h1>

<div style="margin: 1em; font-size: 14pt; background-color: #fffff9; color: black">
	You can download translation files <a href="http://cdn.akeebabackup.com/language/ats/index.html">directly from our CDN page</a>.
</div>
<img src="<?php echo JUri::base() ?>/../media/com_ats/images/ats-48.png" width="48" height="48" alt="Akeeba Ticket System" align="left" />
<h2 style="font-size: 14pt; font-weight: bold; padding: 0; margin: 0 0 0.5em;">&nbsp;Welcome to Akeeba Ticket System!</h2>
<span>
	The simplest and easiest ticket system for Joomla!&trade;
</span>

		<div style="margin: 1em; font-size: 14pt; background-color: #fffff9; color: black">
			You can download translation files <a href="http://cdn.akeebabackup.com/language/ats/index.html">directly from our CDN page</a>.
		</div>

<?php

		$container = \FOF30\Container\Container::getInstance('com_ats');
		/** @var \Akeeba\TicketSystem\Admin\Model\Stats $model */
		try
		{
			$model = $container->factory->model('Stats');

			if(method_exists($model, 'collectStatistics'))
			{
				$iframe = $model->collectStatistics(true);

				if ($iframe)
				{
					echo $iframe;
				}
			}
		}
		catch (\Exception $e)
		{
		}
	}

	protected function renderPostUninstallation($parent)
	{
?>
<h2 style="font-size: 14pt; font-weight: bold; padding: 0; margin: 0 0 0.5em;">&nbsp;Akeeba Ticket System Uninstallation</h2>
<p>We are sorry that you decided to uninstall Akeeba Ticket System. Please let us know why by using the Contact Us form on our site. We appreciate your feedback; it helps us develop better software!</p>

<?php
		parent::renderPostUninstallation($parent);
	}

	/**
	 * The PowerAdmin extension makes menu items disappear. People assume it's our fault. JSN PowerAdmin authors don't
	 * own up to their software's issue. I have no choice but to warn our users about the faulty third party software.
	 */
	private function warnAboutJSNPowerAdmin()
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select('COUNT(*)')
			->from($db->qn('#__extensions'))
			->where($db->qn('type') . ' = ' . $db->q('component'))
			->where($db->qn('element') . ' = ' . $db->q('com_poweradmin'))
			->where($db->qn('enabled') . ' = ' . $db->q('1'));
		$hasPowerAdmin = $db->setQuery($query)->loadResult();

		if (!$hasPowerAdmin)
		{
			return;
		}

		$query = $db->getQuery(true)
					->select('manifest_cache')
					->from($db->qn('#__extensions'))
					->where($db->qn('type') . ' = ' . $db->q('component'))
					->where($db->qn('element') . ' = ' . $db->q('com_poweradmin'))
					->where($db->qn('enabled') . ' = ' . $db->q('1'));
		$paramsJson = $db->setQuery($query)->loadResult();
		$jsnPAManifest = new JRegistry();
		$jsnPAManifest->loadString($paramsJson, 'JSON');
		$version = $jsnPAManifest->get('version', '0.0.0');

		if (version_compare($version, '2.1.2', 'ge'))
		{
			return;
		}

		echo <<<HTML
<div class="well" style="margin: 2em 0;">
<h1 style="font-size: 32pt; line-height: 120%; color: red; margin-bottom: 1em">WARNING: Menu items for {$this->componentTitle} might not be displayed on your site.</h1>
<p style="font-size: 18pt; line-height: 150%; margin-bottom: 1.5em">
	We have detected that you are using JSN PowerAdmin on your site. This software ignores Joomla! standards and
	<b>hides</b> the Component menu items to {$this->componentTitle} in the administrator backend of your site. Unfortunately we
	can't provide support for third party software. Please contact the developers of JSN PowerAdmin for support
	regarding this issue.
</p>
<p style="font-size: 18pt; line-height: 120%; color: green;">
	Tip: You can disable JSN PowerAdmin to see the menu items to {$this->componentTitle}.
</p>
</div>

HTML;

	}

	/**
	 * Removes obsolete update sites created for the component (we are now using an update site for the package, not the
	 * component).
	 *
	 * @param   JInstallerAdapterComponent  $parent  The parent installer
	 */
	protected function removeObsoleteUpdateSites($parent)
	{
		$db = $parent->getParent()->getDBO();

		$query = $db->getQuery(true)
					->select($db->qn('extension_id'))
					->from($db->qn('#__extensions'))
					->where($db->qn('type') . ' = ' . $db->q('component'))
					->where($db->qn('name') . ' = ' . $db->q($this->componentName));
		$db->setQuery($query);
		$extensionId = $db->loadResult();

		if (!$extensionId)
		{
			return;
		}

		$query = $db->getQuery(true)
					->select($db->qn('update_site_id'))
					->from($db->qn('#__update_sites_extensions'))
					->where($db->qn('extension_id') . ' = ' . $db->q($extensionId));
		$db->setQuery($query);

		$ids = $db->loadColumn(0);

		if (!is_array($ids) && empty($ids))
		{
			return;
		}

		foreach ($ids as $id)
		{
			$query = $db->getQuery(true)
						->delete($db->qn('#__update_sites'))
						->where($db->qn('update_site_id') . ' = ' . $db->q($id));
			$db->setQuery($query);

			try
			{
				$db->execute();
			}
			catch (\Exception $e)
			{
				// Do not fail in this case
			}
		}
	}
}