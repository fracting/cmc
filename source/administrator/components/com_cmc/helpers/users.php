<?php
/**
 * @package    Cmc
 * @author     DanielDimitrov <daniel@compojoom.com>
 * @date       05.09.13
 *
 * @copyright  Copyright (C) 2008 - 2013 compojoom.com . All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die('Restricted access');

/**
 * Class CmcHelperUsers
 *
 * @since  1.2
 */
class CmcHelperUsers
{
	protected static $bindings = array(
		'mc_id'            => array(
			'column' => 'id'
		),
		'list_id'          => array(),
		'email'            => array(),
		'email_type'       => array(),
		'ip_signup'        => array(),
		'timestamp_signup' => array(),
		'timestamp_signup' => array(),
		'ip_opt'           => array(),
		'timestamp_opt'    => array(),
		'member_rating'    => array(),
		'info_changed'     => array(),
		'web_id'           => array(),
		'language'         => array(),
		'is_gmonkey'       => array(),
		'geo'              => array(
			'handle' => 'json_encode'
		),
		'clients'          => array(
			'handle' => 'json_encode'
		),
		'merges'           => array(
			'handle' => 'json_encode'
		),
		'timestamp'        => array(),
		'status'           => array(),
		'static_segments' => array(
			'handle' => 'json_encode'
		)
	);

	/**
	 * Saves a batch of users to the db
	 *
	 * @param   array   $users     - the users to save in the db
	 * @param   int     $jListId   - the joomla list id
	 * @param   string  $mcListId  - the list id
	 *
	 * @return mixed
	 */
	public static function save($users, $jListId, $mcListId)
	{
		$db      = JFactory::getDbo();
		$query   = $db->getQuery(true);

		$members = array();

		// Get all e-mails from the array
		$emails = array_map(
			function ($ar) {
				return $ar['email'];
			}, $users
		);

		// Find out if the users on the list are already members of the site
		$jUsers = self::getJoomlaUsers($emails);

		foreach ($users as $member)
		{
			$item = self::bind($member, $jUsers);
			array_walk(
				$item,
				function(&$value) use ($db) {
					// Escape the value
					$value = $db->quote($value);
				}
			);

			$members[] = implode(',', $item);
		}

		$query->insert('#__cmc_users')
			->columns(implode(',', array_keys(self::$bindings)) . ',user_id,firstname, lastname,created_user_id,created_time,modified_user_id,modified_time,query_data ')
			->values($members);

		$db->setQuery($query);

		return $db->execute();
	}

	/**
	 * Binds the information from the listMemberInfo function to the local user table structure
	 *
	 * @param   array  $member  - the member data
	 * @param   array  $jUsers  - joomla users array with email as key
	 *
	 * @return array
	 */
	public static function bind($member, $jUsers)
	{
		$user    = JFactory::getUser();

		$item = array();

		foreach (self::$bindings as $bkey => $bvalue)
		{
			if (!empty($bvalue))
			{
				if (isset($bvalue['column']) && isset($member[$bvalue['column']]))
				{
					$item[$bkey] = isset($bvalue['handle']) ? $member[$bvalue['handle']]($member[$bvalue['column']]) : $member[$bvalue['column']];
				}
				else
				{
					$item[$bkey] = isset($bvalue['handle']) ? $bvalue['handle']($member[$bkey]) : $member[$bkey];
				}
			}
			else
			{
				$item[$bkey] = $member[$bkey];
			}
		}

		$item['user_id'] = isset($jUsers[$member['email']]) ? $jUsers[$member['email']]->id : 0;
		$item['firstname'] = $member['merges']['FNAME'];
		$item['lastname'] = $member['merges']['LNAME'];
		$item['created_user_id']  = $user->id;
		$item['created_time']     = JFactory::getDate()->toSql();
		$item['modified_user_id'] = $user->id;
		$item['modified_time']    = JFactory::getDate()->toSql();
		$item['query_data']       = json_encode($member);

		return $item;
	}

	/**
	 * Delete users from the db belonging to the mailchimp list
	 *
	 * @param   int  $listId  - the list id
	 *
	 * @return mixed
	 */
	public static function delete($listId)
	{
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query->delete($db->qn('#__cmc_users'))->where($db->qn('list_id') . '=' . $db->quote($listId));
		$db->setQuery($query);

		return $db->execute();
	}

	/**
	 * Load a user subscription from the db
	 *
	 * @param   string  $email   - the email of the user
	 * @param   string  $listId  - the list id
	 *
	 * @return bool|mixed
	 */
	public static function getSubscription($email, $listId)
	{
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query->select('*')
			->from('#__cmc_users')
			->where($db->qn('list_id') . '=' . $db->q($listId))
			->where($db->qn('email') . '=' . $db->q($email));
		$db->setQuery($query);

		$subscription = $db->loadObject();

		return $subscription ? $subscription : false;
	}

	/**
	 * Get the ids of any Joomla users we already have on our list
	 *
	 * @param   array  $emails  - emails to search for
	 *
	 * @return array|mixed
	 */
	public static function getJoomlaUsers($emails)
	{
		$users = array();

		if (count($emails))
		{
			$db = JFactory::getDbo();
			$query = $db->getQuery(true);

			$query->select('id, email')->from('#__users')->where(CompojoomQueryHelper::in('email', $emails, $db));

			$db->setQuery($query);

			return $db->loadObjectList('email');
		}

		return $users;
	}
}
