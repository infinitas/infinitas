<?php
App::uses('SoftDeletableBehavior', 'Trash.Model/Behavior');

/**
 * SoftDeletableBehavior Test Case
 *
 */
class SoftDeletableBehaviorTest extends CakeTestCase {

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->SoftDeletable = new SoftDeletableBehavior();
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->SoftDeletable);

		parent::tearDown();
	}

/**
 * testSoftDelete method
 *
 * @return void
 */
	public function testSoftDelete() {
	}

/**
 * testCheckResult method
 *
 * @return void
 */
	public function testCheckResult() {
	}

/**
 * testHardDelete method
 *
 * @return void
 */
	public function testHardDelete() {
	}

/**
 * testPurge method
 *
 * @return void
 */
	public function testPurge() {
	}

/**
 * testUndelete method
 *
 * @return void
 */
	public function testUndelete() {
	}

/**
 * testEnableSoftDeletable method
 *
 * @return void
 */
	public function testEnableSoftDeletable() {
	}

}
