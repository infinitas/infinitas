<?php
App::uses('VcfHelper', 'Contact.View/Helper');
App::uses('View', 'View');
App::uses('Controller', 'Controller');

class VcfHelperTest extends CakeTestCase {

/**
 * @brief set up at the start
 */
	public function setUp() {
		parent::setUp();
		$this->Vcf = new VcfHelper(new View(new Controller()));
	}

/**
 * @brief break down at the end
 */
	public function tearDown() {
		parent::tearDown();
		unset($this->Vcf);
	}

	public function testSomething() {

	}

}