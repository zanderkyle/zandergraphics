<?php
/**
 * @package ats
 * @copyright Copyright (c)2011-2015 Nicholas K. Dionysopoulos / AkeebaBackup.com
 * @license GNU GPL v3 or later
 */
namespace Akeeba\TicketSystem\Admin\Helper;

use FOF30\Container\Container;

defined('_JEXEC') or die;

class Filter
{
    // Array with bad words (ie wget, curl) that would trigger security warnings
    static $badWords = array('wget', 'curl', '^');

    static $transliterationTable = null;

    public static function toSlug($value)
    {
        $originalValue = $value;

        if (class_exists('JApplicationHelper') && method_exists('JApplicationHelper', 'stringURLSafe'))
            // Preferentially use JApplicationHelper::stringURLSafe
        {
            $value = \JApplicationHelper::stringURLSafe($value);
        }

        // If the built-in method returns nothing or utter garbage, restore the previous version
        $v2 = trim($value, '-');
        if (empty($v2))
        {
            $value = $originalValue;
        }

        // We always use our function, since if the user choose the option "Unicode Aliases" we will have aliases with wrong
        // characters (eg ^) and no char transliteration. Moreover we want to remove some word that could cause
        // security issues, like WGET or CURL

        if (function_exists('mb_strtolower'))
        {
            $value = mb_strtolower($value, 'UTF-8');
        }
        else
        {
            $value = trim(strtolower($value));
        }

        //remove any '-' from the string they will be used as concatonater
        $value = str_replace('-', ' ', $value);

        if (is_null(self::$transliterationTable))
        {
            self::$transliterationTable = array();

            // Load the transliteration table from the component configuration
            $rawTable = ComponentParams::getParam('transliteration', 'α|a,β|v,γ|g,δ|d,ε|e,ζ|z,η|i,θ|th,ι|i,κ|k,λ|l,μ|m,ν|n,ξ|x,ο|o,π|p,ρ|r,σ|s,ς|s,τ|t,υ|y,φ|f,χ|ch,ψ|ps,ω|o,ά|a,έ|e,ή|i,ί|i,ό|o,ύ|y,ώ|o,ϊ|i,ϋ|y,ΐ|i,ΰ|y,à|a,á|a,â|a,ä|a,æ|ae,ã|a,å|a,ā|a,ç|c,ć|c,č|c,è|e,é|e,ê|e,ë|e,ē|e,ė|e,ę|e,î|i,ï|i,í|i,ī|i,į|i,ì|i,ł|i,ñ|n,ń|n,ô|o,ö|o,ò|o,ó|o,œ|oe,ø|o,ō|o,õ|o,ß|s,ś|s,š|s,û|u,ü|u,ù|u,ú|u,ū|u,ÿ|y,ž|z,ź|z,ż|z,а|a,б|b,в|v,г|g,д|d,е|e,ё|e,ж|zh,з|z,и|i,й|i,к|k,л|l,м|m,н|n,о|o,п|p,р|r,с|s,т|t,у|y,ф|f,х|kh,ц|ts,ч|ch,ш|sh,щ|sh,ы|y,э|e,ю|yu,я|ya');
            $rawTable = trim($rawTable);

            if (!empty($rawTable))
            {
                $rawTable = explode(',', $rawTable);
                foreach ($rawTable as $rawPair)
                {
                    $rawPair = trim($rawPair);
                    $parts = explode('|', $rawPair);
                    if (count($parts) != 2)
                    {
                        continue;
                    }
                    $parts[0] = trim($parts[0]);
                    $parts[1] = trim($parts[1]);

                    if (empty($parts[0]))
                    {
                        continue;
                    }

                    if (array_key_exists($parts[0], self::$transliterationTable))
                    {
                        continue;
                    }

                    self::$transliterationTable[$parts[0]] = $parts[1];
                }
            }
        }

        if (!empty(self::$transliterationTable))
        {
            foreach (self::$transliterationTable as $from => $to)
            {
                $value = str_replace($from, $to, $value);
            }
        }

        // Remove "bad words"
        $value = str_replace(self::$badWords, ' ', $value);
        $value = trim($value);

        //convert to ascii characters
        $value = self::toASCII($value);

        //remove any duplicate whitespace, and ensure all characters are alphanumeric
        $value = preg_replace(array('/\s+/', '/[^A-Za-z0-9\-]/'), array('-', ''), $value);

        //limit length
        if (strlen($value) > 100)
        {
            $value = substr($value, 0, 100);
        }

        return $value;
    }

    public static function toASCII($value)
    {
        $string = htmlentities(utf8_decode($value));
        $string = preg_replace(
            array('/&szlig;/', '/&(..)lig;/', '/&([aouAOU])uml;/', '/&(.)[^;]*;/'),
            array('ss', "$1", "$1" . 'e', "$1"),
            $string);

        return $string;
    }

