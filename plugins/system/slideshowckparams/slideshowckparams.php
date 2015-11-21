<?php

/**
 * @copyright	Copyright (C) 2011 Cédric KEIFLIN alias ced1870
 * http://www.joomlack.fr
 * @license		GNU/GPL
 * */
defined('_JEXEC') or die('Restricted access');
jimport('joomla.event.plugin');
jimport('joomla.filesystem.folder');

class plgSystemSlideshowckparams extends JPlugin {

    function plgSystemSlideshowckparams(&$subject, $params) {
        parent::__construct($subject, $params);
    }

    /**
     * @param       JForm   The form to be altered.
     * @param       array   The associated data for the form.
     * @return      boolean
     * @since       1.6
     */
    function onContentPrepareForm($form, $data) {
        if ($form->getName() != 'com_modules.module'
                && $form->getName() != 'com_advancedmodules.module'
                || ($form->getName() == 'com_modules.module' && $data && $data->module != 'mod_slideshowck' && $data->module != 'mod_incptvslideshowck2pro')
                || ($form->getName() == 'com_advancedmodules.module' && $data && $data->module != 'mod_slideshowck' && $data->module != 'mod_incptvslideshowck2pro'))
            return;

        JForm::addFormPath(JPATH_SITE . '/plugins/system/slideshowckparams/params');
        JForm::addFieldPath(JPATH_SITE . '/plugins/system/slideshowckparams/elements');

        // get the language
        // $lang = JFactory::getLanguage();
        // $langtag = $lang->getTag(); // returns fr-FR or en-GB
        $this->loadLanguage();

        // module options
        if ($form->getName() == 'com_modules.module' || $form->getName() == 'com_advancedmodules.module') {
            $form->loadFile('advanced_params_slideshowck', false);
        }
    }
	
	function onAjaxSlideshowckparams() {
		$app = JFactory::getApplication();
		$input = $app->input;
		
		$method = $input->get('method');

		if (method_exists($this, $method)) {
			$results = call_user_func('self::' . $method);
		}
		
		return $results;
	}
	
	function AjaxImportfromfolderck() {
		$app = JFactory::getApplication();
		$input = $app->input;

		$folder = $input->get('folder', '', 'string');
		$imgdir = JPATH_ROOT . '/' . trim(trim($folder, "/"), "\\");
		$imgs = JFolder::files($imgdir, '.jpg|.png|.jpeg|.gif|.JPG|.JPEG|.jpeg', false, true);
		$imgs = str_replace(JPATH_ROOT, "", $imgs);
		$imgs = str_replace("\\", "/", $imgs);
		foreach ($imgs as &$img) {
			$img = trim($img, "/");
		}
		$imgs = json_encode($imgs);
		// $imgs = str_replace("\"", "|qq|", $imgs);

		return $imgs;
	}
}