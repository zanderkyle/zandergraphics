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

class modSocialmediaHelper {
    /* -- SCRIPT MAU:
      <script type="text/javascript">
      $(document).ready(function($){
      $('#social-tabs').dcSocialTabs({
      method: 'static',
      width: 450,
      height: 600,
      rotate: {
      delay: 8000,
      direction: 'down'
      },
      widgets: 'google,facebook,fblike,fbrec,rss,twitter,pinterest,delicious,tumblr,youtube,digg,linkedin',
      googleId: '111470071138275408587',
      facebookId: '157969574262873',
      fblikeId: '157969574262873',
      fbrecId: 'http://www.designchemical.com',
      rssId: 'http://feeds.feedburner.com/designmodo',
      twitterId: 'designchemical/9927875',
      pinterestId: 'designchemical',
      deliciousId: 'designchemical',
      youtubeId: 'wired',
      tumblrId: 'richters',
      diggId: 'remix4',
      linkedinId: '589883,http://www.linkedin.com/in/leechestnutt',
      start: 5,
      rss: {
      text: 'content'
      },
      twitter: {
      thumb: true
      }
      });
      });
      </script>
     */

    //----------------------------------------------------------------------------

    /*
     * For Social Media Options
     */
    static function javascript($params) {
        $document = JFactory::getDocument();
        $enable_jquery = $params->get('enable_jquery', 0);
        $enable_noconflict = $params->get('enable_noconflict', 0);

        if ($enable_noconflict) {
            $document->addCustomTag('<script type="text/javascript">jQuery.noConflict();</script>');
        }
        if ($enable_jquery) {
            $document->addScript(JURI::base() . 'modules/mod_jux_social_tabs/assets/js/jquery-1.8.2.min.js');
        }
        //$document->addScript(JURI::base() . 'modules/mod_jux_social_tabs/assets/js/jux_social_tabs.js');
    }

    // return "facebook" ...
    static function getSocialName($params, $socialName) {
        $value = "";
        //
        switch ($socialName) {
            case "mod_showFacebook":
                $value = "facebook";
                break;
            case "mod_showTwitter":
                $value = "twitter";
                break;
            case "mod_showGooglePlus":
                $value = "google";
                break;
            case "mod_showPinterest":
                $value = "pinterest";
                break;
            case "mod_showLinkedIn":
                $value = "linkedin";
                break;
            case "mod_showFacebookLikebox":
                $value = "fblike";
                break;
           /* case "mod_showFacebookRecommentdation":
                $value = "fbrec";
                break;*/
            case "mod_showStumbleupon":
                $value = "stumbleupon";
                break;
            case "mod_showTumblr":
                $value = "tumblr";
                break;
            case "mod_showYoutube":
                $value = "youtube";
                break;
            case "mod_showVimeo":
                $value = "vimeo";
                break;
            case "mod_showInstagram":
                $value = "instagram";
                break;
            case "mod_showRSS":
                $value = "rss";
                break;
            case "mod_showFlickr":
                $value = "flickr";
                break;
            case "mod_showDelicious":
                $value = "delicious";
                break;
            case "mod_showDigg":
                $value = "digg";
                break;
            case "mod_showLastfm":
                $value = "lastfm";
                break;
            case "mod_showDribbble":
                $value = "dribbble";
                break;
            case "mod_showDeviantART":
                $value = "deviantart";
                break;
        }
        return $value;
    }

    // return "widgets: 'twitter,facebook'" and order name
    function getSocialNames($params) {
        // loai bo nhung item rong
        $arrNames = $this->getSocialNamesArray($params);

        // tra ve chuoi chua ten cac social duoc ngan cach = dau ","
        return "widgets: '" . implode(",", $arrNames) . "'";
    }

