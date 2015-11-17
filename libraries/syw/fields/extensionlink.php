<?php
/**
 * @copyright	Copyright (C) 2011 Simplify Your Web, Inc. All rights reserved.
 * @license		GNU General Public License version 3 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die ;

jimport('joomla.form.formfield');

class JFormFieldExtensionLink extends JFormField {
		
	public $type = 'ExtensionLink';

	/**
	 * Method to get the field options.
	 */
	protected function getLabel() {
		
		$html = '';
		
		$lang = JFactory::getLanguage();
		$lang->load('lib_syw.sys', JPATH_SITE);
		
		$version = new JVersion();
		$jversion = explode('.', $version->getShortVersion());
		
		$type = $this->element['linktype'];
		$link = $this->element['link'];
		
		if (intval($jversion[0]) > 2) {
			$html .= '<div style="clear: both;">';
		} else {
			$html .= '<div style="overflow: hidden; margin: 5px 0">';
			$html .= '<label style="margin: 0">';
		}
		
		$image = '';
		$title = '';
		switch ($type) {
			case 'forum': $title = 'LIB_SYW_EXTENSIONLINK_FORUM_LABEL'; break;
			case 'demo': $title = 'LIB_SYW_EXTENSIONLINK_DEMO_LABEL'; break;
			case 'review': $image = 'jed.png'; $title = 'LIB_SYW_EXTENSIONLINK_REVIEW_LABEL'; break;
			case 'donate': $image = 'donate.png'; $title = 'LIB_SYW_EXTENSIONLINK_DONATE_LABEL'; break;
			case 'upgrade': $title = 'LIB_SYW_EXTENSIONLINK_UPGRADE_LABEL'; break;
			case 'buy': $image = 'buy.png'; $title = 'LIB_SYW_EXTENSIONLINK_BUY_LABEL'; break;
			case 'doc': $title = 'LIB_SYW_EXTENSIONLINK_DOC_LABEL'; break;
			case 'onlinedoc': $title = 'LIB_SYW_EXTENSIONLINK_ONLINEDOC_LABEL'; break;
			default: break;
		}
		
		$html .= '<a href="'.$link.'" target="_blank" title="'.JText::_($title).'">';
		if (!empty($image)) {
			$html .= '<img src="'.JURI::root().'libraries/syw/images/'.$image.'" alt="'.JText::_($title).'">';
		} else {
			$html .= JText::_($title);
		}
		$html .= '</a>';
		
		if (intval($jversion[0]) > 2) {
			$html .= '</div>';
		} else {
			$html .= '</label>';
		}
		
		return $html;
	}

	/**
	 * Method to get the field input markup.
	 */
	protected function getInput() {
		
		$lang = JFactory::getLanguage();
		$lang->load('lib_syw.sys', JPATH_SITE);
		
		$version = new JVersion();
		$jversion = explode('.', $version->getShortVersion());
		
		$type = $this->element['linktype'];
		$link = $this->element['link'];
		$specific_desc = $this->element['description'];
		
		$desc = '';
		$image = false;
		switch ($type) {
			case 'forum': $desc = 'LIB_SYW_EXTENSIONLINK_FORUM_DESC'; break;
			case 'demo': $desc = 'LIB_SYW_EXTENSIONLINK_DEMO_DESC'; break;
			case 'review': $image = true; $desc = 'LIB_SYW_EXTENSIONLINK_REVIEW_DESC'; break;
			case 'donate': $image = true; $desc = 'LIB_SYW_EXTENSIONLINK_DONATE_DESC'; break;
			case 'upgrade': $desc = 'LIB_SYW_EXTENSIONLINK_UPGRADE_DESC'; break;
			case 'buy': $desc = 'LIB_SYW_EXTENSIONLINK_BUY_DESC'; break;
			case 'doc': $desc = 'LIB_SYW_EXTENSIONLINK_DOC_DESC'; break;
			case 'onlinedoc': $desc = 'LIB_SYW_EXTENSIONLINK_ONLINEDOC_DESC'; break;
		}
		
		if (intval($jversion[0]) > 2 || ($image && intval($jversion[0]) < 3)) {
			$html = '<div style="padding-top: 5px; overflow: inherit">';
		} else {
			$html = '<div>';
		}
			
		if (empty($specific_desc)) {
			$html .= JText::sprintf($desc, $link);
		} else {
			$html .= JText::sprintf($specific_desc, $link);
		}
		
		if (intval($jversion[0]) > 2) {
			// J3+
		} else {
			$html .= '</div>';
		}
		
		$html .= '</div>';

		return $html;
	}

}
?>