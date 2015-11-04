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

class CreativeContactFormViewCreativefield extends JViewLegacy
{
	protected $form;
	protected $item;
	protected $state;

	/**
	 * Display the view
	 */
	public function display($tpl = null)
	{
		// Initialiase variables.
		$this->form		= $this->get('Form');
		$this->item		= $this->get('Item');
		$this->state	= $this->get('State');
		$max_id	= $this->get('max_id');
		$this->assignRef( 'max_id', $max_id );
		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}

		$this->addToolbar($max_id);
		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @since	1.6
	 */
	protected function addToolbar($max_id)
	{
		JRequest::setVar('hidemainmenu', true);

		$user		= JFactory::getUser();
		$userId		= $user->get('id');
		$isNew		= ($this->item->id == 0);
		$checkedOut	= !($this->item->checked_out == 0 || $this->item->checked_out == $userId);
		// Since we don't track these assets at the item level, use the category id.

		$text = $isNew ? JText::_( 'New' ) : JText::_( 'Edit' );
		JToolBarHelper::title(   JText::_( 'Creative Field' ).': <small><small>[ ' . $text.' ]</small></small>','manage.png' );
	
		// Build the actions for new and existing records.
		if($isNew) {
			if($max_id < 5)
				JToolBarHelper::apply('creativefield.apply');
		}
		else {
			JToolBarHelper::apply('creativefield.apply');
			JToolBarHelper::save('creativefield.save');
			JToolBarHelper::save2new('creativefield.save2new');
			JToolBarHelper::save2copy('creativefield.save2copy');
		}
		JToolBarHelper::cancel('creativefield.cancel','close');
	}
}