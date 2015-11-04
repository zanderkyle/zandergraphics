<?php
/**
 * @package ats
 * @copyright Copyright (c)2011-2014 Nicholas K. Dionysopoulos / AkeebaBackup.com
 * @license GNU GPL v3 or later
 */

// No direct access
defined('_JEXEC') or die;

class JFormFieldDicatsel extends JFormField
{
	public function getInput()
	{
		JLoader::import('joomla.filesystem.folder');

		$canFetch = JFolder::exists(JPATH_ADMINISTRATOR.'/components/com_docimport');

		$options	= array();
		$options[]	= JHTML::_('select.option','0',JText::_( 'COM_ATS_CATEGORY_DICATS_MSG_ALL' ));

		if($canFetch)
		{
			$db	   = JFactory::getDBO();
			$query = $db->getQuery(true)
						->select(array(
							$db->qn('docimport_category_id'),
							$db->qn('title'),
						))
						->from($db->qn('#__docimport_categories'))
						->where($db->qn('enabled').' = '.$db->q(1));

            try
            {
                $results = $db->setQuery($query)->loadObjectList();
            }
            catch(Exception $e)
            {
                $results = array();
            }

			if(!empty($results))
			{
				foreach($results as $item)
				{
					$options[] = JHTML::_('select.option',$item->docimport_category_id,$item->title );
				}
			}
		}

		return JHTML::_('select.genericlist', $options, $this->name.'[]', 'class="inputbox" multiple="multiple" size="5"', 'value', 'text', $this->value, $this->id);
	}
}