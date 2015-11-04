<?php
/**
 * @package   AkeebaTicketSystem
 * @copyright Copyright (c)2011-2015 Nicholas K. Dionysopoulos
 * @license   GNU General Public License version 3, or later
 */

namespace Akeeba\TicketSystem\Admin\CustomField;

use JFactory;

defined('_JEXEC') or die();

/**
 * A radio selection list field
 *
 * @author Nicholas K. Dionysopoulos
 */
class Radio extends Dropdown
{
	public function __construct(array $config = array())
	{
		parent::__construct($config);

		$this->input_type = 'radio';
	}

	public function getJavascript($item)
	{
		$slug       = $item->slug;
		$javascript = <<<JS

;// This comment is intentionally put here to prevent badly written plugins from causing a Javascript error
// due to missing trailing semicolon and/or newline in their code.
(function($) {
	$(document).ready(function(){
		addToValidationFetchQueue(plg_ats_customfields_fetch_$slug);
JS;
		if (!$item->allow_empty)
		{
			$javascript .= <<<JS

		addToValidationQueue(plg_ats_customfields_validate_$slug);
JS;
}
		$javascript .= <<<JS
	});
})(akeeba.jQuery);

function plg_ats_customfields_fetch_$slug()
{
	var result = {};
	(function($) {
		result.$slug = $('input:radio[name=params\\\\[$slug\\\\]]:checked').val();
	})(akeeba.jQuery);
	return result;
}

JS;

			if (!$item->allow_empty):
				$success_javascript = '';
				$failure_javascript = '';
				if (!empty($item->invalid_label))
				{
					$success_javascript .= "$('#{$slug}_invalid').css('display','none');\n";
					$failure_javascript .= "$('#{$slug}_invalid').css('display','inline-block');\n";
				}
				if (!empty($item->valid_label))
				{
					$success_javascript .= "$('#{$slug}_valid').css('display','inline-block');\n";
					$failure_javascript .= "$('#{$slug}_valid').css('display','none');\n";
				}
				$javascript .= <<<JS

function plg_ats_customfields_validate_$slug(response)
{
	var thisIsValid = true;
	(function($) {
		$('#$slug').parents('div.control-group').removeClass('error has-error success has-success');
		$('#{$slug}_invalid').css('display','none');
		$('#{$slug}_valid').css('display','none');
		if (!ats_apply_validation)
		{
		    thisIsValid = true;
			return;
		}

		if(response.custom_validation.$slug) {
			$('#$slug').parents('div.control-group').addClass('success has-success');
			$success_javascript
			thisIsValid = true;
		} else {
			$('#$slug').parents('div.control-group').addClass('error has-error');
			$failure_javascript
			thisIsValid = false;
		}
	})(akeeba.jQuery);

	return thisIsValid;
}

JS;
			endif;

			$document = JFactory::getDocument();
			$document->addScriptDeclaration($javascript);
		}
}