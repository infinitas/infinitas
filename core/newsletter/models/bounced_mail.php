<?php
	class BouncedMail extends NewsletterAppModel{
		public $name = 'BouncedMail';
		
		/**
		 * database configuration to use
		 *
		 * @var string
		 */
		public $useDbConfig = 'imap';

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

		public $server = array();

		public function  __construct($id = false, $table = null, $ds = null) {
			parent::__construct($id, $table, $ds);

			$this->server = array(
				'server' => Configure::read('Newsletter.smtp_host'),
				'username' => Configure::read('Newsletter.smtp_username'),
				'email' => Configure::read('Newsletter.smtp_username'),
				'password' => Configure::read('Newsletter.smtp_password'),
				'ssl' => Configure::read('Newsletter.ssl'),
				'port' => !empty(Configure::read('Newsletter.port')) ? Configure::read('Newsletter.port') : null,
				'type' => Configure::read('Newsletter.type')
			);
		}
	}