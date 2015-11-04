<?php
/**
 * @package		ats
 * @copyright	Copyright (c)2010-2014 Nicholas K. Dionysopoulos / AkeebaBackup.com
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
 */

defined('_JEXEC') or die();

use FOF30\Container\Container;

class plgAtsCustomfields extends JPlugin
{
	private $fieldTypes = array();

	public function __construct(&$subject, $config = array())
    {
		parent::__construct($subject, $config);

        if (!defined('FOF30_INCLUDED') && !@include_once(JPATH_LIBRARIES . '/fof30/include.php'))
        {
            throw new RuntimeException('FOF 3.0 is not installed', 500);
        }

        // Invoke the container to register our autoload, if required
        Container::getInstance('com_ats');

        // Let's get all the fields
        $customFields = \Akeeba\TicketSystem\Admin\Helper\Select::getFieldTypes();

        $this->fieldTypes = array_keys($customFields);
	}

	/**
	 * Renders per-category custom fields
	 *
	 * @param   array   $cache
	 * @param   array   $userparams
	 *
	 * @return  array
	 */
	public function onTicketFormRenderPerCatFields($cache, $userparams = null)
	{
        $container = Container::getInstance('com_ats');

		$db   = $container->db;
		$lang = $container->platform->getLanguage();
		$lang->load('plg_ats_customfields', JPATH_ADMINISTRATOR, 'en-GB', true);
		$lang->load('plg_ats_customfields', JPATH_ADMINISTRATOR, null, true);

		// Init the fields array which will be returned
		$fields = array();

		if(!isset($cache['params']))
        {
            $cache['params'] = array();
        }

		// No catid? Stop here
		if(!isset($cache['catid']) || !$cache['catid'])
		{
			return $fields;
		}
        /** @var \Akeeba\TicketSystem\Admin\Model\CustomFields $model */
        $model     = $container->factory->model('CustomFields')->tmpInstance();

		// Load field definitions
		$items = $model
                    ->enabled(1)
                    ->filter_order('ordering')
                    ->filter_order_Dir('ASC')
                    ->limit(0)
                    ->limitStart(0)
                    ->get();

		if(empty($items))
        {
            return $fields;
        }

        // I can't use model relations since I have to fetch ALL fields + the ones attached to the current category
		$query = $db->getQuery(true)
					->select($db->qn('ats_customfield_id'))
					->from($db->qn('#__ats_customfields_cats'))
					->where($db->qn('catid').' = '.$db->q($cache['catid']));
		$customcats = $db->setQuery($query)->loadColumn();

		// Loop through the items
        /** @var \Akeeba\TicketSystem\Admin\Model\CustomFields $item */
		foreach($items as $item)
		{
			// If it's not something shown in this category, skip it
			if($item->show == 'category')
			{
				if(is_null($cache['catid']))
                {
                    continue;
                }

				if(!in_array($item->ats_customfield_id, $customcats))
                {
                    continue;
                }
			}

			// Get the names of the methods to use
			$type  = $item->type;
			$class = 'Akeeba\\TicketSystem\\Admin\\CustomField\\' . ucfirst($type);

			if (!class_exists($class))
			{
				continue;
			}

            /** @var \Akeeba\TicketSystem\Admin\CustomField\Base $object */
			$object = new $class;

			// Add the field to the list
			if (empty($userparams))
			{
				$userparams = (object)array(
					'params'	=> null
				);
			}

			$result = $object->getField($item, $cache, $userparams);

			if(is_null($result) || empty($result))
            {
				continue;
			}
            else
            {
				$fields[] = $result;
			}

			// Add Javascript for the field
			$object->getJavascript($item);
		}

		return $fields;
	}

	public function onValidate($data, $category)
	{
        $container = Container::getInstance('com_ats');
		$db        = $container->db;

		$response = array(
			'valid'				=> true,
			'isValid'			=> true,
			'custom_validation'	=> array()
		);

        /** @var \Akeeba\TicketSystem\Admin\Model\CustomFields $model */
        $model     = $container->factory->model('CustomFields')->tmpInstance();

		// Load field definitions
		$items = $model
                    ->enabled(1)
                    ->filter_order('ordering')
                    ->filter_order_Dir('ASC')
                    ->get(true);

		// If there are no custom fields return true (all valid)
		if(empty($items))
        {
            return $response;
        }

        // I can't use model relations since I have to fetch ALL fields + the ones attached to the current category
		$query = $db->getQuery(true)
					->select($db->qn('ats_customfield_id'))
					->from($db->qn('#__ats_customfields_cats'))
					->where($db->qn('catid').' = '.$db->q($category));
		$customcats = $db->setQuery($query)->loadColumn();

		// Loop through each custom field
        /** @var \Akeeba\TicketSystem\Admin\Model\CustomFields $item */
		foreach($items as $item)
		{
			// Make sure it's supposed to be shown in the particular level
			if($item->show == 'category')
			{
				if(!$category)
                {
                    continue;
                }

				if(!in_array($item->ats_customfield_id, $customcats))
                {
                    continue;
                }
			}

			// Make sure there is a validation method for this type of field
			$type  = $item->type;
			$class = 'Akeeba\\TicketSystem\\Admin\\CustomField\\' . ucfirst($type);

			if (!class_exists($class))
			{
				continue;
			}

            /** @var \Akeeba\TicketSystem\Admin\CustomField\Base $object */
			$object = new $class;

			// Get the validation result and save it in the $response array
			$response['custom_validation'][$item->slug] = $object->validate($item, $data);

			if(is_null($response['custom_validation'][$item->slug]))
			{
				unset($response['custom_validation'][$item->slug]);
			}
			elseif(!$item->allow_empty)
			{
				$response['isValid'] = $response['isValid'] && $response['custom_validation'][$item->slug];
			}
		}

		// Update the master "valid" reponse. If one of the fields is invalid,
		// the entire plugin's result is invalid (the form should not be submitted)
		$response['valid'] = $response['isValid'];

		return $response;
	}
}