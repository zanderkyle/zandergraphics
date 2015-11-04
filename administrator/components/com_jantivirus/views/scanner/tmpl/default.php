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
//print_r($license_info);
/*
if (intval($license_info['scans']) == 0) 
{
	JError::raiseWarning( 100, JText::_('COM_JANTIVIRUS_MSG_VERSION_HAS_LIMITS') );
}
*/

?>
<div class="ui_container">

<h2 class="ui dividing header">Antivirus Scanner</h2>


<div style="max-width:800px">


<div style="width:60%; float:left; margin-bottom:20px">
    <div class="ui list">
        <p class="item">Google Blacklist Status: <?php if ($license_info['blacklist']['google'] != 'ok') echo '<span class="ui red label">Blacklisted ['.$license_info['blacklist']['google'].']</span> [<a href="https://www.siteguarding.com/en/services/malware-removal-service" target="_blank">Remove From Blacklist</a>]'; else echo '<span class="ui green label">Not blacklisted</span>'; ?></p>
        <p class="item">File Change Monitoring: <?php if ($license_info['filemonitoring']['status'] == 0) echo '<span class="ui red label">Disabled</span> [<a href="https://www.siteguarding.com/en/protect-your-website" target="_blank">Subscribe</a>]'; else echo '<b>'.$license_info['filemonitoring']['plan'].'</b> ['.$license_info['filemonitoring']['exp_date'].']'; ?></p>
        <?php
        if (count($license_info['reports']) > 0) 
        {
            if ($license_info['last_scan_files_counters']['main'] == 0 && $license_info['last_scan_files_counters']['heuristic'] == 0) echo '<p class="item">Website Status: <span class="ui green label">Clean</span></p>';
            if ($license_info['last_scan_files_counters']['main'] > 0) echo '<p class="item">Website Status: <span class="ui red label">Infected</span> [<a href="https://www.siteguarding.com/en/services/malware-removal-service" target="_blank">Clean My Website</a>]</p>';
            else if ($license_info['last_scan_files_counters']['heuristic'] > 0)  echo '<p class="item">Website Status: <span class="ui red label">Review is required</span> [<a href="https://www.siteguarding.com/en/services/malware-removal-service" target="_blank">Review My Website</a>]</p>';
        }
        else {
            echo '<p class="item">Website Status: <span class="ui red label">Never Analyzed</span></p>';
        }
        ?>
    </div>
</div>


<div style="width:40%; float:left; margin-bottom:20px">
    <div class="ui list">
    	<?php
    	$txt = $license_info['membership'];
    	if ($txt != 'pro') $txt = ucwords($txt);
    	else $txt = '<span class="ui green label">'.ucwords($txt).'<span>';
    	?>
        <p class="item">Your subscription: <b><?php echo $txt; ?></b> (ver. <?php echo SGAntiVirus::$antivirus_version; ?>)</p>
        <p class="item"><?php echo JText::_('COM_JANTIVIRUS_MSG_FREE_SCANS'); ?>: <?php echo $license_info['scans']; ?></p>
        <p class="item"><?php echo JText::_('COM_JANTIVIRUS_MSG_VALID_TILL'); ?>: <?php echo $license_info['exp_date']."&nbsp;&nbsp;"; 
        if ($license_info['exp_date'] < date("Y-m-d")) echo '<span class="ui red label">'.JText::_('COM_JANTIVIRUS_MSG_EXPIRED').'</span> [<a href="https://www.siteguarding.com/en/buy-service/antivirus-site-protection?domain='.urlencode( JURI::root() ).'&email='.urlencode($license_info['email']).'" target="_blank">Upgrade</a>]';
        else if ($license_info['exp_date'] < date("Y-m-d", mktime(0, 0, 0, date("m")  , date("d")-7, date("Y")))) echo '<span class="msg_box msg_warning">'.JText::_('COM_JANTIVIRUS_MSG_WILL_EXPIRE_SOON').'</span>';
        ?></p>

    </div>
</div>

</div>


<div style="clear:both"></div>

