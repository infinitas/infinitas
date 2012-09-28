<?php
App::uses('ViewableReportingBehavior', 'ViewCounter.Model/Behavior');

/**
 * ViewableReportingBehavior Test Case
 *
 */
class ViewableReportingBehaviorTest extends CakeTestCase {

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->ViewableReporting = new ViewableReportingBehavior();
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->ViewableReporting);

		parent::tearDown();
	}

/**
 * testGetMostViewed method
 *
 * @return void
 */
	public function testGetMostViewed() {
	}

/**
 * testGetToalViews method
 *
 * @return void
 */
	public function testGetToalViews() {
	}

}
