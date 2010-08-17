<?php
/* ContactContact Fixture generated on: 2010-08-17 14:08:06 : 1282055106 */
class ContactContactFixture extends CakeTestFixture {
	var $name = 'ContactContact';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
		'user_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'image' => array('type' => 'string', 'null' => false, 'default' => NULL),
		'first_name' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 50),
		'last_name' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100),
		'slug' => array('type' => 'string', 'null' => false, 'default' => NULL),
		'position' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 100),
		'phone' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 20),
		'mobile' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 20),
		'email' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100),
		'skype' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 50),
		'branch_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'ordering' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'configs' => array('type' => 'text', 'null' => false, 'default' => NULL),
		'active' => array('type' => 'boolean', 'null' => false, 'default' => NULL),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

	var $records = array(
		array(
			'id' => 1,
			'user_id' => 0,
			'image' => 'installer_home.png',
			'first_name' => 'bob',
			'last_name' => 'smith',
			'slug' => '',
			'position' => 'boss',
			'phone' => '3216549875',
			'mobile' => '',
			'email' => '',
			'skype' => 'bobSmith',
			'branch_id' => 3,
			'ordering' => 1,
			'configs' => '',
			'active' => 1,
			'created' => '2010-02-18 08:21:41',
			'modified' => '2010-02-18 08:21:41'
		),
		array(
			'id' => 2,
			'user_id' => 0,
			'image' => 'installer_home-0.png',
			'first_name' => 'asdf',
			'last_name' => 'asdf',
			'slug' => '',
			'position' => 'asdf',
			'phone' => '3216549875',
			'mobile' => '',
			'email' => '',
			'skype' => 'sdf',
			'branch_id' => 3,
			'ordering' => 2,
			'configs' => '',
			'active' => 1,
			'created' => '2010-02-18 08:24:13',
			'modified' => '2010-02-18 08:24:13'
		),
	);
}
?>