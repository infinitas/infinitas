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
				
			);
		}

		public function beforeValidate(){
			return true;
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