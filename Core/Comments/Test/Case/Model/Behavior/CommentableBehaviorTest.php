<?php
	/* Commentable Test cases generated on: 2010-12-14 02:12:04 : 1292295484*/
	App::uses('CommentableBehavior', 'Comments.Model/Behavior');

	class CommentableBehaviorTest extends CakeTestCase {
		function startTest() {
			$this->Commentable = new CommentableBehavior();
		}

		function testDummy() {}

		function endTest() {
			unset($this->Commentable);
			ClassRegistry::flush();
		}
	}