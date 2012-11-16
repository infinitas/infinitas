<?php
/* Page Test cases generated on: 2010-03-13 11:03:01 : 1268471221*/
App::import('Page', 'Management.Model');

class PageTestCase extends CakeTestCase {
/**
 * @brief set up at the start
 */
	public function setUp() {
		parent::setUp();
		$this->Page = ClassRegistry::init('Contents.GlobalPage');
	}

/**
 * @brief break down at the end
 */
	public function tearDown() {
		parent::tearDown();
		unset($this->Page);
	}

	public function testSomething() {

	}

}