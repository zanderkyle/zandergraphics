<?php
/**
 * @package   AkeebaTicketSystem
 * @copyright Copyright (c)2011-2015 Nicholas K. Dionysopoulos
 * @license   GNU General Public License version 3, or later
 */

namespace Akeeba\TicketSystem\Admin\Model;

defined('_JEXEC') or die;

use FOF30\Container\Container;
use FOF30\Model\DataModel;
use JText;

/**
 * Model for custom field defintions
 *
 * @property  int              $ats_customfield_id  Primary key
 * @property  string           $title               Field title
 * @property  string           $slug                Field alias
 * @property  string           $show                One of 'all', 'category', 'notlevel'
 * @property  string           $type                Field type
 * @property  string           $options             Field options
 * @property  string           $default             Default value
 * @property  bool             $allow_empty         Should we allow empty values?
 * @property  string           $valid_label         Translation key to show next to a valid field
 * @property  string           $invalid_label       Translation key to show next to an invalid field
 * @property  string           $params              Field parameters
 *
 * Filters:
 *
 * @method  $this  ats_customfield_id()  akeebasubs_customfield_id(int $v)
 * @method  $this  title()                      title(string $v)
 * @method  $this  slug()                       slug(string $v)
 * @method  $this  show()                       show(string $v)
 * @method  $this  type()                       type(string $v)
 * @method  $this  options()                    options(string $v)
 * @method  $this  default()                    default(string $v)
 * @method  $this  allow_empty()                allow_empty(bool $v)
 * @method  $this  valid_label()                valid_label(string $v)
 * @method  $this  invalid_label()              invalid_label(string $v)
 * @method  $this  enabled()                    enabled(bool $v)
 * @method  $this  ordering()                   ordering(int $v)
 * @method  $this  created_by()                 created_by(int $v)
 * @method  $this  created_on()                 created_on(string $v)
 * @method  $this  modified_by()                modified_by(int $v)
 * @method  $this  modified_on()                modified_on(string $v)
 *
 * Relations:
 *
 * @property   Categories[]    cats                 List of categories where this custom field should be displayed
 *
 */
class CustomFields extends DefaultDataModel
{

	var $preventCatCheck = false;

	/**
	 * Public constructor.
	 *
	 * @param   Container $container The configuration variables to this model
	 * @param   array     $config    Configuration values for this model
	 *
	 * @throws \FOF30\Model\DataModel\Exception\NoTableColumns
	 */
	public function __construct(Container $container, array $config = array())
	{
		parent::__construct($container, $config);

		$this->addBehaviour('Filters');

		$this->belongsToMany('cats', 'Categories', 'ats_customfield_id', 'id',
			'#__ats_customfields_cats', 'ats_customfield_id', 'catid');
	}

	/**
	 * Check the data for validity.
	 *
	 * @return  static  Self, for chaining
	 *
	 * @throws \RuntimeException  When the data bound to this record is invalid
	 */
	public function check()
	{
		$this->assertNotEmpty($this->show, 'COM_ATS_CUSTOMFIELDS_ERR_SHOW');
		$this->assertNotEmpty($this->slug, 'COM_ATS_ERR_SLUG_EMPTY');

		$pattern = '/^[a-z_][a-z0-9_\-]*$/';

		$this->assert(preg_match($pattern, $this->slug), 'COM_ATS_ERR_SLUG_INVALID');

		$this->slug = str_replace('-', '_', $this->slug);

		parent::check();
	}

	protected function onAfterSave()
	{
		// In some situation (ie reordering) I don't want to perform these check on categories
		if ($this->preventCatCheck)
		{
			return;
		}

		// TODO we should rewrite these steps using the relations, not doing it manually
		$db      = $this->getDbo();
		$app     = \JFactory::getApplication();
		$cats    = array();
		$rawcats = $this->input->get('cats', array(), 'array');

		// Let's wipe out the customfield-category table
		// I always have to do that, otherwise I could have some problems when
		// a field changes from 'all' to 'category'
		$query = $db->getQuery(true)
		            ->delete($db->qn('#__ats_customfields_cats'))
		            ->where($db->qn('ats_customfield_id') . ' = ' . $db->q($this->ats_customfield_id));

		try
		{
			$db->setQuery($query)->execute();
		}
		catch (\Exception $e)
		{
			$app->enqueueMessage(JText::_('COM_ATS_CUSTOMFIELDS_ERR_DELETE_CATS'), 'error');

			return;
		}

		// Let's skip the first row that have no value
		foreach ($rawcats as $raw)
		{
			if ($raw)
			{
				$cats[] = $raw;
			}
		}

		if ($cats && $this->show == 'category')
		{
			$query = $db->getQuery(true)
			            ->insert($db->qn('#__ats_customfields_cats'))
			            ->columns($db->qn('ats_customfield_id') . ', ' . $db->qn('catid'));

			foreach ($cats as $cat)
			{
				if (!$cat)
				{
					continue;
				}

				$query->values($db->q($this->ats_customfield_id) . ', ' . $db->q($cat));
			}

			try
			{
				$db->setQuery($query)->execute();
			}
			catch (\Exception $e)
			{
				$app->enqueueMessage(JText::_('COM_ATS_CUSTOMFIELDS_ERR_INSERT_CATS'), 'error');

				return;
			}
		}
		// I said that I want to show the field on specific categories, but I didn't say which ones...
		elseif (!$cats && $this->show == 'category')
		{
			$this->show = 'all';
			$this->save();
			$app->enqueueMessage(JText::_('COM_ATS_CUSTOMFIELDS_ERR_EMPTY_CATS'), 'error');

			return;
		}
	}

	/**
	 * Deletes every references from the category-customfield table
	 *
	 * @param   int $oid Custom field primary key
	 */
	protected function onAfterDelete($oid)
	{
		$db = $this->getDbo();

		$query = $db->getQuery(true)
		            ->delete($db->qn('#__ats_customfields_cats'))
		            ->where($db->qn('ats_customfield_id') . ' = ' . $db->q($oid));

		$db->setQuery($query)->execute();
	}
}