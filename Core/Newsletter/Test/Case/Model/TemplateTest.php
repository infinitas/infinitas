<?php
App::uses('Template', 'Newsletter.Model');

/**
 * Template Test Case
 *
 */
class TemplateTest extends CakeTestCase {

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
		$this->Template = ClassRegistry::init('Newsletter.Template');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Template);

		parent::tearDown();
	}

/**
 * testGetTemplate method
 *
 * @return void
 */
	public function testGetTemplate() {
	}

}
