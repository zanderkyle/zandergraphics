<?php
/**
 * @package   AkeebaTicketSystem
 * @copyright Copyright (c)2011-2015 Nicholas K. Dionysopoulos
 * @license   GNU General Public License version 3, or later
 */

namespace Akeeba\TicketSystem\Site\View\NewTickets;

use Akeeba\TicketSystem\Admin\Helper\ComponentParams;
use Akeeba\TicketSystem\Admin\Helper\Credits;
use Akeeba\TicketSystem\Admin\Helper\Permissions;
use Akeeba\TicketSystem\Site\Model\Categories;

defined('_JEXEC') or die;

class Html extends \FOF30\View\DataView\Html
{
    /** @var bool Is the current user a manager? */
    protected $isManager = false;

    protected function onBeforeAdd()
    {
        /** @var \JApplicationSite $app */
        $app              = \JFactory::getApplication();
        $params           = $app->getParams();
        $this->pageParams = $params;

        parent::onBeforeAdd();

        if ($this->getLayout() == 'landing')
        {
            return true;
        }

        // Is the "No new tickets" killswitch activated?
        if(ComponentParams::getParam('nonewtickets', 0))
        {
            $this->setLayout('nonewtickets');

            return false;
        }

        $container = $this->container;
        $user      = $container->platform->getUser();
        $container->platform->importPlugin('ats');

        // Fire onTicketsAdd event, maybe I have anything that changes the
        // user credit balance (ie AlphaUserPoint)?
        $container->platform->runPlugins('onTicketsBeforeAdd', array(&$this->item, $this->category, \JFactory::getUser()));

        $container->template->addCSS('media://com_ats/css/frontend.css');

        $js = <<<JS
;//
function addToValidationFetchQueue(myfunction){}
function addToValidationQueue(myfunction){}
JS;
        if(ComponentParams::getParam('showcredits', 0) && ComponentParams::getParam('ticketPriorities', 0))
        {
            $container->template->addJS('media://com_ats/js/priority_credits.js', false, false, $container->mediaVersion);
        }
        else
        {
            $js .=<<<JS

;// This comment is intentionally put here to prevent badly written plugins from causing a Javascript error
// due to missing trailing semicolon and/or newline in their code.
akeeba.jQuery(document).ready(function($){
	$("#privateticket").change(function(){
		if($("#privateticket").val() == 0){
			$("#ats-newticket-pubnote-public").hide();
			$("#ats-newticket-pubnote-private").show();
		}
		else{
			$("#ats-newticket-pubnote-private").hide();
			$("#ats-newticket-pubnote-public").show();
		}
	})
});
JS;
        }

        $this->catparams = new \JRegistry();
        $this->catparams->loadString($this->category->params, 'JSON');

        if($this->catparams->get('instantreply',0))
        {
            $proxyURL  = \JRoute::_('index.php?option=com_ats&view=InstantReply&task=browse&catid='.$this->category->id, false);
            $proxyURL .= strpos($proxyURL, '?') !== false ? '&' : '?';
            $proxyURL .= 'format=json';

            $js .= "var ats_instantreply_proxy = '".$proxyURL."';\n";
            $container->template->addJS('media://com_ats/js/instantreply.js', false, false, $container->mediaVersion);
        }

        $container->template->addJSInline($js);

        $this->isManager = Permissions::isManager($this->category->id);

        $this->show_credits    		  = ComponentParams::getParam('showcredits', 0);
        $this->enough_credits_public  = true;
        $this->enough_credits_private = true;

        // Perform checks only if requested to
        if (!$this->isManager && $this->show_credits)
        {
            /** @var Categories $catModel */
            $catModel = $this->container->factory->model('Categories')->tmpInstance();
            $catModel->find($this->category->id);

            $this->category->objParams = new \JRegistry();
            $this->category->objParams->loadString($catModel->params);

            $forcetype  = $this->category->objParams->get('forcetype');

            // First of all, let's check if the user can post anything with the lower credit cost
            // (ie public ticket with low priority)
            if(ComponentParams::getParam('ticketPriorities'))
            {
                $this->enough_credits_public  = Credits::haveEnoughCredits($user->id, $this->category->id, true, true, false);
                $this->enough_credits_private = Credits::haveEnoughCredits($user->id, $this->category->id, true, false, false);
            }
            else
            {
                $this->enough_credits_public  = Credits::haveEnoughCredits($user->id, $this->category->id, true, true, 5);
                $this->enough_credits_private = Credits::haveEnoughCredits($user->id, $this->category->id, true, false, 1);
            }

            // Let's get credit cost on "default" values
            $this->credits_public  = Credits::creditsRequired($this->category->id, true, true,  5);
            $this->credits_private = Credits::creditsRequired($this->category->id, true, false, 1);

            // If the user can't do anything, neither post on low priority, let's stop here
            // Those tests are run vs default visibility, not the one coming from the session. However, if the user could select it, it means
            // that can use it (checks are performed before saving, too)
            if(
                (!$this->enough_credits_public && !$this->enough_credits_private) ||                         // Not enough credits for anything
                (!$this->enough_credits_public && $this->enough_credits_private && !$this->allow_private) || // Not enough credits for public posting
                ($forcetype == 'PRIV' && !$this->enough_credits_private)                                  || // Forced private but not enough credits
                ($forcetype == 'PUB'  && !$this->enough_credits_public)                                      // Forced public but not enough credits
            ) {
                $this->setLayout('notenoughcredits');

                return false;
            }
        }
        elseif ($this->show_credits)
        {
            $this->enough_credits_public  = true;
            $this->enough_credits_private = true;
            $this->credits_public         = 0;
            $this->credits_private        = 0;
        }
    }
}