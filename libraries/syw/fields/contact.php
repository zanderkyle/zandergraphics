<?php
/**
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('JPATH_BASE') or die;

jimport('joomla.version');

/**
 * Supports a modal contact picker.
 *
 * @package		Joomla.Administrator
 * @subpackage	com_contact
 * @since		1.6
 */
class JFormFieldContact extends JFormField
{
	/**
	 * The form field type.
	 *
	 * @var		string
	 * @since	1.6
	 */
	public $type = 'Contact';

	/**
	 * Method to get the field input markup.
	 *
	 * @return	string	The field input markup.
	 * @since	1.6
	 */
	protected function getInput()
	{
		$lang = JFactory::getLanguage();
		$lang->load('lib_syw.sys', JPATH_SITE);
		
		// Load the javascript
		JHtml::_('behavior.framework');
		JHtml::_('behavior.modal', 'a.modal');

		// Build the script.
		$script = array();
		$script[] = '	function jSelectChart_'.$this->id.'(id, name, object) {';
		$script[] = '		document.id("'.$this->id.'_id").value = id;';
		$script[] = '		document.id("'.$this->id.'_name").value = name;';
		$script[] = '		SqueezeBox.close();';
		$script[] = '	}';

		// Add the script to the document head.
		JFactory::getDocument()->addScriptDeclaration(implode("\n", $script));

		// Get the title of the linked chart
		$db = JFactory::getDBO();
		$db->setQuery(
			'SELECT name' .
			' FROM #__contact_details' .
			' WHERE id = '.(int) $this->value
		);
		$title = $db->loadResult();

		if ($error = $db->getErrorMsg()) {
			JError::raiseWarning(500, $error);
		}

		if (empty($title)) {
			$title = JText::_('LIB_SYW_CONTACT_SELECTCONTACT');
		}
		
		$version = new JVersion();
		$jversion = explode('.', $version->getShortVersion());
		
		$link = 'index.php?option=com_contact&amp;view=contacts&amp;layout=modal&amp;tmpl=component&amp;function=jSelectChart_'.$this->id;
		
		if (intval($jversion[0]) > 2) {
			$html = '<div class="input-append">';
			$html .= '    <input class="input-small" type="text" id="'.$this->id.'_name" value="'.htmlspecialchars($title, ENT_QUOTES, 'UTF-8').'" disabled="disabled" />';
			$html .= '    <a class="modal btn hasTooltip" title="'.JText::_('LIB_SYW_CONTACT_SELECTCONTACT').'" href="'.$link.'" rel="{handler: \'iframe\', size: {x: 800, y: 500}}">';
			$html .= '    <i class="icon-address"></i> '.JText::_('JLIB_FORM_BUTTON_SELECT').'</a>';
			$html .= '    <a class="btn hasTooltip" title="' . JText::_('JLIB_FORM_BUTTON_CLEAR') . '"' . ' href="#" onclick="';
			$html .= '        document.id(\'' . $this->id . '_name\').value=\'\';';
			$html .= '        document.id(\'' . $this->id . '_id\').value=\'\';';
			//$html .= '        document.id(\'' . $this->id . '\').fireEvent(\'change\');';
			$html .= '        return false;';
			$html .= '    ">';
			$html .= '    <i class="icon-remove"></i></a>';
			$html .= '</div>';
		} else {			
			$html = '<div class="fltlft">';
			$html .= '    <input type="text" id="'.$this->id.'_name" value="'.htmlspecialchars($title, ENT_QUOTES, 'UTF-8').'" disabled="disabled" />';
			$html .= '</div>';
			$html .= '<div class="button2-left">';
			$html .= '    <div class="blank">';
			$html .= '        <a class="modal" title="'.JText::_('LIB_SYW_CONTACT_SELECTCONTACT').'" href="'.$link.'" rel="{handler: \'iframe\', size: {x: 800, y: 500}}">'.JText::_('JLIB_FORM_BUTTON_SELECT').'</a>';
			$html .= '    </div>';
			$html .= '</div>';
			
			$html .= '<div class="button2-left">';
			$html .= '    <div class="blank">';
			$html .= '        <a title="' . JText::_('JLIB_FORM_BUTTON_CLEAR') . '"' . ' href="#" onclick="';
			$html .= 'document.id(\'' . $this->id . '_name\').value=\'\';';
			$html .= 'document.id(\'' . $this->id . '_id\').value=\'\';';
			//$html .= 'document.id(\'' . $this->id . '\').fireEvent(\'change\');';
			$html .= 'return false;';
			$html .= '">';
			$html .= JText::_('JLIB_FORM_BUTTON_CLEAR') . '</a>';
			$html .= '    </div>';
			$html .= '</div>';
		}
		
		// The active contact id field.
		if (0 == (int)$this->value) {
			$value = '';
		} else {
			$value = (int)$this->value;
		}

		// class='required' for client side validation
		$class = '';
		if ($this->required) {
			$class = ' class="required modal-value"';
		}

		$html .= '<input type="hidden" id="'.$this->id.'_id"'.$class.' name="'.$this->name.'" value="'.$value.'" />';

		return $html;
	}
}
