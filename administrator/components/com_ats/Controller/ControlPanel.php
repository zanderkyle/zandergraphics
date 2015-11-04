<?php
/**
 * @package   AkeebaTicketSystem
 * @copyright Copyright (c)2011-2015 Nicholas K. Dionysopoulos
 * @license   GNU General Public License version 3, or later
 */

namespace Akeeba\TicketSystem\Admin\Controller;

defined('_JEXEC') or die;

use Akeeba\TicketSystem\Admin\Model\Updates;
use FOF30\Controller\Controller;
use JText;

class ControlPanel extends Controller
{
	/**
	 * Runs before the main task, used to perform housekeeping function automatically
	 */
	protected function onBeforeDefault()
	{
		/** @var \Akeeba\TicketSystem\Admin\Model\ControlPanel $model */
		$model = $this->getModel();
		$model->checkAndFixDatabase();

		/** @var \Akeeba\TicketSystem\Admin\Model\Updates $updatesModel */
		$updatesModel = $this->getModel('Updates');
		$updatesModel->refreshUpdateSite();

        // Store the site URL in the component options, we're going to need it while running in CLI
        $db = \JFactory::getDBO();
        $query = $db->getQuery(true)
                    ->select('params')
                    ->from($db->qn('#__extensions'))
                    ->where($db->qn('element') . '=' . $db->q('com_ats'))
                    ->where($db->qn('type') . '=' . $db->q('component'));
        $rawparams = $db->setQuery($query)->loadResult();
        $params = new \JRegistry();
        $params->loadString($rawparams, 'JSON');

        $siteURL_stored  = $params->get('siteurl', '');
        $siteURL_target  = str_replace('/administrator', '', \JURI::base());
        $sitePath_stored = $params->get('sitepath', '');
        $sitePath_target = str_replace('/administrator', '', \JURI::base(true));

        if (($siteURL_target != $siteURL_stored) || ($sitePath_target != $sitePath_stored))
        {
            $params->set('siteurl', $siteURL_target);
            $params->set('sitepath', $sitePath_target);

            $query = $db->getQuery(true)
                        ->update($db->qn('#__extensions'))
                        ->set($db->qn('params') . '=' . $db->q($params->toString()))
                        ->where($db->qn('element') . '=' . $db->q('com_ats'))
                        ->where($db->qn('type') . '=' . $db->q('component'));
            $db->setQuery($query)->execute();
        }
	}

	/**
	 * Force reload the update information
	 */
	public function updateinfo()
	{
		/** @var Updates $updateModel */
		$updateModel = $this->container->factory->model('Updates');
		$updateInfo = (object)$updateModel->getUpdates();

		$result = '';

		if ($updateInfo->hasUpdate)
		{
			$strings = array(
				'header'		=> JText::sprintf('COM_ATS_CPANEL_MSG_UPDATEFOUND', $updateInfo->version),
				'button'		=> JText::sprintf('COM_ATS_CPANEL_MSG_UPDATENOW', $updateInfo->version),
				'infourl'		=> $updateInfo->infoURL,
				'infolbl'		=> JText::_('COM_ATS_CPANEL_MSG_MOREINFO'),
			);

			$result = <<<ENDRESULT
	<div class="alert alert-warning">
		<h3>
			<span class="icon icon-exclamation-sign glyphicon glyphicon-exclamation-sign"></span>
			{$strings['header']}
		</h3>
		<p>
			<a href="index.php?option=com_installer&view=update" class="btn btn-primary">
				{$strings['button']}
			</a>
			<a href="{$strings['infourl']}" target="_blank" class="btn btn-small btn-info">
				{$strings['infolbl']}
			</a>
		</p>
	</div>
ENDRESULT;
		}

		echo '###' . $result . '###';

		// Cut the execution short
		$this->container->platform->closeApplication();
	}
}