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
class JFormFieldSYWFontPicker extends JFormField {
		
	public $type = 'SYWFontPicker';
	
	protected function getFontTag($fontfamily) 
	{		
		$html = '';
		
		$html .= '<li><a href="#" onclick="';
		$html .= '        document.id(\'' . $this->id . '\').value=\''.htmlspecialchars($fontfamily).'\';';
		$html .= '        document.id(\'' . $this->id . '\').setStyle(\'font-family\', \''.htmlspecialchars($fontfamily).'\');';
		$html .= '        return false;';
		$html .= '">'.$fontfamily.'</a></li>';		
		
		return $html;
	}
	
	protected function getSerifFontFamilies() 
	{
		$html = '';
		
		$html .= self::getFontTag('serif');
		$html .= self::getFontTag('Georgia, serif');
		$html .= self::getFontTag('"Palatino Linotype", "Book Antiqua", Palatino, serif');
		$html .= self::getFontTag('"MS Serif", "New York", serif');
		$html .= self::getFontTag('"Times New Roman", Times, serif');
		
		return $html;
	}
	
	protected function getSansSerifFontFamilies()
	{
		$html = '';
		
		$html .= self::getFontTag('sans-serif');
		$html .= self::getFontTag('Arial, Helvetica, sans-serif');
		$html .= self::getFontTag('"Arial Black", Gadget, sans-serif');
		$html .= self::getFontTag('"Comic Sans MS", cursive, sans-serif');
		$html .= self::getFontTag('Impact, Charcoal, sans-serif');
		$html .= self::getFontTag('"Lucida Sans Unicode", "Lucida Grande", sans-serif');
		$html .= self::getFontTag('Tahoma, Geneva, sans-serif');
		$html .= self::getFontTag('"Trebuchet MS", Helvetica, sans-serif');
		$html .= self::getFontTag('"MS Sans Serif", Geneva, sans-serif');
		$html .= self::getFontTag('Verdana, Geneva, sans-serif');
		
		return $html;
	}
	
	protected function getMonospaceFontFamilies()
	{
		$html = '';
		
		$html .= self::getFontTag('monospace');
		$html .= self::getFontTag('"Courier New", Courier, monospace');
		$html .= self::getFontTag('"Lucida Console", Monaco, monospace');
		
		return $html;
	}
	
	protected function getCursiveFontFamilies()
	{
		$html = '';
		
		$html .= self::getFontTag('cursive');
		
		return $html;
	}
	
	protected function getFantasyFontFamilies()
	{
		$html = '';
		
		$html .= self::getFontTag('fantasy');
		
		return $html;
	}
	
	/**
	 * Method to get the field input markup.
	 *
	 */
	protected function getInput() 
	{		
		$lang = JFactory::getLanguage();
		$lang->load('lib_syw.sys', JPATH_SITE);
            
		JHtml::_('stylesheet', 'syw/fonts.css', false, true);
			
		$html = '<div class="input-prepend input-append">';
            
		$html .= '<div class="add-on"><i class="SYWicon-font"></i></div>';
            
		$html .= '    <input id="'.$this->id.'" name="'.$this->name.'" class="input-medium" type="text" value="'.htmlspecialchars($this->value).'" style="font-family:'.htmlspecialchars($this->value).'" />';
            
		$html .= '    <div class="btn-group" style="display:inline-block;vertical-align:middle">';
		$html .= '        <button style="border-radius:0;margin-left:-1px;min-width:auto" class="btn dropdown-toggle hasTooltip" data-toggle="dropdown" title="' . JText::_('LIB_SYW_FONTPICKER_SELECTFONT') . '">';            
		$html .= '            <span class="caret" style="margin-bottom:auto"></span>';
		$html .= '        </button>';
		$html .= '        <ul class="dropdown-menu">';
    
		// if use global
		//$html .= '<li><a href="#" onclick="';
		//$html .= '        document.id(\'' . $this->id . '\').value=\''.JText::_('JGLOBAL_USE_GLOBAL').'\';';
		//$html .= '        document.id(\'' . $this->id . '\').setStyle(\'font-family\', \'inherit\');';
		//$html .= '        return false;';
		//$html .= '">'.JText::_('JGLOBAL_USE_GLOBAL').'</a></li>';
            
		$html .= '<li><a href="#" onclick="';
		$html .= '        document.id(\'' . $this->id . '\').value=\''.htmlspecialchars('"Google font", fallback, fonts').'\';';
		$html .= '        document.id(\'' . $this->id . '\').setStyle(\'font-family\', \'inherit\');';
		$html .= '        return false;';
		$html .= '">"Google font", fallback, fonts</a></li>';  
            
		$html .= '<li class="nav-header">Serif</li>';            
		$html .= self::getSerifFontFamilies();
                
		$html .= '<li class="nav-header">Sans-Serif</li>'; 
		$html .= self::getSansSerifFontFamilies();                
            
		$html .= '<li class="nav-header">Cursive</li>'; 
		$html .= self::getCursiveFontFamilies();
            
		$html .= '<li class="nav-header">Fantasy</li>'; 
		$html .= self::getFantasyFontFamilies();
            
		$html .= '<li class="nav-header">Monospace</li>'; 
		$html .= self::getMonospaceFontFamilies();           
                
		$html .= '        </ul>';
		$html .= '    </div>';
            
		$html .= '    <a class="btn hasTooltip" href="http://www.google.com/webfonts" target="_blank" title="'.JText::_('LIB_SYW_FONTPICKER_GOOGLEFONTLINK').'"><i class="SYWicon-google"></i></a>';
            
		$html .= '    <a class="btn hasTooltip" title="' . JText::_('JLIB_FORM_BUTTON_CLEAR') . '"' . ' href="#" onclick="';
		$html .= '        document.id(\'' . $this->id . '\').value=\'\';';
		$html .= '        return false;';
		$html .= '    ">';
		$html .= '    <i class="icon-remove"></i></a>';			
		$html .= '</div>';			
		$html .= '<span class="help-block">'.JText::_('LIB_SYW_FONTPICKER_GOOGLEFONTLINKHELP').'</span>';
       		
		return $html;
	}

}
?>
