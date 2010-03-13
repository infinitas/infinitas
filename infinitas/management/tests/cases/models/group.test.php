<?php
/* Group Test cases generated on: 2010-03-13 11:03:33 : 1268471133*/
App::import('Model', 'management.Group');

class GroupTestCase extends CakeTestCase {
	function startTest() {
		$this->Group =& ClassRegistry::init('Group');
	}

	function endTest() {
		unset($this->Group);
		ClassRegistry::flush();
	}

}
?>