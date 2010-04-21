<?php
class M4bce213438dc45b189e820d46318cd70 extends CakeMigration {

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
				'shop_suppliers' => array(
					'product_count' => array('type' => 'integer', 'null' => false, 'default' => '0'),
					'terms' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 50),
					'active' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
				),
			),
		),
		'down' => array(
			'drop_field' => array(
				'shop_suppliers' => array('product_count', 'terms', 'active',),
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