<?php /*
<div class="ui list">
	<?php
	$txt = $license_info['membership'];
	if ($txt != 'pro') $txt = ucwords($txt);
	else $txt = '<span class="ui green label">'.ucwords($txt).'<span>';
	?>
    <p class="item">Your subscription: <b><?php echo $txt; ?></b> (ver. <?php echo SGAntiVirus::$antivirus_version; ?>)</p>
    <p class="item"><?php echo JText::_('COM_JANTIVIRUS_MSG_FREE_SCANS'); ?>: <?php echo $license_info['scans']; ?></p>
    <p class="item"><?php echo JText::_('COM_JANTIVIRUS_MSG_VALID_TILL'); ?>: <?php echo $license_info['exp_date']."&nbsp;&nbsp;"; 
    if ($license_info['exp_date'] < date("Y-m-d")) echo '<span class="ui red label">'.JText::_('COM_JANTIVIRUS_MSG_EXPIRED').'</span> [<a href="https://www.siteguarding.com/en/buy-service/antivirus-site-protection?domain='.urlencode( JURI::root() ).'&email='.urlencode($license_info['email']).'" target="_blank">Upgrade</a>]';
    else if ($license_info['exp_date'] < date("Y-m-d", mktime(0, 0, 0, date("m")  , date("d")-7, date("Y")))) echo '<span class="msg_box msg_warning">'.JText::_('COM_JANTIVIRUS_MSG_WILL_EXPIRE_SOON').'</span>';
    ?><br />
    <p class="item">Google Blacklist Status: <?php if ($license_info['blacklist']['google'] != 'ok') echo '<span class="ui red label">Blacklisted ['.$license_info['blacklist']['google'].']</span> [<a href="https://www.siteguarding.com/en/services/malware-removal-service" target="_blank">Remove From Blacklist</a>]'; else echo '<span class="ui green label">Not blacklisted</span>'; ?></p>
    <p class="item">File Change Monitoring: <?php if ($license_info['filemonitoring']['status'] == 0) echo '<span class="ui red label">Disabled</span> [<a href="https://www.siteguarding.com/en/protect-your-website" target="_blank">Subscribe</a>]'; else echo '<b>'.$license_info['filemonitoring']['plan'].'</b> ['.$license_info['filemonitoring']['exp_date'].']'; ?></p>
    <?php
    if (count($license_info['reports']) > 0) 
    {
        if ($license_info['last_scan_files_counters']['main'] == 0 && $license_info['last_scan_files_counters']['heuristic'] == 0) echo '<p class="item">Website Status: <span class="ui green label">Clean</span></p>';
        if ($license_info['last_scan_files_counters']['main'] > 0) echo '<p class="item">Website Status: <span class="ui red label">Infected</span> [<a href="https://www.siteguarding.com/en/services/malware-removal-service" target="_blank">Clean My Website</a>]</p>';
        else if ($license_info['last_scan_files_counters']['heuristic'] > 0)  echo '<p class="item">Website Status: <span class="ui red label">Review is required</span> [<a href="https://www.siteguarding.com/en/services/malware-removal-service" target="_blank">Review My Website</a>]</p>';
    }
    else {
        echo '<p class="item">Website Status: <span class="ui red label">Never Analyzed</span></p>';
    }
    ?>
</div>
*/ ?>

<?php
/*
if (trim($license_info['membership']) == 'pro') $license_text = JText::_('COM_JANTIVIRUS_MSG_YOU_HAVE_PRO');
else $license_text = JText::_('COM_JANTIVIRUS_MSG_GET_PRO');
*/
if (trim($license_info['membership']) != 'pro') 
{
    $license_text = JText::_('COM_JANTIVIRUS_MSG_GET_PRO');
?>
<div class="ui message warning" style="max-width:760px">
    <p><a href="https://www.siteguarding.com/en/buy-service/antivirus-site-protection?domain=<?php echo urlencode( JURI::root() ); ?>&email=<?php echo urlencode($license_info['email']); ?>" target="_blank"><?php echo $license_text; ?></a></p>
</div>
<?php
}
?>


<div class="mod-box"><div>		
<p>To start the scan process click "Start Scanner" button.</p>
<p>Scanner will automatically collect and analyze the files of your website. The scanning process can take up to 10 mins (it depends of speed of your server and amount of the files to analyze).</p>
<p>After full analyze you will get the report. The copy of the report we will send by email for your records.</p>

			
		<form method="post" action="index.php">
		
        
		<div class="startscanner">
            <p style="text-align: center;">
		      <input type="submit" name="submit" id="submit" class="huge ui green button" value="<?php echo JText::_('COM_JANTIVIRUS_BTTN_START_SCANNER'); ?>">
          </p>
		</div>
		
		<input type="hidden" name="option" value="com_jantivirus"/>
		<input type="hidden" name="view" value="scan"/>
		
		</form>
		
