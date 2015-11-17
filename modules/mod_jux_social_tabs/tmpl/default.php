<?php
/**
 * @version  $Id$
 * @author  JoomlaUX!
 * @package  Joomla.Site
 * @subpackage mod_jux_slideshow
 * @copyright Copyright (C) 2012 - 2013 by JoomlaUX. All rights reserved.
 * @license  http://www.gnu.org/licenses/gpl.html
 */
// no direct access
defined('_JEXEC') or die('Restricted access');

/*
 * Default page thực hiện:
 * 1. Load các option cho thẻ div có id='social-tabs'
 * 2. Điều chỉnh lại đường dẫn ảnh của các social (url bị khác khi script mẫu được đưa vào joomla module)
 * 3. Với trường hợp Tabs có position là absolute, thì cần bổ sung thuộc tính relative và hidden 
 * cho thẻ là POSITION trong joomla template
 */
?>
<script type="text/javascript" src="<?php echo JUri::base().'modules/mod_jux_social_tabs/assets/js/jux_social_tabs.js'?>"></script>
<div id="social-tabs"></div>

<script type="text/javascript">
	var JUX_BASE_URL = '<?php echo JUri::base(); ?>';
    var img_link    = '<?php echo JUri::base() . 'modules/mod_jux_social_tabs/assets/'; ?>';
	jQuery(document).ready(function(jQuery)
    {
        jQuery('#social-tabs').dcSocialTabs(
        {
<?php
// helper object
$helper = new modSocialmediaHelper();

// code de show cac social
$arrOptions = array();



// social options
array_push($arrOptions, $helper->getSocialNames($params));
array_push($arrOptions, $helper->getSocialIDs($params));
array_push($arrOptions, $helper->getTwitterOptions($params));
/*array_push($arrOptions, $helper->getFacebookRecommentdationOptions($params));*/
array_push($arrOptions, $helper->getFacebookLikeoptions($params));
array_push($arrOptions, $helper->getFacebookOptions($params));
array_push($arrOptions, $helper->getGoogleOptions($params));
array_push($arrOptions, $helper->getPinterestOptions($params));
array_push($arrOptions, $helper->getRSSOptions($params));
array_push($arrOptions, $helper->getYoutubeOptions($params));
array_push($arrOptions, $helper->getinstagramOption($params));
array_push($arrOptions, $helper->getVimeoOptions($params));
array_push($arrOptions, $helper->getFlickrOptions($params));
array_push($arrOptions, $helper->getStumbleuponOptions($params));
array_push($arrOptions, $helper->getTumblrOptions($params));
array_push($arrOptions, $helper->getDeliciousOptions($params));
array_push($arrOptions, $helper->getDiggOptions($params));
array_push($arrOptions, $helper->getLastfmOptions($params));
array_push($arrOptions, $helper->getDribbleOptions($params));
array_push($arrOptions, $helper->getDeviantARTOptions($params));

// Demo mode
if(!defined('DEMO_MODE')) {
	define('DEMO_MODE', 1);
}
if(DEMO_MODE) {
	$input = JFactory::getApplication()->input;
	$data = $input->post->get('jux_demo_control_form', array(), 'array');
	$properties = $params->toArray();
	foreach($properties as $key => $value) {
		$params->set($key, isset($data[$key]) ? $data[$key] : $value);
	}
}


// tabs options
array_push($arrOptions, $helper->getMethod($params));
array_push($arrOptions, $helper->getLocation($params));
array_push($arrOptions, $helper->getPosition($params));
array_push($arrOptions, $helper->getOffset($params));

array_push($arrOptions, $helper->getHeight($params));
array_push($arrOptions, $helper->getWidth($params));
array_push($arrOptions, $helper->getDefaultTabIndex($params));
array_push($arrOptions, $helper->getDataDisplay($params));
array_push($arrOptions, $helper->getLoadOpen($params));
array_push($arrOptions, $helper->getAutoClose($params));


// remove blank items
$arrOptions = array_filter($arrOptions);

// echozz
echo implode(", ", $arrOptions);
?>
		});
	});

</script>  
<script type="text/javascript">	
	
	jQuery(window).load(function ()
	{
<?php
// udpate tag đóng vai trò là POSITION mà module được gán, khi tabs có position là absolute
if ($helper->getPositionValue($params) == "absolute")
{
	?>
				var $parent = jQuery('#social-tabs').parent().parent().parent();
				$parent.css("position", "relative");
				$parent.css("overflow", "hidden");
	<?php
}
?>
	});
	
</script>