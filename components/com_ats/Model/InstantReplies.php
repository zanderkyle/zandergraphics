<?php
/**
 * @package   ats
 * @copyright Copyright (c)2011-2014 Nicholas K. Dionysopoulos / AkeebaBackup.com
 * @license   GNU GPL v3 or later
 */

namespace Akeeba\TicketSystem\Site\Model;

use Akeeba\TicketSystem\Admin\Helper\ComponentParams;
use Akeeba\TicketSystem\Admin\Model\DefaultDataModel;
use FOF30\Container\Container;

defined('_JEXEC') or die;

class InstantReplies extends DefaultDataModel
{
    public function __construct(Container $container, array $config = array())
    {
        // Let's fake a table for this model, so it won't complain
        $config['idFieldName'] = 'ats_attempt_id';
        $config['tableName']   = '#__ats_attempts';

        parent::__construct($container, $config);
    }

	public function &getItemsArray($limitstart = 0, $limit = 0, $overrideLimits = false)
	{
		$showChrome            = ComponentParams::getParam('instantreplies_sitechrome', 1);
		$additionalQueryParams = $showChrome ? '' : '&tmpl=component';

		$results   = array();
		$docimport = array();
		$tickets   = array();

		$search = $this->getState('search', '');

		if (empty($search))
		{
			return $results;
		}

		if (strlen($search) < 6)
		{
			return $results;
		}

        // Make sure we have at least one valid value
		$dicats = implode(',', $this->getState('dicats', array(0)));

		$db = $this->getDBO();

        // Fetching results from DocImport pages
		$query = $db->getQuery(true)
			->select(array(
				$db->qn('docimport_article_id') . ' AS ' . $db->qn('id'),
				$db->qn('docimport_category_id') . ' AS ' . $db->qn('catid'),
				$db->qn('title'),
				$db->qn('fulltext'),
				'MATCH(' . $db->qn('fulltext') . ') AGAINST (' . $db->q($search) . ') as ' . $db->qn('score')
			))->from($db->qn('#__docimport_articles'))
			->where($db->qn('docimport_category_id') . ' IN (' . $dicats . ')')
			->where('MATCH(' . $db->qn('fulltext') . ') AGAINST (' . $db->q($search) . ')');

        try
        {
            $raw_results = $db->setQuery($query, 0, 10)->loadObjectList();
        }
        catch(\Exception $e)
        {
            // If we don't have DocImport installed the query fails: let's initialize an empty array
            $raw_results = array();
        }

		foreach ($raw_results as $row)
		{
			$introtext = substr(strip_tags($row->fulltext), 0, 150) . '&hellip;';

			$url = \JRoute::_('index.php?option=com_docimport&view=article&id=' . $row->id . $additionalQueryParams);

			$docimport[$row->score] = (object)array(
				'title'   => $row->title,
				'preview' => $introtext,
				'url'     => $url,
				'source'  => 'docimport'
			);
		}

        // Fetching results from closed tickets
		$query = $db->getQuery(true)
			->select(array(
				$db->qn('tickets') . '.' . $db->qn('ats_ticket_id'),
				$db->qn('tickets') . '.' . $db->qn('title'),
				$db->qn('content_html'),
				'MATCH(' . $db->qn('content_html') . ') AGAINST (' . $db->q($search) . ') as ' . $db->qn('score')
			))
			->from($db->qn('#__ats_posts') . ' AS ' . $db->qn('posts'))
			->innerJoin($db->qn('#__ats_tickets') . ' ' . $db->qn('tickets') . ' on ' . $db->qn('posts') . '.' . $db->qn('ats_ticket_id') . ' = ' .
				$db->qn('tickets') . '.' . $db->qn('ats_ticket_id'))
			->where($db->qn('public') . ' = ' . $db->q(1))
			->where($db->qn('catid') . ' = ' . $db->q($this->input->getInt('catid')))
			->where($db->qn('tickets') . '.' . $db->qn('enabled') . ' = ' . $db->q(1))
			->where('MATCH(' . $db->qn('content_html') . ') AGAINST (' . $db->q($search) . ')');

		$status[] = $db->qn('status') . ' = ' . $db->q('C');

		$pending = '(' . $db->qn('status') . ' != ' . $db->q('C');

		if (ComponentParams::getParam('instantreplies_daylimit', 0))
		{
			$daysago = new \JDate('-' . ComponentParams::getParam('instantreplies_daylimit', 0) . ' days');
			$pending .= ' AND ' . $db->qn('tickets') . '.' . $db->qn('modified_on') . ' >= ' . $db->q($daysago->toSql());
		}

		$status[] = $pending . ')';
		$query->where('(' . implode(' OR ', $status) . ')');

		$raw_results = $db->setQuery($query, 0, 10)->loadObjectList();

		foreach ($raw_results as $row)
		{
			$preview = substr(strip_tags($row->content_html), 0, 150) . '&hellip;';

			$url = \JRoute::_('index.php?option=com_ats&view=Ticket&id=' . $row->ats_ticket_id . $additionalQueryParams);

			$tickets[$row->score] = (object)array(
				'title'   => $row->title,
				'preview' => $preview,
				'url'     => $url,
				'source'  => 'ats'
			);
		}

		// Merge the results ordered by score and pick only the first 10 items
		$sort_results = array_merge($docimport, $tickets);
		krsort($sort_results);
		$sort_results = array_slice($sort_results, 0, 10);

		// Ok, now they're sorted by key, so I can add them to the result array in order to have int keys
		foreach ($sort_results as $result)
		{
			$results[] = $result;
		}

		return $results;
	}
}