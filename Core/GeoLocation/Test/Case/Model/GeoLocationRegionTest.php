<?php
App::uses('GeoLocationRegion', 'GeoLocation.Model');

/**
 * GeoLocationRegion Test Case
 *
 */
class GeoLocationRegionTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.geo_location.geo_location_region',
		'plugin.geo_location.geo_location_country'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->GeoLocationRegion = ClassRegistry::init('GeoLocation.GeoLocationRegion');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->GeoLocationRegion);

		parent::tearDown();
	}

/**
 * testGetViewData method
 *
 * @return void
 */
	public function testGetViewData() {
	}

}
