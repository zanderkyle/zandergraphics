<?php
/**
* @version 1.0.0
* @package RSSEO! 1.0.0
* @copyright (C) 2009-2012 www.rsjoomla.com
* @license GPL, http://www.gnu.org/copyleft/gpl.html
*/

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

class rsseoControllerKeywords extends JControllerAdmin
{
	protected $text_prefix = 'COM_RSSEO_KEYWORDS';
	
	/**
	 * Constructor.
	 *
	 * @param	array	$config	An optional associative array of configuration settings.

	 * @return	rsseoControllerKeywords
	 * @see		JController
	 * @since	1.6
	 */
	public function __construct($config = array()) {
		parent::__construct($config);
	}
	
	/**
	 * Proxy for getModel.
	 *
	 * @param	string	$name	The name of the model.
	 * @param	string	$prefix	The prefix for the PHP class name.
	 *
	 * @return	JModel
	 * @since	1.6
	 */
	public function getModel($name = 'Keyword', $prefix = 'rsseoModel', $config = array('ignore_request' => true)) {
		$model = parent::getModel($name, $prefix, $config);
		return $model;
	}
	
	/**
	 * Method to refresh a keyword
	 *
	 * @return	JSON
	 */
	public function refresh() {
		$db		= JFactory::getDBO();
		$query	= $db->getQuery(true);
		$id		= JFactory::getApplication()->input->getInt('id');
		$config	= rsseoHelper::getConfig();
		
		$query->clear();
		$query->select('keyword, position')->from('`#__rsseo_keywords`')->where('id = '.$id);
		$db->setQuery($query);
		$table = $db->loadObject();
		
		require_once JPATH_ADMINISTRATOR. '/components/com_rsseo/helpers/keywords.php';
		$keyword = keywordsHelper::getInstance($id, $table->keyword, $table->position);
		$values = $keyword->check();
		
		echo $values;
		JFactory::getApplication()->close();
	}
}