<p><?php echo JText::_('COM_JANTIVIRUS_ONLINE_TOOL'); ?> <a target="_blank" href="https://www.siteguarding.com/en/website-antivirus">Click here</a></p>


<h3 class="ui dividing header">Extra Options</h3>

	<div class="divTable avpextraoption">
	
	<div class="divRow">
	<div class="divCell avpextraoption_txt">Your website got hacked and blacklisted by Google? This is really bad, you are going to lose your visitors. We will help you to clean your website and remove from all blacklists.</div>
	<div class="divCell">
		<form method="post" action="https://www.siteguarding.com/en/services/malware-removal-service">
		<input type="submit" name="submit" id="submit" class="ui button" value="Clean My Website">
		</form>
	</div>
	</div>
    
    
	<div class="divRow"><div class="divCell">&nbsp;</div><div class="divCell"></div><div class="divCell"></div><div class="divCell"></div></div>
	
	<div class="divRow">
	<div class="divCell avpextraoption_txt">Select Security Package for Your Website. Server-side scanning & file change monitoring. Daily analyze of all the changes on your website. Malware removal from already hacked website and much more.</div>
	<div class="divCell">
		<form method="post" action="https://www.siteguarding.com/en/protect-your-website">
			<input type="submit" name="submit" id="submit" class="ui button" value="Protect My Website">
		</form>
	</div>
	</div>
	
    
	<div class="divRow"><div class="divCell">&nbsp;</div><div class="divCell"></div><div class="divCell"></div><div class="divCell"></div></div>
	
	<div class="divRow">
	<div class="divCell avpextraoption_txt">Found suspicious files on your website? Send us request for free analyze. Our security experts will review your files and explain what to do.</div>
	<div class="divCell">
		<form method="post" action="index.php">
		<?php
		if ($license_info['membership'] == 'pro') 
		{
			?>
			<input type="submit" name="submit" id="submit" class="ui button" value="Send Files For Analyze">
			<?php
		} else {
			?>
			<input type="button" class="ui button" value="Send Files For Analyze" onclick="javascript:alert('Available in PRO version only. Please Upgrade to PRO version.');">
			<?php
		}
		?>	
		
			<input type="hidden" name="action" value="SendFilesForAnalyze"/>
			<input type="hidden" name="option" value="com_jantivirus"/>
			<input type="hidden" name="view" value="scanner"/>
		</form>
	</div>
	</div>
	
	<div class="divRow"><div class="divCell">&nbsp;</div><div class="divCell"></div><div class="divCell"></div><div class="divCell"></div></div>
	
	<div class="divRow">
	<div class="divCell avpextraoption_txt">Remove viruses from your website with one click.<br><span class="label_error">Please note: Hackers can inject malware codes inside of the normal files. We advice to send request to SiteGuarding.com for file review and analyze.</span></div>
	<div class="divCell">
		<form method="post" action="index.php">
		<?php
		if ($license_info['membership'] == 'pro') 
		{
			?>
			<input type="submit" name="submit" id="submit" class="ui button" value="Quarantine & Remove malware">
			<?php
		} else {
			?>
			<input type="button" class="ui button" value="Quarantine & Remove malware" onclick="javascript:alert('Available in PRO version only. Please Upgrade to PRO version.');">
			<?php
		}
		?>	
		
				<input type="hidden" name="action" value="QuarantineFiles"/>
				<input type="hidden" name="option" value="com_jantivirus"/>
				<input type="hidden" name="view" value="scanner"/>
		</form>
	</div>
	</div>
	

	</div>
	
	


			<?php
			if ($license_info['membership'] == 'free') 
			{
				?>
				<br />
				<span class="msg_box msg_error"><?php echo JText::_('COM_JANTIVIRUS_QUARANTINE_SERVICE'); ?> <a href="https://www.siteguarding.com/en/buy-service/antivirus-site-protection?domain=<?php echo urlencode( JURI::root() ); ?>&email=<?php echo urlencode($license_info['email']); ?>" target="_blank"><?php echo JText::_('COM_JANTIVIRUS_PLEASE_UPGRADE'); ?></a></span>
				<br />
				<br />
				<?php	
			}
			?>
			
