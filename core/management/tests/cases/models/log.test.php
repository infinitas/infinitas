<?php
/* Log Test cases generated on: 2010-03-13 11:03:55 : 1268471155*/
App::import('Model', 'management.Log');

class LogTestCase extends CakeTestCase {
	function startTest() {
		$this->Log =& ClassRegistry::init('Log');
	}

	function endTest() {
		unset($this->Log);
		ClassRegistry::flush();
	}

}
?>