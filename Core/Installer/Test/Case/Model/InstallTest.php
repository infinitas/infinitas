<?php
App::uses('Install', 'Installer.Model');

/**
 * Install Test Case
 *
 */
class InstallTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.installer.install'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Install = ClassRegistry::init('Installer.Install');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Install);

		parent::tearDown();
	}

}
