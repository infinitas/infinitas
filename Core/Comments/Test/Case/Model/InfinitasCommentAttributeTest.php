<?php
	/* CommentAttribute Test cases generated on: 2010-12-14 02:12:49 : 1292295409*/
	App::uses('InfinitasCommentAttribute', 'Comments.Model');

	class InfinitasCommentAttributeTest extends CakeTestCase {
		public $fixtures = array(
			'plugin.comments.infinitas_comment',
			'plugin.comments.infinitas_comment_attribute'
		);
		public function startTest() {
			$this->CommentAttribute = ClassRegistry::init('Comments.InfinitasCommentAttribute');
		}

		public function endTest() {
			unset($this->CommentAttribute);
			ClassRegistry::flush();
		}

		public function testStuff() {
			$this->assertInstanceOf('InfinitasCommentAttribute', $this->CommentAttribute);
		}
	}