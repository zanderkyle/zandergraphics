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

abstract class JHtmlCreativeForm
{
	/**
	 * @param	int $value	The featured value
	 * @param	int $i
	 * @param	bool $canChange Whether the value can be changed or not
	 *
	 * @return	string	The anchor tag to toggle featured/unfeatured contacts.
	 * @since	1.6
	 */
	static function featured($value = 0, $i, $canChange = true)
	{
		// Array of image, task, title, action
		$states	= array(
			0	=> array('disabled.png', 'creativeforms.featured', 'COM_CREATIVECONTACTFORM_UNFEATURED', 'COM_CREATIVECONTACTFORM_UNFEATURED'),
			1	=> array('featured.png', 'creativeforms.unfeatured', 'COM_CREATIVECONTACTFORM_FEATURED', 'COM_CREATIVECONTACTFORM_FEATURED'),
		);
		$state	= JArrayHelper::getValue($states, (int) $value, $states[1]);
		$html	= JHtml::_('image', 'admin/'.$state[0], JText::_($state[2]), NULL, true);
		if ($canChange) {
			$html	= '<a href="#" onclick="return listItemTask(\'cb'.$i.'\',\''.$state[1].'\')" title="'.JText::_($state[3]).'">'
					. $html .'</a>';
		}

		return $html;
	}
}
