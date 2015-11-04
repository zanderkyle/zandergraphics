<?php
/**
 * @package   AkeebaTicketSystem
 * @copyright Copyright (c)2011-2015 Nicholas K. Dionysopoulos
 * @license   GNU General Public License version 3, or later
 */

namespace Akeeba\TicketSystem\Admin\Helper;

use FOF30\Container\Container;
use FOF30\Model\DataModel;
use JFolder;
use JHtml;
use JLoader;
use JText;

defined('_JEXEC') or die;

/**
 * A helper class for drop-down selection boxes
 */
abstract class Select
{
	/**
	 * Returns a list of custom field types
	 *
	 * @return  array  type => description
	 */
	public static function getFieldTypes()
	{
		$fieldTypes = array();

		JLoader::import('joomla.filesystem.folder');

		$basepath = JPATH_ADMINISTRATOR . '/components/com_ats/CustomField';

		$files = JFolder::files($basepath, '.php');

		foreach ($files as $file)
		{
			if ($file === 'Base.php')
			{
				continue;
			}

			$type      = basename($file, '.php');
			$className = 'Akeeba\\TicketSystem\\Admin\\CustomField\\' . $type;

			if (class_exists($className))
			{
				$fieldTypes[ strtolower($type) ] = JText::_('COM_ATS_CUSTOMFIELDS_FIELD_TYPE_' . strtoupper($type));
			}
		}

		return $fieldTypes;
	}

	/**
	 * Return a generic drop-down list
	 *
	 * @param   array   $list      An array of objects, arrays, or scalars.
	 * @param   string  $name      The value of the HTML name attribute.
	 * @param   mixed   $attribs   Additional HTML attributes for the <select> tag. This
	 *                             can be an array of attributes, or an array of options. Treated as options
	 *                             if it is the last argument passed. Valid options are:
	 *                             Format options, see {@see JHtml::$formatOptions}.
	 *                             Selection options, see {@see JHtmlSelect::options()}.
	 *                             list.attr, string|array: Additional attributes for the select
	 *                             element.
	 *                             id, string: Value to use as the select element id attribute.
	 *                             Defaults to the same as the name.
	 *                             list.select, string|array: Identifies one or more option elements
	 *                             to be selected, based on the option key values.
	 * @param   mixed   $selected  The key that is selected (accepts an array or a string).
	 * @param   string  $idTag     Value of the field id or null by default
	 *
	 * @return  string  HTML for the select list
	 */
	protected static function genericlist($list, $name, $attribs = null, $selected = null, $idTag = null)
	{
		if (empty($attribs))
		{
			$attribs = null;
		}
		else
		{
			$temp = '';

			foreach ($attribs as $key => $value)
			{
				$temp .= ' ' . $key . '="' . $value . '"';
			}

			$attribs = $temp;
		}

		return JHtml::_('select.genericlist', $list, $name, $attribs, 'value', 'text', $selected, $idTag);
	}

	/**
	 * Generates an HTML radio list.
	 *
	 * @param   array    $list       An array of objects
	 * @param   string   $name       The value of the HTML name attribute
	 * @param   string   $attribs    Additional HTML attributes for the <select> tag
	 * @param   string   $selected   The name of the object variable for the option text
	 * @param   boolean  $idTag      Value of the field id or null by default
	 *
	 * @return  string  HTML for the select list
	 */
	protected static function genericradiolist($list, $name, $attribs = null, $selected = null, $idTag = null)
	{
		if (empty($attribs))
		{
			$attribs = null;
		}
		else
		{
			$temp = '';

			foreach ($attribs as $key => $value)
			{
				$temp .= $key . ' = "' . $value . '"';
			}

			$attribs = $temp;
		}

		return JHtml::_('select.radiolist', $list, $name, $attribs, 'value', 'text', $selected, $idTag);
	}

	/**
	 * Generates a yes/no drop-down list.
	 *
	 * @param   string  $name      The value of the HTML name attribute
	 * @param   array   $attribs   Additional HTML attributes for the <select> tag
	 * @param   string  $selected  The key that is selected
	 *
	 * @return  string  HTML for the list
	 */
	public static function booleanlist($name, $attribs = null, $selected = null)
	{
		$options = array(
			JHtml::_('select.option', '', '---'),
			JHtml::_('select.option', '0', JText::_('JNo')),
			JHtml::_('select.option', '1', JText::_('JYes'))
		);

		return self::genericlist($options, $name, $attribs, $selected, $name);
	}

