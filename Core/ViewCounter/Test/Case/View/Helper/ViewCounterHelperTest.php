<?php
App::uses('View', 'View');
App::uses('Helper', 'View');
App::uses('ViewCounterHelper', 'ViewCounter.View/Helper');

/**
 * ViewCounterHelper Test Case
 *
 */
class ViewCounterHelperTest extends CakeTestCase {

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$View = new View();
		$this->ViewCounter = new ViewCounterHelper($View);
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->ViewCounter);

		parent::tearDown();
	}

/**
 * testHeader method
 *
 * @return void
 */
	public function testHeader() {
	}

/**
 * testNoData method
 *
 * @return void
 */
	public function testNoData() {
	}

}