    // return "widgets: 'twitter,facebook'" and order name
    function getSocialNamesArray($params) {
        $helper = new modSocialmediaHelper();
        // mang chua ten cua cac social
        $arrNames = array();
        // mang chua cac gia tri order
        $arrOrders = array();

        // add facebook name
        if ($this->getShowSocial($params, "mod_showFacebook") == true) {
            array_push($arrNames, $this->getSocialName($params, "mod_showFacebook"));
            array_push($arrOrders, $this->getSocialOder($params, "mod_facebookOrder"));
        }

        // add twitter name
        if ($this->getShowSocial($params, "mod_showTwitter") == true) {
            array_push($arrNames, $this->getSocialName($params, "mod_showTwitter"));
            array_push($arrOrders, $this->getSocialOder($params, "mod_twitterOrder"));
        }

        // add google name
        if ($this->getShowSocial($params, "mod_showGooglePlus") == true) {
            array_push($arrNames, $this->getSocialName($params, "mod_showGooglePlus"));
            array_push($arrOrders, $this->getSocialOder($params, "mod_googlePlusOrder"));
        }

        // add Printerest name
        if ($this->getShowSocial($params, "mod_showPinterest") == true) {
            array_push($arrNames, $this->getSocialName($params, "mod_showPinterest"));
            array_push($arrOrders, $this->getSocialOder($params, "mod_pinterestOder"));
        }

        // add LinkedIn name
        if ($this->getShowSocial($params, "mod_showLinkedIn") == true) {
            array_push($arrNames, $this->getSocialName($params, "mod_showLinkedIn"));
            array_push($arrOrders, $this->getSocialOder($params, "mod_LinkedInOder"));
        }

        // add FacebookLikebox name
        if ($this->getShowSocial($params, "mod_showFacebookLikebox") == true) {
            array_push($arrNames, $this->getSocialName($params, "mod_showFacebookLikebox"));
            array_push($arrOrders, $this->getSocialOder($params, "mod_FacebookLikeboxOder"));
        }

        // add FacebookRecomendation name
        if ($this->getShowSocial($params, "mod_showFacebookRecommentdation") == true) {
            array_push($arrNames, $this->getSocialName($params, "mod_showFacebookRecommentdation"));
            array_push($arrOrders, $this->getSocialOder($params, "mod_FacebookRecommentdationOder"));
        }

        // add Stumbeupon name
        if ($this->getShowSocial($params, "mod_showStumbleupon") == true) {
            array_push($arrNames, $this->getSocialName($params, "mod_showStumbleupon"));
            array_push($arrOrders, $this->getSocialOder($params, "mod_StumbleUponOder"));
        }

        // add Tumblr name
        if ($this->getShowSocial($params, "mod_showTumblr") == true) {
            array_push($arrNames, $this->getSocialName($params, "mod_showTumblr"));
            array_push($arrOrders, $this->getSocialOder($params, "mod_TumblrOder"));
        }

        // add Youtube name
        if ($this->getShowSocial($params, "mod_showYoutube") == true) {
            array_push($arrNames, $this->getSocialName($params, "mod_showYoutube"));
            array_push($arrOrders, $this->getSocialOder($params, "mod_YoutubeOder"));
        }

        // add Vimeo name
        if ($this->getShowSocial($params, "mod_showVimeo") == true) {
            array_push($arrNames, $this->getSocialName($params, "mod_showVimeo"));
            array_push($arrOrders, $this->getSocialOder($params, "mod_VimeoOder"));
        }

        // add Instagram name
        if ($this->getShowSocial($params, "mod_showInstagram") == true) {
            array_push($arrNames, $this->getSocialName($params, "mod_showInstagram"));
            array_push($arrOrders, $this->getSocialOder($params, "mod_InstagramOder"));
        }

        // add RSS name
        if ($this->getShowSocial($params, "mod_showRSS") == true) {
            array_push($arrNames, $this->getSocialName($params, "mod_showRSS"));
            array_push($arrOrders, $this->getSocialOder($params, "mod_RSSOder"));
        }

        // add Flickr name
        if ($this->getShowSocial($params, "mod_showFlickr") == true) {
            array_push($arrNames, $this->getSocialName($params, "mod_showFlickr"));
            array_push($arrOrders, $this->getSocialOder($params, "mod_FlickrOder"));
        }

        // add Delicious name
        if ($this->getShowSocial($params, "mod_showDelicious") == true) {
            array_push($arrNames, $this->getSocialName($params, "mod_showDelicious"));
            array_push($arrOrders, $this->getSocialOder($params, "mod_DeliciousOder"));
        }

        // add Digg name
        if ($this->getShowSocial($params, "mod_showDigg") == true) {
            array_push($arrNames, $this->getSocialName($params, "mod_showDigg"));
            array_push($arrOrders, $this->getSocialOder($params, "mod_DiggOder"));
        }

        // add Lastfm name
        if ($this->getShowSocial($params, "mod_showLastfm") == true) {
            array_push($arrNames, $this->getSocialName($params, "mod_showLastfm"));
            array_push($arrOrders, $this->getSocialOder($params, "mod_LastfmOder"));
        }

        // add Dribble name
        if ($this->getShowSocial($params, "mod_showDribbble") == true) {
            array_push($arrNames, $this->getSocialName($params, "mod_showDribbble"));
            array_push($arrOrders, $this->getSocialOder($params, "mod_DribbbleOder"));
        }

        // add DeviantART name
        if ($this->getShowSocial($params, "mod_showDeviantART") == true) {
            array_push($arrNames, $this->getSocialName($params, "mod_showDeviantART"));
            array_push($arrOrders, $this->getSocialOder($params, "mod_DeviantARTOder"));
        }

        // order lai
        array_multisort($arrOrders, $arrNames);

        // loai bo nhung item rong
        return array_filter($arrNames);
    }

