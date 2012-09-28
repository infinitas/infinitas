<?php
App::uses('ContentableBehavior', 'Contents.Model/Behavior');

/**
 * ContentableBehavior Test Case
 *
 */
class ContentableBehaviorTest extends CakeTestCase {

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Contentable = new ContentableBehavior();
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Contentable);

		parent::tearDown();
	}

/**
 * testContentStopWords method
 *
 * @return void
 */
	public function testContentStopWords() {
	}

/**
 * testMainKeywords method
 *
 * @return void
 */
	public function testMainKeywords() {
	}

/**
 * testGetContentId method
 *
 * @return void
 */
	public function testGetContentId() {
	}

/**
 * testHasLayouts method
 *
 * @return void
 */
	public function testHasLayouts() {
	}

}
