<?php

/**
 * @copyright	Copyright (C) 2011 Cedric KEIFLIN alias ced1870
 * http://www.joomlack.fr
 * @license		GNU/GPL
 * */
// no direct access
defined('_JEXEC') or die('Restricted access');

class JFormFieldCkspacer extends JFormField {

    protected $type = 'ckspacer';

    protected function getInput() {
        return ' ';
    }

    protected function getLabel() {
        $html = array();
        $class = $this->element['class'] ? (string) $this->element['class'] : '';
        $icon = $this->element['icon'];
        $style = $this->element['style'];
        $styles = '';
        if ($style == 'title')
            $styles = ' style="display:block;background:#666;padding:10px;color:#fff;min-width:300px;text-transform:uppercase;font-size:14px;box-shadow: 0 5px 5px -2px #333;text-shadow: 0 1px 1px #000;border: 1px solid #888;"';
        if ($style == 'link')
            $styles = ' style="display:block;background:#efefef;padding:5px;color:#000;min-width:300px;line-height:25px;"';

        $html[] = '<span class="spacer">';
        $html[] = '<span class="before"></span>';
        $html[] = '<span class="' . $class . '">';
        if ((string) $this->element['hr'] == 'true') {
            $html[] = '<hr class="' . $class . '" />';
        } else {
            $label = '';
            // Get the label text from the XML element, defaulting to the element name.
            $text = $this->element['label'] ? (string) $this->element['label'] : (string) $this->element['name'];
            $text = $this->translateLabel ? JText::_($text) : $text;

            // Build the class for the label.
            $class = !empty($this->description) ? 'hasTip' : '';
            $class = $this->required == true ? $class . ' required' : $class;

            // Add the opening label tag and main attributes attributes.
            $label .= '<label id="' . $this->id . '-lbl" class="' . $class . '"';

            // If a description is specified, use it to build a tooltip.
            if (!empty($this->description)) {
                $label .= ' title="' . htmlspecialchars(trim($text, ':') . '::' .
                                ($this->translateDescription ? JText::_($this->description) : $this->description), ENT_COMPAT, 'UTF-8') . '"';
            }

            // Add the label text and closing tag.
            $label .= $styles . '>';
            $label .= $icon ? '<img src="' . $this->getPathToImages() . '/images/' . $icon . '" style="margin-right:5px;" />' : '';
            $label .= $text . '</label>';
            $html[] = $label;
        }
        $html[] = '</span>';
        $html[] = '<span class="after"></span>';
        $html[] = '</span>';
        return implode('', $html);
    }

    protected function getPathToImages() {
        $localpath = dirname(__FILE__);
        $rootpath = JPATH_ROOT;
        $httppath = trim(JURI::root(), "/");
        $pathtoimages = str_replace("\\", "/", str_replace($rootpath, $httppath, $localpath));
        return $pathtoimages;
    }

    protected function getTitle() {
        return $this->getLabel();
    }

}

