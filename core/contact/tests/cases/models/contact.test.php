<?php
/* Contact Test cases generated on: 2010-12-14 13:12:18 : 1292334978*/
App::import('Model', 'contact.Contact');

class ContactTestCase extends CakeTestCase {
	function startTest() {
		$this->Contact =& ClassRegistry::init('Contact');
	}

	function endTest() {
		unset($this->Contact);
		ClassRegistry::flush();
	}

}
?>