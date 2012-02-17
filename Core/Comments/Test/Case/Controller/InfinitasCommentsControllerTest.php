<?php
	/* Comments Test cases generated on: 2010-12-14 02:12:13 : 1292293453*/
	App::uses('InfinitasCommentsController', 'Comments.Controller');

	class TestCommentsController extends InfinitasCommentsController {
		var $autoRender = false;

		function redirect($url, $status = null, $exit = true) {
			$this->redirectUrl = $url;
		}
	}

	class InfinitasCommentsControllerTest extends CakeTestCase {

		/**
		 * fixtures property
		 *
		 * @var array
		*/
		public $fixtures = array('plugin.configs.config');

		function startTest() {
			$this->Comments = new TestCommentsController();
			$this->Comments->constructClasses();
		}

		function testDummy() {}

		function endTest() {
			unset($this->Comments);
			ClassRegistry::flush();
		}
	}