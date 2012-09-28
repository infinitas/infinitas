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

/**
 * testBuilAdminMenu method
 *
 * @return void
 */
	public function testBuilAdminMenu() {
	}

/**
 * testBuilDashboardLinks method
 *
 * @return void
 */
	public function testBuilDashboardLinks() {
	}

/**
 * testNestedList method
 *
 * @return void
 */
	public function testNestedList() {
	}

/**
 * testLink method
 *
 * @return void
 */
	public function testLink() {
	}

}