    public static function filterText($text, \JUser $user = null)
    {
        static $filterMethod = null;

        if (is_null($filterMethod))
        {
            $filterMethod = ComponentParams::getParam('filtermethod', 'htmlpurifier');
            if (!in_array($filterMethod, array('joomla', 'htmlpurifier', 'hackme')))
            {
                $filterMethod = 'htmlpurifier';
            }
        }

        if ($filterMethod == 'hackme')
        {
            return $text;
        }
        elseif ($filterMethod == 'htmlpurifier')
        {
            return self::filterTextHtmlpurifier($text, $user);
        }
        else
        {
            return self::filterTextJoomla($text, $user);
        }
    }

    public static function filterTextJoomla($text, \JUser $user = null)
    {
        if (!is_object($user))
        {
            $container = Container::getInstance('com_ats');
            $user      = $container->platform->getUser();
        }

        $userGroups = \JAccess::getGroupsByUser($user->get('id'));

        \JLoader::import('joomla.application.component.helper');

        $config = \JComponentHelper::getParams('com_config');
        $filters = $config->get('filters');

        $blackListTags       = array();
        $blackListAttributes = array();

        $customListTags       = array();
        $customListAttributes = array();

        $whiteListTags       = array();
        $whiteListAttributes = array();

        $noHtml     = false;
        $whiteList  = false;
        $blackList  = false;
        $customList = false;
        $unfiltered = false;

        $countNoHtml = 0;
        $countRaw    = 0;
        $countOther  = 0;

        // Cycle through each of the user groups the user is in.
        // Remember they are included in the Public group as well.
        foreach ($userGroups as $groupId)
        {
            // May have added a group but not saved the filters.
            if (!isset($filters->$groupId))
            {
                continue;
            }

            // Each group the user is in could have different filtering properties.
            $filterData = $filters->$groupId;
            $filterType = strtoupper($filterData->filter_type);

            if ($filterType == 'NH')
            {
                // Maximum HTML filtering.
                $countNoHtml++;
            }
            elseif ($filterType == 'NONE')
            {
                // No HTML filtering.
                $countRaw++;
            }
            else
            {
                // Black, white or custom list.
                $countOther++;
                // Preprocess the tags and attributes.
                $tags = explode(',', $filterData->filter_tags);
                $attributes = explode(',', $filterData->filter_attributes);
                $tempTags = array();
                $tempAttributes = array();

                foreach ($tags as $tag)
                {
                    $tag = trim($tag);

                    if ($tag)
                    {
                        $tempTags[] = $tag;
                    }
                }

                foreach ($attributes as $attribute)
                {
                    $attribute = trim($attribute);

                    if ($attribute)
                    {
                        $tempAttributes[] = $attribute;
                    }
                }

                // Collect the black or white list tags and attributes.
                // Each lists is cummulative.
                if ($filterType == 'BL')
                {
                    $blackList = true;
                    $blackListTags = array_merge($blackListTags, $tempTags);
                    $blackListAttributes = array_merge($blackListAttributes, $tempAttributes);
                }
                elseif ($filterType == 'CBL')
                {
                    // Only set to true if Tags or Attributes were added
                    if ($tempTags || $tempAttributes)
                    {
                        $customList = true;
                        $customListTags = array_merge($customListTags, $tempTags);
                        $customListAttributes = array_merge($customListAttributes, $tempAttributes);
                    }
                }
                elseif ($filterType == 'WL')
                {
                    $whiteList = true;
                    $whiteListTags = array_merge($whiteListTags, $tempTags);
                    $whiteListAttributes = array_merge($whiteListAttributes, $tempAttributes);
                }
            }
        }

        // If any group defines no filtering, disable filtering
        if ($countRaw)
        {
            $unfiltered = true;
        }
        // If any group defines No HTML and the other groups do not define a more lax filtering, strip all tags
        elseif ($countNoHtml && !$countOther)
        {
            $noHtml = true;
        }
        // Otherwise we will just sanitize the HTML

        // Remove duplicates before processing (because the black list uses both sets of arrays).
        $blackListTags        = array_unique($blackListTags);
        $blackListAttributes  = array_unique($blackListAttributes);
        $customListTags       = array_unique($customListTags);
        $customListAttributes = array_unique($customListAttributes);
        $whiteListTags        = array_unique($whiteListTags);
        $whiteListAttributes  = array_unique($whiteListAttributes);

        // Unfiltered assumes first priority.
        if ($unfiltered)
        {
            // Don't apply filtering.
        }
        elseif ($noHtml)
        {
            return strip_tags($text);
        }
        else
        {
            // Custom blacklist precedes Default blacklist
            if ($customList)
            {
                $filter = \JFilterInput::getInstance(array(), array(), 1, 1);

                // Override filter's default blacklist tags and attributes
                if ($customListTags)
                {

                    $filter->tagBlacklist = $customListTags;
                }
                if ($customListAttributes)
                {
                    $filter->attrBlacklist = $customListAttributes;
                }
            }
            // Black lists take third precedence.
            elseif ($blackList)
            {
                // Remove the white-listed attributes from the black-list.
                $filter = \JFilterInput::getInstance(
                    array_diff($blackListTags, $whiteListTags), // blacklisted tags
                    array_diff($blackListAttributes, $whiteListAttributes), // blacklisted attributes
                    1, // blacklist tags
                    1 // blacklist attributes
                );

                // Remove white listed tags from filter's default blacklist
                if ($whiteListTags)
                {
                    $filter->tagBlacklist = array_diff($filter->tagBlacklist, $whiteListTags);
                }

                // Remove white listed attributes from filter's default blacklist
                if ($whiteListAttributes)
                {
                    $filter->attrBlacklist = array_diff($filter->attrBlacklist, $whiteListAttributes);
                }
            }
            // White lists take fourth precedence.
            elseif ($whiteList)
            {
                $filter = \JFilterInput::getInstance($whiteListTags, $whiteListAttributes, 0, 0, 0); // turn off xss auto clean
            }
            // No HTML takes last place.
            else
            {
                $filter = \JFilterInput::getInstance();
            }

            // JFilterInput throws a gazillion strict standards warnings when it
            // encounters slightly screwed up HTML. Let's prevent an information
            // disclosure, shall we?
            $error_reporting = error_reporting(0);
            $text = $filter->clean($text, 'html');
            error_reporting($error_reporting);
        }

        return $text;
    }

