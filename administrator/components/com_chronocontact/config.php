<?php
namespace GCore;
defined('_JEXEC') or die('Restricted access');
defined("GCORE_SITE") or die;
class Config {
	public static $site_title = "";
	public static $prepend_site_title = 1;
	public static $list_limit = 30;
	public static $max_list_limit = 1000;
	public static $secret_token = "";
	public static $jquery_theme = "base";
	public static $meta_keywords = "";
	public static $meta_description = "";
	public static $meta_robots = "";
	public static $debug = 1;
	public static $error_reporting = 1;
	public static $session_handler = "php";
	public static $session_lifetime = 35.5;
	public static $cookie_domain = "";
	public static $cookie_path = "";
	public static $sef_urls = 1;
	public static $sef_rewrite = 1;
	public static $cache = 1;
	public static $cache_engine = "file";
	public static $app_cache_expiry = 900;
	public static $cache_dbinfo = 1;
	public static $dbinfo_cache_expiry = 43200;
	public static $cache_query = 0;
	public static $query_cache_expiry = 3600;
	public static $site_language = "en-gb";
	public static $detect_language = 0;
	public static $mail_from_name = "Localhost admin";
	public static $mail_from_email = "from@localhost.com";
	public static $mail_reply_name = "Reply Name";
	public static $mail_reply_email = "reply@localhost.com";
	public static $smtp = 0;
	public static $smtp_host = "";
	public static $smtp_port = "";
	public static $smtp_username = "";
	public static $smtp_password = "";
	public static $db_host = "";
	public static $db_type = "";
	public static $db_name = "";
	public static $db_user = "";
	public static $db_pass = "";
	public static $db_prefix = "";
	public static $cache_permissions = 1;
}