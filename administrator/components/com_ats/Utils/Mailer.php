<?php

namespace Akeeba\TicketSystem\Admin\Utils;

use JMailHelper;
use JText;

defined('_JEXEC') or die;

\JLoader::import('joomla.mail.mail');
\JLoader::import('joomla.mail.helper');
\JLoader::import('phpmailer.phpmailer');


/**
 * This ugly class is required because Joomla! obscures the AddAttachment
 * method in PHPMailer, making it impossible to add attachments with a different
 * name than the actual filename they are read from. Oh, Joomla!, you're the end
 * of mine!
 */
class Mailer extends \PHPMailer
{
    /**
     * @var    array  JMail instances container.
     * @since  11.3
     */
    protected static $instances = array();

    /** @var bool Is test mode enabled? If so mails will be saved inside the db and not sent */
    private $testMode = false;

    /**
     * Constructor
     */
    public function __construct()
    {
        if(file_exists(__DIR__.'/test.email'))
        {
            $this->testMode = true;
        }

        // PHPMailer has an issue using the relative path for its language files
        $this->SetLanguage('joomla', JPATH_PLATFORM . '/phpmailer/language/');
    }

    /**
     * Returns the global email object, only creating it
     * if it doesn't already exist.
     *
     * NOTE: If you need an instance to use that does not have the global configuration
     * values, use an id string that is not 'Joomla'.
     *
     * @param   string  $id  The id string for the JMail instance [optional]
     *
     * @return  \JMail  The global JMail object
     *
     * @since   11.1
     */
    public static function getInstance($id = 'Joomla')
    {
        if (empty(self::$instances[$id]))
        {
            self::$instances[$id] = new \JMail;
        }

        return self::$instances[$id];
    }

    /**
     * Send the mail
     *
     * @return  mixed  True if successful, a JError object otherwise
     *
     * @since   11.1
     */
    public function Send()
    {
        if($this->testMode)
        {
            $firstRecipient = array_pop($this->to);

            $attachment = '';

            if($this->attachment)
            {
                $attachment = $this->attachment[0][2];
            }

            $message = (object)array(
                'from_name'  => $this->FromName,
                'from_email' => $this->From,
                'to_name' => $firstRecipient[1],
                'to_email' => $firstRecipient[0],
                'subject' => $this->Subject,
                'body'	=> $this->Body,
                'attachment' => $attachment
            );

            $db = \JFactory::getDbo();

            try{
                $db->insertObject('#__phonymail', $message);
            }
            catch(\Exception $e){
                // Oh well, what the hell...
            }

            $result = true;
        }
        else
        {
            if (($this->Mailer == 'mail') && !function_exists('mail'))
            {
                return \JError::raiseNotice(500, JText::_('JLIB_MAIL_FUNCTION_DISABLED'));
            }

            @$result = parent::Send();

            if ($result == false)
            {
                // TODO: Set an appropriate error number
                $result = \JError::raiseNotice(500, JText::_($this->ErrorInfo));
            }
        }

        return $result;
    }

    /**
     * Set the email sender
     *
     * @param   array  $from  email address and Name of sender
     *                        <code>array([0] => email Address [1] => Name)</code>
     *
     * @return  \JMail  Returns this object for chaining.
     *
     * @since   11.1
     */
    public function setSender($from)
    {
        if (is_array($from))
        {
            // If $from is an array we assume it has an address and a name
            if (isset($from[2]))
            {
                // If it is an array with entries, use them
                $this->SetFrom(JMailHelper::cleanLine($from[0]), JMailHelper::cleanLine($from[1]), (bool) $from[2]);
            }
            else
            {
                $this->SetFrom(JMailHelper::cleanLine($from[0]), JMailHelper::cleanLine($from[1]));
            }
        }
        elseif (is_string($from))
        {
            // If it is a string we assume it is just the address
            $this->SetFrom(JMailHelper::cleanLine($from));
        }
        else
        {
            // If it is neither, we throw a warning
            \JError::raiseWarning(0, JText::sprintf('JLIB_MAIL_INVALID_EMAIL_SENDER', $from));
        }

        return $this;
    }

    /**
     * Set the email subject
     *
     * @param   string  $subject  Subject of the email
     *
     * @return  \JMail  Returns this object for chaining.
     *
     * @since   11.1
     */
    public function setSubject($subject)
    {
        $this->Subject = JMailHelper::cleanLine($subject);

        return $this;
    }

