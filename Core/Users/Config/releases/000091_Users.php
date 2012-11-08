<?php
	/**
	 * Infinitas Releas
	 *
	 * Auto generated database update
	 */
	 
	class R509b08095ce04e2b83a07e276318cd70 extends CakeRelease {

	/**
	* Migration description
	*
	* @var string
	* @access public
	*/
		public $description = 'Migration for Users version 0.9.1';

	/**
	* Plugin name
	*
	* @var string
	* @access public
	*/
		public $plugin = 'Users';

	/**
	* Actions to be performed
	*
	* @var array $migration
	* @access public
	*/
		public $migration = array(
			'up' => array(
			'alter_field' => array(
				'core_groups' => array(
					'parent_id' => array('type' => 'string', 'null' => true, 'default' => NULL, 'key' => 'index', 'length' => 36, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
				),
				'core_users' => array(
					'group_id' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 36, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
				),
			),
		),
		'down' => array(
			'alter_field' => array(
				'core_groups' => array(
					'parent_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'index'),
				),
				'core_users' => array(
					'group_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
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