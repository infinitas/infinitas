<?php

App::uses('NewsletterCampaign', 'Newsletter.Model');

/**
 * Campaign Test Case
 *
 */
class NewsletterCampaignTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.newsletter.newsletter',
		'plugin.newsletter.newsletter_campaign',
		'plugin.newsletter.newsletter_template',
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
		$this->Model = ClassRegistry::init('Newsletter.NewsletterCampaign');
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
 * test find paginated
 */
	public function testFindPaginated() {
		$expected = array(
			array(
				'NewsletterCampaign' => array(
					'id' => 'campaign-1',
					'name' => 'campaign-1',
					'description' => 'campaign-1',
					'newsletter_count' => '0',
					'active' => true,
					'created' => '2009-12-12 12:47:53',
					'modified' => '2009-12-21 16:28:38',
				),
				'NewsletterTemplate' => array(
					'id' => 'template-1',
					'name' => 'first template',
				),
			),
			array(
				'NewsletterCampaign' => array(
					'id' => 'campaign-inactive',
					'name' => 'campaign-inactive',
					'description' => 'campaign-inactive',
					'newsletter_count' => '0',
					'active' => false,
					'created' => '2009-12-12 12:47:53',
					'modified' => '2009-12-21 16:28:38',
				),
				'NewsletterTemplate' => array(
					'id' => 'template-1',
					'name' => 'first template',
				)
			)
		);
		$result = $this->Model->find('paginated');
		$this->assertEquals($expected, $result);
	}

/**
 * test find active
 */
	public function testFindActive() {
		$expected = array(
			array(
				'NewsletterCampaign' => array(
					'id' => 'campaign-1',
					'name' => 'campaign-1',
					'description' => 'campaign-1',
					'newsletter_count' => '0',
					'active' => true,
					'created' => '2009-12-12 12:47:53',
					'modified' => '2009-12-21 16:28:38',
				),
				'NewsletterTemplate' => array(
					'id' => 'template-1',
					'name' => 'first template',
				),
			)
		);
		$result = $this->Model->find('active');
		$this->assertEquals($expected, $result);
	}
}