    /**
     * Set the email body
     *
     * @param   string  $content  Body of the email
     *
     * @return  \JMail  Returns this object for chaining.
     *
     * @since   11.1
     */
    public function setBody($content)
    {
        /*
         * Filter the Body
         * TODO: Check for XSS
         */
        $this->Body = JMailHelper::cleanText($content);

        return $this;
    }

    /**
     * Add recipients to the email
     *
     * @param   mixed  $recipient  Either a string or array of strings [email address(es)]
     * @param   mixed  $name       Either a string or array of strings [name(s)]
     *
     * @return  \JMail  Returns this object for chaining.
     *
     * @since   11.1
     */
    public function addRecipient($recipient, $name = '')
    {
        // If the recipient is an array, add each recipient... otherwise just add the one
        if (is_array($recipient))
        {
            foreach ($recipient as $to)
            {
                $to = JMailHelper::cleanLine($to);
                $this->AddAddress($to);
            }
        }
        else
        {
            $recipient = JMailHelper::cleanLine($recipient);
            $this->AddAddress($recipient);
        }

        return $this;
    }

    /**
     * Add carbon copy recipients to the email
     *
     * @param   mixed  $cc    Either a string or array of strings [email address(es)]
     * @param   mixed  $name  Either a string or array of strings [name(s)]
     *
     * @return  \JMail  Returns this object for chaining.
     *
     * @since   11.1
     */
    public function addCC($cc, $name = '')
    {
        // If the carbon copy recipient is an array, add each recipient... otherwise just add the one
        if (isset($cc))
        {
            if (is_array($cc))
            {
                foreach ($cc as $to)
                {
                    $to = JMailHelper::cleanLine($to);
                    parent::AddCC($to);
                }
            }
            else
            {
                $cc = JMailHelper::cleanLine($cc);
                parent::AddCC($cc);
            }
        }

        return $this;
    }

    /**
     * Add blind carbon copy recipients to the email
     *
     * @param   mixed  $bcc   Either a string or array of strings [email address(es)]
     * @param   mixed  $name  Either a string or array of strings [name(s)]
     *
     * @return  \JMail  Returns this object for chaining.
     *
     * @since   11.1
     */
    public function addBCC($bcc, $name = '')
    {
        // If the blind carbon copy recipient is an array, add each recipient... otherwise just add the one
        if (isset($bcc))
        {
            if (is_array($bcc))
            {
                foreach ($bcc as $to)
                {
                    $to = JMailHelper::cleanLine($to);
                    parent::AddBCC($to);
                }
            }
            else
            {
                $bcc = JMailHelper::cleanLine($bcc);
                parent::AddBCC($bcc);
            }
        }

        return $this;
    }

    /**
     * Add file attachments to the email
     *
     * @param   mixed  $attachment  Either a string or array of strings [filenames]
     * @param   mixed  $name        Either a string or array of strings [names]
     * @param   mixed  $encoding    The encoding of the attachment
     * @param   mixed  $type        The mime type
     *
     * @return  \JMail  Returns this object for chaining.
     *
     * @since   11.1
     */
    public function addAttachment($path, $name = '', $encoding = 'base64', $type = '', $disposition = 'attachment')
    {
        // If the file attachments is an array, add each file... otherwise just add the one
        if (isset($path))
        {
            if (is_array($path))
            {
                foreach ($path as $file)
                {
                    parent::AddAttachment($file);
                }
            }
            else
            {
                parent::AddAttachment($path);
            }
        }

        return $this;
    }

    /**
     * Add Reply to email address(es) to the email
     *
     * @param   array  $replyto  Either an array or multi-array of form
     *                           <code>array([0] => email Address [1] => Name)</code>
     * @param   array  $name     Either an array or single string
     *
     * @return  \JMail  Returns this object for chaining.
     *
     * @since   11.1
     */
    public function addReplyTo($replyto, $name = '')
    {
        // Take care of reply email addresses
        if (is_array($replyto[0]))
        {
            foreach ($replyto as $to)
            {
                $to0 = JMailHelper::cleanLine($to[0]);
                $to1 = JMailHelper::cleanLine($to[1]);

                parent::AddReplyTo($to0, $to1);
            }
        }
        else
        {
            $replyto0 = JMailHelper::cleanLine($replyto[0]);
            $replyto1 = JMailHelper::cleanLine($replyto[1]);

            parent::AddReplyTo($replyto0, $replyto1);
        }

        return $this;
    }

