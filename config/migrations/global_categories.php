<?php
class M4bc334eb2f6c43b2842a0f786318cd70 extends CakeMigration {

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
			'drop_field' => array(
				'blog_posts' => array('category_id',),
				'cms_contents' => array('category_id',),
			),
			'create_table' => array(
				'global_categories' => array(
					'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
					'title' => array('type' => 'string', 'null' => false, 'default' => NULL),
					'slug' => array('type' => 'string', 'null' => true, 'default' => NULL),
					'description' => array('type' => 'text', 'null' => false, 'default' => NULL),
					'active' => array('type' => 'boolean', 'null' => false, 'default' => '0', 'key' => 'index'),
					'locked' => array('type' => 'boolean', 'null' => false, 'default' => '0', 'key' => 'index'),
					'locked_since' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
					'locked_by' => array('type' => 'integer', 'null' => true, 'default' => NULL),
					'group_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 3, 'key' => 'index'),
					'item_count' => array('type' => 'integer', 'null' => false, 'default' => '0'),
					'parent_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
					'lft' => array('type' => 'integer', 'null' => false, 'default' => NULL),
					'rght' => array('type' => 'integer', 'null' => false, 'default' => NULL),
					'views' => array('type' => 'integer', 'null' => false, 'default' => NULL),
					'created' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
					'modified' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
					'created_by' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
					'modified_by' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
					'deleted' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
					'deleted_date' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
						'cat_idx' => array('column' => array('active', 'group_id'), 'unique' => 0),
						'idx_access' => array('column' => 'group_id', 'unique' => 0),
						'idx_checkout' => array('column' => 'locked', 'unique' => 0),
					),
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'),
				),
				'global_category_items' => array(
					'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
					'class' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 200),
					'foreign_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
					'category_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
					),
					'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'MyISAM'),
				),
			),
			'drop_table' => array(
				'blog_categories', 'cms_categories', 'cms_category_configs'
			),
		),
		'down' => array(
			'create_field' => array(
				'blog_posts' => array(
					'category_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
				),
				'cms_contents' => array(
					'category_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
				),
			),
			'drop_table' => array(
				'global_categories', 'global_category_items'
			),
			'create_table' => array(
				'blog_categories' => array(
					'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
					'name' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100, 'key' => 'unique'),
					'slug' => array('type' => 'string', 'null' => false, 'default' => NULL),
					'description' => array('type' => 'text', 'null' => false, 'default' => NULL),
					'active' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
					'group_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
					'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
					'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
					'deleted' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
					'deleted_date' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
						'name' => array('column' => 'name', 'unique' => 1),
					),
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'),
				),
				'cms_categories' => array(
					'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
					'title' => array('type' => 'string', 'null' => false, 'default' => NULL),
					'slug' => array('type' => 'string', 'null' => true, 'default' => NULL),
					'description' => array('type' => 'text', 'null' => false, 'default' => NULL),
					'active' => array('type' => 'boolean', 'null' => false, 'default' => '0', 'key' => 'index'),
					'locked' => array('type' => 'boolean', 'null' => false, 'default' => '0', 'key' => 'index'),
					'locked_since' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
					'locked_by' => array('type' => 'integer', 'null' => true, 'default' => NULL),
					'group_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 3, 'key' => 'index'),
					'content_count' => array('type' => 'integer', 'null' => false, 'default' => '0'),
					'parent_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
					'lft' => array('type' => 'integer', 'null' => false, 'default' => NULL),
					'rght' => array('type' => 'integer', 'null' => false, 'default' => NULL),
					'views' => array('type' => 'integer', 'null' => false, 'default' => NULL),
					'created' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
					'modified' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
					'created_by' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
					'modified_by' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
					'deleted' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
					'deleted_date' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
						'cat_idx' => array('column' => array('active', 'group_id'), 'unique' => 0),
						'idx_access' => array('column' => 'group_id', 'unique' => 0),
						'idx_checkout' => array('column' => 'locked', 'unique' => 0),
					),
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'),
				),
				'cms_category_configs' => array(
					'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
					'category_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
					'main_articles' => array('type' => 'integer', 'null' => false, 'default' => '0'),
					'columns' => array('type' => 'integer', 'null' => false, 'default' => NULL),
					'limit' => array('type' => 'integer', 'null' => false, 'default' => NULL),
					'keywords' => array('type' => 'string', 'null' => false, 'default' => NULL),
					'description' => array('type' => 'string', 'null' => false, 'default' => NULL),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
					),
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'),
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