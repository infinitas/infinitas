<?php
/* Theme Test cases generated on: 2010-03-13 11:03:20 : 1268471240*/
App::import('Model', 'management.Theme');

class ThemeTestCase extends CakeTestCase {
	function startTest() {
		$this->Theme =& ClassRegistry::init('Theme');
	}

	function endTest() {
		unset($this->Theme);
		ClassRegistry::flush();
	}

}
?>