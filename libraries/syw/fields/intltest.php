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

/**
 *
 * deprecated. Use phpextensiontest instead
 *
 */
class JFormFieldIntltest extends JFormField {
		
	public $type = 'Intltest';

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
		
		$version = new JVersion();
		$jversion = explode('.', $version->getShortVersion());
		
		$extensions = get_loaded_extensions();
		
		$html = '';
		
		if( !in_array( 'intl', $extensions ) ) {
			if (intval($jversion[0]) > 2) { // Joomla! 3+
				$html .= '<div class="alert alert-error">';
			} else {
				$html .= '<div style="clear: both; margin: 5px 0; padding: 8px 35px 8px 14px; border-radius: 4px; border: 1px solid #EED3D7; background-color: #F2DEDE; color: #B94A48;">';
			}
			$html .= '<span>';
			$html .= JText::_('LIB_SYW_INTLTEST_NOTLOADED');
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
			$html .= JText::_('LIB_SYW_INTLTEST_LOADED');
			$html .= '</span>';
			$html .= '</div>';
		}
		
		return $html;
	}

}
?>
