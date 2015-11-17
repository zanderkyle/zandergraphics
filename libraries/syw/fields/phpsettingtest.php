<?php
/**
 * @copyright	Copyright (C) 2011 Simplify Your Web, Inc. All rights reserved.
 * @license		GNU General Public License version 3 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die ;

jimport('joomla.form.formfield');
jimport('joomla.plugin.helper');
jimport('joomla.version');

class JFormFieldPhpSettingTest extends JFormField {
		
	public $type = 'PhpSettingTest';

	/**
	 * Method to get the field options
	 */
	protected function getLabel() {
			
		$html = '';
		
		$html .= '<div style="clear: both;"></div>';
		
		return $html;
	}

	/**
	 * Method to get the field input markup
	 */
	protected function getInput() {
		
		$lang = JFactory::getLanguage();
		$lang->load('lib_syw.sys', JPATH_SITE);
		
		$setting_to_test = trim($this->element['setting']);
		
		$version = new JVersion();
		$jversion = explode('.', $version->getShortVersion());
						
		$html = '';
		
		if (!ini_get($setting_to_test)) {
			if (intval($jversion[0]) > 2) { // Joomla! 3+
				$html .= '<div class="alert alert-error">';
			} else {
				$html .= '<div style="clear: both; margin: 5px 0; padding: 8px 35px 8px 14px; border-radius: 4px; border: 1px solid #EED3D7; background-color: #F2DEDE; color: #B94A48;">';
			}
			$html .= '<span>';
			$html .= JText::sprintf('LIB_SYW_PHPSETTING_DISABLED', $setting_to_test);
			$html .= '</span>';
			$html .= '</div>';
				
			return $html;
		} else {
			if (intval($jversion[0]) > 2) { // Joomla! 3+
				$html .= '<div class="alert alert-success">';
			} else {
				$html .= '<div style="clear: both; margin: 5px 0; padding: 8px 35px 8px 14px; border-radius: 4px; border: 1px solid #D6E9C6; background-color: #DFF0D8; color: #468847;">';
			}
			$html .= '<span>';
			$html .= JText::sprintf('LIB_SYW_PHPSETTING_ENABLED', $setting_to_test);
			$html .= '</span>';
			$html .= '</div>';
		}
		
		return $html;
	}

}
?>
