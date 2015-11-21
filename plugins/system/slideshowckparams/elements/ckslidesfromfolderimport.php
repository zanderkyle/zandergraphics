<?php

/**
 * @copyright	Copyright (C) 2013 Cedric KEIFLIN alias ced1870
 * http://www.joomlack.fr
 * @license		GNU/GPL
 * */
// no direct access
defined('_JEXEC') or die('Restricted access');
jimport('joomla.filesystem.folder');

class JFormFieldCkslidesfromfolderimport extends JFormField {

	protected $type = 'ckslidesfromfolderimport';

	protected function getInput() {

		$document = JFactory::getDocument();
		$path = 'modules/mod_slideshowck/elements/ckslidesfromfoldermanager/';
		JHtml::_('script', $path . 'ckslidesfromfoldermanager.js');
		JHtml::_('stylesheet', $path . 'ckslidesfromfoldermanager.css');
		$this->value = $this->value ? $this->value : 'modules/mod_slideshowck/images/slides2';
		$imgdir = JPATH_ROOT . '/' . trim($this->value, "/");
		$imgs = JFolder::files($imgdir, '.jpg|.png|.jpeg|.gif|.JPG|.JPEG|.jpeg', false, true);
		$imgs = str_replace(JPATH_ROOT, "", $imgs);
		$imgs = str_replace("\\", "/", $imgs);
		foreach ($imgs as &$img) {
			$img = trim($img, "/");
		}
		$imgs = json_encode($imgs);
		$imgs = str_replace("\"", "|qq|", $imgs);

		$html = '';
		$icon = $this->element['icon'];
		// $html .= '<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>';
		$html .= $icon ? '<img src="' . JURI::root() . 'modules/mod_slideshowck/elements/images/' . $icon . '" style="margin-right:5px;" />' : '<div style="float:left;width:15px;margin-right:5px;">&nbsp;</div>';
		$html .= '<input name="' . $this->name . '" id="' . $this->id . '" type="text" value="' . $this->value . '" style="border-radius:3px;-moz-border-radius:3px;padding:1px;"/>'
				. '<input type="button" class="btn" onclick="showfolderslistck();" value="' . JText::_('PLG_SLIDESHOWCK_SELECT') . '" style="width:100px;border-radius:3px;-moz-border-radius:3px;padding:1px;"/>'
				. '<input type="button" class="btn btn-primary" onclick="importfromfolderck(\'' . $imgs . '\');" value="' . JText::_('PLG_SLIDESHOWCK_IMPORT') . '" style="width:100px;border-radius:3px;-moz-border-radius:3px;padding:1px;"/>';

		$html .= '<div id="cklistfolders">';
		$html .= self::listFolders(JPATH_ROOT . '/images', '.', $level = 1);
		$html .= '</div>';

		return $html;
	}
	
	protected function listFolders($path, $filter, $level = 1) {
		$fhtml = '';
		$folders = JFolder::folders($path, $filter);
		if (! count($folders)) return;
		if ($level > 1) {
			$labelfor = str_replace('\\', '', str_replace('/', '', str_replace(JPATH_ROOT, '', $path)));
			$fhtml .=  '<input type="checkbox" style="display: none;" name="' . $labelfor . '" id="' . $labelfor . '"/>';
			$fhtml .=  '<label class="cksubfoldertoggler" for="' . $labelfor . '">+</label>';
			$fhtml .=  '<div class="cksubfolder">';
		}
		foreach ($folders as $name)
		{
			$fullname = JPath::clean($path . '/' . $name);
			$relname = str_replace(JPATH_ROOT, '', $fullname);

			$fhtml .= '<div class="ckfolderitem"><span style="display:inline-block;padding-left:' . ($level*20) . 'px"><img src="' . JUri::root(true) . '/modules/mod_slideshowck/elements/images/folder.png" width="16" height="16" /></span>'
				.'<span class="ckfoldername" data-foldername="' . $relname . '" onclick="selectfolderck(this, \'' . $this->id . '\')">' . $name . '</span></div>';
			$fhtml .= self::listFolders($fullname, $filter, $level + 1);
		}
		if ($level > 1) {
			$fhtml .=  '</div>';
		}

		return $fhtml;
	}

	protected function getPathToImages() {
		$localpath = dirname(__FILE__);
		$rootpath = JPATH_ROOT;
		$httppath = trim(JURI::root(), "/");
		$pathtoimages = str_replace("\\", "/", str_replace($rootpath, $httppath, $localpath));
		return $pathtoimages;
	}

}

