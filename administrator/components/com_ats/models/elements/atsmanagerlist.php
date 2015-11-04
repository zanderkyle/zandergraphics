<?php
/**
 * @package ats
 * @copyright Copyright (c)2011-2014 Nicholas K. Dionysopoulos / AkeebaBackup.com
 * @license GNU GPL v3 or later
 */

use FOF30\Container\Container;

defined('_JEXEC') or die;

if (!defined('FOF30_INCLUDED'))
{
	include_once(JPATH_LIBRARIES . '/fof30/include.php');
}

// Let's build the container so it will register our extension in the autoloader
Container::getInstance('com_ats');

class JFormFieldAtsmanagerlist extends JFormField
{
	public function getInput()
	{
		$html  = '<div style="float:left;width:300px">';
		$catid = $this->form->getValue('id');

		$all[]    = JHTML::_('select.option', 'all', JText::_('COM_ATS_CATEGORY_ALL_MANAGERS'), 'id', 'name');
		$managers = \Akeeba\TicketSystem\Admin\Helper\Permissions::getManagers($catid);
		$options  = array_merge($all, $managers);

		if(!$catid && !isset($this->element['hidetip']))
		{
			$html .= '<div style="font-weight:bold">'.JText::_('COM_ATS_CATEGORY_NOTIFY_MANAGERS_SAVEBEFORE').'</div>';
		}

		$html .= JHTML::_('select.genericlist', $options, $this->name.'[]', 'class="inputbox" multiple="multiple" size="5"', 'id', 'name', $this->value, $this->id);
		$html .= '</div>';

		return $html;
	}
}