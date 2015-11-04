<?php
/**
 * @package ats
 * @copyright Copyright (c)2011-2014 Nicholas K. Dionysopoulos / AkeebaBackup.com
 * @license GNU GPL v3 or later
 */

namespace Akeeba\TicketSystem\Site\Helper;

use Akeeba\TicketSystem\Site\Model\Tickets;
use FOF30\Container\Container;
use JFactory;
use JText;

defined('_JEXEC') or die;

class Breadcrumbs
{
	private static $iHaveRun = false;

	public static function buildCrumbs()
	{
		if(self::$iHaveRun)
        {
            return;
        }

		self::$iHaveRun = true;

        $container = Container::getInstance('com_ats');

		$mObject = JFactory::getApplication()->getMenu()->getActive();
		$mQuery  = is_object($mObject) ? $mObject->query : array();
		$Itemid  = is_object($mObject) ? $mObject->id : null;

		if(!array_key_exists('view',$mQuery))
        {
            $mQuery['view'] = '';
        }

		// Get the menu and actual view
		$mView = $mQuery['view'];
		$aView = $container->input->getCmd('view');

		// Do I have to leave breadcrumbs behind me?
		$leaveCatCrumbs  = in_array($aView, array('ticket','tickets','newticket'));
		$leaveItemCrumbs = $mView != $aView;

		if($leaveCatCrumbs)
        {
			// On these views we have to check the category IDs to make sure they match
			$mCategory = array_key_exists('category', $mQuery) ? $mQuery['category'] : 0;
			$catID     = $container->input->getInt('category', 0);

			// If it's a ticket view, I have to pick the catID from the ticket
			if($aView == 'ticket')
            {
				$ticketID = $container->input->getInt('id',0);
                /** @var Tickets $ticket */
                $ticket = $container->factory->model('Tickets')->tmpInstance();
				$ticket->load($ticketID);

				$catID = $ticket->catid;
			}

			$leaveCatCrumbs = $mCategory != $catID;
		}

		// Don't continue if breadcrumbs are not necessary
		if(!$leaveItemCrumbs && !$leaveCatCrumbs)
        {
            return;
        }

		if($leaveCatCrumbs)
        {
			// Get category
			$db = $container->db;
			$q = $db->getQuery(true)
                    ->select('path')
                    ->from($db->qn('#__categories'))
                    ->where($db->qn('id').' = '.$db->q($catID))
                    ->where($db->qn('extension').' = '.$db->q('com_ats'));
			$path = $db->setQuery($q)->loadResult();

			// Get all categories, by path
			$q = $db->getQuery(true)
				->select(array(
					$db->qn('path'), $db->qn('id'), $db->qn('title')
				))
				->from($db->qn('#__categories'))
				->where($db->qn('id').' = '.$db->q($catID))
				->where($db->qn('extension').' = '.$db->q('com_ats'));

			$categoriesRaw = $db->setQuery($q)->loadAssocList('path');

			$categories       = array();
			$categoriesTitles = array();

			foreach($categoriesRaw as $p => $c)
            {
				$categories[$p] = $c['id'];
				$categoriesTitles[$p] = $c['title'];
			}

			// Break it down
			$pathParts = explode('/',$path);

			// Iterate it into an array cat ID => path
			$catParts = array();
			$temp     = array();

			foreach($pathParts as $p)
            {
				$temp[] = $p;
				$tempPath = implode('/',$temp);
				$tempID = array_key_exists($tempPath, $categories) ? $categories[$tempPath] : 0;
				$catParts[$tempPath] = $tempID;
			}

			// Check if the $mCategory is in the array keys. If so, remove
			// elements before it.
			$mCategory = array_key_exists('category', $mQuery) ? $mQuery['category'] : 0;

			if($mCategory && array_key_exists($mCategory, $catParts))
            {
				$temp = array();
				$start = false;

				foreach($catParts as $p => $i)
                {
					if($i == $mCategory)
                    {
                        $start = true;
                    }

					if($start)
                    {
						$temp[$p] = $i;
					}
				}
				$catParts = $temp;
			}

			// If there are still elements, add them to the breadcrumbs
			if(!empty($catParts))
            {
                foreach($catParts as $path => $catid)
                {
                    if(!array_key_exists($path, $categoriesTitles))
                    {
                        continue;
                    }

                    $label = $categoriesTitles[$path];
                    $url   = 'index.php?option=com_ats&view=Tickets&category='.$catid;

                    if($Itemid)
                    {
                        $url .= '&Itemid='.$Itemid;
                    }

                    $url = \JRoute::_($url);

                    JFactory::getApplication()->getPathway()->addItem($label, $url);
                }
            }
		}

		if($leaveItemCrumbs)
        {
			// This has to be a new or existing
			if($aView == 'newticket')
            {
				JFactory::getApplication()->getPathway()->addItem(JText::_('COM_ATS_BREADCRUMBS_NEWTICKET'));
			}
            elseif($container->input->getString('view') == 'ticket')
            {
				$ticketID = $container->input->getInt('id',0);
                /** @var Tickets $ticket */
                $ticket = $container->factory->model('Tickets')->tmpInstance();
                $ticket->load($ticketID);

				JFactory::getApplication()->getPathway()->addItem(
						'#'.$ticket->ats_ticket_id.': '.
						$ticket->title
				);
			}
		}
	}
}