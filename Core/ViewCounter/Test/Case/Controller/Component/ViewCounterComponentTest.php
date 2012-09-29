<?php
App::uses('ComponentCollection', 'Controller');
App::uses('Component', 'Controller');
App::uses('ViewCounterComponent', 'ViewCounter.Controller/Component');

/**
 * ViewCounterComponent Test Case
 *
 */
class ViewCounterComponentTest extends CakeTestCase {

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$Collection = new ComponentCollection();
		$this->ViewCounter = new ViewCounterComponent($Collection);
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

	public function testSomething() {

	}

}
