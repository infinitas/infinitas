<?php
App::uses('View', 'View');
App::uses('Helper', 'View');
App::uses('LetterHelper', 'Newsletter.View/Helper');

/**
 * LetterHelper Test Case
 *
 */
class LetterHelperTest extends CakeTestCase {

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$View = new View();
		$this->Letter = new LetterHelper($View);
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Letter);

		parent::tearDown();
	}

/**
 * testToggle method
 *
 * @return void
 */
	public function testToggle() {
	}

/**
 * testPreview method
 *
 * @return void
 */
	public function testPreview() {
	}

}
