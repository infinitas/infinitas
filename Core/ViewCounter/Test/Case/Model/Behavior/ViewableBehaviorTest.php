<?php
App::uses('ViewableBehavior', 'ViewCounter.Model/Behavior');

/**
 * ViewableBehavior Test Case
 *
 */
class ViewableBehaviorTest extends CakeTestCase {

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Viewable = new ViewableBehavior();
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Viewable);

		parent::tearDown();
	}

	public function testSomething() {

	}

}
