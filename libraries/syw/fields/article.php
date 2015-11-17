<?php
/**
* @copyright	Copyright (C) 2011 Simplify Your Web, Inc. All rights reserved.
* @license		GNU General Public License version 3 or later; see LICENSE.txt
*/

// no direct access
defined( '_JEXEC' ) or die;

jimport('joomla.html.html');
jimport('joomla.form.formfield');

/**
 * Supports an HTML select list of articles
 * @since  1.6
 */
class JFormFieldArticle extends JFormField
{
	public $type = 'Article';

	protected function getInput() { 
		
		$lang = JFactory::getLanguage();
		$lang->load('lib_syw.sys', JPATH_SITE);
		
		$session = JFactory::getSession();
		$options = array();
  
		$attr = '';

		// Initialize some field attributes.
		$attr .= $this->element['class'] ? ' class="'.(string) $this->element['class'].'"' : '';

		// To avoid user's confusion, readonly="true" should imply disabled="true".
		if ( (string) $this->element['readonly'] == 'true' || (string) $this->element['disabled'] == 'true') {
			$attr .= ' disabled="disabled"';
		}

		$attr .= $this->element['size'] ? ' size="'.(int) $this->element['size'].'"' : '';
		$attr .= $this->multiple ? ' multiple="multiple"' : '';

		// Initialize JavaScript field attributes.
		$attr .= $this->element['onchange'] ? ' onchange="'.(string) $this->element['onchange'].'"' : '';

		// now get to the business of finding the articles
	
		$db = JFactory::getDBO();
		$query = 'SELECT * FROM #__categories WHERE published=1 ORDER BY parent_id';
		$db->setQuery( $query );
		$categories = $db->loadObjectList();
  
		$articles = array();
  
		// set up first element of the array as all articles
		//$articles[0]->id = '';
		//$articles[0]->title = JText::_("ALLARTICLES");
		$articles[0] = array('id' => '', 'title' => JText::_("ALLARTICLES"));
  
		// loop through categories 
		foreach ($categories as $category) {
			$optgroup = JHTML::_('select.optgroup',$category->title,'id','title');
			$query = 'SELECT id,title FROM #__content WHERE catid='.$category->id;
			$db->setQuery( $query );
			$results = $db->loadObjectList();
 			if(count($results)>0) {
				array_push($articles,$optgroup);
				foreach ($results as $result) {
					array_push($articles,$result);
				}
			}
		}   
		
		$article = JTable::getInstance('content');
		if ($this->value) {
			$article->load($this->value);
		} else {
			$article->title = JText::_('LIB_SYW_ARTICLE_SELECTARTICLE');
		}
		
		$link	= 'index.php?option=com_content&amp;view=articles&amp;layout=modal&amp;tmpl=component&amp;function=jSelectArticle_'.$this->id;
  
		// Output
		$js = "
		function jSelectArticle(id, title, object) {
			document.getElementById(object + '_id').value = id;
            document.getElementById(object + '_name').value = title;
            document.getElementById('sbox-window').close();
		}";	

		// Build the script.
		$script = array();
		$script[] = '	function jSelectArticle_'.$this->id.'(id, title, catid, object) {';
		$script[] = '		document.id("'.$this->id.'_id").value = id;';
		$script[] = '		document.id("'.$this->id.'_name").value = title;';
		$script[] = '		SqueezeBox.close();';
		$script[] = '	}';

		// Add the script to the document head.
		JFactory::getDocument()->addScriptDeclaration(implode("\n", $script));
		
		JHTML::_('behavior.modal', 'a.modal');
		
		$version = new JVersion();
		$jversion = explode('.', $version->getShortVersion());
		if (intval($jversion[0]) > 2) {
			$html = '<div class="input-append">';
			$html .= '    <input class="input-small" type="text" id="'.$this->id.'_name" value="'.htmlspecialchars($article->title, ENT_QUOTES, 'UTF-8').'" disabled="disabled" />';
			$html .= '    <a class="modal btn" title="'.JText::_('LIB_SYW_ARTICLE_SELECTARTICLE').'"  href="'.$link.'" rel="{handler: \'iframe\', size: {x: 800, y: 500}}">';
			$html .= '    <i class="icon-file hasTooltip"></i> '.JText::_('JLIB_FORM_BUTTON_SELECT').'</a>';
			$html .= '    <a class="btn hasTooltip" title="' . JText::_('JLIB_FORM_BUTTON_CLEAR') . '"' . ' href="#" onclick="';
			$html .= '        document.id(\'' . $this->id . '_name\').value=\'\';';
			$html .= '        document.id(\'' . $this->id . '_id\').value=\'\';';
			//$html .= '        document.id(\'' . $this->id . '\').fireEvent(\'change\');';
			$html .= '        return false;';
			$html .= '    ">';
			$html .= '    <i class="icon-remove"></i></a>';
			$html .= '</div>';
		} else {
			$html = '<div class="fltlft">';
			$html .= '    <input type="text" id="'.$this->id.'_name" value="'.htmlspecialchars($article->title, ENT_QUOTES, 'UTF-8').'" disabled="disabled" />';
			$html .= '</div>';
			$html .= '<div class="button2-left">';
			$html .= '    <div class="blank">';
			$html .= '        <a class="modal" title="'.JText::_('LIB_SYW_ARTICLE_SELECTARTICLE').'" href="'.$link.'" rel="{handler: \'iframe\', size: {x: 800, y: 500}}">'.JText::_('JLIB_FORM_BUTTON_SELECT').'</a>';
			$html .= '    </div>';
			$html .= '</div>';
			$html .= '<div class="button2-left">';
			$html .= '    <div class="blank">';
			$html .= '        <a title="' . JText::_('JLIB_FORM_BUTTON_CLEAR') . '"' . ' href="#" onclick="';
			$html .= 'document.id(\'' . $this->id . '_name\').value=\'\';';
			$html .= 'document.id(\'' . $this->id . '_id\').value=\'\';';
			//$html .= 'document.id(\'' . $this->id . '\').fireEvent(\'change\');';
			$html .= 'return false;';
			$html .= '">';
			$html .= JText::_('JLIB_FORM_BUTTON_CLEAR') . '</a>';
			$html .= '    </div>';
			$html .= '</div>';
		}
		
		$html .= '<input type="hidden" id="'.$this->id.'_id" name="'.$this->name.'" value="'.(int)$this->value.'" />';

		return $html;  
	}
}