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


class CreativeContactFormViewCreativeforms extends JViewLegacy {
	
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
 
       	if(JV == 'j2') {
       	}
       	else {
       		JHtmlSidebar::addFilter(
       				JText::_('JOPTION_SELECT_PUBLISHED'),
       				'filter_published',
       				JHtml::_('select.options', JHtml::_('jgrid.publishedOptions'), 'value', 'text', $this->state->get('filter.published'), true)
       		);
       		
       		JHtmlSidebar::addFilter(
       				JText::_('JOPTION_SELECT_ACCESS'),
       				'filter_access',
       				JHtml::_('select.options', JHtml::_('access.assetgroups'), 'value', 'text', $this->state->get('filter.access'))
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
		JToolBarHelper::addNew('creativeform.add');
		JToolBarHelper::editList('creativeform.edit');
		    	
		JToolBarHelper::divider();
		JToolBarHelper::publish('creativeforms.publish', 'JTOOLBAR_PUBLISH', true);
		JToolBarHelper::unpublish('creativeforms.unpublish', 'JTOOLBAR_UNPUBLISH', true);
		JToolBarHelper::divider();
		JToolBarHelper::deleteList('', 'creativeforms.delete', 'JTOOLBAR_DELETE');
	    
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
				'sp.published' => JText::_('JSTATUS'),
				'template_title' => JText::_('COM_CREATIVECONTACTFORM_TEMPLATE'),
				'num_fields' => JText::_('COM_CREATIVECONTACTFORM_NUM_FIELDS'),
				'sp.id' => JText::_('JGRID_HEADING_ID')
		);
	}
}