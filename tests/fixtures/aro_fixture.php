<?php
/* Aro Fixture generated on: 2010-08-17 14:08:42 : 1282055082 */
class AroFixture extends CakeTestFixture {
	var $name = 'Aro';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
		'parent_id' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 10),
		'model' => array('type' => 'string', 'null' => true, 'default' => NULL),
		'foreign_key' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 10),
		'alias' => array('type' => 'string', 'null' => true, 'default' => NULL),
		'lft' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 10),
		'rght' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 10),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

	var $records = array(
		array(
			'id' => 1,
			'parent_id' => 0,
			'model' => 'Group',
			'foreign_key' => 1,
			'alias' => '',
			'lft' => 1,
			'rght' => 2
		),
		array(
			'id' => 2,
			'parent_id' => 0,
			'model' => 'User',
			'foreign_key' => 1,
			'alias' => '',
			'lft' => 3,
			'rght' => 4
		),
		array(
			'id' => 3,
			'parent_id' => 0,
			'model' => 'User',
			'foreign_key' => 2,
			'alias' => '',
			'lft' => 5,
			'rght' => 6
		),
		array(
			'id' => 4,
			'parent_id' => 0,
			'model' => 'User',
			'foreign_key' => 3,
			'alias' => '',
			'lft' => 7,
			'rght' => 8
		),
		array(
			'id' => 5,
			'parent_id' => 0,
			'model' => 'User',
			'foreign_key' => 4,
			'alias' => '',
			'lft' => 9,
			'rght' => 10
		),
		array(
			'id' => 6,
			'parent_id' => 0,
			'model' => 'User',
			'foreign_key' => 5,
			'alias' => '',
			'lft' => 11,
			'rght' => 12
		),
		array(
			'id' => 7,
			'parent_id' => 0,
			'model' => 'User',
			'foreign_key' => 6,
			'alias' => '',
			'lft' => 13,
			'rght' => 14
		),
		array(
			'id' => 8,
			'parent_id' => 0,
			'model' => 'User',
			'foreign_key' => 7,
			'alias' => '',
			'lft' => 15,
			'rght' => 16
		),
		array(
			'id' => 9,
			'parent_id' => 0,
			'model' => 'User',
			'foreign_key' => 8,
			'alias' => '',
			'lft' => 17,
			'rght' => 18
		),
		array(
			'id' => 10,
			'parent_id' => 0,
			'model' => 'User',
			'foreign_key' => 9,
			'alias' => '',
			'lft' => 19,
			'rght' => 20
		),
		array(
			'id' => 11,
			'parent_id' => 0,
			'model' => 'User',
			'foreign_key' => 10,
			'alias' => '',
			'lft' => 21,
			'rght' => 22
		),
		array(
			'id' => 12,
			'parent_id' => 0,
			'model' => 'User',
			'foreign_key' => 11,
			'alias' => '',
			'lft' => 23,
			'rght' => 24
		),
		array(
			'id' => 13,
			'parent_id' => 0,
			'model' => 'User',
			'foreign_key' => 12,
			'alias' => '',
			'lft' => 25,
			'rght' => 26
		),
		array(
			'id' => 14,
			'parent_id' => 0,
			'model' => 'User',
			'foreign_key' => 13,
			'alias' => '',
			'lft' => 27,
			'rght' => 28
		),
		array(
			'id' => 15,
			'parent_id' => 0,
			'model' => 'User',
			'foreign_key' => 14,
			'alias' => '',
			'lft' => 29,
			'rght' => 30
		),
		array(
			'id' => 16,
			'parent_id' => 0,
			'model' => 'User',
			'foreign_key' => 15,
			'alias' => '',
			'lft' => 31,
			'rght' => 32
		),
		array(
			'id' => 17,
			'parent_id' => 0,
			'model' => 'User',
			'foreign_key' => 16,
			'alias' => '',
			'lft' => 33,
			'rght' => 34
		),
		array(
			'id' => 18,
			'parent_id' => 0,
			'model' => 'User',
			'foreign_key' => 18,
			'alias' => '',
			'lft' => 35,
			'rght' => 36
		),
	);
}
?>