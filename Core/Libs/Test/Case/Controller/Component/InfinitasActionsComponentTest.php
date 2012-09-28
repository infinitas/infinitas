<?php
App::uses('ComponentCollection', 'Controller');
App::uses('Component', 'Controller');
App::uses('InfinitasActionsComponent', 'Libs.Controller/Component');

/**
 * InfinitasActionsComponent Test Case
 *
 */
class InfinitasActionsComponentTest extends CakeTestCase {

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$Collection = new ComponentCollection();
		$this->InfinitasActions = new InfinitasActionsComponent($Collection);
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->InfinitasActions);

		parent::tearDown();
	}

/**
 * testActionAdminGetPlugins method
 *
 * @return void
 */
	public function testActionAdminGetPlugins() {
	}

/**
 * testActionAdminGetControllers method
 *
 * @return void
 */
	public function testActionAdminGetControllers() {
	}

/**
 * testActionAdminGetModels method
 *
 * @return void
 */
	public function testActionAdminGetModels() {
	}

/**
 * testActionAdminGetActions method
 *
 * @return void
 */
	public function testActionAdminGetActions() {
	}

/**
 * testActionAdminGetRecords method
 *
 * @return void
 */
	public function testActionAdminGetRecords() {
	}

/**
 * testActionAdminAdd method
 *
 * @return void
 */
	public function testActionAdminAdd() {
	}

/**
 * testActionAdminEdit method
 *
 * @return void
 */
	public function testActionAdminEdit() {
	}

/**
 * testActionAdminPreview method
 *
 * @return void
 */
	public function testActionAdminPreview() {
	}

/**
 * testActionAdminExport method
 *
 * @return void
 */
	public function testActionAdminExport() {
	}

/**
 * testActionAdminMine method
 *
 * @return void
 */
	public function testActionAdminMine() {
	}

/**
 * testActionAdminReorder method
 *
 * @return void
 */
	public function testActionAdminReorder() {
	}

/**
 * testActionRate method
 *
 * @return void
 */
	public function testActionRate() {
	}

}
