<?php
/**
 * @copyright	Copyright (C) 2011 Simplify Your Web, Inc. All rights reserved.
 * @license		GNU General Public License version 3 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die ;

jimport('joomla.form.formfield');

/**
 *
 * @author Olivier
 *
 * for Joomla 3+ ONLY
 *
 */
class JFormFieldSYWColorPicker extends JFormField {
		
	public $type = 'SYWColorPicker';
	
	protected $icon;
	protected $help;
	
	/**
	 * Method to get the field input markup.
	 *
	 * @return	string	The field input markup.
	 * @since	1.6
	 */
	protected function getInput() {
		
		$doc = JFactory::getDocument();	
		
		$lang = JFactory::getLanguage();
		$lang->load('lib_syw.sys', JPATH_SITE);
				
		$allow_transparency = ($this->element['transparency'] == "true") ? TRUE : FALSE;
		$use_global = ($this->element['global'] == "true") ? TRUE : FALSE;
					
		$html = '';
			
		$color = strtolower($this->value);
			
		if (!$color || in_array($color, array('none', 'transparent'))) {
			$color = '';
		} elseif ($color['0'] != '#') {
			$color = '#'.$color;
		}
		
		JHtml::_('behavior.colorpicker');
		
		$icon = isset($this->icon) ? $this->icon : '';
		if (!empty($icon)) {
			JHtml::_('stylesheet', 'syw/fonts.css', false, true);
		}
		
		$overall_class = empty($icon) ? '' : 'input-prepend';
		$overall_class .= ($allow_transparency || $use_global) ? ' input-append' : '';
		$overall_class = trim($overall_class);
		$overall_class = empty($overall_class) ? '' : ' class="'.$overall_class.'"';
			
		$html .= '<div'.$overall_class.'>';	
		
		if (!empty($icon)) {
			$html .= '<div class="add-on"><i class="'.$icon.'"></i></div>';
		}	

		if (!$allow_transparency && !$use_global) {
			$html .= '<input type="text" name="'.$this->name.'" id="'.$this->id.'"'.' value="'.htmlspecialchars($color, ENT_COMPAT, 'UTF-8').'"'.' class="minicolors" />';
		} else {
			$disabled = '';
			if (empty($this->value) && $use_global) {
				$disabled = ' disabled';
			}
			
			$html .= '<input type="hidden" name="'.$this->name.'" id="'.$this->id.'"'.' value="'.htmlspecialchars($this->value, ENT_COMPAT, 'UTF-8').'" />';
			$html .= '<input style="height:auto" type="text" name="visible_'.$this->name.'" id="visible_'.$this->id.'"'.' value="'.htmlspecialchars($color, ENT_COMPAT, 'UTF-8').'"'.' class="minicolors"'.$disabled.' />';
		}
		
		if ($use_global) {
			$class = 'btn hasTooltip';
			if (empty($this->value)) {
				$class .= ' btn-primary active';
			}
			$html .= '<a id="global_'.$this->id.'" class="'.$class.'" title="'.JText::_('JGLOBAL_USE_GLOBAL').'" href="#" onclick="return false;">';
			$html .= '<span>'.JText::_('JGLOBAL_USE_GLOBAL').'</span>';
			$html .= '</a>';
		}
			
		if ($allow_transparency) {
			$html .= '<a id="a_'.$this->id.'" class="btn hasTooltip" title="'.JText::_('JLIB_FORM_BUTTON_CLEAR').'" href="#" onclick="return false;">';
			$html .= '<i class="icon-remove"></i>';
			$html .= '</a>';
		}
		
		$html .= '</div>';
		
		if (isset($this->help)) {
			$html .= '<span class="help-block">'.JText::_($this->help).'</span>';
		}
			
		if ($allow_transparency || $use_global) {
			$script = 'jQuery(document).ready(function (){';
			
			$script .= 'jQuery("#visible_'.$this->id.'").change(function() { jQuery("#'.$this->id.'").val(jQuery("#visible_'.$this->id.'").val()) });';
			$script .= 'jQuery("#visible_'.$this->id.'").parent().find("span").first().children(".minicolors-panel").click(function() { jQuery("#visible_'.$this->id.'").change() });';
			$script .= 'jQuery("#visible_'.$this->id.'").next(".minicolors-panel").mouseup(function() { setTimeout(function(){ jQuery("#'.$this->id.'").val(jQuery("#visible_'.$this->id.'").val());}, 500); });';
			
			if ($use_global) {
				$script .= 'jQuery("#global_'.$this->id.'").click(function() {';
				$script .= 'jQuery("#visible_'.$this->id.'").parent().find("span").first().children().css("background-color","transparent");';
				$script .= 'if (jQuery("#global_'.$this->id.'").hasClass("btn-primary")) { jQuery("#global_'.$this->id.'").removeClass("btn-primary") } else { jQuery("#global_'.$this->id.'").addClass("btn-primary"); }';
				$script .= 'if (jQuery("#global_'.$this->id.'").hasClass("active")) { jQuery("#global_'.$this->id.'").removeClass("active") } else { jQuery("#global_'.$this->id.'").addClass("active"); }';
				if ($allow_transparency) {
					$script .= 'if (jQuery("#global_'.$this->id.'").hasClass("btn-primary")) { jQuery("#visible_'.$this->id.'").val(""); jQuery("#'.$this->id.'").val(""); jQuery("#visible_'.$this->id.'").prop("disabled", true) } else { jQuery("#'.$this->id.'").val("transparent"); jQuery("#visible_'.$this->id.'").prop("disabled", false) }';
				} else {
					$script .= 'if (jQuery("#global_'.$this->id.'").hasClass("btn-primary")) { jQuery("#visible_'.$this->id.'").val(""); jQuery("#'.$this->id.'").val(""); jQuery("#visible_'.$this->id.'").prop("disabled", true) } else { jQuery("#visible_'.$this->id.'").val("#ffffff"); jQuery("#'.$this->id.'").val("#ffffff"); jQuery("#visible_'.$this->id.'").parent().find("span").first().children().css("background-color","#ffffff"); jQuery("#visible_'.$this->id.'").prop("disabled", false) }';
				}
				$script .= '});';
			}
			
			if ($allow_transparency) {
				$script .= 'jQuery("#a_'.$this->id.'").click(function() {';
				$script .= 'jQuery("#visible_'.$this->id.'").parent().find("span").first().children().css("background-color","transparent");';
				$script .= 'jQuery("#visible_'.$this->id.'").val(""); jQuery("#'.$this->id.'").val("transparent");';
				$script .= '});';
			}
			
			$script .= '});';
		
			$doc->addScriptDeclaration($script);
		} 
		
		return $html;
	}
	
	public function setup(SimpleXMLElement $element, $value, $group = null)
	{
		$return = parent::setup($element, $value, $group);

		if ($return) {
			$this->icon = isset($this->element['icon']) ? $this->element['icon'] : null;
			$this->help = isset($this->element['help']) ? $this->element['help'] : null;
		}

		return $return;
	}

}
?>
