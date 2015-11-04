<?php
/**
 * @package   AkeebaTicketSystem
 * @copyright Copyright (c)2011-2015 Nicholas K. Dionysopoulos
 * @license   GNU General Public License version 3, or later
 */

namespace Akeeba\TicketSystem\Admin\Helper;

use FOF30\Container\Container;

defined('_JEXEC') or die;

class Credits
{
    static $cache = array();

    /**
     * Does the user have enough credits for an operation?
     *
     * @param   int       $user_id      The user ID to check
     * @param   int       $catid        The category to which he will be creating a ticket / post a reply
     * @param   bool      $newTicket    Is this a new ticket? If false, the user is replying to a ticket
     * @param   bool|int  $public       Is this a public ticket?
     * @param   bool|int  $priority     Priority 0=Ignore, 1=High, 5=Normal, 10=Low, false for getting the lowest credit
     *
     * @return  boolean                 True if the user has enough credits
     */
    public static function haveEnoughCredits($user_id, $catid, $newTicket = false, $public = 0, $priority = false)
    {
		if (defined('ATS_PRO') && !ATS_PRO)
		{
			return true;
		}

        $creditsRequired = self::creditsRequired($catid, $newTicket, $public, $priority);

        if($creditsRequired == 0)
        {
            return true;
        }

        $creditBalance = self::creditsLeft($user_id, true);

        return ($creditsRequired <= $creditBalance);
    }

    /**
     * How many credits are required for an operation
     *
     * @param   int 	  $catid        The category to which he will be creating a ticket / post a reply
     * @param   bool 	  $newTicket    Is this a new ticket? If false, the user is replying to a ticket
     * @param   bool|int  $public       Is this a public ticket?
     * @param	bool|int  $priority     Priority 0=Ignore, 1=High, 5=Normal, 10=Low, false for getting the lowest credit
     *
     * @return  int                     Number of credits required; 0 means "no credits required"
     */
    public static function creditsRequired($catid, $newTicket = false, $public = 0, $priority = false)
    {
		if (defined('ATS_PRO') && !ATS_PRO)
		{
			return 0;
		}

        $ticketSpecifier = $newTicket ? 'new' 	  : 'reply';
        $publicSpecifier = $public 	  ? 'public'  : 'private';
        $prioritySpec	 = $priority  ? $priority : '';

        $key = "$catid-$ticketSpecifier-$publicSpecifier-$prioritySpec";

        if(!isset(self::$cache[$key]))
        {
            $container = Container::getInstance('com_ats');
            /** @var \Akeeba\TicketSystem\Admin\Model\Categories $catModel */
            $catModel  = $container->factory->model('Categories')->tmpInstance();

            $categories = $catModel
                            ->category($catid)
                            ->get(true);

            if(!empty($categories))
            {
                // Get the category
                $category = $categories->last();
                // Fetch parameters
                $params = new \JRegistry();

                $params->loadString($category->params, 'JSON');

                // Let's get any priority modifier
                $modifier = 0;
                if($priority === false)
                {
                    $modifier = min(
                        array($params->get('modifier_lowpriority', 0),
                            $params->get('modifier_normalpriority', 0),
                            $params->get('modifier_highpriority', 0)
                        )
                    );
                }
                elseif($priority !== 0)
                {
                    if    ($priority == 1) $st_priority = 'high';
                    elseif($priority == 5) $st_priority = 'normal';
                    else                   $st_priority = 'low';

                    $modifier = $params->get('modifier_'.$st_priority.'priority', 0);
                }

                // Populate all keys for the category
                self::$cache["$catid-new-public-$prioritySpec"]    = $params->get('credits_newpublic'   , 0, 'int') + $modifier;
                self::$cache["$catid-reply-public-$prioritySpec"]  = $params->get('credits_replypublic' , 0, 'int');
                self::$cache["$catid-new-private-$prioritySpec"]   = $params->get('credits_newprivate'  , 0, 'int') + $modifier;
                self::$cache["$catid-reply-private-$prioritySpec"] = $params->get('credits_replyprivate', 0, 'int');
            }
            else
            {
                self::$cache["$catid-new-public-$prioritySpec"]    = 0;
                self::$cache["$catid-reply-public-$prioritySpec"]  = 0;
                self::$cache["$catid-new-private-$prioritySpec"]   = 0;
                self::$cache["$catid-reply-private-$prioritySpec"] = 0;
            }
        }

        return self::$cache[$key];
    }

