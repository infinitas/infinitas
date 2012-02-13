<?php
/* Comment Test cases generated on: 2010-12-14 02:12:15 : 1292293635*/
	App::import('model', 'Comments.Comment');

	class CommentTestCase extends CakeTestCase {
		var $fixtures = array(
			'plugin.configs.config',
			'plugin.themes.theme',
			'plugin.routes.route',
			'plugin.view_counter.view_count',

			'plugin.comments.comment',
			'plugin.comments.comment_attribute'
		);
		
		function startTest() {
			$this->Comment = ClassRegistry::init('Comments.Comment');
		}

		function endTest() {
			unset($this->Comment);
			ClassRegistry::flush();
		}

		function testStuff(){
			$this->assertIsA($this->Comment, 'Comment');
		}
	}