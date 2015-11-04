<?php
/**
 * @package ats
 * @copyright Copyright (c)2011-2014 Nicholas K. Dionysopoulos / AkeebaBackup.com
 * @license GNU GPL v3 or later
 */

// Protect from unauthorized access

defined('_JEXEC') or die();

JLoader::import('joomla.plugin.plugin');
JLoader::import('joomla.application.component.helper');

// Load the base adapter.
require_once JPATH_ADMINISTRATOR . '/components/com_finder/helpers/indexer/adapter.php';

/**
 * ATS Smart Search plugin
 */

class plgFinderAts extends FinderIndexerAdapter
{
	/**
	 * The plugin identifier.
	 * @var    string
	 */
	protected $context = 'Support';

	/**
	 * The extension name.
	 * @var    string
	 */
	protected $extension = 'com_ats';

	/**
	 * The sublayout to use when rendering the results.
	 * @var    string
	 */
	protected $layout = 'ticket';

	/**
	 * The type of content that the adapter indexes.
	 * @var    string
	 */
	protected $type_title = 'Ticket';

	/**
	 * The table name.
	 * @var    string
	 */
	protected $table = '#__ats_posts';

	/**
	 * The field the published state is stored in.
	 * @var    string
	 */
	protected $state_field = 'enabled';

	/**
	 * Constructor
	 *
	 * @param   object  &$subject  The object to observe
	 * @param   array   $config    An array that holds the plugin configuration
	 */
	public function __construct(&$subject, $config)
	{
		parent::__construct($subject, $config);

		$this->loadLanguage();
	}

	/**
	 * Method to update the item link information when the item category is
	 * changed. This is fired when the item category is published or unpublished
	 * from the list view.
	 *
	 * @param   string   $extension  The extension whose category has been updated.
	 * @param   array    $pks        A list of primary key ids of the content that has changed state.
	 * @param   integer  $value      The value of the state that the content has been changed to.
	 *
	 * @return  void
	 */
	public function onFinderCategoryChangeState($extension, $pks, $value)
	{
		// Make sure we're handling com_content categories
		if ($extension == 'com_ats')
		{
			$this->categoryStateChange($pks, $value);
		}
	}

	/**
	 * Method to remove the link information for items that have been deleted.
	 *
	 * @param   string  $context  The context of the action being performed.
	 * @param   JTable  $table    A JTable object containing the record to be deleted
	 *
	 * @return  boolean  True on success.
	 *
	 * @since   2.5
	 * @throws  Exception on database error.
	 */
	public function onFinderAfterDelete($context, $table)
	{
		if ($context == 'com_ats.post')
		{
			$id = $table->ats_post_id;
		}
		elseif ($context == 'com_finder.index')
		{
			$id = $table->link_id;
		}
		else
		{
			return true;
		}
		// Remove the items.
		return $this->remove($id);
	}

	/**
	 * Method to determine if the access level of an item changed.
	 *
	 * @param   string   $context  The context of the content passed to the plugin.
	 * @param   JTable   $row      A JTable object
	 * @param   boolean  $isNew    If the content has just been created
	 *
	 * @return  boolean  True on success.
	 *
	 * @since   2.5
	 * @throws  Exception on database error.
	 */
	public function onFinderAfterSave($context, $row, $isNew)
	{
		// We only want to handle posts here
		if ($context == 'com_ats.post')
		{
            $container = FOF30\Container\Container::getInstance('com_ats');

            /** @var \Akeeba\TicketSystem\Admin\Model\Tickets $ticket */
            $ticket = $container->factory->model('Tickets')->tmpInstance();
            $ticket->load($row->ats_ticket_id);

			if(!$ticket->public)
            {
				if($isNew)
                {
					// Skip private tickets if they are new
					return true;
				}
                else
                {
					// Remove private tickets if the are not new
					$this->remove($row->ats_ticket_id);

					return true;
				}
			}
            else
            {
				// Reindex the item
				$this->reindex($row->ats_post_id);
			}
		}

		return true;
	}

	/**
	 * Method to update the link information for items that have been changed
	 * from outside the edit screen. This is fired when the item is published,
	 * unpublished, archived, or unarchived from the list view.
	 *
	 * @param   string   $context  The context for the content passed to the plugin.
	 * @param   array    $pks      A list of primary key ids of the content that has changed state.
	 * @param   integer  $value    The value of the state that the content has been changed to.
	 *
	 * @return  void
	 *
	 * @since   2.5
	 */
	public function onFinderChangeState($context, $pks, $value)
	{
		// We only want to handle posts here
		if ($context == 'com_ats.post')
		{
			$this->itemStateChange($pks, $value);
		}
		// Handle tickets visibility change
		if ($context == 'com_ats.ticket.visibility')
		{
			$in = implode(',', $pks);
			$db = Jfactory::getDbo();
			$query = $db->getQuery(true)
                        ->select($db->qn('ats_post_id'))
                        ->from($db->qn('#__ats_posts'))
                        ->where($db->qn('ats_ticket_id').' IN ('.$in.')');
			$ids = $db->setQuery($query)->loadColumn();

			if($value == 0)
            {
				foreach($ids as $post_id)
                {
					$this->remove($post_id);
				}

			}
            else
            {
				foreach($ids as $post_id)
                {
					$this->reindex($post_id);
				}
			}

		}
		// Handle when the plugin is disabled
		if ($context == 'com_plugins.plugin' && $value === 0)
		{
			$this->pluginDisable($pks);
		}
	}

