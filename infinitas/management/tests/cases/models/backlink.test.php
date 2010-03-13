<?php
/* Backlink Test cases generated on: 2010-03-13 11:03:57 : 1268471097*/
App::import('Model', 'Management.Backlink');

class BacklinkTestCase extends CakeTestCase {
	function startTest() {
		$this->Backlink =& ClassRegistry::init('Backlink');
	}

	function endTest() {
		unset($this->Backlink);
		ClassRegistry::flush();
	}

}
?>