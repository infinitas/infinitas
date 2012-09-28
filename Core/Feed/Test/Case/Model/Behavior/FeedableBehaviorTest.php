<?php
App::uses('FeedableBehavior', 'Feed.Model/Behavior');

/**
 * FeedableBehavior Test Case
 *
 */
class FeedableBehaviorTest extends CakeTestCase {

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Feedable = new FeedableBehavior();
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Feedable);

		parent::tearDown();
	}

}
