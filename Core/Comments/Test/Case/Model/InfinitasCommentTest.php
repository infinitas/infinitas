<?php
/* Comment Test cases generated on: 2010-12-14 02:12:15 : 1292293635*/
	App::uses('InfinitasComment', 'Comments.Model');

	class InfinitasCommentTest extends CakeTestCase {
		public $fixtures = array(
			'plugin.configs.config',
			'plugin.themes.theme',
			'plugin.routes.route',
			'plugin.view_counter.view_counter_view',

			'plugin.comments.infinitas_comment',
			'plugin.comments.infinitas_comment_attribute'
		);

		public function startTest() {
			$this->Comment = ClassRegistry::init('Comments.InfinitasComment');
		}

		public function endTest() {
			unset($this->Comment);
			ClassRegistry::flush();
		}

		public function testStuff(){
			$this->assertInstanceOf('InfinitasComment', $this->Comment);
		}
	}