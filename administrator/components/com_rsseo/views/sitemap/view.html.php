<?php
/**
* @version 1.0.0
* @package RSSeo! 1.0.0
* @copyright (C) 2009-2012 www.rsjoomla.com
* @license GPL, http://www.gnu.org/copyleft/gpl.html
*/

defined( '_JEXEC' ) or die( 'Restricted access' ); 
jimport( 'joomla.application.component.view');
jimport( 'joomla.filesystem.folder');
jimport( 'joomla.filesystem.file');

class rsseoViewSitemap extends JViewLegacy
{
	protected $sitemap;
	protected $ror;
	protected $form;
	protected $percent;
	
	public function display($tpl = null) {
		$this->sitemap		= JFile::exists(JPATH_SITE.'/sitemap.xml');
		$this->ror			= JFile::exists(JPATH_SITE.'/ror.xml');
		$this->form			= $this->get('Form');
		$this->percent		= $this->get('Percent');
		
		$this->sidebar = rsseoHelper::isJ3() ? JHtmlSidebar::render() : '';
		$this->addToolBar();
		parent::display($tpl);
	}
	
	protected function addToolBar() {
		JToolBarHelper::title(JText::_('COM_RSSEO_SITEMAP'),'rsseo');
		
		if (JFactory::getUser()->authorise('core.admin', 'com_rsseo'))
			JToolBarHelper::preferences('com_rsseo');
		
		JToolBarHelper::custom('main', 'rsseo.png', 'rsseo.png', JText::_('COM_RSSEO_GLOBAL_COMPONENT'), false);
	}
}