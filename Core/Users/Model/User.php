<?php
/**
 * User
 *
 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
 * @link http://www.infinitas-cms.org
 * @package Infinitas.Users.Model
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @since 0.7alpha
 *
 * @author Carl Sutton <dogmatic69@infinitas-cms.org>
 */
App::uses('UsersAppModel', 'Users.Model');

/**
 * @package Infinitas.Users.Model
 *
 * @property Group $Group
 */
class User extends UsersAppModel {

/**
 * table name to use
 *
 * @var string
 */
	public $useTable = 'users';

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'username';

/**
 * Custom find methods
 *
 * @var array
 */
	public $findMethods = array(
		'loggedIn' => true,
		'lastLogin' => true,
		'profile' => true
	);

/**
 * Behaviors to attach
 *
 * @var array
 */
	public $actsAs = array(
		'Libs.Ticketable'
	);

/**
 * BelongsTo relations
 *
 * @var array
 */
	public $belongsTo = array(
		'Group' => array(
			'className' => 'Users.Group'
		)
	);

/**
 * Constructor
 *
 * @param type $id
 * @param type $table
 * @param type $ds
 *
 * @return void
 */
	public function __construct($id = false, $table = null, $ds = null) {
		parent::__construct($id, $table, $ds);

		$message = Configure::read('Website.password_validation');

		$this->validate = array(
			'username' => array(
				'notEmpty' => array(
					'rule' => 'notEmpty',
					'message' => __d('users', 'Please enter your username')
				),
				'isUnique' => array(
					'rule' => 'isUnique',
					'message' => __d('users', 'That username is taken, sorry')
				)
			),
			'email' => array(
				'notEmpty' => array(
					'rule' => 'notEmpty',
					'message' => __d('users', 'Please enter your email address')
				),
				'email' => array(
					'rule' => array('email', true),
					'message' => __d('users', 'That email address does not seem to be valid')
				),
				'isUnique' => array(
					'rule' => 'isUnique',
					'message' => __d('users', 'It seems you are already registered, please use the forgot password option')
				)
			),
			'confirm_email' => array(
				'validateCompareFields' => array(
					'rule' => array('validateCompareFields', array('email', 'confirm_email')),
					'message' => __d('users', 'Your email address does not match')
				)
			),
			'password' => array(
				'notEmpty' => array(
					'rule' => 'notEmpty',
					'message' => __d('users', 'Please enter a password')
				)
			),
			'confirm_password' => array(
				'notEmpty' => array(
					'rule' => 'notEmpty',
					'message' => __d('users', 'Please re-enter your password')
				),
				'validatePassword' => array(
					'rule' => 'validatePassword',
					'message' => (!empty($message) ? $message : __d('users', 'Please enter a stronger password'))
				),
				'validateCompareFields' => array(
					'rule' => array('validateCompareFields', array('password', 'confirm_password')),
					'message' => __d('users', 'The passwords entered do not match')
				)
			),
			'time_zone' => array(
				'notEmpty' => array(
					'rule' => 'notEmpty',
					'message' => __d('users', 'Please select a time zone')
				)
			),
		);
	}

/**
 * auto hash passwords when other plugins use the model with a different alias
 *
 * Auth does not auto has the pw field when the alias is not User, so we
 * have to do it here so that it seems auto for other plugins.
 *
 * @param array $options see parent::beforeValidate
 *
 * @return parent::beforeValidate
 */
	public function beforeValidate($options = array()) {
		if (!empty($this->data[$this->alias]['password'])) {
			$this->data[$this->alias]['password'] = Security::hash($this->data[$this->alias]['password'], null, true);
		}

		return parent::beforeValidate($options);
	}

/**
 * validate password.
 *
 * This method uses the regex saved in the config to check that the
 * password is secure. The user can update this and the message in the
 * backend by changing the config value "Website.password_regex".
 *
 * @param array $field the array $field => $value from the form
 *
 * @return boolean
 */
	public function validatePassword($field = null) {
		return (bool)preg_match('/' . Configure::read('Website.password_regex') . '/', $field['confirm_password']);
	}

/**
 * Get logged in users
 *
 * Get a list of users that have been active on the site recently.
 *
 * @param string $state
 * @param array $query
 * @param array $results
 *
 * @return array
 */
	protected function _findLoggedIn($state, array $query, array $results = array()) {
		if ($state == 'before') {
			$query = array_merge(array(
				'time' => strtotime('-30 min')
			), $query);

			$query['conditions'] = array_merge(array(
				$this->alias . '.last_login >= ' => date('Y-m-d H:i:s', $query['time'])
			), (array)$query['conditions']);

			$query['order'] = array(
				$this->alias . '.last_login' => 'desc'
			);

			return $query;
		}

		return $results;
	}

/**
 * Get the details of the users last login
 *
 * @param string $state
 * @param array $query
 * @param array $results
 *
 * @return array
 */
	protected function _findLastLogin($state, array $query, array $results = array()) {
		if ($state == 'before') {
			if (empty($query[0])) {
				$query[0] = AuthComponent::user('id');
			}
			$query['fields'] = array_merge((array)$query['fields'], array(
				$this->alias . '.ip_address',
				$this->alias . '.last_login',
				$this->alias . '.country',
				$this->alias . '.city'
			));

			$query['conditions'] = array_merge((array)$query['conditions'], array(
				$this->alias . '.' . $this->primaryKey => $query[0]
			));

			$query['limit'] = 1;
			return $query;
		}

		if (empty($results[0][$this->alias])) {
			return array();
		}

		return $results[0];
	}

/**
 * Get the details for a users profile page
 *
 * @param string $state
 * @param array $query
 * @param array $results
 *
 * @return array
 */
	protected function _findProfile($state, array $query, array $results = array()) {
		if ($state == 'before') {
			if (empty($query[0])) {
				$query[0] = AuthComponent::user('id');
			}

			$query['conditions'] = array_merge((array)$query['conditions'], array(
				$this->alias . '.' . $this->primaryKey => $query[0],
				$this->alias . '.active' => true
			));

			$query['limit'] = 1;
			return $query;
		}

		if (empty($results[0][$this->alias])) {
			return array();
		}

		return $results[0];
	}

/**
 * Save a users profile details
 *
 * Only the user can save with this method. eg: auth id should be the user that is edited.
 *
 * @param array $data
 *
 * @return array
 *
 * @throws InvalidArgumentException
 */
	public function saveProfile(array $data) {
		$userId = AuthComponent::user('id');
		if ($data[$this->alias][$this->primaryKey] != $userId) {
			throw new InvalidArgumentException(__d($this->alias, 'Invalid user specified'));
		}

		if (array_key_exists('prefered_name', $data[$this->alias]) && empty($data[$this->alias]['prefered_name'])) {
			$data[$this->alias]['prefered_name'] = $data[$this->alias]['username'];
		}

		if (empty($data[$this->alias]['password'])) {
			unset($data[$this->alias]['password'], $data[$this->alias]['confirm_password']);
		}

		return $this->save($data);
	}

/**
 * Update a users password
 *
 * @param array $data
 *
 * @return array
 */
	public function updatePassword(array $data) {
		if (empty($data[$this->alias])) {
			$data = array($this->alias => $data);
		}
		if (empty($data[$this->alias][$this->primaryKey])) {
			$data[$this->alias][$this->primaryKey] = $this->id;
		}
		if (empty($data[$this->alias][$this->primaryKey])) {
			return false;
		}

		$this->id = $data[$this->alias][$this->primaryKey];
		return (bool)$this->saveField('password', Security::hash($data[$this->alias]['new_password'], null, true));
	}

/**
 * Method for saving new registrations.
 *
 * @param array $data the data to save
 *
 * @return array
 */
	public function saveRegistration(array $data) {
		if (empty($data[$this->alias])) {
			$data = array($this->alias => $data);
		}

		$data[$this->alias]['active'] = !(int)Configure::read('Website.email_validation');
		$data[$this->alias]['group_id'] = 2;

		$this->create();
		return $this->saveAll($data);
	}

/**
 * Method for saving the activation status
 *
 * This gets the ticket and checks everything is valid before setting the user to active.
 *
 * @param array $data the data to save
 *
 * @return array
 */
	public function saveActivation($hash) {
		$user = $this->find('first', array(
			'conditions' => array(
				$this->alias . '.email' => $this->getTicket($hash)
			)
		));
		if (empty($user)) {
			return false;
		}

		$user[$this->alias]['active'] = 1;

		return $this->save($user[$this->alias]);
	}

