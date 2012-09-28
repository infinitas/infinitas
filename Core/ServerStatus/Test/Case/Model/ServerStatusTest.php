<?php
App::uses('ServerStatus', 'ServerStatus.Model');

/**
 * ServerStatus Test Case
 *
 */
class ServerStatusTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.server_status.server_status'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->ServerStatus = ClassRegistry::init('ServerStatus.ServerStatus');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->ServerStatus);

		parent::tearDown();
	}

/**
 * testReportAllTime method
 *
 * @return void
 */
	public function testReportAllTime() {
	}

/**
 * testReportLastTwoWeeks method
 *
 * @return void
 */
	public function testReportLastTwoWeeks() {
	}

/**
 * testReportLastSixMonths method
 *
 * @return void
 */
	public function testReportLastSixMonths() {
	}

/**
 * testReportByHour method
 *
 * @return void
 */
	public function testReportByHour() {
	}

/**
 * testReportByDay method
 *
 * @return void
 */
	public function testReportByDay() {
	}

}
