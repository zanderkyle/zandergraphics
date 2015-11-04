<?php
/**
 * @package   AkeebaTicketSystem
 * @copyright Copyright (c)2011-2015 Nicholas K. Dionysopoulos
 * @license   GNU General Public License version 3, or later
 */

namespace Akeeba\TicketSystem\Admin\Helper;

use FOF30\Container\Container;
use JFactory;
use JRoute;
use JText;
use JUri;

defined('_JEXEC') or die;

class Editor
{
    public static function showEditor($name, $id = null, $contents, $width, $height, $cols, $rows, $params = array())
    {
        if(self::isEditorBBcode())
        {
            self::showEditorBBcode($name, $id, $contents, $width, $height, $cols, $rows, $params);
        }
        else
        {
            self::showEditorJoomla($name, $id, $contents, $width, $height, $cols, $rows, $params);
        }
    }

    public static function isEditorBBcode()
    {
        static $ret = null;

        if(is_null($ret))
        {
            $editor = ComponentParams::getParam('editor', 'bbcode');
            $ret    = ($editor == 'bbcode');

            // When we detect a mobile device User Agent string we force the use of the BBcode editor
            $default = '; Android,iPad;,iPhone;,; Windows Phone OS,Windows Phone,; Windows CE,BlackBerry;,; Blazer,; BOLT/,/SymbianOS,(Symbian),Fennec/,GoBrowser/,Iris/,Maemo Browser,MIB/,Minimo/,NetFront/,Opera Mobi/,Opera Mini/,SEMC-Browser/,Skyfire/,TeaShark/,Teleca Q,uZardWeb/';
            $rawUAs = ComponentParams::getParam('bbcodeuas', $default);

            if (!empty($rawUAs))
            {
                $forcedUAs = explode(',', $rawUAs);
                $ua = $_SERVER['HTTP_USER_AGENT'];

                foreach ($forcedUAs as $fua)
                {
                    if (strpos($ua, $fua) !== false)
                    {
                        $ret = true;
                        break;
                    }
                }
            }
        }

        return $ret;
    }

    public static function showEditorBBcode($name, $id = null, $contents, $width, $height, $cols, $rows, $params = array(), $returnHTML = false)
    {
        static $injectedFiles = false;

        $container = Container::getInstance('com_ats');

        if (!$injectedFiles)
        {
            // Load the required Javascript
            $container->template->addJS('media://com_ats/js/jquery.markitup.js', false, false, $container->mediaVersion);
            $container->template->addJS('media://com_ats/js/bbcode-set.js', false, false, $container->mediaVersion);
            $container->template->addJS('media://com_ats/js/preview.js', false, false, $container->mediaVersion);

            // Load the required CSS
            $container->template->addCSS('media://com_ats/css/markitup/skin/style.css', $container->mediaVersion);
            $container->template->addCSS('media://com_ats/css/markitup/bbcode/style.css', $container->mediaVersion);

            $injectedFiles = true;
        }

        if(is_null($id) || empty($id))
        {
            $id = $name;
        }

        $extras 	   = '';
        $cannedReplies = false;
        $bucket 	   = false;

        if(is_array($params) && !empty($params))
        {
            foreach($params as $k => $v)
            {
                if($k == 'cannedreplies')
                {
                    if($v) $cannedReplies = true;
                }
                elseif($k == 'buckets' && $v)
                {
                    $bucket = true;
                }
                else
                {
                    $extras .= $k.'="'.$v.'"';
                }
            }
        }

        $throbber = $container->template->parsePath('media://com_ats/images/throbber.gif');
        $html  = '<textarea id="'.$id.'" '.$extras.'rows="'.$rows.'" cols="'.$cols.'" name="'.$name.'">'.$contents.'</textarea>';

        // Setup a preview area
        $html .= '<div id="atsPreviewArea">';
        $html .=    '<div id="atsPreviewLoading" style="display:none;text-align:center"><img src="'.$throbber.'" /></div>';
        $html .=    '<div id="atsPreviewHolder" class="well well-small" style="display: none">';
        $html .=        '<h3>'.JText::_('COM_ATS_POSTS_PREVIEW').'</h3>';
        $html .=        '<div id="atsPreviewHtml"></div>';
        $html .=    '</div>';
        $html .= '</div>';

        $container->template->addJSInline( <<<JS

;// This comment is intentionally put here to prevent badly written plugins from causing a Javascript error
// due to missing trailing semicolon and/or newline in their code.
akeeba.jQuery(document).ready(function()	{
	akeeba.jQuery('#$id').markItUp(myBBCodeSettings);
});

JS
        );

        if($cannedReplies && ATS_PRO)
        {
            \JHtml::_('behavior.modal');

            $container->template->addJS('media://com_ats/js/cannedreply.js', false, false, $container->mediaVersion);
            $siterootURL = JURI::base().'index.php';
            $container->template->addJSInline( <<<JS
var ats_siteroot_url = '$siterootURL';

function useCannedReply(newText, htmlContent)
{
	var allText = akeeba.jQuery('#bbcode').val();
	allText += newText;
	akeeba.jQuery('#bbcode').val(allText);

	try {
		document.getElementById('sbox-window').close();
	} catch(err) {
		SqueezeBox.close();
	}
}

JS
            );

            $url = 'index.php?option=com_ats&amp;view=CannedReplies&amp;enabled=1&amp;tmpl=component';

            // In backend we have to use a different layout, otherwise we will load the default listing
            if($container->platform->isBackend())
            {
                $url .= '&layout=choose';
            }

            if (isset($params['category']))
            {
                $url .= '&amp;category=' . $params['category'];
            }

            $html .= '<a class="modal" style="display: none" id="atsCannedReplyDialog" href="' . $url . '" rel="{handler: \'iframe\', size: {x: 800, y: 500}}">Select</a>';

        }

        if($bucket && ATS_PRO)
        {
            $container->template->addJS('media://com_ats/js/editor_buckets.js', false, false, $container->mediaVersion);
            $url = JRoute::_('index.php?option=com_ats&view=Buckets&task=choosebucket&layout=choose&tmpl=component');

            // Warning, we ALWAYS need the ticket
            if(isset($params['ats_ticket_id']))
            {
                $url .= '&ats_ticket_id='.$params['ats_ticket_id'];
            }

            $html .= '<a class="modal" style="display: none" id="atsBucketsDialog" href="' . $url . '" rel="{handler: \'iframe\', size: {x: 800, y: 500}}">Select</a>';
        }

        $html .= '<a class="" style="display: none" id="atsPreview">Select</a>';

        // Preview button
        if($returnHTML)
        {
            return $html;
        }
        else
        {
            echo $html;
        }
    }

