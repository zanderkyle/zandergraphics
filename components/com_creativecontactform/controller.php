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


/**
 * creative_contact_form Controller
 *
 * @package Joomla
 * @subpackage creative_contact_form
 */
class CreativeContactFormController extends JControllerLegacy {
	
	/**
	 * @var		string	The default view.
	 * @since	1.6
	 */
	protected $default_view = 'creativecontactform';

    public function display($cachable = false, $urlparams = false) {
		parent::display();
    }
}
?>