<?php
	/* GlobalCategory Fixture generated on: 2010-08-16 23:08:56 : 1281999416 */
	class GlobalCategoryFixture extends CakeTestFixture {
		var $name = 'GlobalCategory';

		var $fields = array(
			'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
			'title' => array('type' => 'string', 'null' => false, 'default' => NULL),
			'slug' => array('type' => 'string', 'null' => false, 'default' => NULL),
			'description' => array('type' => 'text', 'null' => true, 'default' => NULL),
			'active' => array('type' => 'boolean', 'null' => false, 'default' => '0', 'key' => 'index'),
			'locked' => array('type' => 'boolean', 'null' => false, 'default' => '0', 'key' => 'index'),
			'locked_since' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
			'locked_by' => array('type' => 'integer', 'null' => true, 'default' => NULL),
			'group_id' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 3, 'key' => 'index'),
			'item_count' => array('type' => 'integer', 'null' => false, 'default' => '0'),
			'parent_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
			'lft' => array('type' => 'integer', 'null' => false, 'default' => NULL),
			'rght' => array('type' => 'integer', 'null' => false, 'default' => NULL),
			'views' => array('type' => 'integer', 'null' => false, 'default' => NULL),
			'created' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
			'modified' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
			'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'cat_idx' => array('column' => array('active', 'group_id'), 'unique' => 0), 'idx_access' => array('column' => 'group_id', 'unique' => 0), 'idx_checkout' => array('column' => 'locked', 'unique' => 0), 'find_list' => array('column' => array('id', 'title', 'lft'), 'unique' => 0)),
			'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
		);

		var $records = array(
			array(
				'id' => 1,
				'title' => 'category 1',
				'slug' => 'category-1',
				'description' => 'the first cateogry',
				'active' => 1,
				'group_id' => 0,
				'lft' => 1,
				'rght' => 2,
				'views' => 24,
				'created' => '2010-08-16 23:56:50',
				'modified' => '2010-08-16 23:56:50'
			),
			array(
				'id' => 2,
				'title' => 'category 2',
				'slug' => 'category-2',
				'description' => 'the second cateogry',
				'active' => 1,
				'group_id' => 0,
				'lft' => 3,
				'rght' => 6,
				'views' => 1,
				'created' => '2010-08-16 23:56:50',
				'modified' => '2010-08-16 23:56:50'
			),
			array(
				'id' => 3,
				'title' => 'category 2a',
				'slug' => 'category-2a',
				'description' => 'the fist sub category in the second cateogry',
				'active' => 1,
				'group_id' => 0,
				'lft' => 4,
				'rght' => 5,
				'views' => 3,
				'created' => '2010-08-16 23:56:50',
				'modified' => '2010-08-16 23:56:50'
			),
			array(
				'id' => 4,
				'title' => 'category 4',
				'slug' => 'category-4',
				'description' => 'not active number 4',
				'active' => 0,
				'group_id' => 0,
				'lft' => 7,
				'rght' => 8,
				'views' => 20,
				'created' => '2010-08-16 23:56:50',
				'modified' => '2010-08-16 23:56:50'
			),
		);
	}