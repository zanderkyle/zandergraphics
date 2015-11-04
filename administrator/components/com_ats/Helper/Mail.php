<?php
/**
 * @package   AkeebaTicketSystem
 * @copyright Copyright (c)2011-2015 Nicholas K. Dionysopoulos
 * @license   GNU General Public License version 3, or later
 */

namespace Akeeba\TicketSystem\Admin\Helper;

use Akeeba\TicketSystem\Admin\Utils\Imap;
use Exception;
use JText;

defined('_JEXEC') or die;

/**
 * Mail download helper class
 */
class Mail
{
	/** @var string Username to connect to the IMAP/POP3 server */
	private $username = '';

	/** @var string Password to connect to the IMAP/POP3 server */
	private $password = '';

	/** @var string The mailbox connection string, used internally */
	private $mailbox = null;

	/** @var resource A mailbox connection */
	private $connection = null;

    /** @var Imap Connector to the remote inbox */
    private $connector;

    /**
	 * Public constructor. The $params array can contain the following variables:
	 * - mailbox_type            (required if not GMail) 'pop3' or 'imap'
	 * - server                    (required if not GMail) host name of the IMAP or POP3 mail server
	 * - port                    (optional) mail server port
	 * - ssl                    (optional) use SSL?
	 * - tls                    (optional) use TLS? 0 = no, 1 = if available, 2 = always
	 * - validate_certificate    (optional) true to validate the SSL certificates
	 * - gmail                    (optional) set to true and omit everything else if it's GMail / Google Apps
	 * - username                (required) Username
	 * - password                (required) Password
	 * - folder                    (optional) Folder containing email (only for IMAP), default is INBOX
	 *
	 * @param array $params
	 */
	public function __construct($params = array())
	{
		$gmail = false;

        // Mailbox type; pop3 or imap
        $mailbox_type = 'pop3';
        // Mail server hostname
        $server = '';
        // Mail server port. Usually 110 for POP3, 143 for IMAP, 995 for POP3S and 993 for IMAPS
        $port = 110;
        // Use SSL?
        $ssl = false;
        // Use TLS? 0 = no, 1 = if available, 2 = always */
        $tls = '';
        // Set to true ())default) to validate SSL certificates
        $validate_certificate = true;

        if (array_key_exists('gmail', $params))
		{
			if ($params['gmail'])
			{
				$mailbox_type = 'imap';
				$server       = 'imap.gmail.com';
				$port         = 993;
				$ssl          = true;
				$tls          = 1;
				$gmail        = true;
			}
		}

		if (array_key_exists('mailbox_type', $params) && !$gmail)
		{
			if (strtolower($params['mailbox_type']) == 'pop3')
			{
				$mailbox_type = 'pop3';
			}
			else
			{
				$mailbox_type = 'imap';
			}
		}

		if (array_key_exists('server', $params) && !$gmail)
		{
			$server = $params['server'];
		}

		if (array_key_exists('ssl', $params) && !$gmail)
		{
			$ssl = $params['ssl'] ? true : false;
		}

		if (array_key_exists('tsl', $params))
		{
			$tls = (int)$params['tsl'];

			if (($tls < 0) || ($tls > 2))
			{
				$tls = 0;
			}
		}

		if (array_key_exists('port', $params) && !$gmail)
		{
			$port = (int)$params['port'];
		}
		elseif (!$gmail)
		{
			if ($mailbox_type == 'pop3')
			{
				if (!$ssl)
				{
					$port = 110;
				}
				else
				{
					$port = 995;
				}
			}
			else
			{
				if (!$ssl)
				{
					$port = 143;
				}
				else
				{
					$port = 993;
				}
			}
		}

		if (array_key_exists('username', $params))
		{
			$this->username = $params['username'];
		}

		if (array_key_exists('password', $params))
		{
			$this->password = $params['password'];
		}

		if (array_key_exists('validate_certificate', $params))
		{
			$validate_certificate = $params['validate_certificate'] ? true : false;
		}

		if (array_key_exists('params', $params))
		{
			$params = (array)$params['params'];
		}

		$email_folder = '';

		if (($mailbox_type == 'imap') && array_key_exists('folder', $params))
		{
			$email_folder = $params['folder'];
		}
		elseif (($mailbox_type == 'imap'))
		{
			$email_folder = 'INBOX';
		}

		$this->mailbox = '{' . $server . ':' . $port;

		if ($ssl)
		{
			$this->mailbox .= '/ssl';
		}

		if ($tls == 0)
		{
			$this->mailbox .= '/notls';
		}
		elseif ($tls == 2)
		{
			$this->mailbox .= '/tls';
		}

		$this->mailbox .= '/' . $mailbox_type;

		if ($validate_certificate)
		{
			$this->mailbox .= '/validate-cert';
		}
		else
		{
			$this->mailbox .= '/novalidate-cert';
		}

		$this->mailbox .= '}' . $email_folder;

        $this->connector = new Imap();
	}

