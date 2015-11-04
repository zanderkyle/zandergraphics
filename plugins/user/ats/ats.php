<?php
/**
 * @package ats
 * @copyright Copyright (c)2011-2014 Nicholas K. Dionysopoulos / AkeebaBackup.com
 * @license GNU GPL v3 or later
 */

defined('JPATH_BASE') or die;
JLoader::import('joomla.utilities.date');

class plgUserAts extends JPlugin
{
	/**
	 * Constructor
	 *
	 * @access      protected
	 * @param       object  $subject The object to observe
	 * @param       array   $config  An array that holds the plugin configuration
	 * @since       1.5
	 */
	public function __construct(& $subject, $config)
	{
		parent::__construct($subject, $config);

		$this->loadLanguage();
	}

	/**
	 * @param	string	$context	The context for the data
	 * @param	int		$data		The user id
	 * @param	object
	 *
	 * @return	boolean
	 */
	public function onContentPrepareData($context, $data)
	{
		// Check we are manipulating a valid form.
		if (!in_array($context, array('com_users.profile','com_users.user', 'com_users.registration', 'com_admin.profile')))
        {
			return true;
		}

		if (is_object($data))
		{
			$userId = isset($data->id) ? $data->id : 0;

			if (!isset($data->profile) and $userId > 0)
            {
				// Load the profile data from the database.
				$db = JFactory::getDbo();

                $query = $db->getQuery(true)
                            ->select(array($db->qn('profile_key'), $db->qn('profile_value')))
                            ->from($db->qn('#__user_profiles'))
                            ->where($db->qn('user_id').' = '.$db->q($userId))
                            ->where($db->qn('profile_key').' LIKE '.$db->q('ats.%', false))
                            ->order($db->qn('ordering'));

                $results = $db->setQuery($query)->loadRowList();
                				// Check for a database error.
				// Merge the profile data.
				$data->ats = array();

				foreach ($results as $v)
				{
					$k = str_replace('ats.', '', $v[0]);
					$data->ats[$k] = $v[1];
				}
			}
		}

		return true;
	}

    /**
     * @param    JForm  $form   The form to be altered.
     * @param    array  $data   The associated data for the form.
     *
     * @return   bool
     *
     * @throws   Exception
     */
	public function onContentPrepareForm($form, $data)
	{

		if (!($form instanceof JForm))
		{
            throw new Exception('JERROR_NOT_A_FORM');
		}

		// Check we are manipulating a valid form.
		if (!in_array($form->getName(), array('com_admin.profile','com_users.user', 'com_users.registration','com_users.profile')))
        {
			return true;
		}

		// Add the registration fields to the form.
		JForm::addFormPath(dirname(__FILE__).'/ats');
		$form->loadFile('ats', false);

		return true;
	}

	public function onUserAfterSave($data, $isNew, $result, $error)
	{
		$userId	= JArrayHelper::getValue($data, 'id', 0, 'int');

		if ($userId && $result && isset($data['ats']) && (count($data['ats'])))
		{
            $db = JFactory::getDbo();

            $query = $db->getQuery(true)
                        ->delete($db->qn('#__user_profiles'))
                        ->where($db->qn('user_id').' = '.$db->q($userId))
                        ->where($db->qn('profile_key').' LIKE '.$db->q('ats.%', false));

            $db->setQuery($query)->execute();

            $order	= 1;

            $query = $db->getQuery(true)
                        ->insert($db->qn('#__user_profiles'))
                        ->columns(array($db->qn('user_id'), $db->qn('profile_key'), $db->qn('profile_value'), $db->qn('ordering')));

            foreach ($data['ats'] as $k => $v)
            {
                $query->values($userId.', '.$db->quote('ats.'.$k).', '.$db->quote($v).', '.$order++);
            }

            $db->setQuery($query)->execute();
		}

		return true;
	}

    /**
     * Remove all user profile information for the given user ID
     *
     * Method is called after user data is deleted from the database
     *
     * @param    array $user Holds the user data
     * @param    boolean $success True if user was succesfully stored in the database
     * @param    string $msg Message
     *
     * @return bool
     *
     * @throws Exception
     */
	public function onUserAfterDelete($user, $success, $msg)
	{
		if (!$success)
        {
			return false;
		}

		$userId	= JArrayHelper::getValue($user, 'id', 0, 'int');

		if ($userId)
		{
            $db = JFactory::getDbo();

            $query = $db->getQuery(true)
                        ->delete($db->qn('#__user_profiles'))
                        ->where($db->qn('user_id').' = '.$db->q($userId))
                        ->where($db->qn('profile_key').' LIKE '.$db->q('ats.%', false));

            $db->setQuery($query)->execute();
		}

		return true;
	}
}
