<?php
/**
* @version 1.0.0
* @package RSSEO! 1.0.0
* @copyright (C) 2009-2012 www.rsjoomla.com
* @license GPL, http://www.gnu.org/copyleft/gpl.html
*/

defined( '_JEXEC' ) or die( 'Restricted access' ); 
jimport( 'joomla.application.component.view');

class rsseoViewBackup extends JViewLegacy
{
	protected $process;
	protected $backup;
	protected $restore;
	
	public function display($tpl = null) {
		$this->process = JFactory::getApplication()->input->getString('process');
		
		if ($this->process == 'backup') {
			$this->backup = $this->backup();
		} else if ($this->process == 'restore') {
			$this->restore = $this->restore();
		}
		
		$this->sidebar = rsseoHelper::isJ3() ? JHtmlSidebar::render() : '';
		$this->cleanup();
		$this->addToolBar();
		parent::display($tpl);
	}
	
	protected function addToolBar() {
		JToolBarHelper::title(JText::_('COM_RSSEO_BACKUP_RESTORE'),'rsseo');
		
		if ($this->process) {
			$bar = JToolBar::getInstance('toolbar');
			$bar->appendButton('Link', 'back', JText::_('COM_RSSEO_GLOBAL_BACK'), 'index.php?option=com_rsseo&view=backup');
		}
		
		if (JFactory::getUser()->authorise('core.admin', 'com_rsseo'))
			JToolBarHelper::preferences('com_rsseo');
		
		JToolBarHelper::custom('main', 'rsseo.png', 'rsseo.png', JText::_('COM_RSSEO_GLOBAL_COMPONENT'), false);
	}
	
	protected function backup() {
		require_once JPATH_ADMINISTRATOR.'/components/com_rsseo/helpers/backup.php';
		
		$db			= JFactory::getDbo();
		$query		= $db->getQuery(true);
		$options	= array();
		
		$query->clear();
		$query->select('*')->from('#__rsseo_pages');
		$pages = (string) $query;
		$query->clear();
		$query->select('*')->from('#__rsseo_redirects');
		$redirects = (string) $query;
		
		$pages = str_replace(array("\r","\n"), array('', ' '), $pages);
		$pages = trim($pages);
		$redirects = str_replace(array("\r","\n"), array('', ' '), $redirects);
		$redirects = trim($redirects);
		
		$options['queries'][] = array('query' => $pages.' ' , 'primary' => 'id');
		$options['queries'][] = array('query' => $redirects.' ', 'primary' => 'id');
		
		$package = new RSPackage($options);
		$package->backup();
		return $package->displayProgressBar();
	}
	
	protected function restore() {
		require_once JPATH_ADMINISTRATOR.'/components/com_rsseo/helpers/backup.php';
		
		$options = array();
		$options['redirect'] = 'index.php?option=com_rsseo&view=backup';
		
		$package = new RSPackage($options);
		$package->restore();
		return $package->displayProgressBar();
	}
	
	protected function cleanup() {
		jimport('joomla.filesystem.folder');
		
		if ($folder = JFactory::getApplication()->input->getString('delfolder')) {
			$folder = base64_decode($folder);
			if (JFolder::exists($folder))
				JFolder::delete($folder);
		}
		
	}
}