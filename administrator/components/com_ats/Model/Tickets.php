<?php
/**
 * @package   AkeebaTicketSystem
 * @copyright Copyright (c)2011-2015 Nicholas K. Dionysopoulos
 * @license   GNU General Public License version 3, or later
 */

namespace Akeeba\TicketSystem\Admin\Model;

use Akeeba\TicketSystem\Admin\Helper\ComponentParams;
use Akeeba\TicketSystem\Admin\Helper\Credits;
use Akeeba\TicketSystem\Admin\Helper\Filter;
use Akeeba\TicketSystem\Admin\Helper\Permissions;
use FOF30\Container\Container;
use FOF30\Model\DataModel;
use JText;

defined('_JEXEC') or die;

/**
 * Class Tickets
 *
 * @property  int           ats_ticket_id      Primary key
 * @property  string        title              Ticket title
 * @property  string        alias              Ticket alias
 * @property  int           ats_bucket_id      Bucket id
 * @property  int           catid              Category id
 * @property  string        status             Ticket status
 * @property  array         params             JSON encoded string with ticket params (and custom fields)
 * @property  int           assigned_to        Assigned user
 * @property  int           public             Ticket visibility
 * @property  string        origin             Ticket origin
 * @property  int           priority           Ticket priority
 * @property  int           timespent          Time spent on the ticket
 *
 * @property-read Categories        joomla_category     Relation to categories
 * @property-read Posts[]           posts               Relation to ticket posts
 * @property-read ManagerNotes[]    manager_notes       Relation to manager notes
 *
 * Filters:
 *
 * @method  $this   alias($alias)           Filter by alias
 * @method  $this   status($status)         Filter by status
 * @method  $this   catid($catid)           Filter by category id
 * @method  $this   public($public)         Filter by visibility
 * @method  $this   modified_on($args)      Filter by modified_on
 * @method  $this   status_array($string)   String containing all the requested statuses
 * @method  $this   created_by($id)         Filter by creator
 *
 */
class Tickets extends DefaultDataModel
{
    private $isNew = false;

    /** @var bool   Used by the CRON jobs to prevent created_by from being overridden */
    public static $overrideuser = false;

    /** @var string The name of the current view */
    public $viewName;

    public function __construct(Container $container, array $config = array())
    {
        // Force the name of the table and the primary key. This is required because we are going to reuse this
        // model in views with a different name
        $config['tableName']   = '#__ats_tickets';
        $config['idFieldName'] = 'ats_ticket_id';

        parent::__construct($container, $config);

        $this->addBehaviour('Filters');
        $this->addBehaviour('AlwaysModified');

        $this->hasMany('posts', 'Posts', 'ats_ticket_id', 'ats_ticket_id');
        $this->hasMany('manager_notes', 'ManagerNotes', 'ats_ticket_id', 'ats_ticket_id');
        $this->hasOne('user', 'JoomlaUsers', 'created_by', 'id');
        $this->hasOne('assigned', 'JoomlaUsers', 'assigned_to', 'id');

		if (defined('ATS_PRO') && ATS_PRO)
		{
			$this->belongsTo('buckets');
		}

        $this->belongsTo('joomla_category', 'Categories', 'catid', 'id');

        // Let's run our own checks
        $this->autoChecks = false;
    }

    /**
     * @param   \JDatabaseQuery  $query
     */
    protected function onBeforeBuildQuery(\JDatabaseQuery &$query)
    {
        // Apply filtering by user. This is a relation filter, it needs to go before the main query builder fires.
        // User search feature
        $created  = $this->getState('username', null, 'string');
        $assigned = $this->getState('ass_username', null, 'string');

        if ($created)
        {
            // First get the Joomla! users fulfilling the criteria
            /** @var JoomlaUsers $users */
            $users = $this->container->factory->model('JoomlaUsers')->tmpInstance();
            $userIDs = $users->search($created)->with(array())->get(true)->modelKeys();

            // If there are user IDs, we need to filter by them
            if (!empty($userIDs))
            {
                $this->whereHas('user', function (\JDatabaseQuery $q) use($userIDs) {
                    $q->where(
                        $q->qn('created_by') . ' IN (' . implode(',', array_map(array($q, 'q'), $userIDs)) . ')'
                    );
                });
            }
        }

        if ($assigned)
        {
            /** @var JoomlaUsers $users */
            $users = $this->container->factory->model('JoomlaUsers')->tmpInstance();
            $userIDs = $users->search($assigned)->with(array())->get(true)->modelKeys();

            // If there are user IDs, we need to filter by them
            if (!empty($userIDs))
            {
                $this->whereHas('assigned', function (\JDatabaseQuery $q) use($userIDs) {
                    $q->where(
                        $q->qn('assigned_to') . ' IN (' . implode(',', array_map(array($q, 'q'), $userIDs)) . ')'
                    );
                });
            }
        }
    }

