<?php
App::uses('View', 'View');
App::uses('Helper', 'View');
App::uses('GlobalContentsHelper', 'Contents.View/Helper');

/**
 * GlobalContentsHelper Test Case
 *
 */
class GlobalContentsHelperTest extends CakeTestCase {

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$View = new View();
		$this->GlobalContents = new GlobalContentsHelper($View);
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->GlobalContents);

		parent::tearDown();
	}

/**
 * testAuthor method
 *
 * @return void
 */
	public function testAuthor() {
	}

/**
 * testRenderTemplate method
 *
 * @return void
 */
	public function testRenderTemplate() {
	}

}
