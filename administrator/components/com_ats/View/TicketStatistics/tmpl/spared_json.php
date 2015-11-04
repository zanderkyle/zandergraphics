<?php
/**
 * @package ats
 * @copyright Copyright (c)2011-2014 Nicholas K. Dionysopoulos / AkeebaBackup.com
 * @license GNU GPL v3 or later
 */

/** @var Akeeba\TicketSystem\Admin\View\TicketStatistics\Json $this */

defined('_JEXEC') or die;

/** @var \Akeeba\TicketSystem\Admin\Model\TicketStatistics $ticket */
$ticket = $this->getContainer()->factory->model('TicketStatistics')->tmpInstance();
$tickets = $ticket->created_since($this->input->getCmd('created_since'))
                  ->created_until($this->input->getCmd('created_until'))
                  ->getTotalsByCategory();

/** @var \Akeeba\TicketSystem\Admin\Model\Attempts $attempts */
$attempts = $this->getContainer()->factory->model('Attempts')->tmpInstance();
$spared = $attempts->created_since($this->input->getCmd('created_since'))
                   ->created_until($this->input->getCmd('created_until'))
                   ->getTotalsByCategory();

/** @var \Akeeba\TicketSystem\Admin\Model\Categories $catModel */
$catModel   = $this->getContainer()->factory->model('Categories');
$categories = $catModel->get(true);

$result    = array();
$not_saved = array();
$saved     = array();

foreach($categories as $category)
{
    if(!isset($spared[$category->id]))
    {
        $cur_saved = 0;
    }
    else
    {
        $cur_saved = $spared[$category->id]->total;
    }

    if(!isset($tickets[$category->id]))
    {
        $cur_created = 0;
    }
    else
    {
        $cur_created = $tickets[$category->id]->total;
    }

    if($cur_created)
    {
        $total  = $cur_created + $cur_saved;
        $not_saved[] = round(($cur_created) * 100 / $total, 2);
        $saved[]     = round(($cur_saved) * 100 / $total, 2);
    }
    else
    {
        $not_saved[] = 0;
        $saved[]     = 0;
    }

	$title = $category->title;
	$title = substr($title, 0, 20);

	$result['categories'][] = $title;
}

$result['tickets'][] = $not_saved;
$result['tickets'][] = $saved;

echo json_encode($result);