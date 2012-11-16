<?php
App::import('Controller', 'AppController');

class AppControllerTest extends CakeTestCase {
	public $fixtures = array(
		'plugin.configs.config',
		'plugin.management.aco',
		'plugin.management.aro',
		'plugin.management.aros_aco',
	);

/**
 * @brief set up at the start
 */
	public function setUp() {
		parent::setUp();
	}

/**
 * @brief break down at the end
 */
	public function tearDown() {
		parent::tearDown();
	}

	public function testSomething() {

	}
}