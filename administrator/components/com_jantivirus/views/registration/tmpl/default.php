<?php
/**
 * @package	Antivirus
 * @copyright	Copyright (C) 2014 SiteGuarding.com. All rights reserved.
 * @license	GNU General Public License version 2 or later
 */
defined('_JEXEC') or die('Restricted Access');
// load tooltip behavior
JHtml::_('behavior.tooltip');
?>

		<script>
		function form_ConfirmRegistration(form)
		{
			if ( jQuery('#registered').is(':checked') ) return true;
			else {
				alert('Confirmation is not checked.');	
				return false;
			}
		}
		</script>
		<form method="post" action="index.php" onsubmit="return form_ConfirmRegistration(this);">
		
		<div class="ui_container">

                <h2 class="ui dividing header"><?php echo JText::_('COM_JANTIVIRUS_TITLE_REGISTRATION'); ?></h2>
			  
				<p>Click "Confirm Registration" button to complete registration process. Your website will be automatically registered on <a href="http://www.siteguarding.com">www.SiteGuarding.com</a>.<br></p>
				
				<p>Already registered? Go to <b>Options</b> and enter your Access Key.</p>
                


      

            <p>               
            <div class="ui form">
              <div class="inline required field">
                <label style="width: 70px; text-align:right;">Domain</label>
                <input type="text" name="domain" id="jform_domain" value="<?php echo JURI::root(); ?>" class="inputbox required" readonly="readonly" size="50">
                    <div class="ui pointing left label">
                      This website URL will be analyzed
                    </div>
              </div>
            </div>
            </p> 
            <p>
            <div class="ui form">
              <div class="inline required field">
                <label style="width: 70px; text-align:right;">Email</label>
            	<?php
            	$user =& JFactory::getUser();
            	?>
                <input type="text" name="email" id="jform_email" value="<?php echo $user->email; ?>" class="inputbox required" size="50">
                    <div class="ui pointing left label">
                      You will receive all reports and alerts to this email.
                    </div>
              </div>
            </div>
            </p> 
            <p>
                <input name="registered" type="checkbox" id="registered" value="1">
                <label for="registered" type="text">I confirm to register my website on <a href="http://www.siteguarding.com">www.SiteGuarding.com</a></label>
            </p>
			

			  
					<p>
					  <input type="submit" name="submit" id="submit" class="ui green button" value="Confirm Registration">
					</p>
					

		</div>
		
		
		<input type="hidden" name="option" value="com_jantivirus"/>
		<input type="hidden" name="task" value="registration_confirm"/>
		</form>
