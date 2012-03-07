<?php
	/**
	 * Infinitas Releas
	 *
	 * Auto generated database update
	 */
	 
	class R4f5634accfa0451eb99e43556318cd70 extends CakeRelease {

	/**
	* Migration description
	*
	* @var string
	* @access public
	*/
		public $description = 'Migration for Modules version 0.9';

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
					'core_module_positions' => array(
						'id' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 36, 'key' => 'primary', 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
						'name' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 50, 'key' => 'index', 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
						'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
						'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
						'module_count' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 5),
						'indexes' => array(
							'PRIMARY' => array('column' => 'id', 'unique' => 1),
							'name' => array('column' => 'name', 'unique' => 0),
						),
						'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'),
					),
					'core_modules' => array(
						'id' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 36, 'key' => 'primary', 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
						'name' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
						'content' => array('type' => 'text', 'null' => false, 'default' => NULL, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
						'plugin' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 50, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
						'module' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
						'config' => array('type' => 'text', 'null' => true, 'default' => NULL, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
						'theme_id' => array('type' => 'string', 'null' => false, 'default' => '0', 'length' => 36, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
						'position_id' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 36, 'key' => 'index', 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
						'group_id' => array('type' => 'string', 'null' => false, 'default' => '0', 'length' => 36, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
						'ordering' => array('type' => 'integer', 'null' => false, 'default' => NULL),
						'admin' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
						'active' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
						'show_heading' => array('type' => 'boolean', 'null' => false, 'default' => '1'),
						'core' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
						'author' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 50, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
						'licence' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 75, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
						'url' => array('type' => 'string', 'null' => true, 'default' => NULL, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
						'update_url' => array('type' => 'string', 'null' => true, 'default' => NULL, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
						'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
						'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
						'indexes' => array(
							'PRIMARY' => array('column' => 'id', 'unique' => 1),
							'module_loader_by_position' => array('column' => array('position_id', 'admin', 'active', 'ordering'), 'unique' => 0),
						),
						'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'),
					),
					'core_modules_routes' => array(
						'id' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 36, 'key' => 'primary', 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
						'module_id' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 36, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
						'route_id' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 36, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
						'indexes' => array(
							'PRIMARY' => array('column' => 'id', 'unique' => 1),
						),
						'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'),
					),
				),
			),
			'down' => array(
				'drop_table' => array(
					'core_module_positions', 'core_modules', 'core_modules_routes'
				),
			),
		);
		
		public $fixtures = array(
			'core' => array(
				'ModulePosition' => array(
					array(
						'id' => '00000000-3394-4e47-0001-000000000000',
						'name' => 'top',
						'module_count' => 0,
					),
					array(
						'id' => '00000000-3394-4e47-0001-000000000001',
						'name' => 'debug',
						'module_count' => 0,
					),
					array(
						'id' => '00000000-3394-4e47-0001-000000000002',
						'name' => 'feeds',
						'module_count' => 0,
					),
					array(
						'id' => '00000000-3394-4e47-0001-000000000003',
						'name' => 'search',
						'module_count' => 0,
					),
					array(
						'id' => '00000000-3394-4e47-0001-000000000004',
						'name' => 'hidden',
						'module_count' => 0,
					),
					array(
						'id' => '00000000-3394-4e47-0001-000000000005',
						'name' => 'bottom',
						'module_count' => 0,
					),
					array(
						'id' => '00000000-3394-4e47-0001-000000000006',
						'name' => 'left',
						'module_count' => 0,
					),
					array(
						'id' => '00000000-3394-4e47-0001-000000000007',
						'name' => 'right',
						'module_count' => 0,
					),
					array(
						'id' => '00000000-3394-4e47-0001-000000000008',
						'name' => 'custom1',
						'module_count' => 0,
					),
					array(
						'id' => '00000000-3394-4e47-0001-000000000009',
						'name' => 'custom2',
						'module_count' => 0,
					),
					array(
						'id' => '00000000-3394-4e47-0001-000000000010',
						'name' => 'custom3',
						'module_count' => 0,
					),
					array(
						'id' => '00000000-3394-4e47-0001-000000000011',
						'name' => 'custom4',
						'module_count' => 0,
					),
					array(
						'id' => '00000000-3394-4e47-0001-000000000012',
						'name' => 'bread_crumbs',
						'module_count' => 0,
					),
				),
				'ModulesRoute' => array(
					array(
						'id' => '00000000-3394-4e47-0003-000000000000',
						'module_id' => '00000000-3394-4e47-0002-000000000003',
						'route_id' => '00000000',
					),
					array(
						'id' => '00000000-3394-4e47-0003-000000000000',
						'module_id' => '00000000-3394-4e47-0002-000000000011',
						'route_id' => '00000000',
					)
				),
				'Module' => array(
					array(
						'id' => '00000000-3394-4e47-0002-000000000000',
						'name' => 'Admin Menu',
						'content' => '',
						'plugin' => 'Menus',
						'module' => 'admin/menu',
						'config' => '{"menu":"core_admin"}',
						'theme_id' => 0,
						'position_id' => '00000000-3394-4e47-0001-000000000000',
						'group_id' => 1,
						'ordering' => 1,
						'admin' => 1,
						'active' => 1,
						'show_heading' => 0,
						'core' => 1,
						'author' => 'Infinitas',
						'licence' => 'MIT',
						'url' => 'http://infinitas-cms.org',
						'update_url' => 'http://infinitas-cms.org/plugins/menus/',
					),
					array(
						'id' => '00000000-3394-4e47-0002-000000000001',
						'name' => 'Plugin Dock',
						'content' => '',
						'plugin' => 'Menus',
						'module' => 'admin/dock',
						'config' => '',
						'theme_id' => 0,
						'position_id' => '00000000-3394-4e47-0001-000000000000',
						'group_id' => 1,
						'ordering' => 2,
						'admin' => 1,
						'active' => 1,
						'show_heading' => 0,
						'core' => 1,
						'author' => 'Infinitas',
						'licence' => 'MIT',
						'url' => 'http://infinitas-cms.org',
						'update_url' => 'http://infinitas-cms.org/plugins/menus/',
					),
					array(
						'id' => '00000000-3394-4e47-0002-000000000002',
						'name' => 'Todo List',
						'content' => '',
						'plugin' => 'Menus',
						'module' => 'admin/todo',
						'config' => '',
						'theme_id' => 0,
						'position_id' => '00000000-3394-4e47-0001-000000000000',
						'group_id' => 1,
						'ordering' => 3,
						'admin' => 1,
						'active' => 1,
						'show_heading' => 0,
						'core' => 1,
						'author' => 'Infinitas',
						'licence' => 'MIT',
						'url' => 'http://infinitas-cms.org',
						'update_url' => 'http://infinitas-cms.org/plugins/menus/',
					),
					array(
						'id' => '00000000-3394-4e47-0002-000000000003',
						'name' => 'Dashboard Icons',
						'content' => '',
						'plugin' => 'Menus',
						'module' => 'admin/dashboard_icons',
						'config' => '',
						'theme_id' => 0,
						'position_id' => '00000000-3394-4e47-0001-000000000005',
						'group_id' => 1,
						'ordering' => 1,
						'admin' => 1,
						'active' => 1,
						'show_heading' => 0,
						'core' => 1,
						'author' => 'Infinitas',
						'licence' => 'MIT',
						'url' => 'http://infinitas-cms.org',
						'update_url' => 'http://infinitas-cms.org/plugins/menus/',
					),
					array(
						'id' => '00000000-3394-4e47-0002-000000000004',
						'name' => 'View Stats',
						'content' => '',
						'plugin' => 'ViewCounter',
						'module' => 'admin/popular_items',
						'config' => '',
						'theme_id' => 0,
						'position_id' => '00000000-3394-4e47-0001-000000000005',
						'group_id' => 1,
						'ordering' => 2,
						'admin' => 1,
						'active' => 1,
						'show_heading' => 0,
						'core' => 1,
						'author' => 'Infinitas',
						'licence' => 'MIT',
						'url' => 'http://infinitas-cms.org',
						'update_url' => 'http://infinitas-cms.org/plugins/menus/',
					),
					array(
						'id' => '00000000-3394-4e47-0002-000000000005',
						'name' => 'Overall Stats',
						'content' => '',
						'plugin' => 'ViewCounter',
						'module' => 'admin/overall',
						'config' => '',
						'theme_id' => 0,
						'position_id' => '00000000-3394-4e47-0001-000000000005',
						'group_id' => 1,
						'ordering' => 3,
						'admin' => 1,
						'active' => 1,
						'show_heading' => 0,
						'core' => 1,
						'author' => 'Infinitas',
						'licence' => 'MIT',
						'url' => 'http://infinitas-cms.org',
						'update_url' => 'http://infinitas-cms.org/plugins/menus/',
					),
					array(
						'id' => '00000000-3394-4e47-0002-000000000011',
						'name' => 'Server Status',
						'content' => '',
						'plugin' => 'ServerStatus',
						'module' => 'admin/by_hour',
						'config' => '',
						'theme_id' => 0,
						'position_id' => '00000000-3394-4e47-0001-000000000005',
						'group_id' => 2,
						'ordering' => 4,
						'admin' => 1,
						'active' => 1,
						'show_heading' => 0,
						'core' => 0,
						'author' => 'Infinitas',
						'licence' => 'MIT',
						'url' => 'http://infinitas-cms.org',
						'update_url' => 'http://infinitas-cms.org/plugins/menus/',
					),
					array(
						'id' => '00000000-3394-4e47-0002-000000000006',
						'name' => 'Google Analytics',
						'content' => '',
						'plugin' => 'Google',
						'module' => 'google_analytics',
						'config' => '{"code":"UA-xxxxxxxx-x"}',
						'theme_id' => 0,
						'position_id' => '00000000-3394-4e47-0001-000000000004',
						'group_id' => 2,
						'ordering' => 1,
						'admin' => 0,
						'active' => 0,
						'show_heading' => 0,
						'core' => 1,
						'author' => 'Infinitas',
						'licence' => 'MIT',
						'url' => 'http://infinitas-cms.org',
						'update_url' => 'http://infinitas-cms.org/plugins/menus/',
					),
					array(
						'id' => '00000000-3394-4e47-0002-000000000007',
						'name' => 'Post Dates',
						'content' => '',
						'plugin' => 'Blog',
						'module' => 'post_dates',
						'config' => '',
						'theme_id' => 0,
						'position_id' => '00000000-3394-4e47-0001-000000000007',
						'group_id' => 2,
						'ordering' => 1,
						'admin' => 0,
						'active' => 1,
						'show_heading' => 0,
						'core' => 0,
						'author' => 'Infinitas',
						'licence' => 'MIT',
						'url' => 'http://infinitas-cms.org',
						'update_url' => 'http://infinitas-cms.org/plugins/menus/',
					),
					array(
						'id' => '00000000-3394-4e47-0002-000000000008',
						'name' => 'Tag Cloud',
						'content' => '',
						'plugin' => 'Blog',
						'module' => 'post_tag_cloud',
						'config' => '',
						'theme_id' => 0,
						'position_id' => '00000000-3394-4e47-0001-000000000007',
						'group_id' => 2,
						'ordering' => 2,
						'admin' => 0,
						'active' => 1,
						'show_heading' => 0,
						'core' => 0,
						'author' => 'Infinitas',
						'licence' => 'MIT',
						'url' => 'http://infinitas-cms.org',
						'update_url' => 'http://infinitas-cms.org/plugins/menus/',
					),
					array(
						'id' => '00000000-3394-4e47-0002-000000000009',
						'name' => 'Popular Posts',
						'content' => '',
						'plugin' => 'Blog',
						'module' => 'popular_posts',
						'config' => '',
						'theme_id' => 0,
						'position_id' => '00000000-3394-4e47-0001-000000000007',
						'group_id' => 2,
						'ordering' => 3,
						'admin' => 0,
						'active' => 1,
						'show_heading' => 0,
						'core' => 0,
						'author' => 'Infinitas',
						'licence' => 'MIT',
						'url' => 'http://infinitas-cms.org',
						'update_url' => 'http://infinitas-cms.org/plugins/menus/',
					),
					array(
						'id' => '00000000-3394-4e47-0002-000000000010',
						'name' => 'Popular Posts',
						'content' => '',
						'plugin' => 'Blog',
						'module' => 'latest_posts',
						'config' => '',
						'theme_id' => 0,
						'position_id' => '00000000-3394-4e47-0001-000000000007',
						'group_id' => 2,
						'ordering' => 3,
						'admin' => 0,
						'active' => 1,
						'show_heading' => 0,
						'core' => 0,
						'author' => 'Infinitas',
						'licence' => 'MIT',
						'url' => 'http://infinitas-cms.org',
						'update_url' => 'http://infinitas-cms.org/plugins/menus/',
					),
				),
			)
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