<?php
/**
 * Joomla! component Creative Contact Form
 *
 * @version $Id: 2012-04-05 14:30:25 svn $
 * @author creative-solutions.net
 * @package Creative Contact Form
 * @subpackage com_creativecontactform
 * @license GNU/GPL
 *
 */

// no direct access
defined('_JEXEC') or die('Restircted access');

class CreativecontactformHelper {
	/**
	 * Configure the Linkbar.
	 *
	 * @param	string	$vName	The name of the active view.
	 *
	 * @return	void
	 * @since	1.6
	 */
	public static function addSubmenu($title, $v, $controller = null, $image = null) {
		$enabled = false;
		$view = JRequest::getWord("view", 'creativehomepage');
		if($view == $v) {
			$img = $v;
			if($image != null) $img = $image;
			JToolBarHelper::title(   JText::_( $title).' - '.( 'Creative Contact Form' ), $img.'.png' );
			$enabled = true;
		}
		$link = 'index.php?option=com_creativecontactform&view='.$v;
		if($controller != null) $link .= '&controller='.$controller;
		
		if(JV == 'j2')
			JSubMenuHelper::addEntry( JText::_($title), $link, $enabled);
		else
			JHtmlSidebar::addEntry( JText::_($title), $link, $enabled);
	}
}