    public function buildQuery($override = false)
    {
        $db = $this->getDbo();

        $query = parent::buildQuery($override);

        if($status_array = $this->getState('status_array', ''))
        {
            $statuses = explode(',', $status_array);
            $statuses = array_map('trim', $statuses);
            $statuses = array_map(array($db, 'quote'), $statuses);

            $query->where($db->qn('status').' IN('.implode(',', $statuses).')');
        }

        return $query;
    }

    /**
     * After deleting the ticket, remove all posts linked to such ticket
     *
     * @param   int   $oid  ID of the deleted record
     */
    protected function onAfterDelete($oid)
    {
        /** @var Posts[] $posts */
        $posts = $this->posts;

        foreach ($posts as $post)
        {
            $post->delete();
        }

        // Remove credit charges for this ticket
        if (ComponentParams::getParam('showcredits', 0))
        {
            Credits::refundCredits($this->catid, $this->ats_ticket_id, 0, 'ticket');
        }
    }

    public function check()
    {
        parent::check();

        $this->assertNotEmpty($this->catid, JText::_('COM_ATS_TICKETS_ERR_NOCATID'));

        if (self::$overrideuser || \JFactory::getSession()->get('ticket.overrideuser', 0, 'com_ats'))
        {
            $userid = $this->created_by;
        }
        else
        {
            $userid = null;
        }

        $actions = Permissions::getActions($this->catid, $userid);

        $this->assert($actions['core.create'], JText::_('COM_ATS_TICKETS_ERR_CATNOAUTH'));

        // Do we have a title?
        $this->title = trim($this->title);
        $this->assertNotEmpty($this->title, JText::_('COM_ATS_TICKETS_ERR_NOTITLE'));

        // Do we have an alias?
        if (is_string($this->alias))
        {
            $this->alias = trim($this->alias);
        }
        else
        {
            $this->alias = '';
        }

        if (empty($this->alias))
        {
            $this->alias = Filter::toSlug($this->title);
        }

        // Do we have the same slug?
        $existingItems = $this->tmpInstance()->alias($this->alias)->get(true);

        if (!empty($existingItems))
        {
            $count = 0;
            $maxid = 0;
            $k     = $this->getKeyName();

            foreach ($existingItems as $item)
            {
                if ($item->$k != $this->$k)
                {
                    // Is it the exact same alias?
                    if ($item->alias == $this->alias)
                    {
                        $count++;
                        // Or is it alias-number
                    }
                    else
                    {
                        $number = substr($item->alias, strlen($this->alias));
                        $number = (int)$number;
                        if ($number <= 0)
                        {
                            continue;
                        }

                        $count++;
                        if ($number > $maxid)
                        {
                            $maxid = $number;
                        }
                    }
                }
            }

            if ($count)
            {
                // Let's try to create a unique alias
                if ($maxid == 0)
                {
                    $maxid = $count;
                }

                $maxid++;
                $this->alias .= '-' . $maxid;
            }
        }

        // Check the public status
        if (!$this->public)
        {
            $myData = $this->getData();

            // If the ticket already exists and it is already private, do nothing
            $checkExisting = $this->tmpInstance();
            $checkExisting->load($this->ats_ticket_id);
            $allowed = ($checkExisting->ats_ticket_id == $this->ats_ticket_id);

            // Is the current user allowed to make the ticket private?
            if (!$allowed)
            {
                $action  = Permissions::getActions($this->catid);
                $allowed = $action['ats.private'];
            }

            // Is the owner allowed private tickets?
            if (!$allowed)
            {
                $user    = $this->container->platform->getUser($this->created_by);
                $allowed = $user->authorise('ats.private', 'com_ats.category.' . (int)($this->catid));
            }

            // Switch to public mode if all checks failed
            if (!$allowed)
            {
                $this->public = 1;
            }

            $this->bind($myData);
        }

        // Check the status
        // @TODO: Do we really need this check?
        if (!in_array($this->status, array('O', 'P', 'C', 1, 2, 3, 4, 5, 6, 7, 8, 9)))
        {
            $this->status = 'O';
        }

        // Check the origin
        if (!in_array($this->origin, array('web', 'email')))
        {
            $this->origin = 'web';
        }

        // Priority is not set, so I automatically set it by looking at the visibility
        if (!$this->priority)
        {
            if (!$this->public)
            {
                // Private tickets have high priority
                $this->priority = 1;
            }
            else
            {
                $this->priority = 5;
            }
        }

        // I have to manually add the create_on field, I can't use the "Created" behavior or F0F will replace
        // the userid in created_by and we can't create tickets on behalf of another user
        if (!$this->created_on || ($this->created_on == '0000-00-00 00:00:00') || ($this->created_on == $this->getDbo()->getNullDate()))
        {
            $date             = $this->container->platform->getDate();
            $this->created_on = $date->toSql();
        }
    }

