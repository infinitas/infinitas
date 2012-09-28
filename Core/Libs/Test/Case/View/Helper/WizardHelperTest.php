<?php
App::uses('View', 'View');
App::uses('Helper', 'View');
App::uses('WizardHelper', 'Libs.View/Helper');

/**
 * WizardHelper Test Case
 *
 */
class WizardHelperTest extends CakeTestCase {

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$View = new View();
		$this->Wizard = new WizardHelper($View);
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
 * testConfig method
 *
 * @return void
 */
	public function testConfig() {
	}

/**
 * testLink method
 *
 * @return void
 */
	public function testLink() {
	}

/**
 * testStepNumber method
 *
 * @return void
 */
	public function testStepNumber() {
	}

/**
 * testActiveStep method
 *
 * @return void
 */
	public function testActiveStep() {
	}

/**
 * testProgressMenu method
 *
 * @return void
 */
	public function testProgressMenu() {
	}

}
