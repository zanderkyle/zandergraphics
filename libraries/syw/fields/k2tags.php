<?php
/**
 * @copyright	Copyright (C) 2011 Simplify Your Web, Inc. All rights reserved.
 * @license		GNU General Public License version 3 or later; see LICENSE.txt
 */

defined('JPATH_PLATFORM') or die;

jimport('joomla.html.html.menu');
jimport('joomla.form.formfield');
jimport('syw.k2');

class JFormFieldK2Tags extends JFormFieldList
{
	public $type = 'K2Tags';
	
	/* hide category selection if no k2 */
	public function getLabel()
	{
		if (SYWK2::exists()) {
			return parent::getLabel();
		}
	
		return '<div style="clear: both;"></div>';
	}
	
	protected function getInput() 
	{
		$html = '';
		
		if (SYWK2::exists()) {
			return parent::getInput();
		} else {
			$lang = JFactory::getLanguage();
			$lang->load('lib_syw.sys', JPATH_SITE);
			
			$version = new JVersion();
			$jversion = explode('.', $version->getShortVersion());
			
			if (intval($jversion[0]) > 2) {
				$html .= '<div class="alert alert-error">';
			} else {
				$html .= '<div style="clear: both; margin: 5px 0; padding: 8px 35px 8px 14px; border-radius: 4px; border: 1px solid #EED3D7; background-color: #F2DEDE; color: #B94A48;">';
			}
			$html .= '<span>';
			$html .= JText::_('LIB_SYW_K2TAGS_MISSING');
			$html .= '</span>';
			$html .= '</div>';
		}
		
		return $html;
	}
	
	protected function getOptions()
	{
		$options = array();
		
		if (SYWK2::exists()) {
		
			$db = JFactory::getDBO();
			$query = $db->getQuery(true);
			
			$query->select('a.*');
			$query->from('#__k2_tags a');
 			$query->where('a.published = 1');
			
			$db->setQuery($query);
			
			try {
				$items = $db->loadObjectList();
			} catch (RuntimeException $e) {
				return false;
			}	

			foreach ($items as $item) {
				$options[] = JHTML::_('select.option', $item->id, $item->name);
			}
		}

		// Merge any additional options in the XML definition.
		$options = array_merge(parent::getOptions(), $options);

		return $options;
	}
	
}