    /**
     * Fetches User info, caching them to prevent excessive queries
     *
     * @param   int $id
     *
     * @return  \JUser
     */
    public function getUser($id)
    {
        static $cache = array();

        if(!isset($cache[$id]))
        {
            $cache[$id] = $this->container->platform->getUser($id);
        }

        return $cache[$id];
    }

    /**
     * Adds tickets to a bucket
     *
     * @param   array|int   $tickets  Ticket to add
     * @param   int		    $bucket   Target bucket
     *
     * @return  bool		Is the task succesfull?
     */
    public function addTicketsToBucket($tickets, $bucket)
    {
        $tickets = (array) $tickets;

        if(!$tickets || !$bucket)
        {
            return false;
        }

        foreach($tickets as $ticket)
        {
            try
            {
                $this->find($ticket);
                $data['ats_bucket_id'] = $bucket;

                $this->save($data);
            }
            catch(\Exception $e)
            {
                return false;
            }
        }

        return true;
    }

    /**
     * Before saving the ticket, we have to check if custom fields are valid
     *
     * @param $data
     *
     * @throws \Exception
     */
    protected function onBeforeSave(&$data)
    {
        $this->isNew = empty($this->ats_ticket_id);

        // TODO Move this code inside the check method
        if(!$this->isValid())
        {
            throw new \Exception(JText::_('COM_ATS_ERR_NEWTICKET_CUSTOM_FIELDS'));
        }
    }

    protected function onAfterSave()
    {
        // If it's a new ticket, charge credits (only if we enabled that feature)
        if (ComponentParams::getParam('showcredits', 0) && $this->isNew)
        {
            Credits::chargeCredits($this->created_by, $this->catid, $this->ats_ticket_id, 0, true, $this->public, $this->priority);
        }
    }

    /**
     * Is everythinng ok?
     *
     * @return   bool   Is the submitted form ok (including custom field data)?
     */
    public function isValid()
    {
        $platform = $this->container->platform;
        $platform->importPlugin('ats');

        $response = (object)array(
            'custom_validation'		=> array(),
            'custom_valid'			=> true
        );

        // Let's get the data contained inside custom fields
        $savestate = $this->_savestate;
        $this->savestate(0);

        $custom = $this->getState('params', array());
        // On frontend I have the field category, on backend I have catid...
        $category = $this->getState('category', $this->getState('catid', 0,'int') ,'int');

        $this->savestate($savestate);

        // Get the results from the custom validation
        $isCli   = $platform->isCli();
        $isAdmin = $platform->isBackend();

        // If I don't have a "params" field simply stop here
        if(!$custom)
        {
            return $response->custom_valid;
        }

        // Run only if I'm on frontend, if I am in backend and I have a valid ticket
        if((!$isCli && !$isAdmin) || ($isAdmin && $this->ats_ticket_id))
        {
            $jResponse = $platform->runPlugins('onValidate', array($custom, $category));

            if(is_array($jResponse) && !empty($jResponse))
            {
                foreach($jResponse as $pluginResponse)
                {
                    if(!is_array($pluginResponse) || !isset($pluginResponse['valid']) || !isset($pluginResponse['custom_validation']))
                    {
                        continue;
                    }

                    $response->custom_valid      = $response->custom_valid && $pluginResponse['valid'];
                    $response->custom_validation = array_merge($response->custom_validation, $pluginResponse['custom_validation']);
                }
            }
        }

        return $response->custom_valid;
    }

    public function getHash()
    {
        $hash = ucfirst($this->container->componentName) . '.';

        // If possible, use the current view name to build the hash, so we won't "pollute" other views
        if($this->viewName)
        {
            $hash .= $this->viewName;
        }
        else
        {
            $hash .= $this->getName();
        }

        $hash .= '.';

        return $hash;
    }

    /**
     * Setter for the "params" field (JSON encoded string)
     *
     * @param $value
     *
     * @return string
     */
    protected function setParamsAttribute($value)
    {
        return $this->setAttributeForJson($value);
    }

    /**
     * Getter for the "params" field (JSON encoded string)
     *
     * @param $value
     *
     * @return string
     */
    protected function getParamsAttribute($value)
    {
        return $this->getAttributeForJson($value);
    }
}