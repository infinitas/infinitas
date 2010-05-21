<?php
class M4bf66ca7c4fc4ea5b2e321b06318cd70 extends CakeMigration {

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
				'blog_posts' => array('intro',),
				'global_categories' => array('created_by', 'modified_by',),
			),
			'alter_field' => array(
				'global_categories' => array(
					'slug' => array('type' => 'string', 'null' => false, 'default' => NULL),
					'description' => array('type' => 'text', 'null' => true, 'default' => NULL),
					'group_id' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 3, 'key' => 'index'),
				),
			),
		),
		'down' => array(
			'create_field' => array(
				'blog_posts' => array(
					'intro' => array('type' => 'text', 'null' => false, 'default' => NULL),
				),
				'global_categories' => array(
					'created_by' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
					'modified_by' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
				),
			),
			'alter_field' => array(
				'global_categories' => array(
					'slug' => array('type' => 'string', 'null' => true, 'default' => NULL),
					'description' => array('type' => 'text', 'null' => false, 'default' => NULL),
					'group_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 3, 'key' => 'index'),
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