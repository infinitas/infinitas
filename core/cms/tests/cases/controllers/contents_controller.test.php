<?php
/* Contents Test cases generated on: 2009-12-13 19:12:00 : 1260726840*/
App::import('Controller', 'Contents');

class TestContentsController extends ContentsController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class ContentsControllerTestCase extends CakeTestCase {
	var $fixtures = array('app.content', 'app.category', 'app.section', 'app.content_frontpage');

	function startTest() {
		$this->Contents =& new TestContentsController();
		$this->Contents->constructClasses();
	}

	function endTest() {
		unset($this->Contents);
		ClassRegistry::flush();
	}

	function testIndex() {

	}

	function testView() {

	}

	function testAdd() {

	}

	function testEdit() {

	}

	function testDelete() {

	}

	function testAdminIndex() {

	}

	function testAdminView() {

	}

	function testAdminAdd() {

	}

	function testAdminEdit() {

	}

	function testAdminDelete() {

	}

}
?>