	/**
	 * Tries to connect to the specified mail server
	 *
	 * @throws Exception
	 */
	public function open()
	{
		// The mailbox is in this format:
		// {www.example.com:143/imap/ssl/notls/novalidate-cert}Folder/subfolder

        $this->connector->isSupported();

		// PHP 5.3.2 or later
		$this->connection = $this->connector->open($this->mailbox, $this->username, $this->password);

		if (empty($this->connection))
		{
			$errors = $this->connector->getErrors();
			$error  = array_pop($errors);

			throw new Exception(JText::_('COM_ATS_ERR_MAIL_CANNOTCONNECT') . ' : ' . $error, 500);
		}
	}

	/**
	 * Reopens the connection to the mail server
	 *
	 * @param string $mailbox (optional) Mailbox string to connect to, or empty to retry connecting using this object's connection parameters
	 *
	 * @throws Exception
	 */
	public function reopen($mailbox = null)
	{
		if (empty($mailbox))
		{
			$mailbox = $this->mailbox;
		}

        $result = $this->connector->reopen($this->connection, $mailbox);

		if (!$result)
		{
			$errors = $this->connector->getErrors();
			$error  = array_pop($errors);

			throw new Exception(JText::_('COM_ATS_ERR_MAIL_CANNOTCONNECT') . ' : ' . $error, 500);
		}
	}

	/**
	 * Closes the connection to the mail server
	 *
	 * @throws Exception
	 */
	public function close()
	{
		if (is_resource($this->connection))
		{
			$result = $this->connector->close($this->connection);
		}
		else
		{
			$result = true;
		}

		if (!$result)
		{
			$errors = $this->connector->getErrors();
			$error  = is_array($errors) && !empty($errors) ? array_pop($errors) : "";

			throw new Exception(JText::_('COM_ATS_ERR_MAIL_CANNOTCLOSE') . ' : ' . $error);
		}
	}

	/**
	 * Expunges the mailbox and closes the connection to the mail server. This
	 * should be called *instead* of close() if you don't want your mailbox to
	 * fill up.
	 *
	 * @throws Exception
	 */
	public function expunge()
	{
		$result = $this->connector->expunge($this->connection);

		if (!$result)
		{
			$errors = $this->connector->getErrors();
			$error  = is_array($errors) && !empty($errors) ? array_pop($errors) : "";

			throw new Exception(JText::_('COM_ATS_ERR_MAIL_CANNOTEXPUNGE') . ' : ' . $error);
		}

		$this->close();
	}

	/**
	 * Returns a mailbox connection info object. See imap_check() for more info.
	 *
	 * @return object
	 */
	public function check()
	{
		$info = $this->connector->check($this->connection);

		return $info;
	}

	/**
	 * Deletes a message
	 *
	 * @param int $messageId Message ID to delete
	 *
	 * @throws Exception
	 */
	public function delete($messageId)
	{
		$result = $this->connector->delete($this->connection, $messageId);

		if (!$result)
		{
			$errors = $this->connector->getErrors();
			$error  = is_array($errors) && !empty($errors) ? array_pop($errors) : "";

			throw new Exception(JText::_('COM_ATS_ERR_MAIL_CANNOTDELETE') . ' ' . $messageId . ' : ' . $error, 500);
		}
	}

