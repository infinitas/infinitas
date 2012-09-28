<?php
App::uses('PublishableBehavior', 'Libs.Model/Behavior');

/**
 * PublishableBehavior Test Case
 *
 */
class PublishableBehaviorTest extends CakeTestCase {

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Publishable = new PublishableBehavior();
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Publishable);

		parent::tearDown();
	}

/**
 * testPublishConditions method
 *
 * @return void
 */
	public function testPublishConditions() {
	}

/**
 * testIsPublished method
 *
 * @return void
 */
	public function testIsPublished() {
	}

/**
 * testPublish method
 *
 * @return void
 */
	public function testPublish() {
	}

/**
 * testUnpublish method
 *
 * @return void
 */
	public function testUnpublish() {
	}

}
