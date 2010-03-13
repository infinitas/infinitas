<?php
/* Address Test cases generated on: 2010-03-13 11:03:14 : 1268471054*/
App::import('Model', 'Management.Address');

class AddressTestCase extends CakeTestCase {
	function startTest() {
		$this->Address =& ClassRegistry::init('Address');
	}

	function endTest() {
		unset($this->Address);
		ClassRegistry::flush();
	}

}
?>