<?php
App::uses('View', 'View');
App::uses('Helper', 'View');
App::uses('InfiniTimeHelper', 'Libs.View/Helper');

/**
 * InfiniTimeHelper Test Case
 *
 */
class InfiniTimeHelperTest extends CakeTestCase {

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$View = new View();
		$this->InfiniTime = new InfiniTimeHelper($View);
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->InfiniTime);

		parent::tearDown();
	}

/**
 * testRelativeTime method
 *
 * @return void
 */
	public function testRelativeTime() {
	}

}
