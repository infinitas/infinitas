<?php
App::uses('HtmlChartEngineHelper', 'Charts.View/Helper');
App::uses('View', 'View');
App::uses('Controller', 'Controller');

class HtmlChartEngineHelperTest extends CakeTestCase {

/**
 * @brief set up at the start
 */
	public function setUp() {
		parent::setUp();
		$this->HtmlChartEngine = new HtmlChartEngineHelper(new View(new Controller()));
	}

/**
 * @brief break down at the end
 */
	public function tearDown() {
		parent::tearDown();
		unset($this->HtmlChartEngine);
	}

	public function testSomething() {
	}
}