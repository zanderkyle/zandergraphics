<?php
/**
 * @package   AkeebaTicketSystem
 * @copyright Copyright (c)2011-2015 Nicholas K. Dionysopoulos
 * @license   GNU General Public License version 3, or later
 */

namespace Akeeba\TicketSystem\Admin\Model;

use FOF30\Container\Container;
use JDate;
use JFile;
use JPath;
use JText;

defined('_JEXEC') or die;

/**
 * Class Attachments
 *
 * @property    int     ats_attachment_id   Primary key
 * @property    string  original_filename   Original name of the attachment
 * @property    string  mangled_filename    Hashed filename
 * @property    string  mime_type           File mime-type
 * @property    int     ats_post_id         Link to the post containing the attachment
 *
 * Filters:
 *
 * @method  $this   ids_search($ids)        Search for a set of attachment ids
 * @method  $this   created_on($search)     Search on created_on field
 *
 * @property-read   Posts   post    Post linked to the attachment
 *
 * @package Akeeba\TicketSystem\Admin\Model
 */
class Attachments extends DefaultDataModel
{
    public function __construct(Container $container, array $config = array())
    {
        parent::__construct($container, $config);

        $this->addBehaviour('Filters');

        $this->belongsTo('post', 'Posts', 'ats_post_id', 'ats_post_id');

        $this->autoChecks = false;
    }

    /**
     * @param null $oid
     *
     * @return bool
     */
    public function deleteFile($oid = null)
    {
        if($oid)
        {
            $this->load($oid);
        }

        // Delete the physical file before deleting the record
        if($this->mangled_filename)
        {
            $filename = JPATH_ROOT.'/media/com_ats/attachments/'.$this->mangled_filename;

            \JLoader::import('joomla.filesystem.file');

            if(JFile::exists($filename))
            {
                return JFile::delete($filename);
            }
        }

        return true;
    }

    protected function onBeforeDelete($oid)
    {
        // Delete the physical file before deleting the record
        $this->deleteFile();

        // Update post record removing the link to this attachment
        /** @var Posts $post */
        $post = $this->container->factory->model('Posts')->tmpInstance();
        $post->load($this->ats_post_id);

        if($post->ats_post_id)
        {
            $attachments = array_diff($post->ats_attachment_id, array($oid));

            // If it's an empty set, let's add 0 for consistency vs previous versions
            if(!$attachments)
            {
                $attachments[] = 0;
            }

            $data['ats_attachment_id'] = $attachments;

            $post->save($data);
        }
    }

    public function buildQuery($override = false)
    {
        $db = $this->getDbo();

        $query = parent::buildQuery($override);

        if($ids = $this->getState('ids_search', array(), 'array'))
        {
            $ids = array_map(array($db, 'quote'), $ids);
            $query->where($db->qn('ats_attachment_id').' IN ('.implode(',',$ids).')');
        }

        return $query;
    }

    public function updateSavedAttachments($attachments, $post)
    {
        if(!is_array($attachments))
        {
            $attachments = explode(',', $attachments);
        }

        $db = $this->getDbo();

        $attachments = array_map(array($db, 'quote'), $attachments);

        $query = $db->getQuery(true)
                    ->update($db->qn('#__ats_attachments'))
                    ->set($db->qn('ats_post_id').' = '.$db->q($post))
                    ->where($db->qn('ats_attachment_id').' IN('.implode(',', $attachments).')');
        $db->setQuery($query)->execute();
    }

