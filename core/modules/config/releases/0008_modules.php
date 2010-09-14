<?php
class R4c8fccf481c44305b7ba58946318cd70 extends CakeRelease {

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
					'name' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 50),
					'module_count' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 5),
					'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
					'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
					),
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'),
				),
				'modules' => array(
					'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
					'name' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100, 'key' => 'unique'),
					'content' => array('type' => 'text', 'null' => false, 'default' => NULL),
					'plugin' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 50),
					'module' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100),
					'config' => array('type' => 'text', 'null' => true, 'default' => NULL),
					'theme_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
					'position_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
					'group_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
					'ordering' => array('type' => 'integer', 'null' => false, 'default' => NULL),
					'admin' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
					'active' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
					'locked' => array('type' => 'boolean', 'null' => false, 'default' => NULL),
					'locked_by' => array('type' => 'integer', 'null' => true, 'default' => NULL),
					'locked_since' => array('type' => 'integer', 'null' => true, 'default' => NULL),
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
						'name' => array('column' => 'name', 'unique' => 1),
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
				'locked' => 0,
				'locked_by' => NULL,
				'locked_since' => NULL,
				'show_heading' => 0,
				'core' => 1,
				'author' => 'Infinitas',
				'licence' => 'MIT',
				'url' => 'http://infinitas-cms.org',
				'update_url' => '',
				'created' => '2010-09-09 19:47:53',
				'modified' => '2010-09-13 22:30:25'
			),
			array(
				'id' => 12,
				'name' => 'Admin Menu',
				'content' => '',
				'plugin' => 'menus',
				'module' => 'admin/menu',
				'config' => '{\"menu\":\"core_admin\"}',
				'theme_id' => 0,
				'position_id' => 1,
				'group_id' => 1,
				'ordering' => 2,
				'admin' => 1,
				'active' => 1,
				'locked' => 0,
				'locked_by' => 0,
				'locked_since' => 0,
				'show_heading' => 0,
				'core' => 1,
				'author' => 'Infinitas',
				'licence' => 'MIT',
				'url' => 'http://infinitas-cms.org',
				'update_url' => '',
				'created' => '2010-01-27 18:14:16',
				'modified' => '2010-09-09 19:09:31'
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
				'locked' => 0,
				'locked_by' => 0,
				'locked_since' => 0,
				'show_heading' => 0,
				'core' => 1,
				'author' => 'Infinitas',
				'licence' => 'MIT',
				'url' => 'http://infinitas-cms.org',
				'update_url' => '',
				'created' => '2010-09-10 15:40:06',
				'modified' => '2010-09-10 15:09:11'
			),
			array(
				'id' => 13,
				'name' => 'Frontend Menu',
				'content' => '',
				'plugin' => 'management',
				'module' => 'main_menu',
				'config' => '{\"public\":\"main_menu\",\"registered\":\"registered_users\"}',
				'theme_id' => 0,
				'position_id' => 1,
				'group_id' => 2,
				'ordering' => 4,
				'admin' => 0,
				'active' => 0,
				'locked' => 0,
				'locked_by' => 0,
				'locked_since' => 0,
				'show_heading' => 0,
				'core' => 1,
				'author' => 'Infinitas',
				'licence' => 'MIT',
				'url' => 'http://infinitas-cms.org',
				'update_url' => '',
				'created' => '2010-02-01 00:57:01',
				'modified' => '2010-09-10 15:41:16'
			),
			array(
				'id' => 16,
				'name' => 'Google analytics',
				'content' => '',
				'plugin' => '',
				'module' => 'google_analytics',
				'config' => '{\"code\":\"UA-xxxxxxxx-x\"}',
				'theme_id' => 0,
				'position_id' => 2,
				'group_id' => 2,
				'ordering' => 1,
				'admin' => 0,
				'active' => 0,
				'locked' => 0,
				'locked_by' => 0,
				'locked_since' => 0,
				'show_heading' => 0,
				'core' => 1,
				'author' => 'Infinitas',
				'licence' => 'MIT',
				'url' => 'http://infinitas-cms.org',
				'update_url' => '',
				'created' => '2010-02-01 20:45:05',
				'modified' => '2010-02-17 23:44:56'
			),
			array(
				'id' => 9,
				'name' => 'Latest Tweets',
				'content' => '',
				'plugin' => '',
				'module' => 'twitter_tweets',
				'config' => '',
				'theme_id' => 0,
				'position_id' => 3,
				'group_id' => 1,
				'ordering' => 2,
				'admin' => 0,
				'active' => 0,
				'locked' => 0,
				'locked_by' => 0,
				'locked_since' => 0,
				'show_heading' => 0,
				'core' => 1,
				'author' => 'Infinitas',
				'licence' => 'MIT',
				'url' => 'http://www.infinitas-cms.org',
				'update_url' => '',
				'created' => '2010-01-21 19:23:37',
				'modified' => '2010-01-21 20:46:54'
			),
			array(
				'id' => 19,
				'name' => 'Browse',
				'content' => '',
				'plugin' => 'shop',
				'module' => 'categories',
				'config' => '',
				'theme_id' => 0,
				'position_id' => 3,
				'group_id' => 2,
				'ordering' => 2,
				'admin' => 0,
				'active' => 0,
				'locked' => 0,
				'locked_by' => 0,
				'locked_since' => 0,
				'show_heading' => 1,
				'core' => 0,
				'author' => 'Infinitas',
				'licence' => 'MIT',
				'url' => 'http://infinitas-cms.org',
				'update_url' => '',
				'created' => '2010-05-03 17:50:09',
				'modified' => '2010-09-09 19:09:05'
			),
			array(
				'id' => 7,
				'name' => 'Latest News',
				'content' => '',
				'plugin' => '',
				'module' => 'latest_news',
				'config' => '',
				'theme_id' => 0,
				'position_id' => 3,
				'group_id' => 1,
				'ordering' => 3,
				'admin' => 0,
				'active' => 0,
				'locked' => 0,
				'locked_by' => 0,
				'locked_since' => 0,
				'show_heading' => 1,
				'core' => 0,
				'author' => 'Infinitas',
				'licence' => 'MIT',
				'url' => 'http://www.i-project.co.za',
				'update_url' => '',
				'created' => '2010-01-19 11:40:45',
				'modified' => '2010-09-09 19:09:05'
			),
			array(
				'id' => 4,
				'name' => 'Popular Posts',
				'content' => '',
				'plugin' => '',
				'module' => '',
				'config' => '',
				'theme_id' => 0,
				'position_id' => 4,
				'group_id' => 1,
				'ordering' => 1,
				'admin' => 0,
				'active' => 0,
				'locked' => 0,
				'locked_by' => 0,
				'locked_since' => 0,
				'show_heading' => 1,
				'core' => 0,
				'author' => 'Infinitas',
				'licence' => 'MIT',
				'url' => 'http://www.i-project.co.za',
				'update_url' => '',
				'created' => '2010-01-19 00:58:20',
				'modified' => '2010-09-09 19:09:05'
			),
			array(
				'id' => 10,
				'name' => 'Twitter News',
				'content' => '',
				'plugin' => '',
				'module' => 'twitter_search',
				'config' => '',
				'theme_id' => 0,
				'position_id' => 4,
				'group_id' => 1,
				'ordering' => 1,
				'admin' => 0,
				'active' => 0,
				'locked' => 0,
				'locked_by' => 0,
				'locked_since' => 0,
				'show_heading' => 0,
				'core' => 1,
				'author' => 'Infinitas',
				'licence' => 'MIT',
				'url' => 'http://www.infinitas-cms.org',
				'update_url' => '',
				'created' => '2010-01-21 19:50:17',
				'modified' => '2010-02-08 01:46:56'
			),
			array(
				'id' => 22,
				'name' => 'Your Cart',
				'content' => '',
				'plugin' => 'shop',
				'module' => 'cart',
				'config' => '',
				'theme_id' => 0,
				'position_id' => 4,
				'group_id' => 2,
				'ordering' => 1,
				'admin' => 0,
				'active' => 0,
				'locked' => 0,
				'locked_by' => 0,
				'locked_since' => 0,
				'show_heading' => 1,
				'core' => 0,
				'author' => 'Infinitas',
				'licence' => 'MIT',
				'url' => 'http://infinitas-cms.org',
				'update_url' => '',
				'created' => '2010-05-08 01:44:09',
				'modified' => '2010-09-09 19:09:05'
			),
			array(
				'id' => 23,
				'name' => 'Your Wishlist',
				'content' => '',
				'plugin' => 'shop',
				'module' => 'wishlist',
				'config' => '',
				'theme_id' => 0,
				'position_id' => 4,
				'group_id' => 2,
				'ordering' => 1,
				'admin' => 0,
				'active' => 0,
				'locked' => 0,
				'locked_by' => 0,
				'locked_since' => 0,
				'show_heading' => 1,
				'core' => 0,
				'author' => 'Infinitas',
				'licence' => 'MIT',
				'url' => 'http://infinitas-cms.org',
				'update_url' => '',
				'created' => '2010-05-08 01:44:39',
				'modified' => '2010-09-09 19:09:05'
			),
			array(
				'id' => 24,
				'name' => 'Rate This',
				'content' => '',
				'plugin' => 'management',
				'module' => 'rate',
				'config' => '',
				'theme_id' => 0,
				'position_id' => 4,
				'group_id' => 2,
				'ordering' => 1,
				'admin' => 0,
				'active' => 0,
				'locked' => 0,
				'locked_by' => 0,
				'locked_since' => 0,
				'show_heading' => 1,
				'core' => 0,
				'author' => 'Infinitas',
				'licence' => 'MIT',
				'url' => 'http://infinitas-cms.org/',
				'update_url' => '',
				'created' => '2010-05-10 20:06:53',
				'modified' => '2010-09-09 19:09:05'
			),
			array(
				'id' => 2,
				'name' => 'login',
				'content' => '',
				'plugin' => '',
				'module' => 'login',
				'config' => '',
				'theme_id' => 0,
				'position_id' => 4,
				'group_id' => 1,
				'ordering' => 2,
				'admin' => 0,
				'active' => 0,
				'locked' => 0,
				'locked_by' => 0,
				'locked_since' => 0,
				'show_heading' => 1,
				'core' => 0,
				'author' => 'Infinitas',
				'licence' => 'MIT',
				'url' => 'http://www.i-project.co.za',
				'update_url' => '',
				'created' => '2010-01-19 00:30:53',
				'modified' => '2010-02-01 01:22:16'
			),
			array(
				'id' => 11,
				'name' => 'Infinitas Users',
				'content' => '<div style=\"padding-top:10px\"><script type=\"text/javascript\" src=\"http://www.ohloh.net/p/442724/widgets/project_users.js?style=blue\"></script></div>',
				'plugin' => '',
				'module' => '',
				'config' => '',
				'theme_id' => 0,
				'position_id' => 4,
				'group_id' => 1,
				'ordering' => 3,
				'admin' => 0,
				'active' => 0,
				'locked' => 0,
				'locked_by' => 0,
				'locked_since' => 0,
				'show_heading' => 0,
				'core' => 0,
				'author' => 'Infinitas',
				'licence' => 'MIT',
				'url' => 'http://infinitas-cms.org',
				'update_url' => '',
				'created' => '2010-01-21 20:02:55',
				'modified' => '2010-02-08 01:59:04'
			),
			array(
				'id' => 14,
				'name' => 'Tag Cloud',
				'content' => '',
				'plugin' => 'blog',
				'module' => 'post_tag_cloud',
				'config' => '',
				'theme_id' => 0,
				'position_id' => 4,
				'group_id' => 2,
				'ordering' => 4,
				'admin' => 0,
				'active' => 0,
				'locked' => 0,
				'locked_by' => 0,
				'locked_since' => 0,
				'show_heading' => 1,
				'core' => 1,
				'author' => 'Infinitas',
				'licence' => 'MIT',
				'url' => 'http://infinitas-cms.org',
				'update_url' => '',
				'created' => '2010-02-01 16:01:01',
				'modified' => '2010-09-09 19:09:05'
			),
			array(
				'id' => 15,
				'name' => 'Post Dates',
				'content' => '',
				'plugin' => 'blog',
				'module' => 'post_dates',
				'config' => '',
				'theme_id' => 0,
				'position_id' => 4,
				'group_id' => 2,
				'ordering' => 5,
				'admin' => 0,
				'active' => 0,
				'locked' => 0,
				'locked_by' => 0,
				'locked_since' => 0,
				'show_heading' => 1,
				'core' => 1,
				'author' => 'Infinitas',
				'licence' => 'MIT',
				'url' => 'http://infinitas-cms.org',
				'update_url' => '',
				'created' => '2010-02-01 16:34:00',
				'modified' => '2010-09-09 19:09:05'
			),
			array(
				'id' => 18,
				'name' => 'banners',
				'content' => '',
				'plugin' => '',
				'module' => 'banners',
				'config' => '{\"path\":\"/banners\"}',
				'theme_id' => 0,
				'position_id' => 5,
				'group_id' => 2,
				'ordering' => 1,
				'admin' => 0,
				'active' => 0,
				'locked' => 0,
				'locked_by' => 0,
				'locked_since' => 0,
				'show_heading' => 0,
				'core' => 0,
				'author' => 'Infinitas',
				'licence' => 'MIT',
				'url' => 'http://infinitas-cms.org',
				'update_url' => '',
				'created' => '2010-05-03 14:59:01',
				'modified' => '2010-09-09 19:09:05'
			),
			array(
				'id' => 20,
				'name' => 'Promotion 1',
				'content' => '',
				'plugin' => 'shop',
				'module' => 'promotion_1',
				'config' => '',
				'theme_id' => 0,
				'position_id' => 6,
				'group_id' => 2,
				'ordering' => 1,
				'admin' => 0,
				'active' => 0,
				'locked' => 0,
				'locked_by' => 0,
				'locked_since' => 0,
				'show_heading' => 0,
				'core' => 0,
				'author' => 'Infinitas',
				'licence' => 'MIT',
				'url' => 'http://infinitas-cms.org',
				'update_url' => '',
				'created' => '2010-05-07 19:17:15',
				'modified' => '2010-09-09 19:09:05'
			),
			array(
				'id' => 21,
				'name' => 'Promotion 2',
				'content' => '',
				'plugin' => 'shop',
				'module' => 'promotion_2',
				'config' => '',
				'theme_id' => 0,
				'position_id' => 6,
				'group_id' => 2,
				'ordering' => 1,
				'admin' => 0,
				'active' => 0,
				'locked' => 0,
				'locked_by' => 0,
				'locked_since' => 0,
				'show_heading' => 0,
				'core' => 0,
				'author' => 'Infinitas',
				'licence' => 'MIT',
				'url' => 'http://infinitas-cms.org',
				'update_url' => '',
				'created' => '2010-05-07 19:18:13',
				'modified' => '2010-09-09 19:09:05'
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
				'locked' => 0,
				'locked_by' => 0,
				'locked_since' => 0,
				'show_heading' => 0,
				'core' => 1,
				'author' => 'Infinitas',
				'licence' => '&copy; MIT',
				'url' => 'http://infinitas-cms.org',
				'update_url' => '',
				'created' => '2010-09-11 17:32:33',
				'modified' => '2010-09-11 17:35:48'
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
				'locked' => 0,
				'locked_by' => 0,
				'locked_since' => 0,
				'show_heading' => 0,
				'core' => 1,
				'author' => 'Infinitas',
				'licence' => '&copy; MIT',
				'url' => 'http://infinitas-cms.org',
				'update_url' => '',
				'created' => '2010-09-12 21:45:32',
				'modified' => '2010-09-12 21:50:46'
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
				'locked' => 0,
				'locked_by' => 0,
				'locked_since' => 0,
				'show_heading' => 0,
				'core' => 0,
				'author' => 'Infinitas',
				'licence' => '&copy; MIT',
				'url' => 'http://infinitas-cms.org',
				'update_url' => '',
				'created' => '2010-09-11 18:00:14',
				'modified' => '2010-09-12 21:51:02'
			),
			array(
				'id' => 5,
				'name' => 'search',
				'content' => '',
				'plugin' => '',
				'module' => 'search',
				'config' => '',
				'theme_id' => 0,
				'position_id' => 12,
				'group_id' => 1,
				'ordering' => 1,
				'admin' => 0,
				'active' => 0,
				'locked' => 0,
				'locked_by' => 0,
				'locked_since' => 0,
				'show_heading' => 0,
				'core' => 1,
				'author' => 'Infinitas',
				'licence' => '',
				'url' => 'http://www.i-project.co.za',
				'update_url' => '',
				'created' => '2010-01-19 11:22:09',
				'modified' => '2010-01-19 14:44:49'
			),
		),
		'ModulesRoute' => array(
			array(
				'id' => 13,
				'module_id' => 7,
				'route_id' => 0
			),
			array(
				'id' => 17,
				'module_id' => 5,
				'route_id' => 0
			),
			array(
				'id' => 21,
				'module_id' => 9,
				'route_id' => 7
			),
			array(
				'id' => 25,
				'module_id' => 2,
				'route_id' => 7
			),
			array(
				'id' => 26,
				'module_id' => 2,
				'route_id' => 8
			),
			array(
				'id' => 27,
				'module_id' => 2,
				'route_id' => 9
			),
			array(
				'id' => 28,
				'module_id' => 10,
				'route_id' => 0
			),
			array(
				'id' => 40,
				'module_id' => 14,
				'route_id' => 0
			),
			array(
				'id' => 41,
				'module_id' => 15,
				'route_id' => 0
			),
			array(
				'id' => 47,
				'module_id' => 20,
				'route_id' => 0
			),
			array(
				'id' => 48,
				'module_id' => 21,
				'route_id' => 0
			),
			array(
				'id' => 49,
				'module_id' => 22,
				'route_id' => 0
			),
			array(
				'id' => 50,
				'module_id' => 23,
				'route_id' => 0
			),
			array(
				'id' => 53,
				'module_id' => 24,
				'route_id' => 19
			),
			array(
				'id' => 54,
				'module_id' => 24,
				'route_id' => 22
			),
			array(
				'id' => 55,
				'module_id' => 24,
				'route_id' => 26
			),
			array(
				'id' => 56,
				'module_id' => 4,
				'route_id' => 0
			),
			array(
				'id' => 60,
				'module_id' => 13,
				'route_id' => 0
			),
			array(
				'id' => 61,
				'module_id' => 18,
				'route_id' => 0
			),
			array(
				'id' => 62,
				'module_id' => 19,
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
				'id' => 146,
				'module_id' => 25,
				'route_id' => 0
			),
		),
		'ModulePosition' => array(
			array(
				'id' => 1,
				'name' => 'top',
				'module_count' => 0,
				'created' => '2010-01-18 21:45:23',
				'modified' => '2010-09-13 23:07:24'
			),
			array(
				'id' => 2,
				'name' => 'bottom',
				'module_count' => 0,
				'created' => '2010-01-18 21:45:23',
				'modified' => '2010-01-18 21:45:23'
			),
			array(
				'id' => 3,
				'name' => 'left',
				'module_count' => 0,
				'created' => '2010-01-18 21:45:23',
				'modified' => '2010-01-18 21:45:23'
			),
			array(
				'id' => 4,
				'name' => 'right',
				'module_count' => 0,
				'created' => '2010-01-18 21:45:23',
				'modified' => '2010-01-18 21:45:23'
			),
			array(
				'id' => 5,
				'name' => 'custom1',
				'module_count' => 0,
				'created' => '2010-01-18 21:45:23',
				'modified' => '2010-01-18 21:45:23'
			),
			array(
				'id' => 6,
				'name' => 'custom2',
				'module_count' => 0,
				'created' => '2010-01-18 21:45:23',
				'modified' => '2010-01-18 21:45:23'
			),
			array(
				'id' => 7,
				'name' => 'custom3',
				'module_count' => 0,
				'created' => '2010-01-18 21:45:23',
				'modified' => '2010-01-18 21:45:23'
			),
			array(
				'id' => 8,
				'name' => 'custom4',
				'module_count' => 0,
				'created' => '2010-01-18 21:45:23',
				'modified' => '2010-01-18 21:45:23'
			),
			array(
				'id' => 9,
				'name' => 'bread_crumbs',
				'module_count' => 0,
				'created' => '2010-01-18 21:45:23',
				'modified' => '2010-01-18 21:45:23'
			),
			array(
				'id' => 10,
				'name' => 'debug',
				'module_count' => 0,
				'created' => '2010-01-18 21:45:23',
				'modified' => '2010-01-18 21:45:23'
			),
			array(
				'id' => 11,
				'name' => 'feeds',
				'module_count' => 0,
				'created' => '2010-01-18 21:45:23',
				'modified' => '2010-01-18 21:45:23'
			),
			array(
				'id' => 12,
				'name' => 'search',
				'module_count' => 0,
				'created' => '2010-01-18 21:45:23',
				'modified' => '2010-01-18 21:45:23'
			),
			array(
				'id' => 13,
				'name' => 'hidden',
				'module_count' => 0,
				'created' => '2010-03-05 18:33:20',
				'modified' => '2010-03-05 18:33:20'
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