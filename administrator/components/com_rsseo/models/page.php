<?php
/**
* @version 1.0.0
* @package RSSeo! 1.0.0
* @copyright (C) 2009-2012 www.rsjoomla.com
* @license GPL, http://www.gnu.org/copyleft/gpl.html
*/

defined('_JEXEC') or die();
jimport( 'joomla.application.component.model' ); 

class rsseoModelPage extends JModelAdmin
{
	/**
	 * @var		string	The prefix to use with controller messages.
	 * @since	1.6
	 */
	protected $text_prefix = 'COM_RSSEO';

	
	/**
	 * Returns a Table object, always creating it.
	 *
	 * @param	type	The table type to instantiate
	 * @param	string	A prefix for the table class name. Optional.
	 * @param	array	Configuration array for model. Optional.
	 *
	 * @return	JTable	A database object
	*/
	public function getTable($type = 'Page', $prefix = 'rsseoTable', $config = array()) {
		return JTable::getInstance($type, $prefix, $config);
	}
	
	/**
	 * Method to get a single record.
	 *
	 * @param	integer	The id of the primary key.
	 *
	 * @return	mixed	Object on success, false on failure.
	 */
	public function getItem($pk = null) {
		if ($item = parent::getItem($pk)) {
			// Convert the robots field to an array.
			$registry = new JRegistry;
			$registry->loadString($item->robots);
			$item->robots = $registry->toArray();
			
			// Get density params.
			$registry = new JRegistry;
			$registry->loadString($item->densityparams);
			$item->densityparams = $registry->toArray();
			
			// Get images without alt attribure
			$registry = new JRegistry;
			$registry->loadString($item->imagesnoalt);
			$item->imagesnoalt = $registry->toArray();
			
			// Get images without width and height attribure
			$registry = new JRegistry;
			$registry->loadString($item->imagesnowh);
			$item->imagesnowh = $registry->toArray();
			
			switch($item->grade) {
				case ($item->grade >= 0 && $item->grade < 33): 
					$item->color = 'red'; 
				break;
				
				case ($item->grade >= 33 && $item->grade < 66):
					$item->color = 'orange'; 
				break;
				
				case -1:
					$item->color = '';
				break;
				
				default:
					$item->color = 'green'; 
				break;
			}
			
		}
		
		return $item;
	}
	
	/**
	 * Method to get the record form.
	 *
	 * @param	array	$data		Data for the form.
	 * @param	boolean	$loadData	True if the form is to load its own data (default case), false if not.
	 *
	 * @return	mixed	A JForm object on success, false on failure
	 * @since	1.6
	 */
	public function getForm($data = array(), $loadData = true) {
		$jinput = JFactory::getApplication()->input;
		
		// Get the form.
		$form = $this->loadForm('com_rsseo.page', 'page', array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form))
			return false;
		
		if ($jinput->get('id')) {
			$form->setFieldAttribute('url', 'readonly', 'true');
			$form->setFieldAttribute('level', 'readonly', 'true');
			
			if ($jinput->get('id') == 1) {
				$form->setFieldAttribute('url', 'required', 'false');
			}
			
		} else {
			$form->setValue('frequency', null, 'weekly');
			$form->setValue('priority', null, '0.5');
		}
		
		return $form;
	}
	
	/**
	 * Method to get the data that should be injected in the form.
	 *
	 * @return	mixed	The data for the form.
	 * @since	1.6
	 */
	protected function loadFormData() {
		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState('com_rsseo.edit.page.data', array());

		if (empty($data))
			$data = $this->getItem();

		return $data;
	}
	
	/**
	 * Method to toggle the "in sitemap" setting of pages.
	 *
	 * @param	array	The ids of the items to toggle.
	 * @param	int		The value to toggle to.
	 *
	 * @return	boolean	True on success.
	 */
	public function addsitemap($pks, $value = 0) {
		// Sanitize the ids.
		$pks = (array) $pks;
		JArrayHelper::toInteger($pks);

		if (empty($pks)) {
			$this->setError(JText::_('JERROR_NO_ITEMS_SELECTED'));
			return false;
		}

		try {
			$db = $this->getDbo();
			$query = $db->getQuery(true);

			$query->update('`#__rsseo_pages`')->set('`insitemap` = '.(int) $value)->where('`id` IN ('.implode(',',$pks).')');
			$db->setQuery($query);
			$db->execute();
		} catch (Exception $e) {
			$this->setError($e->getMessage());
			return false;
		}

		return true;
	}
	
	
	/**
	 * Method to remove all pages.
	 *
	 *
	 * @return	void.
	 */
	public function removeall() {
		try {
			$db		= JFactory::getDBO();
			$query	= $db->getQuery(true);
			
			// Truncate table
			$db->truncateTable('#__rsseo_pages');
			
			$query->insert($db->quoteName('#__rsseo_pages'))
					->set($db->quoteName('id').' = 1')
					->set($db->quoteName('level').' = 0')
					->set($db->quoteName('grade').' = '.$db->quote('0.00'))
					->set($db->quoteName('published').' = 1')
					->set($db->quoteName('date').' = '.$db->quote(JFactory::getDate()->toSql()));
			$db->setQuery($query);
			$db->execute();
		} catch (Exception $e) {
			$this->setError($e->getMessage());
			return false;
		}
		
		return true;
	}
	
	/**
	 * Method to save the form data.
	 *
	 * @param	array	The form data.
	 *
	 * @return	boolean	True on success.
	 * @since	1.6
	 */
	public function save($data) {
		// Initialise variables;
		$table = $this->getTable();
		$pk = (!empty($data['id'])) ? $data['id'] : (int) $this->getState($this->getName() . '.id');
		$isNew = true;

		// Load the row if saving an existing tag.
		if ($pk > 0) {
			$table->load($pk);
			$isNew = false;
		}

		// Bind the data.
		if (!$table->bind($data)) {
			$this->setError($table->getError());
			return false;
		}

		// Check the data.
		if (!$table->check()) {
			$this->setError($table->getError());
			return false;
		}

		// Store the data.
		if (!$table->store()) {
			$this->setError($table->getError());
			return false;
		}
		
		$this->setState($this->getName() . '.id', $table->id);
		
		// After store page
		require_once JPATH_ADMINISTRATOR. '/components/com_rsseo/helpers/crawler.php';
		$initialize = 0;
		$crawler = crawlerHelper::getInstance($initialize, $table->id);
		$crawler->crawl();
		
		return true;
	}
	
	public function getDetails() {
		require_once JPATH_SITE.'/administrator/components/com_rsseo/helpers/class.webpagesize.php';
		$item = $this->getItem();
		
		set_time_limit(100);
		$class = new WebpageSize(JURI::root().$item->url);
		$pages = $class->getPages();
		$total = $class->getTotal();
		
		return array('pages' => $pages, 'total' => $total);
	}
}