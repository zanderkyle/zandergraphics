<?php
/**
 * @package ats
 * @copyright Copyright (c)2011-2014 Nicholas K. Dionysopoulos / AkeebaBackup.com
 * @license GNU GPL v3 or later
 */
use Akeeba\TicketSystem\Admin\Helper\Editor;
use Akeeba\TicketSystem\Admin\Model\ManagerNotes;

/** @var FOF30\View\DataView\Html $this */

defined('_JEXEC') or die;

$container = $this->getContainer();

if(!isset($quickreply))
{
    $quickreply = !($this->item instanceof ManagerNotes);
}

if(!isset($ats_ticket_id))
{
	$ats_ticket_id = 0;

	if(isset($this->item->ats_ticket_id))
    {
        $ats_ticket_id = $this->item->ats_ticket_id;
    }
}

if(!isset($ats_managernote_id))
{
	$ats_managernote_id = 0;

	if(isset($this->item->ats_managernote_id))
    {
        $ats_managernote_id = $this->item->ats_managernote_id;
    }
}

if(!isset($returnURL))
{
	$returnURLtemp = $this->input->getString('returnurl',null);

	if(!empty($returnURLtemp))
    {
        $returnURL = $returnURLtemp;
    }
}

?>

<div class="ats-ticket-replyarea">

<form action="index.php" method="post" <?php echo $quickreply ? '' : 'name="adminForm" id="adminForm"' ?>>
	<input type="hidden" name="option" value="com_ats" />
	<input type="hidden" name="view" value="ManagerNotes" />
	<input type="hidden" name="task" value="save" />
	<input type="hidden" name="ats_ticket_id" value="<?php echo $ats_ticket_id ?>" />
	<input type="hidden" name="ats_managernote_id" value="<?php echo $ats_managernote_id ?>" />
	<input type="hidden" name="<?php echo $container->session->getFormToken();?>" value="1" />
	<?php if(isset($returnURL)): ?>
	<input type="hidden" name="returnurl" value="<?php echo $returnURL ?>" />
	<?php endif; ?>

	<div class="ats-ticket-replyarea-content bbcode">
		<?php
			$contents = '';

			if(Editor::isEditorBBcode())
            {
				$name = 'note';
                $id   = 'bbcodemn';
			}
            else
            {
				$name = 'note_html';
                $id   = 'ats-note';
			}

			if(isset($this->item) && $this->item instanceof ManagerNotes)
            {
                if(Editor::isEditorBBcode())
                {
                    $contents = $this->item->note;
                }
                else
                {
                    $contents = $this->item->note_html;
                }
			}

			Editor::showEditor($name, $id, $contents, '95%', 400, 80, 20);
		?>
	</div>
	<div class="ats-clear"></div>

	<div class="ats-ticket-replyarea-postbutton">
		<input class="btn btn-primary" type="submit" value="<?php echo JText::_('COM_ATS_MANAGERNOTES_MSG_POST') ?>" />
	</div>
</form>
</div>