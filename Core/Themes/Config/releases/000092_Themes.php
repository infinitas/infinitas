<?php
	/**
	 * Infinitas Releas
	 *
	 * Auto generated database update
	 */
	 
	class R50aee8e55e8c49e3a13109856318cd70 extends CakeRelease {

	/**
	* Migration description
	*
	* @var string
	* @access public
	*/
		public $description = 'Migration for Themes version 0.9.2';

	/**
	* Plugin name
	*
	* @var string
	* @access public
	*/
		public $plugin = 'Themes';

	/**
	* Actions to be performed
	*
	* @var array $migration
	* @access public
	*/
		public $migration = array(
			'up' => array(
			'create_field' => array(
				'core_themes' => array(
					'admin' => array('type' => 'boolean', 'null' => false, 'default' => '0', 'key' => 'index', 'after' => 'default_layout'),
					'frontend' => array('type' => 'boolean', 'null' => false, 'default' => '0', 'after' => 'admin'),
					'indexes' => array(
						'active' => array('column' => 'admin', 'unique' => 0),
					),
				),
			),
			'drop_field' => array(
				'core_themes' => array('active', 'core', 'indexes' => array('active')),
			),
		),
		'down' => array(
			'drop_field' => array(
				'core_themes' => array('admin', 'frontend', 'indexes' => array('active')),
			),
			'create_field' => array(
				'core_themes' => array(
					'active' => array('type' => 'boolean', 'null' => false, 'default' => '0', 'key' => 'index'),
					'core' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
					'indexes' => array(
						'active' => array(),
					),
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