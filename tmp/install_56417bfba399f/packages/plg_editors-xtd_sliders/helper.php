<?php
/**
 * Plugin Helper File
 *
 * @package         Sliders
 * @version         5.1.4
 *
 * @author          Peter van Westen <peter@nonumber.nl>
 * @link            http://www.nonumber.nl
 * @copyright       Copyright © 2015 NoNumber All Rights Reserved
 * @license         http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

defined('_JEXEC') or die;

/**
 ** Plugin that places the button
 */
class PlgButtonSlidersHelper
{
	public function __construct(&$params)
	{
		$this->params = $params;
	}

	/**
	 * Display the button
	 *
	 * @return array A two element array of ( imageName, textToInsert )
	 */
	function render($name)
	{
		$button = new JObject;

		if (JFactory::getApplication()->isSite() && !$this->params->enable_frontend)
		{
			return $button;
		}

		$user = JFactory::getUser();
		if ($user->get('guest')
			|| (
				!$user->authorise('core.edit', 'com_content')
				&& !$user->authorise('core.create', 'com_content')
			)
		)
		{
			return $button;
		}

		if ($this->params->button_use_simple_button)
		{
			return $this->renderSimpleButton($name);
		}

		return $this->renderButton($name);
	}

	private function renderButton($name)
	{
		JHtml::stylesheet('nnframework/style.min.css', false, true);

		$link = 'index.php?nn_qp=1'
			. '&folder=plugins.editors-xtd.sliders'
			. '&file=popup.php'
			. '&name=' . $name;

		$button = new JObject;

		$button->modal   = true;
		$button->class   = 'btn';
		$button->link    = $link;
		$button->text    = $this->getButtonText();
		$button->name    = 'nonumber icon-sliders';
		$button->options = "{handler: 'iframe', size: {x:window.getSize().x-100, y: window.getSize().y-100}}";

		return $button;
	}

	private function renderSimpleButton($name)
	{
		require_once JPATH_PLUGINS . '/system/nnframework/helpers/functions.php';
		NNFrameworkFunctions::loadLanguage('plg_editors-xtd_sliders');

		NNFrameworkFunctions::addScriptVersion(JUri::root(true) . '/media/nnframework/js/script.min.js');
		JHtml::stylesheet('nnframework/style.min.css', false, true);

		$this->params->tag_open      = preg_replace('#[^a-z0-9-_]#s', '', $this->params->tag_open);
		$this->params->tag_close     = preg_replace('#[^a-z0-9-_]#s', '', $this->params->tag_close);
		$this->params->tag_delimiter = ($this->params->tag_delimiter == '=') ? '=' : ' ';

		$text = $this->getExampleText();
		$text = str_replace('\\\\n', '\\n', addslashes($text));
		$text = str_replace('{', '{\'+\'', $text);

		$js = "
			function insertSliders(editor) {
				selection = nnScripts.getEditorSelection(editor);
				selection = selection ? selection : '" . JText::_('SLD_TEXT', true) . "';

				text = '" . $text . "';
				text = text.replace('[:SELECTION:]', selection);

				jInsertEditorText(text, editor);
			}
		";
		JFactory::getDocument()->addScriptDeclaration($js);

		$button = new JObject;

		$button->modal   = false;
		$button->class   = 'btn';
		$button->link    = '#';
		$button->onclick = 'insertSliders(\'' . $name . '\');return false;';
		$button->text    = $this->getButtonText();
		$button->name    = 'nonumber icon-sliders';

		return $button;
	}

	private function getButtonText()
	{
		$text_ini = strtoupper(str_replace(' ', '_', $this->params->button_text));
		$text     = JText::_($text_ini);
		if ($text == $text_ini)
		{
			$text = JText::_($this->params->button_text);
		}

		return trim($text);
	}

	private function getExampleText()
	{
		switch (true)
		{
			case ($this->params->button_use_custom_code && $this->params->button_custom_code):
				return $this->getCustomText();
			default:
				return $this->getDefaultText();
		}
	}

	private function getDefaultText()
	{
		return
			'{' . $this->params->tag_open . $this->params->tag_delimiter . JText::_('SLD_TITLE') . ' 1}\n' .
			'<p>[:SELECTION:]</p>\n' .
			'<p>{' . $this->params->tag_open . $this->params->tag_delimiter . JText::_('SLD_TITLE') . ' 2}</p>\n' .
			'<p>' . JText::_('SLD_TEXT') . '</p>\n' .
			'<p>{/' . $this->params->tag_close . '}</p>';
	}

	private function getCustomText()
	{
		$text = trim($this->params->button_custom_code);
		$text = str_replace(array("\r", "\n"), array('', '</p>\n<p>'), trim($text)) . '</p>';
		$text = preg_replace('#^(.*?)</p>#', '\1', $text);
		$text = str_replace(array('{slider ', '{/sliders}'), array('{' . $this->params->tag_open . $this->params->tag_delimiter, '{/' . $this->params->tag_close . '}'), trim($text));

		return $text;
	}
}
