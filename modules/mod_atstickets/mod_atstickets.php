<?php
/**
 *  @package	ats
 *  @copyright	Copyright (c)2010-2014 Nicholas K. Dionysopoulos / AkeebaBackup.com
 *  @license	GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
 *  @version 	$Id$
 *
 *  This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

// no direct access
defined('_JEXEC') or die('');

// PHP version check
if(defined('PHP_VERSION')) {
	$version = PHP_VERSION;
} elseif(function_exists('phpversion')) {
	$version = phpversion();
} else {
	// No version info. I'll lie and hope for the best.
	$version = '5.0.0';
}
// Old PHP version detected. EJECT! EJECT! EJECT!
if(!version_compare($version, '5.4.0', '>=')) return;

if (!defined('FOF30_INCLUDED') && !@include_once(JPATH_LIBRARIES . '/fof30/include.php'))
{
    return;
}

// Let's load the container so our autoloader gets registered
$container = FOF30\Container\Container::getInstance('com_ats');

$user = JFactory::getUser();
if($user->guest)
{
	echo '&nbsp;';
	return;
}

/** @var \Akeeba\TicketSystem\Site\Model\Tickets $ticketsModel */
$ticketsModel = $container->factory->model('Tickets')->tmpInstance();
$ticketsModel
	->created_by($user->id)
	->enabled(1);

$allTickets     = $ticketsModel->count();
$pendingTickets = $ticketsModel->reset()->status('P')->count();
$closedTickets  = $ticketsModel->reset()->status('C')->count();
$openTickets    = $allTickets - ($pendingTickets + $closedTickets);

?>
<div class="mod_atstickets akeeba-bootstrap">
	<?php if($params->get('show_open', 1)): ?>
	<div class="mod_atstickets-open">
		<span class="badge badge-info">
			<?php echo (int)$openTickets ?>
		</span>
		<?php echo JText::_('MOD_ATSTICKETS_LBL_OPENTICKETS'); ?>
	</div>
	<?php endif; ?>

	<?php if($params->get('show_pending', 1)): ?>
	<div class="mod_atstickets-pending">
		<span class="badge badge-warning">
			<?php echo (int)$pendingTickets ?>
		</span>
		<?php echo JText::_('MOD_ATSTICKETS_LBL_PENDINGTICKETS'); ?>
	</div>
	<?php endif; ?>

	<?php if($params->get('show_closed', 1)): ?>
	<div class="mod_atstickets-closed">
		<span class="badge badge-success">
			<?php echo (int)$closedTickets ?>
		</span>
		<?php echo JText::_('MOD_ATSTICKETS_LBL_CLOSEDTICKETS'); ?>
	</div>
	<?php endif; ?>

	<?php if($params->get('show_mytickets', 1)): ?>
	<div class="mod_atstickets-mytickets">
		<a href="<?php echo JRoute::_('index.php?option=com_ats&view=Mies')?>" class="btn btn-primary btn-small">
			<?php echo JText::_('MOD_ATSTICKETS_LBL_MYTICKETS'); ?>
		</a>
	</div>
	<?php endif; ?>

</div>
