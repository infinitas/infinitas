<?php
class R4c94edcd902444fc83c078d86318cd70 extends CakeRelease {

/**
 * Migration description
 *
 * @var string
 * @access public
 */
	public $description = 'Migration for Modules version 0.8';

/**
 * Plugin name
 *
 * @var string
 * @access public
 */
	public $plugin = 'Modules';

/**
 * Actions to be performed
 *
 * @var array $migration
 * @access public
 */
	public $migration = array(
		'up' => array(
			'create_table' => array(
				'module_positions' => array(
					'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
					'name' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 50, 'key' => 'index'),
					'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
					'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
					'module_count' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 5),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
						'name' => array('column' => 'name', 'unique' => 0),
					),
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'),
				),
				'modules' => array(
					'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
					'name' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100),
					'content' => array('type' => 'text', 'null' => false, 'default' => NULL),
					'plugin' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 50),
					'module' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100),
					'config' => array('type' => 'text', 'null' => true, 'default' => NULL),
					'theme_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
					'position_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'index'),
					'group_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
					'ordering' => array('type' => 'integer', 'null' => false, 'default' => NULL),
					'admin' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
					'active' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
					'show_heading' => array('type' => 'boolean', 'null' => false, 'default' => '1'),
					'core' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
					'author' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 50),
					'licence' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 75),
					'url' => array('type' => 'string', 'null' => true, 'default' => NULL),
					'update_url' => array('type' => 'string', 'null' => true, 'default' => NULL),
					'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
					'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
						'module_loader_by_position' => array('column' => array('position_id', 'admin', 'active', 'ordering'), 'unique' => 0),
					),
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'),
				),
				'modules_routes' => array(
					'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
					'module_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
					'route_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
					),
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'),
				),
			),
		),
		'down' => array(
			'drop_table' => array(
				'module_positions', 'modules', 'modules_routes'
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
	'core' => array(
		'Module' => array(
			array(
				'id' => 25,
				'name' => 'Plugin Dock',
				'content' => '',
				'plugin' => 'menus',
				'module' => 'admin/dock',
				'config' => '',
				'theme_id' => 0,
				'position_id' => 1,
				'group_id' => 1,
				'ordering' => 1,
				'admin' => 1,
				'active' => 1,
				'show_heading' => 0,
				'core' => 1,
				'author' => 'Infinitas',
				'licence' => 'MIT',
				'url' => 'http://infinitas-cms.org',
				'update_url' => '',
				'created' => '2010-09-09 19:47:53',
				'modified' => '2010-09-16 14:09:45'
			),
			array(
				'id' => 12,
				'name' => 'Admin Menu',
				'content' => '',
				'plugin' => 'menus',
				'module' => 'admin/menu',
				'config' => '{\\\"menu\\\":\\\"core_admin\\\"}',
				'theme_id' => 0,
				'position_id' => 1,
				'group_id' => 1,
				'ordering' => 2,
				'admin' => 1,
				'active' => 1,
				'show_heading' => 0,
				'core' => 1,
				'author' => 'Infinitas',
				'licence' => 'MIT',
				'url' => 'http://infinitas-cms.org',
				'update_url' => '',
				'created' => '2010-01-27 18:14:16',
				'modified' => '2010-09-16 14:09:45'
			),
			array(
				'id' => 26,
				'name' => 'Admin Todo List',
				'content' => '',
				'plugin' => 'menus',
				'module' => 'admin/todo',
				'config' => '',
				'theme_id' => 0,
				'position_id' => 1,
				'group_id' => 1,
				'ordering' => 3,
				'admin' => 1,
				'active' => 1,
				'show_heading' => 0,
				'core' => 1,
				'author' => 'Infinitas',
				'licence' => 'MIT',
				'url' => 'http://infinitas-cms.org',
				'update_url' => '',
				'created' => '2010-09-10 15:40:06',
				'modified' => '2010-09-16 14:09:45'
			),
			array(
				'id' => 13,
				'name' => 'Frontend Menu',
				'content' => '',
				'plugin' => 'menus',
				'module' => 'main_menu',
				'config' => '{\\\"menu\\\":\\\"main_menu\\\"}',
				'theme_id' => 0,
				'position_id' => 1,
				'group_id' => 2,
				'ordering' => 4,
				'admin' => 0,
				'active' => 1,
				'show_heading' => 0,
				'core' => 1,
				'author' => 'Infinitas',
				'licence' => 'MIT',
				'url' => 'http://infinitas-cms.org',
				'update_url' => '',
				'created' => '2010-02-01 00:57:01',
				'modified' => '2010-09-16 16:03:36'
			),
			array(
				'id' => 27,
				'name' => 'Admin Dashboard Icons',
				'content' => '',
				'plugin' => 'menus',
				'module' => 'admin/dashboard_icons',
				'config' => '',
				'theme_id' => 0,
				'position_id' => 8,
				'group_id' => 1,
				'ordering' => 1,
				'admin' => 1,
				'active' => 1,
				'show_heading' => 0,
				'core' => 1,
				'author' => 'Infinitas',
				'licence' => '&copy; MIT',
				'url' => 'http://infinitas-cms.org',
				'update_url' => '',
				'created' => '2010-09-11 17:32:33',
				'modified' => '2010-09-16 14:09:45'
			),
			array(
				'id' => 29,
				'name' => 'Overall Stats',
				'content' => '',
				'plugin' => 'view_counter',
				'module' => 'admin/overall',
				'config' => '',
				'theme_id' => 0,
				'position_id' => 8,
				'group_id' => 1,
				'ordering' => 2,
				'admin' => 1,
				'active' => 1,
				'show_heading' => 0,
				'core' => 1,
				'author' => 'Infinitas',
				'licence' => '&copy; MIT',
				'url' => 'http://infinitas-cms.org',
				'update_url' => '',
				'created' => '2010-09-12 21:45:32',
				'modified' => '2010-09-16 14:09:45'
			),
			array(
				'id' => 28,
				'name' => 'View Counter Stats',
				'content' => '',
				'plugin' => 'view_counter',
				'module' => 'admin/popular_items',
				'config' => '',
				'theme_id' => 0,
				'position_id' => 8,
				'group_id' => 1,
				'ordering' => 3,
				'admin' => 1,
				'active' => 1,
				'show_heading' => 0,
				'core' => 0,
				'author' => 'Infinitas',
				'licence' => '&copy; MIT',
				'url' => 'http://infinitas-cms.org',
				'update_url' => '',
				'created' => '2010-09-11 18:00:14',
				'modified' => '2010-09-16 14:09:45'
			),
		),
		'ModulesRoute' => array(
			array(
				'id' => 65,
				'module_id' => 25,
				'route_id' => 0
			),
			array(
				'id' => 66,
				'module_id' => 12,
				'route_id' => 0
			),
			array(
				'id' => 67,
				'module_id' => 26,
				'route_id' => 0
			),
			array(
				'id' => 69,
				'module_id' => 27,
				'route_id' => 9
			),
			array(
				'id' => 71,
				'module_id' => 28,
				'route_id' => 9
			),
			array(
				'id' => 72,
				'module_id' => 29,
				'route_id' => 9
			),
			array(
				'id' => 74,
				'module_id' => 13,
				'route_id' => 0
			),
			array(
				'id' => 76,
				'module_id' => 30,
				'route_id' => 0
			),
		),
		'ModulePosition' => array(
			array(
				'id' => 1,
				'name' => 'top',
				'created' => '2010-01-18 21:45:23',
				'modified' => '2010-01-18 21:45:23',
				'module_count' => 4
			),
			array(
				'id' => 2,
				'name' => 'bottom',
				'created' => '2010-01-18 21:45:23',
				'modified' => '2010-01-18 21:45:23',
				'module_count' => 3
			),
			array(
				'id' => 3,
				'name' => 'left',
				'created' => '2010-01-18 21:45:23',
				'modified' => '2010-01-18 21:45:23',
				'module_count' => 0
			),
			array(
				'id' => 4,
				'name' => 'right',
				'created' => '2010-01-18 21:45:23',
				'modified' => '2010-01-18 21:45:23',
				'module_count' => 0
			),
			array(
				'id' => 5,
				'name' => 'custom1',
				'created' => '2010-01-18 21:45:23',
				'modified' => '2010-01-18 21:45:23',
				'module_count' => 0
			),
			array(
				'id' => 6,
				'name' => 'custom2',
				'created' => '2010-01-18 21:45:23',
				'modified' => '2010-01-18 21:45:23',
				'module_count' => 1
			),
			array(
				'id' => 7,
				'name' => 'custom3',
				'created' => '2010-01-18 21:45:23',
				'modified' => '2010-01-18 21:45:23',
				'module_count' => 2
			),
			array(
				'id' => 8,
				'name' => 'custom4',
				'created' => '2010-01-18 21:45:23',
				'modified' => '2010-01-18 21:45:23',
				'module_count' => 0
			),
			array(
				'id' => 9,
				'name' => 'bread_crumbs',
				'created' => '2010-01-18 21:45:23',
				'modified' => '2010-01-18 21:45:23',
				'module_count' => 0
			),
			array(
				'id' => 10,
				'name' => 'debug',
				'created' => '2010-01-18 21:45:23',
				'modified' => '2010-01-18 21:45:23',
				'module_count' => 0
			),
			array(
				'id' => 11,
				'name' => 'feeds',
				'created' => '2010-01-18 21:45:23',
				'modified' => '2010-01-18 21:45:23',
				'module_count' => 0
			),
			array(
				'id' => 12,
				'name' => 'search',
				'created' => '2010-01-18 21:45:23',
				'modified' => '2010-01-18 21:45:23',
				'module_count' => 0
			),
			array(
				'id' => 13,
				'name' => 'hidden',
				'created' => '2010-03-05 18:33:20',
				'modified' => '2010-03-05 18:33:20',
				'module_count' => 0
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