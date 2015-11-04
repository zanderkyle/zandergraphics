<?php
/**
 * @package   AkeebaTicketSystem
 * @copyright Copyright (c)2011-2015 Nicholas K. Dionysopoulos
 * @license   GNU General Public License version 3, or later
 */

defined('_JEXEC') or die;

/** @var FOF30\Form\Form $form */
$fieldName = (string) $fieldElement['name'];
$selected = isset($fieldValue['categories']) ? $fieldValue['categories'] : array();


$html = \Akeeba\TicketSystem\Admin\Helper\Select::getCategories($selected, $fieldName,
    array(
        'multiple' => "true",
        'size' => 5
    ),
    false
    );

echo $html;