    function getNameCount($params) {
        $nameCount = 0;
        $helper = new modSocialmediaHelper();
        /*
         *  - neu social duoc chon, $nameCount se tang them 1 gia tri
         *  - $nameCount co tac dung dung de so sanh khi user nhap gia tri default tab 
         * ngoai khoang $nameCount
         */
        if ($this->getShowSocial($params, "mod_showFacebook") == true)
            $nameCount++;
        if ($this->getShowSocial($params, "mod_showTwitter") == true)
            $nameCount++;
        if ($this->getShowSocial($params, "mod_showGooglePlus") == true)
            $nameCount++;
        if ($this->getShowSocial($params, "mod_showPinterest") == true)
            $nameCount++;
        if ($this->getShowSocial($params, "mod_showLinkedIn") == true)
            $nameCount++;
        if ($this->getShowSocial($params, "mod_showFacebookLikebox") == true)
            $nameCount++;
        if ($this->getShowSocial($params, "mod_showFacebookRecommentdation") == true)
            $nameCount++;
        if ($this->getShowSocial($params, "mod_showStumbleupon") == true)
            $nameCount++;
        if ($this->getShowSocial($params, "mod_showTumblr") == true)
            $nameCount++;
        if ($this->getShowSocial($params, "mod_showYoutube") == true)
            $nameCount++;
        if ($this->getShowSocial($params, "mod_showVimeo") == true)
            $nameCount++;
        if ($this->getShowSocial($params, "mod_showInstagram") == true)
            $nameCount++;
        if ($this->getShowSocial($params, "mod_showRSS") == true)
            $nameCount++;
        if ($this->getShowSocial($params, "mod_showFlickr") == true)
            $nameCount++;
        if ($this->getShowSocial($params, "mod_showDelicious") == true)
            $nameCount++;
        if ($this->getShowSocial($params, "mod_showDigg") == true)
            $nameCount++;
        if ($this->getShowSocial($params, "mod_showLastfm") == true)
            $nameCount++;
        if ($this->getShowSocial($params, "mod_showDribbble") == true)
            $nameCount++;
        if ($this->getShowSocial($params, "mod_showDeviantART") == true)
            $nameCount++;

        return $nameCount;
    }

    // true => show
    function getShowSocial($params, $socialName) {
        return $params->get($socialName) == 1 ? true : false;
    }

    // gia tri sap xep cua social trong cac tab
    function getSocialOder($params, $socialName) {
        return $params->get($socialName);
    }

    // hàm gọi các ID của function name (Facebook,Twitter...)
    function getSocialID($params, $socicalName) {
        $value = "";
        switch ($socicalName) {
            case "mod_facebookID":
                $value = "facebookId: '" . $params->get($socicalName) . "'";
                break;
            case "mod_twitterID":
                $value = "twitterId: '" . $params->get($socicalName) . "'";
                break;
            case "mod_googlePlusID":
                $value = "googleId: '" . $params->get($socicalName) . "'";
                break;
            case "mod_googlePlusAPI":
                $value = "googleAPI: '" . $params->get($socicalName) . "'";
                break;
            case "mod_pinterestID":
                $value = "pinterestId: '" . $params->get($socicalName) . "'";
                break;
            case "mod_LinkedInID":
                $value = "linkedinId: '" . $params->get($socicalName) . "'";
                break;
            case "mod_FacebookLikeboxID":
                $value = "fblikeId: '" . $params->get($socicalName) . "'";
                break;
           /* case "mod_FacebookRecimmentdationID":
                $value = "fbrecId: '" . $params->get($socicalName) . "'";
                break;*/
            case "mod_StumbleUponID":
                $value = "stumbleuponId: '" . $params->get($socicalName) . "'";
                break;
            case "mod_TumblrID":
                $value = "tumblrId: '" . $params->get($socicalName) . "'";
                break;
            case "mod_YoutubeChannel":
                $value = "youtubeId: '" . $params->get($socicalName) . "'";
                break;
            case "mod_VimeoID":
                $value = "vimeoId: '" . $params->get($socicalName) . "'";
                break;
            case "mod_InstagramID":
                $value = "instagramId: '!" . $params->get($socicalName) . "'";
                break;
            case "mod_RSSID":
                $value = "rssId: '" . $params->get($socicalName) . "'";
                break;
            case "mod_FlickrID":
                $value = "flickrId: '" . $params->get($socicalName) . "'";
                break;
            case "mod_DeliciousID":
                $value = "deliciousId: '" . $params->get($socicalName) . "'";
                break;
            case "mod_DiggID":
                $value = "diggId: '" . $params->get($socicalName) . "'";
                break;
            case "mod_LastfmID":
                $value = "lastfmId: '" . $params->get($socicalName) . "'";
                break;
            case "mod_DribbbleID":
                $value = "dribbbleId: '" . $params->get($socicalName) . "'";
                break;
            case "mod_DeviantARTID":
                $value = "deviantartId: '" . $params->get($socicalName) . "'";
                break;
        }
        return $value;
    }

