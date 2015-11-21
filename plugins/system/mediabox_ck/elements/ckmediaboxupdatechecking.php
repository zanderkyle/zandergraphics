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

class JFormFieldCkmediaboxupdatechecking extends JFormField {

	protected $type = 'ckmediaboxupdatechecking';

	protected function getLabel() {
		if (file_exists(JPATH_ROOT . '/plugins/system/mediabox_ck/pro/mediaboxck_pro.php')) {
			$ispro = true;
		} else {
			$ispro = false;
		}

		// get the version installed
		$installed_version = false;
		$file_url = JPATH_SITE .'/plugins/system/mediabox_ck/mediabox_ck.xml';
		if (! $xml_installed = JFactory::getXML($file_url)) {
			die;
		} else {
			$installed_version = (string)$xml_installed->version;
		}

		$imgpath = JUri::root(true) . '/plugins/system/mediabox_ck/elements/images/';

		$js_checking = '';
	
			$js_checking = '<script>
			jQuery(document).ready(function (){
				// check the release notes
				updateck = function() {}; // needed to avoid errors on bad request
				jQuery.ajax({
						type: "GET",
						url: "http://update.joomlack.fr/mediaboxck_update.json?callback=?",
						jsonpCallback: "updateck",
						contentType: "application/json",
						dataType: "jsonp",
					}).done(function(response) {
						for (var version in response) {
							if (compareVersions(version,"' . $installed_version . '")) {
								if (! jQuery("#updatealert").text().length) {
									jQuery("#updatealert").append("<span class=\"label label-warning\" style=\"font-size:1em;padding:0.4em;\">' . JText::_('MEDIABOXCK_NEW_VERSION_AVAILABLE') . '</span>");
									jQuery("#updatealert").append("<a href=\"http://www.joomlack.fr/en/joomla-extensions/mediabox-ck\" target=\"_blank\" class=\"pull-right btn btn-info\" style=\"font-size:1em;padding:0.4em;\"><i class=\"icon icon-download\"></i>' . JText::_('MEDIABOXCK_DOWNLOAD') . '</a>");
								}
								var notes = writeVersionInfo(response, version);
								jQuery(".updatechecking").append(notes);
							}
						}
					}).fail(function( jqxhr, textStatus, error ) {
						// var err = textStatus + ", " + error;
						// console.log( "Request Failed: " + err );
					});
				
			});
			
			function compareVersions(installed, required) {
				var a = installed.split(".");
				var b = required.split(".");

				for (var i = 0; i < a.length; ++i) {
					a[i] = Number(a[i]);
				}
				for (var i = 0; i < b.length; ++i) {
					b[i] = Number(b[i]);
				}
				if (a.length == 2) {
					a[2] = 0;
				}

				if (a[0] > b[0]) return true;
				if (a[0] < b[0]) return false;

				if (a[1] > b[1]) return true;
				if (a[1] < b[1]) return false;

				if (a[2] > b[2]) return true;
				if (a[2] < b[2]) return false;

				return false;
			}
			
			function writeVersionInfo(response, version) {
				var txt = "<div>";
				txt += "<strong class=\"badge\">Version : " + version + "</strong>";
				txt += " - Date : " + response[version]["date"];
				txt += "</div>";
				txt += "<ul>";
				for (var note in response[version]["notes"]) {
					txt += "<li>" + response[version]["notes"][note] + "</li>";
				}
				txt += "</ul>";
				// txt += "<br />";
				return txt;
			}
		</script>';

		$html = '<style>.updatechecking { /*background:#efefef;*/
	border: none;
    border-radius: 3px;
    color: #333;
    font-weight: normal;
	line-height: 24px;
    padding: 5px;
	margin: 3px 0;
    text-align: left;
    text-decoration: none;
    }
	.updatechecking img {
	margin: 5px;
    }</style>';

		$version_text = $ispro ? JText::_('MEDIABOXCK_VERSION_PRO') : '<a href="http://www.joomlack.fr/en/joomla-extensions/mediabox-ck" target="_blank">' . JText::_('MEDIABOXCK_VERSION_FREE') . '</a>';
		$icon = $ispro ? 'accept.png' : 'information.png';

		$html .= '<div style="background:#efefef;border: none;border-radius: 3px;color: #333;font-weight: normal;line-height: 24px;padding: 5px;margin: 3px 0;text-align: left;text-decoration: none;"><img style="margin: 0 10px 5px 5px;" src="' . $imgpath . $icon . '">' . $version_text . '</div>';
		$html .= '<div>' . JText::_('MEDIABOXCK_YOU_HAVE_VERSION') . ' : <span class="label">' . $installed_version . '</span></div>';
		$html .= '<hr />';
		$html .= '<div id="updatealert"></div>';
		$html .= '<div class="updatechecking"></div>';

		$html .= $js_checking;
		return $html;
	}

	protected function getInput() {

		return '';
	}
}

