<?php
class M4beb4cb980ac4fdc98bf1e986318cd70 extends CakeMigration {

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
			'create_table' => array(
				'core_tickets' => array(
					'id' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 32, 'key' => 'primary'),
					'data' => array('type' => 'text', 'null' => false, 'default' => NULL),
					'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
					),
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'MyISAM'),
				),
			),
			'alter_field' => array(
				'sessions' => array(
					'expires' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'key' => 'index'),
				),
			),
		),
		'down' => array(
			'drop_field' => array(
				'sessions' => array('', 'indexes' => array('id_unique', 'expires_index')),
				'shop_products' => array('specifications',),
			),
			'drop_table' => array(
				'core_tickets'
			),
			'alter_field' => array(
				'sessions' => array(
					'expires' => array('type' => 'integer', 'null' => true, 'default' => NULL),
				),
			)
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