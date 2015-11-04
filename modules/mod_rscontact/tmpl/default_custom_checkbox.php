<?php
/**
* @package RSContact!
* @copyright (C) 2015 www.rsjoomla.com
* @license GPL, http://www.gnu.org/licenses/gpl-3.0.html
*/

defined('_JEXEC') or die('Restricted access');

if (${"cf" .$i. "_input"}) {
		if (is_string(${"cf" .$i. "_input"})) {
			$options = modRSContactHelper::split(${"cf" .$i. "_input"});	
		} ?>
	<?php if (!$show_label) { ?>
			<label for="mod-rscontact-cf<?php echo $i.'-'.$uniqid; ?>"  class="control-label">
			<?php echo ${"cf" . $i. "_name"};
				  if(${"show_cf" .$i. "_req"}) { ?>
					<span class="help-inline"><?php echo $required_marker;?></span>
			<?php } ?>
			
			</label>
	<?php }
		foreach ($options as $option){
			$id = 'mod-rscontact-cf'.$i.'-'.JFilterOutput::stringURLSafe($option).'-'.$uniqid; ?>
			<label for="<?php echo $id;?>" class="checkbox">
				<input type="checkbox" class="<?php echo $class; ?>" id="<?php echo $id;?>" name="<?php echo 'mod_rscontact_cf'.$i; ?>[]" value="<?php echo $option; ?>" /><?php echo $option; ?>
			</label>
	<?php } ?>
			<label for="mod_rscontact_cf<?php echo $i; ?>" class="mod-rscontact-error" style="display: none;"></label>
<?php } else { ?>
			<div class="alert alert-error">
			<?php echo JText::sprintf('MOD_RSCONTACT_INPUT_ERROR', ${"cf" . $i. "_name"}); ?>
			
			</div>
<?php	} ?>