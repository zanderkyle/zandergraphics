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

jimport('joomla.application.component.modellist');

class CreativeContactFormModelCreativeFields extends JModelList {
	
	/**
	 * Constructor.
	 *
	 * @param	array	An optional associative array of configuration settings.
	 * @see		JController
	 * @since	1.6
	 */
	public function __construct($config = array())
	{
		if (empty($config['filter_fields'])) {
			$config['filter_fields'] = array(
					'id', 'sp.id',
					'name', 'sp.name',
					'id_template', 'sp.template_id',
					'form', 'sf.form',
					'type', 'type',
					'published', 'sp.published',
					'ordering', 'sp.ordering',
					'publish_up', 'sp.publish_up',
					'publish_down', 'sp.publish_down'
			);
		}
	
		parent::__construct($config);
	}
	
	/**
	 * Method to get category options
	 *
	 */
	public function getCreativeForms() {
		$db		= $this->getDbo();
		$sql = "SELECT `id`, `name`, `id_template` FROM `#__creative_forms` WHERE `published` <> '-2' order by `id` ";
		$db->setQuery($sql);
		return $opts = $db->loadObjectList();
	}
	
	/**
	 * Method to get category options
	 *
	 */
	public function getCreativeTypes() {
		$db		= $this->getDbo();
		$sql = "SELECT `id`, `name` FROM `#__creative_field_types`";
		$db->setQuery($sql);
		return $opts = $db->loadObjectList();
	}
	
	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @return	void
	 * @since	1.6
	 */
	protected function populateState($ordering = null, $direction = null)
	{
		// Initialise variables.
		$app = JFactory::getApplication();
		$db	= $this->getDbo();
	
		// Adjust the context to support modal layouts.
		if ($layout = JRequest::getVar('layout')) {
			$this->context .= '.'.$layout;
		}

		// set limit to all
		$_REQUEST['limit'] = 0;
		$_REQUEST['filter_order'] = 'sp.ordering';
		$_REQUEST['filter_order'] = 'sp.ordering';
		$_REQUEST['filter_order_Dir'] = 'asc';
		$_REQUEST['directionTable'] = 'asc';
	
		$search = $this->getUserStateFromRequest($this->context.'.filter.search', 'filter_search');
		$this->setState('filter.search', $search);
	
		$published = $this->getUserStateFromRequest($this->context.'.filter.published', 'filter_published', '');
		$this->setState('filter.published', $published);
	
		//check filter form, if emprty, set it!
		$form_id = isset($_REQUEST['filter_form_id']) ? (int)$_REQUEST['filter_form_id'] : 0;
		if($form_id == 0) {
			$query = "SELECT min(`id`) FROM #__creative_forms";
			$db->setQuery($query);
			$min_form_id = $db->loadResult();

			$_REQUEST['filter_form_id'] = $min_form_id;
		}
		$formId = $this->getUserStateFromRequest($this->context.'.filter.form_id', 'filter_form_id');
		$this->setState('filter.form_id', $formId);
		
		$typeId = $this->getUserStateFromRequest($this->context.'.filter.type_id', 'filter_type_id');
		$this->setState('filter.type_id', $typeId);
		
		$language = $this->getUserStateFromRequest($this->context.'.filter.language', 'filter_language', '');
		$this->setState('filter.language', $language);
	
		// List state information.
		parent::populateState('sp.ordering', 'asc');
	}
	
	/**
	 * Method to get a store id based on model configuration state.
	 *
	 * This is necessary because the model is used by the component and
	 * different modules that might need different sets of data or different
	 * ordering requirements.
	 *
	 * @param	string		$id	A prefix for the store id.
	 *
	 * @return	string		A store id.
	 * @since	1.6
	 */
	protected function getStoreId($id = '')
	{
		// Compile the store id.
		$id	.= ':'.$this->getState('filter.search');
		$id	.= ':'.$this->getState('filter.published');
		$id	.= ':'.$this->getState('filter.form_id');
		$id	.= ':'.$this->getState('filter.language');
	
		return parent::getStoreId($id);
	}
	