    public static function filterTextHtmlpurifier($text, \JUser $user = null)
    {
        $container = Container::getInstance('com_ats');

        if (!is_object($user))
        {
            $user = $container->platform->getUser();
        }

        $userGroups = \JAccess::getGroupsByUser($user->get('id'));

        $config = \JComponentHelper::getParams('com_config');
        $filters = $config->get('filters');

        $blackListTags       = array();
        $blackListAttributes = array();

        $customListTags       = array();
        $customListAttributes = array();

        $whiteListTags       = array();
        $whiteListAttributes = array();

        $noHtml     = false;
        $whiteList  = false;
        $blackList  = false;
        $customList = false;
        $unfiltered = false;

        $countNoHtml = 0;
        $countRaw    = 0;
        $countOther  = 0;

        // Cycle through each of the user groups the user is in.
        // Remember they are included in the Public group as well.
        foreach ($userGroups as $groupId)
        {
            // May have added a group but not saved the filters.
            if (!isset($filters->$groupId))
            {
                continue;
            }

            // Each group the user is in could have different filtering properties.
            $filterData = $filters->$groupId;
            $filterType = strtoupper($filterData->filter_type);

            switch ($filterType)
            {
                case 'NH':
                    $countNoHtml++;
                    break;

                case 'NONE':
                    $countRaw++;
                    break;

                default:
                    $countOther++;
                    break;
            }
        }

        // If any group defines no filtering, disable filtering
        if ($countRaw)
        {
            $unfiltered = true;
        }
        // If any group defines No HTML and the other groups do not define a more lax filtering, strip all tags
        elseif ($countNoHtml && !$countOther)
        {
            $noHtml = true;
        }
        // Otherwise we will just sanitize the HTML

        // Remove duplicates before processing (because the black list uses both sets of arrays).
        $blackListTags = array_unique($blackListTags);
        $blackListAttributes = array_unique($blackListAttributes);
        $customListTags = array_unique($customListTags);
        $customListAttributes = array_unique($customListAttributes);
        $whiteListTags = array_unique($whiteListTags);
        $whiteListAttributes = array_unique($whiteListAttributes);

        // Unfiltered assumes first priority.
        if ($unfiltered)
        {
            return $text;
        }

        // No HTML - strip tags and get done with it
        if ($noHtml)
        {
            return strip_tags($text);
        }

        // Make sure HTML Purifier is loaded
        if (!class_exists('HTMLPurifier'))
        {
            $hpinclude = ComponentParams::getParam('htmlpurifier_include', 1);
            if ($hpinclude)
            {
                require_once JPATH_ADMINISTRATOR . '/components/com_ats/assets/htmlpurifier/HTMLPurifier.includes.php';
            }
            else
            {
                require_once JPATH_ADMINISTRATOR . '/components/com_ats/assets/htmlpurifier/HTMLPurifier.auto.php';
            }
        }

        // Set up HTML Purifier
        $config = \HTMLPurifier_Config::createDefault();
        $config->set('Core.Encoding', 'UTF-8');
        $config->set('HTML.Doctype', 'HTML 4.01 Transitional');

        if (is_writable(JPATH_CACHE))
        {
            $config->set('Cache.SerializerPath', JPATH_CACHE);
        }
        else
        {
            $config->set('Core.DefinitionCache', null);
        }

        //$config->set('AutoFormat.AutoParagraph', true);
        $string = ComponentParams::getParam('htmlpurifier_configstring', '');
        $string = trim($string);

        if (empty($string))
        {
            $string = 'p,b,a[href],i,u,strong,em,small,big,span[style],font[size],font[color],ul,ol,li,br,img[src],img[width],img[height],code,pre,blockquote';
        }

        $config->set('HTML.Allowed', $string);

        // Clean the HTML
        $purifier = new \HTMLPurifier($config);
        $clean_html = $purifier->purify($text);

        return $clean_html;
    }
}