<?php
/**
 * @copyright	Copyright (C) 2011 Simplify Your Web, Inc. All rights reserved.
 * @license		GNU General Public License version 3 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;
?>

<?php if (intval($jversion[0]) > 2) : ?>
    <div>
        <table class="table table-condensed table-bordered">
            <thead>
                <tr>
                    <th><?php echo JText::_('LIB_SYW_GDTEST_VERSION'); ?></th>
                    <th><?php echo GD_VERSION; ?></th>
                </tr>
            </thead>
            <tbody>
            	<?php if ($show_gif) : ?>
	                <?php if (imagetypes() & IMG_GIF) : ?>
	                    <tr class="success">
	                        <td><?php echo JText::_('LIB_SYW_GDTEST_GIF_SUPPORT'); ?></td>
	                        <td><span class="label label-success"><?php echo JText::_('JENABLED'); ?></span></td>
	                    </tr>
	                <?php else : ?>
	                    <tr class="error">
	                        <td><?php echo JText::_('LIB_SYW_GDTEST_GIF_SUPPORT'); ?></td>
	                        <td><span class="label label-important"><?php echo JText::_('JDISABLED'); ?></span></td>
	                    </tr>
	                <?php endif; ?>
	            <?php endif; ?>
                <?php if (imagetypes() & IMG_JPG) : ?>
                    <tr class="success">
                        <td><?php echo JText::_('LIB_SYW_GDTEST_JPG_SUPPORT'); ?></td>
                        <td><span class="label label-success"><?php echo JText::_('JENABLED'); ?></span></td>
                    </tr>
                <?php else : ?>
                    <tr class="error">
                        <td><?php echo JText::_('LIB_SYW_GDTEST_JPG_SUPPORT'); ?></td>
                        <td><span class="label label-important"><?php echo JText::_('JDISABLED'); ?></span></td>
                    </tr>
                <?php endif; ?>
                <?php if (imagetypes() & IMG_PNG) : ?>
                    <tr class="success">
                        <td><?php echo JText::_('LIB_SYW_GDTEST_PNG_SUPPORT'); ?></td>
                        <td><span class="label label-success"><?php echo JText::_('JENABLED'); ?></span></td>
                    </tr>
                <?php else : ?>
                    <tr class="error">
                        <td><?php echo JText::_('LIB_SYW_GDTEST_PNG_SUPPORT'); ?></td>
                        <td><span class="label label-important"><?php echo JText::_('JDISABLED'); ?></span></td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
<?php else : ?>
    <div style="margin: 5px 0; padding: 10px; background-color: #D9EDF7;">
		<table width="100%" cellpading="2" cellspacing="2">
			<tr style="font-weight:bold"><td width="50%"><?php echo JText::_('LIB_SYW_GDTEST_VERSION'); ?></td><td style="text-align: center"><?php echo GD_VERSION; ?></td></tr>
			<?php if ($show_gif) : ?>
				<tr><td width="50%"><?php echo JText::_('LIB_SYW_GDTEST_GIF_SUPPORT'); ?></td><td style="text-align: center"><?php if (imagetypes() & IMG_GIF) : echo JText::_('JENABLED'); else : echo JText::_('JDISABLED'); endif; ?></td></tr>
			<?php endif; ?>
			<tr><td width="50%"><?php echo JText::_('LIB_SYW_GDTEST_JPG_SUPPORT'); ?></td><td style="text-align: center"><?php if (imagetypes() & IMG_JPG) : echo JText::_('JENABLED'); else : echo JText::_('JDISABLED'); endif; ?></td></tr>
			<tr><td width="50%"><?php echo JText::_('LIB_SYW_GDTEST_PNG_SUPPORT'); ?></td><td style="text-align: center"><?php if (imagetypes() & IMG_PNG) : echo JText::_('JENABLED'); else : echo JText::_('JDISABLED'); endif;?></td></tr>
		</table>
	</div>
<?php endif; ?>