    public static function showEditorJoomla($name, $id = null, $contents, $width, $height, $cols, $rows, $params = array())
    {
        $conf = JFactory::getConfig();
        $editor = $conf->get('editor');

        $editor  	   = \JEditor::getInstance($editor);
        $buttons 	   = false;
        $asset   	   = null;
        $author  	   = null;
        $other_buttons = '';

        if(array_key_exists('buttons', $params))
        {
            $buttons = $params['buttons'];
            unset($params['buttons']);
        }

        if(array_key_exists('asset', $params))
        {
            $asset = $params['asset'];
            unset($params['asset']);
        }

        if(array_key_exists('author', $params))
        {
            $author = $params['author'];
            unset($params['author']);
        }

        $cannedReplies = false;

        if(array_key_exists('cannedreplies', $params))
        {
            $cannedReplies = $params['cannedreplies'];
            unset($params['cannedreplies']);
        }

        $buckets = false;

        if(array_key_exists('buckets', $params))
        {
            $buckets = $params['buckets'];
            unset($params['buckets']);

            if(array_key_exists('ats_ticket_id', $params))
            {
                $ticket_id = $params['ats_ticket_id'];
                unset($params['ats_ticket_id']);

            }
        }


        $html =  $editor->display( $name,  $contents, $width, $height, $cols, $rows, $buttons, $id, $asset, $author, $params );

        $html .= '<div>';
        if($cannedReplies && ATS_PRO) {
            $siterootURL = JURI::base().'index.php';
            JFactory::getDocument()->addScriptDeclaration( <<<JS
var ats_siteroot_url = '$siterootURL';

function useCannedReply(newText, htmlContent)
{
	jInsertEditorText(htmlContent, 'ats-content');

	try {
		document.getElementById('sbox-window').close();
	} catch(err) {
		SqueezeBox.close();
	}
}

JS
            );

            $url = 'index.php?option=com_ats&amp;view=CannedReplies&amp;enabled=1&amp;tmpl=component';

            if (isset($params['category']))
            {
                $url .= '&amp;category=' . $params['category'];
            }

            $other_buttons .= '<span class="pull-left ats-canned-reply-button"><a class="modal btn btn-small" id="atsCannedReplyDialog" href="' . $url . '" rel="{handler: \'iframe\', size: {x: 800, y: 500}}">'.
                '<i class="icon-book"></i> '.
                JText::_('COM_ATS_POST_INSERTCANNEDREPLY_LBL').
                '</a></span>';

        }

        if($buckets && ATS_PRO)
        {
            $url = JRoute::_('index.php?option=com_ats&view=Buckets&task=choosebucket&layout=choose&tmpl=component');

            // Warning, we ALWAYS need the ticket
            if(isset($ticket_id))
            {
                $url .= '&ats_ticket_id='.$ticket_id;
            }

            $other_buttons .= '<span class="pull-left ats-canned-reply-button"><a class="modal btn btn-small" id="atsBucketsDialog" href="' . $url . '" rel="{handler: \'iframe\', size: {x: 800, y: 500}}">'.
                '<i class="icon-bookmark"></i> '.
                JText::_('COM_ATS_ADD_TO_BUCKET').
                '</a></span>';
        }

        $html .= $other_buttons.'</div>';

        echo $html;
    }
}