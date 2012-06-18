<?php
	/**
	 * Infinitas Releas
	 *
	 * Auto generated database update
	 */
	 
	class R4fdf1936e2ac4687a9a93c866318cd70 extends CakeRelease {

	/**
	* Migration description
	*
	* @var string
	* @access public
	*/
		public $description = 'Migration for Contact version 0.9.1';

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
			'create_field' => array(
				'contact_addresses' => array(
					'latitude' => array('type' => 'float', 'null' => true, 'default' => NULL, 'length' => '9,6', 'after' => 'continent_id'),
					'longitude' => array('type' => 'float', 'null' => true, 'default' => NULL, 'length' => '9,6', 'after' => 'latitude'),
				),
			),
			'drop_field' => array(
				'contact_addresses' => array('plugin',),
			),
			'alter_field' => array(
				'contact_branches' => array(
					'address_id' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 36, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
				),
			),
		),
		'down' => array(
			'drop_field' => array(
				'contact_addresses' => array('latitude', 'longitude',),
			),
			'create_field' => array(
				'contact_addresses' => array(
					'plugin' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 50, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
				),
			),
			'alter_field' => array(
				'contact_branches' => array(
					'address_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
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