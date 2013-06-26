<?php
/**
 * @brief fixture file for GeoLocationRegion tests.
 *
 * @package .Fixture
 * @since 0.9b1
 */
class GeoLocationRegionFixture extends CakeTestFixture {
	public $name = 'GeoLocationRegion';

	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'geo_location_country_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'index'),
		'name' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 128, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'code' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 32, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'active' => array('type' => 'boolean', 'null' => false, 'default' => '1'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'country' => array('column' => 'geo_location_country_id', 'unique' => 0)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

	public $records = array(
		array(
			'id' => 1,
			'geo_location_country_id' => 1,
			'name' => 'uk1',
			'code' => 'uk1',
			'active' => 1
		),
		array(
			'id' => 2,
			'geo_location_country_id' => 1,
			'name' => 'uk2',
			'code' => 'uk2',
			'active' => 0
		),
		array(
			'id' => 3,
			'geo_location_country_id' => 2,
			'name' => 'gb1',
			'code' => 'gb1',
			'active' => 1
		),
		array(
			'id' => 4,
			'geo_location_country_id' => 3,
			'name' => 'zzz',
			'code' => 'zzz',
			'active' => 1
		),
	);
}