    /**
     * How many credits are left to a user's account?
     *
     * If $total is true the total number of credits is returned.
     * If $total is false, you get an array with keys debits (all active credit
     * transactions debiting credits to the user's account), charges (all
     * creditconsumption records charging credits to the user) and total (total
     * amount of available credits)
     *
     * @param int $user_id The user ID to check
     * @param bool $total See above
     *
     * @return int|array See above
     */
    public static function creditsLeft($user_id, $total = true)
    {
		if (defined('ATS_PRO') && !ATS_PRO)
		{
			return 0;
		}

        if(!isset(self::$cache[$user_id]))
        {
            $container = Container::getInstance('com_ats');

            /** @var \Akeeba\TicketSystem\Admin\Model\CreditTransactions $transModel */
            $transModel = $container->factory->model('CreditTransactions')->tmpInstance();

            // Load all transactions
            $transactions = $transModel
                                ->user_id($user_id)
                                ->enabled(1)
                                ->get(true);

            // Get transaction IDs
            $tids    = array();
            $debits  = array();
            $charges = array();

            /** @var \Akeeba\TicketSystem\Admin\Model\CreditTransactions $transaction */
            foreach($transactions as $transaction)
            {
                $tids[] = $transaction->ats_credittransaction_id;
                $debits[$transaction->ats_credittransaction_id] = $transaction->value;
            }

            if(!empty($tids))
            {
                /** @var \Akeeba\TicketSystem\Admin\Model\CreditConsumptions $consumpModel */
                $consumpModel = $container->factory->model('CreditConsumptions')->tmpInstance();

                // Get totals per transaction
                $consumptions = $consumpModel
                                    ->sum(1)
                                    ->transaction_ids($tids)
                                    ->get(true);

                /** @var \Akeeba\TicketSystem\Admin\Model\CreditConsumptions $consumption */
                foreach($consumptions as $consumption)
                {
                    $charges[$consumption->ats_credittransaction_id] = $consumption->consumed;
                }
            }

            // construct array per user
            self::$cache[$user_id] = array(
                'debits'    => $debits,
                'charges'   => $charges,
                'total'     => array_sum($debits) - array_sum($charges)
            );
        }

        if($total)
        {
            return self::$cache[$user_id]['total'];
        }
        else
        {
            return self::$cache[$user_id];
        }
    }

    /**
     * How many credits have been charged for a particular post or ticket
     *
     * @param    string     $type       post|ticket
     * @param    int        $ticket_id  The ticket ID, required
     * @param    int|null   $post_id    The post ID, only if $type == "post"
     * @param    bool       $use_cache  Should I use the cache?
     *
     * @return int Amount of credits charged for this post or ticket
     */
    public static function creditsCharged($type, $ticket_id, $post_id = null, $use_cache = true)
    {
		if (defined('ATS_PRO') && !ATS_PRO)
		{
			return 0;
		}

        static $cache = array();

        switch ($type)
        {
            case 'ticket':
                $post_id = 0;
            // Do not put a break here!

            case 'post':
                $key = $type.'-'.$ticket_id.'-'.$post_id;
                break;

            case 'all':
                $key = $type.'-'.$ticket_id.'-*';
                break;
        }

        if(!isset($cache[$key]) || !$use_cache)
        {
            $container = Container::getInstance('com_ats');

            /** @var \Akeeba\TicketSystem\Admin\Model\CreditConsumptions $consumpModel */
            $consumpModel = $container->factory->model('CreditConsumptions')->tmpInstance();

            $items = $consumpModel
                        ->ats_ticket_id($ticket_id)
                        ->get(true);
            $all = 0;

            foreach($items as $item)
            {
                $myKey = $item->type.'-'.$item->ats_ticket_id.'-'.$item->ats_post_id;
                $cache[$myKey] = $item->value;

                $all += $item->value;
            }

            $cache["all-$ticket_id-*"] = $all;

            if(!isset($cache[$key]))
            {
                $cache[$key] = 0;
            }
        }

        return $cache[$key];
    }

