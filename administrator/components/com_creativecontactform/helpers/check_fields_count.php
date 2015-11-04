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
define('_JEXEC',true);
defined('_JEXEC') or die('Restircted access');

error_reporting(0);
include '../../../../configuration.php';

$config = new JConfig;

//conects to datababse
mysql_connect($config->host, $config->user, $config->password);
mysql_select_db($config->db);
mysql_query("SET NAMES utf8");

$id = (int)$_POST['id'];
$res = mysql_query("SELECT COUNT(id) as count_fields FROM `".$config->dbprefix."creative_fields` WHERE id_form = '$id' GROUP BY id_form");
$row = mysql_fetch_assoc($res);
echo $count = $row["count_fields"];
?>