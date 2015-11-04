<?php
/**
 * @package   AkeebaTicketSystem
 * @copyright Copyright (c)2011-2015 Nicholas K. Dionysopoulos
 * @license   GNU General Public License version 3, or later
 */
defined('_JEXEC') or die;

/**
 * This class is required since Joomla will look for a file in helpers/ats.php with a class and method named
 * AtsHelper::addSubmenu
 */
class AtsHelper
{
    public static function addSubmenu($vName)
    {
        JToolBarHelper::title(
            JText::sprintf('COM_CATEGORIES_CATEGORIES_TITLE', JText::_('COM_ATS')),
            'ats-categories');

        // Joomla layout doesn't support dropdowns, so we just display a link back to our component
        JSubMenuHelper::addEntry(JText::_('COM_ATS_TITLE_CPANEL'), 'index.php?option=com_ats');
        JSubMenuHelper::addEntry(JText::_('COM_ATS_SUBMENU_CATEGORIES'),
            'index.php?option=com_categories&extension=com_ats');
    }
}