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
		'plugin.newsletter.campaign',
		'plugin.newsletter.template',
		'plugin.newsletter.newsletter',
		'plugin.newsletter.view_counter_view',
		'plugin.newsletter.user',
		'plugin.newsletter.group',
		'plugin.newsletter.newsletters_user'
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

}
