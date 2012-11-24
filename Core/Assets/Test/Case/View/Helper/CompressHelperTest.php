<?php
App::uses('CompressHelper', 'Assets.View/Helper');
App::uses('View', 'View');
App::uses('Controller', 'Controller');

class CompressHelperTest extends CakeTestCase {

/**
 * @brief set up at the start
 */
	public function setUp() {
		parent::setUp();
		$this->Compress = new CompressHelper(new View(new Controller()));
	}

/**
 * @brief break down at the end
 */
	public function tearDown() {
		parent::tearDown();
		unset($this->Compress);
	}

	public function testSomething() {
	}
}