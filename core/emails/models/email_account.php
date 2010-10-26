<?php
	class EmailAccount extends EmailsAppModel{
		public $name = 'EmailAccount';

		public $useTable = 'accounts';

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

		public function  __construct($id = false, $table = null, $ds = null) {
			parent::__construct($id, $table, $ds);

			$this->validate = array(
				'name' => array(
					'notEmpty' => array(
						'rule' => 'notEmpty',
						'message' => __('Please enter the name of the account', true)
					),
					'isUnique' => array(
						'rule' => 'isUnique',
						'message' => __('There is already an account with that name', true)
					)
				),
				'server' => array(
					'notEmpty' => array(
						'rule' => 'notEmpty',
						'message' => __('Please enter the server address or ip', true)
					),
					'validServer' => array(
						'rule' => 'validServer',
						'message' => __('Please enter a valid server address or ip', true)
					)
				),
				'username' => array(
					'notEmpty' => array(
						'rule' => 'notEmpty',
						'message' => __('Please enter the username for this account', true)
					)
				),
				'password' => array(
					'notEmpty' => array(
						'rule' => 'notEmpty',
						'message' => __('Please enter the password for this account', true)
					)
				),
				'email' => array(
					'notEmpty' => array(
						'rule' => 'notEmpty',
						'message' => __('Please enter the email address of this account', true)
					)
				),
				'type' => array(
					'notEmpty' => array(
						'rule' => 'notEmpty',
						'message' => __('Please select the type of service', true)
					)
				),
				'port' => array(
					'notEmpty' => array(
						'rule' => 'notEmpty',
						'message' => __('Please enter the port number to connect to', true)
					),
					'validPort' => array(
						'rule' => '/[1-9][0-9]{2,5}/',
						'message' => __('Please enter a valid port number', true)
					)
				)
			);
		}

		/**
		 * check the mail server is valid
		 * 
		 * @param array $field the field being validated
		 * @return bool is it valid
		 */
		public function validServer($field){
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
		 * @return bool were we able to connect?
		 */
		public function beforeSave(){
			return is_int(ClassRegistry::init('Emails.MailSystem')->testConnection($this->data['EmailAccount']));
		}

		/**
		 * Get a list of email accounts that are associated to the user plus the
		 * main account for the system.
		 *
		 * @param <type> $user_id the user requesting
		 * @return <type> array of accounts
		 */
		public function getMyAccounts($user_id){
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
							'EmailAccount.user_id' => $user_id,
							'EmailAccount.system' => 1
						)
					)
				)
			);

			return $accounts;
		}

		public function getConnectionDetails($id){
			$account = $this->find(
				'first',
				array(
					'conditions' => array(
						'EmailAccount.id' => $id
					)
				)
			);

			if(!empty($account)){
				return $account['EmailAccount'];
			}

			return false;
		}
	}