<?php
App::uses('ExpandableBehavior', 'Libs.Model/Behavior');

/**
 * ExpandableBehavior Test Case
 *
 */
class ExpandableBehaviorTest extends CakeTestCase {

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Expandable = new ExpandableBehavior();
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Expandable);

		parent::tearDown();
	}

	public function testSomething() {

	}

}
