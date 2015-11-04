<?php
/**
* @package RSFirewall!
* @copyright (C) 2009-2014 www.rsjoomla.com
* @license GPL, http://www.gnu.org/licenses/gpl-2.0.html
*/

defined('_JEXEC') or die('Restricted access');

class RSFirewallViewRsfirewall extends JViewLegacy
{
	public function display($tpl = null) {
		JFactory::getApplication()->enqueueMessage(JText::_('COM_RSFIREWALL_FRONTEND_MESSAGE'), 'warning');
	}
}