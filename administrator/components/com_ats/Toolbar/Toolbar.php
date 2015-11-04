<?php
/**
 * @package   AkeebaTicketSystem
 * @copyright Copyright (c)2011-2015 Nicholas K. Dionysopoulos
 * @license   GNU General Public License version 3, or later
 */

namespace Akeeba\TicketSystem\Admin\Toolbar;

use FOF30\Inflector\Inflector;
use JToolbar;
use JToolBarHelper;
use JText;

defined('_JEXEC') or die;

class Toolbar extends \FOF30\Toolbar\Toolbar
{
	/**
	 * Renders the submenu (toolbar links) for all defined views of this component
	 */
	public function renderSubmenu()
	{
		$views = array(
			'ControlPanel',
			'COM_ATS_MAINMENU_SETUP' => array(
				'CustomFields',
				'EmailTemplates',
				'UserTags',
				'OfflineSchedules'
			),
			'COM_ATS_FAST_REPLIES' => array(
				'AutoReplies',
				'Buckets',
				'CannedReplies'
			),
			'CreditTransactions',
            'Tickets',
            'TimeCards'
		);

		foreach ($views as $label => $view)
		{
			if (!is_array($view))
			{
				$this->addSubmenuLink($view);
				continue;
			}

			$label = \JText::_($label);
			$this->appendLink($label, '', false);

			foreach ($view as $v)
			{
				$this->addSubmenuLink($v, $label);
			}
		}

		$this->appendLink(
			JText::_('COM_ATS_SUBMENU_CATEGORIES'),
			'index.php?option=com_categories&extension=com_ats'
		);
	}

	public function onControlPanels()
	{
		$this->renderSubmenu();

		$option = $this->container->componentName;

		JToolBarHelper::title(JText::_(strtoupper($option)), str_replace('com_', '', $option));

		JToolBarHelper::preferences($option);
	}

	public function onEmailtemplatesAdd()
	{
		// Quick hack to mark this record as new
		$this->_isNew = true;

		parent::onAdd();
	}

	public function onEmailtemplatesEdit()
	{
		if(!isset($this->_isNew))
		{
			$html  = '<a href="#" onclick="javascript:Joomla.submitbutton(\'testtemplate\')" class="btn btn-small">';
			$html .=    '<span class="icon-print"></span>';
			$html .= JText::_('COM_ATS_EMAILTEMPLATES_TESTTEMPLATE');
			$html .= '</a>';

			$bar = JToolBar::getInstance();
			$bar->appendButton('Custom', $html, 'test-template');
		}
		parent::onEdit();
	}

    public function onTicketsBrowse()
    {
        parent::onBrowse();

        $bar = JToolbar::getInstance();

        // Add to bucket button
        $html  = '<a id="bucketadd" class="btn btn-small" href="index.php?option=com_ats&view=Buckets&tmpl=component&task=choosebucket&layout=choose">';
        $html .= '<i class="icon-cogs"></i>'.JText::_('COM_ATS_ADD_TO_BUCKET');
        $html .= '</a>';

        $bar->appendButton('Custom', $html, 'bucketadd');

        // Export as CSV button
        $csvLink = 'index.php?option=com_ats&view=Tickets&task=browse&format=csv';

        $bar->appendButton('Link', 'download', JText::_('COM_ATS_COMMON_EXPORTCSV'), $csvLink);
    }

    public function onTimecards()
    {
        JToolBarHelper::title(JText::_('COM_ATS') . ': ' . JText::_('COM_ATS_TITLE_TIMECARDS'), 'ats');

        $this->renderSubmenu();
    }

	/**
	 * Adds a link to the submenu (toolbar links)
	 *
	 * @param string $view   The view we're linking to
	 * @param array  $parent The parent view
	 */
	private function addSubmenuLink($view, $parent = null)
	{
		static $activeView = null;

		if (empty($activeView))
		{
			$activeView = $this->container->input->getCmd('view', 'cpanel');
		}

		if ($activeView == 'cpanels')
		{
			$activeView = 'cpanel';
		}

		$key = $this->container->componentName . '_TITLE_' . $view;

		// Exceptions to avoid introduction of a new language string
		if ($view == 'ControlPanel')
		{
			$key = $this->container->componentName . '_TITLE_CPANEL';
		}

		if (strtoupper(\JText::_($key)) == strtoupper($key))
		{
			$altView = Inflector::isPlural($view) ? Inflector::singularize($view) : Inflector::pluralize($view);
			$key2    = strtoupper($this->container->componentName) . '_TITLE_' . strtoupper($altView);

			if (strtoupper(\JText::_($key2)) == $key2)
			{
				$name = ucfirst($view);
			}
			else
			{
				$name = \JText::_($key2);
			}
		}
		else
		{
			$name = \JText::_($key);
		}

		$link = 'index.php?option=' . $this->container->componentName . '&view=' . $view;

		$active = $view == $activeView;

		$this->appendLink($name, $link, $active, null, $parent);
	}

	/**
	 * Add a custom toolbar button
	 *
	 * @param string $id      The button ID
	 * @param array  $options Button options
	 */
	protected function addCustomBtn($id, $options = array())
	{
		$options = (array) $options;
		$a_class = 'btn btn-small';
		$href    = '';
		$task    = '';
		$text    = '';
		$rel     = '';
		$target  = '';
		$other   = '';

		if (isset($options['a.class']))
		{
			$a_class .= $options['a.class'];
		}
		if (isset($options['a.href']))
		{
			$href = $options['a.href'];
		}
		if (isset($options['a.task']))
		{
			$task = $options['a.task'];
		}
		if (isset($options['a.target']))
		{
			$target = $options['a.target'];
		}
		if (isset($options['a.other']))
		{
			$other = $options['a.other'];
		}
		if (isset($options['text']))
		{
			$text = $options['text'];
		}
		if (isset($options['class']))
		{
			$class = $options['class'];
		}
		else
		{
			$class = 'default';
		}

		if (isset($options['modal']))
		{
			\JHtml::_('behavior.modal');
			$a_class .= ' modal';
			$rel = "'handler':'iframe'";
			if (is_array($options['modal']))
			{
				if (isset($options['modal']['size']['x']) && isset($options['modal']['size']['y']))
				{
					$rel .= ", 'size' : {'x' : " . $options['modal']['size']['x'] . ", 'y' : " . $options['modal']['size']['y'] . "}";
				}
			}
		}

		$html = '<a id="' . $id . '" class="' . $a_class . '" alt="' . $text . '"';

		if ($rel)
		{
			$html .= ' rel="{' . $rel . '}"';
		}
		if ($href)
		{
			$html .= ' href="' . $href . '"';
		}
		if ($task)
		{
			$html .= " onclick=\"javascript:submitbutton('" . $task . "')\"";
		}
		if ($target)
		{
			$html .= ' target="' . $target . '"';
		}
		if ($other)
		{
			$html .= ' ' . $other;
		}
		$html .= ' >';

		$html .= '<span class="icon icon-' . $class . '" title="' . $text . '" > </span>';

		$html .= $text;

		$html .= '</a>';

		$bar = \JToolBar::getInstance();
		$bar->appendButton('Custom', $html, $id);
	}
}