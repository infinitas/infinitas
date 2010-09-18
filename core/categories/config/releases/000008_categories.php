<?php
class R4c94edcbe44c421e917478d86318cd70 extends CakeRelease {

/**
 * Migration description
 *
 * @var string
 * @access public
 */
	public $description = 'Migration for Categories version 0.8';

/**
 * Plugin name
 *
 * @var string
 * @access public
 */
	public $plugin = 'Categories';

/**
 * Actions to be performed
 *
 * @var array $migration
 * @access public
 */
	public $migration = array(
		'up' => array(
			'create_table' => array(
				'categories' => array(
					'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
					'title' => array('type' => 'string', 'null' => false, 'default' => NULL),
					'slug' => array('type' => 'string', 'null' => false, 'default' => NULL),
					'description' => array('type' => 'text', 'null' => true, 'default' => NULL),
					'active' => array('type' => 'boolean', 'null' => false, 'default' => '0', 'key' => 'index'),
					'group_id' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 3, 'key' => 'index'),
					'item_count' => array('type' => 'integer', 'null' => false, 'default' => '0'),
					'parent_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
					'lft' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'index'),
					'rght' => array('type' => 'integer', 'null' => false, 'default' => NULL),
					'views' => array('type' => 'integer', 'null' => false, 'default' => NULL),
					'created' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
					'modified' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
						'tree_list_all' => array('column' => array('lft', 'id', 'title', 'rght'), 'unique' => 1),
						'cat_idx' => array('column' => array('active', 'group_id'), 'unique' => 0),
						'idx_access' => array('column' => 'group_id', 'unique' => 0),
					),
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'),
				),
			),
		),
		'down' => array(
			'drop_table' => array(
				'categories'
			),
		),
	);

/**
 * Fixtures for data
 *
 * @var array $fixtures
 * @access public
 */
	public $fixtures = array(
	'sample' => array(
		'Category' => array(
			array(
				'id' => 1,
				'title' => 'Infinitas news',
				'slug' => 'infinitas-news',
				'description' => 'Some special Infinitas news items.',
				'active' => 0,
				'group_id' => NULL,
				'item_count' => 0,
				'parent_id' => 0,
				'lft' => 1,
				'rght' => 2,
				'views' => 0,
				'created' => '2010-09-18 18:36:36',
				'modified' => '2010-09-18 18:36:36'
			),
		),
		),
	);
	
/**
 * Before migration callback
 *
 * @param string $direction, up or down direction of migration process
 * @return boolean Should process continue
 * @access public
 */
	public function before($direction) {
		return true;
	}

/**
 * After migration callback
 *
 * @param string $direction, up or down direction of migration process
 * @return boolean Should process continue
 * @access public
 */
	public function after($direction) {
		return true;
	}
}
?>