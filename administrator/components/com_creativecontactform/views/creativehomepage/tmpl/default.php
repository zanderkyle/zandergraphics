<?php
/**
 * Joomla! component Creative Contact Form
 *
 * @version $Id: 2012-04-05 14:30:25 svn $
 * @author creative-solutions.net
 * @package Creative Contact Form
 * @subpackage com_creativecontactform
 * @license GNU/GPL
 *
 */

// no direct access
defined('_JEXEC') or die('Restircted access');

?>
<div id="m_wrapper">

<div id="cpanel">
	<div class="icon">
		<a href="index.php?option=com_creativecontactform&view=creativeforms" title="<?php echo JText::_( 'COM_CREATIVECONTACTFORM_SUBMENU_FORMS' ); ?>">
			<table style="width: 100%;height: 100%;text-decoration: none;">
				<tr>
					<td align="center" valign="middle">
						<img src="components/com_creativecontactform/assets/images/category.png" /><br />
						<?php echo JText::_( 'COM_CREATIVECONTACTFORM_SUBMENU_FORMS' ); ?>
					</td>
				</tr>
			</table>
		</a>
	</div>
</div>
<div id="cpanel">
	<div class="icon">
		<a href="index.php?option=com_creativecontactform&view=creativefields" title="<?php echo JText::_( 'COM_CREATIVECONTACTFORM_SUBMENU_FIELDS' ); ?>">
			<table style="width: 100%;height: 100%;text-decoration: none;">
				<tr>
					<td align="center" valign="middle">
						<img src="components/com_creativecontactform/assets/images/answer.png" /><br />
						<?php echo JText::_( 'COM_CREATIVECONTACTFORM_SUBMENU_FIELDS' ); ?>
					</td>
				</tr>
			</table>
		</a>
	</div>
</div>
<div id="cpanel">
	<div class="icon">
		<a href="index.php?option=com_creativecontactform&view=templates" title="<?php echo JText::_( 'COM_CREATIVECONTACTFORM_SUBMENU_TEMPLATES' ); ?>">
			<table style="width: 100%;height: 100%;text-decoration: none;">
				<tr>
					<td align="center" valign="middle">
						<img src="components/com_creativecontactform/assets/images/template.png" /><br />
						<?php echo JText::_( 'COM_CREATIVECONTACTFORM_SUBMENU_TEMPLATES' ); ?>
					</td>
				</tr>
			</table>
		</a>
	</div>
</div>

<div id="cpanel">
	<div class="icon" style="float: right;">
		<a href="<?php echo JText::_( 'COM_CREATIVECONTACTFORM_SUBMENU_BUY_PRO_VERSION_LINK' ); ?>" target="_blank" title="<?php echo JText::_( 'COM_CREATIVECONTACTFORM_SUBMENU_BUY_PRO_VERSION_DESCRIPTION' ); ?>">
			<table style="width: 100%;height: 100%;text-decoration: none;">
				<tr>
					<td align="center" valign="middle">
						<img src="components/com_creativecontactform/assets/images/shopping_cart.png" /><br />
						<?php echo JText::_( 'COM_CREATIVECONTACTFORM_SUBMENU_BUY_PRO_VERSION' ); ?>
					</td>
				</tr>
			</table>
		</a>
	</div>
</div>
<div id="cpanel">
	<div class="icon" style="float: right;">
		<a href="<?php echo JText::_( 'COM_CREATIVECONTACTFORM_SUBMENU_RATE_US_LINK' ); ?>" target="_blank" title="<?php echo JText::_( 'COM_CREATIVECONTACTFORM_SUBMENU_RATE_US_DESCRIPTION' ); ?>">
			<table style="width: 100%;height: 100%;text-decoration: none;">
				<tr>
					<td align="center" valign="middle">
						<img src="components/com_creativecontactform/assets/images/icon-star-48.png" /><br />
						<?php echo JText::_( 'COM_CREATIVECONTACTFORM_SUBMENU_RATE_US' ); ?>
					</td>
				</tr>
			</table>
		</a>
	</div>
</div>
<div id="cpanel">
	<div class="icon" style="float: right;">
		<a href="<?php echo JText::_( 'COM_CREATIVECONTACTFORM_SUBMENU_SUPPORT_FORUM_LINK' ); ?>" target="_blank" title="<?php echo JText::_( 'COM_CREATIVECONTACTFORM_SUBMENU_SUPPORT_FORUM_DESCRIPTION' ); ?>">
			<table style="width: 100%;height: 100%;text-decoration: none;">
				<tr>
					<td align="center" valign="middle">
						<img src="components/com_creativecontactform/assets/images/forum.png" /><br />
						<?php echo JText::_( 'COM_CREATIVECONTACTFORM_SUBMENU_SUPPORT_FORUM' ); ?>
					</td>
				</tr>
			</table>
		</a>
	</div>
</div>
<div id="cpanel">
	<div class="icon" style="float: right;">
		<a href="<?php echo JText::_( 'COM_CREATIVECONTACTFORM_SUBMENU_PROJECT_HOMEPAGE_LINK' ); ?>" target="_blank" title="<?php echo JText::_( 'COM_CREATIVECONTACTFORM_SUBMENU_PROJECT_HOMEPAGE_DESCRIPTION' ); ?>">
			<table style="width: 100%;height: 100%;text-decoration: none;">
				<tr>
					<td align="center" valign="middle">
						<img src="components/com_creativecontactform/assets/images/project.png" /><br />
						<?php echo JText::_( 'COM_CREATIVECONTACTFORM_SUBMENU_PROJECT_HOMEPAGE' ); ?>
					</td>
				</tr>
			</table>
		</a>
	</div>
</div>

<?php include (JPATH_BASE.'/components/com_creativecontactform/helpers/footer.php'); ?>
</div>
