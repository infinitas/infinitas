<?php
/**
 * @brief fixture file for GlobalCategory tests.
 *
 * @package Contents.Fixture
 * @since 0.9b1
 */
class GlobalCategoryFixture extends CakeTestFixture {
	public $table = 'GlobalCategory';

	public $fields = array(
		'id' => array('type' => 'string', 'null' => false, 'default' => null, 'key' => 'primary', 'length' => 36, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'active' => array('type' => 'boolean', 'null' => false, 'default' => '0', 'key' => 'index'),
		'group_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 3, 'key' => 'index'),
		'item_count' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'parent_id' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 36, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'lft' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'index'),
		'rght' => array('type' => 'integer', 'null' => false, 'default' => null),
		'views' => array('type' => 'integer', 'null' => false, 'default' => null),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'hide' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'path_depth' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 5),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'cat_idx' => array('column' => array('active', 'group_id'), 'unique' => 0),
			'idx_access' => array('column' => 'group_id', 'unique' => 0),
			'find_list' => array('column' => array('id', 'lft'), 'unique' => 0),
			'tree_list_all' => array('column' => array('lft', 'id', 'rght'), 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

	public $records = array(
		array(
			'id' => 'category-1',
			'active' => 1,
			'group_id' => 0,
			'lft' => 1,
			'rght' => 2,
			'views' => 24,
			'created' => '2010-08-16 23:56:50',
			'modified' => '2010-08-16 23:56:50',
			'hide' => 0,
			'path_depth' => 0
		),
		array(
			'id' => 'category-2',
			'active' => 1,
			'group_id' => 0,
			'lft' => 3,
			'rght' => 6,
			'views' => 1,
			'created' => '2010-08-16 23:56:50',
			'modified' => '2010-08-16 23:56:50',
			'hide' => 0,
			'path_depth' => 0
		),
		array(
			'id' => 'category-2a',
			'active' => 1,
			'group_id' => 0,
			'lft' => 4,
			'rght' => 5,
			'views' => 3,
			'created' => '2010-08-16 23:56:50',
			'modified' => '2010-08-16 23:56:50',
			'hide' => 0,
			'path_depth' => 1
		),
		array(
			'id' => 'category-3',
			'active' => 0,
			'group_id' => 0,
			'lft' => 7,
			'rght' => 8,
			'views' => 20,
			'created' => '2010-08-16 23:56:50',
			'modified' => '2010-08-16 23:56:50',
			'hide' => 1,
			'path_depth' => 0
		),
	);
}