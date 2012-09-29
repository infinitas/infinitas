<?php
App::uses('ComponentCollection', 'Controller');
App::uses('Component', 'Controller');
App::uses('LockerComponent', 'Locks.Controller/Component');

/**
 * LockerComponent Test Case
 *
 */
class LockerComponentTest extends CakeTestCase {

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$Collection = new ComponentCollection();
		$this->Locker = new LockerComponent($Collection);
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Locker);

		parent::tearDown();
	}

	public function testSomething() {
		
	}

}