    public static function refundCredits($catid, $ticket_id, $post_id, $type = 'ticket')
    {
		if (defined('ATS_PRO') && !ATS_PRO)
		{
			return;
		}

        // Have we already charged anything for this?
        $pid = ($type == 'ticket') ? 0 : $post_id;

        $alreadyCharged = self::creditsCharged($type, $ticket_id, $pid, false);

        // Not charged? Do nothing.
        if(!$alreadyCharged)
        {
            return;
        }

        $container = Container::getInstance('com_ats');
        /** @var \Akeeba\TicketSystem\Admin\Model\CreditConsumptions $model */
        $model     = $container->factory->model('CreditConsumptions')->tmpInstance();

        $items = $model
                    ->type($type)
                    ->ats_ticket_id($ticket_id)
                    ->ats_post_id($pid)
                    ->get(true);

        if(!empty($items))
        {
            /** @var \Akeeba\TicketSystem\Admin\Model\CreditConsumptions $item */
            foreach($items as $item)
            {
                $item->forceDelete();
            }
        }
    }

    /**
     * Charge credits to a user
     *
     * @param   int       $user_id    User ID
     * @param   int       $catid      Ticket category
     * @param   int       $ticket_id  Ticket ID
     * @param   int|null  $post_id    Post ID, leave null if you are charging for a ticket
     * @param   bool      $newTicket  Is it a new ticket (true) or a reply (false)?
     * @param   bool      $public     Is this a public ticket?
     * @param   int       $priority   Ticket priority, set it to 0 if not used
     * @param   int       $custom     Custom amount of credits
     *
     * @return  void
     */
    public static function chargeCredits($user_id, $catid, $ticket_id, $post_id, $newTicket = false, $public = false, $priority = 0, $custom = 0)
    {
		if (defined('ATS_PRO') && !ATS_PRO)
		{
			return;
		}

        // If I have custom amount of credits, let's use it, avoiding calculations
        if($custom)
        {
            $howMuch        = $custom;
            $alreadyCharged = 0;
            $pid			= $post_id;
            $type			= 'post';
        }
        else
        {
            // How much do I have to charge?
            $howMuch = self::creditsRequired($catid, $newTicket, $public, (int) $priority);

            // Have we already charged anything for this?
            $type = $newTicket ? 'ticket' : 'post';
            $pid  = $newTicket ? 0 : $post_id;
            $alreadyCharged = self::creditsCharged($type, $ticket_id, $pid);

            // Already charged? Do nothing.
            if($alreadyCharged == $howMuch)
            {
                return;
            }
        }

        $container = Container::getInstance('com_ats');
        /** @var \Akeeba\TicketSystem\Admin\Model\CreditConsumptions $model */
        $model     = $container->factory->model('CreditConsumptions')->tmpInstance();

        // Delete all charge if $alreadyCharged > 0
        if($alreadyCharged > 0)
        {
            $model
                ->type($type)
                ->ats_ticket_id($ticket_id)
                ->ats_post_id($pid);

            $items = $model->get(true);

            if(!empty($items))
            {
                /** @var \Akeeba\TicketSystem\Admin\Model\CreditConsumptions $item */
                foreach($items as $item)
                {
                    $item->forceDelete();
                }
            }
        }

        // No charge? Nothing to do.
        if($howMuch == 0)
        {
            return;
        }

        // Determine the transaction ID to use
        $info         = self::creditsLeft($user_id, false);
        $transactions = $info['debits'];
        $tid          = 0;
        $oldesttid    = 0;

        // -- try to find the oldest transaction with enough credits
        foreach($transactions as $id => $debit)
        {
            if($oldesttid == 0)
            {
                $oldesttid = $id;
            }

            if(isset($info['charges'][$id]))
            {
                $left = $debit - $info['charges'][$id];
            }
            else
            {
                $left = $debit;
            }

            if($left >= $howMuch)
            {
                $tid = $id;
                break;
            }
        }

        // -- if none is found, just use the oldest transaction (in the future we'll gift some credits to the user, tough luck)
        if($tid == 0)
        {
            $tid = $oldesttid;
        }

        // Create new charge
        $data = array(
            'ats_credittransaction_id'	=> $tid,
            'user_id' 					=> $user_id,
            'value'						=> $howMuch,
            'type' 						=> $type,
            'ats_ticket_id' 			=> $ticket_id,
            'ats_post_id' 				=> $pid
        );
        $model->save($data);
    }

    /**
     * Method to purge internal cache.
     *
     * @param    string   $key  Optional key to purge
     *
     * @return   void
     */
    public static function purgeCache($key = '')
    {
        if($key)
        {
            unset(self::$cache[$key]);
        }
        else
        {
            self::$cache = array();
        }
    }
}