<?php

/**
 * @copyright	Copyright (C) 2015 Cedric KEIFLIN alias ced1870
 * http://www.joomlack.fr
 * @license		GNU/GPL
 * */
// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.form.form');
jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');
// error_reporting(0);

class JFormFieldckCheckproversion extends JFormField {

	protected $type = 'ckcheckproversion';

	protected function getLabel() {

		if (file_exists(JPATH_ROOT . '/plugins/system/mediabox_ck/pro/mediaboxck_pro.php')) {
			$ispro = true;
		} else {
			$ispro = false;
		}

		$imgpath = JUri::root(true) . '/plugins/system/mediabox_ck/elements/images/';
		$version_text = $ispro ? JText::_('MEDIABOXCK_VERSION_PRO') : '<a href="http://www.joomlack.fr/en/joomla-extensions/mediabox-ck" target="_blank">' . JText::_('MEDIABOXCK_VERSION_FREE') . '</a>';
		$icon = $ispro ? 'accept.png' : 'cross.png';

		$html = '<div style="background:#efefef;border: none;border-radius: 3px;color: #333;font-weight: normal;line-height: 24px;padding: 5px;margin: 3px 0;text-align: left;text-decoration: none;"><img style="margin: 0 10px 5px 5px;" src="' . $imgpath . $icon . '">' . $version_text . '</div>';

		return $html;
	}

	protected function getInput() {

		return '';
	}
}

