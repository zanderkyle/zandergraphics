<?php
/**
 * @package ats
 * @copyright Copyright (c)2011-2014 Nicholas K. Dionysopoulos / AkeebaBackup.com
 * @license GNU GPL v3 or later
 */

// No direct access
defined('_JEXEC') or die;

if(empty($this->items)) return;

$i = 0;
foreach($this->items as $item)
{
	echo $this->loadAnyTemplate('site:com_ats/Posts/print_post',array(
		'item'				=> $item,
		'showCustomFields'	=> ($i === 0)
	));

	$i++;
}