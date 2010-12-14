<?php
	/* CommentsAppController Test cases generated on: 2010-12-14 02:12:53 : 1292292233*/
	App::import('controller', 'comments.CommentsAppController');

	class TestCommentsAppController extends CommentsAppController {
		var $autoRender = false;

		function redirect($url, $status = null, $exit = true) {
			$this->redirectUrl = $url;
		}
	}

	class CommentsAppControllercontrollerTestCase extends CakeTestCase {
		function startTest() {
			$this->CommentsAppController =& new TestCommentsAppController();
			$this->CommentsAppController->constructClasses();
		}

		function endTest() {
			unset($this->CommentsAppController);
			ClassRegistry::flush();
		}

	}