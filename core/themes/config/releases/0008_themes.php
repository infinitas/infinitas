<?php
class R4c8fccf730284a61ae5b58946318cd70 extends CakeRelease {

/**
 * Migration description
 *
 * @var string
 * @access public
 */
	public $description = 'Migration for Themes version 0.8';

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
			'create_table' => array(
				'themes' => array(
					'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
					'name' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100),
					'description' => array('type' => 'text', 'null' => true, 'default' => NULL),
					'author' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 150),
					'url' => array('type' => 'string', 'null' => true, 'default' => NULL),
					'update_url' => array('type' => 'string', 'null' => true, 'default' => NULL),
					'licence' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100),
					'active' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
					'core' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
					'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
					'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
					),
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'),
				),
			),
		),
		'down' => array(
			'drop_table' => array(
				'themes'
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
		'Theme' => array(
			array(
				'id' => 1,
				'name' => 'default',
				'description' => 'This is the default infinitas theme',
				'author' => 'Infinitas',
				'url' => '',
				'update_url' => '',
				'licence' => '',
				'active' => 0,
				'core' => 1,
				'created' => '2010-01-14 01:39:54',
				'modified' => '2010-01-14 01:39:57'
			),
			array(
				'id' => 2,
				'name' => 'terrafirma',
				'description' => '',
				'author' => '',
				'url' => '',
				'update_url' => '',
				'licence' => '',
				'active' => 0,
				'core' => 0,
				'created' => '0000-00-00 00:00:00',
				'modified' => '0000-00-00 00:00:00'
			),
			array(
				'id' => 3,
				'name' => 'aqueous',
				'description' => 'A blue 3 col layout',
				'author' => 'Six Shooter Media\r\n',
				'url' => '',
				'update_url' => '',
				'licence' => '',
				'active' => 0,
				'core' => 0,
				'created' => '0000-00-00 00:00:00',
				'modified' => '2010-09-14 00:09:19'
			),
			array(
				'id' => 4,
				'name' => 'aqueous_light',
				'description' => 'aqueous_light',
				'author' => '',
				'url' => '',
				'update_url' => '',
				'licence' => '',
				'active' => 1,
				'core' => 0,
				'created' => '0000-00-00 00:00:00',
				'modified' => '2010-09-14 00:09:40'
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