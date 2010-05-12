<?php
class M4bea8f6abfb84b2092a908186318cd70 extends CakeMigration {

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
				'core_modules' => array(
					'plugin' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 50),
				),
				'sessions' => array(
					'indexes' => array(
						'id_unique' => array('column' => 'id', 'unique' => 1),
						'expires_index' => array('column' => 'expires', 'unique' => 0),
					),
				),
				'shop_products' => array(
					'specifications' => array('type' => 'text', 'null' => false, 'default' => NULL),
				),
			),
			'alter_field' => array(
				'sessions' => array(
					'expires' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'key' => 'index'),
				),
			),
			'create_table' => array(
				'shop_shop_categories' => array(
					'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
					'name' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 45),
					'slug' => array('type' => 'string', 'null' => false, 'default' => NULL),
					'description' => array('type' => 'string', 'null' => true, 'default' => NULL),
					'keywords' => array('type' => 'string', 'null' => true, 'default' => NULL),
					'image_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
					'product_count' => array('type' => 'integer', 'null' => true, 'default' => '0'),
					'active' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
					'lft' => array('type' => 'integer', 'null' => false, 'default' => NULL),
					'rght' => array('type' => 'integer', 'null' => false, 'default' => NULL),
					'parent_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
					'deleted' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
					'deleted_date' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
					'created' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
					'modified' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
					),
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'),
				),
			),
			'drop_table' => array(
				'shop_categories'
			),
		),
		'down' => array(
			'drop_field' => array(
				'core_modules' => array('plugin',),
				'sessions' => array('', 'indexes' => array('id_unique', 'expires_index')),
				'shop_products' => array('specifications',),
			),
			'alter_field' => array(
				'sessions' => array(
					'expires' => array('type' => 'integer', 'null' => true, 'default' => NULL),
				),
			),
			'drop_table' => array(
				'shop_shop_categories'
			),
			'create_table' => array(
				'shop_categories' => array(
					'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
					'name' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 45),
					'slug' => array('type' => 'string', 'null' => false, 'default' => NULL),
					'description' => array('type' => 'string', 'null' => true, 'default' => NULL),
					'keywords' => array('type' => 'string', 'null' => true, 'default' => NULL),
					'image_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
					'product_count' => array('type' => 'integer', 'null' => true, 'default' => '0'),
					'active' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
					'lft' => array('type' => 'integer', 'null' => false, 'default' => NULL),
					'rght' => array('type' => 'integer', 'null' => false, 'default' => NULL),
					'parent_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
					'deleted' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
					'deleted_date' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
					'created' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
					'modified' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
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