<?php
App::uses('SluggableBehavior', 'Libs.Model/Behavior');

/**
 * SluggableBehavior Test Case
 *
 */
class SluggableBehaviorTestCase extends CakeTestCase {
/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Sluggable = new SluggableBehavior();
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Sluggable);

		parent::tearDown();
	}

}
