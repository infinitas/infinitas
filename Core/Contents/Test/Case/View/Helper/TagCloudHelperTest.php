<?php
App::uses('View', 'View');
App::uses('Helper', 'View');
App::uses('TagCloudHelper', 'Contents.View/Helper');

/**
 * TagCloudHelper Test Case
 *
 */
class TagCloudHelperTest extends CakeTestCase {

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$View = new View();
		$this->TagCloud = new TagCloudHelper($View);
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->TagCloud);

		parent::tearDown();
	}

/**
 * testDisplay method
 *
 * @return void
 */
	public function testDisplay() {
	}

/**
 * testTagList method
 *
 * @return void
 */
	public function testTagList() {
	}

}
