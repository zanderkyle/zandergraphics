<?php

namespace Akeeba\TicketSystem\Admin\Utils;

use Exception;
use JText;

defined('_JEXEC') or die;

class Imap
{
    /** @var bool Should I log the return value of every request performed? */
    private $log = false;

    public function __construct()
    {
        if(defined('JDEBUG') && JDEBUG)
        {
            if(\JFactory::getConfig()->get('error_reporting') == 'development')
            {
                $this->log = true;
            }
        }
    }

    public function isSupported()
    {
        // Double check we have the imap module installed
        if(!function_exists('imap_open'))
        {
            throw new Exception(JText::_('COM_ATS_ERR_MAIL_CANNOTCONNECT') . ' - IMAP extension not installed.', 500);
        }
    }

    public function getErrors()
    {
        $return = imap_errors();

        if($this->log)
        {
            $filename = 'getErrors_'.time().'.txt';
            file_put_contents(JPATH_ROOT.'/tmp/'.$filename, __METHOD__."\n".print_r($return, true));
        }

        return $return;
    }

    public function open($mailbox, $username, $password)
    {
        $return =  @imap_open($mailbox, $username, $password);

        if($this->log)
        {
            $filename = 'open_'.time().'.txt';
            file_put_contents(JPATH_ROOT.'/tmp/'.$filename, __METHOD__."\n".print_r($return, true));
        }

        return $return;
    }

    public function reopen($connection, $mailbox)
    {
        $return =  @imap_reopen($connection, $mailbox);

        if($this->log)
        {
            $filename = 'reopen_'.time().'.txt';
            file_put_contents(JPATH_ROOT.'/tmp/'.$filename, __METHOD__."\n".print_r($return, true));
        }

        return $return;
    }

    public function close($connection)
    {
        $return =  @imap_close($connection);

        if($this->log)
        {
            $filename = 'close_'.time().'.txt';
            file_put_contents(JPATH_ROOT.'/tmp/'.$filename, __METHOD__."\n".print_r($return, true));
        }

        return $return;
    }

    public function expunge($connection)
    {
        $return =  @imap_expunge($connection);

        if($this->log)
        {
            $filename = 'expunge_'.time().'.txt';
            file_put_contents(JPATH_ROOT.'/tmp/'.$filename, __METHOD__."\n".print_r($return, true));
        }

        return $return;
    }

    public function check($connection)
    {
        $return =  imap_check($connection);

        if($this->log)
        {
            $filename = 'check_'.time().'.txt';
            file_put_contents(JPATH_ROOT.'/tmp/'.$filename, __METHOD__."\n".print_r($return, true));
        }

        return $return;
    }

    public function delete($connection, $id)
    {
        $return =  @imap_delete($connection, $id);

        if($this->log)
        {
            $filename = 'delete_'.time().'.txt';
            file_put_contents(JPATH_ROOT.'/tmp/'.$filename, __METHOD__."\n".print_r($return, true));
        }

        return $return;
    }

    public function setFlag($connection, $id, $flag)
    {
        $return =  @imap_setflag_full($connection, $id, $flag);

        if($this->log)
        {
            $filename = 'setFlag_'.time().'.txt';
            file_put_contents(JPATH_ROOT.'/tmp/'.$filename, __METHOD__."\n".print_r($return, true));
        }

        return $return;
    }

    public function search($connection, $string)
    {
        $return =  @imap_search($connection, $string);

        if($this->log)
        {
            $filename = 'search_'.time().'.txt';
            file_put_contents(JPATH_ROOT.'/tmp/'.$filename, __METHOD__."\n".print_r($return, true));
        }

        return $return;
    }

    public function headerInfo($connection, $id)
    {
        $return =  @imap_headerinfo($connection, $id);

        if($this->log)
        {
            $filename = 'headerInfo_'.time().'.txt';
            file_put_contents(JPATH_ROOT.'/tmp/'.$filename, __METHOD__."\n".print_r($return, true));
        }

        return $return;
    }

    public function fetchHeader($connection, $id)
    {
        $return =  @imap_fetchheader($connection, $id);

        if($this->log)
        {
            $filename = 'fetchHeader_'.time().'.txt';
            file_put_contents(JPATH_ROOT.'/tmp/'.$filename, __METHOD__."\n".print_r($return, true));
        }

        return $return;
    }

    public function fetchStructure($connection, $id)
    {
        $return =  @imap_fetchstructure($connection, $id);

        if($this->log)
        {
            $filename = 'fetchStructure_'.time().'.txt';
            file_put_contents(JPATH_ROOT.'/tmp/'.$filename, __METHOD__."\n".print_r($return, true));
        }

        return $return;
    }

    public function fetchBody($connection, $id, $part)
    {
        $return =  imap_fetchbody($connection, $id, $part);

        if($this->log)
        {
            $filename = 'fetchBody_'.time().'.txt';
            file_put_contents(JPATH_ROOT.'/tmp/'.$filename, __METHOD__."\n".print_r($return, true));
        }

        return $return;
    }

    public function uid($connection, $id)
    {
        $return =  @imap_uid($connection, $id);

        if($this->log)
        {
            $filename = 'uid_'.time().'.txt';
            file_put_contents(JPATH_ROOT.'/tmp/'.$filename, __METHOD__."\n".print_r($return, true));
        }

        return $return;
    }

    public function base64($text)
    {
        $return =  imap_base64($text);

        if($this->log)
        {
            $filename = 'base64_'.time().'.txt';
            file_put_contents(JPATH_ROOT.'/tmp/'.$filename, __METHOD__."\n".print_r($return, true));
        }

        return $return;
    }

    public function qprint($text)
    {
        $return =  imap_qprint($text);

        if($this->log)
        {
            $filename = 'qprint_'.time().'.txt';
            file_put_contents(JPATH_ROOT.'/tmp/'.$filename, __METHOD__."\n".print_r($return, true));
        }

        return $return;
    }
}