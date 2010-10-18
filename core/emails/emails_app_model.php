<?php
	class EmailsAppModel extends AppModel{
		public $tablePrefix = 'email_';

		public $types = array(
			'smtp' => 'smtp',
			'php' => 'php',
			'pop3' => 'pop3',
			'imap' => 'imap'
		);
	}
