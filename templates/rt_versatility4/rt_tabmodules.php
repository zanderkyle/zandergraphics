<?php
/**
 * @package   Versatility4 Template - RocketTheme
* @version   $Id: rt_tabmodules.php 26096 2015-01-27 14:14:12Z james $
* @author    RocketTheme, LLC http://www.rockettheme.com
* @copyright Copyright (C) 2007 - 2015 RocketTheme, LLC
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 *
 * Rockettheme Versatility4 Template uses the Joomla Framework (http://www.joomla.org), a GNU/GPLv2 content management system
 *
 */
defined( '_JEXEC' ) or die( 'Restricted index access' );

function outputTabModules(&$document, $module, $counter) {
	
	$max_mods_per_row = $document->params->get("maxModsPerRow", 3);
	
	if ($document->countModules($module["module"]) > $max_mods_per_row) {
		$cols = $max_mods_per_row;
	} else {
		$cols = $document->countModules($module["module"]);
	}

	echo "<div class=\"tab-pane\" id=\"tab-$counter-pane\">\n";
	echo "<h1 class=\"tab-title\"><span>" . $module["title"] . "</span></h1>\n";
	echo "<div class=\"padding mmpr-" . $cols . "\">\n";
	
	$renderer	= $document->loadRenderer( 'modules' );
	$options	= array( 'style' => 'rounded' );
	echo $renderer->render( $module["module"], $options, null );
	echo "</div>\n";
	echo "</div>\n";
	
}

function displayTabs(&$document) {
	global $modules_list;

	$module_count = 0;
	foreach ($modules_list as $module) {
		if ($document->countModules($module["module"]) > 0) $module_count++;
	}
	
	if ($module_count > 0) {
		echo "<script type=\"text/javascript\">
					window.addEvent('domready', function() {
						var mySlideModules = new RokSlide($('moduleslide'), {
							fx: {
								wait: true,
								duration: 1000
							},
							scrollFX: {
								transition: Fx.Transitions.Cubic.easeIn
							},
							dimensions: {
							    height: $('moduleslider-size').getCoordinates().height - 40,
							    width: $('moduleslider-size').getCoordinates().width
							},
							arrows: false
						});
					});
					</script>\n";
		echo '	<div id="tabmodules">
					<div>
						<div>
							<div>';					
		echo '<div id="moduleslide">';
		$counter = 0;

		foreach ($modules_list as $module) {
					if ($document->countModules($module["module"])) {
									outputTabModules($document, $module, $counter++);
					}
				}
		echo '</div>';
		echo '				</div>
						</div>
					</div>
				</div>';
	}
}


?>