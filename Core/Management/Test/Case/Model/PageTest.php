<?php
/* Page Test cases generated on: 2010-03-13 11:03:01 : 1268471221*/
App::import('Page', 'Management.Model');

class PageTestCase extends CakeTestCase {
	function startTest() {
		$this->Page =& ClassRegistry::init('Management.Page');
	}

	function testDummy() {}

	function endTest() {
		unset($this->Page);
		ClassRegistry::flush();
	}

}
?>