<?php
class R4c94edce5f344b39992078d86318cd70 extends CakeRelease {

/**
 * Migration description
 *
 * @var string
 * @access public
 */
	public $description = 'Migration for Users version 0.8';

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
			'create_table' => array(
				'groups' => array(
					'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
					'name' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 50),
					'description' => array('type' => 'text', 'null' => false, 'default' => NULL),
					'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
					'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
					'parent_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'index'),
					'lft' => array('type' => 'integer', 'null' => false, 'default' => NULL),
					'rght' => array('type' => 'integer', 'null' => false, 'default' => NULL),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
						'parent_id' => array('column' => 'parent_id', 'unique' => 0),
					),
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'),
				),
				'users' => array(
					'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
					'username' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 20, 'key' => 'unique'),
					'email' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100, 'key' => 'unique'),
					'password' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 40),
					'facebook_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 20),
					'twitter_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 20, 'key' => 'index'),
					'birthday' => array('type' => 'date', 'null' => true, 'default' => NULL),
					'active' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
					'group_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
					'ip_address' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100),
					'browser' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100),
					'operating_system' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 50),
					'last_login' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
					'last_click' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
					'country' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 150),
					'city' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 150),
					'is_mobile' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
					'created' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
					'modified' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
						'username' => array('column' => 'username', 'unique' => 1),
						'email' => array('column' => 'email', 'unique' => 1),
						'twitter_id' => array('column' => 'twitter_id', 'unique' => 0),
					),
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'),
				),
			),
		),
		'down' => array(
			'drop_table' => array(
				'groups', 'users'
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
		'Group' => array(
			array(
				'id' => 1,
				'name' => 'Administrators',
				'description' => 'Site Administrators',
				'created' => '2010-02-04 16:53:33',
				'modified' => '2010-02-04 16:53:33',
				'parent_id' => 0,
				'lft' => 1,
				'rght' => 2
			),
			array(
				'id' => 2,
				'name' => 'Users',
				'description' => '',
				'created' => '2010-02-17 23:14:32',
				'modified' => '2010-02-17 23:14:32',
				'parent_id' => 0,
				'lft' => 3,
				'rght' => 4
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