	public function getSiteRelatedList() {
		return $this->find('list', array(
			'conditions' => array(
				'User.group_id' => 1
			)
		));
	}

/**
 * Get a list of site admins with emails
 *
 * @param string $fields
 *
 * @return array
 */
	public function getAdmins($fields = array()) {
		if (!$fields) {
			$fields = array(
				$this->alias . '.username',
				$this->alias . '.email'
			);
		}

		return $this->find('list', array(
			'fields' => $fields,
			'conditions' => array(
				$this->alias . '.group_id' => 1
			)
		));
	}

/**
 * get a count of registrations per month for the last two years
 *
 * Format: list of (year_month => count)
 *
 * @return array
 */
	public function getRegistrationChartData() {
		$this->virtualFields['join_date'] = 'CONCAT_WS("/", YEAR(`' . $this->alias . '`.`created`), LPAD(MONTH(`' . $this->alias . '`.`created`), 2, 0))';
		$this->virtualFields['count_joins'] = 'COUNT(`' . $this->alias . '`.`id`)';

		$i = - 24;
		$dates = array();
		while($i <= 0) {
			$dates[date('Y/m', mktime(0, 0, 0, date('m') + $i, 1, date('Y')))] = null;
			$i++;
		}

		$data = $this->find('list', array(
			'fields' => array(
				'join_date',
				'count_joins',
			),
			'conditions' => array(
				$this->alias . '.created >= ' => date('Y-m-d H:i:s', mktime(0, 0, 0, date('m') - 24, date('d'), date('Y')))
			),
			'group' => array(
				'join_date'
			)
		));

		return array_merge($dates, $data);
	}

	public function parentNode() {
		if (!$this->id && empty($this->data)) {
			return null;
		}

		$data = $this->data;
		if (empty($this->data)) {
			$data = $this->read();
		}

		if (!isset($data[$this->alias]['group_id']) || !$data[$this->alias]['group_id']) {
			return null;
		}
		return array('Group' => array('id' => $data[$this->alias]['group_id']));
	}

/**
 * After save callback
 *
 * Update the aro for the user.
 *
 * @return void
 */
	public function afterSave($created) {
		if (!$created && is_a('Model', $this->Aro)) {
			$parent = $this->node($this->parentNode());
			$node = $this->node();
			$aro = $node[0];
			$aro['Aro']['parent_id'] = $parent[0]['Aro']['id'];
			$this->Aro->save($aro);
		}
	}
}