<?php
/**
 * Joomla! component Creative Contact Form
 *
 * @version $Id: 2012-04-05 14:30:25 svn $
 * @author creative-solutions.net
 * @package Creative Contact Form
 * @subpackage com_creativecontactform
 * @license GNU/GPL
 *
 */

// no direct access
defined('_JEXEC') or die('Restircted access');

class com_creativecontactformInstallerScript {

    /**
     * method to install the component
     *
     * @return void
     */
    function install($parent) {
        // installing module
        $module_installer = new JInstaller;
        if(@$module_installer->install(dirname(__FILE__).DIRECTORY_SEPARATOR.'modules'.DIRECTORY_SEPARATOR.'module'))
            echo '<p>'.JText::_('COM_CREATIVECONTACTFORM_MODULE_INSTALL_SUCCESS').'</p>';
        else
           echo '<p>'.JText::_('COM_CREATIVECONTACTFORM_MODULE_INSTALL_FAILED').'</p>';

       // installing plugin
        $plugin_installer = new JInstaller;
        if($plugin_installer->install(dirname(__FILE__).DIRECTORY_SEPARATOR.'plugins'.DIRECTORY_SEPARATOR.'plugin'))
             echo '<p>'.JText::_('COM_CREATIVECONTACTFORM_PLUGIN_INSTALL_SUCCESS').'</p>';
        else
            echo '<p>'.JText::_('COM_CREATIVECONTACTFORM_PLUGIN_INSTALL_FAILED').'</p>';
        
        // enabling plugin
        $db = JFactory::getDBO();
        $db->setQuery('UPDATE #__extensions SET enabled = 1 WHERE element = "creativecontactform" AND folder = "system"');
        $db->query();
    }

    /**
     * method to uninstall the component
     *
     * @return void
     */
    function uninstall($parent) {
        // $parent is the class calling this method
        //echo '<p>' . JText::_('COM_HELLOWORLD_UNINSTALL_TEXT') . '</p>';

        $db = JFactory::getDBO();
        
        
        
        $sql = 'SELECT `extension_id` AS id, `name`, `element`, `folder` FROM #__extensions WHERE `type` = "module" AND ( (`element` = "mod_creativecontactform") ) ';
        $db->setQuery($sql);
        $creative_module = $db->loadObject();
        $module_uninstaller = new JInstaller;
        if($module_uninstaller->uninstall('module', $creative_module->id))
        	 echo '<p>'.JText::_('COM_CREATIVECONTACTFORM_MODULE_UNINSTALL_SUCCESS').'</p>';
        else
        	echo '<p>'.JText::_('COM_CREATIVECONTACTFORM_MODULE_UNINSTALL_FAILED').'</p>';

         // uninstalling creative image slider plugin
        $db->setQuery("select extension_id from #__extensions where type = 'plugin' and element = 'creativecontactform'");
        $creative_plugin = $db->loadObject();
        $plugin_uninstaller = new JInstaller;
        if($plugin_uninstaller->uninstall('plugin', $creative_plugin->extension_id))
            echo '<p>'.JText::_('COM_CREATIVECONTACTFORM_PLUGIN_UNINSTALL_SUCCESS').'</p>';
        else
            echo '<p>'.JText::_('COM_CREATIVECONTACTFORM_PLUGIN_UNINSTALL_FAILED').'</p>';
    }

    /**
     * method to update the component
     *
     * @return void
     */
    function update($parent) {
        $module_installer = new JInstaller;
        if(@$module_installer->install(dirname(__FILE__).DIRECTORY_SEPARATOR.'modules'.DIRECTORY_SEPARATOR.'module'))
            echo '<p>'.JText::_('COM_CREATIVECONTACTFORM_MODULE_INSTALL_SUCCESS').'</p>';
        else
           echo '<p>'.JText::_('COM_CREATIVECONTACTFORM_MODULE_INSTALL_FAILED').'</p>';

        $plugin_uninstaller = new JInstaller;
        if(@$plugin_uninstaller->install(dirname(__FILE__).DIRECTORY_SEPARATOR.'plugins'.DIRECTORY_SEPARATOR.'plugin'))
            echo '<p>'.JText::_('COM_CREATIVECONTACTFORM_PLUGIN_INSTALL_SUCCESS').'</p>';
        else
           echo '<p>'.JText::_('COM_CREATIVECONTACTFORM_PLUGIN_INSTALL_FAILED').'</p>';
    }

