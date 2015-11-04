<?php
/**
 * @package   AkeebaTicketSystem
 * @copyright Copyright (c)2011-2015 Nicholas K. Dionysopoulos
 * @license   GNU General Public License version 3, or later
 */

namespace Akeeba\TicketSystem\Site\Dispatcher;

defined('_JEXEC') or die;

use Akeeba\TicketSystem\Admin\Helper\ComponentParams;

class Dispatcher extends \FOF30\Dispatcher\Dispatcher
{
	/** @var   string  The name of the default view, in case none is specified */
	public $defaultView = 'ControlPanel';

	public function onBeforeDispatch()
	{
		if (!@include_once(JPATH_ADMINISTRATOR . '/components/com_ats/version.php'))
		{
			define('ATS_PRO', '0');
			define('ATS_VERSION', 'dev');
			define('ATS_DATE', date('Y-m-d'));
		}

		// Load Akeeba Strapper, if it is installed
		\JLoader::import('joomla.filesystem.folder');

        if (class_exists('Akeeba\TicketSystem\Site\Helper\AutoOffline'))
        {
            \Akeeba\TicketSystem\Site\Helper\AutoOffline::processAutoOffline();
        }

		$useStrapper = ComponentParams::getParam('usestrapper', 3);

		if (in_array($useStrapper, array(1, 3)) && \JFolder::exists(JPATH_SITE . '/media/strapper30'))
		{
			@include_once JPATH_SITE . '/media/strapper30/strapper.php';

			if (class_exists('\\AkeebaStrapper30', false))
			{
				\AkeebaStrapper30::bootstrap();
				\AkeebaStrapper30Loader();
			}
		}

		// Load common CSS and JavaScript
		\JHtml::_('jquery.framework');

		// Make sure akeeba.jQuery is always defined
		if (!in_array($useStrapper, array(1, 3)) || !\JFolder::exists(JPATH_SITE . '/media/strapper30'))
		{
			$this->container->template->addJS('media://com_ats/js/namespaced.js', false, false, $this->container->mediaVersion);
		}

		// If Joomla is using SEF URLs, but URL rewrite is OFF (so we have index.php/foobar/test urls),
		// I have to use absolute URLs, otherwise all the requests are performed vs the current page, not the site root
		if($this->input->getCmd('format', 'html') == 'html')
		{
			$root = \JUri::root();
			$js = <<<JS
var ATS_ROOT_URL = "$root";
JS;
			$this->container->template->addJSInline($js);
		}
	}

    public function onAfterDispatch()
    {
        if (class_exists('Akeeba\TicketSystem\Site\Helper\Breadcrumbs'))
        {
            \Akeeba\TicketSystem\Site\Helper\Breadcrumbs::buildCrumbs();
        }
    }
}