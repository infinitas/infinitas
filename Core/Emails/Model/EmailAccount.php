<?php
class EmailAccount extends EmailsAppModel {
/**
 * custom find methods
 *
 * @var array
 */
	public $findMethods = array(
		'systemAccount' => true
	);

/**
 * belongsTo relations
 *
 * @var array
 */
	public $belongsTo = array(
		'User' => array(
			'className' => 'Users.User',
			'fields' => array(
				'User.id',
				'User.usermane',
				'User.email'
			)
		)
	);

/**
 * overload constructor for translated validation
 *
 * @param type $id
 * @param type $table
 * @param type $ds
 *
 * @return void
 */
	public function  __construct($id = false, $table = null, $ds = null) {
		parent::__construct($id, $table, $ds);

		$this->validate = array(
			'name' => array(
				'notEmpty' => array(
					'rule' => 'notEmpty',
					'message' => __d('emails', 'Please enter the name of the account')
				),
				'isUnique' => array(
					'rule' => 'isUnique',
					'message' => __d('emails', 'There is already an account with that name')
				)
			),
			'server' => array(
				'notEmpty' => array(
					'rule' => 'notEmpty',
					'message' => __d('emails', 'Please enter the server address or ip')
				),
				'validServer' => array(
					'rule' => 'validServer',
					'message' => __d('emails', 'Please enter a valid server address or ip')
				)
			),
			'username' => array(
				'notEmpty' => array(
					'rule' => 'notEmpty',
					'message' => __d('emails', 'Please enter the username for this account')
				)
			),
			'password' => array(
				'notEmpty' => array(
					'rule' => 'notEmpty',
					'message' => __d('emails', 'Please enter the password for this account')
				)
			),
			'email' => array(
				'notEmpty' => array(
					'rule' => 'notEmpty',
					'message' => __d('emails', 'Please enter the email address of this account')
				)
			),
			'type' => array(
				'notEmpty' => array(
					'rule' => 'notEmpty',
					'message' => __d('emails', 'Please select the type of service')
				)
			),
			'port' => array(
				'notEmpty' => array(
					'rule' => 'notEmpty',
					'message' => __d('emails', 'Please enter the port number to connect to')
				),
				'validPort' => array(
					'rule' => '/[1-9][0-9]{2,5}/',
					'message' => __d('emails', 'Please enter a valid port number')
				)
			)
		);
	}

/**
 * check the mail server is valid
 *
 * @param array $field the field being validated
 * @return boolean
 */
	public function validServer($field) {
		return
			// server like mail.something.ext
			preg_match('/^[a-z0-9]*\.[a-z0-9]*\.([a-z]{2,3}|[a-z]{2,3}\.[a-z]{2,3})*$/', current($field)) ||
			// ip address
			preg_match('/^(([0-2]*[0-9]+[0-9]+)\.([0-2]*[0-9]+[0-9]+)\.([0-2]*[0-9]+[0-9]+)\.([0-2]*[0-9]+[0-9]+))$/', current($field));
	}

/**
 * this is used for making sure that the mail server details are correct
 * before saving them to the database.
 *
 * @return boolean
 */
	public function beforeSave() {
		return true; //is_int(ClassRegistry::init('Emails.MailSystem')->testConnection($this->data['EmailAccount']));
	}

/**
 * Get a list of email accounts that are associated to the user plus the
 * main account for the system.
 *
 * @param <type> $user_id the user requesting
 * @return <type> array of accounts
 */
	public function getMyAccounts($userId) {
		$accounts = $this->find(
			'all',
			array(
				'fields' => array(
					'EmailAccount.id',
					'EmailAccount.name',
					'EmailAccount.slug',
					'EmailAccount.email',
				),
				'conditions' => array(
					'or' => array(
						'EmailAccount.user_id' => $userId,
						'EmailAccount.system' => 1
					)
				)
			)
		);

		return $accounts;
	}

/**
 * get a list of mails that are set to download with crons
 */
	public function getCronAccounts() {
		return $this->find(
			'all',
			array(
				'fields' => array(
					$this->alias . '.id',
					$this->alias . '.host',
					$this->alias . '.username',
					$this->alias . '.password',
					$this->alias . '.email',
					$this->alias . '.ssl',
					$this->alias . '.port',
					$this->alias . '.type',
					$this->alias . '.readonly'
				),
				'conditions' => array(
					$this->alias . '.cron' => 1
				)
			)
		);
	}

	public function getConnectionDetails($id) {
		$account = $this->find(
			'first',
			array(
				'conditions' => array(
					'EmailAccount.id' => $id
				)
			)
		);

		if(!empty($account)) {
			return $account['EmailAccount'];
		}

		return false;
	}

/**
 * find email config for sending mails
 *
 * This method will look for configs that are used for sending out emails with the
 * InfinitasEmail class. The email can be selected in two ways.
 *
 * For smaller sites where one email is configured it can be automatically detected.
 * This email should be set for outgoing mail and marked as system.
 *
 * On larger sites where more than one system mail is configured (or to force a
 * specific email) make sure to configure the `Emails.default_config` with the slug
 * of the email to use.
 *
 * If no configuration is found a last ditch attempt will be made using the EmailConfig
 * class. This will return the details if EmailConfig::$default is not empty. By
 * default this will use the php mailer.
 *
 * @param string $state
 * @param array $query
 * @param array $results
 *
 * @return array
 *
 * @throws CakeException
 */
	protected function _findSystemAccount($state, array $query, array $results = array()) {
		if($state == 'before') {
			$query['fields'] = array_merge((array)$query['fields'], array(
				$this->alias . '.name',
				$this->alias . '.email',
				$this->alias . '.server',
				$this->alias . '.ssl',
				$this->alias . '.port',
				$this->alias . '.type',
				$this->alias . '.username',
				$this->alias . '.password'
			));

			if(empty($query['config'])) {
				$config = Configure::read('Emails.default_config');
				$query['config'] = $config;
			}

			if(!empty($query['config'])) {
				$query['conditions'] = array_merge((array)$query['conditions'], array(
					$this->alias . '.slug' => $query['config']
				));
			}

			$query['conditions'] = array_merge((array)$query['conditions'], array(
				$this->alias . '.system' => 1,
				$this->alias . '.outgoing' => 1,
			));

			$query['limit'] = 1;
			return $query;
		}

		if(empty($results[0][$this->alias])) {
			if(class_exists('EmailConfig')) {
				$Email = new EmailConfig();
				if(!empty($Email->default)) {
					return $Email->default;
				}
			}

			throw new CakeException('System emails not configured');
		}

		return array(
			'name' => $results[0][$this->alias]['name'],
			'email' => $results[0][$this->alias]['email'],
			'host' => ($results[0][$this->alias]['ssl'] ? 'ssl://' : null) . $results[0][$this->alias]['server'],
			'port' => $results[0][$this->alias]['port'],
			'username' => $results[0][$this->alias]['username'],
			'password' => $results[0][$this->alias]['password'],
			'transport' => ucfirst($results[0][$this->alias]['type'])
		);
	}

}