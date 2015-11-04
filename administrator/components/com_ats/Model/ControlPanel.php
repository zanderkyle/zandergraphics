<?php
/**
 * @package   AkeebaTicketSystem
 * @copyright Copyright (c)2011-2015 Nicholas K. Dionysopoulos
 * @license   GNU General Public License version 3, or later
 */

namespace Akeeba\TicketSystem\Admin\Model;

defined('_JEXEC') or die;

use Akeeba\TicketSystem\Admin\Helper\ComponentParams;
use FOF30\Database\Installer;
use FOF30\Model\Model;

class ControlPanel extends Model
{
	/**
	 * Checks the database for missing / outdated tables using the $dbChecks
	 * data and runs the appropriate SQL scripts if necessary.
	 *
	 * @return  $this
	 */
	public function checkAndFixDatabase()
	{
		$db = $this->container->platform->getDbo();

		$dbInstaller = new Installer($db, JPATH_ADMINISTRATOR . '/components/com_ats/sql/xml');
		$dbInstaller->updateSchema();

		return $this;
	}

    /**
     * Does the user needs to enter a Download ID?
     *
     * @return boolean
     */
    public function needsDownloadID()
    {
        // Do I need a Download ID?
        $ret  = false;
        $dlid = ComponentParams::getParam('downloadid', '');

        if (!preg_match('/^([0-9]{1,}:)?[0-9a-f]{32}$/i', $dlid))
        {
            $ret = true;
        }

        return $ret;
    }
}