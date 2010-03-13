<?php
/* Config Test cases generated on: 2010-03-13 11:03:23 : 1268471123*/
App::import('Model', 'management.Config');

class ConfigTestCase extends CakeTestCase {
	function startTest() {
		$this->Config =& ClassRegistry::init('Config');
	}

	function endTest() {
		unset($this->Config);
		ClassRegistry::flush();
	}

}
?>