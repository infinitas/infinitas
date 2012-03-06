<?php
	/**
	 * Infinitas Releas
	 *
	 * Auto generated database update
	 */
	 
	class R4f5634eaace44f9aadc543556318cd70 extends CakeRelease {

	/**
	* Migration description
	*
	* @var string
	* @access public
	*/
		public $description = 'Migration for ServerStatus version 0.9';

	/**
	* Plugin name
	*
	* @var string
	* @access public
	*/
		public $plugin = 'ServerStatus';

	/**
	* Actions to be performed
	*
	* @var array $migration
	* @access public
	*/
		public $migration = array(
			'up' => array(
			'create_table' => array(
				'core_crons' => array(
					'id' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 36, 'key' => 'primary', 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'process_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 8),
					'year' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 4, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'month' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 2),
					'day' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 2),
					'start_time' => array('type' => 'time', 'null' => false, 'default' => NULL),
					'start_mem' => array('type' => 'integer', 'null' => false, 'default' => '0'),
					'start_load' => array('type' => 'string', 'null' => false, 'default' => '0', 'length' => 10, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'end_time' => array('type' => 'time', 'null' => true, 'default' => NULL),
					'end_mem' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 10),
					'end_load' => array('type' => 'string', 'null' => false, 'default' => '0', 'length' => 10, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'mem_ave' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 10, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'load_ave' => array('type' => 'string', 'null' => false, 'default' => '0', 'length' => 10, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'tasks_ran' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 3),
					'done' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 3),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
					),
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'),
				),
			),
		),
		'down' => array(
			'drop_table' => array(
				'core_crons'
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