	/**
	 * Generates a Published/Unpublished drop-down list.
	 *
	 * @param   string  $selected  The key that is selected (0 = unpublished / 1 = published)
	 * @param   string  $id        The value of the HTML name attribute
	 * @param   array   $attribs   Additional HTML attributes for the <select> tag
	 *
	 * @return  string  HTML for the list
	 */
	public static function published($selected = null, $id = 'enabled', $attribs = array())
	{
		$options   = array();
		$options[] = JHtml::_('select.option', null, '- ' . JText::_('COM_ATS_COMMON_SELECTSTATE') . ' -');
		$options[] = JHtml::_('select.option', 0, JText::_('JUNPUBLISHED'));
		$options[] = JHtml::_('select.option', 1, JText::_('JPUBLISHED'));

		return self::genericlist($options, $id, $attribs, $selected, $id);
	}

	/**
	 * Generates a the options for the drop-down list of the available languages on a multi-language site.
	 * In this way we can use it in a F0FForm list field.
	 *
	 * @param   string  $selected  The key that is selected
	 * @param   string  $id        The value of the HTML name attribute
	 * @param   array   $attribs   Additional HTML attributes for the <select> tag
	 *
	 * @return  string  HTML for the list
	 */
	public static function languages($selected = null, $id = 'language', $attribs = array())
	{
		JLoader::import('joomla.language.helper');
		$languages = \JLanguageHelper::getLanguages('lang_code');
		$options   = array();
		$options[] = array('value' => '*', 'text' => JText::_('JALL_LANGUAGE'));

		if (!empty($languages))
		{
			foreach ($languages as $key => $lang)
			{
				$options[] = array('value' => $key, 'text' => $lang->title);
			}
		}

		return $options;
	}

	public static function getCategories($selected = null, $name = 'category', $attribs = array(), $default_option = null)
	{
		$options = self::getCategoriesOptions($default_option, true);

		return JHTML::_('select.genericlist', $options, $name, $attribs, 'value', 'text', $selected, $name);
	}

    /**
     * Static method that return only the options for a category select list.
     * In this way we can use it in a F0FForm list field.
     *
     * @param   string  $default_option     The no option text
     * @param   bool    $asObject           Do I want to create an array of array or of objects?
     *
     * @return array
     */
	public static function getCategoriesOptions($default_option = null, $asObject = false)
	{
        $options	= array();

        // Do I want a default option, at all?
        if($default_option !== false)
        {
            if(is_null($default_option))
            {
                $default_option = 'COM_ATS_CONFIG_DEFAULTCAT_MSG';
            }

            if($asObject)
            {
                $options[]	= JHTML::_('select.option','',JText::_( $default_option ));
            }
            else
            {
                $options[] = array('value' => '', 'text' => JText::_($default_option));
            }
        }


		if(!class_exists('CategoriesModelCategories'))
		{
			require_once JPATH_ADMINISTRATOR.'/components/com_categories/models/categories.php';
		}

		$model = new \CategoriesModelCategories();

        $modelState = $model->getState();

		$modelState->set('list.select', 'a.id, a.title, a.level');
        $modelState->set('filter.extension',	'com_ats');
        $modelState->set('filter.access',		null);
        $modelState->set('filter.published',	1);
        $modelState->set('list.start',	0);
        $modelState->set('list.limit',	0);

		$items = $model->getItems();

		if(count($items)) foreach($items as $item)
		{
			$id     = $item->id;
			$title  = $item->title;

			if($item->level > 1)
			{
				$title = str_repeat('–', $item->level - 1).' '.$title;
			}

			if($asObject)
			{
				$options[] = JHTML::_('select.option', $id, $title);
			}
			else
			{
				$options[] = array('value' => $id, 'text' => $title);
			}

		}

		return $options;
	}

    public static function ticketstatuses($selected = '', $name = 'status', $attribs = array(), $id = 'status')
    {
        $options[] = JHTML::_('select.option','','- '.JText::_('COM_ATS_TICKETS_STATUS_SELECT').' -');

        $options = array_merge($options, self::getTicketStatus());

        return self::genericlist($options, $name, $attribs, $selected, $id);
    }

