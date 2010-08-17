<?php
class M4c6a931e21a848caadbf0b886318cd70 extends CakeMigration {

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
				'cms_content_layouts' => array(
					'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
					'content_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
					'name' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 50),
					'css' => array('type' => 'text', 'null' => false, 'default' => NULL),
					'html' => array('type' => 'text', 'null' => false, 'default' => NULL),
					'php' => array('type' => 'text', 'null' => false, 'default' => NULL),
					'locked' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 4),
					'locked_by' => array('type' => 'integer', 'null' => true, 'default' => NULL),
					'locked_since' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
					'content_count' => array('type' => 'integer', 'null' => false, 'default' => NULL),
					'active' => array('type' => 'integer', 'null' => false, 'default' => '1', 'length' => 4),
					'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
					'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
					),
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'),
				),
				'cms_contents' => array(
					'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
					'title' => array('type' => 'string', 'null' => false, 'default' => NULL),
					'slug' => array('type' => 'string', 'null' => false, 'default' => NULL),
					'body' => array('type' => 'text', 'null' => false, 'default' => NULL),
					'locked' => array('type' => 'boolean', 'null' => false, 'default' => '0', 'key' => 'index'),
					'locked_since' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
					'locked_by' => array('type' => 'integer', 'null' => true, 'default' => NULL),
					'ordering' => array('type' => 'integer', 'null' => false, 'default' => '0'),
					'group_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'key' => 'index'),
					'views' => array('type' => 'integer', 'null' => false, 'default' => '0'),
					'active' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
					'start' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
					'end' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
					'created' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
					'modified' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
					'layout_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
					'created_by' => array('type' => 'integer', 'null' => false, 'default' => NULL),
					'modified_by' => array('type' => 'integer', 'null' => false, 'default' => NULL),
					'category_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'index'),
					'rating' => array('type' => 'float', 'null' => false, 'default' => '0'),
					'rating_count' => array('type' => 'integer', 'null' => false, 'default' => '0'),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
						'idx_access' => array('column' => 'group_id', 'unique' => 0),
						'idx_checkout' => array('column' => 'locked', 'unique' => 0),
						'category_id' => array('column' => 'category_id', 'unique' => 0),
					),
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'),
				),
				'cms_features' => array(
					'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
					'content_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
					'ordering' => array('type' => 'integer', 'null' => false, 'default' => '0'),
					'order_id' => array('type' => 'integer', 'null' => false, 'default' => '1'),
					'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
					'created_by' => array('type' => 'integer', 'null' => false, 'default' => NULL),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
					),
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'),
				),
				'cms_frontpages' => array(
					'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
					'content_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
					'ordering' => array('type' => 'integer', 'null' => false, 'default' => '0'),
					'order_id' => array('type' => 'integer', 'null' => false, 'default' => '1'),
					'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
					'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
					'created_by' => array('type' => 'integer', 'null' => false, 'default' => NULL),
					'modified_by' => array('type' => 'integer', 'null' => false, 'default' => NULL),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
					),
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'),
				),
			),
		),
		'down' => array(
			'drop_table' => array(
				'cms_content_layouts', 'cms_contents', 'cms_features', 'cms_frontpages'
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