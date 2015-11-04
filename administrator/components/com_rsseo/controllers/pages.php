<?php
/**
* @version 1.0.0
* @package RSSeo! 1.0.0
* @copyright (C) 2009-2012 www.rsjoomla.com
* @license GPL, http://www.gnu.org/copyleft/gpl.html
*/

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

class rsseoControllerPages extends JControllerAdmin
{
	protected $text_prefix = 'COM_RSSEO_PAGES';
	
	/**
	 * Constructor.
	 *
	 * @param	array	$config	An optional associative array of configuration settings.

	 * @return	rsseoControllerPages
	 * @see		JController
	 * @since	1.6
	 */
	public function __construct($config = array()) {
		parent::__construct($config);
		
		$this->registerTask('removesitemap',	'addsitemap');
	}
	
	/**
	 *	Method to include or exculde pages from the sitemap
	 *
	 * @return	void
	 */
	public function addsitemap() {
		// Check for request forgeries
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		$ids    = JFactory::getApplication()->input->get('cid', array(), 'array');
		$values = array('addsitemap' => 1, 'removesitemap' => 0);
		$task   = $this->getTask();
		$value  = JArrayHelper::getValue($values, $task, 0, 'int');

		if (empty($ids)) {
			JError::raiseWarning(500, JText::_('JERROR_NO_ITEMS_SELECTED'));
		}
		else {
			// Get the model.
			$model = $this->getModel();

			// Publish the items.
			if (!$model->addsitemap($ids, $value)) {
				$this->setMessage($model->getError(),'error');
			}
		}
		
		$this->setRedirect('index.php?option=com_rsseo&view=pages');
	}
	
	/**
	 *	Method to remove all pages
	 *
	 * @return	void
	 */
	public function removeall() {
		// Check for request forgeries
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		
		// Get the model.
		$model = $this->getModel();

		// Publish the items.
		if (!$model->removeall()) {
			$this->setMessage($model->getError(),'error');
		} else {
			$this->setMessage(JText::_('COM_RSSEO_ALL_PAGES_DELETED'));
		}
		
		$this->setRedirect('index.php?option=com_rsseo&view=pages');
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
	public function getModel($name = 'Page', $prefix = 'rsseoModel', $config = array('ignore_request' => true)) {
		$model = parent::getModel($name, $prefix, $config);
		return $model;
	}
}