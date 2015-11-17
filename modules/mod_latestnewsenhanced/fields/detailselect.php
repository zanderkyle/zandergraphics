<?php
/**
 * @copyright	Copyright (C) 2011 Simplify Your Web, Inc. All rights reserved.
 * @license		GNU General Public License version 3 or later; see LICENSE.txt
*/

// no direct access
defined( '_JEXEC' ) or die;

//jimport('syw.k2');

JFormHelper::loadFieldClass('list');

class JFormFieldDetailSelect extends JFormFieldList
{
	protected $type = 'DetailSelect';

	protected function getInput() {
		
		JHtml::_('stylesheet', 'syw/fonts.css', false, true); // to add icons
			
		$options = array();
		
		$options[] = JHTML::_('select.option', 'hits', JText::_('MOD_LATESTNEWSENHANCEDEXTENDED_VALUE_HITS'), 'value', 'text', $disable=false );
		$options[] = JHTML::_('select.option', 'rating', JText::_('MOD_LATESTNEWSENHANCEDEXTENDED_VALUE_RATING'), 'value', 'text', $disable=false );
		$options[] = JHTML::_('select.option', 'author', JText::_('MOD_LATESTNEWSENHANCEDEXTENDED_VALUE_AUTHOR'), 'value', 'text', $disable=false );
		$options[] = JHTML::_('select.option', 'date', JText::_('MOD_LATESTNEWSENHANCEDEXTENDED_VALUE_DATE'), 'value', 'text', $disable=false );
		$options[] = JHTML::_('select.option', 'time', JText::_('MOD_LATESTNEWSENHANCEDEXTENDED_VALUE_TIME'), 'value', 'text', $disable=false );
		$options[] = JHTML::_('select.option', 'category', JText::_('MOD_LATESTNEWSENHANCEDEXTENDED_VALUE_CATEGORY'), 'value', 'text', $disable=false );
		$options[] = JHTML::_('select.option', 'linkedcategory', JText::_('MOD_LATESTNEWSENHANCEDEXTENDED_VALUE_LINKEDCATEGORY'), 'value', 'text', $disable=false );
		$options[] = JHTML::_('select.option', 'tags', JText::_('MOD_LATESTNEWSENHANCEDEXTENDED_VALUE_TAGS'), 'value', 'text', $disable=true );
		$options[] = JHTML::_('select.option', 'selectedtags', JText::_('MOD_LATESTNEWSENHANCEDEXTENDED_VALUE_SELECTEDTAGS'), 'value', 'text', $disable=true );
		$options[] = JHTML::_('select.option', 'linkedtags', JText::_('MOD_LATESTNEWSENHANCEDEXTENDED_VALUE_LINKEDTAGS'), 'value', 'text', $disable=true );
		$options[] = JHTML::_('select.option', 'linkedselectedtags', JText::_('MOD_LATESTNEWSENHANCEDEXTENDED_VALUE_LINKEDSELECTEDTAGS'), 'value', 'text', $disable=true );
		$options[] = JHTML::_('select.option', 'keywords', JText::_('MOD_LATESTNEWSENHANCEDEXTENDED_VALUE_KEYWORDS'), 'value', 'text', $disable=false );
		$options[] = JHTML::_('select.option', 'linka', JText::_('MOD_LATESTNEWSENHANCEDEXTENDED_VALUE_LINKA'), 'value', 'text', $disable=true );
		$options[] = JHTML::_('select.option', 'linkb', JText::_('MOD_LATESTNEWSENHANCEDEXTENDED_VALUE_LINKB'), 'value', 'text', $disable=true );
		$options[] = JHTML::_('select.option', 'linkc', JText::_('MOD_LATESTNEWSENHANCEDEXTENDED_VALUE_LINKC'), 'value', 'text', $disable=true );
		$options[] = JHTML::_('select.option', 'links', JText::_('MOD_LATESTNEWSENHANCEDEXTENDED_VALUE_LINKS'), 'value', 'text', $disable=true );
		$options[] = JHTML::_('select.option', 'linksnl', JText::_('MOD_LATESTNEWSENHANCEDEXTENDED_VALUE_LINKSNEWLINE'), 'value', 'text', $disable=true );
		$options[] = JHTML::_('select.option', 'share', JText::_('MOD_LATESTNEWSENHANCEDEXTENDED_VALUE_SHAREICONS'), 'value', 'text', $disable=true );
		
		//if (SYWK2::exists()) {
			//$options[] = JHTML::_('select.option', 'k2_user', JText::_('MOD_LATESTNEWSENHANCEDEXTENDED_VALUE_K2USER'), 'value', 'text', $disable=false );
		//}
		
		if (file_exists(JPATH_ROOT . '/components/com_jcomments/jcomments.php')) {
			$options[] = JHTML::_('select.option', 'jcommentscount', JText::_('MOD_LATESTNEWSENHANCEDEXTENDED_VALUE_JCOMMENTSCOUNT'), 'value', 'text', $disable=true );
		}
		
		// Merge any additional options in the XML definition.
		$options = array_merge(parent::getOptions(), $options);
		
		$attributes = 'class="inputbox"';

		return JHTML::_('select.genericlist', $options, $this->name, $attributes, 'value', 'text', $this->value, $this->id);
	}
}
?>