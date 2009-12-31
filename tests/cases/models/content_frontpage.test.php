<?php
/* ContentFrontpage Test cases generated on: 2009-12-13 19:12:29 : 1260726929*/
App::import('Model', 'ContentFrontpage');

class ContentFrontpageTestCase extends CakeTestCase {
	var $fixtures = array('app.content_frontpage', 'app.content', 'app.category', 'app.section');

	function startTest() {
		$this->ContentFrontpage =& ClassRegistry::init('ContentFrontpage');
	}

	function endTest() {
		unset($this->ContentFrontpage);
		ClassRegistry::flush();
	}

}
?>