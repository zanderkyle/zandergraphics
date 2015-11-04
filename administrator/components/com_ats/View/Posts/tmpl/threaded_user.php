<?php
/**
 * @package ats
 * @copyright Copyright (c)2011-2014 Nicholas K. Dionysopoulos / AkeebaBackup.com
 * @license GNU GPL v3 or later
 */

/** @var FOF30\View\DataView\Html   $this */

// No direct access
use Akeeba\TicketSystem\Admin\Helper\Filter;
use Akeeba\TicketSystem\Admin\Helper\Html;
use Akeeba\TicketSystem\Admin\Helper\Subscriptions;

defined('_JEXEC') or die;

JLoader::import('joomla.filesystem.file');

$container   = $this->getContainer();
$avatarURL   = Html::getAvatarURL($user, 64);
$subsPerUser = Subscriptions::getSubscriptionsList($user);

?>
<?php if($avatarURL): ?>
<div class="ats-post-userinfo-avatar">
	<img src="<?php echo $avatarURL?>" />
</div>
<?php endif; ?>

<div class="ats-post-userinfo-username">
	<?php echo $user->username ?>
</div>

<div class="ats-post-userinfo-badge">
	<?php foreach($user->groups as $groupName => $groupID):
	$basePath = JPATH_ROOT.'/media/com_ats/groups/';
	$altPath  = JPATH_BASE.'/templates/'.JFactory::getApplication()->getTemplate().'/media/com_ats/groups/';
	$alias    = Filter::toSlug($groupName);

	$img      = null;

	if(JFile::exists($altPath.$alias.'.png') || JFile::exists($basePath.$alias.'.png'))
    {
		$img = $container->template->parsePath('media://'.$alias.'.png');
	}
    elseif(JFile::exists($altPath.$alias.'.jpg') || JFile::exists($basePath.$alias.'.jpg'))
    {
		$img = $container->template->parsePath('media://'.$alias.'.jpg');
	}

	if(!is_null($img)):
	?>
	<img src="<?php echo $img?>" border="0" />
	<?php
	endif;
	endforeach; ?>
</div>
<?php
$countryInfo  = null;
$pluginResult = $container->platform->runPlugins('onAtsGetSubscriptionCountry', array($user->id, $this->isManager));

if(is_array($pluginResult))
{
    $countryInfo = array_shift($pluginResult);
}

if(!empty($countryInfo) && is_array($countryInfo) && (count($countryInfo) > 1)): ?>
    <div class="ats-post-userinfo-flag">
        <img class="hasTooltip" src="<?php echo $container->template->parsePath('media://com_ats/flags/' . strtolower($countryInfo[0]) . '.gif') ?>" title="<?php echo $countryInfo[1] ?>" alt="<?php echo $countryInfo[1] ?>" />
    </div>
<?php endif;?>
<?php if($subsPerUser->active): ?>
<div class="ats-post-userinfo-subscriptions">
	<?php echo implode(', ', $subsPerUser->active) ?>
</div>
<?php endif; ?>
<?php
    /** @var \Akeeba\TicketSystem\Admin\Model\UserTags $tagModel */
    $tagModel = $container->factory->model('UserTags')->tmpInstance();
    $tags     = $tagModel->loadTagsByUser($user->id);

    foreach($tags as $tagid)
    {
        $tag = $tagModel->decodeTag($tagid);
        ?>
        <span class="label label-info atsTooltip" data-toggle="tooltip" title="<?php echo $tag->descr?>">
            <?php echo $tag->title ?>
        </span>
    <?php
    }