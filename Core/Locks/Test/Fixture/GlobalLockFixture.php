<?php
	class GlobalLockFixture extends CakeTestFixture {
		public $name = 'GlobalLock';

			public $fields = array(
				'id' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'key' => 'primary', 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
				'class' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 128, 'key' => 'index', 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
				'foreign_key' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
				'user_id' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
				'created' => array('type' => 'datetime', 'null' => false, 'default' => null),
				'indexes' => array(
					'PRIMARY' => array('column' => 'id', 'unique' => 1),
					'lock' => array('column' => array('class', 'foreign_key'), 'unique' => 1)
				),
				'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
			);

			public $records = array(
				array(
					'id' => 'lock-1',
					'class' => 'Blog.Post',
					'foreign_key' => 'post-1',
					'user_id' => '1',
					'created' => '2012-06-21 16:44:36'
				),
			);
		}