<?php
/* Contact Test cases generated on: 2010-12-14 13:12:18 : 1292334978*/
App::uses('Contact', 'Contact.Model');

class ContactTest extends CakeTestCase {
	function startTest() {
		$this->Contact =& ClassRegistry::init('Contact.Contact');
	}

	function testDummy() {}

	function endTest() {
		unset($this->Contact);
		ClassRegistry::flush();
	}

}
?>