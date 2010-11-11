<?php
	class MailSystem extends EmailsAppModel{
		public $name = 'MailSystem';

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
		public function testConnection($details){
			$this->server = $details;
			return $this->find('count');
		}
	}
