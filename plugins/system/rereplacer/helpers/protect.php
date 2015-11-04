<?php
/**
 * Plugin Helper File: Protect
 *
 * @package         ReReplacer
 * @version         6.1.1
 *
 * @author          Peter van Westen <peter@nonumber.nl>
 * @link            http://www.nonumber.nl
 * @copyright       Copyright © 2015 NoNumber All Rights Reserved
 * @license         http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

defined('_JEXEC') or die;

require_once JPATH_PLUGINS . '/system/nnframework/helpers/protect.php';

class PlgSystemReReplacerHelperProtect
{
	var $protect_start = '<!-- START: RR_PROTECT -->';
	var $protect_end = '<!-- END: RR_PROTECT -->';

	public function protect($string, $protect = 1)
	{
		return $protect
			? $this->protect_start . $string . $this->protect_end
			: $this->protect_end . $string . $this->protect_start;
	}

	public function stringToProtectedArray($string, &$item, $onlyform = 0)
	{
		$string_array = array($string);

		if (!($item->enable_in_edit_forms && !NNProtect::isAdmin())
			&& NNProtect::isEditPage()
		)
		{
			// Protect complete adminForm (to prevent ReReplacer messing stuff up when editing articles and such)
			$search = NNProtect::getFormRegex();
			$search = '(' . $search . '.*?</form>)';
			$this->protectArrayByRegex($string_array, $search, '', 1);
		}

		if ($onlyform)
		{
			return $string_array;
		}


		// Protect everything between the {noreplace} tags
		$search = '(\{noreplace\}.*?\{/noreplace\})';
		// Protect search result
		$this->protectArrayByRegex($string_array, $search, '', 1);


		return $string_array;
	}

	private function protectArrayByRegex(&$array, $search = '', $replace = '', $protect = 1, $convert = 1)
	{
		$search = '#' . $search . '#si';
		if (!$replace)
		{
			$replace = '\1';
		}

		$is_array = is_array($array);
		if (!$is_array)
		{
			$array = array($array);
		}

		foreach ($array as $key => &$string)
		{
			// only do something if string is not empty
			// or on uneven count = not yet protected
			if (trim($string) == '' || fmod($key, 2))
			{
				continue;
			}

			$this->protectStringByRegex($string, $search, $replace, $protect);
		}

		if (!$is_array)
		{
			$array = $array['0'];
		}

		if ($convert)
		{
			$array = $this->protectArray($array);
		}
	}

	private function protectStringByRegex(&$string, $search = '', $replace = '', $protect = 1)
	{
		if (@preg_match($search . 'u', $string))
		{
			$search .= 'u';
		}

		if (preg_match($search, $string))
		{
			$string = $protect
				? preg_replace($search, $this->protect($replace), $string)
				: $this->protect(preg_replace($search, $this->protect($replace, 0), $string));
		}

		$this->cleanProtected($string);
	}

	public function cleanProtect(&$string)
	{
		$string = str_replace(array($this->protect_start, $this->protect_end), '', $string);
	}

	private function cleanProtected(&$string)
	{
		while (strpos($string, $this->protect_start . $this->protect_start) !== false)
		{
			$string = str_replace($this->protect_start . $this->protect_start, $this->protect_start, $string);
		}
		while (strpos($string, $this->protect_end . $this->protect_end) !== false)
		{
			$string = str_replace($this->protect_end . $this->protect_end, $this->protect_end, $string);
		}
		while (strpos($string, $this->protect_end . $this->protect_start) !== false)
		{
			$string = str_replace($this->protect_end . $this->protect_start, '', $string);
		}
	}

	private function protectArray($array)
	{
		$new_array = array();

		foreach ($array as $key => $string)
		{
			// is string already protected?
			$protect = fmod($key, 2);
			$item_array = $this->protectStringToArray($string, $protect);

			$new_array = array_merge($new_array, $item_array);
		}

		return $new_array;
	}

	private function protectStringToArray($string, $protected = 0)
	{
		if ($protected)
		{
			// If already protected, just clean string and place in an array
			$this->cleanProtect($string);

			return array($string);
		}

		// Return an array with 1 or 3 items.
		// 1) first part to protector start (if found) (= unprotected)
		// 2) part between the first protector start and its matching end (= protected)
		// 3) Rest of the string (= unprotected)

		$array = array();
		// Devide sting on protector start
		$string_array = explode($this->protect_start, $string);
		// Add first element to the string ( = even = unprotected)
		$this->cleanProtect($string_array['0']);
		$array[] = $string_array['0'];

		$count = count($string_array);
		if ($count < 2)
		{
			return $array;
		}

		for ($i = 1; $i < $count; $i++)
		{
			$substr = $string_array[$i];
			$protect_count = 1;

			// Add the next string if not enough protector ends are found
			while (
				substr_count($substr, $this->protect_end) < $protect_count
				&& $i < ($count - 1)
			)
			{
				$protect_count++;
				$substr .= $string_array[++$i];
			}

			// Devide sting on protector end
			$substr_array = explode($this->protect_end, $substr);

			$protect_part = '';
			// Add as many parts to the string as there are protector starts
			for ($protect_i = 0; $protect_i < $protect_count; $protect_i++)
			{
				$protect_part .= array_shift($substr_array);
				if (!count($substr_array))
				{
					break;
				}
			}

			// This part is protected (uneven)
			$this->cleanProtect($protect_part);
			$array[] = $protect_part;

			// The rest of the string is unprotected (even)
			$unprotect_part = implode('', $substr_array);
			$this->cleanProtect($unprotect_part);
			$array[] = $unprotect_part;
		}

		return $array;
	}

}
