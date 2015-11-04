<?php
/**
 * @package ats
 * @copyright Copyright (c)2011-2014 Nicholas K. Dionysopoulos / AkeebaBackup.com
 * @license GNU GPL v3 or later
 */

/** @var \Akeeba\TicketSystem\Site\View\Posts\Raw $this */
defined('_JEXEC') or die;

$errors    = $this->input->get('attachmentErrors', array(), 'array');
$returnURL = $this->input->getBase64('returnurl', base64_encode(JURI::getInstance()->toString()));

echo $this->loadAnyTemplate('site:com_ats/Posts/threaded_post', array(
    'item'              => $this->item,
    'returnURL'         => $returnURL,
    'showCustomFields'  => 0,
    'attachmentErrors'  => $errors
));