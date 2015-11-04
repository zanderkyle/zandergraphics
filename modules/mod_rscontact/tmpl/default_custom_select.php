<?php
/**
* @package RSContact!
* @copyright (C) 2015 www.rsjoomla.com
* @license GPL, http://www.gnu.org/licenses/gpl-3.0.html
*/

defined('_JEXEC') or die('Restricted access');

 if (${"cf" .$i. "_input"}) {
		if(is_string(${"cf" .$i. "_input"})) {
			$options = modRSContactHelper::split(${"cf" .$i. "_input"});
		}
		if(!$show_label ) {
			if(${"show_cf" .$i. "_req"}) {
				$cf_select = JText::_('MOD_RSCONTACT_CUSTOM_FIELD'.$i.'_SELECT').' '.${"cf" . $i. "_name"}.' '.$required_marker;
			} else {
				$cf_select = JText::_('MOD_RSCONTACT_CUSTOM_FIELD'.$i.'_SELECT').' '.${"cf" . $i. "_name"};
			}
		} else {
			$cf_select = JText::_('MOD_RSCONTACT_CUSTOM_FIELD'.$i.'_SELECT');
		}
		
		${"data_cf" .$i }[] = JHtml::_('select.option', "", $cf_select);
		foreach ($options as $input) {
			${"data_cf" .$i }[] = JHtml::_('select.option', $input, $input);
		}
		echo JHtml::_('select.genericlist', ${"data_cf" .$i }, 'mod_rscontact_cf'.$i, 'class="'.$class.'"', 'value', 'text', null, 'mod_rscontact_cf'.$i.'_'.$uniqid);
		
	 } else { ?>
		<div class="alert alert-error">
			<?php echo JText::sprintf('MOD_RSCONTACT_INPUT_ERROR', ${"cf" . $i. "_name"}); ?>
		</div>
<?php } ?>