<?php
App::uses('CakeSchema', 'Installer.Model');

/**
 * CakeSchema Test Case
 *
 */
class CakeSchemaTest extends CakeTestCase {

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->CakeSchema = ClassRegistry::init('Installer.CakeSchema');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->CakeSchema);

		parent::tearDown();
	}

/**
 * testBuild method
 *
 * @return void
 */
	public function testBuild() {
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
 * testLoad method
 *
 * @return void
 */
	public function testLoad() {
	}

/**
 * testRead method
 *
 * @return void
 */
	public function testRead() {
	}

/**
 * testWrite method
 *
 * @return void
 */
	public function testWrite() {
	}

/**
 * testGenerateTable method
 *
 * @return void
 */
	public function testGenerateTable() {
	}

/**
 * testCompare method
 *
 * @return void
 */
	public function testCompare() {
	}

}
