<?php
App::uses('GeoLocationTimeZone', 'GeoLocation.Lib');
class GeoLocationTimeZoneTest extends CakeTestCase {

	public function setUp() {
		parent::setUp();
	}
	public function tearDown() {
		parent::tearDown();
	}

/**
 * @brief test counrty data
 *
 * @dataProvider dataProvider
 */
	public function testCountryData($data, $expected) {
		$result = GeoLocationTimeZone::fromIp($data['ip']);
		$this->assertEquals($expected, $result);
	}

	public function dataProvider() {
		return array(
			'empty' => array(
				array(
					'ip' => '127.0.0.1',
					'country_code' => 'ZZZ',
					'region' => 'ZZZ'
				),
				false
			),
			'internal' => array(
				array(
					'ip' => '0.255.255.255',
					'country_code' => '',
					'region' => ''
				),
				false
			),
			'sweden' => array(
				array(
					'ip' => '2.255.255.255',
					'country_code' => 'SE',
					'region' => null
				),
				'Europe/Stockholm'
			),
			'usa' => array(
				array(
					'ip' => '20.255.255.255',
					'country_code' => 'US',
					'region' => 'VA'
				),
				'America/New_York'
			)
		);
	}
}