    // return "twitterId: 'designchemical', facebookId: '157969574262873', fblikeId: '157969574262873'"
    function getSocialIDs($params) {
        $helper = new modSocialmediaHelper();
        // mang chua ten cua cac social
        $arrIDs = array();

        /*
         * Add ID cua cac mang xa hoi do nguoi dung chon
         */
        if ($this->getShowSocial($params, "mod_showFacebook") == true)
            array_push($arrIDs, $this->getSocialID($params, "mod_facebookID"));

        if ($this->getShowSocial($params, "mod_showTwitter") == true)
            array_push($arrIDs, $this->getSocialID($params, "mod_twitterID"));

        if ($this->getShowSocial($params, "mod_showGooglePlus") == true)
            array_push($arrIDs, $this->getSocialID($params, "mod_googlePlusID"));

        if ($this->getShowSocial($params, "mod_showGooglePlus") == true)
            array_push($arrIDs, $this->getSocialID($params, "mod_googlePlusAPI"));

        if ($this->getShowSocial($params, "mod_showPinterest") == true)
            array_push($arrIDs, $this->getSocialID($params, "mod_pinterestID"));

        if ($this->getShowSocial($params, "mod_showLinkedIn") == true)
            array_push($arrIDs, $this->getSocialID($params, "mod_LinkedInID"));

        if ($this->getShowSocial($params, "mod_showFacebookLikebox") == true)
            array_push($arrIDs, $this->getSocialID($params, "mod_FacebookLikeboxID"));

        if ($this->getShowSocial($params, "mod_showFacebookRecommentdation") == true)
            array_push($arrIDs, $this->getSocialID($params, "mod_FacebookRecimmentdationID"));

        if ($this->getShowSocial($params, "mod_showStumbleupon") == true)
            array_push($arrIDs, $this->getSocialID($params, "mod_StumbleUponID"));

        if ($this->getShowSocial($params, "mod_showTumblr") == true)
            array_push($arrIDs, $this->getSocialID($params, "mod_TumblrID"));

        if ($this->getShowSocial($params, "mod_showYoutube") == true)
            array_push($arrIDs, $this->getSocialID($params, "mod_YoutubeChannel"));

        if ($this->getShowSocial($params, "mod_showVimeo") == true)
            array_push($arrIDs, $this->getSocialID($params, "mod_VimeoID"));

        if ($this->getShowSocial($params, "mod_showInstagram") == true)
            array_push($arrIDs, $this->getSocialID($params, "mod_InstagramID"));

        if ($this->getShowSocial($params, "mod_showRSS") == true)
            array_push($arrIDs, $this->getSocialID($params, "mod_RSSID"));

        if ($this->getShowSocial($params, "mod_showFlickr") == true)
            array_push($arrIDs, $this->getSocialID($params, "mod_FlickrID"));

        if ($this->getShowSocial($params, "mod_showDelicious") == true)
            array_push($arrIDs, $this->getSocialID($params, "mod_DeliciousID"));

        if ($this->getShowSocial($params, "mod_showDigg") == true)
            array_push($arrIDs, $this->getSocialID($params, "mod_DiggID"));

        if ($this->getShowSocial($params, "mod_showLastfm") == true)
            array_push($arrIDs, $this->getSocialID($params, "mod_LastfmID"));

        if ($this->getShowSocial($params, "mod_showDribbble") == true)
            array_push($arrIDs, $this->getSocialID($params, "mod_DribbbleID"));

        if ($this->getShowSocial($params, "mod_showDeviantART") == true)
            array_push($arrIDs, $this->getSocialID($params, "mod_DeviantARTID"));

        // loai bo nhung item rong
        $arrIDs = array_filter($arrIDs);

        // tra ve chuoi chua id cac social duoc ngan cach = dau ","
        return implode(", ", $arrIDs);
    }

