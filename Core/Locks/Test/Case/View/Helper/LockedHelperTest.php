<?php
App::uses('View', 'View');
App::uses('Helper', 'View');
App::uses('LockedHelper', 'Locks.View/Helper');

/**
 * LockedHelper Test Case
 *
 */
class LockedHelperTest extends CakeTestCase {

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$View = new View();
		$this->Locked = new LockedHelper($View);
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Locked);

		parent::tearDown();
	}

/**
 * testDisplay method
 *
 * @return void
 */
	public function testDisplay() {
	}

}
