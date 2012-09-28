<?php
App::uses('GlobalLayout', 'Contents.Model');

/**
 * GlobalLayout Test Case
 *
 */
class GlobalLayoutTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.contents.global_layout',
		'plugin.contents.theme',
		'plugin.contents.global_content',
		'plugin.contents.global_category',
		'plugin.contents.group',
		'plugin.contents.view_counter_view',
		'plugin.contents.user',
		'plugin.contents.global_tagged',
		'plugin.contents.global_tag'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->GlobalLayout = ClassRegistry::init('Contents.GlobalLayout');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->GlobalLayout);

		parent::tearDown();
	}

/**
 * testHasLayouts method
 *
 * @return void
 */
	public function testHasLayouts() {
	}

}
