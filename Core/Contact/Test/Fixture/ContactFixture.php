<?php
/**
 * @brief fixture file for Contact tests.
 *
 * @package Contact.Fixture
 * @since 0.9b1
 */
class ContactFixture extends CakeTestFixture {
	public $name = 'Contact';
	public $table = 'contact_contacts';

	public $fields = array(
		'id' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'key' => 'primary', 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'user_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'image' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'first_name' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 50, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'last_name' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'slug' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'position' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'phone' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 20, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'mobile' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 20, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'email' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'skype' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 50, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'branch_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'ordering' => array('type' => 'integer', 'null' => false, 'default' => null),
		'configs' => array('type' => 'text', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'active' => array('type' => 'boolean', 'null' => false, 'default' => null),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

	public $records = array(
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