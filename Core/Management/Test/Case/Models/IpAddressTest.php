<?php
/* IpAddress Test cases generated on: 2010-03-13 11:03:42 : 1268471142*/
App::import('Model', 'management.IpAddress');

class IpAddressTestCase extends CakeTestCase {
	function startTest() {
		$this->IpAddress =& ClassRegistry::init('IpAddress');
	}

	function endTest() {
		unset($this->IpAddress);
		ClassRegistry::flush();
	}

}
?>