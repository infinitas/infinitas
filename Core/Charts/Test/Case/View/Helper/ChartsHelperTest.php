<?php
App::uses('ChartsHelper', 'Charts.View/Helper');
App::uses('View', 'View');
App::uses('Controller', 'Controller');

class ChartsHelperTest extends CakeTestCase {
/**
 * @brief set up at the start
 */
	public function setUp() {
		parent::setUp();
		$this->Charts = new ChartsHelper(new View(new Controller()));
	}

/**
 * @brief break down at the end
 */
	public function tearDown() {
		parent::tearDown();
		unset($this->Charts);
	}

	public function testSomething() {

	}

}