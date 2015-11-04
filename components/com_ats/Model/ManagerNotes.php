<?php
/**
 * @package   AkeebaTicketSystem
 * @copyright Copyright (c)2011-2015 Nicholas K. Dionysopoulos
 * @license   GNU General Public License version 3, or later
 */

namespace Akeeba\TicketSystem\Site\Model;

defined('_JEXEC') or die;

/**
 * Class ManagerNotes
 *
 * We simply expose the backend model to the frontend. Since we're using the BasicFactory, there are no security issues,
 * public visitors can't reach this model
 *
 * @package Akeeba\TicketSystem\Site\Model
 */
class ManagerNotes extends \Akeeba\TicketSystem\Admin\Model\ManagerNotes
{

}
