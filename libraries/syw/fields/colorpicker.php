<?php
/**
 * @copyright	Copyright (C) 2011 Simplify Your Web, Inc. All rights reserved.
 * @license		GNU General Public License version 3 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die ;

jimport('joomla.form.formfield');

class JFormFieldColorPicker extends JFormField {
		
	public $type = 'ColorPicker';
	
	static $mrLoaded = false;
	
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
		
		$version = new JVersion();
		$jversion = explode('.', $version->getShortVersion());
		
		$allow_transparency = (trim($this->element['transparency']) === "true") ? TRUE : FALSE;
					
		$html = '';
		
		if (intval($jversion[0]) > 2) { // Joomla! 3+ (jQuery)
			
			$color = strtolower($this->value);
			
			if (!$color || in_array($color, array('none', 'transparent'))) {
				$color = '';
			} elseif ($color['0'] != '#') {
				$color = '#' . $color;
			}
			
			JHtml::_('behavior.colorpicker');
			
			if ($allow_transparency) {

                $html .= '<div class="input-append">';

                $html .= '<input style="height:18px" type="text" name="' . $this->name . '" id="' . $this->id . '"' . ' value="'. htmlspecialchars($color, ENT_COMPAT, 'UTF-8') . '"' . ' class="' . 'minicolors' . '"' . '/>';

				$html .= '<a id="a_'.$this->id.'" class="btn hasTooltip" title="'.JText::_('JLIB_FORM_BUTTON_CLEAR').'" href="#" onclick="return false;">';
				$html .= '<i class="icon-remove"></i>';
				$html .= '</a>';

                $html .= '</div>';

				$doc->addScriptDeclaration("
					jQuery(document).ready(function (){
						jQuery('#a_".$this->id."').click(function() {
							jQuery('#".$this->id."').parent().find('span').first().children().css('background-color','transparent');
                            jQuery('#".$this->id."').val('');
						});
					});
				");
			} else {
                $html .= '<input type="text" name="' . $this->name . '" id="' . $this->id . '"' . ' value="'. htmlspecialchars($color, ENT_COMPAT, 'UTF-8') . '"' . ' class="' . 'minicolors' . '"' . '/>';
            }
			
		} else { // Joomla! 2.5+ (MooTools)
			
			self::load_moorainbow();
					
			$script = '';
			$script .= "window.addEvent('domready', function() {\n";
			
			$script .= "	var r_".$this->id." = new MooRainbow('".$this->id."_color', {\n";
			$script .= "		'id': '".$this->id."_id',\n";
			if (!empty($this->value)) {	
				$color = $this->value;
				if ($color[0] == '#') {
					$color = substr($color, 1);
				}
				if (strlen($color) == 6) {
					list($r, $g, $b) = array($color[0].$color[1], $color[2].$color[3], $color[4].$color[5]);
					$r = hexdec($r); $g = hexdec($g); $b = hexdec($b);
					$script .= "		'startColor': [".$r.", ".$g.", ".$b."],\n";
				} elseif (strlen($color) == 3) {
					list($r, $g, $b) = array($color[0].$color[0], $color[1].$color[1], $color[2].$color[2]);
					$r = hexdec($r); $g = hexdec($g); $b = hexdec($b);
					$script .= "		'startColor': [".$r.", ".$g.", ".$b."],\n";
				} else {
					$script .= "		'startColor': [0, 0, 0],\n";
				}
			} else {
				$script .= "		'startColor': [0, 0, 0],\n";
			}
			$script .= "		'imgPath': '".JURI::root(true)."/media/system/images/mooRainbow/"."',\n";
			$script .= "		'onChange': function(color) {\n";
			$script .= "			$('".$this->id."_color').setStyle('background-color', color.hex);\n";
			$script .= "			$('".$this->id."').value = color.hex;\n";
	        //$script .= "		},\n";
			//$script .= "		'onComplete': function(color) {\n";
			//$script .= "			$('".$this->id."_color').setStyle('background-color', color.hex);\n";
			//$script .= "			$('".$this->id."').value = color.hex;\n";
			$script .= "		}\n";
			$script .= "	});\n";        
			
			if (!empty($this->value)) {
				$color = $this->value;
				if ($color[0] != '#') {
					$color = '#'.$color;
				}
				$script .= "	$('".$this->id."_color').setStyle('background-color', '".$color."');\n";
			} else {
	            $script .= "	$('".$this->id."').value = '';\n";
	            $script .= "	$('".$this->id."_color').setStyle('background-color', 'transparent');\n";
	        }
	        
			$script .= "});\n";		
			
			$doc->addScriptDeclaration($script);
			
			$html .= '<button id="'.$this->id.'_color" title="' . JText::_('LIB_SYW_COLORPICKER_SELECTCOLOR') . '" style="display:inline-block;width:16px;height:16px;border:1px solid #C0C0C0;">&nbsp;</button>';
			$html .= '<input id="'.$this->id.'" name="'.$this->name.'" type="text" size="7" value="'.$this->value.'" />';
			
			if ($allow_transparency) {
				$html .= '<div class="button2-left">';
				$html .= '    <div class="blank">';
				$html .= '        <a title="' . JText::_('JLIB_FORM_BUTTON_CLEAR') . '"' . ' href="#" onclick="';
				$html .= '        document.id(\'' . $this->id . '\').value=\'\';';
				$html .= '        document.id(\'' . $this->id . '_color\').setStyle(\'background-color\', \'transparent\');';
				$html .= 'return false;';
				$html .= '">';
				$html .= JText::_('JLIB_FORM_BUTTON_CLEAR') . '</a>';
				$html .= '    </div>';
			}
		}
		
		return $html;
	}
	
	/**
	 * Load mooRainbow plugin if needed
	 */
	static function load_moorainbow()
	{
		$doc = JFactory::getDocument();
	
		if (self::$mrLoaded) {
			return;
		}
		
		$doc->addScript(JURI::root(true).'/media/system/js/mooRainbow.js');
		$doc->addStyleSheet(JURI::root(true).'/media/system/css/mooRainbow.css');
		
		self::$mrLoaded = true;
	}

}
?>
