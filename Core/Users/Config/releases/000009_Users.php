<?php
	/**
	 * Infinitas Releas
	 *
	 * Auto generated database update
	 */
	 
	class R4f563536d34048f4a57243556318cd70 extends CakeRelease {

	/**
	* Migration description
	*
	* @var string
	* @access public
	*/
		public $description = 'Migration for Users version 0.9';

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
					'core_groups' => array(
						'id' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 36, 'key' => 'primary', 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
						'name' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 50, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
						'description' => array('type' => 'text', 'null' => false, 'default' => NULL, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
						'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
						'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
						'parent_id' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'key' => 'index'),
						'lft' => array('type' => 'integer', 'null' => false, 'default' => NULL),
						'rght' => array('type' => 'integer', 'null' => false, 'default' => NULL),
						'indexes' => array(
							'PRIMARY' => array('column' => 'id', 'unique' => 1),
							'parent_id' => array('column' => 'parent_id', 'unique' => 0),
						),
						'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'),
					),
					'core_users' => array(
						'id' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 36, 'key' => 'primary', 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
						'username' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 50, 'key' => 'unique', 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
						'email' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100, 'key' => 'unique', 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
						'password' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 40, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
						'facebook_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 20),
						'twitter_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 20, 'key' => 'index'),
						'birthday' => array('type' => 'date', 'null' => true, 'default' => NULL),
						'active' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
						'group_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
						'ip_address' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
						'browser' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
						'operating_system' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 50, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
						'last_login' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
						'last_click' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
						'country' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 150, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
						'city' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 150, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
						'is_mobile' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
						'created' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
						'modified' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
						'time_zone' => array('type' => 'string', 'null' => false, 'default' => 'UTC', 'length' => 50, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
						'full_name' => array('type' => 'string', 'null' => true, 'default' => NULL, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
						'prefered_name' => array('type' => 'string', 'null' => true, 'default' => NULL, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
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
					'core_groups', 'core_users'
				),
			),
		);
		
		public $fixtures = array(
			'core' => array(
				'Group' => array(
					array(
						'id' => 1,
						'name' => 'Administrators',
						'description' => 'Site Administrators',
						'parent_id' => null,
						'lft' => 1,
						'rght' => 2,
					),
					array(
						'id' => 2,
						'name' => 'Users',
						'description' => 'Users',
						'parent_id' => null,
						'lft' => 3,
						'rght' => 4,
					)
				)
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