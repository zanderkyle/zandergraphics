<?php
/**
 * @package   AkeebaTicketSystem
 * @copyright Copyright (c)2011-2015 Nicholas K. Dionysopoulos
 * @license   GNU General Public License version 3, or later
 */
namespace Akeeba\TicketSystem\Admin\View\Tickets\Html;

use Akeeba\TicketSystem\Admin\Helper\Format;
use Akeeba\TicketSystem\Admin\Helper\Select;
use JText;

defined('_JEXEC') or die;
?>
<h3><?php echo JText::_('COM_ATS_TICKETS_LEGEND_TICKETPARAMS'); ?></h3>

<h4><?php echo JText::sprintf('COM_ATS_TICKETS_HEADING_ID', $this->item->ats_ticket_id) ?></h4>

<div class="control-group">
    <label class="control-label"><?php echo JText::_('COM_ATS_TICKETS_HEADING_TITLE'); ?></label>
    <div class="controls">
        <input type="text" name="title" class="ats-title" value="<?php echo $this->escape($this->item->title) ?>" />
    </div>
</div>
<div class="control-group">
    <label class="control-label"><?php echo JText::_('COM_ATS_TICKETS_HEADING_SLUG'); ?></label>
    <div class="controls">
        <input type="text" name="alias" class="ats-slug" value="<?php echo $this->escape($this->item->alias) ?>" />
    </div>
</div>
<div class="control-group">
    <label class="control-label"><?php echo JText::_('COM_ATS_TICKETS_HEADING_CATEGORY'); ?></label>
    <div class="controls">
        <?php echo Select::getCategories($this->item->catid, 'catid')?>
    </div>
</div>
<div class="control-group">
    <label class="control-label"><?php echo JText::_('COM_ATS_TICKETS_HEADING_STATUS'); ?></label>
    <div class="controls">
        <?php echo Select::ticketstatuses($this->item->status, 'status', array(), 'atsstatus')?>
    </div>
</div>
<div class="control-group">
    <label class="control-label"><?php echo JText::_('COM_ATS_TICKETS_HEADING_PUBLIC'); ?></label>
    <div class="controls">
        <?php echo Select::publicstate($this->item->public, 'public') ?>
    </div>
</div>
<div class="control-group">
    <label class="control-label"><?php echo JText::_('COM_ATS_TICKET_PRIORITY'); ?></label>
    <div class="controls">
        <?php echo Select::priorities('priority', $this->item->priority) ?>
    </div>
</div>
<?php if (ATS_PRO): ?>
<div class="control-group">
    <label class="control-label"><?php echo JText::_('COM_ATS_TICKETS_ASSIGN_TO'); ?></label>
    <div class="controls">
        <?php echo Select::getManagers($this->item->assigned_to, 'assigned_to', array(), $this->item->catid) ?>
    </div>
</div>
<div class="control-group">
    <label class="control-label"><?php echo JText::_('COM_ATS_TICKETS_HEADING_BUCKET'); ?></label>
    <div class="controls">
        <?php echo Select::buckets($this->item->ats_bucket_id) ?>
    </div>
</div>
<?php endif; ?>
<div class="control-group">
    <label class="control-label"><?php echo JText::_('COM_ATS_TICKETS_HEADING_ORIGIN'); ?></label>
    <div class="controls">
        <?php echo $this->item->origin ? JText::_('COM_ATS_TICKETS_ORIGIN_'.$this->item->origin) : JText::_('COM_ATS_TICKETS_ORIGIN_WEB') ?>
    </div>
</div>
<div class="control-group">
    <label class="control-label"><?php echo JText::_('COM_ATS_TICKETS_HEADING_CREATED'); ?></label>
    <div class="controls">
        <?php echo Format::date($this->item->created_on) ?>
    </div>
</div>
<div class="control-group">
    <label class="control-label"><?php echo JText::_('COM_ATS_TICKETS_HEADING_MODIFIED'); ?></label>
    <div class="controls">
        <?php if($this->item->modified_on == '0000-00-00 00:00:00'): ?>
            &mdash;
        <?php else: ?>
            <?php echo Format::date($this->item->modified_on) ?>
        <?php endif; ?>
    </div>
</div>
