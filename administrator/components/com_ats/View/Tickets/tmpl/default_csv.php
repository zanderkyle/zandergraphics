<?php
/**
 * @package ats
 * @copyright Copyright (c)2011-2014 Nicholas K. Dionysopoulos / AkeebaBackup.com
 * @license GNU GPL v3 or later
 */

/** @var \Akeeba\TicketSystem\Admin\View\Tickets\Csv $this */
/** @var \Akeeba\TicketSystem\Admin\Model\Tickets $model */

// No direct access
defined('_JEXEC') or die;

// I have to manually build the file since I have relations

$csv    = array();
$model  = $this->getModel();

$columns = array('ats_ticket_id', 'status', 'title', 'alias', 'public', 'origin', 'timespent', 'created_on', 'created_by', 'enabled',);
$custom  = array();

if ($this->csvHeader)
{
	foreach ($columns as $column)
	{
		$csv[] = '"' . str_replace('"', '""', $column) . '"';
	}

    $csv[] = "category_title";
    $csv[] = "user_username";
    $csv[] = "user_name";
    $csv[] = "user_email";

    // These are the "standard" fields, now let's see if I have any custom field
    /** @var Akeeba\TicketSystem\Admin\Model\Tickets $item */
    foreach($this->items as $item)
    {
        $params = $item->params;
        $keys   = array_keys($params);

        foreach($keys as $key)
        {
            $custom[] = 'custom_'.$key;
        }
    }

    $custom = array_unique($custom);
    $csv    = array_merge($csv, $custom);

	echo implode(",", $csv) . "\r\n";
}

/** @var Akeeba\TicketSystem\Admin\Model\Tickets $item */
foreach ($this->items as $item)
{
	$csv = array();

    // First let's get the standard columns
	foreach ($columns as $column)
	{
		if (isset($item->$column))
		{
			$csv[] = '"' . str_replace('"', '""', $item->$column) . '"';
		}
		else
		{
			$csv[] = '""';
		}
	}

    // Now let's add the relation fields
    $csv[] = '"' . str_replace('"', '""', $item->joomla_category->title) . '"';
    $csv[] = '"' . str_replace('"', '""', $model->getUser($item->created_by)->username) . '"';
    $csv[] = '"' . str_replace('"', '""', $model->getUser($item->created_by)->name) . '"';
    $csv[] = '"' . str_replace('"', '""', $model->getUser($item->created_by)->email) . '"';

    // It's time for the custom fields
    foreach($custom as $custField)
    {
        $params = $item->params;
        $value  = '';
        $field  = str_replace('custom_', '', $custField);

        if(isset($params[$field]))
        {
            if(is_array($params[$field]))
            {
                $value = 'Array';
            }
            elseif(is_object($params[$field]))
            {
                $value = 'Object';
            }
            else
            {
                $value = $params[$field];
            }
        }

        $csv[] = '"' . str_replace('"', '""', $value) . '"';
    }

	echo implode(",", $csv) . "\r\n";
}