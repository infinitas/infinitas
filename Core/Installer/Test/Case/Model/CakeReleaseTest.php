<?php
App::uses('CakeRelease', 'Installer.Model');

/**
 * CakeRelease Test Case
 *
 */
class CakeReleaseTest extends CakeTestCase {

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->CakeRelease = ClassRegistry::init('Installer.CakeRelease');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->CakeRelease);

		parent::tearDown();
	}

/**
 * testBefore method
 *
 * @return void
 */
	public function testBefore() {
	}

/**
 * testAfter method
 *
 * @return void
 */
	public function testAfter() {
	}

/**
 * testRun method
 *
 * @return void
 */
	public function testRun() {
	}

/**
 * testGenerateModel method
 *
 * @return void
 */
	public function testGenerateModel() {
	}

}
