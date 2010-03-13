<?php
/* Route Test cases generated on: 2010-03-13 11:03:13 : 1268471233*/
App::import('Model', 'management.Route');

class RouteTestCase extends CakeTestCase {
	function startTest() {
		$this->Route =& ClassRegistry::init('Route');
	}

	function endTest() {
		unset($this->Route);
		ClassRegistry::flush();
	}

}
?>