<?php
/* CoreGroup Fixture generated on: 2010-08-17 14:08:27 : 1282055127 */
class CoreGroupFixture extends CakeTestFixture {
	var $name = 'CoreGroup';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
		'name' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 50),
		'description' => array('type' => 'text', 'null' => false, 'default' => NULL),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'parent_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'index'),
		'lft' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'rght' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'parent_id' => array('column' => 'parent_id', 'unique' => 0)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

	var $records = array(
		array(
			'id' => 1,
			'name' => 'Administrators',
			'description' => 'Site Administrators',
			'created' => '2010-02-04 16:53:33',
			'modified' => '2010-02-04 16:53:33',
			'parent_id' => 0,
			'lft' => 1,
			'rght' => 2
		),
		array(
			'id' => 2,
			'name' => 'Users',
			'description' => '',
			'created' => '2010-02-17 23:14:32',
			'modified' => '2010-02-17 23:14:32',
			'parent_id' => 0,
			'lft' => 3,
			'rght' => 4
		),
	);
}
?>