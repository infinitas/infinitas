<?php
class R4ce16995f0904ffe81861e806318cd70 extends CakeRelease {

/**
 * Migration description
 *
 * @var string
 * @access public
 */
	public $description = 'Migration for ViewCounter version 0.8';

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
				'view_counts' => array(
					'id' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 36, 'key' => 'primary', 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'model' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 50, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'foreign_key' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 36, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'user_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 10),
					'ip_address' => array('type' => 'string', 'null' => false, 'default' => '?', 'length' => 15, 'key' => 'index', 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'year' => array('type' => 'text', 'null' => false, 'default' => NULL, 'length' => 4),
					'month' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 4),
					'day' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 4),
					'hour' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 4),
					'week_of_year' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 4),
					'day_of_year' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 4),
					'day_of_week' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 1, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'continent_code' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 5, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'country_code' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 2, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'country' => array('type' => 'string', 'null' => false, 'default' => 'Unknown', 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'city' => array('type' => 'string', 'null' => false, 'default' => 'Unknown', 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
						'ip_address' => array('column' => 'ip_address', 'unique' => 0),
					),
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'),
				),
			),
		),
		'down' => array(
			'drop_table' => array(
				'view_counts'
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