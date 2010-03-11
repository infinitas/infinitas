<?php
/* CoreUser Fixture generated on: 2010-03-11 18:03:02 : 1268325062 */
class UserFixture extends CakeTestFixture {
	var $name = 'User';

	var $table = 'core_users';

	var $fields = array(
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
		'deleted' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'deleted_date' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'username' => array('column' => array('username', 'email'), 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

	var $records = array(
		array(
			'id' => 1,
			'username' => 'testAdmin',
			'email' => 'admin@admin.com',
			'password' => 'b45e7cddbeafb9f619d93fd996c6a78c784e7fb5',
			'birthday' => '2010-02-04',
			'active' => 1,
			'group_id' => 1,
			'ip_address' => '::1',
			'browser' => 'Mozilla 5.0',
			'operating_system' => 'Windows NT',
			'last_login' => '2010-03-10 13:25:53',
			'last_click' => '0000-00-00 00:00:00',
			'country' => 'Unknown',
			'city' => 'TODO',
			'is_mobile' => 0,
			'created' => '2010-02-04 16:54:48',
			'modified' => '2010-02-04 16:54:48',
			'deleted' => 0,
			'deleted_date' => '0000-00-00 00:00:00'
		),
	);
}
?>