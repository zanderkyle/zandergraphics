<?php
/**
 * @package   AkeebaTicketSystem
 * @copyright Copyright (c)2011-2015 Nicholas K. Dionysopoulos
 * @license   GNU General Public License version 3, or later
 */

namespace Akeeba\TicketSystem\Admin\Model;

defined('_JEXEC') or die;

use FOF30\Model\DataModel;

class DefaultDataModel extends DataModel
{
    /**
     * Make sure $condition is true or throw a RuntimeException with the $message language string
     *
     * @param   bool    $condition  The condition which must be true
     * @param   string  $message    The language key for the message to throw
     *
     * @throws  \RuntimeException
     */
    protected function assert($condition, $message)
    {
        if (!$condition)
        {
            throw new \RuntimeException(\JText::_($message));
        }
    }

    /**
     * Assert that $value is not empty or throw a RuntimeException with the $message language string
     *
     * @param   mixed   $value    The value to check
     * @param   string  $message  The language key for the message to throw
     *
     * @throws  \RuntimeException
     */
    protected function assertNotEmpty($value, $message)
    {
        $this->assert(!empty($value), $message);
    }

    /**
     * Assert that $value is set to one of $validValues or throw a RuntimeException with the $message language string
     *
     * @param   mixed   $value        The value to check
     * @param   array   $validValues  An array of valid values for $value
     * @param   string  $message      The language key for the message to throw
     *
     * @throws  \RuntimeException
     */
    protected function assertInArray($value, array $validValues, $message)
    {
        $this->assert(in_array($value, $validValues), $message);
    }

    /**
     * Converts the loaded JSON string into an array
     *
     * @param   string  $value  The JSON string
     *
     * @return  array  The data
     */
    protected function getAttributeForJson($value)
    {
        if (is_array($value))
        {
            return $value;
        }

        if (empty($value))
        {
            return array();
        }

        $value = json_decode($value, true);

        if (empty($value))
        {
            return array();
        }

        return $value;
    }

    /**
     * Converts and array into a JSON string
     *
     * @param   array  $value  The data
     *
     * @return  string  The JSON string
     */
    protected function setAttributeForJson($value)
    {
        if (!is_array($value))
        {
            return $value;
        }

        $value = json_encode($value);

        return $value;
    }

    /**
     * Converts the loaded comma-separated list into an array
     *
     * @param   string  $value  The comma-separated list
     *
     * @return  array  The exploded array
     */
    protected function getAttributeForImplodedArray($value)
    {
        if (is_array($value))
        {
            return $value;
        }

        if (empty($value))
        {
            return array();
        }

        $value = explode(',', $value);
        $value = array_map('trim', $value);

        return $value;
    }

    /**
     * Converts an array of values into a comma separated list
     *
     * @param   array  $value  The array of values
     *
     * @return  string  The imploded comma-separated list
     */
    protected function setAttributeForImplodedArray($value)
    {
        if (!is_array($value))
        {
            return $value;
        }

        $value = array_map('trim', $value);
        $value = implode(',', $value);

        return $value;
    }
}