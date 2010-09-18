<?php
class R4c94edce63b447ddb6b178d86318cd70 extends CakeRelease {

/**
 * Migration description
 *
 * @var string
 * @access public
 */
	public $description = 'Migration for Routes version 0.8';

/**
 * Plugin name
 *
 * @var string
 * @access public
 */
	public $plugin = 'Routes';

/**
 * Actions to be performed
 *
 * @var array $migration
 * @access public
 */
	public $migration = array(
		'up' => array(
			'create_table' => array(
				'routes' => array(
					'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
					'core' => array('type' => 'boolean', 'null' => false, 'default' => NULL),
					'name' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 50),
					'url' => array('type' => 'string', 'null' => false, 'default' => NULL),
					'prefix' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 100),
					'plugin' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 50),
					'controller' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 50),
					'action' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 50),
					'values' => array('type' => 'text', 'null' => false, 'default' => NULL),
					'pass' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 100),
					'rules' => array('type' => 'text', 'null' => false, 'default' => NULL),
					'force_backend' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
					'force_frontend' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
					'order_id' => array('type' => 'integer', 'null' => false, 'default' => '1'),
					'ordering' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'index'),
					'theme_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
					'active' => array('type' => 'boolean', 'null' => false, 'default' => NULL),
					'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
					'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
						'active_routes' => array('column' => array('ordering', 'active', 'theme_id'), 'unique' => 1),
					),
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'),
				),
			),
		),
		'down' => array(
			'drop_table' => array(
				'routes'
			),
		),
	);

/**
 * Fixtures for data
 *
 * @var array $fixtures
 * @access public
 */
	public $fixtures = array(
	'core' => array(
		'Route' => array(
			array(
				'id' => 25,
				'core' => 0,
				'name' => 'Default homepage',
				'url' => '/',
				'prefix' => NULL,
				'plugin' => NULL,
				'controller' => 'pages',
				'action' => 'display',
				'values' => '["home"]',
				'pass' => NULL,
				'rules' => '',
				'force_backend' => 0,
				'force_frontend' => 0,
				'order_id' => 1,
				'ordering' => 0,
				'theme_id' => NULL,
				'active' => 1,
				'created' => NULL,
				'modified' => NULL
			),
			array(
				'id' => 8,
				'core' => 0,
				'name' => 'Pages',
				'url' => '/pages/*',
				'prefix' => '',
				'plugin' => '0',
				'controller' => 'pages',
				'action' => 'display',
				'values' => '',
				'pass' => '',
				'rules' => '',
				'force_backend' => 0,
				'force_frontend' => 0,
				'order_id' => 1,
				'ordering' => 3,
				'theme_id' => 4,
				'active' => 1,
				'created' => '2010-01-13 18:26:36',
				'modified' => '2010-01-14 00:38:53'
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
?>