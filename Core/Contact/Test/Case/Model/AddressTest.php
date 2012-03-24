<?php
/* Address Test cases generated on: 2010-03-13 11:03:14 : 1268471054*/
App::uses('Address', 'Contact.Model');

class AddressTest extends CakeTestCase {

	function startTest() {
		$this->Address = ClassRegistry::init('Contact.Address');
	}

	function testDummy() {}

	function endTest() {
		unset($this->Address);
		ClassRegistry::flush();
	}

}