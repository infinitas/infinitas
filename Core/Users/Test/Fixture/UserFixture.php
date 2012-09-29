<?php
/**
 * @brief fixture file for User tests.
 *
 * @package Users.Fixture
 * @since 0.9b1
 */
class UserFixture extends CakeTestFixture {
	public $name = 'User';
	public $table = 'core_users';

	public $fields = array(
		'id' => array('type' => 'string', 'null' => false, 'default' => null, 'key' => 'primary', 'length' => 36, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'username' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 50, 'key' => 'unique', 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'email' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 100, 'key' => 'unique', 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'password' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 40, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'birthday' => array('type' => 'date', 'null' => true, 'default' => null),
		'active' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'group_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'ip_address' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'browser' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'operating_system' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 50, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'last_login' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'last_click' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'country' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 150, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'city' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 150, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'is_mobile' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'facebook_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 20),
		'twitter_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 20, 'key' => 'index'),
		'time_zone' => array('type' => 'string', 'null' => false, 'default' => 'UTC', 'length' => 50, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'full_name' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'prefered_name' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'username' => array('column' => 'username', 'unique' => 1),
			'email' => array('column' => 'email', 'unique' => 1),
			'twitter_id' => array('column' => 'twitter_id', 'unique' => 0)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

	public $records = array(
		array(
			'id' => 1,
			'username' => 'admin',
			'email' => 'admin@admin.com',
			'password' => 'b45e7cddbeafb9f619d93fd996c6a78c784e7fb5',
			'birthday' => '2010-02-04',
			'active' => 1,
			'group_id' => 1,
			'ip_address' => '127.0.0.1',
			'browser' => 'Mozilla 5.0',
			'operating_system' => 'Windows NT',
			'last_login' => '2010-08-16 10:49:19',
			'last_click' => '0000-00-00 00:00:00',
			'country' => 'Unknown',
			'city' => '',
			'is_mobile' => 0,
			'created' => '2010-02-04 16:54:48',
			'modified' => '2010-02-04 16:54:48',
			'facebook_id' => null,
			'twitter_id' => null,
			'time_zone' => 'UTC',
			'full_name' => 'Admin Guy',
			'prefered_name' => null
		),
		array(
			'id' => 15,
			'username' => 'asdf',
			'email' => 'dogmatic@test.com',
			'password' => 'cb0b7000df8e143bfc4c4cb6d6f4d88e693b1234',
			'birthday' => '2010-05-15',
			'active' => 1,
			'group_id' => 1,
			'ip_address' => '',
			'browser' => '',
			'operating_system' => '',
			'last_login' => '0000-00-00 00:00:00',
			'last_click' => '0000-00-00 00:00:00',
			'country' => '',
			'city' => '',
			'is_mobile' => 0,
			'created' => '2010-05-15 14:35:32',
			'modified' => '2010-05-15 14:36:19',
			'facebook_id' => null,
			'twitter_id' => null,
			'time_zone' => 'UTC',
			'full_name' => null,
			'prefered_name' => 'dogmatic69'
		),
	);
}