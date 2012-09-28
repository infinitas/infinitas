<?php
App::uses('LogableBehavior', 'Libs.Model/Behavior');

/**
 * LogableBehavior Test Case
 *
 */
class LogableBehaviorTest extends CakeTestCase {

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Logable = new LogableBehavior();
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Logable);

		parent::tearDown();
	}

/**
 * testSettings method
 *
 * @return void
 */
	public function testSettings() {
	}

/**
 * testEnableLog method
 *
 * @return void
 */
	public function testEnableLog() {
	}

/**
 * testFindLog method
 *
 * @return void
 */
	public function testFindLog() {
	}

/**
 * testFindUserActions method
 *
 * @return void
 */
	public function testFindUserActions() {
	}

/**
 * testSetUserData method
 *
 * @return void
 */
	public function testSetUserData() {
	}

/**
 * testCustomLog method
 *
 * @return void
 */
	public function testCustomLog() {
	}

/**
 * testClearUserData method
 *
 * @return void
 */
	public function testClearUserData() {
	}

/**
 * testSetUserIp method
 *
 * @return void
 */
	public function testSetUserIp() {
	}

}
