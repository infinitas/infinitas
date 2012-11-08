<?php
	/**
	 * Infinitas Releas
	 *
	 * Auto generated database update
	 */
	 
	class R509b0562966440e08da17bd16318cd70 extends CakeRelease {

	/**
	* Migration description
	*
	* @var string
	* @access public
	*/
		public $description = 'Migration for Security version 0.9.1';

	/**
	* Plugin name
	*
	* @var string
	* @access public
	*/
		public $plugin = 'Security';

	/**
	* Actions to be performed
	*
	* @var array $migration
	* @access public
	*/
		public $migration = array(
			'up' => array(
			'create_table' => array(
				'core_ip_addresses' => array(
					'id' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 36, 'key' => 'primary', 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'ip_address' => array('type' => 'text', 'null' => false, 'default' => NULL, 'collate' => 'utf8_general_ci', 'charset' => 'utf8', 'after' => 'id'),
					'user_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'after' => 'ip_address'),
					'description' => array('type' => 'text', 'null' => false, 'default' => NULL, 'collate' => 'utf8_general_ci', 'charset' => 'utf8', 'after' => 'user_id'),
					'times_blocked' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'after' => 'description'),
					'unlock_at' => array('type' => 'datetime', 'null' => true, 'default' => NULL, 'after' => 'times_blocked'),
					'risk' => array('type' => 'integer', 'null' => false, 'default' => '1', 'after' => 'unlock_at'),
					'active' => array('type' => 'boolean', 'null' => false, 'default' => '1', 'after' => 'risk'),
					'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL, 'after' => 'active'),
					'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL, 'after' => 'created'),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
					),
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'),
				),
			),
		),
		'down' => array(
			'drop_table' => array(
				'core_ip_addresses'
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