	/**
	 * Marks one or several messages as read
	 *
	 * @param int|array|string $messageIds A message ID, or an array of message IDs or a string with a comma-separated list of message IDs
	 *
	 * @throws Exception
	 */
	public function markRead($messageIds)
	{
		if (is_array($messageIds))
		{
			$messageIds = implode(',', $messageIds);
		}

		$result = $this->connector->setFlag($this->connection, $messageIds, '\\Seen');

		if (!$result)
		{
			$errors = $this->connector->getErrors();
			$error  = is_array($errors) && !empty($errors) ? array_pop($errors) : "";

			throw new Exception(JText::_('COM_ATS_ERR_MAIL_CANNOTMARKREAD') . ' ' . $messageIds . ' : ' . $error, 500);
		}
	}

	/**
	 * Returns the headers of messages received since a specific date
	 *
	 * @param string $date
	 *
	 * @return array
	 */
	public function getHeadersSince($date = '')
	{
		$mids = $this->getMIDsSince($date);
		$ret  = array();

		foreach ($mids as $k => $mid)
		{
			$ret[] = $this->getHeader($mid);
		}

		return $ret;
	}

	/**
	 * Returns the full messages received since a specific date
	 *
	 * @param string $date
	 *
	 * @return array
	 */
	public function getMessagesSince($date = '')
	{
		$mids = $this->getMIDsSince($date);
		$ret  = array();

		if (!empty($mids))
		{
			foreach ($mids as $k => $mid)
			{
				$ret[] = $this->getMessage($mid);
			}
		}

		return $ret;
	}

	/**
	 * Gets the Message IDs since a specific date
	 *
	 * @param string $date An IMAP-compatible date, e.g. 31-12-1998
	 *
	 * @return array
     *
	 * @throws Exception
	 */
	public function getMIDsSince($date = '')
	{
		if (is_null($date))
		{
			$messages = $this->connector->search($this->connection, 'UNDELETED');
		}
		elseif (empty($date))
		{
			$messages = $this->connector->search($this->connection, 'RECENT UNDELETED');
		}
		else
		{
			$messages = $this->connector->search($this->connection, 'SINCE "' . $date . '" UNDELETED');
		}

		if ($messages === false)
		{
			$errors   = $this->connector->getErrors();
			$error    = is_array($errors) && !empty($errors) ? array_pop($errors) : "";
			$messages = array();

			if (!empty($error))
			{
				throw new Exception(JText::_('COM_ATS_ERR_MAIL_CANNOTRETRIEVE') . ' ' . $error);
			}
		}

		return $messages;
	}

	/**
	 * Returns the headers of a message
	 *
	 * @param int $mid Message ID
	 *
	 * @return array
	 */
	public function getHeader($mid)
	{
		$header = $this->connector->headerInfo($this->connection, $mid);

		$message = array(
			'subject'     => $header->subject,
			'fromaddress' => $header->fromaddress,
			'fromemail'   => $header->from[0]->mailbox . '@' . $header->from[0]->host,
			'toaddress'   => $header->toaddress,
			'toemail'     => $header->to[0]->mailbox . '@' . $header->to[0]->host,
			'date'        => $header->MailDate, // email delivery date
			'sentdate'    => $header->date,
			'unixdate'    => $header->udate,
			'mid'         => $mid
		);

		return $message;
	}

	/**
	 * Returns a message's headers and body
	 *
	 * @param int $mid The message ID
	 *
	 * @return array
	 */
	public function getMessage($mid)
	{
		$header = $this->connector->headerInfo($this->connection, $mid);
		$struct = $this->connector->fetchStructure($this->connection, $mid);
		$uid    = $this->connector->uid($this->connection, $mid);

		$ticketid   = 0;
		$rawheaders = $this->connector->fetchHeader($this->connection, $mid);
		$rawheaders = explode("\n", $rawheaders);

		if (is_array($rawheaders) && count($rawheaders))
		{
			foreach ($rawheaders as $line)
			{
				$line = trim($line);
				$line = strtolower($line);
				if (substr($line, 0, 14) == 'x-ats-ticketid')
				{
					$parts = explode(':', $line, 2);
					$ticketid = $parts[1];
				}
			}
		}

		$message = array(
			'subject'     => $header->subject,
			'fromaddress' => $header->fromaddress,
			'fromemail'   => $header->from[0]->mailbox . '@' . $header->from[0]->host,
			'toaddress'   => $header->toaddress,
			'toemail'     => $header->to[0]->mailbox . '@' . $header->to[0]->host,
			'date'        => $header->MailDate, // email delivery date
			'sentdate'    => $header->date,
			'unixdate'    => $header->udate,
			'mid'         => $mid,
			'uid'         => $uid,
			'ticketid'    => $ticketid,
			'parts'       => array(),
		);

		$plainText = $this->get_part($mid, "TEXT/PLAIN");
		$HTML      = $this->get_part($mid, "TEXT/HTML");

		if (!empty($HTML))
		{
			$message['body'] = $HTML;
		}
		elseif (!empty($plainText))
		{
			$message['body'] = $plainText;
		}
		else
		{
			$message['body'] = '[EMAIL CONTAINS NO MESSAGE BODY]';
		}

		// Get attachments
		$attachments = array();
		$this->extractAttachments($struct, $mid, $attachments);
		$message['parts'] = $attachments;

		return $message;
	}

