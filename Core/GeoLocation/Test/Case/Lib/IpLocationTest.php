<?php
App::uses('IpLocation', 'GeoLocation.Lib');
class IpLocationTest extends CakeTestCase {

	public function setUp() {
		parent::setUp();
		$this->IpLocation = new IpLocation();
	}
	public function tearDown() {
		parent::tearDown();

		unset($this->IpLocation);
	}

/**
 * @brief test counrty data
 *
 * @dataProvider dataProvider
 */
	public function testCountryData($data, $expected) {
		$result = $this->IpLocation->getCountryData($data['ip'], $data['code']);
		$this->assertEquals($expected['country'], $result);
	}

/**
 * @brief test city data
 *
 * @dataProvider dataProvider
 */
	public function testCityData($data, $expected) {
		$result = $this->IpLocation->getCityData($data['ip'], $data['code']);
		$this->assertEquals($expected['city'], $result);
	}

/**
 * @brief data provider for ip -> location
 * @return type
 */
	public function dataProvider() {
		return array(
			'localhost' => array(
				array('ip' => '127.0.0.1', 'code' => false),
				array(
					'country' => array(
						'country' => 'localhost',
						'country_code' => '',
						'country_id' => 0,
						'city' => 'localhost',
						'ip_address' => '127.0.0.1'
					),
					'city' => array(
						'country' => 'localhost',
						'country_code' => '',
						'country_id' => 0,
						'city' => 'localhost',
						'ip_address' => '127.0.0.1',
						'country_code3' => null,
						'region' => null,
						'postal_code' => null,
						'latitude' => null,
						'longitude' => null,
						'area_code' => null,
						'dma_code' => null,
						'metro_code' => null,
						'continent_code' => null
					)
				)
			),
			'internal' => array(
				array('ip' => '10.255.255.255', 'code' => false),
				array(
					'country' => array(
						'country' => '',
						'country_code' => '',
						'country_id' => 0,
						'city' => null,
						'ip_address' => '10.255.255.255'
					),
					'city' => array(
						'country' => '',
						'country_code' => '',
						'country_id' => 0,
						'city' => null,
						'ip_address' => '10.255.255.255',
						'country_code3' => null,
						'region' => null,
						'postal_code' => null,
						'latitude' => null,
						'longitude' => null,
						'area_code' => null,
						'dma_code' => null,
						'metro_code' => null,
						'continent_code' => null
					)
				)
			),
			'sweden' => array(
				array('ip' => '2.255.255.255', 'code' => false),
				array(
					'country' => array(
						'country' => 'Sweden',
						'country_code' => 'SE',
						'country_id' => 191,
						'city' => null,
						'ip_address' => '2.255.255.255'
					),
					'city' => array(
						'country' => 'Sweden',
						'country_code' => 'SE',
						'country_id' => 191,
						'city' => null,
						'ip_address' => '2.255.255.255',
						'country_code3' => 'SWE',
						'region' => null,
						'postal_code' => null,
						'latitude' => 62,
						'longitude' => 15,
						'area_code' => null,
						'dma_code' => null,
						'metro_code' => null,
						'continent_code' => 'EU'
					)
				)
			),
			'usa' => array(
				array('ip' => '20.255.255.255', 'code' => false),
				array(
					'country' => array(
						'country' => 'United States',
						'country_code' => 'US',
						'country_id' => 225,
						'city' => null,
						'ip_address' => '20.255.255.255'
					),
					'city' => array(
						'country' => 'United States',
						'country_code' => 'US',
						'country_id' => 225,
						'city' => 'Falls Church',
						'ip_address' => '20.255.255.255',
						'country_code3' => 'USA',
						'region' => 'VA',
						'postal_code' => '22042',
						'latitude' => 38.864,
						'longitude' => -77.1922,
						'area_code' => 703,
						'dma_code' => 511.0,
						'metro_code' => 511.0,
						'continent_code' => 'NA'
					)
				)
			)
		);
	}
}
