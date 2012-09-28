<?php
App::uses('ComponentCollection', 'Controller');
App::uses('Component', 'Controller');
App::uses('InfinitasComponent', 'Libs.Controller/Component');

/**
 * InfinitasComponent Test Case
 *
 */
class InfinitasComponentTest extends CakeTestCase {

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$Collection = new ComponentCollection();
		$this->Infinitas = new InfinitasComponent($Collection);
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Infinitas);

		parent::tearDown();
	}

/**
 * testChangePaginationLimit method
 *
 * @return void
 */
	public function testChangePaginationLimit() {
	}

/**
 * testPaginationHardLimit method
 *
 * @return void
 */
	public function testPaginationHardLimit() {
	}

/**
 * testForceWwwUrl method
 *
 * @return void
 */
	public function testForceWwwUrl() {
	}

/**
 * testGetBrowser method
 *
 * @return void
 */
	public function testGetBrowser() {
	}

/**
 * testGetOperatingSystem method
 *
 * @return void
 */
	public function testGetOperatingSystem() {
	}

/**
 * testGetPluginAssets method
 *
 * @return void
 */
	public function testGetPluginAssets() {
	}

/**
 * testTreeMove method
 *
 * @return void
 */
	public function testTreeMove() {
	}

/**
 * testOrderedMove method
 *
 * @return void
 */
	public function testOrderedMove() {
	}

/**
 * testAddToPaginationRecall method
 *
 * @return void
 */
	public function testAddToPaginationRecall() {
	}

/**
 * testCheckDbVersion method
 *
 * @return void
 */
	public function testCheckDbVersion() {
	}

}
