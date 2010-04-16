<?php
class M4bc84829f0b04d0792530d586318cd70 extends CakeMigration {

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
			'create_field' => array(
				'blog_posts' => array(
					'category_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'index'),
					'tags' => array('type' => 'string', 'null' => false, 'default' => NULL),
					'indexes' => array(
						'category_id' => array('column' => 'category_id', 'unique' => 0),
					),
				),
				'cms_contents' => array(
					'category_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'index'),
					'indexes' => array(
						'category_id' => array('column' => 'category_id', 'unique' => 0),
					),
				),
			),
			'create_table' => array(
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
				'global_tags' => array(
					'id' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 36, 'key' => 'primary'),
					'identifier' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 30, 'key' => 'index'),
					'name' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 30),
					'keyname' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 30),
					'weight' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 2),
					'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
					'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
						'UNIQUE_TAG' => array('column' => array('identifier', 'keyname'), 'unique' => 1),
					),
					'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'MyISAM'),
				),
			),
			'drop_table' => array(
				'blog_posts_tags', 'blog_tags', 'global_category_items'
			),
		),
		'down' => array(
			'drop_field' => array(
				'blog_posts' => array('category_id', 'tags', 'indexes' => array('category_id')),
				'cms_contents' => array('category_id', 'indexes' => array('category_id')),
			),
			'drop_table' => array(
				'global_tagged', 'global_tags'
			),
			'create_table' => array(
				'blog_posts_tags' => array(
					'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
					'post_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
					'tag_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
					),
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'),
				),
				'blog_tags' => array(
					'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
					'name' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 50, 'key' => 'index'),
					'created' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
					'modified' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
						'name' => array('column' => 'name', 'unique' => 0),
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