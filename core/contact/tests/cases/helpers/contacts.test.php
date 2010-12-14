<?php
/* Contacts Test cases generated on: 2010-12-14 13:12:08 : 1292334968*/
App::import('Helper', 'contact.Contacts');

class ContactsHelperTestCase extends CakeTestCase {
	function startTest() {
		$this->Contacts =& new ContactsHelper();
	}

	function endTest() {
		unset($this->Contacts);
		ClassRegistry::flush();
	}

}
?>