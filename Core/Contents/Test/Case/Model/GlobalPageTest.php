<?php
App::uses('GlobalPage', 'Contents.Model');

/**
 * GlobalPage Test Case
 *
 */
class GlobalPageTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.contents.global_page'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->GlobalPage = ClassRegistry::init('Contents.GlobalPage');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->GlobalPage);

		parent::tearDown();
	}

/**
 * testPaginate method
 *
 * @return void
 */
	public function testPaginate() {
	}

/**
 * testPaginateCount method
 *
 * @return void
 */
	public function testPaginateCount() {
	}

}
