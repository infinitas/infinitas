<?php
App::uses('View', 'View');
App::uses('Helper', 'View');
App::uses('MenuHelper', 'Menus.View/Helper');

/**
 * MenuHelper Test Case
 *
 */
class MenuHelperTest extends CakeTestCase {

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$View = new View();
		$this->Menu = new MenuHelper($View);
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Menu);

		parent::tearDown();
	}

	public function testBuilAdminMenu() {
	}

}
