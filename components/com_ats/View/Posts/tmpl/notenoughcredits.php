<?php
/**
 * @package ats
 * @copyright Copyright (c)2011-2014 Nicholas K. Dionysopoulos / AkeebaBackup.com
 * @license GNU GPL v3 or later
 */

// No direct access
use Akeeba\TicketSystem\Admin\Helper\Html;

defined('_JEXEC') or die;

?>
<p id="ats-post-notenoughcredits-default-message">
	<?php echo JText::_('COM_ATS_POST_MSG_NOTENOUGHCREDITS'); ?>
</p>
<?php echo Html::loadposition('ats-post-notenoughcredits'); ?>