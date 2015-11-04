<?php
/**
 * @package   AkeebaTicketSystem
 * @copyright Copyright (c)2011-2015 Nicholas K. Dionysopoulos
 * @license   GNU General Public License version 3, or later
 */

namespace Akeeba\TicketSystem\Admin\CustomField;

use Akeeba\TicketSystem\Admin\Model\CustomFields;
use JText;
use JFactory;
use stdClass;

defined('_JEXEC') or die();

/**
 * A textarea field
 *
 * @author Nicholas K. Dionysopoulos
 * @since  2.6.0
 */
class Textarea extends Base
{
	/**
	 * Creates a custom field of the "textarea" type
	 *
	 * @param   CustomFields  $item        A custom field definition
	 * @param   array         $cache       The values cache
	 * @param   stdClass      $userparams  User parameters
	 *
	 * @return  array
	 */
	public function getField($item, $cache, $userparams)
	{
		// Get the current value
		if (array_key_exists($item->slug, $cache['params']))
		{
			$current = $cache['params'][ $item->slug ];
		}
		else
		{
			if (!is_object($userparams->params))
			{
				$current = $item->default;
			}
			else
			{
				$slug    = $item->slug;
				$current = property_exists($userparams->params, $item->slug) ? $userparams->params->$slug : $item->default;
			}
		}

		// Is this a required field?
		$required = $item->allow_empty ? '' : '* ';

		// Parse options
		if ($item->options)
		{
			$placeholder = htmlentities(str_replace("\n", '', $item->options), ENT_COMPAT, 'UTF-8');
		}
		else
		{
			$placeholder = '';
		}

		// Set up field's HTML content
		$html = '<textarea name="params[' . $item->slug . ']" id="' . $item->slug . '" placeholder="' . $placeholder . '">';
		$html .= $current;
		$html .= '</textarea>';

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

	/**
	 * Create the necessary Javascript for a textbox
	 *
	 * @param   CustomFields  $item  The item to render the Javascript for
	 *
	 * @return  string
	 */
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
		result.$slug = $('#$slug').val();
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

	/**
	 * Validate a text field
	 *
	 * @param CustomFields $item   The custom field to validate
	 * @param array                      $custom The custom fields' values array
	 *
	 * @return int 1 if the field is valid, 0 otherwise
	 */
	public function validate($item, $custom)
	{
		if (!isset($custom[ $item->slug ]) || !is_array($custom))
		{
			$custom[ $item->slug ] = '';
		}
		elseif (!array_key_exists($item->slug, $custom))
		{
			$custom[ $item->slug ] = '';
		}

		$valid = true;

		if (!$item->allow_empty)
		{
			$valid = !empty($custom[ $item->slug ]);
		}

		return $valid ? 1 : 0;
	}
}