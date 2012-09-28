<?php
App::uses('View', 'View');
App::uses('Helper', 'View');
App::uses('EmailAttachmentsHelper', 'Emails.View/Helper');

/**
 * EmailAttachmentsHelper Test Case
 *
 */
class EmailAttachmentsHelperTest extends CakeTestCase {

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$View = new View();
		$this->EmailAttachments = new EmailAttachmentsHelper($View);
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->EmailAttachments);

		parent::tearDown();
	}

/**
 * testOutputBody method
 *
 * @return void
 */
	public function testOutputBody() {
	}

/**
 * testHasAttachment method
 *
 * @return void
 */
	public function testHasAttachment() {
	}

/**
 * testIsFlagged method
 *
 * @return void
 */
	public function testIsFlagged() {
	}

/**
 * testListAttachments method
 *
 * @return void
 */
	public function testListAttachments() {
	}

}
