<?php
	/* CoreModulePosition Fixture generated on: 2010-03-13 11:03:31 : 1268472511 */
	class ModulePositionFixture extends CakeTestFixture {
		var $name = 'ModulePosition';

		var $table = 'core_module_positions';

		var $fields = array(
			'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
			'name' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 50),
			'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
			'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
			'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
			'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
		);

		var $records = array(
			array(
				'id' => 1,
				'name' => 'top',
				'created' => '2010-01-18 21:45:23',
				'modified' => '2010-01-18 21:45:23'
			),
			array(
				'id' => 2,
				'name' => 'bottom',
				'created' => '2010-01-18 21:45:23',
				'modified' => '2010-01-18 21:45:23'
			),
			array(
				'id' => 3,
				'name' => 'left',
				'created' => '2010-01-18 21:45:23',
				'modified' => '2010-01-18 21:45:23'
			),
			array(
				'id' => 4,
				'name' => 'right',
				'created' => '2010-01-18 21:45:23',
				'modified' => '2010-01-18 21:45:23'
			),
			array(
				'id' => 5,
				'name' => 'custom1',
				'created' => '2010-01-18 21:45:23',
				'modified' => '2010-01-18 21:45:23'
			),
			array(
				'id' => 6,
				'name' => 'custom2',
				'created' => '2010-01-18 21:45:23',
				'modified' => '2010-01-18 21:45:23'
			),
			array(
				'id' => 7,
				'name' => 'custom3',
				'created' => '2010-01-18 21:45:23',
				'modified' => '2010-01-18 21:45:23'
			),
			array(
				'id' => 8,
				'name' => 'custom4',
				'created' => '2010-01-18 21:45:23',
				'modified' => '2010-01-18 21:45:23'
			),
			array(
				'id' => 9,
				'name' => 'bread_crumbs',
				'created' => '2010-01-18 21:45:23',
				'modified' => '2010-01-18 21:45:23'
			),
			array(
				'id' => 10,
				'name' => 'debug',
				'created' => '2010-01-18 21:45:23',
				'modified' => '2010-01-18 21:45:23'
			),
			array(
				'id' => 11,
				'name' => 'hidden',
				'created' => '2010-01-18 21:45:23',
				'modified' => '2010-01-18 21:45:23'
			),
		);
	}