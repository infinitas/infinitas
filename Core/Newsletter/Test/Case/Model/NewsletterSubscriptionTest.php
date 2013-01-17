<?php
App::uses('NewsletterSubscription', 'Newsletter.Model');

/**
 * NewsletterSubscription Test Case
 *
 */
class NewsletterSubscriptionTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.newsletter.newsletter_subscription',
		'plugin.newsletter.newsletter_subscriber',
		'plugin.newsletter.newsletter_campaign',
		'plugin.newsletter.newsletter_template',
		'plugin.newsletter.newsletter',
		'plugin.view_counter.view_counter_view',
		'plugin.users.user',
		'plugin.users.group',
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Model = ClassRegistry::init('Newsletter.NewsletterSubscription');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Model);

		parent::tearDown();
	}

/**
 * testSubscribe method
 *
 * @return void
 */
	public function testSomething() {
	}

}
