<?php
/**
* @version 1.0.0
* @package RSSEO! 1.0.0
* @copyright (C) 2009-2012 www.rsjoomla.com
* @license GPL, http://www.gnu.org/licenses/gpl-2.0.html
*/

defined('_JEXEC') or die('Restricted access');

class rsseoViewRsseo extends JViewLegacy
{
	protected $jversion;
	protected $code;
	
	public function display($tpl=null) {
		$layout = $this->getLayout();
		
		if ($layout == 'update') {
			$this->sidebar = rsseoHelper::isJ3() ? JHtmlSidebar::render() : '';
			$jversion = new JVersion();
			$this->jversion = $jversion->getShortVersion();
		} else {
			$this->code	= rsseoHelper::getConfig('global_register_code');
		}
		
		$this->addToolBar($layout);
		parent::display($tpl);
	}
	
	protected function addToolBar($layout) {
		if ($layout == 'update') {
			JToolBarHelper::title(JText::_('COM_RSSEO_MENU_UPDATE'),'rsseo');
		} else {
			JToolBarHelper::title(JText::_('COM_RSSEO_GLOBAL_COMPONENT'),'rsseo');
		}
		
		if (JFactory::getUser()->authorise('core.admin', 'com_rsseo'))
			JToolBarHelper::preferences('com_rsseo');
		
		JToolBarHelper::custom('main', 'rsseo.png', 'rsseo.png', JText::_('COM_RSSEO_GLOBAL_COMPONENT'), false);
	}
}