    /**
     * Process all the uploaded files
     *
     * @param   array   $files  Uploaded files. Please note that this array should be already organized following JInputFiles
     *                          behavior, ie $files = array(0 => array(file details), 1 => array(file details))
     *
     * @return array|bool
     */
    public function manageUploads($files)
    {
        if(!is_array($files))
        {
            return false;
        }

        if(!isset($files[0]['name']))
        {
            return false;
        }

        \JLoader::import('joomla.utilities.date');

        $w_files    = array();
        $errors     = array();
        $return     = array();

        // We allow only 10 attachments maximum
        for($i = 0; $i < 10; $i++)
        {
            if(!isset($files[$i]))
            {
                break;
            }

            // Remove whitespaces or MediaManager will report an error
            $files[$i]['name'] = str_replace(' ', '_', $files[$i]['name']);

            $w_files[] = $files[$i];
        }

        $files = $w_files;

        foreach($files as $file)
        {
            try
            {
                $filedef = $this->uploadFile($file, true);
            }
            catch(\Exception $e)
            {
                $errors[] = JText::sprintf('COM_ATS_ATTACHMENTS_ERR_UPLOAD', $file['name'], $e->getMessage());

                continue;
            }

            $this->reset();
            $this->ats_attachment_id = null;

            $jdate = new JDate();

            $filedef['created_by'] = $this->container->platform->getUser()->id;
            $filedef['created_on'] = $jdate->toSql();
            $filedef['enabled']    = 1;

            try{
                $this->save($filedef);
            }
            catch(\Exception $e)
            {
                $errors[] = JText::sprintf('COM_ATS_ATTACHMENTS_ERR_UPLOAD', $file['name'], $e->getMessage());

                continue;
            }

            $return[] = $this->ats_attachment_id;
        }

        return array(
            implode(',', $return),
            $errors
        );
    }

    /**
     * Moves an uploaded file to the media://com_ats/attachments directory
     * under a random name and returns a full file definition array, or false if
     * the upload failed for any reason.
     *
     * @param array $file The file descriptor returned by PHP
     * @param boolean $checkUpload Should I check if upload is alloed for this file?
     *
     * @return array
     *
     * @throws \Exception
     */
    public function uploadFile($file, $checkUpload = true)
    {
        if(!isset($file['name']))
        {
            throw new \Exception(JText::_('COM_ATS_ATTACHMENTS_ERR_NOFILE'));
        }

        if (isset($file['name']))
        {
            \JLoader::import('joomla.filesystem.file');

            // Can we upload this file type?
            if ($checkUpload)
            {
                $paths = array(JPATH_ROOT, JPATH_ADMINISTRATOR);

                $jlang = $this->container->platform->getLanguage();
                $jlang->load('com_media', $paths[0], 'en-GB', true);
                $jlang->load('com_media', $paths[0], null, true);
                $jlang->load('com_media', $paths[1], 'en-GB', true);
                $jlang->load('com_media', $paths[1], null, true);

                $mediaHelper = new \JHelperMedia;

                if (!$mediaHelper->canUpload($file, 'com_media'))
                {
                    $app = \JFactory::getApplication();
                    $errors = $app->getMessageQueue();

                    if (count($errors))
                    {
                        $error = array_pop($errors);
                        $err   = $error['message'];
                    }
                    else
                    {
                        $err = '';
                    }

                    if (!empty($err))
                    {
                        throw new \Exception(JText::_('COM_ATS_ATTACHMENTS_ERR_MEDIAHELPERERROR').' '.$err);
                    }
                    else
                    {
                        throw new \Exception(JText::_('COM_ATS_POSTS_ERR_ATTACHMENTERRORGENERIC'));
                    }
                }
            }

            // Get a (very!) randomised name
            $serverkey = \JFactory::getConfig()->get('secret','');
            $sig       = $file['name'].microtime().$serverkey;

            if(function_exists('sha256'))
            {
                $mangledname = sha256($sig);
            }
            elseif(function_exists('sha1'))
            {
                $mangledname = sha1($sig);
            }
            else
            {
                $mangledname = md5($sig);
            }

            // ...and its full path
            $filepath = JPath::clean(JPATH_ROOT.'/media/com_ats/attachments/'.$mangledname);

            // If we have a name clash, abort the upload
            if (JFile::exists($filepath))
            {
                throw new \Exception(JText::_('COM_ATS_ATTACHMENTS_ERR_NAMECLASH'));
            }

            // Do the upload
            if ($checkUpload)
            {
                if (!JFile::upload($file['tmp_name'], $filepath))
                {
                    throw new \Exception(JText::_('COM_ATS_ATTACHMENTS_ERR_CANTJFILEUPLOAD'));
                }
            }
            else
            {
                if (!JFile::copy($file['tmp_name'], $filepath))
                {
                    throw new \Exception(JText::_('COM_ATS_ATTACHMENTS_ERR_CANTJFILEUPLOAD'));
                }
            }

            // Get the MIME type
            if(function_exists('mime_content_type'))
            {
                $mime = mime_content_type($filepath);
            }
            elseif(function_exists('finfo_open'))
            {
                $finfo = finfo_open(FILEINFO_MIME_TYPE);
                $mime = finfo_file($finfo, $filepath);
            }
            else
            {
                $mime = 'application/octet-stream';
            }

            // Return the file info
            return array(
                'original_filename'	=> $file['name'],
                'mangled_filename'	=> $mangledname,
                'mime_type'			=> $mime
            );
        }
    }

