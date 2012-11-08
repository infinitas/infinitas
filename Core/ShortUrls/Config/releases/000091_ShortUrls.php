<?php
	/**
	 * Infinitas Releas
	 *
	 * Auto generated database update
	 */
	 
	class R509b1312e43846cba08306f66318cd70 extends CakeRelease {

	/**
	* Migration description
	*
	* @var string
	* @access public
	*/
		public $description = 'Migration for ShortUrls version 0.9.1';

	/**
	* Plugin name
	*
	* @var string
	* @access public
	*/
		public $plugin = 'ShortUrls';

	/**
	* Actions to be performed
	*
	* @var array $migration
	* @access public
	*/
		public $migration = array(
			'up' => array(
			'alter_field' => array(
				'core_short_urls' => array(
					'id' => array('type' => 'biginteger', 'null' => false, 'default' => NULL, 'key' => 'primary'),
				),
			),
		),
		'down' => array(
			'alter_field' => array(
				'core_short_urls' => array(
					'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
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