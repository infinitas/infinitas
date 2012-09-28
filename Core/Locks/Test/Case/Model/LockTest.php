<?php
App::uses('Lock', 'Locks.Model');

/**
 * Lock Test Case
 *
 */
class LockTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.locks.lock',
		'plugin.locks.user',
		'plugin.locks.group'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Lock = ClassRegistry::init('Locks.Lock');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Lock);

		parent::tearDown();
	}

/**
 * testClearOldLocks method
 *
 * @return void
 */
	public function testClearOldLocks() {
	}

}
