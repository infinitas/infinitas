<?php
App::uses('View', 'View');
App::uses('Helper', 'View');
App::uses('ModuleLoaderHelper', 'Modules.View/Helper');

/**
 * ModuleLoaderHelper Test Case
 *
 */
class ModuleLoaderHelperTest extends CakeTestCase {

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$View = new View();
		$this->ModuleLoader = new ModuleLoaderHelper($View);
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->ModuleLoader);

		parent::tearDown();
	}

/**
 * testLoad method
 *
 * @return void
 */
	public function testLoad() {
	}

/**
 * testLoadModule method
 *
 * @return void
 */
	public function testLoadModule() {
	}

/**
 * testLoadDirect method
 *
 * @return void
 */
	public function testLoadDirect() {
	}

}
