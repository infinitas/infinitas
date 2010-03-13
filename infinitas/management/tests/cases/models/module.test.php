<?php
/* Module Test cases generated on: 2010-03-13 11:03:28 : 1268471188*/
App::import('Model', 'management.Module');

class ModuleTestCase extends CakeTestCase {
	function startTest() {
		$this->Module =& ClassRegistry::init('Module');
	}

	function endTest() {
		unset($this->Module);
		ClassRegistry::flush();
	}

}
?>