	public static function getTicketStatus()
	{
		$options[] = array('value' => 'O', 'text' => JText::_('COM_ATS_TICKETS_STATUS_O'));
		$options[] = array('value' => 'P', 'text' => JText::_('COM_ATS_TICKETS_STATUS_P'));

		$statuses = ComponentParams::getCustomTicketStatuses();

		foreach($statuses as $value => $text)
		{
			$options[] = array('value' => $value, 'text' => $text);
		}

		$options[] = array('value' => 'C', 'text' => JText::_('COM_ATS_TICKETS_STATUS_C'));

		return $options;
	}

    public static function publicstate($selected = '', $id = 'status', $attribs = array())
    {
        $options = array(
            JHTML::_('select.option','','- '.JText::_('COM_ATS_TICKETS_PUBLIC_SELECT').' -'),
            JHTML::_('select.option',  '0', JText::_( 'COM_ATS_TICKETS_PUBLIC_PRIVATE' ) ),
            JHTML::_('select.option',  '1', JText::_( 'COM_ATS_TICKETS_PUBLIC_PUBLIC' ) )
        );
        return self::genericlist($options, $id, $attribs, $selected, $id);
    }

    /**
     * Ticket priorities select list
     *
     * @param   string       $name      Select list name and id
     * @param   string       $selected  Selected value
     * @param   array|string $attribs   Associative array of attributes
     *
     * @return  string   Select list HTML
     */
    public static function priorities($name = 'priority', $selected = '', $attribs = array())
    {
        $options = array();

        // Priority is a 1-5-10 list, so in the future we can add more items for fine-tuning
        $options[] = JHTML::_('select.option', ''   ,'- '.JText::_('COM_ATS_COMMON_SELECT').' -');
        $options[] = JHTML::_('select.option', '1'  ,JText::_('COM_ATS_PRIORITIES_HIGH'));
        $options[] = JHTML::_('select.option', '5'  ,JText::_('COM_ATS_PRIORITIES_NORMAL'));
        $options[] = JHTML::_('select.option', '10' ,JText::_('COM_ATS_PRIORITIES_LOW'));

        return self::genericlist($options, $name, $attribs, $selected, $name);
    }

    public static function getManagers($selected = '', $id = 'user_id', $attribs = array(), $category = null)
    {
        $options[] = JHTML::_('select.option','','- '.JText::_('COM_ATS_TICKETS_ASSIGN_TO').' -');

        $managers = Permissions::getManagers($category);

        foreach ($managers as $manager)
        {
            $options[] = JHTML::_('select.option', $manager->id, $manager->name);
        }

        return self::genericlist($options, $id, $attribs, $selected, $id);
    }

    public static function buckets($selected = '', $id = 'ats_bucket_id', $attribs = array())
    {
        $container = Container::getInstance('com_ats');

        $options[] = JHTML::_('select.option', null, '- '.JText::_('COM_ATS_TICKETS_BUCKET_SELECT').' -');

        /** @var \Akeeba\TicketSystem\Admin\Model\Buckets $model */
        $model   = $container->factory->model('Buckets')->tmpInstance();
        $buckets = $model->status('O', 'P')->get(true);

        foreach($buckets as $bucket)
        {
            $options[] = JHTML::_('select.option',$bucket->ats_bucket_id,$bucket->title);
        }

        return self::genericlist($options, $id, $attribs, $selected, $id);
    }

    /**
     * User tag select list
     *
     * @param   string  $name       Select list name and id
     * @param   array   $selected   Selected value
     * @param   array   $attribs    Associative array of attributes
     *
     * @return string   Select list HTML
     */
    public static function usertags($name, $selected, $attribs = array())
    {
        $container = Container::getInstance('com_ats');

        /** @var \Akeeba\TicketSystem\Admin\Model\UserTags $model */
        $model   = $container->factory->model('UserTags')->tmpInstance();

        $options = array();

        $tags = $model
                ->enabled(1)
                ->filter_Order('ordering')
                ->filter_Order_Dir('ASC')
                ->limit(0)
                ->get();

        foreach($tags as $tag)
        {
            $options[] = JHTML::_('select.option', $tag->ats_usertag_id, $tag->title);
        }

        return self::genericlist($options, $name, $attribs, $selected, $name);
    }
}