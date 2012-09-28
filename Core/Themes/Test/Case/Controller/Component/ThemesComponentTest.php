<?php
App::uses('ComponentCollection', 'Controller');
App::uses('Component', 'Controller');
App::uses('ThemesComponent', 'Themes.Controller/Component');

/**
 * ThemesComponent Test Case
 *
 */
class ThemesComponentTest extends CakeTestCase {

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$Collection = new ComponentCollection();
		$this->Themes = new ThemesComponent($Collection);
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Themes);

		parent::tearDown();
	}

/**
 * testActionAdminGetThemeLayouts method
 *
 * @return void
 */
	public function testActionAdminGetThemeLayouts() {
	}

}
