<?php
/* Group Test cases generated on: 2010-03-13 11:03:33 : 1268471133*/
App::uses('Group', 'Users.Model');

class GroupTestCase extends CakeTestCase {
	function startTest() {
		$this->Group =& ClassRegistry::init('Users.Group');
	}

	function testDummy() {}

	function endTest() {
		unset($this->Group);
		ClassRegistry::flush();
	}

}
?>