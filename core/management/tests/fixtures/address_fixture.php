<?php
/* CoreAddress Fixture generated on: 2010-03-13 11:03:09 : 1268471349 */
class AddressFixture extends CakeTestFixture {
	var $name = 'Address';

	var $table = 'core_addresses';

	var $fields = array(
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
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'deleted' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'deleted_date' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

	var $records = array(
		array(
			'id' => 1,
			'name' => 'My Address 1',
			'street' => 'some street',
			'city' => 'some city',
			'province' => 'Some province',
			'postal' => '12345',
			'country_id' => 1,
			'continent_id' => 1,
			'plugin' => 'Core',
			'model' => 'User',
			'created' => '2010-03-01 00:00:00',
			'modified' => '2010-03-01 00:00:00',
			'deleted' => 0,
			'deleted_date' => NULL
		),
	);
}
?>