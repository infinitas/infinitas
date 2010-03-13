<?php
/* MenuItem Test cases generated on: 2010-03-13 11:03:13 : 1268471173*/
App::import('Model', 'management.MenuItem');

class MenuItemTestCase extends CakeTestCase {
	function startTest() {
		$this->MenuItem =& ClassRegistry::init('MenuItem');
	}

	function endTest() {
		unset($this->MenuItem);
		ClassRegistry::flush();
	}

}
?>