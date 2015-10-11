/**
 * @package   Versatility4 Template - RocketTheme
* @version   $Id: roksameheight.js 26096 2015-01-27 14:14:12Z james $
* @author    RocketTheme, LLC http://www.rockettheme.com
* @copyright Copyright (C) 2007 - 2015 RocketTheme, LLC
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 *
 * Rockettheme Versatility4 Template uses the Joomla Framework (http://www.joomla.org), a GNU/GPLv2 content management system
 *
 */

var maxHeight = function(classname) {
    var divs = document.getElements(classname);
    var max = 0;
    divs.each(function(div) {
        max = Math.max(max, div.getSize().y);
    });
	divs.setStyle('height', max);
    return max;
};

window.addEvent('load', function() {
	if (!Browser.ie6) {
		maxHeight('#mainmodules .block div div div');
		maxHeight('#mainmodules2 .block div div div');
	}

	(function(){ maxHeight('div.main-height'); }).delay(500);
});