    // option facebook
    function getFacebookOptions($params) {
        $mod_facebookTitle = $params->get('mod_facebookTitle', 'Facebook');
        $mod_facebookFollow = $params->get('mod_facebookFollow', 'Find us on Facebook');
        $mod_facebookRSSType = $params->get('mod_facebookRSSType', 'content');
        $mod_facebookLimit = $params->get('mod_facebookLimit', 10);

        $FacebookOptions = 'facebook:{';
        $FacebookOptions .="title:'$mod_facebookTitle',";
        $FacebookOptions.="follow:'$mod_facebookFollow',";
        $FacebookOptions.="text:'$mod_facebookRSSType',";
        $FacebookOptions .="limit:$mod_facebookLimit";

        $FacebookOptions.='}';
        return $FacebookOptions;
    }

    // option likefacebook
    function getFacebookLikeoptions($params) {
        $mod_facebookLikeboxStream = $params->get('mod_facebookLikeboxStream', 1) ? 'true' : 'false';
        $mod_facebookLikeboxHeader = $params->get('mod_facebookLikeboxHeader', 1) ? 'true' : 'false';
        $mod_facebookLikeboxLimit = $params->get('mod_facebookLikeboxLimit', 36);

        $FacebookLikeoptions = 'fblike:{';
        $FacebookLikeoptions.="stream:$mod_facebookLikeboxStream,";
        $FacebookLikeoptions.="header:$mod_facebookLikeboxHeader,";
        $FacebookLikeoptions .= "limit:$mod_facebookLikeboxLimit";

        $FacebookLikeoptions.='}';
        return $FacebookLikeoptions;
    }

    // option Facebook Recomment dation
   /* function getFacebookRecommentdationOptions($params) {
        $mod_facebookRecommentdationHeader = $params->get('mod_facebookRecommentdationHeader', 1) ? 'true' : 'false';

        $FacebookRecommentdationOptions = 'fbrec: {';
        $FacebookRecommentdationOptions .= "header:$mod_facebookRecommentdationHeader";

        $FacebookRecommentdationOptions .= '}';

        return $FacebookRecommentdationOptions;
    }*/

    // option twitter
    function getTwitterOptions($params) {
        $mod_twitterTitle = $params->get('mod_twitterTitle', 'Latest Tweets');
        $mod_twitterFollow = $params->get('mod_twitterFollow', 'Follow on Twitter');
        $mod_twitterThumb = $params->get('mod_twitterThumb', 1) ? 'true' : 'false';
        $mod_twitterRetweets = $params->get('mod_twitterRetweets', 1) ? 'true' : 'false';
        $mod_twitterReplies = $params->get('mod_twitterReplies', 1) ? 'true' : 'false';
        $mod_twitterLimit = $params->get('mod_twitterLimit', 10);
        $mod_twitterUrl = JUri::base() . 'modules/mod_jux_social_tabs/twitter.php';

        $twitterOptions = 'twitter: {';
        $twitterOptions .= "title:'$mod_twitterTitle',";
        $twitterOptions .= "follow:'$mod_twitterFollow',";
        $twitterOptions .= "thumb:$mod_twitterThumb,";
        $twitterOptions .= "retweets:$mod_twitterRetweets,";
        $twitterOptions .= "replies:$mod_twitterReplies,";
        $twitterOptions .= "url:'$mod_twitterUrl',";
        $twitterOptions .= "limit:$mod_twitterLimit";


        $twitterOptions .= '}';

        return $twitterOptions;
    }

    function getGoogleOptions($params) {
        $mod_googlePlusTitle = $params->get('mod_googlePlusTitle', 'Google+');
		$mod_googlePlusFollow = $params->get('mod_googlePlusFollow', 'Add to Circles');
		$mod_googlePlusLimit = $params->get('mod_googlePlusLimit', 10);
		$mod_googleID = $params->get('mod_googlePlusID', '');
		$mod_googleApi = $params->get('mod_googlePlusAPI', '');

		$googleOptions = 'google: {';
		$googleOptions .= "title:'$mod_googlePlusTitle',";
		$googleOptions .= "follow:'$mod_googlePlusFollow',";
		$googleOptions .= "limit:$mod_googlePlusLimit,";
		$googleOptions .= "api_key:'$mod_googleApi',";
		$googleOptions .= "pageId:'$mod_googleID'";

		$googleOptions .= '}';
		return $googleOptions;
    }

    function getPinterestOptions($params) {
        $mod_pinterestTitle = $params->get('mod_pinterestTitle', 'Pinterest+');
        $mod_pinterestFollow = $params->get('mod_pinterestFollow', 'Find us on Pinterest');
        $mod_pinterestLimit = $params->get('mod_pinterestLimit', 10);


        $pinterestOptions = 'pinterest: {';
        $pinterestOptions .= "title:'$mod_pinterestTitle',";
        $pinterestOptions .= "follow:'$mod_pinterestFollow',";
        $pinterestOptions .= "limit:$mod_pinterestLimit";


        $pinterestOptions .= '}';

        return $pinterestOptions;
    }

