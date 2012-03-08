<?php
	/**
	 * Infinitas Releas
	 *
	 * Auto generated database update
	 */
	 
	class R4f564a8e65284703b3b34d916318cd70 extends CakeRelease {

	/**
	* Migration description
	*
	* @var string
	* @access public
	*/
		public $description = 'Migration for ViewCounter version 0.9';

	/**
	* Plugin name
	*
	* @var string
	* @access public
	*/
		public $plugin = 'ViewCounter';

	/**
	* Actions to be performed
	*
	* @var array $migration
	* @access public
	*/
		public $migration = array(
			'up' => array(
			'create_table' => array(
				'view_counter_views' => array(
					'id' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 36, 'key' => 'primary', 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'model' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 50, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'foreign_key' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 36, 'key' => 'index', 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'user_id' => array('type' => 'string', 'null' => false, 'default' => '0', 'length' => 36, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'ip_address' => array('type' => 'string', 'null' => false, 'default' => '?', 'length' => 15, 'key' => 'index', 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'year' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 4, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'month' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 4),
					'day' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 4),
					'hour' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 4),
					'week_of_year' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 4),
					'day_of_year' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 4),
					'day_of_week' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 1, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'continent_code' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 5, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'country_code' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 2, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'country' => array('type' => 'string', 'null' => false, 'default' => 'Unknown', 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'city' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'referer' => array('type' => 'text', 'null' => false, 'default' => NULL, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
						'ip_address' => array('column' => 'ip_address', 'unique' => 0),
						'foreign_key' => array('column' => 'foreign_key', 'unique' => 0),
					),
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'),
				),
			),
		),
		'down' => array(
			'drop_table' => array(
				'view_counter_views'
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