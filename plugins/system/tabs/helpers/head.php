<?php
/**
 * Plugin Helper File: Head
 *
 * @package         Tabs
 * @version         5.1.4
 *
 * @author          Peter van Westen <peter@nonumber.nl>
 * @link            http://www.nonumber.nl
 * @copyright       Copyright © 2015 NoNumber All Rights Reserved
 * @license         http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

defined('_JEXEC') or die;

class PlgSystemTabsHelperHead
{
	var $helpers = array();
	var $params = null;

	public function __construct()
	{
		require_once __DIR__ . '/helpers.php';
		$this->helpers = PlgSystemTabsHelpers::getInstance();
		$this->params  = $this->helpers->getParams();
	}

	public function addHeadStuff()
	{
		// do not load scripts/styles on feeds or print pages
		if (NNFrameworkFunctions::isFeed() || JFactory::getApplication()->input->getInt('print', 0))
		{
			return;
		}

		if ($this->params->load_bootstrap_framework)
		{
			JHtml::_('bootstrap.framework');
		}


		$script = '
			var nn_tabs_urlscroll = 0;
			var nn_tabs_use_hash = ' . (int) $this->params->use_hash . ';
			var nn_tabs_reload_iframes = ' . (int) $this->params->reload_iframes . ';
			var nn_tabs_init_timeout = ' . (int) $this->params->init_timeout . ';
		';
		JFactory::getDocument()->addScriptDeclaration('/* START: Tabs scripts */ ' . preg_replace('#\n\s*#s', ' ', trim($script)) . ' /* END: Tabs scripts */');

		JHtml::script('tabs/script.min.js', false, true);

		switch ($this->params->load_stylesheet)
		{
			case 2:
				JHtml::stylesheet('tabs/old.min.css', false, true);
				break;

			case 1:
				JHtml::stylesheet('tabs/style.min.css', false, true);
				break;

			case 0:
			default:
				// Do not load styles
				break;
		}

	}

	public function removeHeadStuff(&$html)
	{
		// Don't remove if tabs class is found
		if (strpos($html, 'class="nn_tabs-tab') !== false)
		{
			return;
		}

		// remove style and script if no items are found
		$html = preg_replace('#\s*<' . 'link [^>]*href="[^"]*/(tabs/css|css/tabs)/[^"]*\.css[^"]*"[^>]* />#s', '', $html);
		$html = preg_replace('#\s*<' . 'script [^>]*src="[^"]*/(tabs/js|js/tabs)/[^"]*\.js[^"]*"[^>]*></script>#s', '', $html);
		$html = preg_replace('#((?:;\s*)?)(;?)/\* START: Tabs .*?/\* END: Tabs [a-z]* \*/\s*#s', '\1', $html);
	}
}
