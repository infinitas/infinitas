<?php
App::uses('ComponentCollection', 'Controller');
App::uses('Component', 'Controller');
App::uses('InfinitasSecurityComponent', 'Security.Controller/Component');

/**
 * InfinitasSecurityComponent Test Case
 *
 */
class InfinitasSecurityComponentTest extends CakeTestCase {

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$Collection = new ComponentCollection();
		$this->InfinitasSecurity = new InfinitasSecurityComponent($Collection);
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->InfinitasSecurity);

		parent::tearDown();
	}

/**
 * testBadLoginAttempt method
 *
 * @return void
 */
	public function testBadLoginAttempt() {
	}

}
