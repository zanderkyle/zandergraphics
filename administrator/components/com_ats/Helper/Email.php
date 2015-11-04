<?php
/**
 * @package   AkeebaTicketSystem
 * @copyright Copyright (c)2011-2015 Nicholas K. Dionysopoulos
 * @license   GNU General Public License version 3, or later
 */

namespace Akeeba\TicketSystem\Admin\Helper;

use Akeeba\TicketSystem\Admin\Utils\Mailer;
use FOF30\Container\Container;
use JFactory;
use JFile;
use JMailHelper;
use JUri;

defined('_JEXEC') or die();

class Email
{
    // @todo This function should be moved inside email templates model
	public static function loadEmailTemplateFromDB($key)
	{
		// Initialise
		$templateText = '';
		$subject      = '';

		// Look for desired languages
		$jLang = JFactory::getLanguage();
		$userLang = JFactory::getUser()->getParam('language','');
		$languages = array(
			$userLang, $jLang->getTag(), $jLang->getDefault(), 'en-GB', '*'
		);

		// Look for an override in the database
		$db = JFactory::getDbo();
		$query = $db->getQuery(true)
					->select('*')
					->from($db->qn('#__ats_emailtemplates'))
					->where($db->qn('key').'='.$db->q($key))
					->where($db->qn('enabled').'='.$db->q(1))
		;
		$db->setQuery($query);
		$allTemplates = $db->loadObjectList('language');

		// Try finding the most relevant language override and load it
		$loadLanguage = null;

		foreach($languages as $lang)
		{
			if(!array_key_exists($lang, $allTemplates)) continue;

			if($lang != '*') $loadLanguage = $lang;

			$subject = $allTemplates[$lang]->subject;
			$templateText = $allTemplates[$lang]->template;

			// If I found a match for this $languages key, it has higher
			// affinity than the next entries on the list, therefore I needn't
			// look further.
			break;
		}

		return array($subject, $templateText, $loadLanguage);
	}

	public static function &getMailer()
	{
		$mailer = new Mailer();

		$conf     = JFactory::getConfig();
		$smtpauth = ($conf->get('smtpauth') == 0) ? null : 1;
		$mailfrom = $conf->get('mailfrom');
		$fromname = $conf->get('fromname');
		$mail     = $conf->get('mailer');

		$mailer->SetFrom(JMailHelper::cleanLine($mailfrom), JMailHelper::cleanLine($fromname), 0);

		switch ($mail)
		{
			case 'smtp':
				$smtpuser = $conf->get('smtpuser');
				$smtppass = $conf->get('smtppass');
				$smtphost = $conf->get('smtphost');
				$smtpsecure = $conf->get('smtpsecure');
				$smtpport = $conf->get('smtpport');
				$mailer->useSMTP($smtpauth, $smtphost, $smtpuser, $smtppass, $smtpsecure, $smtpport);
				break;

			case 'sendmail':
				$mailer->IsSendmail();
				break;

			default:
				$mailer->IsMail();
				break;
		}

		$mailer->IsHTML(true);
		// Required in order not to get broken characters
		$mailer->CharSet = 'UTF-8';

		return $mailer;
	}

    /**
     * Parses template text and subject with email variables
     *
     * @TODO Abastract this method and instead of working by reference, simply return the parsed text
     *
     * @param   string   $templateText
     * @param   string   $subject
     * @param   array    $mailInfo
     * @param   JMail    $mailer        If set to null, this function will return values, instead of setting them to the mailer
     *
     * @return  bool|array  If a mailer is passed, it returns true, otherwise it will return parsed subject and body
     */
    public static function parseTemplate($templateText, $subject, $mailInfo, &$mailer = null )
	{
		// Email variables
        $return    = array();
		$container = Container::getInstance('com_ats');
		$isCli     = $container->platform->isCli();

		if($isCli)
        {
			\JLoader::import('joomla.application.component.helper');

			$baseURL    = \JComponentHelper::getParams('com_ats')->get('siteurl','http://www.example.com');
			$temp       = str_replace('http://', '', $baseURL);
			$temp       = str_replace('https://', '', $temp);
			$parts      = explode($temp, '/', 2);
			$subpathURL = count($parts) > 1 ? $parts[1] : '';
		}
        else
        {
			$baseURL    = JURI::base();
			$subpathURL = JURI::base(true);
		}

		$baseURL    = str_replace('/administrator', '', $baseURL);
		$subpathURL = str_replace('/administrator', '', $subpathURL);
        $sitename   = JFactory::getConfig()->get('sitename');

		$emailVars = array(
			'sitename'	=> $sitename,
			'siteurl'	=> $baseURL,
		);

		if(is_array($mailInfo) && !empty($mailInfo))
        {
			$emailVars = array_merge($emailVars, $mailInfo);
		}

		// Perform substitutions
		foreach($emailVars as $key => $value)
        {
			$tag = '['.strtoupper($key).']';
			$templateText = str_replace($tag, $value, $templateText);
			$subject = str_replace($tag, $value, $subject);
		}

        if($mailer)
        {
            $mailer->setSubject($subject);
        }
        else
        {
            $return['subject'] = $subject;
        }


		// Include inline images
		$pattern           = '/(src)=\"([^"]*)\"/i';
		$number_of_matches = preg_match_all($pattern, $templateText, $matches, PREG_OFFSET_CAPTURE);

		if($number_of_matches > 0)
        {
			$substitutions = $matches[2];
			$last_position = 0;
			$temp          = '';

			// Loop all URLs
			$imgidx    = 0;
			$imageSubs = array();
			foreach($substitutions as &$entry)
			{
				// Copy unchanged part, if it exists
				if($entry[1] > 0)
					$temp .= substr($templateText, $last_position, $entry[1]-$last_position);
				// Examine the current URL
				$url = $entry[0];
				if( (substr($url,0,7) == 'http://') || (substr($url,0,8) == 'https://') )
                {
					// External link, skip
					$temp .= $url;
				}
                else
                {
					$ext = strtolower(JFile::getExt($url));
					if(!JFile::exists($url))
                    {
						// Relative path, make absolute
                        // @TODO $template var seems undefined...
						$url = dirname($template).'/'.ltrim($url,'/');
					}

					if( !JFile::exists($url) || !in_array($ext, array('jpg','png','gif')) )
                    {
						// Not an image or inexistent file
						$temp .= $url;
					}
                    else
                    {
						// Image found, substitute
						if(!array_key_exists($url, $imageSubs)) {
							// First time I see this image, add as embedded image and push to
							// $imageSubs array.
							$imgidx++;
							$mailer->AddEmbeddedImage($url, 'img'.$imgidx, basename($url));
							$imageSubs[$url] = $imgidx;
						}
						// Do the substitution of the image
						$temp .= 'cid:img'.$imageSubs[$url];
					}
				}

				// Calculate next starting offset
				$last_position = $entry[1] + strlen($entry[0]);
			}
			// Do we have any remaining part of the string we have to copy?
			if($last_position < strlen($templateText))
				$temp .= substr($templateText, $last_position);
			// Replace content with the processed one
			$templateText = $temp;
		}

        if($mailer)
        {
            $mailer->setBody($templateText);
        }
        else
        {
            $return['body'] = $templateText;
        }

        return $return;
	}
}