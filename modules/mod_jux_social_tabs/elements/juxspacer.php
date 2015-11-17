<?php
/**
 * @version  $Id$
 * @author  JoomlaUX!
 * @package  Joomla.Site
 * @subpackage mod_jux_slideshow
 * @copyright Copyright (C) 2012 - 2013 by JoomlaUX. All rights reserved.
 * @license  http://www.gnu.org/licenses/gpl.html
 */

defined('JPATH_BASE') or die;

jimport('joomla.html.html');
//jimport('joomla.form.fields.spacer');
require_once(JPATH_ROOT.'/libraries/joomla/form/fields/spacer.php');

/**
 * Extent the Spacer element of Joomla
 */
class JFormFieldJUXSpacer extends JFormFieldSpacer {

	/**
	 * The form field type.
	 *
	 * @var    string
	 */
	protected $type = 'JUXSpacer';

	/**
	 * Return a blank div instead of blank string for better usage.
	 *
	 * @return  string  The field input markup.
	 *
	 * @since   11.1
	 */
	protected function getInput() {
//		if (!defined ('_JUX_RADIO_OPTION_ASSETS')) {
//			define ('_JUX_RADIO_OPTION_ASSETS', 1);
//			$uri = str_replace('\\',"/",str_replace( JPATH_SITE, JURI::base (), dirname(__FILE__) ));
//			$uri = str_replace("/administrator/", "", $uri);
//			JHTML::script($uri.'/assets/js/juxoptions.js');	
//		}

		$html	= '<div id="'.$this->id.'"></div>';
		
		return $html;
	}

	/**
	 * Override getLabel function for hiding Label when don't need.
	 * 
	 * @return string	Label if label element is set, blank otherwise
	 */
	protected function getLabel_() {
		if($this->element['label']) {
			return parent::getLabel();
		} else {
			return '';
		}
	}
}