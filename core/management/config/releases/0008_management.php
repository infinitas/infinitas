<?php
class R4c8fccf1c5404488836358946318cd70 extends CakeRelease {

/**
 * Migration description
 *
 * @var string
 * @access public
 */
	public $description = 'Migration for Management version 0.8';

/**
 * Plugin name
 *
 * @var string
 * @access public
 */
	public $plugin = 'Management';

/**
 * Actions to be performed
 *
 * @var array $migration
 * @access public
 */
	public $migration = array(
		'up' => array(
			'create_table' => array(
				'addresses' => array(
					'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
					'name' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100),
					'street' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100),
					'city' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100),
					'province' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100),
					'postal' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 10),
					'country_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
					'continent_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
					'plugin' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 50),
					'model' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 50),
					'foreign_key' => array('type' => 'integer', 'null' => false, 'default' => NULL),
					'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
					'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
					),
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'),
				),
				'backups' => array(
					'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
					'plugin' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 50),
					'model' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 50),
					'last_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
					'data' => array('type' => 'text', 'null' => false, 'default' => NULL),
					'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
					),
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'),
				),
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
				'ip_addresses' => array(
					'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
					'ip_address' => array('type' => 'text', 'null' => false, 'default' => NULL),
					'user_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
					'description' => array('type' => 'text', 'null' => false, 'default' => NULL),
					'times_blocked' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
					'unlock_at' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
					'risk' => array('type' => 'integer', 'null' => false, 'default' => '1'),
					'active' => array('type' => 'boolean', 'null' => false, 'default' => '1'),
					'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
					'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
					),
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'),
				),
				'logs' => array(
					'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
					'title' => array('type' => 'string', 'null' => true, 'default' => NULL),
					'description' => array('type' => 'text', 'null' => true, 'default' => NULL),
					'model' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 100),
					'model_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
					'action' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 50),
					'user_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
					'change' => array('type' => 'text', 'null' => true, 'default' => NULL),
					'version_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
					'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
					),
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'),
				),
				'relation_types' => array(
					'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
					'name' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100),
					'type' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100, 'key' => 'unique'),
					'description' => array('type' => 'text', 'null' => false, 'default' => NULL),
					'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
					'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
						'type' => array('column' => 'type', 'unique' => 1),
					),
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'),
				),
				'relations' => array(
					'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
					'name' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100),
					'description' => array('type' => 'text', 'null' => false, 'default' => NULL),
					'plugin' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100),
					'model' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100, 'key' => 'unique'),
					'foreign_key' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 100),
					'conditions' => array('type' => 'text', 'null' => true, 'default' => NULL),
					'fields' => array('type' => 'text', 'null' => true, 'default' => NULL),
					'order' => array('type' => 'text', 'null' => true, 'default' => NULL),
					'dependent' => array('type' => 'boolean', 'null' => true, 'default' => '0'),
					'limit' => array('type' => 'integer', 'null' => true, 'default' => NULL),
					'offset' => array('type' => 'integer', 'null' => true, 'default' => NULL),
					'counter_cache' => array('type' => 'boolean', 'null' => true, 'default' => '0'),
					'counter_scope' => array('type' => 'text', 'null' => false, 'default' => NULL),
					'join_table' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 150),
					'with' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 150),
					'association_foreign_key' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100),
					'unique' => array('type' => 'boolean', 'null' => true, 'default' => '0'),
					'finder_query' => array('type' => 'text', 'null' => false, 'default' => NULL),
					'delete_query' => array('type' => 'text', 'null' => false, 'default' => NULL),
					'insert_query' => array('type' => 'text', 'null' => false, 'default' => NULL),
					'bind' => array('type' => 'boolean', 'null' => false, 'default' => '1'),
					'reverse_association' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
					'type_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
					'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
					'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
						'class_name' => array('column' => 'model', 'unique' => 1),
					),
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'),
				),
				'tickets' => array(
					'id' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 36, 'key' => 'primary'),
					'data' => array('type' => 'text', 'null' => false, 'default' => NULL),
					'expires' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
					),
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'MyISAM'),
				),
				'trash' => array(
					'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
					'model' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100),
					'foreign_key' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 36),
					'name' => array('type' => 'string', 'null' => false, 'default' => NULL),
					'data' => array('type' => 'text', 'null' => false, 'default' => NULL),
					'deleted' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
					'deleted_by' => array('type' => 'integer', 'null' => true, 'default' => NULL),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
					),
					'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'MyISAM'),
				),
				'users' => array(
					'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
					'username' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 20, 'key' => 'index'),
					'email' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100),
					'password' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 40),
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
					'facebook_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 20),
					'twitter_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 20, 'key' => 'index'),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
						'username' => array('column' => array('username', 'email'), 'unique' => 1),
						'twitter_id' => array('column' => 'twitter_id', 'unique' => 0),
					),
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'),
				),
			),
		),
		'down' => array(
			'drop_table' => array(
				'addresses', 'backups', 'groups', 'ip_addresses', 'logs', 'relation_types', 'relations', 'tickets', 'trash', 'users'
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
		'Address' => array(
			array(
				'id' => 1,
				'name' => 'some address',
				'street' => 'some',
				'city' => 'thing',
				'province' => 'goes',
				'postal' => 'here',
				'country_id' => 1,
				'continent_id' => 1,
				'plugin' => '',
				'model' => '',
				'foreign_key' => 0,
				'created' => '0000-00-00 00:00:00',
				'modified' => '0000-00-00 00:00:00'
			),
			array(
				'id' => 2,
				'name' => 'Home',
				'street' => '123 some street',
				'city' => 'Jhb',
				'province' => 'Gauteng',
				'postal' => 'po box 123',
				'country_id' => 1,
				'continent_id' => 1,
				'plugin' => 'management',
				'model' => 'user',
				'foreign_key' => 1,
				'created' => '2010-05-18 00:49:58',
				'modified' => '2010-05-18 00:49:58'
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