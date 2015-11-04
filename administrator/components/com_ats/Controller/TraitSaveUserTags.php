<?php
/**
 * @package   AkeebaTicketSystem
 * @copyright Copyright (c)2011-2015 Nicholas K. Dionysopoulos
 * @license   GNU General Public License version 3, or later
 */

namespace Akeeba\TicketSystem\Admin\Controller;

use Akeeba\TicketSystem\Admin\Helper\Permissions;
use Akeeba\TicketSystem\Admin\Model\Tickets;
use FOF30\Controller\DataController;

defined('_JEXEC') or die;

trait TraitSaveUserTags
{
	/**
	 * Handles user tags saving
	 *
	 * User tags are normally saved when a manager submits a Post (reply). However, if a ticket is filed by a manager
	 * then we will save user tags anyway.
	 *
	 * @return  void
	 */
	protected function saveUserTags()
	{
		// Am I a manager?
		/** @var DataController $this*/
		/** @var Tickets $model */
		$model     = $this->getModel();
		$category  = $model->catid;
		$isManager = Permissions::isManager($category);

		// If it's not a manager they're not allowed to save user tags
		if (!$isManager)
		{
			return;
		}

		// Get and save user tags
		$tags = $this->input->get('usertags', array(), 'array', 2);
		$user = \JUser::getInstance($model->created_by);

		$user->setParam('ats_tags', $tags);
		$user->save();
	}
}