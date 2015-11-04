<?php
/**
* @package RSFirewall!
* @copyright (C) 2009-2014 www.rsjoomla.com
* @license GPL, http://www.gnu.org/licenses/gpl-2.0.html
*/

defined('_JEXEC') or die('Restricted access');

class RSFirewallViewUpdates extends JViewLegacy
{
	protected $hash;
	protected $jversion;
	protected $version;
	protected $sidebar;
	
	public function display($tpl = null) {
		$user = JFactory::getUser();
		if (!$user->authorise('updates.view', 'com_rsfirewall')) {
			$app = JFactory::getApplication();
			$app->enqueueMessage(JText::_('JERROR_ALERTNOAUTHOR'), 'error');
			$app->redirect(JRoute::_('index.php?option=com_rsfirewall', false));
		}
		
		$this->addToolBar();
		
		$this->hash 	= $this->get('hash');
		$this->jversion = $this->get('joomlaVersion');
		$this->version	= (string) new RSFirewallVersion;
		
		$this->sidebar	= $this->get('SideBar');
		
		parent::display($tpl);
	}
	
	protected function addToolbar() {
		// set title
		JToolBarHelper::title('RSFirewall!', 'rsfirewall');
		
		require_once JPATH_COMPONENT.'/helpers/toolbar.php';
		RSFirewallToolbarHelper::addToolbar('updates');
	}
}