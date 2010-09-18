<?php
/* ContactBranch Fixture generated on: 2010-08-17 14:08:03 : 1282055103 */
class ContactBranchFixture extends CakeTestFixture {
	var $name = 'ContactBranch';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
		'name' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100),
		'slug' => array('type' => 'string', 'null' => false, 'default' => NULL),
		'map' => array('type' => 'string', 'null' => true, 'default' => NULL),
		'image' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 100),
		'email' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100),
		'phone' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 20),
		'fax' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 20),
		'address_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'user_count' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'active' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'ordering' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'time_zone_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

	var $records = array(
		array(
			'id' => 3,
			'name' => 'Head Office',
			'slug' => 'head-office',
			'map' => 'http://osm.org/go/k07zlcCm',
			'image' => 'admin_login.png',
			'email' => 'something@here.com',
			'phone' => '3216549875',
			'fax' => '',
			'address_id' => 1,
			'user_count' => 0,
			'active' => 1,
			'ordering' => 1,
			'time_zone_id' => 0,
			'created' => '2010-02-18 08:07:27',
			'modified' => '2010-02-18 18:52:16'
		),
	);
}
?>