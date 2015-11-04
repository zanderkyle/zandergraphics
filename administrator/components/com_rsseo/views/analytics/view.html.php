<?php
/**
* @version 1.0.0
* @package RSSeo! 1.0.0
* @copyright (C) 2009-2012 www.rsjoomla.com
* @license GPL, http://www.gnu.org/copyleft/gpl.html
*/

defined( '_JEXEC' ) or die( 'Restricted access' ); 
jimport('joomla.application.component.view');

class rsseoViewAnalytics extends JViewLegacy
{
	protected $config;
	protected $accounts;
	protected $acc;
	protected $rsstart;
	protected $rsend;
	protected $tabs;
	protected $visits;
	protected $sources;
	
	public function display($tpl = null) {
		$this->config	= rsseoHelper::getConfig();
		
		if (JFactory::getApplication()->input->getInt('ajax')) {
			$layout = $this->getLayout();
			$this->{$layout} = $this->get('GA'.ucfirst($layout));
			
		} else {
			// Check for errors
			$this->check();
			
			$this->accounts = $this->get('Accounts');
			$this->acc[] = JHTML::_('select.option', '', JText::_('COM_RSSEO_SELECT_GA_ACCOUNT'));
			if ($this->accounts) {
				foreach($this->accounts as $account) {
					$this->acc[] = JHTML::_('select.option', $account->getProfileId(), $account->getProfileName().' ('.$account->getTitle().')');
				}
			}
			
			$now			= JFactory::getDate()->toUnix(); 
			$this->rsstart	= $this->config->ga_start ? $this->config->ga_start : JHtml::_('date', ($now - 604800), 'Y-m-d');
			$this->rsend	= $this->config->ga_end ? $this->config->ga_end : JHtml::_('date', ($now - 86400), 'Y-m-d');
			$this->tabs		= $this->get('Tabs');
			
			$this->visits = $this->get('GAVisits');
			$this->sources = $this->get('GASources');
			
			JFactory::getDocument()->addScript('http://www.google.com/jsapi');
			
			$this->sidebar = rsseoHelper::isJ3() ? JHtmlSidebar::render() : '';
			$this->addToolBar();
		}
		
		parent::display($tpl);
		if (JFactory::getApplication()->input->getInt('ajax')) {
			JFactory::getApplication()->close();
		}
	}
	
	protected function addToolBar() {
		JToolBarHelper::title(JText::_('COM_RSSEO_GOOGLE_ANALYTICS'),'rsseo');
		
		if (JFactory::getUser()->authorise('core.admin', 'com_rsseo'))
			JToolBarHelper::preferences('com_rsseo');
		
		JToolBarHelper::custom('main', 'rsseo.png', 'rsseo.png', JText::_('COM_RSSEO_GLOBAL_COMPONENT'), false);
	}
	
	protected function check() {
		if (!extension_loaded('curl'))
			JFactory::getApplication()->redirect('index.php?option=com_rsseo',JText::_('COM_RSSEO_NO_CURL'));
		
		if (trim($this->config->analytics_username) == '' || trim($this->config->analytics_password) == '' || $this->config->analytics_enable == 0)
			JFactory::getApplication()->redirect('index.php?option=com_rsseo',JText::_('COM_RSSEO_GA_ERROR'));
	}
}