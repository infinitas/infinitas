<?php
App::uses('InfinitasComment', 'Comments.Model');

class InfinitasCommentTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.configs.config',
		'plugin.themes.theme',
		'plugin.routes.route',
		'plugin.view_counter.view_counter_view',

		'plugin.comments.infinitas_comment',
		'plugin.comments.infinitas_comment_attribute'
	);

/**
 * @brief set up at the start
 */
	public function setUp() {
		parent::setUp();
		$this->Comment = ClassRegistry::init('Comments.InfinitasComment');
	}

/**
 * @brief break down at the end
 */
	public function tearDown() {
		parent::tearDown();
		unset($this->Comment);
	}

	public function testSomething() {
	}
}