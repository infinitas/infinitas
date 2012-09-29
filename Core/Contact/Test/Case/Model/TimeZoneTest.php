<?php
App::uses('TimeZone', 'Contact.Model');

/**
 * TimeZone Test Case
 *
 */
class TimeZoneTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.contact.time_zone'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->TimeZone = ClassRegistry::init('Contact.TimeZone');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->TimeZone);

		parent::tearDown();
	}

	public function testSomething() {

	}

}
