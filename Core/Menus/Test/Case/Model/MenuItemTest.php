<?php
App::uses('MenuItem', 'Menus.Model');

/**
 * MenuItem Test Case
 *
 */
class MenuItemTestCase extends CakeTestCase {
/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array('plugin.menus.menu_item', 'plugin.menus.menu', 'plugin.users.group');

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->MenuItem = ClassRegistry::init('MenuItem');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->MenuItem);

		parent::tearDown();
	}

/**
 * testValidateEmptyOrCssClass method
 *
 * @return void
 */
	public function testValidateEmptyOrCssClass() {

	}
/**
 * testGetMenu method
 *
 * @return void
 */
	public function testGetMenu() {

	}
/**
 * testHasContainer method
 *
 * @return void
 */
	public function testHasContainer() {

	}
}
