<?php
/**
 * @package ats
 * @copyright Copyright (c)2011-2014 Nicholas K. Dionysopoulos / AkeebaBackup.com
 * @license GNU GPL v3 or later
 */

/** @var $this \Akeeba\TicketSystem\Admin\View\Tickets\Html */
defined('_JEXEC') or die;

JHtml::_('formbehavior.chosen');
?>
    <h3><?php echo JText::_('COM_ATS_TICKETS_LEGEND_USERINFO'); ?></h3>
<?php
if (!$this->showuserinfo)
{
    echo $this->loadTemplate('userinfoselect');
}
else
{
    echo $this->loadTemplate('userinfodisplay');
}