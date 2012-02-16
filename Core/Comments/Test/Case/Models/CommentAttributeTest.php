<?php
	/* CommentAttribute Test cases generated on: 2010-12-14 02:12:49 : 1292295409*/
	App::import('model', 'Comments.CommentAttribute');

	class CommentAttributeTestCase extends CakeTestCase {
		function startTest() {
			$this->CommentAttribute = ClassRegistry::init('Comments.CommentAttribute');
		}

		function endTest() {
			unset($this->CommentAttribute);
			ClassRegistry::flush();
		}

		function testStuff(){
			$this->assertIsA($this->CommentAttribute, 'CommentAttribute');
		}
	}