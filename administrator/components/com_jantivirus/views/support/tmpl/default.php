<?php
/**
 * @package	Antivirus
 * @copyright	Copyright (C) 2014 SiteGuarding.com. All rights reserved.
 * @license	GNU General Public License version 2 or later
 */
defined('_JEXEC') or die('Restricted Access');
// load tooltip behavior
JHtml::_('behavior.tooltip');

$session = JFactory::getSession();
$license_info = $session->get('jantivirus_license_info');
?>

<div class="ui_container">

<h2 class="ui dividing header">Free Professional Support</h2>

<p class="avp_getpro"><a href="https://www.siteguarding.com/en/buy-service/antivirus-site-protection?domain=<?php echo urlencode( JURI::root() ); ?>&email=<?php echo urlencode($license_info['email']); ?>" target="_blank"><?php echo JText::_('COM_JANTIVIRUS_MSG_GET_PRO'); ?></a></p>

<p>
For more information and details about Antivirus Site Protection please <a target="_blank" href="https://www.siteguarding.com/en/antivirus-site-protection-for-joomla">click here</a>.<br /><br />
<a href="http://www.siteguarding.com/livechat/index.html" target="_blank">
	<img src="<?php echo '../media/com_jantivirus/images/livechat.png'; ?>"/>
</a><br />
For any questions and support please use LiveChat or this <a href="https://www.siteguarding.com/en/contacts" rel="nofollow" target="_blank" title="SiteGuarding.com - Website Security. Professional security services against hacker activity. Daily website file scanning and file changes monitoring. Malware detecting and removal.">contact form</a>.<br>
<br>
<a href="https://www.siteguarding.com/" target="_blank">SiteGuarding.com</a> - Website Security. Professional security services against hacker activity.<br />
</p>

<div style="width:100%">
	<fieldset class="adminform">
	  <legend><?php echo JText::_('COM_JANTIVIRUS_TITLE_CRON_SETTINGS'); ?></legend>
		
		<p>
		If you want to enable daily scan of your website. Add this line in your hosting panel in cron settings.<br /><br />
		<b>Unix time settings:</b> 0 0 * * *<br />
		<b>Command:</b> wget -O /dev/null "<?php echo str_replace("/administrator/", "/", JURI::base()); ?>index.php?option=com_jantivirus&action=cron&access_key=<?php echo $license_info['access_key']; ?>"
		</p>
	</fieldset>
</div>

</div>

