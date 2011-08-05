<?php
	class ModulePositionFixture extends CakeTestFixture {
		public $name = 'ModulePosition';
		
		public $table = 'core_module_positions';
		
		public $fields = 	array(
			'id' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 36, 'key' => 'primary', 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
			'name' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 50, 'key' => 'index', 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
			'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
			'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
			'module_count' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 5),
			'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'name' => array('column' => 'name', 'unique' => 0)),
			'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
		);
	
		public $records = 	array(
			array(
				'id' => 'module-position-top',
				'name' => 'top',
				'created' => '2010-01-18 21:45:23',
				'modified' => '2010-01-18 21:45:23',
				'module_count' => 'null'
			),
			array(
				'id' => 'module-position-bottom',
				'name' => 'bottom',
				'created' => '2010-01-18 21:45:23',
				'modified' => '2010-01-18 21:45:23',
				'module_count' => 'null'
			),
			array(
				'id' => 'module-position-left',
				'name' => 'left',
				'created' => '2010-01-18 21:45:23',
				'modified' => '2010-01-18 21:45:23',
				'module_count' => 'null'
			),
			array(
				'id' => 'module-position-right',
				'name' => 'right',
				'created' => '2010-01-18 21:45:23',
				'modified' => '2010-01-18 21:45:23',
				'module_count' => 'null'
			),
			array(
				'id' => 'module-position-custom1',
				'name' => 'custom1',
				'created' => '2010-01-18 21:45:23',
				'modified' => '2010-01-18 21:45:23',
				'module_count' => 'null'
			),
			array(
				'id' => 'module-position-custom2',
				'name' => 'custom2',
				'created' => '2010-01-18 21:45:23',
				'modified' => '2010-01-18 21:45:23',
				'module_count' => 'null'
			),
			array(
				'id' => 'module-position-custom3',
				'name' => 'custom3',
				'created' => '2010-01-18 21:45:23',
				'modified' => '2010-01-18 21:45:23',
				'module_count' => 'null'
			),
			array(
				'id' => 'module-position-custom4',
				'name' => 'custom4',
				'created' => '2010-01-18 21:45:23',
				'modified' => '2010-01-18 21:45:23',
				'module_count' => 'null'
			),
			array(
				'id' => 'module-position-bread-crumbs',
				'name' => 'bread_crumbs',
				'created' => '2010-01-18 21:45:23',
				'modified' => '2010-01-18 21:45:23',
				'module_count' => 'null'
			),
			array(
				'id' => 'module-position-debug',
				'name' => 'debug',
				'created' => '2010-01-18 21:45:23',
				'modified' => '2010-01-18 21:45:23',
				'module_count' => 'null'
			),
			array(
				'id' => 'module-position-hidden',
				'name' => 'hidden',
				'created' => '2010-01-18 21:45:23',
				'modified' => '2010-01-18 21:45:23',
				'module_count' => 'null'
			),
		);
	}