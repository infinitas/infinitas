<?php
App::uses('NewsletterTemplate', 'Newsletter.Model');

/**
 * Template Test Case
 *
 */
class NewsletterTemplateTest extends CakeTestCase {

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
		$this->Model = ClassRegistry::init('Newsletter.NewsletterTemplate');
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
 * testGetTemplate method
 *
 * @expectedException CakeException
 */
	public function testGetTemplateException() {
		$this->Model->getTemplate();
	}

/**
 * test get template config with config
 */
	public function testGetTemplateConfig() {
		Configure::write('Newsletter.template', 'first template');
		$expected = array(
			'NewsletterTemplate' => array(
				'id' => 'template-1',
				'name' => 'first template',
				'header' => '<p style=\\"color: red;\\">this is the head</p>',
				'footer' => '<p>this is the foot</p>',
			)
		);
		$result = $this->Model->getTemplate();
		$this->assertEquals($expected, $result);

		$expected = array(
			'NewsletterTemplate' => array(
				'id' => 'template-2',
				'name' => 'User - Registration',
				'header' => '<p>\\r\\n	Thank-you for joining us, your account is active and you may login at www.site.com</p>',
				'footer' => '<p>\\r\\n	&nbsp;</p>\\r\\n<div firebugversion=\\"1.5.4\\" id=\\"_firebugConsole\\" style=\\"display: none;\\">\\r\\n	&nbsp;</div>',
			)
		);
		$result = $this->Model->getTemplate('User - Registration');
		$this->assertEquals($expected, $result);

		$result = $this->Model->getTemplate('template-2');
		$this->assertEquals($expected, $result);
	}

/**
 * test get template without config
 */
	public function testGetTemplate() {
		Configure::delete('Newsletter.template');
		$expected = array(
			'NewsletterTemplate' => array(
				'id' => 'template-1',
				'name' => 'first template',
				'header' => '<p style=\\"color: red;\\">this is the head</p>',
				'footer' => '<p>this is the foot</p>',
			)
		);
		$result = $this->Model->getTemplate('template-1');
		$this->assertEquals($expected, $result);

		$result = $this->Model->getTemplate('first template');
		$this->assertEquals($expected, $result);

		$expected = array(
			'NewsletterTemplate' => array(
				'id' => 'template-2',
				'name' => 'User - Registration',
				'header' => '<p>\\r\\n	Thank-you for joining us, your account is active and you may login at www.site.com</p>',
				'footer' => '<p>\\r\\n	&nbsp;</p>\\r\\n<div firebugversion=\\"1.5.4\\" id=\\"_firebugConsole\\" style=\\"display: none;\\">\\r\\n	&nbsp;</div>',
			)
		);
		$result = $this->Model->getTemplate('User - Registration');
		$this->assertEquals($expected, $result);

		$result = $this->Model->getTemplate('template-2');
		$this->assertEquals($expected, $result);
	}
}