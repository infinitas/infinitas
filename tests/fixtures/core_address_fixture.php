<?php
/* CoreAddress Fixture generated on: 2010-08-17 14:08:08 : 1282055108 */
class CoreAddressFixture extends CakeTestFixture {
	var $name = 'CoreAddress';

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
		'foreign_key' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

	var $records = array(
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
			'created' => '0000-00-00 00:00:00',
			'modified' => '0000-00-00 00:00:00',
			'foreign_key' => 0
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
			'created' => '2010-05-18 00:49:58',
			'modified' => '2010-05-18 00:49:58',
			'foreign_key' => 1
		),
	);
}
?>