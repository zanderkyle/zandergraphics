<?php 
defined('_JEXEC') or die('Restricted access');
echo '<div id="com_phocacommander-multipleupload" class="ph-in">';
echo $this->t['mu_response_msg'] ;
echo '<form action="'. JURI::base().'index.php?option=com_phocacommander" >';
if ($this->t['ftp']) {echo PhocaDownloadFileUpload::renderFTPaccess();}
echo '<small>'.JText::_('COM_PHOCACOMMANDER_SELECT_FILES').'. '.JText::_('COM_PHOCACOMMANDER_ADD_FILES_TO_UPLOAD_QUEUE_AND_CLICK_START_BUTTON').'</small>';
echo $this->t['mu_output'];
echo '</form>';
echo '</div>';
?>