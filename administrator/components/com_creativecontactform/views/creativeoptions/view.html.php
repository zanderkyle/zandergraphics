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

// Import Joomla! libraries
jimport( 'joomla.application.component.view');

class CreativeContactFormViewCreativeoptions extends JViewLegacy {
	
	protected $items;
	protected $pagination;
	protected $state;
	
	/**
	 * Display the view
	 *
	 * @return	void
	 */
    public function display($tpl = null) {
    	
    	$this->items		= $this->get('Items');
    	$this->pagination	= $this->get('Pagination');
    	$this->state		= $this->get('State');
    	$forms	= $this->get('creativeForms');
    	$fields	= $this->get('creativeFields');
 
    	//get form options
    	$options        = array();
    	foreach($forms AS $form) {
    		$options[]      = JHtml::_('select.option', $form->id, $form->name);
    	}
    	//get type options
    	$field_options = array();
    	foreach($fields AS $field) {
    		$field_options[]      = JHtml::_('select.option', $field->id, $field->name);
    	}
    	if(JV == 'j2') {
    		$this->assignRef( 'form_options', $options );
    		$this->assignRef( 'field_options', $field_options );
    	}
    	else {
    		JHtmlSidebar::addFilter(
    				JText::_('JOPTION_SELECT_PUBLISHED'),
    				'filter_published',
    				JHtml::_('select.options', JHtml::_('jgrid.publishedOptions'), 'value', 'text', $this->state->get('filter.published'), true)
    		);
    		 
    		JHtmlSidebar::addFilter(
    				JText::_('COM_CREATIVECONTACTFORM_SELECT_FORM'),
    				'filter_form_id',
    				JHtml::_('select.options', $options, 'value', 'text', $this->state->get('filter.form_id'))
    		);
    		 
    		JHtmlSidebar::addFilter(
    				JText::_('COM_CREATIVECONTACTFORM_SELECT_FIELD'),
    				'filter_field_id',
    				JHtml::_('select.options', $field_options, 'value', 'text', $this->state->get('filter.field_id'))
    		);
    	}
       	$this->addToolbar();
       	if(JV == 'j3')
       		$this->sidebar = JHtmlSidebar::render();
		parent::display($tpl);
    }
	    
    /**
     * Add the page title and toolbar.
     *
     * @since	1.6
     */
    protected function addToolbar()
    {
    	JToolBarHelper::addNew('creativeoption.add');
    	JToolBarHelper::editList('creativeoption.edit');
	    	
		JToolBarHelper::divider();
 		JToolBarHelper::publish('creativeoptions.publish', 'JTOOLBAR_PUBLISH', true);
		JToolBarHelper::unpublish('creativeoptions.unpublish', 'JTOOLBAR_UNPUBLISH', true);
	    
    	if ($this->state->get('filter.published') == -2) {
    		JToolBarHelper::deleteList('', 'creativeoptions.delete', 'JTOOLBAR_EMPTY_TRASH');
    		JToolBarHelper::divider();
    	}
    	else {
    		JToolBarHelper::trash('creativeoptions.trash');
    		JToolBarHelper::divider();
    	}
	    
    }
    
    /**
     * Returns an array of fields the table can be sorted by
     *
     * @return  array  Array containing the field name to sort by as the key and display text as value
     *
     * @since   3.0
     */
    protected function getSortFields()
    {
    	return array(
    			'sp.ordering' => JText::_('JGRID_HEADING_ORDERING'),
    			'sp.name' => JText::_('COM_CREATIVECONTACTFORM_NAME'),
    			'form' => JText::_('COM_CREATIVECONTACTFORM_FORM'),
    			'sp.published' => JText::_('JSTATUS'),
    			'field' => JText::_('COM_CREATIVECONTACTFORM_TYPE'),
    			'sp.id' => JText::_('JGRID_HEADING_ID')
    	);
    }
}