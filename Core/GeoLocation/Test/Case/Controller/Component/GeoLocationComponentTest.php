<?php
App::uses('ComponentCollection', 'Controller');
App::uses('Component', 'Controller');
App::uses('GeoLocationComponent', 'GeoLocation.Controller/Component');

/**
 * GeoLocationComponent Test Case
 *
 */
class GeoLocationComponentTest extends CakeTestCase {

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$Collection = new ComponentCollection();
		$this->GeoLocation = new GeoLocationComponent($Collection);
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->GeoLocation);

		parent::tearDown();
	}

/**
 * testGetCountryData method
 *
 * @return void
 */
	public function testGetCountryData() {
	}

/**
 * testGetCityData method
 *
 * @return void
 */
	public function testGetCityData() {
	}

}
