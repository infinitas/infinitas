<?php
App::uses('View', 'View');
App::uses('Helper', 'View');
App::uses('WysiwygHelper', 'Libs.View/Helper');

/**
 * WysiwygHelper Test Case
 *
 */
class WysiwygHelperTest extends CakeTestCase {

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$View = new View();
		$this->Wysiwyg = new WysiwygHelper($View);
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Wysiwyg);

		parent::tearDown();
	}

/**
 * testLoad method
 *
 * @return void
 */
	public function testLoad() {
	}

/**
 * testText method
 *
 * @return void
 */
	public function testText() {
	}

/**
 * testInput method
 *
 * @return void
 */
	public function testInput() {
	}

}
