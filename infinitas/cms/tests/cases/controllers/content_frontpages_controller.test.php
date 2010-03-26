<?php
/* ContentFrontpages Test cases generated on: 2009-12-13 19:12:17 : 1260726977*/
App::import('Controller', 'ContentFrontpages');

class TestContentFrontpagesController extends ContentFrontpagesController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class ContentFrontpagesControllerTestCase extends CakeTestCase {
	var $fixtures = array('app.content_frontpage', 'app.content', 'app.category', 'app.section');

	function startTest() {
		$this->ContentFrontpages =& new TestContentFrontpagesController();
		$this->ContentFrontpages->constructClasses();
	}

	function endTest() {
		unset($this->ContentFrontpages);
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