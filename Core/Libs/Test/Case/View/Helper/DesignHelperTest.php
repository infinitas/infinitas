<?php
App::uses('View', 'View');
App::uses('Helper', 'View');
App::uses('DesignHelper', 'Libs.View/Helper');

/**
 * DesignHelper Test Case
 *
 */
class DesignHelperTest extends CakeTestCase {

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$View = new View();
		$this->Design = new DesignHelper($View);
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Design);

		parent::tearDown();
	}

/**
 * testArrayToList method
 *
 * @return void
 */
	public function testArrayToList() {
	}

/**
 * testInfoBox method
 *
 * @return void
 */
	public function testInfoBox() {
	}

/**
 * testQuickLink method
 *
 * @return void
 */
	public function testQuickLink() {
	}

/**
 * testTabs method
 *
 * @return void
 */
	public function testTabs() {
	}

}
