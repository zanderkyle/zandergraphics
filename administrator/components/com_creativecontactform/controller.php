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

jimport( 'joomla.application.component.controller' );
require_once( JPATH_COMPONENT.DS.'helpers'.DS.'helper.php' );

class CreativecontactformController extends JControllerLegacy
{
	/**
	 * @var		string	The default view.
	 * @since	1.6
	 */
	protected $default_view = 'creativehomepage';

	/**
	 * Method to display a view.
	 *
	 * @param	boolean			If true, the view output will be cached
	 * @param	array			An array of safe url parameters and their variable types, for valid values see {@link JFilterInput::clean()}.
	 *
	 * @return	JController		This object to support chaining.
	 * @since	1.5
	 */
	public function display($cachable = false, $urlparams = false)
	{
		// Load the submenu.
		CreativecontactformHelper::addSubmenu( 'Overview', 'creativehomepage');
		CreativecontactformHelper::addSubmenu( 'Forms', 'creativeforms');
		CreativecontactformHelper::addSubmenu( 'Fields', 'creativefields');
		//CreativecontactformHelper::addSubmenu( 'Options', 'creativeoptions');
		CreativecontactformHelper::addSubmenu( 'Templates', 'templates');

		parent::display();

		return $this;
	}
}
