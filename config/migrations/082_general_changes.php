<?php
class M4bf54213ffdc4bc396a311046318cd70 extends CakeMigration {

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
				'order_clients' => array(
					'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
					'user_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
					'order_count' => array('type' => 'integer', 'null' => false, 'default' => NULL),
					'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
					'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
					),
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'MyISAM'),
				),
				'order_items' => array(
					'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
					'name' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 200),
					'product_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
					'price' => array('type' => 'float', 'null' => false, 'default' => '0'),
					'quantity' => array('type' => 'integer', 'null' => false, 'default' => '1'),
					'order_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
					'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
					'modified' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
					),
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'),
				),
				'order_orders' => array(
					'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
					'user_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
					'address_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
					'special_instructions' => array('type' => 'text', 'null' => false, 'default' => NULL),
					'payment_method' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 50),
					'shipping_method' => array('type' => 'string', 'null' => false, 'default' => '0', 'length' => 50),
					'tracking_number' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 20),
					'item_count' => array('type' => 'integer', 'null' => false, 'default' => '0'),
					'total' => array('type' => 'float', 'null' => false, 'default' => NULL),
					'shipping' => array('type' => 'float', 'null' => false, 'default' => NULL),
					'status_id' => array('type' => 'integer', 'null' => false, 'default' => '1'),
					'created' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
					'modified' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
					),
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'),
				),
				'order_statuses' => array(
					'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
					'name' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 50),
					'description' => array('type' => 'text', 'null' => false, 'default' => NULL),
					'ordering' => array('type' => 'integer', 'null' => false, 'default' => NULL),
					'order_count' => array('type' => 'integer', 'null' => false, 'default' => '0'),
					'deleted' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
					'deleted_date' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
					'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
					'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
					),
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'MyISAM'),
				),
			),
			'create_field' => array(
				'core_addresses' => array(
					'foreign_key' => array('type' => 'integer', 'null' => false, 'default' => NULL),
				),
				'newsletter_campaigns' => array(
					'deleted' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 1),
					'deleted_date' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
				),
				'newsletter_templates' => array(
					'delete' => array('type' => 'boolean', 'null' => false, 'default' => NULL),
					'deleted_date' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
				),
				'shop_products' => array(
					'sales' => array('type' => 'integer', 'null' => false, 'default' => '0'),
				),
			),
			'alter_field' => array(
				'core_tickets' => array(
					'id' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 36, 'key' => 'primary'),
				),
			),
		),
		'down' => array(
			'drop_table' => array(
				'order_clients', 'order_items', 'order_orders', 'order_statuses'
			),
			'drop_field' => array(
				'core_addresses' => array('foreign_key',),
				'core_tickets' => array('expires',),
				'newsletter_campaigns' => array('deleted', 'deleted_date',),
				'newsletter_templates' => array('delete', 'deleted_date',),
				'shop_products' => array('sales',),
			),
			'create_field' => array(
				'core_tickets' => array(
					'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
				),
			),
			'alter_field' => array(
				'core_tickets' => array(
					'id' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 32, 'key' => 'primary'),
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