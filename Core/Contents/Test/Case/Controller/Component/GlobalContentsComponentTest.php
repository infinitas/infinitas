<?php
App::uses('ComponentCollection', 'Controller');
App::uses('Component', 'Controller');
App::uses('GlobalContentsComponent', 'Contents.Controller/Component');

/**
 * GlobalContentsComponent Test Case
 *
 */
class GlobalContentsComponentTest extends CakeTestCase {

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$Collection = new ComponentCollection();
		$this->GlobalContents = new GlobalContentsComponent($Collection);
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->GlobalContents);

		parent::tearDown();
	}

	public function testSomething() {

	}

}
