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
		'plugin.newsletter.template',
		'plugin.newsletter.newsletter',
		'plugin.newsletter.campaign',
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
