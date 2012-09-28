<?php
App::uses('GlobalContent', 'Contents.Model');

/**
 * GlobalContent Test Case
 *
 */
class GlobalContentTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.contents.global_content',
		'plugin.contents.global_layout',
		'plugin.contents.theme',
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
		$this->GlobalContent = ClassRegistry::init('Contents.GlobalContent');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->GlobalContent);

		parent::tearDown();
	}

/**
 * testMoveContent method
 *
 * @return void
 */
	public function testMoveContent() {
	}

/**
 * testGetNewContentByMonth method
 *
 * @return void
 */
	public function testGetNewContentByMonth() {
	}

}
