<?php
/* Vcf Test cases generated on: 2010-12-14 13:12:02 : 1292334962*/
App::uses('VcfHelper', 'Contact.View/Helper');
App::uses('View', 'View');
App::uses('Controller', 'Controller');

class VcfHelperTest extends CakeTestCase {
	function startTest() {
		$this->Vcf = new VcfHelper(new View(new Controller()));
	}

	function testDummy() {}

	function endTest() {
		unset($this->Vcf);
		ClassRegistry::flush();
	}

}