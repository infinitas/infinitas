<?php
class M4c929f79b9fc401e985f58ae6318cd70 extends CakeMigration {

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
			'alter_field' => array(
				'blog_posts' => array(
					'active' => array('type' => 'boolean', 'null' => false, 'default' => '0', 'key' => 'index'),
					'views' => array('type' => 'integer', 'null' => false, 'default' => '0', 'key' => 'index'),
				),
				'cms_contents' => array(
					'views' => array('type' => 'integer', 'null' => false, 'default' => '0', 'key' => 'index'),
					'active' => array('type' => 'boolean', 'null' => false, 'default' => '0', 'key' => 'index'),
				),
				'core_module_positions' => array(
					'name' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 50, 'collate' => 'utf8_general_ci', 'charset' => 'utf8', 'key' => 'index'),
				),
				'core_modules' => array(
					'position_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'index'),
				),
				'core_routes' => array(
					'ordering' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'index'),
				),
				'core_themes' => array(
					'active' => array('type' => 'boolean', 'null' => false, 'default' => '0', 'key' => 'index'),
				),
				'global_categories' => array(
					'lft' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'index'),
				),
				'global_comments' => array(
					'active' => array('type' => 'boolean', 'null' => false, 'default' => NULL, 'key' => 'index'),
					'status' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8', 'key' => 'index'),
				),
			),
			'create_field' => array(
				'blog_posts' => array(
					'indexes' => array(
						'active' => array('column' => 'active', 'unique' => 0),
						'most_views' => array('column' => array('views', 'id', 'title'), 'unique' => 0),
					),
				),
				'cms_contents' => array(
					'indexes' => array(
						'most_views' => array('column' => array('views', 'id', 'title'), 'unique' => 0),
						'active' => array('column' => array('active', 'ordering'), 'unique' => 0),
					),
				),
				'core_module_positions' => array(
					'indexes' => array(
						'name' => array('column' => 'name', 'unique' => 0),
					),
				),
				'core_modules' => array(
					'indexes' => array(
						'module_loader_by_position' => array('column' => array('position_id', 'admin', 'active', 'ordering'), 'unique' => 0),
					),
				),
				'core_routes' => array(
					'indexes' => array(
						'active_routes' => array('column' => array('ordering', 'active', 'theme_id'), 'unique' => 1),
					),
				),
				'core_themes' => array(
					'indexes' => array(
						'active' => array('column' => 'active', 'unique' => 0),
					),
				),
				'global_categories' => array(
					'indexes' => array(
						'tree_list_all' => array('column' => array('lft', 'id', 'title', 'rght'), 'unique' => 1),
					),
				),
				'global_comments' => array(
					'indexes' => array(
						'active' => array('column' => 'active', 'unique' => 0),
						'status' => array('column' => 'status', 'unique' => 0),
					),
				),
			),
			'create_table' => array(				
			),
			'drop_field' => array(
				'core_modules' => array('', 'indexes' => array('name')),
			),
		),
		'down' => array(
			'alter_field' => array(
				'blog_posts' => array(
					'active' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
					'views' => array('type' => 'integer', 'null' => false, 'default' => '0'),
				),
				'cms_contents' => array(
					'views' => array('type' => 'integer', 'null' => false, 'default' => '0'),
					'active' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
				),
				'core_module_positions' => array(
					'name' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 50, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
				),
				'core_modules' => array(
					'position_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
				),
				'core_routes' => array(
					'ordering' => array('type' => 'integer', 'null' => false, 'default' => NULL),
				),
				'core_themes' => array(
					'active' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
				),
				'global_categories' => array(
					'lft' => array('type' => 'integer', 'null' => false, 'default' => NULL),
				),
				'global_comments' => array(
					'active' => array('type' => 'boolean', 'null' => false, 'default' => NULL),
					'status' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
				),
			),
			'drop_field' => array(
				'blog_posts' => array('', 'indexes' => array('active', 'most_views')),
				'cms_contents' => array('', 'indexes' => array('most_views', 'active')),
				'core_module_positions' => array('', 'indexes' => array('name')),
				'core_modules' => array('', 'indexes' => array('module_loader_by_position')),
				'core_routes' => array('', 'indexes' => array('active_routes')),
				'core_themes' => array('', 'indexes' => array('active')),
				'global_categories' => array('', 'indexes' => array('tree_list_all')),
				'global_comments' => array('', 'indexes' => array('active', 'status')),
			),
			'create_field' => array(
				'core_modules' => array(
					'indexes' => array(
						'name' => array('column' => 'name', 'unique' => 1),
					),
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