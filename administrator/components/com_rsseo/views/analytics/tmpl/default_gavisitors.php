<?php
/**
* @version 1.0.0
* @package RSSEO! 1.0.0
* @copyright (C) 2009-2012 www.rsjoomla.com
* @license GPL, http://www.gnu.org/copyleft/gpl.html
*/

defined('_JEXEC') or die('Restricted access');?>
<table cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td colspan="3">
			<div id="rss_visualization" style="text-align: center; clear: both;"></div><br />
		</td>
	</tr>
	<tr>
		<td>
			<img src="<?php echo JURI::root(); ?>administrator/components/com_rsseo/assets/images/rssloader.gif" id="imggeneral" alt="" style="display:none;" />
			<span id="gageneral"></span>
		</td>
		<td>&nbsp;</td>
		<td valign="top">
			<img src="<?php echo JURI::root(); ?>administrator/components/com_rsseo/assets/images/rssloader.gif" id="imgnewreturning" alt="" style="display:none;" />
			<span id="ganewreturning"></span>
		</td>
	</tr>
	<tr>
		<td colspan="3">
			<img src="<?php echo JURI::root(); ?>administrator/components/com_rsseo/assets/images/rssloader.gif" id="imgvisits" alt="" style="display:none;" />
			<span id="gavisits"></span>
		</td>
	</tr>
	<tr>
		<td>
			<img src="<?php echo JURI::root(); ?>administrator/components/com_rsseo/assets/images/rssloader.gif" id="imgbrowsers" alt="" style="display:none;" />
			<span id="gabrowsers"></span>
		</td>
		<td>&nbsp;</td>
		<td valign="top">
			<img src="<?php echo JURI::root(); ?>administrator/components/com_rsseo/assets/images/rssloader.gif" id="imgmobiles" alt="" style="display:none;" />
			<span id="gamobiles"></span>
		</td>
	</tr>
</table>