<?php
class R4c94edccf7144d6c8f1778d86318cd70 extends CakeRelease {

/**
 * Migration description
 *
 * @var string
 * @access public
 */
	public $description = 'Migration for Contact version 0.8';

/**
 * Plugin name
 *
 * @var string
 * @access public
 */
	public $plugin = 'Contact';

/**
 * Actions to be performed
 *
 * @var array $migration
 * @access public
 */
	public $migration = array(
		'up' => array(
			'create_table' => array(
				'branches' => array(
					'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
					'name' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100),
					'slug' => array('type' => 'string', 'null' => false, 'default' => NULL),
					'map' => array('type' => 'string', 'null' => true, 'default' => NULL),
					'image' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 100),
					'email' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100),
					'phone' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 20),
					'fax' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 20),
					'address_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
					'user_count' => array('type' => 'integer', 'null' => false, 'default' => NULL),
					'active' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
					'ordering' => array('type' => 'integer', 'null' => false, 'default' => NULL),
					'time_zone_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
					'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
					'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
					),
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'),
				),
				'contacts' => array(
					'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
					'user_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
					'image' => array('type' => 'string', 'null' => false, 'default' => NULL),
					'first_name' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 50),
					'last_name' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100),
					'slug' => array('type' => 'string', 'null' => false, 'default' => NULL),
					'position' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 100),
					'phone' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 20),
					'mobile' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 20),
					'email' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100),
					'skype' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 50),
					'branch_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
					'ordering' => array('type' => 'integer', 'null' => false, 'default' => NULL),
					'configs' => array('type' => 'text', 'null' => false, 'default' => NULL),
					'active' => array('type' => 'boolean', 'null' => false, 'default' => NULL),
					'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
					'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
					),
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'),
				),
			),
		),
		'down' => array(
			'drop_table' => array(
				'branches', 'contacts'
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