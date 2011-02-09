<?php
	class EmailsAppModel extends AppModel{
		public $tablePrefix = 'emails_';

		public $types = array(
			'smtp' => 'smtp',
			'php' => 'php',
			'pop3' => 'pop3',
			'imap' => 'imap'
		);
	}