    function getRSSOptions($params) {
        $mod_RSSTitle = $params->get('mod_RSSTitle', 'Subscribe to our RSS');
        $mod_RSSFollow = $params->get('mod_RSSFollow', 'Subscribe');
        $mod_RSSLimit = $params->get('mod_RSSLimit', 10);


        $rssOptions = 'rss: {';
        $rssOptions .= "title:'$mod_RSSTitle',";
        $rssOptions .= "follow:'$mod_RSSFollow',";
        $rssOptions .= "limit:$mod_RSSLimit";


        $rssOptions .= '}';

        return $rssOptions;
    }

    function getYoutubeOptions($params) {
        $mod_YoutubeTitle = $params->get('mod_YoutubeTitle', '');
        $mod_YoutubeUserID = $params->get('mod_YoutubeUserID', '');
        $mod_YoutubeSubscribe = $params->get('mod_YoutubeSubscribe', 1) ? 'true' : 'false';
        $mod_YoutubeLimit = $params->get('mod_YoutubeLimit', 10);


        $youtubeOptions = 'youtube: {';
        $youtubeOptions .= "title:'$mod_YoutubeTitle',";
        $youtubeOptions .= "subscribe:$mod_YoutubeSubscribe,";
        $youtubeOptions .= "limit:$mod_YoutubeLimit,";
        $youtubeOptions .= "id:'$mod_YoutubeUserID'";


        $youtubeOptions .= '}';

        return $youtubeOptions;
    }

    function getinstagramOption($params) {
        $mod_InstagramTitle = $params->get('mod_InstagramTitle', 'Instagram');
        $mod_InstagramLimit = $params->get('mod_InstagramLimit', 10);
        $mod_InstagramClientID = $params->get('mod_InstagramClientID', '.');
        $mod_InstagramRedirectURI = $params->get('mod_InstagramRedirectURI', '.');
        $mod_InstagramAccessToken = $params->get('mod_InstagramAccessToken', '.');


        $instagramOption = 'instagram: {';
        $instagramOption .= "clientId:'$mod_InstagramClientID',";
        $instagramOption .= "redirectUrl:'$mod_InstagramRedirectURI',";
        $instagramOption .= "accessToken:'$mod_InstagramAccessToken',";
        $instagramOption .= "limit:$mod_InstagramLimit,";
        $instagramOption .= "title:'$mod_InstagramTitle'";


        $instagramOption .= '}';

        return $instagramOption;
    }

    function getVimeoOptions($params) {
        $mod_VimeoTitle = $params->get('mod_VimeoTitle', 'Subscribe to our RSS');
        $mod_VimeoFollow = $params->get('mod_VimeoFollow', 'Subscribe');
        $mod_VimeoLimit = $params->get('mod_VimeoLimit', 10);
	$mod_VimeoFeed = 'all_videos';

        $vimeoOptions = 'vimeo: {';
        $vimeoOptions .= "title:'$mod_VimeoTitle',";
        $vimeoOptions .= "follow:'$mod_VimeoFollow',";
	$vimeoOptions .= "feed:'$mod_VimeoFeed',";
        $vimeoOptions .= "limit:$mod_VimeoLimit";
	
        $vimeoOptions .= '}';

        return $vimeoOptions;
    }

    function getFlickrOptions($params) {
        $mod_FlickrTitle = $params->get('mod_FlickrTitle', 'Subscribe to our RSS');
        $mod_FlickrLimit = $params->get('mod_FlickrLimit', 20);


        $flickrOptions = 'flickr: {';
        $flickrOptions .= "title:'$mod_FlickrTitle',";
        $flickrOptions .= "limit:$mod_FlickrLimit";


        $flickrOptions .= '}';

        return $flickrOptions;
    }

    function getStumbleuponOptions($params) {
        $mod_StumbleuponTitle = $params->get('mod_StumbleuponTitle', 'Stumbleupon');
        $mod_StumbleuponFollow = $params->get('mod_StumbleuponFollow', 'Follow');
        $mod_StumbleuponLimit = $params->get('mod_StumbleuponLimit', 10);


        $stumbleuponOptions = 'stumbleupon: {';
        $stumbleuponOptions .= "title:'$mod_StumbleuponTitle',";
        $stumbleuponOptions .= "follow:'$mod_StumbleuponFollow',";
        $stumbleuponOptions .= "limit:$mod_StumbleuponLimit";


        $stumbleuponOptions .= '}';

        return $stumbleuponOptions;
    }

