<?php
App::uses('GeoLocationTimeZone', 'GeoLocation.Lib');
class GeoLocationTimeZoneTest extends CakeTestCase {
/**
 * test counrty data
 *
 * @dataProvider dataProvider
 */
	public function testFromIp($data, $expected) {
		$IpLocation = new IpLocation();
		$this->skipif (!$IpLocation->hasCityData());
		$result = GeoLocationTimeZone::fromIp($data['ip']);
		$this->assertEquals($expected, $result);
	}

/**
 * test counrty data
 *
 * @dataProvider dataProvider
 */
	public function testFromCountry($data, $expected) {
		$result = GeoLocationTimeZone::fromCountry($data['country_code'], $data['region']);
		$this->assertEquals($expected, $result);
	}

/**
 * data provider for tests
 *
 * @return array
 */
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