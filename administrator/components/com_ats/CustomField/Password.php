<?php
/**
 * @package   AkeebaTicketSystem
 * @copyright Copyright (c)2011-2015 Nicholas K. Dionysopoulos
 * @license   GNU General Public License version 3, or later
 */

namespace Akeeba\TicketSystem\Admin\CustomField;

defined('_JEXEC') or die();

/**
 * A password input field
 *
 * @author Nicholas K. Dionysopoulos
 */
class Password extends Text
{
	public function __construct(array $config = array())
	{
		parent::__construct($config);

		$this->input_type = 'password';
	}
}