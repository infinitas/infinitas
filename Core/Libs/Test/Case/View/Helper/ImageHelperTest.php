<?php
App::uses('View', 'View');
App::uses('Helper', 'View');
App::uses('ImageHelper', 'Libs.View/Helper');

/**
 * ImageHelper Test Case
 *
 */
class ImageHelperTest extends CakeTestCase {

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$View = new View();
		$this->Image = new ImageHelper($View);
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Image);

		parent::tearDown();
	}

/**
 * testImage method
 *
 * @return void
 */
	public function testImage() {
	}

/**
 * testFindByExtention method
 *
 * @return void
 */
	public function testFindByExtention() {
	}

/**
 * testFindByChildren method
 *
 * @return void
 */
	public function testFindByChildren() {
	}

/**
 * testGetRelativePath method
 *
 * @return void
 */
	public function testGetRelativePath() {
	}

}
