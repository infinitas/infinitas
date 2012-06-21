<?php
	class MailSystem extends EmailsAppModel {
		/**
		 * database configuration to use
		 *
		 * @var string
		 */
		public $useDbConfig = 'emails';

		/**
		 * Behaviors to attach
		 *
		 * @var mixed
		 */
		public $actsAs = false;

		/**
		 * database table to use
		 *
		 * @var string
		 */
		public $useTable = false;

		/**
		 * The details of the server to connect to
		 * @var array
		 */
		public $server = array();

		/**
		 * Test a connection
		 *
		 * Validation method before saving an email account.
		 */
		public function testConnection($details) {
			$this->server = $details;
			return $this->find('count');
		}

		public function checkNewMail($account) {
			$mails = $this->find(
				'all',
				array(
					'conditions' => Set::flatten(array($this->alias => $account))
				)
			);

			// @todo save to the db here

			// @todo delete messages from server here

			return $mails;
		}
	}
