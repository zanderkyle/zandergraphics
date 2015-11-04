<?php
/**
* @package RSFirewall!
* @copyright (C) 2009-2014 www.rsjoomla.com
* @license GPL, http://www.gnu.org/licenses/gpl-2.0.html
*/

defined('_JEXEC') or die('Restricted access');

class RSFirewallViewRsfirewall extends JViewLegacy
{
	protected $buttons;
	protected $canViewLogs;
	protected $lastLogs;
	protected $logNum;
	protected $lastMonthLogs;
	protected $feeds;
	protected $files;
	// version info
	protected $version;
	protected $code;
	protected $isJ30;
	
	public function display($tpl = null) {
		$this->addToolBar();
		
		$model = $this->getModel('RSFirewall');
		if (!$model->isPluginEnabled()) {
			$app = JFactory::getApplication();
			$app->enqueueMessage(JText::_('COM_RSFIREWALL_WARNING_PLUGIN_DISABLED'), 'notice');
		}
		
		$this->isJ30 		= $this->get('isJ30');
		
		$this->buttons  	= JHtml::_('icons.buttons', $this->get('Buttons'));
		$this->version		= (string) new RSFirewallVersion;
		$this->canViewLogs	= JFactory::getUser()->authorise('logs.view', 'com_rsfirewall');
		$this->code			= $this->get('code');
		$this->feeds		= $this->get('feeds');
		$this->files		= $this->get('modifiedFiles');
		
		if ($this->canViewLogs) {
			$this->logNum 		 = $this->get('logOverviewNum');
			$this->lastLogs 	 = $this->get('lastLogs');
			$this->lastMonthLogs = $this->get('lastMonthLogs');
			
			$this->document->addScript('https://www.google.com/jsapi');
		}	
		
		$this->sidebar = $this->get('SideBar');
		
		// Load GeoIP helper class
		require_once JPATH_ADMINISTRATOR.'/components/com_rsfirewall/helpers/geoip/geoip.php';
		$this->geoip = RSFirewallGeoIP::getInstance();
		
		parent::display($tpl);
	}
	
	protected function addToolbar() {
		// set title
		JToolBarHelper::title('RSFirewall!', 'rsfirewall');
		
		require_once JPATH_COMPONENT.'/helpers/toolbar.php';
		RSFirewallToolbarHelper::addToolbar();
	}
	
	protected function showDate($date) {
		return JHTML::_('date', $date, 'Y-m-d H:i:s');
	}
}