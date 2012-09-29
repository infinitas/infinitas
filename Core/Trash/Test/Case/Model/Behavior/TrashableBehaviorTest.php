<?php
App::uses('TrashableBehavior', 'Trash.Model/Behavior');

/**
 * TrashableBehavior Test Case
 *
 */
class TrashableBehaviorTest extends CakeTestCase {

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Trashable = new TrashableBehavior();
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Trashable);

		parent::tearDown();
	}

	public function testSomething() {

	}

}
