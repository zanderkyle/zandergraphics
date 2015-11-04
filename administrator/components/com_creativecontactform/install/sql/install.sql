--
-- Table structure for table `#__creative_forms`
--
CREATE TABLE IF NOT EXISTS `#__creative_forms` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `email_to` text NOT NULL,
  `email_bcc` text NOT NULL,
  `email_subject` text NOT NULL,
  `email_from` text NOT NULL,
  `email_from_name` text NOT NULL,
  `email_replyto` text NOT NULL,
  `email_replyto_name` text NOT NULL,
  `shake_count` mediumint(8) unsigned NOT NULL,
  `shake_distanse` mediumint(8) unsigned NOT NULL,
  `shake_duration` mediumint(8) unsigned NOT NULL,
  `id_template` mediumint(8) unsigned NOT NULL,
  `name` text NOT NULL,
  `top_text` text NOT NULL,
  `pre_text` text NOT NULL,
  `thank_you_text` text NOT NULL,
  `send_text` text NOT NULL,
  `send_new_text` text NOT NULL,
  `close_alert_text` text NOT NULL,
  `form_width` text NOT NULL,
  `alias` text NOT NULL,
  `created` datetime NOT NULL,
  `publish_up` datetime NOT NULL,
  `publish_down` datetime NOT NULL,
  `published` tinyint(1) NOT NULL,
  `checked_out` int(10) unsigned NOT NULL,
  `checked_out_time` datetime NOT NULL,
  `access` int(10) unsigned NOT NULL,
  `featured` tinyint(3) unsigned NOT NULL,
  `ordering` int(11) NOT NULL,
  `language` char(7) NOT NULL,
  `redirect` enum('0','1') NOT NULL DEFAULT '0',
  `redirect_itemid` int(10) unsigned NOT NULL,
  `redirect_url` text NOT NULL,
  `redirect_delay` int(11) NOT NULL,
  `send_copy_enable` enum('0','1') NOT NULL,
  `send_copy_text` text NOT NULL,
  `show_back` enum('0','1') NOT NULL DEFAULT '1',
  `email_info_show_referrer` tinyint not null DEFAULT  '1',
  `email_info_show_ip` tinyint not null DEFAULT  '1',
  `email_info_show_browser` tinyint not null DEFAULT  '1',
  `email_info_show_os` tinyint not null DEFAULT  '1',
  `email_info_show_sc_res` tinyint not null DEFAULT  '1',
  `custom_css` text not null,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM CHARACTER SET = `utf8`;

--
-- Table structure for table `#__creative_fields`
--

