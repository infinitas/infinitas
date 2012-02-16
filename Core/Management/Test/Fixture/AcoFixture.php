<?php
/* Aco Fixture generated on: 2010-03-11 21:03:42 : 1268336022 */
class AcoFixture extends CakeTestFixture {
	var $name = 'Aco';
	var $table = 'acos';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
		'parent_id' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 10),
		'model' => array('type' => 'string', 'null' => true, 'default' => NULL),
		'foreign_key' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 10),
		'alias' => array('type' => 'string', 'null' => true, 'default' => NULL, 'key' => 'index'),
		'lft' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 10),
		'rght' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 10),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'mptt_alias' => array('column' => array('alias', 'lft', 'rght'), 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

	var $records = array(
		array(
			'id' => 1,
			'parent_id' => NULL,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'Users',
			'lft' => 1,
			'rght' => 88
		),
		array(
			'id' => 2,
			'parent_id' => 1,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'login',
			'lft' => 2,
			'rght' => 3
		),
		array(
			'id' => 3,
			'parent_id' => 1,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'logout',
			'lft' => 4,
			'rght' => 5
		),
		array(
			'id' => 4,
			'parent_id' => 1,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'register',
			'lft' => 6,
			'rght' => 7
		),
		array(
			'id' => 5,
			'parent_id' => 1,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_login',
			'lft' => 8,
			'rght' => 9
		),
		array(
			'id' => 6,
			'parent_id' => 1,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_logout',
			'lft' => 10,
			'rght' => 11
		),
		array(
			'id' => 7,
			'parent_id' => 1,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_index',
			'lft' => 12,
			'rght' => 13
		),
		array(
			'id' => 8,
			'parent_id' => 1,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_logged_in',
			'lft' => 14,
			'rght' => 15
		),
		array(
			'id' => 9,
			'parent_id' => 1,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_add',
			'lft' => 16,
			'rght' => 17
		),
		array(
			'id' => 10,
			'parent_id' => 1,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_edit',
			'lft' => 18,
			'rght' => 19
		),
	);
}
?>