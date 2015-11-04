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

class JFormFieldForm extends JFormField
{

	protected $type 		= 'creativecontactform';

	function getInput()
	{
		$doc 		= JFactory::getDocument();
		$fieldName	= $this->name;

		$db = JFactory::getDBO();

		$query = "SELECT name text,id value FROM #__creative_forms WHERE published = '1'";
		$db->setQuery($query);
		$options = $db->loadObjectList();

		$html = array();

		$html[] = "<select name=\"$fieldName\">";
		//$html[] = '<option value="0">'.JText::_("All").'</option>';
		foreach($options AS $o) {
			$html[] = '<option value="'.$o->value.'"'.(($o->value == $this->value) ? ' selected="selected"' : '').'>';
			$html[] = $o->text;
			$html[] = '</option>';
		}
		$html[] = "</select>";

		return implode("", $html);
	}
}
?>
