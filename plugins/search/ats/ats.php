<?php
/**
 * @package ats
 * @copyright Copyright (c)2011-2014 Nicholas K. Dionysopoulos / AkeebaBackup.com
 * @license GNU GPL v3 or later
 */

defined('_JEXEC') or die();

JLoader::import('joomla.plugin.plugin');

/**
 * ATS Search plugin
 */
class plgSearchAts extends JPlugin
{
	/**
	 * Constructor
	 *
	 * @access      protected
	 * @param       object  $subject The object to observe
	 * @param       array   $config  An array that holds the plugin configuration
	 */
	public function __construct(& $subject, $config)
	{
		parent::__construct($subject, $config);

		$this->loadLanguage();
	}

	/**
	 * @return array An array of search areas
	 */
	public function onContentSearchAreas()
    {
		static $areas = array(
			'ats' => 'PLG_SEARCH_ATS_ATS'
		);

		return $areas;
	}

    /**
     * ATS Search method
     *
     * The sql must return the following fields that are used in a common display
     * routine: href, title, section, created, text, browsernav
     *
     * @param   string  $text       Target search string
     * @param   string  $phrase     Matching option, exact|any|all
     * @param   string  $ordering   Ordering option, newest|oldest|popular|alpha|category
     * @param   mixed   $areas      An array if the search it to be restricted to areas, null if search all
     *
     * @return array
     */
	public function onContentSearch($text, $phrase='', $ordering='', $areas=null)
	{
		$db		= JFactory::getDbo();
		$app	= JFactory::getApplication();
		$user	= JFactory::getUser();
		$groups	= implode(',', $user->getAuthorisedViewLevels());

		$searchText = $text;

		if (is_array($areas))
        {
			if (!array_intersect($areas, array_keys($this->onContentSearchAreas())))
            {
				return array();
			}
		}

		$limit = $this->params->get('search_limit',	50);
		$text  = trim($text);

		if ($text == '')
        {
			return array();
		}

		$section = JText::_('PLG_SEARCH_ATS_ATS');

		$query = $db->getQuery(true);

		switch ($phrase)
		{
			case 'exact':
				$text		= $db->Quote('%'.$db->escape($text, true).'%', false);
				$query->where(
					'(('.$db->qn('t').'.'.$db->qn('title').' LIKE '.$text.')'
					.' OR '.
					'('.$db->qn('p').'.'.$db->qn('content_html').' LIKE '.$text.'))'
				);
				break;

			case 'all':
			default:
				$words	= explode(' ', $text);
				foreach ($words as $word)
				{
					$word		= $db->Quote('%'.$db->escape($word, true).'%', false);
					$query->where(
						'(('.$db->qn('t').'.'.$db->qn('title').' LIKE '.$word.')'
						.' OR '.
						'('.$db->qn('p').'.'.$db->qn('content_html').' LIKE '.$word.'))'
					);
				}
				break;

			case 'any':
				$words	= explode(' ', $text);
				foreach ($words as $word)
				{
					$word		= $db->Quote('%'.$db->escape($word, true).'%', false);
					$query->where(
						'(('.$db->qn('t').'.'.$db->qn('title').' LIKE '.$word.')'
						.' OR '.
						'('.$db->qn('p').'.'.$db->qn('content_html').' LIKE '.$word.'))',
						'OR'
					);
				}
				break;
		}

		switch ($ordering)
		{
			case 'oldest':
				$order = 'p.created_on ASC';
				break;

			case 'popular':
				// @todo Implement a hits field
				$order = 'p.created_on DESC';
				break;

			case 'alpha':
				$order = 't.title ASC';
				break;

			case 'category':
				$order = 'c.title ASC, t.title ASC';
				break;

			case 'newest':
			default:
				$order = 'p.created_on DESC';
		}

		$query->select(array(
			$db->qn('p').'.'.$db->qn('ats_post_id'),
			$db->qn('p').'.'.$db->qn('ats_ticket_id'),
			$db->qn('t').'.'.$db->qn('title').' AS '.$db->qn('title'),
			'CONCAT_WS(" / ", '.$db->q($section).', c.title) AS section',
			$db->qn('p').'.'.$db->qn('created_on').' AS '.$db->qn('created'),
			$db->qn('p').'.'.$db->qn('content_html').' AS '.$db->qn('text'),
			$db->q('1').' AS '.$db->qn('browsernav')
		))->from($db->qn('#__ats_posts').' AS '.$db->qn('p'))
		->join('INNER',
			$db->qn('#__ats_tickets').' AS '.$db->qn('t').' ON('.
			$db->qn('t').'.'.$db->qn('ats_ticket_id').' = '.
			$db->qn('p').'.'.$db->qn('ats_ticket_id').')'
		)->join('INNER',
			$db->qn('#__categories').' AS '.$db->qn('c').' ON('.
			$db->qn('c').'.'.$db->qn('id').' = '.
			$db->qn('t').'.'.$db->qn('catid').')'
		)
		->where('p.enabled = 1')
		->where('t.enabled = 1')
		->where('c.published = 1')
		->where('t.public = 1')
		->order($order);
		;

		// Just checkin' that the user ain't a Suepr Administrator
		if(!$user->authorise('core.admin'))
        {
			$query->where($db->qn('c').'.'.$db->qn('access').' IN ('.$groups.')');
		}

		// Filter by language
		if ($app->isSite() && $app->getLanguageFilter())
        {
			$tag = JFactory::getLanguage()->getTag();
			$query->where('c.language in (' . $db->Quote($tag) . ',' . $db->Quote('*') . ')');
		}

		$db->setQuery($query, 0, $limit);
		$rows = $db->loadObjectList();

		$return = array();

		if ($rows)
        {
			foreach($rows as $key => $row)
            {
				$rows[$key]->href = JRoute::_('index.php?option=com_ats&view=ticket&id='.$row->ats_ticket_id).'#p'.$row->ats_post_id;
			}

			foreach($rows as $key => $article)
            {
				if (SearchHelper::checkNoHTML($article, $searchText, array('url', 'text', 'title')))
                {
					$return[] = $article;
				}
			}
		}

		return $return;
	}
}