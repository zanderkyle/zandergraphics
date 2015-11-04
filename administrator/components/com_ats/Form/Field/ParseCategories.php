<?php
/**
 * @package   AkeebaTicketSystem
 * @copyright Copyright (c)2011-2015 Nicholas K. Dionysopoulos
 * @license   GNU General Public License version 3, or later
 */

namespace Akeeba\TicketSystem\Admin\Form\Field;

use FOF30\Form\Field\Text;
use JText;

defined('_JEXEC') or die();

class ParseCategories extends Text
{
    public function getRepeatable()
    {
        // Initialise
        $class					= $this->id;
        $format_string			= '';
        $format_if_not_empty	= false;
        $parse_value			= false;
        $empty_replacement		= '';
        $value                  = null;

        // Get field parameters
        if ($this->element['class'])
        {
            $class = (string) $this->element['class'];
        }

        if ($this->element['format'])
        {
            $format_string = (string) $this->element['format'];
        }

        if ($this->element['format_if_not_empty'] == 'true')
        {
            $format_if_not_empty = true;
        }

        if ($this->element['parse_value'] == 'true')
        {
            $parse_value = true;
        }

        if ($this->element['empty_replacement'])
        {
            $empty_replacement = (string) $this->element['empty_replacement'];
        }

        // Get the (optionally formatted) value
        $params = $this->item->params;

        if(isset($params['categories']))
        {
            $value  = $params['categories'];
        }

        if (!empty($empty_replacement) && empty($value))
        {
            $value = JText::_($empty_replacement);
        }

        if ($parse_value)
        {
            $value = $this->parseFieldTags($value);
        }

        $categories = array();

        if($value)
        {
            if(!class_exists('CategoriesModelCategories'))
            {
                require_once JPATH_ADMINISTRATOR.'/components/com_categories/models/categories.php';
            }

            $model = new \CategoriesModelCategories();
            $model->getState()->set('list.select', 'a.id, a.title, a.level');
            $model->getState()->set('filter.extension',	'com_ats');
            $model->getState()->set('filter.access',		null);
            $model->getState()->set('filter.published',	1);
            $items = $model->getItems();

            if(count($items)) foreach($items as $item)
            {
                if(!in_array($item->id, $value))
                {
                    continue;
                }

                $categories[] = $item->title;
            }
        }

        if (!empty($format_string) && (!$format_if_not_empty || ($format_if_not_empty && !empty($categories))))
        {
            $format_string = $this->parseFieldTags($format_string);

            $value = '';

            foreach($categories as $category)
            {
                $value .= sprintf($format_string, $category);
            }
        }
        else
        {
            $value = htmlspecialchars(implode(',', $value), ENT_COMPAT, 'UTF-8');
        }

        // Create the HTML
        $html = '<span class="' . $class . '">';

        $html .= $value;

        $html .= '</span>';

        return $html;
    }
}
