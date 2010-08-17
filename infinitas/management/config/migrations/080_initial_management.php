<?php
class M4c6a937af4184bd89b6c063c6318cd70 extends CakeMigration {

/**
 * Migration description
 *
 * @var string
 * @access public
 */
	public $description = '';

/**
 * Actions to be performed
 *
 * @var array $migration
 * @access public
 */
	public $migration = array(
		'up' => array(
			'create_table' => array(
				'core_addresses' => array(
					'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
					'name' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100),
					'street' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100),
					'city' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100),
					'province' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100),
					'postal' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 10),
					'country_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
					'continent_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
					'plugin' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 50),
					'model' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 50),
					'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
					'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
					'foreign_key' => array('type' => 'integer', 'null' => false, 'default' => NULL),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
					),
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'),
				),
				'core_backups' => array(
					'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
					'plugin' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 50),
					'model' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 50),
					'last_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
					'data' => array('type' => 'text', 'null' => false, 'default' => NULL),
					'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
					),
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'),
				),
				'core_comments' => array(
					'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
					'class' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 128),
					'foreign_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
					'name' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 50),
					'email' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100),
					'website' => array('type' => 'string', 'null' => false, 'default' => NULL),
					'comment' => array('type' => 'text', 'null' => false, 'default' => NULL),
					'active' => array('type' => 'boolean', 'null' => false, 'default' => NULL),
					'rating' => array('type' => 'integer', 'null' => false, 'default' => NULL),
					'points' => array('type' => 'integer', 'null' => false, 'default' => NULL),
					'status' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 100),
					'created' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
					),
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'),
				),
				'core_configs' => array(
					'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
					'key' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100, 'key' => 'unique'),
					'value' => array('type' => 'text', 'null' => false, 'default' => NULL),
					'type' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 20),
					'options' => array('type' => 'text', 'null' => false, 'default' => NULL),
					'description' => array('type' => 'text', 'null' => true, 'default' => NULL),
					'core' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
						'config_key' => array('column' => 'key', 'unique' => 1),
					),
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'),
				),
				'core_groups' => array(
					'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
					'name' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 50),
					'description' => array('type' => 'text', 'null' => false, 'default' => NULL),
					'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
					'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
					'parent_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'index'),
					'lft' => array('type' => 'integer', 'null' => false, 'default' => NULL),
					'rght' => array('type' => 'integer', 'null' => false, 'default' => NULL),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
						'parent_id' => array('column' => 'parent_id', 'unique' => 0),
					),
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'),
				),
				'core_ip_addresses' => array(
					'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
					'ip_address' => array('type' => 'text', 'null' => false, 'default' => NULL),
					'user_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
					'description' => array('type' => 'text', 'null' => false, 'default' => NULL),
					'times_blocked' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
					'unlock_at' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
					'risk' => array('type' => 'integer', 'null' => false, 'default' => '1'),
					'active' => array('type' => 'boolean', 'null' => false, 'default' => '1'),
					'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
					'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
					),
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'),
				),
				'core_logs' => array(
					'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
					'title' => array('type' => 'string', 'null' => true, 'default' => NULL),
					'description' => array('type' => 'text', 'null' => true, 'default' => NULL),
					'model' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 100),
					'model_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
					'action' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 50),
					'user_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
					'change' => array('type' => 'text', 'null' => true, 'default' => NULL),
					'version_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
					'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
					),
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'),
				),
				'core_menu_items' => array(
					'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
					'name' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 200),
					'slug' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 200),
					'link' => array('type' => 'string', 'null' => false, 'default' => NULL),
					'prefix' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 50),
					'plugin' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100),
					'controller' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100),
					'action' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100),
					'params' => array('type' => 'string', 'null' => false, 'default' => NULL),
					'force_backend' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
					'force_frontend' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
					'class' => array('type' => 'string', 'null' => false, 'default' => NULL),
					'active' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
					'menu_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'index'),
					'group_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'index'),
					'parent_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
					'lft' => array('type' => 'integer', 'null' => false, 'default' => NULL),
					'rght' => array('type' => 'integer', 'null' => false, 'default' => NULL),
					'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
					'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
						'menuItems_groupIndex' => array('column' => 'group_id', 'unique' => 0),
						'menuItems_menuIndex' => array('column' => 'menu_id', 'unique' => 0),
					),
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'),
				),
				'core_menus' => array(
					'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
					'name' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100),
					'type' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100, 'key' => 'index'),
					'item_count' => array('type' => 'integer', 'null' => false, 'default' => '0'),
					'active' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
					'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
					'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
						'menu_index' => array('column' => array('type', 'active'), 'unique' => 0),
					),
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'),
				),
				'core_module_positions' => array(
					'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
					'name' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 50),
					'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
					'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
					),
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'),
				),
				'core_modules' => array(
					'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
					'name' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100, 'key' => 'unique'),
					'content' => array('type' => 'text', 'null' => false, 'default' => NULL),
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
					'plugin' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 50),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
						'name' => array('column' => 'name', 'unique' => 1),
					),
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'),
				),
				'core_modules_routes' => array(
					'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
					'module_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
					'route_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
					),
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'),
				),
				'core_routes' => array(
					'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
					'core' => array('type' => 'boolean', 'null' => false, 'default' => NULL),
					'name' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 50),
					'url' => array('type' => 'string', 'null' => false, 'default' => NULL),
					'prefix' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 100),
					'plugin' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 50),
					'controller' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 50),
					'action' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 50),
					'values' => array('type' => 'text', 'null' => false, 'default' => NULL),
					'pass' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 100),
					'rules' => array('type' => 'text', 'null' => false, 'default' => NULL),
					'force_backend' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
					'force_frontend' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
					'order_id' => array('type' => 'integer', 'null' => false, 'default' => '1'),
					'ordering' => array('type' => 'integer', 'null' => false, 'default' => NULL),
					'theme_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
					'active' => array('type' => 'boolean', 'null' => false, 'default' => NULL),
					'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
					'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
					),
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'),
				),
				'core_short_urls' => array(
					'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
					'url' => array('type' => 'text', 'null' => false, 'default' => NULL),
					'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
					'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
					),
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'),
				),
				'core_themes' => array(
					'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
					'name' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100),
					'description' => array('type' => 'text', 'null' => true, 'default' => NULL),
					'author' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 150),
					'url' => array('type' => 'string', 'null' => true, 'default' => NULL),
					'update_url' => array('type' => 'string', 'null' => true, 'default' => NULL),
					'licence' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100),
					'active' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
					'core' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
					'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
					'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
					),
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'),
				),
				'core_trash' => array(
					'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
					'model' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100),
					'foreign_key' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 36),
					'name' => array('type' => 'string', 'null' => false, 'default' => NULL),
					'data' => array('type' => 'text', 'null' => false, 'default' => NULL),
					'deleted' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
					'deleted_by' => array('type' => 'integer', 'null' => true, 'default' => NULL),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
					),
					'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'MyISAM'),
				),
				'core_users' => array(
					'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
					'username' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 20, 'key' => 'index'),
					'email' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100),
					'password' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 40),
					'birthday' => array('type' => 'date', 'null' => true, 'default' => NULL),
					'active' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
					'group_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
					'ip_address' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100),
					'browser' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100),
					'operating_system' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 50),
					'last_login' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
					'last_click' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
					'country' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 150),
					'city' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 150),
					'is_mobile' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
					'created' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
					'modified' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
						'username' => array('column' => array('username', 'email'), 'unique' => 1),
					),
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'),
				),
				'relation_relation_types' => array(
					'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
					'name' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100),
					'type' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100, 'key' => 'unique'),
					'description' => array('type' => 'text', 'null' => false, 'default' => NULL),
					'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
					'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
						'type' => array('column' => 'type', 'unique' => 1),
					),
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'),
				),
				'relation_relations' => array(
					'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
					'name' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100),
					'description' => array('type' => 'text', 'null' => false, 'default' => NULL),
					'plugin' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100),
					'model' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100, 'key' => 'unique'),
					'foreign_key' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 100),
					'conditions' => array('type' => 'text', 'null' => true, 'default' => NULL),
					'fields' => array('type' => 'text', 'null' => true, 'default' => NULL),
					'order' => array('type' => 'text', 'null' => true, 'default' => NULL),
					'dependent' => array('type' => 'boolean', 'null' => true, 'default' => '0'),
					'limit' => array('type' => 'integer', 'null' => true, 'default' => NULL),
					'offset' => array('type' => 'integer', 'null' => true, 'default' => NULL),
					'counter_cache' => array('type' => 'boolean', 'null' => true, 'default' => '0'),
					'counter_scope' => array('type' => 'text', 'null' => false, 'default' => NULL),
					'join_table' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 150),
					'with' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 150),
					'association_foreign_key' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100),
					'unique' => array('type' => 'boolean', 'null' => true, 'default' => '0'),
					'finder_query' => array('type' => 'text', 'null' => false, 'default' => NULL),
					'delete_query' => array('type' => 'text', 'null' => false, 'default' => NULL),
					'insert_query' => array('type' => 'text', 'null' => false, 'default' => NULL),
					'bind' => array('type' => 'boolean', 'null' => false, 'default' => '1'),
					'reverse_association' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
					'type_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
					'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
					'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
						'class_name' => array('column' => 'model', 'unique' => 1),
					),
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'),
				),
			),
		),
		'down' => array(
			'drop_table' => array(
				'core_addresses', 'core_backups', 'core_comments', 'core_configs', 'core_groups', 'core_ip_addresses', 'core_logs', 'core_menu_items', 'core_menus', 'core_module_positions', 'core_modules', 'core_modules_routes', 'core_routes', 'core_short_urls', 'core_themes', 'core_trash', 'core_users', 'relation_relation_types', 'relation_relations'
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