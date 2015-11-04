<?php
/**
 * Webmaster Site Verification is a plugin for Joomla 1.6, 1.7, 2.5 and 3.0+ which easily allows webmasters to verify with such sites as Google, Bing, Alexa, Norton, Yandex, WOT, Pinterest, Majestic, AVG ThreatLabs and Custom Sites by adding code to the header of your main page on your site.
 * @package 	plg_webmastersiteverification
 * @version		v2.3
 * @author		bybe.net
 * @link		https://www.bybe.net/
 * @copyright 	(C)2015 ByBe. All rights reserved.
 * @license 	GNU/GPL - http://www.gnu.org/licenses/gpl-2.0.html
 * This software is provided "as is", without warranty of any kind. Use it at your OWN risk.
 */

/* no direct access */
defined('_JEXEC') or die('Restricted access');

jimport('joomla.plugin.plugin');

class plgSystemWebmasterSiteVerification extends JPlugin{
	function plgSystemWebmasterSiteVerification(& $subject, $params){
		parent::__construct($subject, $params);
	}
	function onAfterInitialise(){
		$baseurl = JURI::base();
		$currenturl = JURI::current();

		// Only Displays on Main Page
		if ($baseurl != $currenturl){return;}

		// Plugin Parameters
		$plgparams = $this->params;

		// Site Verification ID's (Tab Basic)
		$googleid = $plgparams->get('googleid');
		$bingid = $plgparams->get('bingid');
		$wotid = $plgparams->get('wotid');
		$alexaid = $plgparams->get('alexaid');
		$nortonid = $plgparams->get('nortonid');
		$yandexid = $plgparams->get('yandexid');
		$pinterestid = $plgparams->get('pinterestid');
		$majesticid = $plgparams->get('majesticid');
		$avgid = $plgparams->get('avgid');
		$flippaid = $plgparams->get('flippaid');
		$globalsignid = $plgparams->get('globalsignid');
		$bitlyid = $plgparams->get('bitlyid');

		// Site Verification ID's (Tab Adnetworks)
		$admitadid = $plgparams->get('admitadid');
		$eroid = $plgparams->get('eroid');
		$inmobiid = $plgparams->get('inmobiid');
		$plugrushid = $plgparams->get('plugrushid');

		// Custom Site Vefication Metas (Tab Advanced)
		$customname1 = $this->params->get('name1');
		$customvalue1 = $this->params->get('content1');
		$customname2 = $this->params->get('name2');
		$customvalue2 = $this->params->get('content2');
		$customname3 = $this->params->get('name3');
		$customvalue3 = $this->params->get('content3');
		$customname4 = $this->params->get('name4');
		$customvalue4 = $this->params->get('content4');
		$customname5 = $this->params->get('name5');
		$customvalue5 = $this->params->get('content5');
		$customname6 = $this->params->get('name6');
		$customvalue6 = $this->params->get('content6');
		$customname7 = $this->params->get('name7');
		$customvalue7 = $this->params->get('content7');
		$customname8 = $this->params->get('name8');
		$customvalue8 = $this->params->get('content8');
		$customname9 = $this->params->get('name9');
		$customvalue9 = $this->params->get('content9');

		// Document
		$document = JFactory::getDocument();

		// Meta Tags (Tab Basic)
		if(!empty($googleid)){$document->setMetaData('google-site-verification', $googleid);}
		if(!empty($bingid)){$document->setMetaData('msvalidate.01', $bingid);}
		if(!empty($wotid)){$document->setMetaData('wot-verification', $wotid);}
		if(!empty($alexaid)){$document->setMetaData('alexaVerifyID', $alexaid);}
		if(!empty($nortonid)){$document->setMetaData('norton-safeweb-site-verification', $nortonid);}
		if(!empty($yandexid)){$document->setMetaData('yandex-verification', $yandexid);}
		if(!empty($pinterestid)){$document->setMetaData('p:domain_verify', $pinterestid);}
		if(!empty($majesticid)){$document->setMetaData('majestic-site-verification', $majesticid);}
		if(!empty($avgid)){$document->setMetaData('avgthreatlabs-verification', $avgid);}		
		if(!empty($flippaid)){$document->setMetaData('verifyownership', $flippaid);}
		if(!empty($globalsignid)){$document->setMetaData('globalsign-domain-verification', $globalsignid);}
		if(!empty($bitlyid)){$document->setMetaData('bitly-verification', $bitlyid);}		

		// Meta Tags (Tab Adnetworks)
		if(!empty($admitadid)){$document->setMetaData('verify-admitad', $admitadid);}
		if(!empty($eroid)){$document->setMetaData('ero_verify', $eroid);}
		if(!empty($inmobiid)){$document->setMetaData('inmobi-site-verification', $inmobiid);}
		if(!empty($plugrushid)){$document->setMetaData('prVerify', $plugrushid);}		

		// Meta Tags (Tab Advanced)
		if(!empty($customname1)&&!empty($customvalue1)){$document->setMetaData($customname1, $customvalue1);}
		if(!empty($customname2)&&!empty($customvalue2)){$document->setMetaData($customname2, $customvalue2);}
		if(!empty($customname3)&&!empty($customvalue3)){$document->setMetaData($customname3, $customvalue3);}
		if(!empty($customname4)&&!empty($customvalue4)){$document->setMetaData($customname4, $customvalue4);}
		if(!empty($customname5)&&!empty($customvalue5)){$document->setMetaData($customname5, $customvalue5);}
		if(!empty($customname6)&&!empty($customvalue6)){$document->setMetaData($customname6, $customvalue6);}
		if(!empty($customname7)&&!empty($customvalue7)){$document->setMetaData($customname7, $customvalue7);}
		if(!empty($customname8)&&!empty($customvalue8)){$document->setMetaData($customname8, $customvalue8);}
		if(!empty($customname9)&&!empty($customvalue9)){$document->setMetaData($customname9, $customvalue9);}
	}
}
?>