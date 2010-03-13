<?php
/* Page Test cases generated on: 2010-03-13 11:03:01 : 1268471221*/
App::import('Model', 'management.Page');

class PageTestCase extends CakeTestCase {
	function startTest() {
		$this->Page =& ClassRegistry::init('Page');
	}

	function endTest() {
		unset($this->Page);
		ClassRegistry::flush();
	}

}
?>