    function getTumblrOptions($params) {
        $mod_TumblrTitle = $params->get('mod_TumblrTitle', 'Tumblr');
        $mod_TumblrFollow = $params->get('mod_TumblrFollow', 'Follow');
        $mod_TumblrLimit = $params->get('mod_TumblrLimit', 10);


        $tumblrOptions = 'tumblr: {';
        $tumblrOptions .= "title:'$mod_TumblrTitle',";
        $tumblrOptions .= "follow:'$mod_TumblrFollow',";
        $tumblrOptions .= "limit:$mod_TumblrLimit";


        $tumblrOptions .= '}';

        return $tumblrOptions;
    }

    function getDeliciousOptions($params) {
        $mod_DeliciousTitle = $params->get('mod_DeliciousTitle', 'Delicious');
        $mod_DeliciousFollow = $params->get('mod_DeliciousFollow', 'Follow on Delicious');
        $mod_DeliciousLimit = $params->get('mod_DeliciousLimit', 10);


        $deliciousOptions = 'delicious: {';
        $deliciousOptions .= "title:'$mod_DeliciousTitle',";
        $deliciousOptions .= "follow:'$mod_DeliciousFollow',";
        $deliciousOptions .= "limit:$mod_DeliciousLimit";


        $deliciousOptions .= '}';

        return $deliciousOptions;
    }

    function getDiggOptions($params) {
        $mod_DiggTitle = $params->get('mod_DiggTitle', 'Latest Diggs');
        $mod_DiggLimit = $params->get('mod_DiggLimit', 10);


        $diggOptions = 'digg: {';
        $diggOptions .= "title:'$mod_DiggTitle',";
        $diggOptions .= "limit:$mod_DiggLimit";


        $diggOptions .= '}';

        return $diggOptions;
    }

    function getLastfmOptions($params) {
        $mod_LastfmTitle = $params->get('mod_LastfmTitle', 'Last.fm');
        $mod_LastfmLimit = $params->get('mod_LastfmLimit', 20);


        $LastfmOptions = 'lastfm: {';
        $LastfmOptions .= "title:'$mod_LastfmTitle',";
        $LastfmOptions .= "limit:$mod_LastfmLimit";


        $LastfmOptions .= '}';

        return $LastfmOptions;
    }

    function getDribbleOptions($params) {
        $mod_DribbbleTitle = $params->get('mod_DribbbleTitle', 'Dribbble');
        $mod_DribbbleFollow = $params->get('mod_DribbbleFollow', 'Follow on Dribbble');
        $mod_DribbbleLimit = $params->get('mod_DribbbleLimit', 10);


        $dribbleOptions = 'dribbble: {';
        $dribbleOptions .= "title:'$mod_DribbbleTitle',";
        $dribbleOptions .= "follow:'$mod_DribbbleFollow',";
        $dribbleOptions .= "limit:$mod_DribbbleLimit";


        $dribbleOptions .= '}';

        return $dribbleOptions;
    }

    function getDeviantARTOptions($params) {
        $mod_DeviantARTTitle = $params->get('mod_DeviantARTTitle', 'deviantART');
        $mod_DeviantARTFollow = $params->get('mod_DeviantARTFollow', 'Follow');
        $mod_DeviantARTLimit = $params->get('mod_DeviantARTLimit', 10);


        $deviantARTOptions = 'deviantart: {';
        $deviantARTOptions .= "title:'$mod_DeviantARTTitle',";
        $deviantARTOptions .= "follow:'$mod_DeviantARTFollow',";
        $deviantARTOptions .= "limit:$mod_DeviantARTLimit";


        $deviantARTOptions .= '}';

        return $deviantARTOptions;
    }

    /*
     * For Tabs Options
     */

    // return location & align
    function getLocation($params) {
        $location = $params->get('mod_location');
        $value = "";
        switch ($location) {
            case 'top-right':
                $value = "location: 'top', align: 'right'";
                break;
            case 'top-left':
                $value = "location: 'top', align: 'left'";
                break;
            case 'middle-right':
                $value = "location: 'right', align: 'top'";
                break;
            case 'middle-left':
                $value = "location: 'left', align: 'top'";
                break;
            case 'bottom-right':
                $value = "location: 'bottom', align: 'right'";
                break;
            case 'bottom-left':
                $value = "location: 'bottom', align: 'left'";
                break;
        }
        return $value;
    }

    // hàm truyền trực tiếp giá trị của Method
    function getMethod($params) {
        return "method: '" . $params->get('mod_method') . "'";
    }

    // return "position: fixed" ...
    function getPosition($params) {
        return "position: '" . $params->get('mod_position') . "'";
    }

    // return "fixed" or "absolute"
    function getPositionValue($params) {
        return $params->get('mod_position');
    }

