<?php
/**
 * @copyright	Copyright (C) 2011 Simplify Your Web, Inc. All rights reserved.
 * @license		GNU General Public License version 3 or later; see LICENSE.txt
*/

// no direct access
defined( '_JEXEC' ) or die;

jimport('syw.k2');

JFormHelper::loadFieldClass('list');

class JFormFieldDatasourceSelect extends JFormFieldList
{
	protected $type = 'DatasourceSelect';

	protected function getInput() {
			
		$options = array();
		
		if (SYWK2::exists()) {
			$options[] = JHTML::_('select.option', 'k2', JText::_('MOD_LATESTNEWSENHANCEDEXTENDED_VALUE_K2ITEMS'), 'value', 'text', $disable=false );
		} else {
			$options[] = JHTML::_('select.option', 'k2', JText::_('MOD_LATESTNEWSENHANCEDEXTENDED_VALUE_K2ITEMS'), 'value', 'text', $disable=true );
		}
		
		// Merge any additional options in the XML definition.
		$options = array_merge(parent::getOptions(), $options);
		
		$attributes = 'class="inputbox"';

		return JHTML::_('select.genericlist', $options, $this->name, $attributes, 'value', 'text', $this->value, $this->id);
	}
}
?>