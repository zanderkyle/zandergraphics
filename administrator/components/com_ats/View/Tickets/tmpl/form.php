<?php
/**
* @package   AkeebaTicketSystem
* @copyright Copyright (c)2011-2015 Nicholas K. Dionysopoulos
* @license   GNU General Public License version 3, or later
*/

/** @var \Akeeba\TicketSystem\Admin\View\Tickets\Html $this */

defined('_JEXEC') or die;

$container = $this->getContainer();

$container->template->addJSInline("\n;//\nfunction addToValidationFetchQueue(myfunction){}");
$container->template->addJSInline("\n;//\nfunction addToValidationQueue(myfunction){}");

?>
<form action="index.php?option=com_ats&view=Ticket" method="post" name="adminForm" id="adminForm" class="form form-horizontal">
    <input type="hidden" name="option" value="com_ats" />
    <input type="hidden" name="view" value="Tickets" />
    <input type="hidden" name="task" value="" />
    <input type="hidden" name="ats_ticket_id" value="<?php echo $this->item->ats_ticket_id ?>" />
    <input type="hidden" name="<?php echo $this->getContainer()->session->getFormToken()?>" value="1" />

    <div class="span6">
        <?php echo $this->loadTemplate('general'); ?>
    </div>

    <div class="span6">
    <?php echo
        $this->loadTemplate('userinfo');

        $jResponse = array();

        // I have to display custom fields only on edit mode, since I don't have the catid while creating it
        // (in frontend I'm ok since I can get it from menu params)
        if($this->item->ats_ticket_id)
        {
            // I use the array + array syntax so empty values won't replace valid ones
            $args = $this->item->getData() + $this->cache;

            $container->platform->importPlugin('ats');
            $jResponse = $container->platform->runPlugins('onTicketFormRenderPerCatFields', array($args));
        }

        if(is_array($jResponse) && !empty($jResponse))
        {
            $customFields = array_pop($jResponse);
    ?>
        <h3><?php echo JText::_('COM_ATS_TITLE_CUSTOMFIELDS')?></h3>

        <?php
            if(is_array($customFields) && !empty($customFields))
            {
                foreach($customFields as $field)
                {
                    if (array_key_exists('isValid', $field))
                    {
                        $customField_class = $field['isValid'] ? (array_key_exists('validLabel', $field) ? 'success' : '') : 'error';
                    }
                    else
                    {
                        $customField_class = '';
                    }
                    ?>
                    <div class="control-group <?php echo $customField_class ?>">
                        <label for="<?php echo $field['id']?>" class="control-label">
                            <?php echo $field['label']?>
                        </label>

                        <div class="controls">
                            <?php echo $field['elementHTML']?>
                            <?php if (array_key_exists('validLabel', $field)): ?>
                                <span id="<?php echo $field['id'] ?>_valid" class="help-inline"
                                      style="<?php if (!$field['isValid']): ?>display:none<?php endif ?>">
									  <?php echo $field['validLabel'] ?>
							</span>
                            <?php endif;?>
                            <?php if (array_key_exists('invalidLabel', $field)): ?>
                                <span id="<?php echo $field['id'] ?>_invalid" class="help-inline"
                                      style="<?php if ($field['isValid']): ?>display:none<?php endif ?>">
									  <?php echo $field['invalidLabel'] ?>
							</span>
                            <?php endif;?>
                        </div>
                    </div>

                <?php
                }
            }
        }
        ?>
    </div>
</form>

<?php // Load the post form only if we have a ticket (let's keep it simple)?>
<?php if($this->item->ats_ticket_id):?>
    <div class="span12">
        <div class="tabbable">
            <ul class="nav nav-tabs">
                <li class="active">
                    <a href="#ats_ticket_backend_conversation" data-toggle="tab">
                        <?php echo JText::_('COM_ATS_TICKETS_LEGEND_CONVO') ?>
                    </a>
                </li>
            <?php if($this->isManager):?>
                <li>
                    <a href="#ats_ticket_backend_notes" data-toggle="tab">
                        <?php echo JText::_('COM_ATS_TICKETS_LEGEND_MANAGERNOTES') ?>
                    </a>
                </li>
            <?php endif; ?>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="ats_ticket_backend_conversation">
                    <h3><?php echo JText::_('COM_ATS_TICKETS_LEGEND_CONVO'); ?></h3>
                    <?php
                        $posts = $this->item->posts;

                        $this->items = $posts;
                        echo $this->loadAnyTemplate('admin:com_ats/Posts/threaded');
                        $this->items = null;
                    ?>

                    <div class="ats-clear"></div>

                    <?php if($this->item->status != 'C'): ?>
                        <h3 class="ats-ticket-reply-header"><?php echo JText::_('COM_ATS_POSTS_HEADING_REPLYAREA')?></h3>

                        <?php // If the ticket is already assigned to another manager, warn the user ?>
                        <?php if($this->item->assigned_to && $this->item->assigned_to != $container->platform->getUser()->id):?>
                            <div class="alert alert-danger">
                                <?php echo JText::sprintf('COM_ATS_TICKET_ALREADY_ASSIGNED_WARN', $container->platform->getUser($this->item->assigned_to)->name)?>
                            </div>
                        <?php endif;?>

                        <?php
                            echo $this->loadAnyTemplate('admin:com_ats/Posts/form');
                        ?>
                    <?php endif; ?>
                </div>
            <?php if($this->isManager):?>
                <div class="tab-pane" id="ats_ticket_backend_notes">
                    <h3><?php echo JText::_('COM_ATS_TICKETS_LEGEND_MANAGERNOTES'); ?></h3>
                    <?php
                        $notes = $this->item->manager_notes;

                        $this->items = $notes;
                        echo $this->loadAnyTemplate('admin:com_ats/ManagerNotes/threaded');
                        $this->items = null;
                    ?>

                    <h3 class="ats-ticket-reply-header"><?php echo JText::_('COM_ATS_POSTS_HEADING_MANAGERNOTEAREA')?></h3>
                    <?php
                        echo $this->loadAnyTemplate('admin:com_ats/ManagerNotes/form');
                    ?>
                </div>
            <?php endif; ?>
            </div>
        </div>
    </div>
<?php endif?>