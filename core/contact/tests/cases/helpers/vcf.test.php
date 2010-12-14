<?php
/* Vcf Test cases generated on: 2010-12-14 13:12:02 : 1292334962*/
App::import('Helper', 'contact.Vcf');

class VcfHelperTestCase extends CakeTestCase {
	function startTest() {
		$this->Vcf =& new VcfHelper();
	}

	function endTest() {
		unset($this->Vcf);
		ClassRegistry::flush();
	}

}
?>