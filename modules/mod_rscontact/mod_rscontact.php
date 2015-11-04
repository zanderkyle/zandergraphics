<?php
/**
* @package RSContact!
* @copyright (C) 2015 www.rsjoomla.com
* @license GPL, http://www.gnu.org/licenses/gpl-3.0.html
*/

defined('_JEXEC') or die('Restricted access');

// Load our helper file
require_once dirname(__FILE__).'/helper.php';

// Load jQuery
if ($params->get('jquery')){
	JHtml::_('jquery.framework');
}

// Load form validation
JHtml::_('behavior.formvalidation');

// Load our scripts
modRSContactHelper::loadJS('jquery.validate');
modRSContactHelper::loadJS('jquery.placeholder');
modRSContactHelper::loadJS('rscontact');

// Load our stylesheet
modRSContactHelper::loadCSS('rscontact');

// Load extra scripts & stylesheets
$document = JFactory::getDocument();
if ($css = $params->get('css')) {
	$document->addStyleDeclaration($css);
}
if ($js = $params->get('js')) {
	$document->addScriptDeclaration($js);
}

// Get a unique id for the module to be used as a suffix
$uniqid = $module->id;

// Define parameters
$form_pre_text			= $params->get('form_pre');
$form_post_text			= $params->get('form_post');
$show_salutation		= $params->get('salut');
$show_salutation_req 	= $show_salutation == 2;
$show_label				= !$params->get('label');
$show_name				= $params->get('name');
$show_name_req			= $show_name == 2;
$name_type				= $params->get('name_type');
$show_full_name			= $name_type == 1;
$show_address_1			= $params->get('addr_1');
$address_1_req			= $show_address_1 == 2;
$show_address_2			= $params->get('addr_2');
$address_2_req			= $show_address_2 == 2;
$show_city				= $params->get('city');
$show_city_req			= $show_city == 2;
$show_state				= $params->get('state');
$show_state_req			= $show_state == 2;
$show_zip				= $params->get('zip');
$show_zip_req			= $show_zip == 2;
$show_home_phone		= $params->get('h_phone');
$home_phone_req			= $show_home_phone == 2;
$show_mobile_phone		= $params->get('m_phone');
$mobile_phone_req		= $show_mobile_phone == 2;
$show_work_phone		= $params->get('w_phone');
$work_phone_req			= $show_work_phone == 2;
$show_company			= $params->get('comp');
$company_req			= $show_company == 2;
$show_website			= $params->get('web');
$website_req			= $show_website == 2;
$show_subject			= $params->get('subj');
$subject_req			= $show_subject == 2;
$subject_type			= $params->get('subj_type');
$subject_input			= $params->get('subject_input');
$required_marker		= $params->get('req_marker');
$show_send_copy			= $params->get('send_copy');
$show_send_copy_to_self = $show_send_copy == 2;
$show_message			= $params->get('msg');
$message_req			= $show_message == 2;
$msg_len				= (int) $params->get('msg_len', 1000) ? (int) $params->get('msg_len', 1000) : 1000;
$auto_width				= $params->get('auto_width');
$form_horizontal		= $params->get('form_h');
$show_captcha			= $params->get('captcha');

// Custom fields
$show_cf1			= $params->get('cf1');
$show_cf1_req		= $show_cf1 == 2;
$show_cf1_type		= $params->get('cf1_type');
$cf1_name			= $params->get('cf1_name');
$cf1_input			= $params->get('cf1_inp');
$show_cf2			= $params->get('cf2');
$show_cf2_req		= $show_cf2 == 2;
$show_cf2_type		= $params->get('cf2_type');
$cf2_name			= $params->get('cf2_name');
$cf2_input			= $params->get('cf2_inp');
$show_cf3			= $params->get('cf3');
$show_cf3_req		= $show_cf3 == 2;
$show_cf3_type		= $params->get('cf3_type');
$cf3_name			= $params->get('cf3_name');
$cf3_input			= $params->get('cf3_inp');
$options			= '';

require JModuleHelper::getLayoutPath('mod_rscontact');