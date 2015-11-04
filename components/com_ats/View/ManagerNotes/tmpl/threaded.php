<?php
/**
 * @package ats
 * @copyright Copyright (c)2011-2014 Nicholas K. Dionysopoulos / AkeebaBackup.com
 * @license GNU GPL v3 or later
 */

// No direct access
defined('_JEXEC') or die;

if(!count($this->items))
{
    return;
}

// Get the return URL to point back to ourselves
$returnURL = base64_encode(JURI::getInstance()->toString());

/** @var \Akeeba\TicketSystem\Site\Model\ManagerNotes $item */
foreach($this->items as $item)
{
    if(!$item->enabled)
    {
        continue;
    }

    echo $this->loadAnyTemplate('site:com_ats/ManagerNotes/threaded_note',array(
        'item'		=> $item,
        'returnURL'	=> $returnURL
    ));
}