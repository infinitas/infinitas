<?php
	/**
	 * Infinitas Releas
	 *
	 * Auto generated database update
	 */
	 
	class R4f5634c57a60462b8ac743556318cd70 extends CakeRelease {

	/**
	* Migration description
	*
	* @var string
	* @access public
	*/
		public $description = 'Migration for Routes version 0.9';

	/**
	* Plugin name
	*
	* @var string
	* @access public
	*/
		public $plugin = 'Routes';

	/**
	* Actions to be performed
	*
	* @var array $migration
	* @access public
	*/
		public $migration = array(
			'up' => array(
				'create_table' => array(
					'core_routes' => array(
						'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
						'core' => array('type' => 'boolean', 'null' => false, 'default' => NULL),
						'name' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 50, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
						'url' => array('type' => 'string', 'null' => false, 'default' => NULL, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
						'prefix' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
						'plugin' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 50, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
						'controller' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 50, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
						'action' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 50, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
						'values' => array('type' => 'text', 'null' => false, 'default' => NULL, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
						'pass' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
						'rules' => array('type' => 'text', 'null' => false, 'default' => NULL, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
						'force_backend' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
						'force_frontend' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
						'order_id' => array('type' => 'integer', 'null' => false, 'default' => '1'),
						'ordering' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'index'),
						'theme_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
						'active' => array('type' => 'boolean', 'null' => false, 'default' => NULL),
						'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
						'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
						'indexes' => array(
							'PRIMARY' => array('column' => 'id', 'unique' => 1),
							'active_routes' => array('column' => array('ordering', 'active', 'theme_id'), 'unique' => 1),
						),
						'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'),
					),
				),
			),
			'down' => array(
				'drop_table' => array(
					'core_routes'
				),
			),
		);
		
		public $fixtures = array(
			'core' => array(
				'Route' => array(
					array(
						'id' => '00000000-3394-4e47-0020-000000000000',
						'core' => 0,
						'name' => 'Home',
						'url' => '/',
						'prefix' => null,
						'plugin' => 'contents',
						'controller' => 'global_pages',
						'action' => 'display',
						'values' => '{"0":"welcome"}',
						'pass' => null,
						'rules' => '',
						'force_backend' => false,
						'force_frontend' => false,
						'order_id' => 1,
						'ordering' => 1,
						'theme_id' => null,
						'active' => 1,
					),
					array(
						'id' => '00000000-3394-4e47-0020-000000000001',
						'core' => 0,
						'name' => 'Pages',
						'url' => '/pages/*',
						'prefix' => null,
						'plugin' => 'contents',
						'controller' => 'pages',
						'action' => 'display',
						'values' => '',
						'pass' => null,
						'rules' => '',
						'force_backend' => false,
						'force_frontend' => true,
						'order_id' => 1,
						'ordering' => 2,
						'theme_id' => null,
						'active' => 1,
					),
					array(
						'id' => '00000000-3394-4e47-0020-000000000002',
						'core' => 0,
						'name' => 'Contact Form',
						'url' => '/contact',
						'prefix' => null,
						'plugin' => 'newsletter',
						'controller' => 'newsletters',
						'action' => 'contact',
						'values' => '',
						'pass' => null,
						'rules' => '',
						'force_backend' => false,
						'force_frontend' => true,
						'order_id' => 1,
						'ordering' => 3,
						'theme_id' => null,
						'active' => 1,
					),
					array(
						'id' => '00000000-3394-4e47-0020-000000000003',
						'core' => 0,
						'name' => 'Articles',
						'url' => '/articles/*',
						'prefix' => null,
						'plugin' => 'contents',
						'controller' => 'global_categories',
						'action' => 'index',
						'values' => '',
						'pass' => null,
						'rules' => '',
						'force_backend' => false,
						'force_frontend' => true,
						'order_id' => 1,
						'ordering' => 4,
						'theme_id' => null,
						'active' => 1,
					),
					array(
						'id' => '00000000-3394-4e47-0020-000000000004',
						'core' => 0,
						'name' => 'Category View',
						'url' => '/category/:slug',
						'prefix' => null,
						'plugin' => 'contents',
						'controller' => 'global_categories',
						'action' => 'view',
						'values' => '',
						'pass' => 'slug',
						'rules' => '',
						'force_backend' => false,
						'force_frontend' => true,
						'order_id' => 1,
						'ordering' => 5,
						'theme_id' => null,
						'active' => 1,
					),
					array(
						'id' => '00000000-3394-4e47-0020-000000000005',
						'core' => 0,
						'name' => 'CMS Content View',
						'url' => '/cms/:category/:slug',
						'prefix' => null,
						'plugin' => 'cms',
						'controller' => 'cms_contents',
						'action' => 'view',
						'values' => '',
						'pass' => 'category,slug',
						'rules' => '',
						'force_backend' => false,
						'force_frontend' => true,
						'order_id' => 1,
						'ordering' => 6,
						'theme_id' => null,
						'active' => 1,
					),
					array(
						'id' => '00000000-3394-4e47-0020-000000000006',
						'core' => 0,
						'name' => 'Blog Post View',
						'url' => '/blog/:category/:slug',
						'prefix' => null,
						'plugin' => 'blog',
						'controller' => 'blog_post',
						'action' => 'view',
						'values' => '',
						'pass' => 'category,slug',
						'rules' => '',
						'force_backend' => false,
						'force_frontend' => true,
						'order_id' => 1,
						'ordering' => 7,
						'theme_id' => null,
						'active' => 1,
					),
					array(
						'id' => '00000000-3394-4e47-0020-000000000007',
						'core' => 0,
						'name' => 'Blog Post Dates',
						'url' => '/blog/archive/:year/*',
						'prefix' => null,
						'plugin' => 'blog',
						'controller' => 'blog_post',
						'action' => 'index',
						'values' => '',
						'pass' => 'year',
						'rules' => '{"year":"[1][9]\d\d|20[0-9][0-9]"}',
						'force_backend' => false,
						'force_frontend' => true,
						'order_id' => 1,
						'ordering' => 8,
						'theme_id' => null,
						'active' => 1,
					),
					array(
						'id' => '00000000-3394-4e47-0020-000000000008',
						'core' => 0,
						'name' => 'Blog Post Tags',
						'url' => '/blog/tag/:tag',
						'prefix' => null,
						'plugin' => 'blog',
						'controller' => 'blog_post',
						'action' => 'index',
						'values' => '',
						'pass' => 'tag',
						'rules' => '',
						'force_backend' => false,
						'force_frontend' => true,
						'order_id' => 1,
						'ordering' => 9,
						'theme_id' => null,
						'active' => 1,
					),
					array(
						'id' => '00000000-3394-4e47-0020-000000000009',
						'core' => 0,
						'name' => 'Blog index',
						'url' => '/blog/*',
						'prefix' => null,
						'plugin' => 'blog',
						'controller' => 'blog_post',
						'action' => 'index',
						'values' => '',
						'pass' => '',
						'rules' => '',
						'force_backend' => false,
						'force_frontend' => true,
						'order_id' => 1,
						'ordering' => 10,
						'theme_id' => null,
						'active' => 1,
					),
					array(
						'id' => '00000000-3394-4e47-0020-000000000010',
						'core' => 0,
						'name' => 'Portfolio View',
						'url' => '/portfolio/:slug',
						'prefix' => null,
						'plugin' => 'portfolios',
						'controller' => 'portfolios',
						'action' => 'view',
						'values' => '',
						'pass' => 'slug',
						'rules' => '',
						'force_backend' => false,
						'force_frontend' => true,
						'order_id' => 1,
						'ordering' => 11,
						'theme_id' => null,
						'active' => 1,
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