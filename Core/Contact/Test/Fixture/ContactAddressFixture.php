<?php
/**
 * fixture file for ContactAddress tests.
 *
 * @package Contact.Fixture
 * @since 0.9b1
 */
class ContactAddressFixture extends CakeTestFixture {

	public $name = 'ContactAddress';

	public $table = 'contact_addresses';

	public $fields = array(
		'id' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'key' => 'primary', 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'name' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'street' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'city' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'province' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'postal' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 10, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'country_id' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'continent_id' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'length' => 2),
		'model' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 50, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'foreign_key' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'latitude' => array('type' => 'float', 'null' => true, 'default' => null, 'length' => '9,6'),
		'longitude' => array('type' => 'float', 'null' => true, 'default' => null, 'length' => '9,6'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

	public $records = array(
		array(
			'id' => 1,
			'name' => 'some address',
			'street' => 'some',
			'city' => 'thing',
			'province' => 'goes',
			'postal' => 'here',
			'country_id' => 1,
			'continent_id' => 1,
			'model' => '',
			'created' => '0000-00-00 00:00:00',
			'modified' => '0000-00-00 00:00:00',
			'foreign_key' => 0,
			'latitude' => null,
			'longitude' => null
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
			'model' => 'user',
			'created' => '2010-05-18 00:49:58',
			'modified' => '2010-05-18 00:49:58',
			'foreign_key' => 1,
			'latitude' => null,
			'longitude' => null
		),
	);
}