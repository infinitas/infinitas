<?php
App::uses('ComponentCollection', 'Controller');
App::uses('Component', 'Controller');
App::uses('MassActionComponent', 'Libs.Controller/Component');

/**
 * MassActionComponent Test Case
 *
 */
class MassActionComponentTest extends CakeTestCase {

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$Collection = new ComponentCollection();
		$this->MassAction = new MassActionComponent($Collection);
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->MassAction);

		parent::tearDown();
	}

/**
 * testActionAdminMass method
 *
 * @return void
 */
	public function testActionAdminMass() {
	}

/**
 * testGetIds method
 *
 * @return void
 */
	public function testGetIds() {
	}

/**
 * testGetAction method
 *
 * @return void
 */
	public function testGetAction() {
	}

/**
 * testFilter method
 *
 * @return void
 */
	public function testFilter() {
	}

/**
 * testDelete method
 *
 * @return void
 */
	public function testDelete() {
	}

/**
 * testToggle method
 *
 * @return void
 */
	public function testToggle() {
	}

/**
 * testCopy method
 *
 * @return void
 */
	public function testCopy() {
	}

/**
 * testMove method
 *
 * @return void
 */
	public function testMove() {
	}

/**
 * testGeneric method
 *
 * @return void
 */
	public function testGeneric() {
	}

}
