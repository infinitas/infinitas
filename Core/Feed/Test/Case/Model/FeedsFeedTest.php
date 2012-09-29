<?php
App::uses('FeedsFeed', 'Feed.Model');

/**
 * FeedsFeed Test Case
 *
 */
class FeedsFeedTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.feed.feeds_feed'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->FeedsFeed = ClassRegistry::init('Feed.FeedsFeed');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->FeedsFeed);

		parent::tearDown();
	}

	public function testSomething() {

	}

}
