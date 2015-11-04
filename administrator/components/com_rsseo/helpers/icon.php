<?php
/**
* @version 1.0.0
* @package RSSEO! 1.0.0
* @copyright (C) 2009-2012 www.rsjoomla.com
* @license GPL, http://www.gnu.org/licenses/gpl-2.0.html
*/

defined('_JEXEC') or die('Restricted access');


class JHtmlIcon {
	
	public static function modified($value = 0, $id) {
		// Array of image, task, title, action
		if (rsseoHelper::isJ3()) {
			$states	= array(
				0	=> array('unpublish'),
				1	=> array('publish')
			);
		} else {
			$states	= array(
				0	=> array('publish_x.png'),
				1	=> array('tick.png')
			);
		}
		
		$state	= JArrayHelper::getValue($states, (int) $value, $states[1]);
		$icon	= $state[0];
		$html	= '<a href="javascript:void(0)" class="btn btn-micro active">';
		$html  .= rsseoHelper::isJ3() ? '<i id="img'. $id .'" class="icon-'. $icon.'"></i>' : '<img id="img'.$id.'" src="'.JURI::root().'administrator/components/com_rsseo/assets/images/icons/'. $icon .'" border="0" alt="" />';
		$html  .= '</a>';

		return $html;
	}
	
	public static function insitemap($value = 0, $i) {
		// Array of image, task, title, action
		if (rsseoHelper::isJ3()) {
			$states	= array(
				0	=> array('unpublish',	'pages.addsitemap',		'COM_RSSEO_PAGE_ADD_TO_SITEMAP'),
				1	=> array('publish',		'pages.removesitemap',	'COM_RSSEO_PAGE_REMOVE_FROM_SITEMAP')
			);
		} else {
			$states	= array(
				0	=> array('publish_x.png',	'pages.addsitemap',		'COM_RSSEO_PAGE_ADD_TO_SITEMAP'),
				1	=> array('tick.png',		'pages.removesitemap',	'COM_RSSEO_PAGE_REMOVE_FROM_SITEMAP')
			);
		}
		
		$state	= JArrayHelper::getValue($states, (int) $value, $states[1]);
		$icon	= $state[0];
		$html	= '<a href="#" onclick="return listItemTask(\'cb'.$i.'\',\''.$state[1].'\')" class="btn btn-micro ' . ($value == 1 ? 'active' : '') . '" title="'.JText::_($state[2]).(rsseoHelper::isJ3() ? '' : '::').'">';
		$html  .= rsseoHelper::isJ3() ? '<i class="icon-'. $icon.'"></i>' : '<img src="'.JURI::root().'administrator/components/com_rsseo/assets/images/icons/'. $icon .'" border="0" alt="" />';
		$html  .= '</a>';

		return $html;
	}
	
	public function filter($name, $options, $default, $selected, $class = null) {
		$html = array();
		
		$class = $class ? $class : 'inputbox';
		
		$html[] = '<select name="'.$name.'" id="'.$name.'" class="'.$class.'" onchange="this.form.submit()">';
		
		if ($default)
			$html[] = '<option value="">'.$default.'</option>';
		
		$html[] = JHtml::_('select.options', $options, 'value', 'text', $selected, true);
		$html[] = '</select>';
		
		return implode("\n",$html);
	}
}