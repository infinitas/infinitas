<?php
App::uses('View', 'View');
App::uses('Helper', 'View');
App::uses('InfinitasHelper', 'Libs.View/Helper');

/**
 * InfinitasHelper Test Case
 *
 */
class InfinitasHelperTest extends CakeTestCase {

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$View = new View();
		$this->Infinitas = new InfinitasHelper($View);
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
 * testStatus method
 *
 * @return void
 */
	public function testStatus() {
	}

/**
 * testFeatured method
 *
 * @return void
 */
	public function testFeatured() {
	}

/**
 * testLoggedInUserText method
 *
 * @return void
 */
	public function testLoggedInUserText() {
	}

/**
 * testMassActionCheckBox method
 *
 * @return void
 */
	public function testMassActionCheckBox() {
	}

/**
 * testHasPlugin method
 *
 * @return void
 */
	public function testHasPlugin() {
	}

}
