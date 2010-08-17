<?php
class M4c6a92e5b85c4be88de113346318cd70 extends CakeMigration {

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
				'blog_posts' => array(
					'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
					'title' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 100),
					'slug' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100),
					'body' => array('type' => 'text', 'null' => true, 'default' => NULL),
					'comment_count' => array('type' => 'integer', 'null' => false, 'default' => NULL),
					'active' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
					'views' => array('type' => 'integer', 'null' => false, 'default' => '0'),
					'rating' => array('type' => 'float', 'null' => false, 'default' => '0'),
					'rating_count' => array('type' => 'integer', 'null' => false, 'default' => '0'),
					'parent_id' => array('type' => 'integer', 'null' => true, 'default' => '0'),
					'ordering' => array('type' => 'integer', 'null' => false, 'default' => NULL),
					'locked' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
					'locked_by' => array('type' => 'integer', 'null' => true, 'default' => NULL),
					'locked_since' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
					'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
					'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
					'deleted' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
					'deleted_date' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
					'category_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'index'),
					'tags' => array('type' => 'string', 'null' => false, 'default' => NULL),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
						'category_id' => array('column' => 'category_id', 'unique' => 0),
					),
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'),
				),
				'global_tagged' => array(
					'id' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 36, 'key' => 'primary'),
					'foreign_key' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 36),
					'tag_id' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 36),
					'model' => array('type' => 'string', 'null' => false, 'default' => NULL, 'key' => 'index'),
					'language' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 6, 'key' => 'index'),
					'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
					'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
						'UNIQUE_TAGGING' => array('column' => array('model', 'foreign_key', 'tag_id', 'language'), 'unique' => 1),
						'INDEX_TAGGED' => array('column' => 'model', 'unique' => 0),
						'INDEX_LANGUAGE' => array('column' => 'language', 'unique' => 0),
					),
					'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'MyISAM'),
				),
			),
		),
		'down' => array(
			'drop_table' => array(
				'blog_posts', 'global_tagged'
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