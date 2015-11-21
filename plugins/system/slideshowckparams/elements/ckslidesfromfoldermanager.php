<?php

/**
 * @copyright	Copyright (C) 2013 Cedric KEIFLIN alias ced1870
 * http://www.joomlack.fr
 * @license		GNU/GPL
 * */
// no direct access
defined('_JEXEC') or die('Restricted access');


class JFormFieldCkslidesfromfoldermanager extends JFormField {

    protected $type = 'ckslidesfromfoldermanager';

    protected function getInput() {

        $document = JFactory::getDocument();
		$document->addScriptDeclaration('JURIROOT="'.JUri::root().'";');
        $path = 'plugins/system/slideshowckparams/elements/ckslidesfromfoldermanager/';
        JHtml::_('script', $path.'ckslidesfromfoldermanager.js');
        JHtml::_('stylesheet', $path.'ckslidesfromfoldermanager.css');

        $html = '<input name="' . $this->name . '" id="ckslidesfromfolder" type="hidden" value="' . $this->value . '" />'
            .'<ul id="ckslidesfromfolderpreview"></ul>';

        return $html;
    }

    protected function getPathToImages() {
        $localpath = dirname(__FILE__);
        $rootpath = JPATH_ROOT;
        $httppath = trim(JURI::root(), "/");
        $pathtoimages = str_replace("\\", "/", str_replace($rootpath, $httppath, $localpath));
        return $pathtoimages;
    }

    protected function getLabel() {

        return '';
    }

}