CREATE TABLE IF NOT EXISTS `#__creative_fields` (
 `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_user` int(10) unsigned NOT NULL,
  `id_form` mediumint(8) unsigned NOT NULL,
  `name` text NOT NULL,
  `tooltip_text` text NOT NULL,
  `id_type` mediumint(8) unsigned NOT NULL,
  `alias` text NOT NULL,
  `created` datetime NOT NULL,
  `publish_up` datetime NOT NULL,
  `publish_down` datetime NOT NULL,
  `published` tinyint(1) NOT NULL,
  `checked_out` int(10) unsigned NOT NULL,
  `checked_out_time` datetime NOT NULL,
  `access` int(10) unsigned NOT NULL,
  `featured` tinyint(3) unsigned NOT NULL,
  `ordering` int(11) NOT NULL,
  `language` char(7) NOT NULL,
  `required` enum('0','1') NOT NULL DEFAULT '0',
  `width` text NOT NULL,
  `field_margin_top` text NOT NULL,
  `select_show_scroll_after` int(11) NOT NULL DEFAULT '10',
  `select_show_search_after` int(11) NOT NULL DEFAULT '10',
  `message_required` text NOT NULL,
  `message_invalid` text NOT NULL,
  `ordering_field` enum('0','1') NOT NULL DEFAULT '0',
  `show_parent_label` enum('0','1') NOT NULL DEFAULT '1',
  `select_default_text` text NOT NULL,
  `select_no_match_text` text NOT NULL,
  `upload_button_text` text NOT NULL,
  `upload_minfilesize` text NOT NULL,
  `upload_maxfilesize` text NOT NULL,
  `upload_acceptfiletypes` text NOT NULL,
  `upload_minfilesize_message` text NOT NULL,
  `upload_maxfilesize_message` text NOT NULL,
  `upload_acceptfiletypes_message` text NOT NULL,
  `captcha_wrong_message` text NOT NULL,
  `datepicker_date_format` text NOT NULL,
  `datepicker_animation` text NOT NULL,
  `datepicker_style` smallint(5) unsigned NOT NULL DEFAULT '1',
  `datepicker_icon_style` smallint(6) NOT NULL DEFAULT '1',
  `datepicker_show_icon` smallint(5) unsigned NOT NULL DEFAULT '1',
  `datepicker_input_readonly` smallint(5) unsigned NOT NULL DEFAULT '1',
  `datepicker_number_months` smallint(5) unsigned NOT NULL DEFAULT '1',
  `datepicker_mindate` text NOT NULL,
  `datepicker_maxdate` text NOT NULL,
  `datepicker_changemonths` smallint(5) unsigned NOT NULL DEFAULT '0',
  `datepicker_changeyears` smallint(5) unsigned NOT NULL DEFAULT '0',
  `column_type` tinyint(4) NOT NULL,
  `custom_html` text NOT NULL,
  `google_maps` text NOT NULL,
  `heading` text NOT NULL,
  `recaptcha_site_key` text NOT NULL,
  `recaptcha_security_key` text NOT NULL,
  `recaptcha_wrong_message` text NOT NULL,
  `recaptcha_theme` text NOT NULL,
  `recaptcha_type` text NOT NULL,
  `contact_data` text NOT NULL,
  `contact_data_width` smallint(6) NOT NULL DEFAULT '120',
  `creative_popup` text NOT NULL,
  `creative_popup_embed` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_user` (`id_user`),
  KEY `id_form` (`id_form`)
) ENGINE=MyISAM CHARACTER SET = `utf8`;

--
-- Table structure for table `#__creative_field_types`
--

CREATE TABLE IF NOT EXISTS `#__creative_field_types` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM CHARACTER SET = `utf8`;

--
-- Table structure for table `#__creative_form_options`
--

CREATE TABLE IF NOT EXISTS `#__creative_form_options` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_parent` int(11) unsigned NOT NULL,
  `name` text NOT NULL,
  `value` text NOT NULL,
  `ordering` int(11) NOT NULL,
  `showrow` enum('0','1') NOT NULL DEFAULT '1',
  `selected` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM CHARACTER SET = `utf8`;

--
-- Table structure for table `#__contact_templates`
--

CREATE TABLE IF NOT EXISTS `#__contact_templates` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `created` datetime NOT NULL,
  `date_start` date NOT NULL,
  `date_end` date NOT NULL,
  `publish_up` datetime NOT NULL,
  `publish_down` datetime NOT NULL,
  `published` tinyint(1) NOT NULL,
  `checked_out` int(10) unsigned NOT NULL,
  `checked_out_time` datetime NOT NULL,
  `access` int(10) unsigned NOT NULL,
  `featured` tinyint(3) unsigned NOT NULL,
  `ordering` int(11) NOT NULL,
  `language` char(7) NOT NULL,
  `styles` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM CHARACTER SET = `utf8`;


--
-- Dumping data for table  `#__creative_field_types`
--

INSERT IGNORE INTO `#__creative_field_types` (`id`, `name`) VALUES
(1, 'Text Input'),
(2, 'Text Area'),
(3, 'Name'),
(4, 'E-mail'),
(5, 'Address'),
(6, 'Phone'),
(7, 'Number'),
(8, 'Url'),
(9, 'Select'),
(10, 'Multiple Select'),
(11, 'Checkbox'),
(12, 'Radio'),
(13, 'Captcha : PRO feature'),
(14, 'File upload : PRO feature'),
(16, 'Custom Html : PRO feature'),
(15, 'Datepicker : PRO feature'),
(17, 'Heading : PRO feature'),
(18, 'Google Maps : PRO feature'),
(19, 'Google reCAPTCHA : PRO feature'),
(20, 'Contact Data : PRO feature'),
(21, 'Creative Popup : PRO feature');

