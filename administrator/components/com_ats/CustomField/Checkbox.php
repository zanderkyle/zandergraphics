<?php
/**
 * @package   AkeebaTicketSystem
 * @copyright Copyright (c)2011-2015 Nicholas K. Dionysopoulos
 * @license   GNU General Public License version 3, or later
 */

namespace Akeeba\TicketSystem\Admin\CustomField;

use JText;
use JFactory;

defined('_JEXEC') or die();

/**
 * A single checkbox field
 *
 * @author Nicholas K. Dionysopoulos
 */
class Checkbox extends Base
{
	public function getField($item, $cache, $userparams)
	{
		$default = strtoupper(trim($item->default));

		switch ($default)
		{
			case 'YES':
			case 'TRUE':
			case 'ON':
			case '1':
			case 'ENABLED':
			case 'CHECKED':
			case 'SELECTED':
				$default = 1;
				break;

			default:
				$default = 0;
				break;
		}

		// Get the current value
		if (array_key_exists($item->slug, $cache['params']))
		{
			$current = $cache['params'][ $item->slug ];
		}
		else
		{
			if (!is_object($userparams->params))
			{
				$current = $default;
			}
			else
			{
				$slug    = $item->slug;
				$current = property_exists($userparams->params, $item->slug) ? $userparams->params->$slug : $default;
			}
		}

		// Is this a required field?
		$required = $item->allow_empty ? '' : '* ';

		// Parse value
		if ($current)
		{
			$checked = 'checked="checked"';
		}
		else
		{
			$checked = '';
		}

		// Set up field's HTML content
		$html = '<input type="checkbox" name="params[' . $item->slug . ']" id="' . $item->slug . '" ' . $checked . ' />';

		// Setup the field
		$field = array(
			'id'          => $item->slug,
			'label'       => $required . JText::_($item->title),
			'elementHTML' => $html,
			'isValid'     => $required ? !empty($current) : true
		);

		if ($item->invalid_label)
		{
			$field['invalidLabel'] = JText::_($item->invalid_label);
		}

		if ($item->valid_label)
		{
			$field['validLabel'] = JText::_($item->valid_label);
		}

		return $field;
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
		result.$slug = $('#$slug').is(':checked') ? 1 : 0;
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

	public function validate($item, $custom)
	{
		if (!is_array($custom) || !array_key_exists($item->slug, $custom))
		{
			$custom[ $item->slug ] = 0;
		}

		$valid = true;

		if (!$item->allow_empty)
		{
			$valid = $custom[ $item->slug ];
		}

		return $valid ? 1 : 0;
	}
}