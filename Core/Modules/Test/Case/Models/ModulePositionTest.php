<?php
/* ModulePosition Test cases generated on: 2010-03-13 11:03:53 : 1268471213*/
App::uses('ModulePosition', 'Modules.Model');

class ModulePositionTestCase extends CakeTestCase {
	function startTest() {
		$this->ModulePosition =& ClassRegistry::init('Modules.ModulePosition');
	}

	function testDummy() {}

	function endTest() {
		unset($this->ModulePosition);
		ClassRegistry::flush();
	}

}
?>