<?php
/* CoreGroup Fixture generated on: 2010-03-13 11:03:51 : 1268471871 */
class GroupFixture extends CakeTestFixture {
	var $name = 'Group';

	var $table = 'core_groups';

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
			'created' => '2010-02-01 00:00:00',
			'modified' => '2010-02-01 00:00:00',
			'parent_id' => 0,
			'lft' => 1,
			'rght' => 2
		),
		array(
			'id' => 2,
			'name' => 'Users',
			'description' => '',
			'created' => '2010-02-01 00:00:00',
			'modified' => '2010-02-01 00:00:00',
			'parent_id' => 0,
			'lft' => 3,
			'rght' => 4
		),
	);
}
?>