<?php
App::uses('Campaign', 'Newsletter.Model');

/**
 * Campaign Test Case
 *
 */
class CampaignTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.newsletter.newsletter',
		'plugin.newsletter.campaign',
		'plugin.newsletter.template',
		'plugin.newsletter.newsletters_user',
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
		$this->Campaign = ClassRegistry::init('Newsletter.Campaign');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Campaign);

		parent::tearDown();
	}

	public function testSomething() {

	}

}
