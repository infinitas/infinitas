<?php
/* Menu Test cases generated on: 2010-03-13 11:03:04 : 1268471164*/
App::import('Model', 'management.Menu');

class MenuTestCase extends CakeTestCase {
	function startTest() {
		$this->Menu =& ClassRegistry::init('Menu');
	}

	function endTest() {
		unset($this->Menu);
		ClassRegistry::flush();
	}

}
?>