<?php
/**
 * Item Controller
 *
 * @package         ReReplacer
 * @version         6.1.2
 *
 * @author          Peter van Westen <peter@nonumber.nl>
 * @link            http://www.nonumber.nl
 * @copyright       Copyright © 2015 NoNumber All Rights Reserved
 * @license         http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

defined('_JEXEC') or die;

jimport('joomla.application.component.controllerform');

/**
 * Item Controller
 */
class ReReplacerControllerItem extends JControllerForm
{
	/**
	 * @var        string    The prefix to use with controller messages.
	 */
	protected $text_prefix = 'NN';
	// Parent class access checks are sufficient for this controller.
}
