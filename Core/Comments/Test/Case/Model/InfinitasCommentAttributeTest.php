<?php
	/* CommentAttribute Test cases generated on: 2010-12-14 02:12:49 : 1292295409*/
	App::uses('InfinitasCommentAttribute', 'Comments.Model');

	class InfinitasCommentAttributeTest extends CakeTestCase {
		function startTest() {
			$this->CommentAttribute = ClassRegistry::init('Comments.InfinitasCommentAttribute');
		}

		function endTest() {
			unset($this->CommentAttribute);
			ClassRegistry::flush();
		}

		function testStuff(){
			$this->assertInstanceOf('InfinitasCommentAttribute', $this->CommentAttribute);
		}
	}