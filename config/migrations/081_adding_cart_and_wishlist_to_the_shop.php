<?php
class M4be4348b234045f0baa114606318cd70 extends CakeMigration {

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
				'shop_carts' => array(
					'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
					'name' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 200),
					'user_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
					'product_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
					'price' => array('type' => 'float', 'null' => false, 'default' => '0'),
					'quantity' => array('type' => 'integer', 'null' => false, 'default' => '1'),
					'deleted' => array('type' => 'integer', 'null' => false, 'default' => '0'),
					'deleted_date' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
					'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
					'modified' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
					),
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'MyISAM'),
				),
				'shop_wishlists' => array(
					'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
					'name' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 200),
					'user_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
					'product_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
					'price' => array('type' => 'float', 'null' => false, 'default' => '0'),
					'quantity' => array('type' => 'integer', 'null' => false, 'default' => '1'),
					'deleted' => array('type' => 'integer', 'null' => false, 'default' => '0'),
					'deleted_date' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
					'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
					'modified' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
					),
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'MyISAM'),
				),
			),
			'create_field' => array(
				'shop_products' => array(
					'added_to_cart' => array('type' => 'integer', 'null' => false, 'default' => '0'),
					'added_to_wishlist' => array('type' => 'integer', 'null' => false, 'default' => '0'),
				),
				'shop_specials' => array(
					'active' => array('type' => 'boolean', 'null' => false, 'default' => '1'),
				),
				'shop_spotlights' => array(
					'active' => array('type' => 'integer', 'null' => false, 'default' => '1'),
				),
				'shop_units' => array(
					'symbol' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 5),
					'active' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
					'product_count' => array('type' => 'integer', 'null' => false, 'default' => '0'),
				),
			),
		),
		'down' => array(
			'drop_table' => array(
				'shop_carts', 'shop_wishlists'
			),
			'drop_field' => array(
				'shop_products' => array('added_to_cart', 'added_to_wishlist',),
				'shop_specials' => array('active',),
				'shop_spotlights' => array('active',),
				'shop_units' => array('symbol', 'active', 'product_count',),
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