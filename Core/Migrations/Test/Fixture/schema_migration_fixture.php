<?php
	class SchemaMigrationFixture extends CakeTestFixture {		
		public $table = 'schema_migrations';
		
		public $fields = array(
			'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
			'version' => array('type' => 'integer', 'null' => false, 'default' => NULL),
			'type' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 50, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
			'created' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
			'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
			'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'MyISAM')
		);
	
		public $records = array(
			array(
				'id' => 1,
				'version' => 1,
				'type' => 'migrations',
				'created' => '2011-09-06 16:53:39'
			),
			array(
				'id' => 2,
				'version' => 1,
				'type' => 'app',
				'created' => '2011-09-06 16:54:08'
			),
			array(
				'id' => 3,
				'version' => 1,
				'type' => 'Installer',
				'created' => '2011-09-06 16:54:09'
			),
			array(
				'id' => 4,
				'version' => 2,
				'type' => 'Installer',
				'created' => '2011-09-06 16:54:09'
			),
		);
	}