    /**
     * method to run before an install/update/uninstall method
     *
     * @return void
     */
    function preflight($type, $parent) {
        // $parent is the class calling this method
        // $type is the type of change (install, update or discover_install)
        //echo '<p>' . JText::_('COM_HELLOWORLD_PREFLIGHT_' . $type . '_TEXT') . '</p>';
    }

    /**
     * method to run after an install/update/uninstall method
     *
     * @return void
     */
    function postflight($type, $parent) {
        $db = JFactory::getDBO();

       //2.0.1 -> 3.0.0 update/////////////////////////////////////////////////////////////////////////////////////////////
        $query = "SELECT * FROM `#__creative_fields` LIMIT 1";
        $db->setQuery($query);
        $columns_data = $db->LoadAssoc();
        
        if(is_array($columns_data)) {
            $columns_titles = array_keys($columns_data);
            if(!in_array('datepicker_date_format',$columns_titles)) {
                //add required columns to __creative_fields
                $query_update = "
                                    ALTER TABLE  `#__creative_fields`   
                                        ADD `tooltip_text` text not null after `name`,
                                        ADD `field_margin_top` text not null after `width`,
                                        ADD `column_type` tinyint not null DEFAULT  '0',
                                        ADD `datepicker_date_format` text not null,
                                        ADD `datepicker_animation` text not null,
                                        ADD `datepicker_style` SMALLINT UNSIGNED NOT NULL DEFAULT  '1',
                                        ADD `datepicker_icon_style` SMALLINT NOT NULL DEFAULT  '1',
                                        ADD `datepicker_show_icon` SMALLINT UNSIGNED NOT NULL DEFAULT  '1',
                                        ADD `datepicker_input_readonly` SMALLINT UNSIGNED NOT NULL DEFAULT  '1',
                                        ADD `datepicker_number_months` SMALLINT UNSIGNED NOT NULL DEFAULT  '1',
                                        ADD `datepicker_mindate` text not null,
                                        ADD `datepicker_maxdate` text not null,
                                        ADD `datepicker_changemonths` SMALLINT UNSIGNED NOT NULL DEFAULT  '0',
                                        ADD `datepicker_changeyears` SMALLINT UNSIGNED NOT NULL DEFAULT  '0',
                                        ADD `custom_html` text not null,
                                        ADD `heading` text not null,
                                        ADD `google_maps` text not null,
                                        ADD `recaptcha_site_key` text not null,
                                        ADD `recaptcha_security_key` text not null,
                                        ADD `recaptcha_wrong_message` text not null,
                                        ADD `recaptcha_theme` text not null,
                                        ADD `recaptcha_type` text not null,
                                        ADD `contact_data` text not null,
                                        ADD `contact_data_width` SMALLINT UNSIGNED NOT NULL DEFAULT '120',
                                        ADD `creative_popup` text not null,
                                        ADD `creative_popup_embed` text not null
                                    ";
                $db->setQuery($query_update);
                $db->query();

                //add required columns to __creative_forms
                $query_update = "
                                    ALTER TABLE  `#__creative_forms`   
                                        ADD `email_info_show_referrer` tinyint not null DEFAULT  '1',
                                        ADD `email_info_show_ip` tinyint not null DEFAULT  '1',
                                        ADD `email_info_show_browser` tinyint not null DEFAULT  '1',
                                        ADD `email_info_show_os` tinyint not null DEFAULT  '1',
                                        ADD `email_info_show_sc_res` tinyint not null DEFAULT  '1',
                                        ADD `custom_css` text not null
                                    ";
                $db->setQuery($query_update);
                $db->query();


                //add new field types
                $query_update = "INSERT INTO  `#__creative_field_types`(`id`, `name`) VALUES (NULL, 'Datepicker'),(NULL, 'Custom Html'),(NULL, 'Heading'),(NULL, 'Google Maps'),(NULL, 'Google reCAPTCHA'),(NULL, 'Contact Data'),(NULL, 'Creative Popup')";
                $db->setQuery($query_update);
                $db->query();


                //update templates
                $query = "SELECT `id`,`styles` FROM `#__contact_templates`";
                $db->setQuery($query);
                $styles = $db->LoadAssocList();
                $addon_styles = '587~#111111|588~13|589~1|627~1|517~50|518~50|600~0|601~#ffffff|602~#ffffff|603~15|604~15|605~15|606~15|607~0|608~solid|609~#ffffff|610~0|611~#ffffff|612~#ffffff|613~5|614~15|615~10|616~15|617~0|618~#ffffff|619~#ffffff|620~15|621~15|622~15|623~15|624~0|625~solid|626~#ffffff|506~inherit|510~ccf_font_effect_none|502~left|511~ccf_font_effect_none|507~inherit|512~ccf_font_effect_none|509~inherit|505~white|508~inherit|519~85|520~90|500~left|501~left|513~ccf_font_effect_none|514~ccf_font_effect_none|521~85|522~90|523~130|535~8|536~15|537~9|538~12|539~15|540~15|541~#e3e3e3|542~#e6e6e6|543~1|544~1|545~1|546~1|547~dotted|548~#b8b8b8|549~#b8b8b8|550~#b8b8b8|551~#b8b8b8|524~#525252|525~15|526~normal|527~normal|528~none|529~inherit|530~ccf_font_effect_none|531~#6b6b6b|532~0|533~0|534~0|515~ccf_font_effect_none|516~ccf_font_effect_none|552~1|553~#3d3d3d|554~14|555~normal|556~normal|596~none|590~0|591~dotted|592~#fafafa|558~#ffffff|559~0|560~0|561~0|563~10|562~1|597~10|598~30|564~#0055cc|565~bold|566~normal|594~none|567~1|568~dotted|569~#ffffff|570~#ffffff|571~0|572~0|573~0|574~#b00023|595~none|575~#b50000|576~#ffffff|577~0|578~0|579~0|580~#008f00|581~normal|582~italic|593~none|583~#ffffff|584~0|585~0|586~0|599~|629~dark-thin|630~dark-thin|589~1|628~';
                if(is_array($styles)) {
                    foreach($styles as $val) {
                        $t_id = $val["id"];
                        $t_styles = $val["styles"];
                        $updates_styles = addslashes($t_styles) . '|' . addslashes($addon_styles);

                        $query_update_t = "UPDATE `#__contact_templates` SET `styles` = '".$updates_styles."' WHERE `id` = '".$t_id."'";
                        $db->setQuery($query_update_t);
                        $db->query();
                    }
                }
                //END update styles
                
            }

        }
        // END 2.0.1->3.0.0 update////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


        // INSTALL TEMPLATES////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

        $query = "SELECT `name` FROM `#__contact_templates`";
        $db->setQuery($query);
        $tmp_names_array = $db->LoadAssocList();

        $tmp_names = array();
        if(is_array($tmp_names_array)) {
            foreach($tmp_names_array as $k => $tmp_name) {
                $tmp_names[] = $tmp_name["name"];
            }
        }

        if(!in_array('White Template 1', $tmp_names)) {

            $ccf_templates_array  = array
                                            (
                                                'White Template 1' => '587~#111111|588~13|131~Arial, Helvetica, sans-serif|589~1|629~inset-2-dark|630~inset-2-dark|627~1|0~#ffffff|130~#ffffff|517~50|518~50|1~#dedede|2~1|3~solid|4~0|5~0|6~0|7~0|8~#ffffff|9~|10~0|11~0|12~0|13~0|14~#bababa|15~|16~0|17~0|18~7|19~0|600~0|601~#ffffff|602~#ffffff|603~15|604~15|605~15|606~15|607~0|608~solid|609~#ffffff|610~0|611~#ffffff|612~#ffffff|613~5|614~15|615~10|616~15|617~0|618~#ffffff|619~#ffffff|620~0|621~15|622~15|623~15|624~0|625~solid|626~#ffffff|20~#000000|21~28|22~normal|23~normal|24~none|25~left|506~inherit|510~ccf_font_effect_none|27~#ffffff|28~2|29~1|30~2|190~3|191~0|192~82|502~left|193~3|194~1|195~#a8a8a8|196~dotted|197~#000000|198~13|199~normal|200~normal|201~none|202~inherit|511~ccf_font_effect_none|203~#ffffff|204~0|205~0|206~0|215~0|216~0|217~1|218~3|31~#000000|32~13|33~normal|34~normal|35~none|36~left|507~inherit|512~ccf_font_effect_none|37~#ffffff|38~2|39~1|40~1|41~#ff0000|42~20|43~normal|44~normal|509~inherit|46~#ffffff|47~0|48~0|49~0|505~white|508~inherit|132~#ffffff|133~#ffffff|168~60|519~90|520~90|500~left|501~left|134~#cccccc|135~1|136~solid|137~0|138~0|139~0|140~0|141~#f5f5f5|142~|143~0|144~0|145~10|146~0|147~#000000|148~14|149~normal|150~normal|151~none|152~inherit|153~#ffffff|154~0|155~0|156~0|157~#ffffff|158~#ffffff|159~#1c1c1c|160~#ffffff|161~#cccccc|162~#d4d4d4|163~|164~0|165~0|166~10|167~2|513~ccf_font_effect_none|176~#ffdbdd|177~#ffdbdd|178~#ff9999|179~#363636|180~#ffffff|181~0|182~0|183~0|184~#ebaaaa|185~inset|186~0|187~0|188~19|189~0|171~#c70808|514~ccf_font_effect_none|172~#ffffff|173~2|174~1|175~1|169~95|521~90|522~90|170~150|523~130|535~8|536~15|537~9|538~12|539~15|540~15|541~#f4f6f7|542~#f4f6f7|543~1|544~1|545~1|546~1|547~solid|548~#d4d4d4|549~#d4d4d4|550~#d4d4d4|551~#d4d4d4|524~#525252|525~15|526~normal|527~normal|528~none|529~inherit|530~ccf_font_effect_none|531~#e0e0e0|532~0|533~0|534~0|91~#ffffff|50~#e0e0e0|212~right|92~12|93~26|209~95|100~#c2c2c2|101~1|127~solid|102~0|103~0|104~0|105~0|94~#525252|95~|96~0|97~0|98~0|99~0|106~#666666|107~14|108~bold|109~normal|110~none|112~inherit|515~ccf_font_effect_none|113~#ffffff|114~0|115~0|116~3|51~#ffffff|52~#e0e0e0|124~#6b6b6b|516~ccf_font_effect_none|125~#ffffff|126~#cfcfcf|117~#999999|118~|119~0|120~0|121~6|122~0|552~1|553~#3d3d3d|554~14|555~normal|556~normal|596~none|590~0|591~dotted|592~#fafafa|558~#ffffff|559~0|560~0|561~0|563~10|562~1|597~10|598~30|564~#0055cc|565~bold|566~normal|594~none|567~1|568~dotted|569~#ffffff|570~#ffffff|571~0|572~0|573~0|574~#b00023|595~none|575~#b50000|576~#ffffff|577~0|578~0|579~0|580~#008f00|581~normal|582~italic|593~none|583~#ffffff|584~0|585~0|586~0|599~/*Creative Scrollbar***************************************/\n.creative_form_FORM_ID .creative_content_scrollbar {\nbackground-color: #ddd;\ncolor: #333;\nborder-radius: 12px;\n}\n.creative_form_FORM_ID .creative_content_scrollbar hr {\nborder-bottom: 1px solid rgba(255,255,255,0.6);\nborder-top: 1px solid rgba(0,0,0,0.2);\n}\n.creative_form_FORM_ID p.scrollbar_light {\npadding: 5px 10px;\nborder-radius: 6px;\ncolor: #666;\nbackground: #fff;\nbackground: rgba(255,255,255,0.8);\n}|628~'
                                            );
            $query_insert = "INSERT IGNORE INTO `#__contact_templates` (`id`, `name`, `created`, `date_start`, `date_end`, `publish_up`, `publish_down`, `published`, `checked_out`, `checked_out_time`, `access`, `featured`, `ordering`, `language`, `styles`) VALUES ";
            $k = 1;
            foreach($ccf_templates_array as $tmp_name => $tmp_row) {
                $query_last_symbol = $k == sizeof($ccf_templates_array) ? ';' : ',';
                if(!in_array($tmp_name,$tmp_names))
                    $query_insert .= "(NULL, '".$tmp_name."', '0000-00-00 00:00:00', '0000-00-00', '0000-00-00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 1, 0, '0000-00-00 00:00:00', 0, 0, 0, '', '".$tmp_row."')". $query_last_symbol;
                $k ++;
            }
                            
            $db->setQuery($query_insert);
            $db->query();

        }


        // INSTALL FORMS ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $query = 'SELECT COUNT(id) AS count_id FROM #__creative_forms';
        $db->setQuery($query);
        $count_forms = $db->loadResult();

        $countries_array = array("Afghanistan","Albania","Algeria","American Samoa","Andorra","Angola","Anguilla","Antarctica","Antigua and Barbuda","Argentina","Armenia","Aruba","Australia","Austria","Azerbaijan","Bahamas","Bahrain","Bangladesh","Barbados","Belarus","Belgium","Belize","Benin","Bermuda","Bhutan","Bolivia","Bosnia and Herzegowina","Botswana","Bouvet Island","Brazil","British Indian Ocean Territory","Brunei Darussalam","Bulgaria","Burkina Faso","Burundi","Cambodia","Cameroon","Canada","Cape Verde","Cayman Islands","Central African Republic","Chad","Chile","China","Christmas Island","Cocos (Keeling) Islands","Colombia","Comoros","Congo","Cook Islands","Costa Rica","Cote D","Croatia","Cuba","Cyprus","Czech Republic","Democratic Republic of Congo","Denmark","Djibouti","Dominica","Dominican Republic","East Timor","Ecuador","Egypt","El Salvador","Equatorial Guinea","Eritrea","Estonia","Ethiopia","Falkland Islands (Malvinas)","Faroe Islands","Fiji","Finland","France","France, Metropolitan","French Guiana","French Polynesia","French Southern Territories","Gabon","Gambia","Georgia","Germany","Ghana","Gibraltar","Greece","Greenland","Grenada","Guadeloupe","Guam","Guatemala","Guinea","Guinea-bissau","Guyana","Haiti","Heard and Mc Donald Islands","Honduras","Hong Kong","Hungary","Iceland","India","Indonesia","Iran","Iraq","Ireland","Israel","Italy","Jamaica","Japan","Jordan","Kazakhstan","Kenya","Kiribati","Korea","Kuwait","Kyrgyzstan","Lao People","Latvia","Lebanon","Lesotho","Liberia","Libyan Arab Jamahiriya","Liechtenstein","Lithuania","Luxembourg","Macau","Macedonia","Madagascar","Malawi","Malaysia","Maldives","Mali","Malta","Marshall Islands","Martinique","Mauritania","Mauritius","Mayotte","Mexico","Micronesia","Moldova","Monaco","Mongolia","Montserrat","Morocco","Mozambique","Myanmar","Namibia","Nauru","Nepal","Netherlands","Netherlands Antilles","New Caledonia","New Zealand","Nicaragua","Niger","Nigeria","Niue","Norfolk Island","North Korea","Northern Mariana Islands","Norway","Oman","Pakistan","Palau","Panama","Papua New Guinea","Paraguay","Peru","Philippines","Pitcairn","Poland","Portugal","Puerto Rico","Qatar","Reunion","Romania","Russian Federation","Rwanda","Saint Kitts and Nevis","Saint Lucia","Saint Vincent and the Grenadines","Samoa","San Marino","Sao Tome and Principe","Saudi Arabia","Senegal","Seychelles","Sierra Leone","Singapore","Slovak Republic","Slovenia","Solomon Islands","Somalia","South Africa","South Georgia &amp; South Sandwich Islands","Spain","Sri Lanka","St. Helena","St. Pierre and Miquelon","Sudan","Suriname","Svalbard and Jan Mayen Islands","Swaziland","Sweden","Switzerland","Syrian Arab Republic","Taiwan","Tajikistan","Tanzania","Thailand","Togo","Tokelau","Tonga","Trinidad and Tobago","Tunisia","Turkey","Turkmenistan","Turks and Caicos Islands","Tuvalu","Uganda","Ukraine","United Arab Emirates","United Kingdom","United States","United States Minor Outlying Islands","Uruguay","Uzbekistan","Vanuatu","Vatican City State (Holy See)","Venezuela","Viet Nam","Virgin Islands (British)","Virgin Islands (U.S.)","Wallis and Futuna Islands","Western Sahara","Yemen","Yugoslavia","Zambia","Zimbabwe");

        if($count_forms == 0) {

            // insert form 1 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            $query_insert = 
                            "
                                INSERT IGNORE INTO `#__creative_forms` (`id`, `email_to`, `email_bcc`, `email_subject`, `email_from`, `email_from_name`, `email_replyto`, `email_replyto_name`, `shake_count`, `shake_distanse`, `shake_duration`, `id_template`, `name`, `top_text`, `pre_text`, `thank_you_text`, `send_text`, `send_new_text`, `close_alert_text`, `form_width`, `alias`, `created`, `publish_up`, `publish_down`, `published`, `checked_out`, `checked_out_time`, `access`, `featured`, `ordering`, `language`, `redirect`, `redirect_itemid`, `redirect_url`, `redirect_delay`, `send_copy_enable`, `send_copy_text`) VALUES
                                (NULL, '', '', '', '', '', '', '', 2, 10, 300, 1, 'Basic Contact Form', 'Contact Us', 'Feel free to contact us if you have any questions', 'Message successfully sent', 'Send', 'New email', 'OK', '100%', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 1, 0, '0000-00-00 00:00:00', 1, 0, 0, '', '0', 103, '', 0, '1', 'Send me a copy');
                            ";
            $db->setQuery($query_insert);
            $db->query();
            $form_id = $db->insertid();

            // insert fields
            $query_insert = 
                            "
                                INSERT IGNORE INTO `#__creative_fields` (`id`, `id_user`, `id_form`, `name`, `tooltip_text`, `id_type`, `alias`, `created`, `publish_up`, `publish_down`, `published`, `checked_out`, `checked_out_time`, `access`, `featured`, `ordering`, `language`, `required`, `width`, `field_margin_top`, `select_show_scroll_after`, `select_show_search_after`, `message_required`, `message_invalid`, `ordering_field`, `show_parent_label`, `select_default_text`, `select_no_match_text`, `upload_button_text`, `upload_minfilesize`, `upload_maxfilesize`, `upload_acceptfiletypes`, `upload_minfilesize_message`, `upload_maxfilesize_message`, `upload_acceptfiletypes_message`, `captcha_wrong_message`, `datepicker_date_format`, `datepicker_animation`, `datepicker_style`, `datepicker_icon_style`, `datepicker_show_icon`, `datepicker_input_readonly`, `datepicker_number_months`, `datepicker_mindate`, `datepicker_maxdate`, `datepicker_changemonths`, `datepicker_changeyears`, `column_type`, `custom_html`, `google_maps`, `heading`, `recaptcha_site_key`, `recaptcha_security_key`, `recaptcha_wrong_message`, `recaptcha_theme`, `recaptcha_type`, `contact_data`, `contact_data_width`, `creative_popup`, `creative_popup_embed`) VALUES
                                (NULL, 0, ".$form_id.", 'Name', 'Please enter your name!', 3, '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 1, 0, '0000-00-00 00:00:00', 1, 0, 1, '', '1', '', '', 10, 10, '', '', '0', '1', '', '', '', '', '', '', '', '', '', '', '', '', 0, 0, 1, 0, 1, '', '', 0, 0, 0, '', '', '', '', '', '', '', '', '', 120, '', ''),
                                (NULL, 0, ".$form_id.", 'Email', 'Please enter your email!', 4, '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 1, 0, '0000-00-00 00:00:00', 1, 0, 2, '', '1', '', '', 10, 10, '', '', '', '1', '', '', '', '', '', '', '', '', '', '', '', '', 0, 0, 1, 0, 1, '', '', 0, 0, 0, '', '', '', '', '', '', '', '', '', 120, '', ''),
                                (NULL, 0, ".$form_id.", 'Country', '', 9, '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 1, 0, '0000-00-00 00:00:00', 1, 0, 3, '', '1', '', '', 10, 10, '', '', '0', '1', 'Select country', 'No results match', '', '', '', '', '', '', '', '', '', '', 1, 1, 1, 1, 1, '', '', 0, 0, 0, '', '', '', '', '', '', '', '', '', 120, '', ''),
                                (NULL, 0, ".$form_id.", 'How did you find us?', '', 12, '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 1, 0, '0000-00-00 00:00:00', 1, 0, 4, '', '1', '', '', 10, 10, '', '', '0', '1', '', '', '', '', '', '', '', '', '', '', '', '', 1, 1, 1, 1, 1, '', '', 0, 0, 0, '', '', '', '', '', '', '', '', '', 120, '', ''),
                                (NULL, 0, ".$form_id.", 'Message', 'Write your message!', 2, '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 1, 0, '0000-00-00 00:00:00', 1, 0, 5, '', '1', '', '', 10, 10, '', '', '0', '1', '', '', '', '', '', '', '', '', '', '', '', '', 0, 0, 1, 0, 1, '', '', 0, 0, 0, '', '', '', '', '', '', '', '', '', 120, '', '');

                            ";
            $db->setQuery($query_insert);
            $db->query();
            $field_id_first = $db->insertid();

            // insert options
            $field_id = $field_id_first + 2;
            $query_insert = 
                            "
                                INSERT IGNORE INTO `#__creative_form_options` (`id`, `id_parent`, `name`, `value`, `ordering`, `showrow`, `selected`) VALUES 

                            ";
            foreach($countries_array as $k => $country_val) {
                 $query_insert .= "(NULL, ".$field_id.", '".$country_val."', '".$country_val."', ".$k.", '1', '0')";
                 if($k != sizeof($countries_array) - 1)
                    $query_insert .= ',';
            }
            $db->setQuery($query_insert);
            $db->query();

            // insert options
            $field_id = $field_id_first + 3;
            $query_insert = 
                            "
                                INSERT IGNORE INTO `#__creative_form_options` (`id`, `id_parent`, `name`, `value`, `ordering`, `showrow`, `selected`) VALUES 
                                (NULL, ".$field_id.", 'Web search', 'Web search', 2, '1', '0'),
                                (NULL, ".$field_id.", 'Social networks', 'Social networks', 1, '1', '0'),
                                (NULL, ".$field_id.", 'Recommended by a friend', 'Recommended by a friend', 3, '1', '0');
                            ";
            $db->setQuery($query_insert);
            $db->query();
            // end insert form 1
        }

    }
}