<?php
/**
 * @copyright	Copyright (C) 2011 Simplify Your Web, Inc. All rights reserved.
 * @license		GNU General Public License version 3 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die ;

jimport('joomla.form.formfield');
jimport('joomla.filesystem.folder');
jimport('joomla.version');

class JFormFieldDemotest extends JFormField {
		
	public $type = 'Demotest';

	/**
	 * Method to get the field options.
	 *
	 * @return	array	The field option objects.
	 * @since	1.6
	 */
	protected function getLabel() {
		
		$html = '';
		
		$html .= '<div style="clear: both;"></div>';
		
		return $html;		
	}

	/**
	 * Method to get the field input markup.
	 *
	 * @return	string	The field input markup.
	 * @since	1.6
	 */
	protected function getInput() {
		
		$lang = JFactory::getLanguage();
		$lang->load('lib_syw.sys', JPATH_SITE);
		
		$html = '';
		
		$folder = trim($this->element['demofolder']);
			
		$version = new JVersion();
		$jversion = explode('.', $version->getShortVersion());
		
		$folder = JPATH_ROOT.$folder;
		if (JFolder::exists($folder)) {
			if (intval($jversion[0]) > 2) {
				$html .= '<div class="alert alert-warning">';
			} else {
				$html .= '<div style="clear: both; margin: 5px 0; padding: 8px 35px 8px 14px; border-radius: 4px; border: 1px solid #FBEED5; background-color: #FCF8E3; color: #C09853;">';
			}
			$html .= '<span style="text-transform: uppercase;">';
			$html .= JText::_('LIB_SYW_DEMOTEST_THISISADEMO');
			$html .= '</span>';
			$html .= '</div>';
		} 
		
		return $html;
	}

}
?>