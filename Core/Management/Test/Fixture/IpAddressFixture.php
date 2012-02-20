<?php
/* CoreIpAddress Fixture generated on: 2010-03-13 11:03:25 : 1268472025 */
class IpAddressFixture extends CakeTestFixture {
	var $name = 'IpAddress';

	var $table = 'core_ip_addresses';

	var $fields = array(
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
		'deleted' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'deleted_date' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

	var $records = array(
	);
}
?>