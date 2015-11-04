<?php
/**
* @version 1.0.0
* @package RSSeo! 1.0.0
* @copyright (C) 2009-2012 www.rsjoomla.com
* @license GPL, http://www.gnu.org/copyleft/gpl.html
*/

defined('_JEXEC') or die('Restricted access');?>

<form action="<?php echo JRoute::_('index.php?option=com_rsseo'); ?>" method="post" name="adminForm" id="adminForm" autocomplete="off" class="form-validate form-horizontal">

<div class="row-fluid">
	<div class="width-70 fltlft">
		<div class="dashboard-container">
			<div class="span2">
				<div class="dashboard-wraper">
					<div class="dashboard-content"> 
						<a href="index.php?option=com_rsseo&amp;view=competitors"> 
							<img src="<?php echo JURI::root(); ?>administrator/components/com_rsseo/assets/images/dashboard/competitors.png" alt="<?php echo JText::_('COM_RSSEO_MENU_SEO_PERFORMANCE'); ?>" />
							<span class="dashboard-title"><?php echo JText::_('COM_RSSEO_MENU_SEO_PERFORMANCE'); ?></span> 
						</a> 
					</div>
				</div>
			</div>
			<div class="span2">
				<div class="dashboard-wraper">
					<div class="dashboard-content"> 
						<a href="index.php?option=com_rsseo&amp;view=pages"> 
							<img src="<?php echo JURI::root(); ?>administrator/components/com_rsseo/assets/images/dashboard/pages.png" alt="<?php echo JText::_('COM_RSSEO_MENU_PAGES'); ?>" />
							<span class="dashboard-title"><?php echo JText::_('COM_RSSEO_MENU_PAGES'); ?></span> 
						</a> 
					</div>
				</div>
			</div>
			<div class="span2">
				<div class="dashboard-wraper">
					<div class="dashboard-content"> 
						<a href="index.php?option=com_rsseo&amp;view=crawler"> 
							<img src="<?php echo JURI::root(); ?>administrator/components/com_rsseo/assets/images/dashboard/crawler.png" alt="<?php echo JText::_('COM_RSSEO_MENU_CRAWLER'); ?>" />
							<span class="dashboard-title"><?php echo JText::_('COM_RSSEO_MENU_CRAWLER'); ?></span> 
						</a> 
					</div>
				</div>
			</div>
			<div class="span2">
				<div class="dashboard-wraper">
					<div class="dashboard-content"> 
						<a href="index.php?option=com_rsseo&amp;view=sitemap"> 
							<img src="<?php echo JURI::root(); ?>administrator/components/com_rsseo/assets/images/dashboard/sitemap.png" alt="<?php echo JText::_('COM_RSSEO_MENU_SITEMAP'); ?>" />
							<span class="dashboard-title"><?php echo JText::_('COM_RSSEO_MENU_SITEMAP'); ?></span> 
						</a> 
					</div>
				</div>
			</div>
			<div class="span2">
				<div class="dashboard-wraper">
					<div class="dashboard-content"> 
						<a href="index.php?option=com_rsseo&amp;view=redirects"> 
							<img src="<?php echo JURI::root(); ?>administrator/components/com_rsseo/assets/images/dashboard/redirects.png" alt="<?php echo JText::_('COM_RSSEO_MENU_REDIRECTS'); ?>" />
							<span class="dashboard-title"><?php echo JText::_('COM_RSSEO_MENU_REDIRECTS'); ?></span> 
						</a> 
					</div>
				</div>
			</div>
			<div class="span2">
				<div class="dashboard-wraper">
					<div class="dashboard-content"> 
						<a href="index.php?option=com_rsseo&amp;view=keywords"> 
							<img src="<?php echo JURI::root(); ?>administrator/components/com_rsseo/assets/images/dashboard/keywords.png" alt="<?php echo JText::_('COM_RSSEO_MENU_KEYWORDS'); ?>" />
							<span class="dashboard-title"><?php echo JText::_('COM_RSSEO_MENU_KEYWORDS'); ?></span> 
						</a> 
					</div>
				</div>
			</div>
			<div class="span2">
				<div class="dashboard-wraper">
					<div class="dashboard-content"> 
						<a href="index.php?option=com_rsseo&amp;view=backup"> 
							<img src="<?php echo JURI::root(); ?>administrator/components/com_rsseo/assets/images/dashboard/backup.png" alt="<?php echo JText::_('COM_RSSEO_MENU_BACKUP_RESTORE'); ?>" />
							<span class="dashboard-title"><?php echo JText::_('COM_RSSEO_MENU_BACKUP_RESTORE'); ?></span> 
						</a> 
					</div>
				</div>
			</div>
			<div class="span2">
				<div class="dashboard-wraper">
					<div class="dashboard-content"> 
						<a href="index.php?option=com_rsseo&amp;view=analytics"> 
							<img src="<?php echo JURI::root(); ?>administrator/components/com_rsseo/assets/images/dashboard/analytics.png" alt="<?php echo JText::_('COM_RSSEO_MENU_ANALYTICS'); ?>" />
							<span class="dashboard-title"><?php echo JText::_('COM_RSSEO_MENU_ANALYTICS'); ?></span> 
						</a> 
					</div>
				</div>
			</div>
			<div class="span2">
				<div class="dashboard-wraper">
					<div class="dashboard-content"> 
						<a href="index.php?option=com_rsseo&amp;task=connectivity"> 
							<img src="<?php echo JURI::root(); ?>administrator/components/com_rsseo/assets/images/dashboard/checkconnection.png" alt="<?php echo JText::_('COM_RSSEO_CHECK_CONNECTIVITY'); ?>" />
							<span class="dashboard-title"><?php echo JText::_('COM_RSSEO_CHECK_CONNECTIVITY'); ?></span> 
						</a> 
					</div>
				</div>
			</div>
			<div class="span2">
				<div class="dashboard-wraper">
					<div class="dashboard-content"> 
						<a href="index.php?option=com_rsseo&amp;task=connectivity&amp;google=1"> 
							<img src="<?php echo JURI::root(); ?>administrator/components/com_rsseo/assets/images/dashboard/google.png" alt="<?php echo JText::_('COM_RSSEO_CHECK_GOOGLE_CONNECTIVITY'); ?>" />
							<span class="dashboard-title"><?php echo JText::_('COM_RSSEO_CHECK_GOOGLE_CONNECTIVITY'); ?></span> 
						</a> 
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="width-30 fltrt">
		<div class="dashboard-container">
			<div class="dashboard-info">
				<span>
					<img src="<?php echo JURI::root(true); ?>/administrator/components/com_rsseo/assets/images/rsseo.png" align="middle" alt="RSSeo!" />
				</span>
				<table class="dashboard-table">
					<tr>
						<td nowrap="nowrap"><strong><?php echo JText::_('COM_RSSEO_PRODUCT_VERSION') ?>: </strong></td>
						<td nowrap="nowrap">RSSeo! <?php echo RSSEO_VERSION; ?> Rev <?php echo RSSEO_REVISION; ?></td>
					</tr>
					<tr>
						<td nowrap="nowrap"><strong><?php echo JText::_('COM_RSSEO_COPYRIGHT_NAME') ?>: </strong></td>
						<td nowrap="nowrap">&copy; 2007 - <?php echo date('Y'); ?> <a href="http://www.rsjoomla.com" target="_blank">RSJoomla.com</a></td>
					</tr>
					<tr>
						<td nowrap="nowrap"><strong><?php echo JText::_('COM_RSSEO_LICENSE_NAME') ?>: </strong></td>
						<td nowrap="nowrap">GPL Commercial License</a></td>
					</tr>
					<tr>
						<td nowrap="nowrap"><strong><?php echo JText::_('COM_RSSEO_CODE_FOR_UPDATE') ?>: </strong></td>
						<?php if (strlen($this->code) == 20) { ?>
						<td nowrap="nowrap" class="correct-code"><?php echo $this->escape($this->code); ?></td>
						<?php } elseif ($this->code) { ?>
						<td nowrap="nowrap" class="incorrect-code"><?php echo $this->escape($this->code); ?></td>
						<?php } else { ?>
						<td nowrap="nowrap" class="missing-code">
							<?php if (rsseoHelper::isJ3()) { ?>
							<a href="<?php echo JRoute::_('index.php?option=com_config&view=component&component=com_rsseo&path=&return='.base64_encode(JURI::getInstance())); ?>">
								<?php echo JText::_('COM_RSSEO_PLEASE_ENTER_YOUR_CODE_IN_THE_CONFIGURATION'); ?>
							</a>
							<?php } else { ?>
							<a class="modal" rel="{handler: 'iframe', size: {x: 875, y: 550}, onClose: function() {}}" href="index.php?option=com_config&view=component&component=com_rsseo&path=&tmpl=component">
								<?php echo JText::_('COM_RSSEO_PLEASE_ENTER_YOUR_CODE_IN_THE_CONFIGURATION'); ?>
							</a>
							<?php } ?>
						</td>
						<?php } ?>
					</tr>
				</table>
			</div>
		</div>
	</div>
</div>

<?php echo JHTML::_('form.token'); ?>
<input type="hidden" name="task" value="" />
<?php echo JHTML::_('behavior.keepalive'); ?>
</form>