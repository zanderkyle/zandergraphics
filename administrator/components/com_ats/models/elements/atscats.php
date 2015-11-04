<?php
/**
 * @package ats
 * @copyright Copyright (c)2011-2014 Nicholas K. Dionysopoulos / AkeebaBackup.com
 * @license GNU GPL v3 or later
 */

// No direct access
use FOF30\Container\Container;

defined('_JEXEC') or die;

if (!defined('FOF30_INCLUDED'))
{
    include_once(JPATH_LIBRARIES . '/fof30/include.php');
}

// Let's build the container so it will register our extension in the autoloader
Container::getInstance('com_ats');

class JFormFieldAtscats extends JFormField
{
	public function getInput()
	{
        $container = FOF30\Container\Container::getInstance('com_ats');

		$options	= array();
		$options[]	= JHTML::_('select.option','0',JText::_( 'COM_ATS_CONFIG_DEFAULTCAT_MSG' ));

        /** @var \Akeeba\TicketSystem\Admin\Model\Categories $catModel */
        $catModel   = $container->factory->model('Categories')->tmpInstance();
        $categories = $catModel->ignoreUser(1)->get(true);

        foreach($categories as $item)
        {
            $title = $item->title;

            if($item->level > 1)
            {
                $title = str_repeat('–', $item->level - 1).' '.$title;
            }

            $options[] = JHTML::_('select.option', $item->id, $title);
        }

		return JHTML::_('select.genericlist', $options, $this->name, 'class="inputbox"', 'value', 'text', $this->value, $this->id);
	}
}