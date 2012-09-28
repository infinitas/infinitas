<?php
App::uses('RateableBehavior', 'Libs.Model/Behavior');

/**
 * RateableBehavior Test Case
 *
 */
class RateableBehaviorTest extends CakeTestCase {

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Rateable = new RateableBehavior();
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Rateable);

		parent::tearDown();
	}

/**
 * testRateRecord method
 *
 * @return void
 */
	public function testRateRecord() {
	}

}
