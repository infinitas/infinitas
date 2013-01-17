<?php
App::uses('NewsletterSubscriber', 'Newsletter.Model');

/**
 * NewsletterSubscriber Test Case
 *
 */
class NewsletterSubscriberTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.newsletter.newsletter_subscriber',
		'plugin.newsletter.newsletter_subscription',
		'plugin.newsletter.newsletter_campaign',
		'plugin.newsletter.newsletter_template',
		'plugin.newsletter.newsletter',

		'plugin.management.ticket',
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
		$this->Model = ClassRegistry::init('Newsletter.NewsletterSubscriber');
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
 * testIsSubscriber method
 *
 * @dataProvider isSubscriberDataProvider
 */
	public function testIsSubscriber($data, $expected) {
		$result = $this->Model->isSubscriber($data);
		$this->assertEquals($expected, $result);

		$result = $this->Model->isSubscriber(array('email' => $data));
		$this->assertEquals($expected, $result);

		$result = $this->Model->isSubscriber(array('NewsletterSubscriber' => array('email' => $data)));
		$this->assertEquals($expected, $result);
	}

/**
 * is subscriber data provieder
 *
 * @return array
 */
	public function isSubscriberDataProvider() {
		return array(
			array('non-user@subscriber.com', true),
			array('inactive@subscriber.com', true),
			array('pending@subscriber.com', true),
			array('user@subscriber.com', true),
			array('fake@subscriber.com', false),
		);
	}

/**
 * test find paginate
 */
	public function testFindPaginate() {
		$expected = array(
			'subscriber-inactive',
			'subscriber-non-user',
			'subscriber-pending',
			'subscriber-user',
		);
		$result = Hash::extract($this->Model->find('paginated'), '{n}.NewsletterSubscriber.id');
		sort($expected);
		sort($result);
		$this->assertEquals($expected, $result);
	}

/**
 * testSubscribe method
 *
 * @return void
 */
	public function testSubscribe() {
		$this->assertFalse($this->Model->isSubscriber('bob@bob.com'));
		$oldCount = $this->Model->find('count');

		$data = array(
			'email' => 'bob@bob.com',
			'prefered_name' => 'bob'
		);
		$this->assertTrue((bool)$this->Model->subscribe($data));
		$this->assertCount($oldCount + 1, $this->Model->find('list'));
		$this->assertTrue($this->Model->isSubscriber('bob@bob.com'));

		$data = array(
			'NewsletterSubscriber' => array(
				'email' => 'sam@sam.com',
				'prefered_name' => 'sam'
			)
		);
		$this->assertTrue((bool)$this->Model->subscribe($data));
		$this->assertEquals('sam@sam.com', $this->Model->field('email'));
		$this->assertFalse($this->Model->field('active'));
		$this->assertCount($oldCount + 2, $this->Model->find('list'));
	}

}
