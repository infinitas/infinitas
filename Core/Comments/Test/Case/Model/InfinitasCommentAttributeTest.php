<?php
App::uses('InfinitasCommentAttribute', 'Comments.Model');

class InfinitasCommentAttributeTest extends CakeTestCase {
	public $fixtures = array(
		'plugin.comments.infinitas_comment',
		'plugin.comments.infinitas_comment_attribute'
	);

/**
 * @brief set up at the start
 */
	public function setUp() {
		parent::setUp();
		$this->CommentAttribute = ClassRegistry::init('Comments.InfinitasCommentAttribute');
	}

/**
 * @brief break down at the end
 */
	public function tearDown() {
		parent::tearDown();
		unset($this->CommentAttribute);
	}

	public function testSomething() {

	}
	
}