	private function extractAttachments($struct, $mid, &$attachments, $prefix = '')
	{
        if(empty($struct->parts))
        {
            return;
        }

        foreach ($struct->parts as $partNo => $p)
        {
            $params = array();
            $filename = null;

            // Get all parameters of the messages into a single array
            if (property_exists($p, 'parameters'))
            {
                foreach ($p->parameters as $x)
                {
                    $params[strtolower($x->attribute)] = $x->value;
                }
            }

            if (property_exists($p, 'dparameters'))
            {
                foreach ($p->dparameters as $x)
                {
                    $params[strtolower($x->attribute)] = $x->value;
                }
            }

            // Do we have an attachment filename?
            if (array_key_exists('filename', $params))
            {
                $filename = $params['filename'];
            }
            elseif (array_key_exists('name', $params))
            {
                $filename = $params['name'];
            }

            // What to do if this part is not an attachment?
            if (is_null($filename))
            {
                // If it has sub-parts try to extract attachments from it
                if (property_exists($p, 'parts'))
                {
                    $tempPrefix = ($prefix ? $prefix . '.' : '') . ($partNo + 1);
                    $tempPrefix = rtrim(str_replace('..', '.', $tempPrefix), '.');
                    $this->extractAttachments($p, $mid, $attachments, $tempPrefix);
                }

                continue;
            }

            $mime = $this->get_mime_type($p);
            $tempPrefix = ($prefix ? $prefix . '.' : '') . ($partNo + 1);
            $tempPrefix = rtrim(str_replace('..', '.', $tempPrefix), '.');
            $data = $this->get_part($mid, false, $p, $tempPrefix);

            $attachments[] = (object)array(
                'filename' => $filename,
                'data'     => $data,
                'mime'     => $mime,
            );
        }
	}

	private function get_mime_type(&$structure)
	{
		$primary_mime_type = array("TEXT", "MULTIPART", "MESSAGE", "APPLICATION", "AUDIO", "IMAGE", "VIDEO", "OTHER");

		if ($structure->subtype)
		{
			return $primary_mime_type[(int)$structure->type] . '/' . $structure->subtype;
		}

		return "TEXT/PLAIN";
	}

	private function get_part($msg_number, $mime_type = false, $structure = false, $part_number = false)
	{
		if (!$structure)
		{
			$structure = $this->connector->fetchStructure($this->connection, $msg_number);
		}

        if(!$structure)
        {
            return false;
        }

        if ($structure->type == 1) /* multipart */
        {
            $data = '';

            while (list($index, $sub_structure) = each($structure->parts))
            {
                if ($part_number)
                {
                    $prefix = $part_number . '.';
                }
                else
                {
                    $prefix = '';
                }

                $data .= $this->get_part($msg_number, $mime_type, $sub_structure, $prefix . ($index + 1));
            }

            return $data;
        }
        elseif (($mime_type === false) || ($mime_type == $this->get_mime_type($structure)))
        {
            if (!$part_number)
            {
                $part_number = "1";
            }

            $text = $this->connector->fetchBody($this->connection, $msg_number, $part_number);

            if ($structure->encoding == 3)
            {
                return $this->connector->base64($text);
            }
            else if ($structure->encoding == 4)
            {
                return $this->connector->qprint($text);
            }
            else
            {
                return $text;
            }
        }

		return false;
	}
}