<?php
if ( $license_info['last_scan_files_counters']['main'] > 0 || $license_info['last_scan_files_counters']['heuristic'] > 0 )
{
	?>
    <h3 class="ui dividing header">Latest Scan Result</h3>
	<?php
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
	
					
					<table>
						<tr>
						<td>
					
						<form method="post" action="index.php">
						<?php
						if ($license_info['membership'] == 'pro') 
						{
							?>
							<input type="submit" name="submit" id="submit" class="mini ui button" value="<?php echo JText::_('COM_JANTIVIRUS_QUARANTINE'); ?>">
							<?php
						} else {
							?>
							<input type="button" class="mini ui button" value="" onclick="javascript:alert('<?php echo JText::_('COM_JANTIVIRUS_AVAILABLE_IN_PRO_VERSION_ONLY'); ?> <?php echo JText::_('COM_JANTIVIRUS_PLEASE_UPGRADE'); ?>');">
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
							<input type="submit" name="submit" id="submit" class="mini ui button" value="<?php echo JText::_('COM_JANTIVIRUS_SEND_FILES'); ?>">
							<?php
						} else {
							?>
							<input type="button" class="mini ui button" value="<?php echo JText::_('COM_JANTIVIRUS_SEND_FILES'); ?>" onclick="javascript:alert('<?php echo JText::_('COM_JANTIVIRUS_AVAILABLE_IN_PRO_VERSION_ONLY'); ?> <?php echo JText::_('COM_JANTIVIRUS_PLEASE_UPGRADE'); ?>');">
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
                    <p class="label_error">
					* Please note: Hackers can inject malware codes inside of the normal files. If you delete these files, website can stop to work or will be not stable. We advice to send request to SiteGuarding.com for file review and analyze.
                    </p>
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
							<input type="submit" name="submit" id="submit" class="mini ui button" value="<?php echo JText::_('COM_JANTIVIRUS_QUARANTINE'); ?>">
							<?php
						} else {
							?>
							<input type="button" class="mini ui button" value="" onclick="javascript:alert('<?php echo JText::_('COM_JANTIVIRUS_AVAILABLE_IN_PRO_VERSION_ONLY'); ?> <?php echo JText::_('COM_JANTIVIRUS_PLEASE_UPGRADE'); ?>');">
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
							<input type="submit" name="submit" id="submit" class="mini ui button" value="<?php echo JText::_('COM_JANTIVIRUS_SEND_FILES'); ?>">
							<?php
						} else {
							?>
							<input type="button" class="mini ui button" value="<?php echo JText::_('COM_JANTIVIRUS_SEND_FILES'); ?>" onclick="javascript:alert('<?php echo JText::_('COM_JANTIVIRUS_AVAILABLE_IN_PRO_VERSION_ONLY'); ?> <?php echo JText::_('COM_JANTIVIRUS_PLEASE_UPGRADE'); ?>');">
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
                    <p class="label_error">
					* Please note: Hackers can inject malware codes inside of the normal files. If you delete these files, website can stop to work or will be not stable. We advice to send request to SiteGuarding.com for file review and analyze.
					</p>
					</div>
					<?php
				}
			}
			
?>

<img class="imgpos" alt="Antivirus Website Protection" src="<?php echo '../media/com_jantivirus/images/mid_box.png'; ?>" width="110" height="70">
			
</div></div>

<p>
<a href="http://www.siteguarding.com/livechat/index.html" target="_blank">
	<img src="<?php echo '../media/com_jantivirus/images/livechat.png'; ?>"/>
</a><br />
For any questions and support please use LiveChat or this <a href="https://www.siteguarding.com/en/contacts" rel="nofollow" target="_blank" title="SiteGuarding.com - Website Security. Professional security services against hacker activity. Daily website file scanning and file changes monitoring. Malware detecting and removal.">contact form</a>.<br>
</p>

<p>
Powered by <a href="https://www.siteguarding.com" target="_blank">SiteGuarding.com</a>
</p>

</div>