    /**
     * Use sendmail for sending the email
     *
     * @param   string  $sendmail  Path to sendmail [optional]
     *
     * @return  boolean  True on success
     *
     * @since   11.1
     */
    public function useSendmail($sendmail = null)
    {
        $this->Sendmail = $sendmail;

        if (!empty($this->Sendmail))
        {
            $this->IsSendmail();

            return true;
        }
        else
        {
            $this->IsMail();

            return false;
        }
    }

    /**
     * Use SMTP for sending the email
     *
     * @param   string   $auth    SMTP Authentication [optional]
     * @param   string   $host    SMTP Host [optional]
     * @param   string   $user    SMTP Username [optional]
     * @param   string   $pass    SMTP Password [optional]
     * @param   string   $secure  Use secure methods
     * @param   integer  $port    The SMTP port
     *
     * @return  boolean  True on success
     *
     * @since   11.1
     */
    public function useSMTP($auth = null, $host = null, $user = null, $pass = null, $secure = null, $port = 25)
    {
        $this->SMTPAuth = $auth;
        $this->Host = $host;
        $this->Username = $user;
        $this->Password = $pass;
        $this->Port = $port;

        if ($secure == 'ssl' || $secure == 'tls')
        {
            $this->SMTPSecure = $secure;
        }

        if (($this->SMTPAuth !== null && $this->Host !== null && $this->Username !== null && $this->Password !== null)
            || ($this->SMTPAuth === null && $this->Host !== null))
        {
            $this->IsSMTP();

            return true;
        }
        else
        {
            $this->IsMail();

            return false;
        }
    }

    /**
     * Function to send an email
     *
     * @param   string   $from         From email address
     * @param   string   $fromName     From name
     * @param   mixed    $recipient    Recipient email address(es)
     * @param   string   $subject      email subject
     * @param   string   $body         Message body
     * @param   boolean  $mode         false = plain text, true = HTML
     * @param   mixed    $cc           CC email address(es)
     * @param   mixed    $bcc          BCC email address(es)
     * @param   mixed    $attachment   Attachment file name(s)
     * @param   mixed    $replyTo      Reply to email address(es)
     * @param   mixed    $replyToName  Reply to name(s)
     *
     * @return  boolean  True on success
     *
     * @since   11.1
     */
    public function sendMail($from, $fromName, $recipient, $subject, $body, $mode = 0, $cc = null, $bcc = null, $attachment = null, $replyTo = null,
                             $replyToName = null)
    {
        $this->setSender(array($from, $fromName));
        $this->setSubject($subject);
        $this->setBody($body);

        // Are we sending the email as HTML?
        if ($mode)
        {
            $this->IsHTML(true);
        }

        $this->addRecipient($recipient);
        $this->addCC($cc);
        $this->addBCC($bcc);
        $this->addAttachment($attachment);

        // Take care of reply email addresses
        if (is_array($replyTo))
        {
            $numReplyTo = count($replyTo);

            for ($i = 0; $i < $numReplyTo; $i++)
            {
                $this->addReplyTo(array($replyTo[$i], $replyToName[$i]));
            }
        }
        elseif (isset($replyTo))
        {
            $this->addReplyTo(array($replyTo, $replyToName));
        }

        return $this->Send();
    }

    /**
     * Sends mail to administrator for approval of a user submission
     *
     * @param   string  $adminName   Name of administrator
     * @param   string  $adminEmail  Email address of administrator
     * @param   string  $email       [NOT USED TODO: Deprecate?]
     * @param   string  $type        Type of item to approve
     * @param   string  $title       Title of item to approve
     * @param   string  $author      Author of item to approve
     * @param   string  $url         A URL to included in the mail
     *
     * @return  boolean  True on success
     *
     * @since   11.1
     */
    public function sendAdminMail($adminName, $adminEmail, $email, $type, $title, $author, $url = null)
    {
        $subject = JText::sprintf('JLIB_MAIL_USER_SUBMITTED', $type);

        $message = sprintf(JText::_('JLIB_MAIL_MSG_ADMIN'), $adminName, $type, $title, $author, $url, $url, 'administrator', $type);
        $message .= JText::_('JLIB_MAIL_MSG') . "\n";

        $this->addRecipient($adminEmail);
        $this->setSubject($subject);
        $this->setBody($message);

        return $this->Send();
    }

    /**
     * Shamelessly copied from PHPMailer
     */
    public function AtsAddAttachment($path, $name = '', $encoding = 'base64', $type = 'application/octet-stream')
    {
        parent::addAttachment($path, $name, $encoding, $type);
    }
}