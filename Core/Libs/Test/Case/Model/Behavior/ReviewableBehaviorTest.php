<?php
App::uses('ReviewableBehavior', 'Libs.Model/Behavior');

/**
 * ReviewableBehavior Test Case
 *
 */
class ReviewableBehaviorTest extends CakeTestCase {

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Reviewable = new ReviewableBehavior();
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Reviewable);

		parent::tearDown();
	}

/**
 * testCreateReview method
 *
 * @return void
 */
	public function testCreateReview() {
	}

/**
 * testGetReviews method
 *
 * @return void
 */
	public function testGetReviews() {
	}

}
