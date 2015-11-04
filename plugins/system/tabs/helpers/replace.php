<?php
/**
 * Plugin Helper File: Replace
 *
 * @package         Tabs
 * @version         5.1.3
 *
 * @author          Peter van Westen <peter@nonumber.nl>
 * @link            http://www.nonumber.nl
 * @copyright       Copyright © 2015 NoNumber All Rights Reserved
 * @license         http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

defined('_JEXEC') or die;

class PlgSystemTabsHelperReplace
{
	var $helpers = array();
	var $params = null;

	public function __construct()
	{
		require_once __DIR__ . '/helpers.php';
		$this->helpers = PlgSystemTabsHelpers::getInstance();
		$this->params  = $this->helpers->getParams();

		// Tag character start and end
		list($tag_start, $tag_end) = $this->getTagCharacters(true);

		// Break/paragraph start and end tags
		$this->params->breaks_start = NNTags::getRegexSurroundingTagPre(array('div', 'p', 'span'));
		$this->params->breaks_end   = NNTags::getRegexSurroundingTagPost(array('div', 'p', 'span'));
		$breaks_start               = $this->params->breaks_start;
		$breaks_end                 = $this->params->breaks_end;
		$inside_tag                 = NNTags::getRegexInsideTag();

		$this->params->tag_delimiter = ($this->params->tag_delimiter == 'space') ? NNTags::getRegexSpaces() : '=';
		$delimiter                   = $this->params->tag_delimiter;
		$sub_id                      = '(?:-[a-zA-Z0-9-_]+)?';

		$this->params->tag_open  = trim($this->params->tag_open);
		$this->params->tag_close = trim($this->params->tag_close);

		$this->params->regex = '#'
			. '(?<pre>' . $breaks_start . ')'
			. $tag_start . '(?<tag>'
			. $this->params->tag_open . 's?' . '(?<setid>' . $sub_id . ')' . $delimiter . '(?<data>' . $inside_tag . ')'
			. '|/' . $this->params->tag_close . $sub_id
			. ')' . $tag_end
			. '(?<post>' . $breaks_end . ')'
			. '#s';

		$this->params->regex_end = '#'
			. '(?<pre>' . $breaks_start . ')'
			. $tag_start . '/' . $this->params->tag_close . $sub_id . $tag_end
			. '(?<post>' . $breaks_end . ')'
			. '#s';

		$this->params->regex_link = '#'
			. $tag_start . $this->params->tag_link . $sub_id . $delimiter . '(?<id>' . $inside_tag . ')' . $tag_end
			. '(?<text>.*?)'
			. $tag_start . '/' . $this->params->tag_link . $tag_end
			. '#s';

		$this->ids      = array();
		$this->matches  = array();
		$this->allitems = array();
		$this->setcount = 0;

		$this->setMainClass();
	}

	private function setMainClass()
	{
		$this->mainclass = array();
		if ($this->params->load_stylesheet == 2)
		{
			$this->mainclass[]                 = 'oldschool';
			$this->params->use_responsive_view = 0;

			return;
		}

		if ($this->params->color_inactive_handles)
		{
			$this->mainclass[] = 'color_inactive_handles';
		}
		if ($this->params->outline_handles)
		{
			$this->mainclass[] = 'outline_handles';
		}
		if ($this->params->outline_content)
		{
			$this->mainclass[] = 'outline_content';
		}
		if (!$this->params->alignment)
		{
			$this->params->alignment = JFactory::getLanguage()->isRTL() ? 'right' : 'left';
		}
		$positioning = 'top';
		if ($positioning == 'top' || $positioning == 'bottom')
		{
			$this->mainclass[] = 'align_' . $this->params->alignment;
		}
	}

	public function replaceTags(&$string, $area = 'article', $context = '')
	{
		if (!is_string($string) || $string == '')
		{
			return;
		}

		// Check if tags are in the text snippet used for the search component
		if (strpos($context, 'com_search.') === 0)
		{
			$limit = explode('.', $context, 2);
			$limit = (int) array_pop($limit);

			$string_check = substr($string, 0, $limit);

			if (
				strpos($string_check, '{' . $this->params->tag_open) === false
				&& strpos($string_check, '{' . $this->params->tag_link) === false
			)
			{
				return;
			}
		}

		// allow in component?
		if (
			($area == 'component' || ($area == 'article' && JFactory::getApplication()->input->get('option') == 'com_content'))
			&& in_array(JFactory::getApplication()->input->get('option'), $this->params->disabled_components)
		)
		{

			$this->helpers->get('protect')->protect($string);

			$this->handlePrintPage($string);

			NNProtect::unprotect($string);

			return;
		}

		if (
			strpos($string, '{' . $this->params->tag_open) === false
			&& strpos($string, '{' . $this->params->tag_link) === false
		)
		{
			// Links with #tab-name or &tab=tab-name
			$this->replaceLinks($string);

			return;
		}

		$this->helpers->get('protect')->protect($string);

		list($pre_string, $string, $post_string) = NNText::getContentContainingSearches(
			$string,
			array(
				'{' . $this->params->tag_open,
				'{' . $this->params->tag_link,
			),
			array(
				'{/' . $this->params->tag_close,
				'{/' . $this->params->tag_link . '}',
			)
		);

		if (JFactory::getApplication()->input->getInt('print', 0))
		{
			// Replace syntax with general html on print pages
			$this->handlePrintPage($string);

			$string = $pre_string . $string . $post_string;

			NNProtect::unprotect($string);

			return;
		}

		$sets = $this->getSets($string);
		$this->initSets($sets);

		// Tag syntax: {tab ...}
		$this->replaceSyntax($string, $sets);

		// Closing tag: {/tab}
		$this->replaceClosingTag($string);

		// Links with #tab-name or &tab=tab-name
		$this->replaceLinks($string);

		// Link tag {tablink ...}
		$this->replaceLinkTag($string);

		$string = $pre_string . $string . $post_string;

		NNProtect::unprotect($string);
	}

	private function handlePrintPage(&$string)
	{
		if (substr($this->params->regex, -1) != 'u' && @preg_match($this->params->regex . 'u', $string))
		{
			$this->params->regex .= 'u';
		}

		if (preg_match_all($this->params->regex, $string, $matches, PREG_SET_ORDER) > 0)
		{
			foreach ($matches as $match)
			{
				$title = NNText::cleanTitle($match['data']);
				if (strpos($title, '|') !== false)
				{
					list($title, $extra) = explode('|', $title, 2);
				}
				$title   = trim($title);
				$id      = NNText::cleanTitle($title, true);
				$title   = preg_replace('#<\?h[0-9](\s[^>]* )?>#', '', $title);
				$replace = '<' . $this->params->title_tag . ' class="nn_tabs-title"><a id="anchor-' . $id . '" class="anchor"></a>' . $title . '</' . $this->params->title_tag . '>';
				$string  = str_replace($match['0'], $replace, $string);
			}
		}

		if (preg_match_all($this->params->regex_end, $string, $matches, PREG_SET_ORDER) > 0)
		{
			foreach ($matches as $match)
			{
				$string = str_replace($match['0'], '', $string);
			}
		}

		if (substr($this->params->regex_link, -1) != 'u' && @preg_match($this->params->regex_link . 'u', $string))
		{
			$this->params->regex_link .= 'u';
		}

		if (preg_match_all($this->params->regex_link, $string, $matches, PREG_SET_ORDER) > 0)
		{
			foreach ($matches as $match)
			{
				$href   = NNText::getURI($match['id']);
				$link   = '<a href="' . $href . '">' . $match['text'] . '</a>';
				$string = str_replace($match['0'], $link, $string);
			}
		}
	}

	private function getSets(&$string)
	{
		if (substr($this->params->regex, -1) != 'u' && @preg_match($this->params->regex . 'u', $string))
		{
			$this->params->regex .= 'u';
		}

		if (!preg_match_all($this->params->regex, $string, $matches, PREG_SET_ORDER))
		{
			return array();
		}

		$sets   = array();
		$setids = array();


		foreach ($matches as $match)
		{
			if (substr($match['tag'], 0, 1) == '/')
			{

				array_pop($setids);
				continue;
			}

			end($setids);

			$item        = new stdClass;
			$item->orig  = $match['0'];
			$item->setid = trim(str_replace('-', '_', $match['setid']));

			if (empty($setids) || current($setids) != $item->setid)
			{
				$this->setcount++;
				$setids[$this->setcount . '.'] = $item->setid;
			}

			$item->set   = str_replace('__', '_', array_search($item->setid, array_reverse($setids)) . $item->setid);
			$item->title = NNText::cleanTitle($match['data'], false, false);

			list($item->pre, $item->post) = NNTags::cleanSurroundingTags(array($match['pre'], $match['post']), array('div', 'p', 'span'));

			if (!isset($sets[$item->set]))
			{
				$sets[$item->set] = array();
			}


			$sets[$item->set][] = $item;
		}


		return $sets;
	}

	private function getParent(&$sets, $item, $prev_item, $setid, $prev_setid)
	{
		if (!$prev_item)
		{
			return '';
		}

		if (count($sets[$item->set]))
		{
			$last_item = end($sets[$item->set]);
			reset($sets[$item->set]);

			return $last_item->parent;
		}

		if ($prev_setid != $setid)
		{
			$sets[$prev_item->set][$prev_item->id]->children[] = $item->set;

			return $prev_item->set . $prev_item->id;
		}

		return '';
	}


	private function initSets(&$sets)
	{
		$urlitem   = JFactory::getApplication()->input->get('tab');
		$itemcount = 0;

		foreach ($sets as $set_id => $items)
		{
			$active = 0;
			foreach ($items as $i => $item)
			{
				// Fix some different syntaxes
				$tag = str_replace(
					array(
						'|alias:',
						'title-close=',
						'title-closed=',
						'title-open=',
						'title-opened=',
					),
					array(
						'|alias=',
						'title-inactive=',
						'title-inactive=',
						'title-active=',
						'title-active=',
					),
					$item->title
				);
				$tag = preg_replace('#^title-(in)?active=#', '', $tag);

				// Get the values from the tag
				$tag = NNTags::getTagValues($tag);

				$item->title      = $tag->title;
				$item->title_full = $item->title;

				if (isset($tag->{'title-active'}) || isset($tag->{'title-inactive'}))
				{
					$title_inactive = isset($tag->{'title-inactive'}) ? $tag->{'title-inactive'} : $item->title;
					$title_active   = isset($tag->{'title-active'}) ? $tag->{'title-active'} : $item->title;

					// Set main title to the title-active, otherwise to title-inactive
					$item->title = $title_active ?: ($title_inactive ?: $item->title);

					// place the title-active and title-inactive in css controlled spans
					$item->title_full = '<span class="nn_tabs-title-inactive">' . $title_inactive . '</span>'
						. '<span class="nn_tabs-title-active">' . $title_active . '</span>';
				}

				$item->haslink = preg_match('#<a [^>]*>.*?</a>#usi', $item->title);

				$item->title = NNText::cleanTitle($item->title, true);
				$item->title = $item->title ?: NNText::getAttribute('title', $item->title_full);
				$item->title = $item->title ?: NNText::getAttribute('alt', $item->title_full);

				$item->alias = NNText::createAlias(isset($tag->alias) ? $tag->alias : $item->title);
				$item->alias = $item->alias ?: 'tab';

				$item->id     = $this->createId($item->alias);
				$item->set    = (int) $set_id;
				$item->count  = $i + 1;
				$item->active = 0;

				foreach ($tag->params as $j => $val)
				{
					if (!$val)
					{
						continue;
					}

					if (in_array($val, array('default', 'opened', 'open', 'active')))
					{
						$item->active = 1;
						$active       = $i;
						unset($tag->params[$j]);
						continue;
					}

					if (strpos($val, ' ') !== false)
					{
						$vals = explode(' ', $val);
						foreach ($vals as $v)
						{
							$tag->params[] = $v;
						}
						unset($tag->params[$j]);
					}
				}
				$item->classes = $this->getClassesFromTag($tag);
				$item->class   = trim(implode(' ', $item->classes));

				$item->matches   = NNText::createUrlMatches(array($item->id, $item->title));
				$item->matches[] = ++$itemcount . '';
				$item->matches[] = $item->set . '.' . ($i + 1);
				$item->matches[] = $item->set . '-' . ($i + 1);

				$item->matches = array_unique($item->matches);
				$item->matches = array_diff($item->matches, $this->matches);
				$this->matches = array_merge($this->matches, $item->matches);

				if ($urlitem && in_array($urlitem, $item->matches))
				{
					$item->active = 1;
					$active       = $i;
				}

				if (!$item->active && $active == $i && $item->haslink)
				{
					$active++;
				}

				$sets[$set_id][$i] = $item;
				$this->allitems[]  = $item;
			}

			$active = (int) $active;

			if (!isset($sets[$set_id][$active]))
			{
				$active = 0;
			}

			$sets[$set_id][$active]->active = 1;
		}
	}

	private function replaceSyntax(&$string, $sets)
	{
		if (!preg_match($this->params->regex_end, $string))
		{
			return;
		}

		foreach ($sets as $items)
		{
			$this->replaceSyntaxItemList($string, $items);
		}
	}

	private function replaceSyntaxItemList(&$string, $items)
	{
		$first = key($items);
		end($items);

		foreach ($items as $i => &$item)
		{
			$this->replaceSyntaxItem($string, $item, $items, ($i == $first));
		}
	}

	private function replaceSyntaxItem(&$string, $item, $items, $first = 0)
	{
		$s = '#' . preg_quote($item->orig, '#') . '#';
		if (@preg_match($s . 'u', $string))
		{
			$s .= 'u';
		}

		if (!preg_match($s, $string, $match))
		{
			return;
		}

		$html   = array();
		$html[] = $item->post;
		$html[] = $item->pre;

		$html[] = $this->getPreHtml($item, $items, $first);

		$class   = array();
		$class[] = 'tab-pane nn_tabs-pane';
		if ($item->active)
		{
			$class[] = 'active';
		}
		$class[] = trim($item->class);

		$html[] = '<div class="' . trim(implode(' ', $class)) . '" id="' . $item->id . '"'
			. ' role="tabpanel" aria-labelledby="tab-' . $item->id . '" aria-hidden="' . ($item->active ? 'false' : 'true') . '">';

		if (!$item->haslink)
		{
			$class = 'anchor';
			$html[] = '<' . $this->params->title_tag . ' class="nn_tabs-title">'
				. '<a id="anchor-' . $item->id . '" class="' . $class . '"></a>'
				. $item->title . '</' . $this->params->title_tag . '>';
		}

		$html   = implode("\n", $html);
		$string = NNText::strReplaceOnce($match['0'], $html, $string);
	}

	private function getPreHtml($item, $items, $first = 0)
	{
		if (!$first)
		{
			return '</div>';
		}

		$classes = $this->mainclass;

		// Set overruling main classes
		if (in_array('color_inactive_handles=0', $item->classes))
		{
			$this->addClass($item, $classes, '', array('color_inactive_handles', 'color_inactive_handles=0'));
		}
		if (in_array('nooutline', $item->classes))
		{
			$this->addClass($item, $classes, '', array('nooutline', 'outline_handles', 'outline_handles=0', 'outline_content', 'outline_content=0'));
		}
		if (in_array('outline_handles=0', $item->classes))
		{
			$this->addClass($item, $classes, '', array('outline_handles', 'outline_handles=0'));
		}
		if (in_array('outline_content=0', $item->classes))
		{
			$this->addClass($item, $classes, '', array('outline_content', 'outline_content=0'));
		}

		if (in_array('color_inactive_handles', $item->classes))
		{
			$this->addClass($item, $classes, 'color_inactive_handles');
		}
		if (in_array('outline_handles', $item->classes))
		{
			$this->addClass($item, $classes, 'outline_handles');
		}
		if (in_array('outline_content', $item->classes))
		{
			$this->addClass($item, $classes, 'outline_content');
		}


		$positionings = array('top', 'bottom', 'left', 'right');
		$alignments   = array('align_left', 'align_right', 'align_center', 'align_justify');
		$this->addClass($item, $classes, 'top', $positionings);

		foreach ($alignments as $align)
		{
			if (!in_array($align, $item->classes))
			{
				continue;
			}

			$this->addClass($item, $classes, $align, $alignments);
			break;
		}

		$item->class = trim(implode(' ', $item->classes));


		$html[] = '<div class="' . trim('nn_tabs ' . implode(' ', $classes)) . '">';
		$html[] = $this->getNav($items);
		$html[] = '<div class="tab-content">';

		return implode("\n", $html);
	}

	private function addClass(&$item, &$classes, $class = '', $removes = array())
	{
		if (empty($removes))
		{
			$removes = array($class);
		}

		$item->classes = array_diff($item->classes, $removes);
		$classes       = array_diff($classes, $removes);

		if ($class)
		{
			$classes[] = $class;
		}
	}

	private function replaceClosingTag(&$string)
	{
		if (!preg_match_all($this->params->regex_end, $string, $matches, PREG_SET_ORDER))
		{
			return;
		}

		foreach ($matches as $match)
		{
			$html = '</div></div></div>';


			list($pre, $post) = NNTags::cleanSurroundingTags(array($match['pre'], $match['post']));

			$html = $pre . $html . $post;

			$string = NNText::strReplaceOnce($match['0'], $html, $string);
		}
	}

	private function replaceLinks(&$string)
	{
		// Links with #tab-name
		$this->replaceAnchorLinks($string);
		// Links with &tab=tab-name
		$this->replaceUrlLinks($string);
	}

	private function replaceAnchorLinks(&$string)
	{
		if (!preg_match_all(
			'#(?<link><a\s[^>]*href="(?<url>([^"]*)?)\#(?<id>[^"]*)"[^>]*>)(?<text>.*?)</a>#si',
			$string,
			$matches,
			PREG_SET_ORDER
		)
		)
		{
			return;
		}

		$this->replaceLinksMatches($string, $matches);
	}

	private function replaceUrlLinks(&$string)
	{
		if (!preg_match_all(
			'#(?<link><a\s[^>]*href="(?<url>[^"]*)(?:\?|&(?:amp;)?)tab=(?<id>[^"\#&]*)(?:\#[^"]*)?"[^>]*>)(?<text>.*?)</a>#si',
			$string,
			$matches,
			PREG_SET_ORDER
		)
		)
		{
			return;
		}

		$this->replaceLinksMatches($string, $matches);
	}

	private function replaceLinksMatches(&$string, $matches)
	{
		$uri            = JUri::getInstance();
		$current_urls   = array();
		$current_urls[] = $uri->toString(array('path'));
		$current_urls[] = $uri->toString(array('scheme', 'host', 'path'));
		$current_urls[] = $uri->toString(array('scheme', 'host', 'port', 'path'));

		foreach ($matches as $match)
		{
			$link = $match['link'];

			if (
				strpos($link, 'data-toggle=') !== false
				|| strpos($link, 'onclick=') !== false
				|| strpos($link, 'nn_tabs-toggle-sm') !== false
				|| strpos($link, 'nn_tabs-link') !== false
				|| strpos($link, 'nn_sliders-link') !== false
			)
			{
				continue;
			}

			$url = $match['url'];
			if (strpos($url, 'index.php/') === 0)
			{
				$url = '/' . $url;
			}

			if (strpos($url, 'index.php') === 0)
			{
				$url = JRoute::_($url);
			}

			if ($url != '' && !in_array($url, $current_urls))
			{
				continue;
			}

			$id = $match['id'];

			if (!$this->stringHasItem($string, $id))
			{
				// This is a link to a normal anchor or other element on the page
				// Remove the prepending obsolete url and leave the hash
				// $string = str_replace('href="' . $match['url'] . '#' . $id . '"', 'href="#' . $id . '"', $string);

				continue;
			}

			$attribs = $this->getLinkAttributes($id);

			// Combine attributes with original
			$attribs = NNText::combineAttributes($link, $attribs);

			$html = '<a ' . $attribs . '><span class="nn_tabs-link-inner">' . $match['text'] . '</span></a>';

			$string = str_replace($match['0'], $html, $string);
		}
	}

	private function replaceLinkTag(&$string)
	{
		if (substr($this->params->regex_link, -1) != 'u' && @preg_match($this->params->regex_link . 'u', $string))
		{
			$this->params->regex_link .= 'u';
		}

		if (!preg_match_all($this->params->regex_link, $string, $matches, PREG_SET_ORDER))
		{
			return;
		}

		foreach ($matches as $match)
		{
			$this->replaceLinkTagMatch($string, $match);
		}
	}

	private function replaceLinkTagMatch(&$string, $match)
	{
		$id = NNText::createAlias($match['id']);

		if (!$this->stringHasItem($string, $id))
		{
			$id = $this->findItemByMatch($match['id']);
		}

		if (!$this->stringHasItem($string, $id))
		{
			$html = '<a href="' . NNText::getURI($id) . '">' . $match['text'] . '</a>';

			$string = NNText::strReplaceOnce($match['0'], $html, $string);

			return;
		}

		$html = '<a ' . $this->getLinkAttributes($id) . '>'
			. '<span class="nn_tabs-link-inner">' . $match['text'] . '</span>'
			. '</a>';

		$string = NNText::strReplaceOnce($match['0'], $html, $string);
	}

	private function findItemByMatch($id)
	{
		foreach ($this->allitems as $item)
		{
			if (!in_array($id, $item->matches))
			{
				continue;
			}

			return $item->id;
		}

		return $id;
	}

	private function getLinkAttributes($id)
	{
		return 'href="' . NNText::getURI($id) . '"'
		. ' class="nn_tabs-link nn_tabs-link-' . $id . '"'
		. ' data-id="' . $id . '"';
	}

	private function stringHasItem(&$string, $id)
	{
		return (strpos($string, 'data-toggle="tab" data-id="' . $id . '"') !== false);
	}

	private function getClassesFromTag($tag)
	{
		$classes   = $tag->params;
		$overrules = array('color_inactive_handles', 'outline_handles', 'outline_content', 'nooutline');
		foreach ($overrules as $overrule)
		{
			if (!isset($tag->{$overrule}))
			{
				continue;
			}

			if ($tag->{$overrule} === '0')
			{
				$classes[] = $overrule . '=0';
				continue;
			}

			$classes[] = $overrule;
		}

		return $classes;
	}

	private function getNav(&$items)
	{
		$html = array();

		$ul_extra = '';

		// Nav for non-mobile view
		$html[] = '<a id="nn_tabs-scrollto_' . $items['0']->set . '" class="anchor nn_tabs-scroll"></a>';
		$html[] = '<ul class="nav nav-tabs" id="set-nn_tabs-' . $items['0']->set . '" role="tablist"' . $ul_extra . '>';
		foreach ($items as $item)
		{
			$html[] = '<li class="' . trim('nn_tabs-tab ' . ($item->active ? 'active' : '') . ' ' . trim($item->class)) . '"'
				. ' role="presentation">';

			if ($item->haslink)
			{
				$html[] = $item->title_full;
				$html[] = '</li>';
				continue;
			}

			$class = 'nn_tabs-toggle';

			$html[] = '<a href="#' . $item->id . '" class="' . $class . '"'
				. ' id="tab-' . $item->id . '"'
				. ' data-toggle="tab" data-id="' . $item->id . '"'
				. ' role="tab" aria-controls="' . $item->id . '" aria-selected="' . ($item->active ? 'true' : 'false') . '"'
				. '>'
				. '<span class="nn_tabs-toggle-inner">'
				. $item->title_full
				. '</span></a>';
			$html[] = '</li>';
		}
		$html[] = '</ul>';

		return implode("\n", $html);
	}


	private function createId($alias)
	{
		$id = $alias;

		$i = 1;
		while (in_array($id, $this->ids))
		{
			$id = $alias . '-' . ++$i;
		}

		$this->ids[] = $id;

		return $id;
	}

	public function getTagCharacters($quote = false)
	{
		if (!isset($this->params->tag_character_start))
		{
			list($this->params->tag_character_start, $this->params->tag_character_end) = explode('.', $this->params->tag_characters);
		}

		$start = $this->params->tag_character_start;
		$end   = $this->params->tag_character_end;

		if ($quote)
		{
			$start = preg_quote($start, '#');
			$end   = preg_quote($end, '#');
		}

		return array($start, $end);
	}
}
