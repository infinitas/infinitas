<?php
App::uses('WebmasterRedirect', 'Webmaster.Model');

/**
 * WebmasterRedirect Test Case
 *
 */
class WebmasterRedirectTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.webmaster.webmaster_redirect'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->WebmasterRedirect = ClassRegistry::init('Webmaster.WebmasterRedirect');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->WebmasterRedirect);

		parent::tearDown();
	}

/**
 * testGetViewData method
 *
 * @return void
 */
	public function testGetViewData() {
	}

}
