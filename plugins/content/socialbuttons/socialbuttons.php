<?php
/**
 * @package		Social Buttons
 * @subpackage	plg_social_buttons
 * @copyright	Copyright (C) 2013 Elite Developers All rights reserved.
 * @license		GNU/GPL v3 http://www.gnu.org/licenses/gpl.html
 */

defined('_JEXEC') or die( 'Restricted access' );

class plgContentSocialButtons extends JPlugin {

	public function onContentAfterTitle ( $context , &$row , &$params , $page = 0 ) {
		$app = JFactory::getApplication();
		$active = $app -> getMenu() -> getActive();
		$show = $this->params->get( 'show' );
		if ( $show ) {
			if ( !is_array( $show ) ) {
				$shows[] = $show ;
			} else {
				$shows = $show ;
			}
			
			foreach ( $shows as $va ) {
				if ( $va == 'other' ) {
					if ( ( $active->component != 'com_content' ) || ( $context != 'com_content.article' ) ) {
						return ;
					}
				} else {
					if ( ( JRequest :: getVar( 'view' ) ) == $va ) {
						return ;
					}
					if ( $va == 'frontpage' ) {
						$menu = $app->getMenu();
						if ($active == $menu->getDefault()) {
							return ;
						}
					}
				}
			}
		}
		if ( $context != 'mod_custom.content' ) {
			$exclude_cat = $this->params->get( 'exclude_cat' , 0 );
			if ( !empty( $exclude_cat ) ) {
				if ( strlen( array_search( $row->catid , $exclude_cat ) ) ) { 
					return ; 
				}
			}
			$exclude_art = $this->params->get( 'exclude_art' , '' );
			$articlesArray = explode( "," , $exclude_art );
			if( !empty( $exclude_art ) ) { 
				if ( strlen( array_search( $row->id , $articlesArray ) ) ) {
					return ; 
				}
			}
			require_once JPATH_BASE . '/components/com_content/helpers/route.php' ;
			$Itemid = JRequest::getVar( "Itemid" , "1" );
			if ( $row->id ) {
				$link = JURI::getInstance();
				$root = $link->getScheme() . "://" . $link->getHost();  
				if ( $active->component ) {
					if ( $active->component == 'com_content' ) {
						if ( $row->slug && $row->catslug ) {
							$link = JRoute::_( ContentHelperRoute::getArticleRoute( $row->slug , $row->catslug ) , false );
						} 
					}
				}
				$link = $root . $link ;
			} else {
				$jURI = &JURI::getInstance();
				$link = $jURI->toString();
			}
			$facebook_width = $this->params->get( 'facebook_width' );

			$twitter_width = $this->params->get( 'twitter_width' );
			
			$googleplus_width = $this->params->get( 'googleplus_width' ); 
			
			$linkedin_width = $this->params->get( 'linkedin_width' );
			
			$html = '' ;
			$html .= '<div style="clear:both;"></div>' ;
			$html .= '<div class="socialbuttons" style="padding-top: 5px;padding-bottom:5px; overflow: hidden; float: ' . $this->params->get( 'align' , 'left' ) . ';">' ;
			$document = JFactory::getDocument();
		    $config = JFactory::getConfig();
		    $pattern = "/<img[^>]*src\=['\"]?(([^>]*)(jpg|gif|png|jpeg))['\"]?/" ;
			preg_match( $pattern , $row->text , $matches );
			if ( !empty( $matches ) ) {
				$document->addCustomTag( '<meta property="og:image" content="' . JURI::root() . '' . $matches[1] . '"/>' );
			}
			if ( $this->params->get( 'facebook' ) == 1 ) { 
				$sitename = $config->get( 'sitename' );
				$document->addCustomTag( '<meta property="og:site_name" content="' . $sitename . '"/>' );
				$document->addCustomTag( '<meta property="og:title" content="' . $row->title . '"/>' );
				$document->addCustomTag( '<meta property="og:type" content="article"/>' );
				$document->addCustomTag( '<meta property="og:url" content="' . $link . '"/>' );
				$html .= '<div style="width: ' . $facebook_width . 'px !important; height: 20px; float: left; border: none;">' ;
				$html .= '<iframe src="https://www.facebook.com/plugins/like.php?locale=' . $this->params->get( 'facebook_language' ) . '&href=' . rawurlencode( $link ) . '&amp;layout=button_count&amp;show_faces=true&amp;action=' . $this->params->get( 'facebook_action' ) . '&amp;colorscheme=' . $this->params->get( 'facebook_color' ) . '&amp;font=' . $this->params->get( 'facebook_font' ) . '&amp;height=20" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width: ' . $this->params->get( 'facebook_width' ) . 'px; height :20px;" allowTransparency="true"></iframe>' ;
				$html .= '</div>' ;
			} else { 
				$html .= '' ; 
			}
			if ( $this->params->get( 'twitter' ) == 1 ) {
				$html .= '<div style="width: ' . $twitter_width . 'px !important; height: 20px; float: left; margin-left: 10px; border: none;">' ;
				$html .= '<a rel="nofollow" href="http://twitter.com/share" class="twitter-share-button" data-url="' . $link . '" data-count="horizontal" data-lang="en">Twitter</a><script src="https://platform.twitter.com/widgets.js" type="text/javascript"></script>' ; 
				$html .= '</div>' ;
			} else { 
				$html .= '' ; 
			}			
			if ( $this->params->get( 'google' ) == 1 ) {
				$doc = JFactory::getDocument();
				$document->addCustomTag( '<script type="text/javascript" src="https://apis.google.com/js/plusone.js">{lang: \'' . $this->params->get( 'googleplus_language' ) . '\'}</script>');
				$html .= '<div style="width: ' . $googleplus_width . 'px !important; height: 20px; float: left; margin-left: 10px; border: none;">' ;
				$html .= '<g:plusone size="medium"></g:plusone>' ;
				$html .= '</div>';
			} else {
				$html .= '' ;
			}
			if ( $this->params->get( 'linkedin' ) == 1 ) {
				$html .= '<div style="width: ' . $linkedin_width . 'px !important; height: 20px; float: left; margin-left: 10px; border: none;">' ;
				$html .= '<script type="text/javascript" src="https://platform.linkedin.com/in.js"></script><script type="IN/share" data-url="' . $link . '" data-counter="right"></script>' ; 
				$html .= '</div>' ;
			} else {
				$html .= '' ;
			}
			$html .= '</div>' ;
			$html .= '<div style="clear:both;"></div>' ;
            $position = $this->params->get( 'position' , 'above' ) ;
			if ( $this->params->get( 'show_front' ) == 1 ) {
				if ( $position == 'above' ) {
					$row->text = $html . $row->text ;
					$row->introtext = $html . $row->introtext ;
				} else {
					$row->text .= $html ;
					$row->introtext .= $html ;
				}
			} else {
				if ( $position == 'above' ) {
					$row->text = $html . $row->text ;
				} else {
					$row->text .= $html ;
				}
			}
		} 
	}
}
?>