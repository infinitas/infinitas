<?php
/**
 * @brief fixture file for GeoLocationCountry tests.
 *
 * @package .Fixture
 * @since 0.9b1
 */
class GeoLocationCountryFixture extends CakeTestFixture {
	public $name = 'GeoLocationCountry';

	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'name' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 128, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'code_2' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 2, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'code_3' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 3, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'format' => array('type' => 'text', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'postcode_required' => array('type' => 'boolean', 'null' => false, 'default' => null),
		'postcode_regex' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'main' => array('type' => 'boolean', 'null' => false, 'default' => null),
		'active' => array('type' => 'boolean', 'null' => false, 'default' => '1'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

	public $records = array(
		array(
			'id' => 1,
			'name' => 'UK',
			'code_2' => 'uk',
			'code_3' => 'ukk',
			'format' => '{name}\r\n{address_1}\r\n{address_2}',
			'postcode_required' => 1,
			'postcode_regex' => '[a-z0-9]{2}',
			'main' => 1,
			'active' => 1
		),
		array(
			'id' => 2,
			'name' => 'GB',
			'code_2' => 'GB',
			'code_3' => 'GB',
			'format' => '',
			'postcode_required' => 0,
			'postcode_regex' => '',
			'main' => 0,
			'active' => 1
		),
		array(
			'id' => 3,
			'name' => 'inactive',
			'code_2' => 'in',
			'code_3' => 'ina',
			'format' => '',
			'postcode_required' => 0,
			'postcode_regex' => '',
			'main' => 0,
			'active' => 0
		),
	);
}