<?php
/**
 * fixture file for Group tests.
 *
 * @package Users.Fixture
 * @since 0.9b1
 */
class GroupFixture extends CakeTestFixture {
	public $name = 'Group';
	
	public $table = 'core_groups';

	public $fields = array(
		'id' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'key' => 'primary', 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'name' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 50, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'description' => array('type' => 'text', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'parent_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'key' => 'index'),
		'lft' => array('type' => 'integer', 'null' => false, 'default' => null),
		'rght' => array('type' => 'integer', 'null' => false, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'parent_id' => array('column' => 'parent_id', 'unique' => 0)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

	public $records = array(
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