    // hàm chỉnh vj trí xuất hiện trên wed
    function getOffset($params) {
        $offset = $params->get('mod_offset');
        $defaultValue = "offset: 20";

        //check rong
        if ($offset == "")
            return $defaultValue;
        else {
            //check so
            if (is_numeric($offset))
                return "offset: " . $offset;
            else
                return $defaultValue;
        }
    }

    // hàm chỉnh chiều rộng của social
    function getWidth($params) {
        $width = $params->get('mod_width');
        $defaultValue = "width: 300";
        //check rong
        if ($width == "")
            return $defaultValue;
        else {
            //check so
            if (is_numeric($width))
                return "width: " . $width;
            else
                return $defaultValue;
        }
    }

    // hàm chỉnh chiều cao của social
    function getHeight($params) {
        $height = $params->get('mod_height');
        $defaultValue = "height: 500";
        //check rong
        if ($height == "")
            return $defaultValue;
        else {
            //check so
            if (is_numeric($height))
                return "height: " . $height;
            else
                return $defaultValue;
        }
    }

    // hàm chỉ định chỉ số của tab mặc định sẽ được focus
    function getDefaultTabIndex($params) {
        $userValue = $params->get("mod_tabFirstIndex");
        $socialNames = $this->getSocialNamesArray($params);
        $defaltValue = "start: 0";
        $index = array_search($userValue, $socialNames);
        if ($index)
            return "start: " . $index;
        else
            return $defaltValue;
    }

    // hàm điều khiển thời gian lấy dữ liệu trong social
    function getRequestTime($params) {
        $requestTime = $params->get('mod_requestTime');
        // default = 8' (số giây X 1000 = số giây muốn nhập)
        $defaultValue = "delay: 8000";

        // 1. check rong
        if ($requestTime == "")
            return $defaultValue;
        else {
            // 2. check so
            if (is_numeric($requestTime))
                return 'delay: ' . (intval($requestTime)) * 1000;
            else
                return $defaultValue;
        }
    }

    // hàm điều hướng lên/xuống của dữ liệu trong social
    function getDisplayDirection($params) {
        return "direction :'" . $params->get('mod_displayDirection') . "'";
    }

    // hàm điều hướng dữ liệu và thời gian lấy dữ liệu của social
    function getDataDisplay($params) {
        $helper = new modSocialmediaHelper();
        return "rotate: { " . $this->getRequestTime($params) . "," . $this->getDisplayDirection($params) . " }";
    }

    // return "loadOpen: true" ...
    function getLoadOpen($params) {
        $defaultvalue = "";
        if ($params->get('mod_method') == 'slide')
            return "loadOpen:" . ($params->get('mod_loadOpen') == 1 ? "true" : "false");
        else
            return $defaultvalue;
    }

    // return "autoClose: true" ...
    function getAutoClose($params) {
        $defaultvalue = "";
        if ($params->get('mod_method') == 'slide')
            return "autoClose:" . ($params->get('mod_autoClose') == 1 ? "true" : "false");
        else
            return $defaultvalue;
    }

    //create licence key twitter
   static function createTwitterLicenseKey($params) {
        // Array custom key
        $arrays = array('consumer_key' => $params->get('mod_twitter_consumer_key',''),
            'consumer_secret' => $params->get('mod_twitter_consumer_secret',''),
            'oauth_access_token' => $params->get('mod_twitter_oauth_access_token',''),
            'oauth_access_token_secret' => $params->get('mod_twitter_oauth_access_token_secret',''));

        $ini_array = parse_ini_file(JPATH_SITE . '/modules/mod_jux_social_tabs/twitter_licence_key.ini');
	if ($ini_array) {
            if ($ini_array['consumer_key'] != $arrays['consumer_key'] ||
                    $ini_array['consumer_secret'] != $arrays['consumer_secret'] ||
                    $ini_array['oauth_access_token'] != $arrays['oauth_access_token'] ||
                    $ini_array['oauth_access_token_secret'] != $arrays['oauth_access_token_secret']
            ) {
                $file = JPATH_SITE . '/modules/mod_jux_social_tabs/twitter_licence_key.ini';
                $fh = fopen($file, 'w') or die("can't open file");
                foreach ($arrays as $key => $array) {
                    fwrite($fh, $key . '="' . $array . '"' . "\n");
                }
                fclose($fh);
            }
        } else {
            $file = JPATH_SITE . '/modules/mod_jux_social_tabs/twitter_licence_key.ini';
            $fh = fopen($file, 'w') or die("can't open file");
            foreach ($arrays as $key => $array) {
                fwrite($fh, $key . '="' . $array . '"' . "\n");
            }
            fclose($fh);
        }
    }

}
