<?php
/* Branch Test cases generated on: 2010-12-14 13:12:22 : 1292334982*/
App::import('Model', 'contact.Branch');

class BranchTestCase extends CakeTestCase {
	function startTest() {
		$this->Branch =& ClassRegistry::init('Branch');
	}

	function endTest() {
		unset($this->Branch);
		ClassRegistry::flush();
	}

}
?>