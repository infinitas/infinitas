<?php
App::uses('ComponentCollection', 'Controller');
App::uses('Component', 'Controller');
App::uses('WizardComponent', 'Libs.Controller/Component');

/**
 * WizardComponent Test Case
 *
 */
class WizardComponentTest extends CakeTestCase {

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$Collection = new ComponentCollection();
		$this->Wizard = new WizardComponent($Collection);
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Wizard);

		parent::tearDown();
	}

/**
 * testProcess method
 *
 * @return void
 */
	public function testProcess() {
	}

/**
 * testBranch method
 *
 * @return void
 */
	public function testBranch() {
	}

/**
 * testConfig method
 *
 * @return void
 */
	public function testConfig() {
	}

/**
 * testRead method
 *
 * @return void
 */
	public function testRead() {
	}

/**
 * testRedirect method
 *
 * @return void
 */
	public function testRedirect() {
	}

/**
 * testResetWizard method
 *
 * @return void
 */
	public function testResetWizard() {
	}

/**
 * testReset method
 *
 * @return void
 */
	public function testReset() {
	}

/**
 * testSave method
 *
 * @return void
 */
	public function testSave() {
	}

/**
 * testUnbranch method
 *
 * @return void
 */
	public function testUnbranch() {
	}

}
