<?php
/**
 * @package ats
 * @copyright Copyright (c)2011-2014 Nicholas K. Dionysopoulos / AkeebaBackup.com
 * @license GNU GPL v3 or later
 */

/** @var Akeeba\TicketSystem\Site\View\Categories\Html $this */
defined('_JEXEC') or die;

use \Akeeba\TicketSystem\Admin\Helper\Html;
use \Akeeba\TicketSystem\Admin\Helper\Permissions;

$this->getContainer()->template->addCSS('media://com_ats/css/frontend.css');
?>
<div class="akeeba-bootstrap">

<?php echo Html::loadposition('ats-top'); ?>
<?php echo Html::loadposition('ats-categories-top'); ?>

<?php if ($this->getPageParams()->get('show_page_heading', 1)) : ?>
	<h1>
		<?php echo $this->escape($this->getPageParams()->get('page_heading', JText::_('COM_ATS_CATEGORIES_TITLE'))); ?>
	</h1>
<?php endif; ?>

<?php if(!count($this->items)):?>
<?php echo Html::loadposition('ats-categories-none-top'); ?>
<p>
	<?php echo JText::_('COM_ATS_CATEGORIES_MSG_NOCATEGORIES') ?>
</p>
<?php echo Html::loadposition('ats-categories-none-bottom'); ?>
<?php else: ?>
<table class="table table-striped">
	<tbody>
<?php
    /** @var \Akeeba\TicketSystem\Site\Model\Categories $cat */
    foreach($this->items as $cat)
    {
        $catURL          = JRoute::_('index.php?option=com_ats&view=Tickets&category='.$cat->id);
        $newTicketURL    = JRoute::_('index.php?option=com_ats&view=NewTicket&category='.$cat->id);

        $catParams = new \JRegistry($cat->params);

        $catimage        = $catParams->get('image', '');
        $catalt          = $catParams->get('image_alt', '');

        $actions = Permissions::getActions($cat->id);
?>
		<tr>
			<td>
				<h4>
                <?php
                    if($cat->level > 1)
                    {
                        for($i = 1; $i < $cat->level; $i++)
                        {
                            echo '<div class="ats-category-levelpad">&#x2520;</div>';
                        }
                    }
                ?>
					<a href="<?php echo $catURL ?>">
						<?php echo $this->escape($cat->title) ?>
					</a>
				</h4>

				<div class="ats-category-quickbuttons pull-right">
					<a class="btn btn-info" href="<?php echo $catURL ?>">
						<i class="icon-folder-open icon-white"></i>
						<?php echo JText::_('COM_ATS_CATEGORIES_VIEWTICKETS') ?>
					</a>
                    <?php if($actions['core.create']): ?>
                        <br/>
                        <a class="btn btn-success btn-small" href="<?php echo $newTicketURL ?>">
						    <i class="icon-file icon-white"></i>
						    <?php echo JText::_('COM_ATS_TICKETS_BUTTON_NEWTICKET') ?>
					    </a>
					<?php endif; ?>
				</div>

				<div class="ats-category-desc">
					<?php
                        if($catimage)
                        {
                            echo '<img src="'.$catimage.'" alt="'.$catalt.'" class="pull-left ats-category-image" />';
                        }

                        echo $cat->description;
                    ?>
				</div>
			</td>
		</tr>
<?php }; ?>
	</tbody>
</table>
<?php endif; ?>

<?php echo Html::loadposition('ats-categories-bottom'); ?>
<?php echo Html::loadposition('ats-bottom'); ?>

</div>