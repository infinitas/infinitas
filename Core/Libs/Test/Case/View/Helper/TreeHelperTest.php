<?php
App::uses('View', 'View');
App::uses('Helper', 'View');
App::uses('TreeHelper', 'Libs.View/Helper');

/**
 * TreeHelper Test Case
 *
 */
class TreeHelperTest extends CakeTestCase {

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$View = new View();
		$this->Tree = new TreeHelper($View);
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Tree);

		parent::tearDown();
	}

/**
 * testSettings method
 *
 * @return void
 */
	public function testSettings() {
	}

/**
 * testTick method
 *
 * @return void
 */
	public function testTick() {
	}

/**
 * testIsFirstChild method
 *
 * @return void
 */
	public function testIsFirstChild() {
	}

/**
 * testIsLastChild method
 *
 * @return void
 */
	public function testIsLastChild() {
	}

/**
 * testNodeInfo method
 *
 * @return void
 */
	public function testNodeInfo() {
	}

}
