<?php
/**
 * @package   AkeebaTicketSystem
 * @copyright Copyright (c)2011-2015 Nicholas K. Dionysopoulos
 * @license   GNU General Public License version 3, or later
 */

namespace Akeeba\TicketSystem\Admin\CustomField;

defined('_JEXEC') or die();

/**
 * A multiple selection list field
 *
 * @author Nicholas K. Dionysopoulos
 */
class Multiselect extends Dropdown
{
	public function __construct(array $config = array())
	{
		parent::__construct($config);

		$this->input_type = 'multiselect';
	}
}