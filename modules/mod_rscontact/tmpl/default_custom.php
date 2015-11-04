<?php
/**
* @package RSContact!
* @copyright (C) 2015 www.rsjoomla.com
* @license GPL, http://www.gnu.org/licenses/gpl-3.0.html
*/

defined('_JEXEC') or die('Restricted access');

for ($i=1; $i<=3; $i++) {
	if (${"show_cf" . $i}) { ?>
		<?php $placeholder = !$show_label ? 'placeholder="'.${"cf" . $i. "_name"}.'"' : '';
			  if(${"show_cf" .$i. "_req"} && !$show_label){
				$placeholder = 'placeholder="'.${"cf" . $i. "_name"}.' '.$required_marker.'"';   
			  }
		?>
		<div class="control-group">
	<?php if ($show_label) { ?>
			<label for="mod-rscontact-cf<?php echo $i.'-'.$uniqid; ?>"  class="control-label"><?php echo ${"cf" . $i. "_name"};?>
		<?php if(${"show_cf" .$i. "_req"}) { ?>
				<span class="help-inline"><?php echo $required_marker;?></span>
		<?php } ?>
			</label>
	<?php } ?>
			<div class="controls">
		<?php if (${"show_cf" . $i. "_req"}) { 
				$class = 'required'.$class_auto;
			  } else {
				$class = 'ignore'.$class_auto;
			  }
			require JModuleHelper::getLayoutPath('mod_rscontact', 'default_custom_'.${"show_cf" .$i. "_type"});
		?> 
			</div>
		</div>
<?php 
	}
} ?>