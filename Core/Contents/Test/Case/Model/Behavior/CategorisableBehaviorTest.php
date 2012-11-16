<?php
App::uses('CategorisableBehavior', 'Contents.Model/Behavior');

class CategorisableBehaviorTest extends CakeTestCase {

/**
 * @brief set up at the start
 */
	public function setUp() {
		$this->Categorisable = new CategorisableBehavior();
		parent::setUp();
	}

/**
 * @brief break down at the end
 */
	public function tearDown() {
		unset($this->Categorisable);
		parent::tearDown();
	}

	public function testSomething() {
	}

}