<?php
App::uses('View', 'View');
App::uses('Helper', 'View');
App::uses('GravatarHelper', 'Libs.View/Helper');

/**
 * GravatarHelper Test Case
 *
 */
class GravatarHelperTest extends CakeTestCase {

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$View = new View();
		$this->Gravatar = new GravatarHelper($View);
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Gravatar);

		parent::tearDown();
	}

/**
 * testImage method
 *
 * @return void
 */
	public function testImage() {
	}

/**
 * testDefaultImages method
 *
 * @return void
 */
	public function testDefaultImages() {
	}

}
