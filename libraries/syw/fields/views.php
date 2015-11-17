<?php
/**
 * @copyright	Copyright (C) 2011 Simplify Your Web, Inc. All rights reserved.
 * @license		GNU General Public License version 3 or later; see LICENSE.txt
 */

defined('JPATH_PLATFORM') or die;

jimport('joomla.html.html.menu');
jimport('joomla.form.formfield');
jimport('joomla.filesystem.folder');

class JFormFieldViews extends JFormFieldList
{
	public $type = 'Views';	
	
	protected function getOptions()
	{
		$options = array();
			
		$option = $this->element['option'];
		$view = $this->element['view'];
	
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		
		$query->select('a.id AS value, a.title AS text, a.alias, a.level, a.menutype, a.type, a.template_style_id, a.checked_out');
		$query->from('#__menu AS a');
		$query->join('LEFT', $db->quoteName('#__menu') . ' AS b ON a.lft > b.lft AND a.rgt < b.rgt');
		$query->where('a.link like '.$db->quote('%option='.$option.'&view='.$view.'%'));
		$query->where('a.published = 1');
		
		if (JLanguageMultilang::isEnabled()) {
			$lang = JFactory::getLanguage();
			$query->where('a.language = '.$db->quote($lang->getTag()));
		}
		
		$db->setQuery($query);
		
		try {
			$options = $db->loadObjectList();
		} catch (RuntimeException $e) {
			return false;
		}		

		// Merge any additional options in the XML definition.
		$options = array_merge(parent::getOptions(), $options);

		return $options;
	}
	
}
