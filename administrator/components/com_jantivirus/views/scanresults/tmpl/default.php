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

<h3><?php echo JText::_('COM_JANTIVIRUS_LATEST_SCAN_RESULT'); ?></h3>


			<?php
			if ($license_info['membership'] == 'free') 
			{
				?>
				<span class="msg_box msg_error"><?php echo JText::_('COM_JANTIVIRUS_QUARANTINE_SERVICE'); ?> <a href="https://www.siteguarding.com/en/buy-service/antivirus-site-protection?domain=<?php echo urlencode( JURI::root() ); ?>&email=<?php echo urlencode($license_info['email']); ?>" target="_blank"><?php echo JText::_('COM_JANTIVIRUS_PLEASE_UPGRADE'); ?></a></span>
				<?php	
			}
			?>
			
			
<?php
if ( $license_info['last_scan_files_counters']['main'] == 0 && $license_info['last_scan_files_counters']['heuristic'] == 0 )
{
	echo JText::_('COM_JANTIVIRUS_NO_FILES_FOR_ACTION');
}

			if (count($license_info['last_scan_files']['main']))
			{
				// Check files
				foreach ($license_info['last_scan_files']['main'] as $k => $tmp_file)
				{
					if (!file_exists(JPATH_SITE.'/'.$tmp_file)) unset($license_info['last_scan_files']['main'][$k]);
				}
				
				if (count($license_info['last_scan_files']['main']) > 0)
				{
					?>
					<div class="avp_latestfiles_block">
					<h4 class="label_error"><?php echo JText::_('COM_JANTIVIRUS_ACTION_REQUIRED'); ?></h4>
					
					<?php
					foreach ($license_info['last_scan_files']['main'] as $tmp_file)
					{
						echo '<p>'.$tmp_file.'</p>';
					}
					?>
					<br />	
					
					<table>
						<tr>
						<td>
					
						<form method="post" action="index.php">
						<?php
						if ($license_info['membership'] == 'pro') 
						{
							?>
							<input type="submit" name="submit" id="submit" class="button" value="<?php echo JText::_('COM_JANTIVIRUS_QUARANTINE'); ?>">
							<?php
						} else {
							?>
							<input type="button" class="button" value="" onclick="javascript:alert('<?php echo JText::_('COM_JANTIVIRUS_AVAILABLE_IN_PRO_VERSION_ONLY'); ?> <?php echo JText::_('COM_JANTIVIRUS_PLEASE_UPGRADE'); ?>');">
							&nbsp;[<?php echo JText::_('COM_JANTIVIRUS_AVAILABLE_IN_PRO_VERSION_ONLY'); ?> <a href="https://www.siteguarding.com/en/buy-service/antivirus-site-protection?domain=<?php echo urlencode( JURI::root() ); ?>&email=<?php echo urlencode($license_info['email']); ?>" target="_blank"><?php echo JText::_('COM_JANTIVIRUS_PLEASE_UPGRADE'); ?></a>]
							<?php
						}
						?>
						<input type="hidden" name="action" value="QuarantineFiles"/>
						<input type="hidden" name="file_type" value="main"/>
						<input type="hidden" name="option" value="com_jantivirus"/>
						<input type="hidden" name="view" value="scanner"/>
						</form>
						
						</td>
						<td>
						
						<form method="post" action="index.php">
						<?php
						if ($license_info['membership'] == 'pro') 
						{
							?>
							<input type="submit" name="submit" id="submit" class="button" value="<?php echo JText::_('COM_JANTIVIRUS_SEND_FILES'); ?>">
							<?php
						} else {
							?>
							<input type="button" class="button" value="<?php echo JText::_('COM_JANTIVIRUS_SEND_FILES'); ?>" onclick="javascript:alert('<?php echo JText::_('COM_JANTIVIRUS_AVAILABLE_IN_PRO_VERSION_ONLY'); ?> <?php echo JText::_('COM_JANTIVIRUS_PLEASE_UPGRADE'); ?>');">
							<?php
						}
						?>
						<input type="hidden" name="action" value="SendFilesForAnalyze"/>
						<input type="hidden" name="option" value="com_jantivirus"/>
						<input type="hidden" name="view" value="scanner"/>
						</form>
						
						</td>
						</tr>
					</table>
					* Please note: Hackers can inject malware codes inside of the normal files. If you delete these files, website can stop to work or will be not stable. We advice to send request to SiteGuarding.com for file review and analyze.
					
					</div>
					<?php
				}

			}
			
			
			if (count($license_info['last_scan_files']['heuristic']))
			{
				// Check files
				foreach ($license_info['last_scan_files']['heuristic'] as $k => $tmp_file)
				{
					if (!file_exists(JPATH_SITE.'/'.$tmp_file)) unset($license_info['last_scan_files']['heuristic'][$k]);
				}
				
				if (count($license_info['last_scan_files']['heuristic']) > 0)
				{
					?>
					<div class="avp_latestfiles_block">
					<h4 class="label_error"><?php echo JText::_('COM_JANTIVIRUS_REVIEW_REQUIRED'); ?></h4>
					<?php
					foreach ($license_info['last_scan_files']['heuristic'] as $tmp_file)
					{
						echo '<p>'.$tmp_file.'</p>';
					}
					?>
					<br />
					
					<?php
					
					if ($license_info['whitelist_filters_enabled'] == 1)
					{
						?>
						<span class="msg_box msg_warning"><?php echo JText::_('COM_JANTIVIRUS_WHITE_LIST_ENABLED'); ?></span><br /><br />
						<?php
					}

					?>
					<table>
						<tr>
						<td>
					
						<form method="post" action="index.php">
						<?php
						if ($license_info['membership'] == 'pro') 
						{
							?>
							<input type="submit" name="submit" id="submit" class="button" value="<?php echo JText::_('COM_JANTIVIRUS_QUARANTINE'); ?>">
							<?php
						} else {
							?>
							<input type="button" class="button" value="" onclick="javascript:alert('<?php echo JText::_('COM_JANTIVIRUS_AVAILABLE_IN_PRO_VERSION_ONLY'); ?> <?php echo JText::_('COM_JANTIVIRUS_PLEASE_UPGRADE'); ?>');">
							&nbsp;[<?php echo JText::_('COM_JANTIVIRUS_AVAILABLE_IN_PRO_VERSION_ONLY'); ?> <a href="https://www.siteguarding.com/en/buy-service/antivirus-site-protection?domain=<?php echo urlencode( JURI::root() ); ?>&email=<?php echo urlencode($license_info['email']); ?>" target="_blank"><?php echo JText::_('COM_JANTIVIRUS_PLEASE_UPGRADE'); ?></a>]
							<?php
						}
						?>
						<input type="hidden" name="action" value="QuarantineFiles"/>
						<input type="hidden" name="file_type" value="heuristic"/>
						<input type="hidden" name="option" value="com_jantivirus"/>
						<input type="hidden" name="view" value="scanner"/>
						</form>
						
						</td>
						<td>
						
						<form method="post" action="index.php">
						<?php
						if ($license_info['membership'] == 'pro') 
						{
							?>
							<input type="submit" name="submit" id="submit" class="button" value="<?php echo JText::_('COM_JANTIVIRUS_SEND_FILES'); ?>">
							<?php
						} else {
							?>
							<input type="button" class="button" value="<?php echo JText::_('COM_JANTIVIRUS_SEND_FILES'); ?>" onclick="javascript:alert('<?php echo JText::_('COM_JANTIVIRUS_AVAILABLE_IN_PRO_VERSION_ONLY'); ?> <?php echo JText::_('COM_JANTIVIRUS_PLEASE_UPGRADE'); ?>');">
							<?php
						}
						?>
						<input type="hidden" name="action" value="SendFilesForAnalyze"/>
						<input type="hidden" name="option" value="com_jantivirus"/>
						<input type="hidden" name="view" value="scanner"/>
						</form>
						
						</td>
						</tr>
					</table>
					* Please note: Hackers can inject malware codes inside of the normal files. If you delete these files, website can stop to work or will be not stable. We advice to send request to SiteGuarding.com for file review and analyze.
					
					
					</div>
					<?php
				}
			}