	/**
	 * Build an SQL query to load the list data.
	 *
	 * @return	JDatabaseQuery
	 * @since	1.6
	 */
	protected function getListQuery()
	{
		// Create a new query object.
		$db		= $this->getDbo();
		$query	= $db->getQuery(true);
		$user	= JFactory::getUser();
	
		// Select the required fields from the table.
		$query->select(
				$this->getState(
						'list.select',
						'sp.id, sp.name'.
						', sp.published, sp.created, sp.ordering, sp.column_type'.
						', sp.publish_up, sp.publish_down'
				)
		);
		$query->from('#__creative_fields AS sp');
		
		// get only published polls fields
		$query->join('INNER', '#__creative_forms AS sp1 ON sp1.id=sp.id_form AND sp1.published <> -2');
		
		// Join over the forms.
		$query->select('sf.name AS form, sf.id AS form_id, sf.id_template as id_template');
		$query->join('LEFT', '#__creative_forms AS sf ON sf.id=sp.id_form');
		
		// Join over the types.
		$query->select('st.name AS type, st.id AS type_id');
		$query->join('LEFT', '#__creative_field_types AS st ON st.id=sp.id_type');
	
		// Join over the language
		$query->select('l.title AS language_title');
		$query->join('LEFT', $db->quoteName('#__languages').' AS l ON l.lang_code = sp.language');
	
		// Join over the users for the checked out user.
		$query->select('uc.name AS editor');
		$query->join('LEFT', '#__users AS uc ON uc.id=sp.checked_out');
	
		// Join over the asset groups.
		$query->select('ag.title AS access_level');
		$query->join('LEFT', '#__viewlevels AS ag ON ag.id = sp.access');
	
		// Filter by access level.
		if ($access = $this->getState('filter.access')) {
			$query->where('sp.access = ' . (int) $access);
		}
		
		// Filter by  forms
		$formId = $this->getState('filter.form_id');
		if (is_numeric($formId)) {
			$query->where('sp.id_form = '.(int) $formId);
		}
		elseif (is_array($formId)) {
			JArrayHelper::toInteger($formId);
			$formId = implode(',', $formId);
			$query->where('sp.id_form IN ('.$formId.')');
		}
	
		// Filter by types
		$typeId = $this->getState('filter.type_id');
		if (is_numeric($typeId)) {
			$query->where('sp.id_type = '.(int) $typeId);
		}
		elseif (is_array($typeId)) {
			JArrayHelper::toInteger($typeId);
			$typeId = implode(',', $typeId);
			$query->where('sp.id_type IN ('.$typeId.')');
		}
	
		// Filter by published state
		$published = $this->getState('filter.published');
		if (is_numeric($published)) {
			$query->where('sp.published = ' . (int) $published);
		}
		elseif ($published === '') {
			$query->where('(sp.published = 0 OR sp.published = 1)');
		}
	
		// Filter by search in name.
		$search = $this->getState('filter.search');
		if (!empty($search)) {
			if (stripos($search, 'id:') === 0) {
				$query->where('sp.id = '.(int) substr($search, 3));
			}
			else {
				$search = $db->Quote('%'.$db->escape($search, true).'%');
				$query->where('(sp.name LIKE '.$search.')');
			}
		}
	
		// Filter on the language.
		if ($language = $this->getState('filter.language')) {
			$query->where('sp.language = '.$db->quote($language));
		}
	
		// Add the list ordering clause.
		$orderCol	= $this->state->get('list.ordering', 'sp.id');
		$orderDirn	= $this->state->get('list.direction', 'asc');
		/*
		if ($orderCol == 'a.ordering' || $orderCol == 'category_title') {
			$orderCol = 'c.title '.$orderDirn.', a.ordering';
		}
		*/
		$query->order($db->escape($orderCol.' '.$orderDirn));
		//$query->group('sp.id');
	
		//echo nl2br(str_replace('#__','jos_',$query));
		return $query;
	}
}