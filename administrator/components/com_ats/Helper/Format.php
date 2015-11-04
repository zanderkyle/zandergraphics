<?php
/**
 * @package   AkeebaTicketSystem
 * @copyright Copyright (c)2011-2015 Nicholas K. Dionysopoulos
 * @license   GNU General Public License version 3, or later
 */

namespace Akeeba\TicketSystem\Admin\Helper;

use Akeeba\TicketSystem\Admin\Model\Categories;
use DateTimeZone;
use FOF30\Container\Container;
use JDate;
use JFactory;
use JText;

defined('_JEXEC') or die;

class Format
{
    public static function date($date, $format = null, $local = false)
    {
        \JLoader::import('joomla.utilities.date');
        $jDate = new JDate($date, 'GMT');

        if(empty($format))
        {
            $format = 'Y-m-d H:i';
        }

        if($local)
        {
            $zone = JFactory::getUser()->getParam('timezone', JFactory::getConfig()->get('offset', 'UTC'));
            $tz = new DateTimeZone($zone);
            $jDate->setTimezone($tz);
        }
        return $jDate->format($format, $local);
    }

    public static function date2($date, $format = null, $local = false)
    {
        \JLoader::import('joomla.utilities.date');
        $jDate = new \JDate($date, 'GMT');

        if(empty($format))
        {
            $format = \JText::_('DATE_FORMAT_LC2').' T';
        }

        if($local)
        {
            $zone = JFactory::getUser()->getParam('timezone', JFactory::getConfig()->get('offset', 'UTC'));
            $tz = new DateTimeZone($zone);
            $jDate->setTimezone($tz);
        }

        return $jDate->format($format, $local, true);
    }

    /**
     * Converts a fancy shothand size notation (e.g. 8M) to bytes
     *
     * @param string $fancy
     *
     * @return int
     */
    public static function toBytes($fancy)
    {
        if(empty($fancy))
        {
            return 0;
        }

        $value = (float)$fancy;
        $fancy = trim($fancy);
        $units = array(
            'K'	=> 1024, 'M' => 1048576, 'G' => 1073741824
        );

        $u = substr($fancy, -1);

        if($u === false)
        {
            return (int)$value;
        }

        if(!array_key_exists($u, $units))
        {
            return (int)$value;
        }

        return (int)($value * $units[$u]);
    }

    /**
     * Convert bytes to human readable format
     *
     * @see http://codeaid.net/php/convert-size-in-bytes-to-a-human-readable-format-(php)
     * @param int $bytes
     * @param int $precision
     *
     * @return string
     */
    public static function bytesToSize($bytes, $precision = 2)
    {
        $kilobyte = 1024;
        $megabyte = $kilobyte * 1024;
        $gigabyte = $megabyte * 1024;
        $terabyte = $gigabyte * 1024;

        if (($bytes >= 0) && ($bytes < $kilobyte))
        {
            return $bytes . ' B';

        }
        elseif (($bytes >= $kilobyte) && ($bytes < $megabyte))
        {
            return round($bytes / $kilobyte, $precision) . ' KB';

        }
        elseif (($bytes >= $megabyte) && ($bytes < $gigabyte))
        {
            return round($bytes / $megabyte, $precision) . ' MB';

        }
        elseif (($bytes >= $gigabyte) && ($bytes < $terabyte))
        {
            return round($bytes / $gigabyte, $precision) . ' GB';
        }
        elseif ($bytes >= $terabyte)
        {
            return round($bytes / $terabyte, $precision) . ' TB';
        }
        else
        {
            return $bytes . ' B';
        }
    }

    public static function categoryName($id)
    {
        static $categories = null;

        if (is_null($categories))
        {
            $container = Container::getInstance('com_ats');

            /** @var Categories $catModel */
            $catModel = $container->factory->model('Categories')->tmpInstance();
            $items    = $catModel->ignoreUser(1)->get();

            foreach ($items as $item)
            {
                $categories[$item->id] = $item->title;
            }
        }

        if (isset($categories[$id]))
        {
            return $categories[$id];
        }
        else
        {
            return '';
        }
    }

    /**
     * Returns a fancy formatted time lapse code
     *
     * @param   int       $referenceTime    Timestamp of the reference date / time
     * @param   string    $currentTime      Timestamp of the current date / time
     * @param   string    $quantifyBy       Quantify by unit, one of s (seconds), m (minutes), h (yours), d (days) or y (years)
     * @param   bool      $autoText		    Automatically appent text
     *
     * @return string
     */
    public static function timeAgo($referenceTime = 0, $currentTime = '', $quantifyBy = '', $autoText = true)
    {
        if ($currentTime == '')
        {
            $currentTime = time();
        }

        // Raw time difference
        $rawTimeDifference = $currentTime - $referenceTime;
        $absoluteTimeDifference = abs($rawTimeDifference);

        $uomMap = array(
            array('s', 60),
            array('m', 60 * 60),
            array('h', 60 * 60 * 60),
            array('d', 60 * 60 * 60 * 24),
            array('y', 60 * 60 * 60 * 24 * 365)
        );

        $textMap = array(
            's' => array(1, 'COM_ATS_TIME_SECOND'),
            'm' => array(60, 'COM_ATS_TIME_MINUTE'),
            'h' => array(60 * 60, 'COM_ATS_TIME_HOUR'),
            'd' => array(60 * 60 * 24, 'COM_ATS_TIME_DAY'),
            'y' => array(60 * 60 * 24 * 365, 'COM_ATS_TIME_YEAR')
        );

        if ($quantifyBy == '')
        {
            $uom = 's';

            for ($i = 0; $i < count($uomMap); $i++)
            {
                if ($absoluteTimeDifference <= $uomMap[$i][1])
                {
                    $uom = $uomMap[$i][0];

                    break;
                }
            }
        }
        else
        {
            $uom = $quantifyBy;
        }

        $dateDifference = floor($absoluteTimeDifference / $textMap[$uom][0]);

        $prefix = '';
        $suffix = '';

        if ($autoText == true && ($currentTime == time()))
        {
            if ($rawTimeDifference < 0)
            {
                $prefix = JText::_('COM_ATS_TIME_AFTER_PRE');
                $suffix = JText::_('COM_ATS_TIME_AFTER_POST');
            }
            else
            {
                $prefix = JText::_('COM_ATS_TIME_AGO_PRE');
                $suffix = JText::_('COM_ATS_TIME_AGO_POST');
            }
        }

        if ($prefix)
        {
            $prefix = trim($prefix) . ' ';
        }

        if ($suffix)
        {
            $suffix = ' ' . trim($suffix);
        }

        return $prefix . $dateDifference . ' ' . JText::_($textMap[$uom][1]) . $suffix;
    }
}