<?php
App::uses('BigDataBehavior', 'Data.Model/Behavior');

/**
 * BigDataBehavior Test Case
 *
 */
class BigDataBehaviorTest extends CakeTestCase {

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->BigData = new BigDataBehavior();
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->BigData);

		parent::tearDown();
	}

/**
 * testEngineType method
 *
 * @return void
 */
	public function testEngineType() {
	}

/**
 * testDisableIndexing method
 *
 * @return void
 */
	public function testDisableIndexing() {
	}

/**
 * testEnableIndexing method
 *
 * @return void
 */
	public function testEnableIndexing() {
	}

/**
 * testRawSave method
 *
 * @return void
 */
	public function testRawSave() {
	}

}