	/**
	 * Method to index an item. The item must be a FinderIndexerResult object.
	 *
	 * @param   FinderIndexerResult  $item    The item to index as an FinderIndexerResult object.
	 * @param   string               $format  The item format
	 *
	 * @return  void
	 *
	 * @since   2.5
	 * @throws  Exception on database error.
	 */
	protected function index(FinderIndexerResult $item, $format = 'html')
	{
		if(!$item->public)
        {
			$this->remove($item->id);

			return;
		}

		// Check if the extension is enabled
		if (JComponentHelper::isEnabled($this->extension) == false)
		{
			return;
		}

		// Build the necessary route and path information.
		$item->url   = 'index.php?option=com_ats&view=ticket&id='.$item->ats_ticket_id.'#p'.$item->id;
		$item->route = $item->url;
		$item->path  = FinderIndexerHelper::getContentPath($item->route);

		// Translate the state. Articles should only be published if the category is published.
		$item->state = $this->translateState($item->enabled, $item->cat_state);

		$item->summary = $item->body;

		// Add the type taxonomy data.
		$item->addTaxonomy('Type', 'Ticket');

		// Add the author taxonomy data.
		if (!empty($item->author))
		{
			$item->addTaxonomy('Author', $item->author);
		}

		// Add the category taxonomy data.
		$item->addTaxonomy('Category', $item->category, $item->cat_state, $item->cat_access);

		// Index the item.
		$this->indexer->index($item);
	}

	/**
	 * Method to setup the indexer to be run.
	 *
	 * @return  boolean  True on success.
	 *
	 * @since   2.5
	 */
	protected function setup()
	{
		// Load dependent classes.
        if (!defined('FOF30_INCLUDED') && !@include_once(JPATH_LIBRARIES . '/fof30/include.php'))
        {
            throw new RuntimeException('FOF 3.0 is not installed', 500);
        }

		include_once JPATH_SITE . '/components/com_ats/router.php';

		if(!defined('JDEBUG'))
        {
			$config = new JConfig();
			define('JDEBUG', $config->debug);
		}

		return true;
	}

	/**
	 * Method to get the SQL query used to retrieve the list of content items.
	 *
	 * @param   mixed  $sql  A JDatabaseQuery object or null.
	 *
	 * @return  JDatabaseQuery  A database object.
	 *
	 * @since   2.5
	 */
	protected function getListQuery($sql = null)
	{
		$db = JFactory::getDbo();
		// Check if we can use the supplied SQL query.
		$sql = ($sql instanceof JDatabaseQuery) ? $sql : $db->getQuery(true);
		$sql->select('a.ats_post_id AS id, t.title, t.alias, "" AS summary, a.content_html AS body');
		$sql->select('a.enabled, t.catid, a.created_on AS start_date, a.created_by');
		$sql->select('a.modified_on, a.modified_by');
		$sql->select('a.ats_ticket_id, t.alias AS slug, t.public');
		$sql->select('c.title AS category, c.published AS cat_state, c.access AS cat_access');
		$sql->select('c.language AS language, c.access AS access');

		$case_when_category_alias = ' CASE WHEN ';
		$case_when_category_alias .= $sql->charLength('c.alias');
		$case_when_category_alias .= ' THEN ';
		$c_id = $sql->castAsChar('c.id');
		$case_when_category_alias .= $sql->concatenate(array($c_id, 'c.alias'), ':');
		$case_when_category_alias .= ' ELSE ';
		$case_when_category_alias .= $c_id.' END as catslug';
		$sql->select($case_when_category_alias);

		$sql->select('u.name AS author');
		$sql->from('#__ats_posts AS a');
		$sql->join('LEFT', '#__ats_tickets AS t ON t.ats_ticket_id = a.ats_ticket_id');
		$sql->join('LEFT', '#__categories AS c ON c.id = t.catid');
		$sql->join('LEFT', '#__users AS u ON u.id = a.created_by');

		return $sql;
	}

	/**
	 * Method to get the URL for the item. The URL is how we look up the link
	 * in the Finder index.
	 *
	 * @param   integer  $id         The id of the item.
	 * @param   string   $extension  The extension the category is in.
	 * @param   string   $view       The view for the URL.
	 *
	 * @return  string  The URL of the item.
	 */
	protected function getURL($id, $extension, $view)
	{
        $container = \FOF30\Container\Container::getInstance('com_ats');

        /** @var \Akeeba\TicketSystem\Admin\Model\Posts $post */
        $post = $container->factory->model('Posts')->tmpInstance();
        $post->load($id);

		$ticket_id = $post->ats_ticket_id;
		$url = 'index.php?option=' . $extension . '&view=' . $view . '&id=' . $ticket_id . '#p' . $id;

		return $url;
	}

	/**
	 * Method to get a content item to index.
	 *
	 * @param   integer  $id  The id of the content item.
	 *
	 * @return  FinderIndexerResult  A FinderIndexerResult object.
	 *
	 * @since   2.5
	 * @throws  Exception on database error.
	 */
	protected function getItem($id)
	{
		JLog::add('FinderIndexerAdapter::getItem', JLog::INFO);

		// Get the list query and add the extra WHERE clause.
		$sql = $this->getListQuery();
		$sql->where('a.' . $this->db->quoteName('ats_post_id') . ' = ' . (int) $id);

		// Get the item to index.
		$this->db->setQuery($sql);
		$row = $this->db->loadAssoc();

		// Check for a database error.
		if ($this->db->getErrorNum())
		{
			// Throw database error exception.
			throw new Exception($this->db->getErrorMsg(), 500);
		}

		// Convert the item to a result object.
		$item = JArrayHelper::toObject($row, 'FinderIndexerResult');

		// Set the item type.
		$item->type_id = $this->type_id;

		// Set the item layout.
		$item->layout = $this->layout;

		return $item;
	}
}