    public function doDownload()
    {
        \JLoader::import('joomla.filesystem.folder');
        \JLoader::import('joomla.filesystem.file');
        \JLoader::import('joomla.utilities.date');

        // Calculate the Etag
        $etagContent = $this->mangled_filename.$this->mime_type.$this->original_filename.$this->created_on.$this->created_by;

        if(function_exists('sha1'))
        {
            $eTag = sha1($etagContent);
        }
        else
        {
            $eTag = md5($etagContent);
        }

        // Do we have an If-None-Match header?
        $inm = '';

        if(function_exists('apache_request_headers'))
        {
            $headers = apache_request_headers();

            if(array_key_exists('If-None-Match', $headers))
            {
                $inm = $headers['If-None-Match'];
            }
        }

        if(empty($inm))
        {
            if(array_key_exists('HTTP-IF-NONE-MATCH', $_SERVER))
            {
                $inm = $_SERVER['HTTP-IF-NONE-MATCH'];
            }
        }

        if($inm == $eTag)
        {
            while (@ob_end_clean());
            header('HTTP/1.0 304 Not Modified');

            $this->container->platform->closeApplication();
        }

        $filepath = \JPath::clean(JPATH_ROOT.'/media/com_ats/attachments/'.$this->mangled_filename);
        $basename = $this->original_filename;

        if(!\JFile::exists($filepath))
        {
            header('HTTP/1.0 404 Not Found');
            $this->container->platform->closeApplication();
        }

        $this->input->set('format', 'raw');

        // Disable error reporting and error display
        if(function_exists('error_reporting'))
        {
            error_reporting(0);
        }

        if(function_exists('ini_set'))
        {
            @ini_set('display_error', 0);
        }

        // Clear cache
        while (@ob_end_clean());

        // Fix IE bugs
        if (isset($_SERVER['HTTP_USER_AGENT']) && strstr($_SERVER['HTTP_USER_AGENT'], 'MSIE'))
        {
            $header_file = preg_replace('/\./', '%2e', $basename, substr_count($basename, '.') - 1);

            if (ini_get('zlib.output_compression'))
            {
                ini_set('zlib.output_compression', 'Off');
            }
        }
        else
        {
            $header_file = $basename;
        }

        @clearstatcache();

        // Disable caching for regular attachment disposition
        if($this->getState('disposition','attachment') !== 'attachment')
        {
            header("Pragma: public");
            header("Expires: 0");
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            header("Cache-Control: public", false);
        }

        // Send a Date header
        $jDate = new \JDate($this->created_on);

        header('Date: '.$jDate->toRFC822());

        // Send an Etag
        header('Etag: '.$eTag);

        // Send MIME headers
        header("Content-Description: File Transfer");

        if(empty($this->mime_type))
        {
            header('Content-Type: application/octet-stream');
        }
        else
        {
            header('Content-Type: '.$this->mime_type);
        }

        header("Accept-Ranges: bytes");

        if($this->getState('disposition','attachment') != 'attachment')
        {
            header('Content-Disposition: inline; filename="'.$header_file.'"');
        }
        else
        {
            header('Content-Disposition: attachment; filename="'.$header_file.'"');
        }

        header('Content-Transfer-Encoding: binary');

        // Notify of filesize, if this info is available
        $filesize = @filesize($filepath);

        if($filesize > 0)
        {
            header('Content-Length: '.(int)$filesize);
        }

        // Disable time limits
        if ( ! ini_get('safe_mode') )
        {
            set_time_limit(0);
        }

        // Use 1M chunks for echoing the data to the browser
        @flush();
        $chunksize = 1024*1024; //1M chunks

        $handle = @fopen($filepath, 'rb');

        if($handle !== false)
        {
            while (!feof($handle))
            {
                $buffer = fread($handle, $chunksize);

                echo $buffer;

                @ob_flush();
                flush();
            }

            @fclose($handle);
        }
        else
        {
            @readfile($filepath);
            @flush();
        }

        // Ungraceful application exit -- so that any plugins won't screw up the download...
        $this->container->platform->closeApplication();
    }
}