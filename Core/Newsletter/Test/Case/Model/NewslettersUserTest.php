<?php
App::uses('NewslettersUser', 'Newsletter.Model');

/**
 * NewslettersUser Test Case
 *
 */
class NewslettersUserTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.newsletter.newsletters_user'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->NewslettersUser = ClassRegistry::init('Newsletter.NewslettersUser');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->NewslettersUser);

		parent::tearDown();
	}

}
