<?php
App::uses('LockableBehavior', 'Locks.Model/Behavior');

/**
 * LockableBehavior Test Case
 *
 */
class LockableBehaviorTest extends CakeTestCase {

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Lockable = new LockableBehavior();
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Lockable);

		parent::tearDown();
	}

/**
 * testUnlock method
 *
 * @return void
 */
	public function testUnlock() {
	}

}
