<?php
/**
 * @package   AkeebaTicketSystem
 * @copyright Copyright (c)2011-2015 Nicholas K. Dionysopoulos
 * @license   GNU General Public License version 3, or later
 */

namespace Akeeba\TicketSystem\Admin\Form\Field;

use FOF30\Form\Field\Integer;
use JHtml;
use JText;

defined('_JEXEC') or die();

class DateInteger extends Integer
{
	/**
	 * Method to get the field options.
	 *
	 * @return  array  The field option objects.
	 *
	 * @since   11.1
	 */
	protected function getOptions()
	{
		// Initialize variables.
		$options = array();
		$options[] = JHtml::_('select.option', '*');

		// Initialize some field attributes.
		$first = (int) $this->element['first'];
		$last = (int) $this->element['last'];
		$step = (int) $this->element['step'];

		if ($this->element['year'])
		{
			if (($first < 2010) && ($last < 2010))
			{
				$first = gmdate('Y') + $first;
				$last = gmdate('Y') + $last;
			}
		}

		// Sanity checks.
		if ($step == 0)
		{
			// Step of 0 will create an endless loop.
			return $options;
		}
		elseif ($first < $last && $step < 0)
		{
			// A negative step will never reach the last number.
			return $options;
		}
		elseif ($first > $last && $step > 0)
		{
			// A position step will never reach the last number.
			return $options;
		}

		// Build the options array.
		for ($i = $first; $i <= $last; $i += $step)
		{
			if ($this->element['month'])
			{
				$options[] = JHtml::_('select.option', $i, JText::_($this->monthName($i)));
			}
			else
			{
				$options[] = JHtml::_('select.option', $i);
			}
		}

		return $options;
	}

	private function monthName($i)
	{
		switch($i)
		{
			case 1:
				return 'JANUARY';
				break;
			case 2:
				return 'FEBRUARY';
				break;
			case 3:
				return 'MARCH';
				break;
			case 4:
				return 'APRIL';
				break;
			case 5:
				return 'MAY';
				break;
			case 6:
				return 'JUNE';
				break;
			case 7:
				return 'JULY';
				break;
			case 8:
				return 'AUGUST';
				break;
			case 9:
				return 'SEPTEMBER';
				break;
			case 10:
				return 'OCTOBER';
				break;
			case 11:
				return 'NOVEMBER';
				break;
			case 12:
				return 'DECEMBER';
				break;
		}
	}
}
