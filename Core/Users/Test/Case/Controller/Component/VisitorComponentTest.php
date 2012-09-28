<?php
App::uses('ComponentCollection', 'Controller');
App::uses('Component', 'Controller');
App::uses('VisitorComponent', 'Users.Controller/Component');

/**
 * VisitorComponent Test Case
 *
 */
class VisitorComponentTest extends CakeTestCase {

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$Collection = new ComponentCollection();
		$this->Visitor = new VisitorComponent($Collection);
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Visitor);

		parent::tearDown();
	}

}
