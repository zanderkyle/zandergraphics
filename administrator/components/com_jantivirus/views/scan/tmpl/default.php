<?php
/**
 * @package	Antivirus
 * @copyright	Copyright (C) 2014 SiteGuarding.com. All rights reserved.
 * @license	GNU General Public License version 2 or later
 */
defined('_JEXEC') or die('Restricted Access');
// load tooltip behavior
JHtml::_('behavior.tooltip');

$ajax_url = str_replace("/administrator/", "", JURI::base()).'/index.php?option=com_jantivirus';
$session = JFactory::getSession();
$license_info = $session->get('jantivirus_license_info');

$session_report_key = md5(JURI::root().'-'.rand(1,100).'-'.time());
?>

        <script>
        	
            jQuery(document).ready(function(){
            	
            	var refreshIntervalId;
            	var link = "<?php echo $ajax_url; ?>";
	        	var show_report = 0;
	        	
	        	function ShowReport(data)
	        	{
	        		show_report = 0;
	        		
					jQuery("#progress_bar_process").css('width', '100%');
					jQuery("#progress_bar").hide();
					
					clearInterval(refreshIntervalId);
					
	                jQuery("#report_area").html(data);
	                jQuery("#back_bttn").show();
	                jQuery("#help_block").show();
	                jQuery("#rek_block").hide();
	        	}

				jQuery.post(link, {
					    action: "StartScan_AJAX",
					    scan_path: "<?php echo JPATH_SITE; ?>",
						session_id: "<?php echo $_SESSION['scan']['session_id']; ?>",
						access_key: "<?php echo $license_info['access_key']; ?>",
						session_report_key: "<?php echo $session_report_key; ?>",
						do_evristic: "1",
						domain: "<?php echo JURI::root(); ?>",
						email: "<?php echo $license_info['email']; ?>"
					},
					function(data){
						ShowReport(data);
					}
				)
				.error(function() { 
   					GetReport();
				});
				
				function GetReport()
				{
					clearInterval(refreshIntervalId);
					
					jQuery.post(link, {
						    action: "GetReport_AJAX",
							session_id: "<?php echo $_SESSION['scan']['session_id']; ?>",
							access_key: "<?php echo $license_info['access_key']; ?>",
							session_report_key: "<?php echo $session_report_key; ?>",
							domain: "<?php echo JURI::root(); ?>",
							email: "<?php echo $license_info['email']; ?>"
						},
						function(data){
							ShowReport(data);
						}
					);
				}
				
				
				function GetProgress()
				{
	               	var link = "<?php echo $ajax_url; ?>";
	
					jQuery.post(link, {
						    action: "GetScanProgress_AJAX",
							session_id: "<?php echo $_SESSION['scan']['session_id']; ?>"
						},
						function(data){
						    var tmp_data = data.split('|');
						    if (tmp_data[0] == 100) {
				    			jQuery("#help_block").show();
				    			clearInterval(refreshIntervalId);
				    			refreshIntervalId =  setInterval(GetReport, 5000);
						    }
						    jQuery("#progress_bar_txt").html(tmp_data[0]+'% - '+tmp_data[1]);
						    jQuery("#progress_bar_process").css('width', parseInt(tmp_data[0])+'%');
						}
					);	
				}
				
				refreshIntervalId =  setInterval(GetProgress, 3000);
				
            });
        </script>
        
        <div id="progress_bar"><div id="progress_bar_process"></div><div id="progress_bar_txt">Scanning process started...</div></div>
        
        
		<p class="msg_box msg_info avp_reviewreport_block">If the scanning process takes too long. Get the results using the link<br /><a href="https://www.siteguarding.com/antivirus/viewreport?report_id=<?php echo $session_report_key; ?>" target="_blank">https://www.siteguarding.com/antivirus/viewreport?report_id=<?php echo $session_report_key; ?></a></p>
        
        
		<div id="report_area"></div>
        
        <div id="help_block" style="display: none;">

		<?php /*
		<a target="_blank" href="<?php echo JRoute::_('index.php?option=com_jantivirus&view=reports'); ?>"><?php echo JText::_('COM_JANTIVIRUS_SEE_REPORT_ONLINE'); ?></a><br /><br />
		*/ ?>
		<a href="http://www.siteguarding.com" target="_blank">SiteGuarding.com</a> - Website Security. Professional security services against hacker activity.
		
		</div>
        
        <button id="back_bttn" style="display: none;" class="button" onclick="location.href='<?php echo JRoute::_('index.php?option=com_jantivirus'); ?>'"><?php echo JText::_('COM_JANTIVIRUS_BTTN_BACK'); ?></button>
        
        <div id="rek_block">
			<a href="https://www.siteguarding.com" target="_blank">
				<img class="effect7" src="<?php echo '../media/com_jantivirus/images/rek_scan.jpg'; ?>">
			</a>
		</div>
