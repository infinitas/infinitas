<?php
/* Address Test cases generated on: 2010-03-13 11:03:14 : 1268471054*/
App::uses('ContactAddress', 'Contact.Model');

class ContactAddressTest extends CakeTestCase {

	function startTest() {
		$this->ContactAddress = ClassRegistry::init('Contact.ContactAddress');
	}

	function testDummy() {}

	function endTest() {
		unset($this->ContactAddress);
		ClassRegistry::flush();
	}

}