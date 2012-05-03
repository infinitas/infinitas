<?php
	/**
	 * Infinitas Releas
	 *
	 * Auto generated database update
	 */
	 
	class R4fa2f737bd044d7798b553296318cd70 extends CakeRelease {

	/**
	* Migration description
	*
	* @var string
	* @access public
	*/
		public $description = 'Migration for Contents version 0.9.3';

	/**
	* Plugin name
	*
	* @var string
	* @access public
	*/
		public $plugin = 'Contents';

	/**
	* Actions to be performed
	*
	* @var array $migration
	* @access public
	*/
		public $migration = array(
			'up' => array(
			'create_field' => array(
				'global_categories' => array(
					'path_depth' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 5, 'after' => 'rght'),
				),
			),
		),
		'down' => array(
			'drop_field' => array(
				'global_categories' => array('path_depth',),
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