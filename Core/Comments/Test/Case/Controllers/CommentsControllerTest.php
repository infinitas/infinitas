<?php
	/* Comments Test cases generated on: 2010-12-14 02:12:13 : 1292293453*/
	App::import('Controller', 'Comments.Comments');

	class TestCommentsController extends CommentsController {
		var $autoRender = false;

		function redirect($url, $status = null, $exit = true) {
			$this->redirectUrl = $url;
		}
	}

	class CategoriesControllerTestCase extends CakeTestCase {
		function startTest() {
			$this->Comments = new TestCommentsController();
			$this->Comments->constructClasses();
		}

		function endTest() {
			unset($this->Comments);
			ClassRegistry::flush();
		}
	}