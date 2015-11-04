<?php
/**
 * @package   AkeebaTicketSystem
 * @copyright Copyright (c)2011-2015 Nicholas K. Dionysopoulos
 * @license   GNU General Public License version 3, or later
 */

namespace Akeeba\TicketSystem\Admin\Model\Behaviour;

use FOF30\Event\Observer;
use FOF30\Model\DataModel;

defined('_JEXEC') or die;

class AlwaysModified extends Observer
{
	/**
	 * Add the modified_on and modified_by fields in the fieldsSkipChecks list of the model. We expect them to be empty
	 * so that we can fill them in through this behaviour.
	 *
	 * @param   DataModel  $model
	 */
	public function onBeforeCheck(&$model)
	{
		$model->addSkipCheckField('modified_on');
		$model->addSkipCheckField('modified_by');
	}

	/**
     * Always fill the modified time, even if we are creating a new record. This is a special case used in ATS to maintain
     * backward compatibility
     *
	 * @param   DataModel  $model
	 * @param   \stdClass  $dataObject
	 */
	public function onBeforeCreate(&$model, &$dataObject)
	{
		// Make sure we're not modifying a locked record
		$userId   = $model->getContainer()->platform->getUser()->id;
		$isLocked = $model->isLocked($userId);

		if ($isLocked)
		{
			return;
		}

		// Handle the modified_on field
		if ($model->hasField('modified_on'))
		{
			$model->setFieldValue('modified_on', $model->getContainer()->platform->getDate()->toSql(false, $model->getDbo()));

			$modifiedOnField = $model->getFieldAlias('modified_on');
			$dataObject->$modifiedOnField = $model->getFieldValue('modified_on');
		}

		// Handle the modified_by field
		if ($model->hasField('modified_by'))
		{
			$model->setFieldValue('modified_by', $userId);

			$modifiedByField = $model->getFieldAlias('modified_by');
			$dataObject->$modifiedByField = $model->getFieldValue('modified_by');
		}
	}
}