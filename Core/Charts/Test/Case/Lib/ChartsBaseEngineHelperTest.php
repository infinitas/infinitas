<?php
App::uses('ChartsBaseEngineHelper', 'Charts.Lib');
App::uses('View', 'View');
App::uses('Controller', 'Controller');

class ChartsBaseEngineHelperTest extends CakeTestCase {

/**
 * @brief set up at the start
 */
	public function setUp() {
		parent::setUp();
		$this->BaseEngine = new ChartsBaseEngineHelper(new View(new Controller()));
	}

/**
 * @brief break down at the end
 */
	public function tearDown() {
		parent::tearDown();
		unset($this->BaseEngine);
	}

	public function testSomething() {

	}

}